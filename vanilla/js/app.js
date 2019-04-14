/**
 * James Boullion's Game Board!
 * 
 * Version 0.0.1
 */
window.onload = function(){

	//Get our document elements. Label with $ for reference
	var $gamearea = document.getElementById("party-gamearea"),
		$map = document.getElementById("gamearea-map"),
		$tileColor = document.getElementById("tile-color"),
		$textureHolder = document.getElementById("texture-holder"), 
		$currentTile = document.getElementById("current-tile"),
		$currentColor = document.getElementById("current-color");

	//Tools
	var $paintTool = document.getElementById("paint-tool"),
		$fillTool = document.getElementById("fill-tool"),
		$eraseTool = document.getElementById("erase-tool"),
		$highlightTool = document.getElementById("highlight-tool"),
		$sampleTool = document.getElementById("sample-tool"),
		$handTool = document.getElementById("hand-tool"),
		$saveTool = document.getElementById("save-tool"),
		$openTool = document.getElementById("open-tool"),
		$newBoardTool = document.getElementById("new-tool"),
		$copyTool = document.getElementById("copy-tool"),
		$importTool = document.getElementById("import-tool"),
		$toolSelect = document.getElementById("tool-select"),
		$tools = document.getElementsByClassName("tool"),
		$activeTool = null;
	
	//Forms
	var $saveForm = document.getElementById("save-form");

	//PURE JS elements
	var tileTpl = document.createElement("div"),
		npcTpl = document.createElement("div"),
		textureTpl = document.createElement("div")
		tileHolder =  document.createDocumentFragment(), //'';
		tileColorVal = $tileColor.value;


	//Our standard tile
	var defaultTile = {
		id: 0,
		x: 0,
		y: 0,
		passable: true,
		empty: true,
		name: '',
		type: 'color', //color / image
		background: '', //hex code or background image
		tileSize: 32,
		element: null,
		classes: "tile ",
		textureclass: "tile sprite " //TODO: should probably change this to just sprite and add the this.classes
	};

	var tile = defaultTile;

	var textures = ['dirt01','dirt02',
					'grass01','grass02',
					'stone01','stone02','stone03','stone04',
					'wall01','wall02','wall03',
					'rock01','rock02'];

	var defaultColor = '#526F35';

	//This is the example / current tile that will do the painting
	var defaultPaintTile = {
		type: 'color', //color / image
		background: defaultColor, //hex code or background image
		texture: ''
	}

	var paintTile = defaultPaintTile;

	//Our NPCs are basically a type of tile so we need all the same values to start with
	var defaultNpc = {
		id: 0,
		lvl: 1,
		alignment: 1, //Do a [3][3] array for alignments? (Good Neutral Evil) x (Lawful Neutral Chaotic)
		faction: 1,
		x: 0,
		y: 0,
		hp: 10,
		movement: 10,
		passable: false,
		classes: "npc speed-5",
		name: 'NPC',
		image: 'https://via.placeholder.com/28x28/FF0000/FFFFFF/?text=NPC 1', //NPC must always be an image
	}; //_.clone(tile);

	var dmTiles = {
		id: 0,
		passable: true,
		type: 'color', //color / image
		background: defaultColor, //hex code or background image
		textures: []
	};

	//Our GAME Object will be the store of the GAME's state.
	//If something exists in our game it should be in our GAME object
	var GAME = {};

	GAME.NPCs = [];

	//Our main board object that knows it's own state
	GAME.board = {
		id: 0,
		name: 'New Board',
		height: 30,
		width: 30,
		tiles: [],
		dragging: false,
		painting: false,
		mouseIsOver: false,
		boardPosX: 0,
		boardPosY: 0,
		maxPosX: (this.width * tile.tileSize),
		maxPosY: (this.height * tile.tileSize)
	};

	var defaultBoard = _.clone(GAME.board);
	
	//Used for AStar Searches
	GAME.boardGraphArray = [];

	var currentPath = [];
	var aStarStart = null;
	var aStarEnd = null;

	//Createing a throttled function for painting. Improves FPS steadyness. Set to try and paint at 60fps / 17ms
	var throttlePaint = _.throttle(doPaintTile, 17);
	var debounceFill = _.debounce(fillTiles, 250);
	var throttleErase = _.throttle(doTileErase, 17);

	var highlightTiles = [];
	var throttleHighlight = _.throttle(doTileHighlight, 17);
	var debounceHighlight = _.debounce(cancelHighlight, 5000);

	var throttleAstar = _.throttle(doAstar, 100);
	var throttleBuildGraph = _.throttle(buildGraphArray, 1000);

	//Setup some basic attributes for our tiles
	tileTpl.setAttribute("class", defaultTile.classes);
	textureTpl.setAttribute("class", "dm-tile sprite");
	npcTpl.setAttribute("class", defaultNpc.classes);
	npcTpl.setAttribute("draggable", "true");
	

	//If someone is attempting to 
	document.body.oncontextmenu = function(e){
		//gameAreaContext(); this could disable the context menu on the whole site. Probably not wanted
		setActiveTool($handTool);
	}


	//What to do when the user attempts to right click on the game area
	function gameAreaContext(){
		//setActiveTool($handTool);
	}


	//Initialize our game
	gameInit();

	/*
	if(isMobileDevice()){
		$map.ontouchstart = function(e){
	}else{
		$map.onmousedown = function(e){
	}
	*/
	$map.onmousedown = function(e){
		
		//"painting" means any of our single tile tools that have a drag / draw effect
		switch($activeTool){
			case $paintTool:
				GAME.board.painting = true;
				break;
			case $fillTool:
				GAME.board.painting = false;
				break;
			case $eraseTool:
				GAME.board.painting = true;
				break;
			case $highlightTool:
				GAME.board.painting = true;
				break;
			case $sampleTool:
				GAME.board.painting = false;
				break;
			case $handTool:
				GAME.board.painting = false;
				break;
		}

	};

	$map.onmouseup = function(e){
		GAME.board.painting = false;
	};

	$map.onmouseleave = function(e){
		GAME.board.painting = false;
	};


	/**
	 * Setup Tools
	 */
	//ES6
	var unActiveClass = 'tool';
	var activeClass = 'tool active';
	for(let i = 0; i < $tools.length; i++) {
		$tools[i].addEventListener("click", function() {

			//Set ourselves as the active tool
			setActiveTool(this);

		})
	}

	//Setup a new active tool
	function setActiveTool($newTool){
		//If we are changing our tool let's not be actively painting
		GAME.board.painting = false;

		//File Tools
		switch($newTool){
			case $saveTool:
				saveBoard();
			break;
			case $openTool:

			break;
			case $newBoardTool:
			/*
				//For this to work we need to clear the elements. This will be done automajically in Vue
					console.log('New Board');
					var confirmBool = confirm('Are you sure?');
					GAME.board = defaultBoard;
					if(confirmBool){
						buildBoard();
					}
			*/
				
			break;
			case $copyTool:

			break;
			case $importTool:

			break;
			default:
				$unActive = $toolSelect.querySelector('.active');
				if($unActive){
					$unActive.classList.remove("active");
				}
				
				$activeTool = $newTool;

				$activeTool.classList.add("active");//.setAttribute("class", activeClass);

				$gamearea.setAttribute("class", $activeTool.getAttribute('id'));
		}

	}
	
	/**
	 * Initialize our game
	 */
	function gameInit(){

		//Create our board
		buildBoard();
		//loadBoard(27);

		//Layout our tools
		setupDmTools();

		//setup our currnet tile
		doPaintTile($currentTile);


	}

	//Setup our DM tool area
	function setupDmTools(){
		//Setup our painting textures
		for(var t = 0; t < textures.length; t++){
			//Clone our default HTML element
			dmTiles.textures[t] = textureTpl.cloneNode(false);

			//Add our texture class to this element
			dmTiles.textures[t].classList.add(textures[t]);

			//On Clikc set our texture as the current paint tool
			dmTiles.textures[t].onclick = (function(t) {return function() {
				setPaintTile('texture', t);
			};})(t);

			//console.log(dmTiles.textures[t]);
			$textureHolder.appendChild(dmTiles.textures[t]);
		}
	}

	/**
	 * Create all our tiles and stick them to the board
	 * 
	 * NOTE: This might be DM only...unless everyone can paint?
	 */
	function buildBoard(){

		var boardSize = (GAME.board.width * defaultTile.tileSize) + 200; //padding

		//$gamearea.style.maxWidth = (boardSize + 20) +'px'; //extra space for scroll bar
		$map.style.height = boardSize +'px';
		$map.style.width = boardSize +'px';
		
		//Build our board! //0 or 1 based?
		for(var w = 0; w < GAME.board.width; w++){
			GAME.board.tiles[w] = [];
			GAME.NPCs[w] = [];
			for(var h = 0; h < GAME.board.height; h++){	

				//Put our tile into our state board
				GAME.board.tiles[w][h] = createTile(w,h);
				
				//Creating a bunch of random GAME.NPCs
				if( (w + h) % 10 == 0){
					GAME.NPCs[w][h] = createNPC(w,h);
				}

				//tileHolder.appendChild(board.tiles[w][h].element);
			}
		}

		displayBoard(GAME.board);
		displayNPCs(GAME.NPCs);

		//we need to build our graph array AFTER npcs have been added so we know if the tiles are passable
		buildGraphArray();
	}

	//Create a tile a a specific location
	function createTile(x,y){
		var tmpTile = _.clone(tile);

			tmpTile.id++;
			tmpTile.x = x;
			tmpTile.y = y;

			//Clone our default HTML element
			tmpTile.element = tileTpl.cloneNode(true);
			tmpTile.element.x = x;
			tmpTile.element.y = y;

			//Give it a unique ID
			tmpTile.element.setAttribute("id", 'tile-'+x+'-'+y);

			tmpTile.element.type = tmpTile.type;
			tmpTile.element.background = tmpTile.background;
			//tile.element.setAttribute('data-background', tile.background);

			tmpTile.element.ondrop = function(e){
				e.preventDefault();

				//Move whatever was dropped here
				var data = e.dataTransfer.getData("text");
				e.target.appendChild(aStarStart);
				
				//Since our aStarStart is a reference to an actual NPC we can update it's XY so we know where to start our next Astar path for this NPC
				aStarStart.x = this.x;
				aStarStart.y = this.y;

				//After we drop something we need to update our graph for aStar searches
				buildGraphArray();
				clearAstarPath();
			};

			tmpTile.element.ondragover = function(e){
				if (this.firstChild) {
					// tile has a child element
					// NOT empty
				}else{
					//This ALLOWs dropping here
					//We only drop here when this tile has no children. No two things can occupy the same space
					e.preventDefault();

					throttleAstar(this);

				}
			};

			//Setup a click listener on this tile
			//tile.element.addEventListener('click', colorTile);
			/*
			if(isMobileDevice()){
				//&& (e.which === 1 || ev.touches)
				tmpTile.element.ontouchstart  = function(e){
					tileMouseMove(e, this);
				};

				tmpTile.element.ontouchmove  = function(e){
					tileMouseMove(e, this);
				};
			}else{
				tmpTile.element.onmousedown = function(e){
					tileMouseDown(e, this);
				};
				tmpTile.element.onmousemove = function(e){
					tileMouseMove(e, this);
				};
			}
			*/

			tmpTile.element.onmousedown = function(e){
				tileMouseDown(e, this);
			};
			tmpTile.element.onmousemove = function(e){
				tileMouseMove(e, this);
			};

			/*
			tmpTile.element.touchstart = function(e){
				GAME.board.painting = true;
			};
			tmpTile.element.ontouchmove = function(e){
				tileMouseMove(e, this);
			};
			tmpTile.element.touchend = function(e){
				GAME.board.painting = false;
			};
			*/
			

		return tmpTile;
	}

	//Take action when moving your mouse accross the tile
	function tileMouseMove(e, $element){
		//&& (e.which === 1 || ev.touches)
		if( GAME.board.painting ){

			switch($activeTool){
				case $paintTool:
					throttlePaint($element);
					break;
				case $fillTool:
					
					break;
				case $eraseTool:
					throttleErase($element);
					break;
				case $highlightTool:
					throttleHighlight($element);
					break;
				case $sampleTool:
					
					break;
			}
		}
	}

	//Take action when our onmousedown and on touchstart events
	function tileMouseDown(e, $element){
		//What tool are we tring to use?
		//&& (e.which === 1 || ev.touches)
		if( $activeTool !== $handTool ){

			switch($activeTool){
				case $paintTool:
					throttlePaint($element);
					break;
				case $fillTool:
					debounceFill($element);
					break;
				case $eraseTool:
					throttleErase($element);
					break;
				case $highlightTool:
					throttleHighlight($element);
					break;
				case $sampleTool:
					sampleTile($element);
					break;

			}
		}
	}

	//Create an NPC that can stick on our board
	function createNPC(x, y, name){

		var tmpNPC = _.clone(defaultNpc);

			tmpNPC.id++;

			if(! name){
				name = tmpNPC.name+' ('+x+','+y+')';
			}

			tmpNPC.x = x;
			tmpNPC.y = y;

			//Clone our default HTML element
			tmpNPC.element = npcTpl.cloneNode(true);

			tmpNPC.element.x = x;
			tmpNPC.element.y = y;
			tmpNPC.element.movement = tmpNPC.movement;
			//TODO need
			tmpNPC.element.title = name;

			//Give it a unique ID
			tmpNPC.element.setAttribute("id", 'npc-'+x+'-'+y);

			tmpNPC.element.ondragstart = function(e){
				//save the id of our NPC for talking to the dropped tile. This NPC will then be moved to that tile if it is available
				e.dataTransfer.setData("text", e.target.id);
				this.classList.add("movement");

				//This is the new start point for aStar
				aStarStart = this;

				//Set our active tool to the hand tool
				setActiveTool($handTool);
			};

			tmpNPC.element.ondragend = function(e){
				this.classList.remove("movement");
			};

			//Setup a click listener on this tile
			//Might want to change this to onclick
			tmpNPC.element.onclick = function(e){
				//e.stopPropagation();
				//e.preventDefault();

				console.log('click NPC: '+this.x+', '+this.y);
			};

			//Setup a click listener on this tile
			//Might want to change this to onclick
			tmpNPC.element.onmousedown = function(e){
				e.stopPropagation();
				//e.preventDefault();

				//console.log('down NPC: '+this.id);
			};

			//Some tools need to function while moving over
			tmpNPC.element.onmousemove = function(e){
				e.stopPropagation();
				//e.preventDefault();

				//console.log('move NPC: '+this.id);
			};

		return tmpNPC;
	}

	//Build our AStar Graph for fast A* searching through our tiles
	function buildGraphArray(){
		//Our grid is read in the oppirite order we built it
		for(var h = 0; h < GAME.board.height; h++){
			GAME.boardGraphArray[h] = [];
			for(var w = 0; w < GAME.board.width; w++){	
				if (GAME.board.tiles[h][w].element.firstChild || ! GAME.board.tiles[h][w].passable) {
					//not passable
					GAME.boardGraphArray[h][w] = 0;
				}else{
					//passable
					GAME.boardGraphArray[h][w] = 1;
				}
			}
		}
	}

	//Use a parth of elements returned from the aStar function and draw a path on the tiles
	function drawPath(path){
		//If we already 
		if(path){
			for(var p = 0; p < path.length; p++){
				//console.log(board.tiles[path[p].x][path[p].y].element.classList);
				GAME.board.tiles[path[p].x][path[p].y].element.classList.add('path');
				currentPath.push(GAME.board.tiles[path[p].x][path[p].y].element);
			}
		}
	}

	/**
	 * Build a 0,1 two dimentional array to represent passable tiles. Run an Astar function on it to find the shortest route between two things
	 */
	function doAstar($element){
		
		//If we already have this as our aStarEnd then we already are showing it's path
		if(aStarEnd == $element) return;

		var path = [];
		aStarEnd = $element;

		//We are using a Graph class that is bundled with our astar.js This Graph class allows us to use a heap to search the map space.
		//https://briangrinstead.com/blog/astar-search-algorithm-in-javascript-updated/
		var boardGraph = new Graph(GAME.boardGraphArray);

		//Do we have both our points to check?
		if(aStarStart && aStarEnd){

			// Get the distance for these two elements
			var distance = Math.hypot(aStarEnd.x - aStarStart.x, aStarEnd.y - aStarStart.y);
			
			//This is not a perfect check but should catch most long
			if(aStarStart.movement < distance ){
				//Too far away dont try to move there
				clearAstarPath();
				return [];
			}

			var start = boardGraph.grid[aStarStart.x][aStarStart.y];
			var end = boardGraph.grid[aStarEnd.x][aStarEnd.y];

			//OUR ASTAR SEARCH! This is a super fast search even over long distances.
			//We are hopefully removing anything TOO distance by only running this when the end target is close enough to be moved to
			path = astar.search(boardGraph, start, end);
			
			//Don't return a longer path than the user has movement
			if(path.length > aStarStart.movement){
				path = path.slice(0, aStarStart.movement);
			}

		}

		clearAstarPath();
		drawPath(path);

		//return path;
	}

	//Clear our current Astar Highlighted path
	function clearAstarPath(){
		for(var p = 0; p < currentPath.length; p++){
			currentPath[p].classList.remove('path');
		}
		currentPath = [];
	}


	//Display a board object to the screen
	function displayBoard(newBoard){
		tileHolder = document.createDocumentFragment();

		for(var w = 0; w < newBoard.width; w++){
			for(var h = 0; h < newBoard.height; h++){
				//build our pure JS object before sticking it on the board
				tileHolder.appendChild(newBoard.tiles[w][h].element);
			}
		}

		//Set our tiles into our map
		//Put our tile on the screen
		$map.appendChild(tileHolder);
	}

	//Draw all of the NPCs in our NPCs array
	function displayNPCs(npcs){
		npcs.forEach(function(w) {
			w.forEach(function(h) {
				GAME.board.tiles[h.x][h.y].element.appendChild(h.element);
			});
		});

	}

	//Paint Bucket Fill all similar tiles
	function fillTiles($element){

		var findTiles = [];
		for(var w = 0; w < GAME.board.width; w++){
			for(var h = 0; h < GAME.board.height; h++){
				if($element.background === GAME.board.tiles[w][h].element.background){
					//If we set our background color here we only fill UP TO the location we clicked.
					findTiles.push(GAME.board.tiles[w][h].element);
				}
			}
		}

		//Fille all the tiles we found
		for(var l = 0; l < findTiles.length; l++){
			doPaintTile(findTiles[l]);
		}
	}

	/**
	 * Change the paintTile
	 */
	function setPaintTile(type, value){
		paintTile.type = type;

		if(type === 'color'){
			paintTile.background = value;
			paintTile.texture = '';
		}else if(type === 'texture'){
			paintTile.background = value;
			paintTile.texture = textures[value];
		}else if(type === 'image'){
			paintTile.background = value;
			paintTile.texture = '';
		}

		doPaintTile($currentTile);
	}

	//Get a sample of the tile we click and setup our current tile to that tile
	function sampleTile($element){
		setPaintTile($element.type, $element.background);
	}


	//Add the highlight class to our tiles
	function doTileHighlight($element){
		$element.classList.add("highlight");
		highlightTiles.push($element);

		//this function will run after all of our highlights have been set
		debounceHighlight();
	}

	//Eventually remove the highlight class from highlighted tiles
	function cancelHighlight(){
		for(var l = 0; l < highlightTiles.length; l++){
			highlightTiles[l].classList.remove("highlight");
		}

		highlightTiles = [];
	}


	//Reset our tile back to the standard
	function doTileErase($element){
		$element.type = defaultTile.type;
		$element.background = defaultTile.background;
		$element.style.backgroundColor = defaultTile.background;
		$element.style.backgroundImage = '';
		$element.setAttribute("class", defaultTile.classes);
	}

	//Set the new tool color
	$tileColor.addEventListener('input', function(e){
		setPaintTile('color', this.value);

		$currentColor.value = this.value;
	}, false);

	/**
	* Paint our map! Update the background of a tile
	*/
	function doPaintTile($element){
		$element.type = paintTile.type;

		if(paintTile.type === 'color'){
			$element.style.backgroundColor = paintTile.background;
			$element.style.backgroundImage = '';
			$element.setAttribute("class", defaultTile.classes);
		}else if(paintTile.type === 'texture'){
			$element.style.backgroundColor = defaultColor;
			$element.style.backgroundImage = '';
			$element.setAttribute("class", defaultTile.textureclass+paintTile.texture);
		}else if(paintTile.type === 'image'){
			$element.style.backgroundColor = '';
			$element.style.backgroundImage = 'url('+paintTile.background+')';
		}

		$element.background = paintTile.background;

	}


	//Handle our board submission
	$saveForm.onsubmit = function(e){
		e.preventDefault();

		var $boardName = document.getElementById("board-name");
			$autoSave = document.getElementById("auto-save");

		if($boardName.value && $boardName.value.length < 100){
			GAME.board.name = $boardName.value;
		}else if($boardName.value.length > 100){
			GAME.board.name = $boardName.value.slice(0, 100);
		}

		saveBoard($autoSave.checked);

		return false;
	};
/*
	//Save our board for later retrival
	function saveBoard(autosave){

		//console.log(GAME.board);
		$.post( WPURLS.templateUrl+"/external/save-board.php", {autosave, board: JSON.stringify(GAME.board) }, function( data ) {
			//console.log(data);
			if(data.success){
				//success
				GAME.board.id = data.success;
			}else{
				//error
			}
		},'json');

	}

	//Save our board for later retrival
	function loadBoard(board_id){

		$.post( WPURLS.templateUrl+"/external/get-board.php", {board_id}, function( data ) {
			//console.log(data);
			if(! data.error){
				//success
				GAME.board = JSON.parse(data);
				//console.log(data.success);
				buildBoard();
			}else{
				//error
			}
		},'json');
	}
	*/
/*
	//Handle our board submission
	$saveForm.onsubmit = function(e){
		e.preventDefault();

		var $boardName = document.getElementById("board-name");
			$autoSave = document.getElementById("auto-save");

		if($boardName.value && $boardName.value.length < 100){
			GAME.board.name = $boardName.value;
		}else if($boardName.value.length > 100){
			GAME.board.name = $boardName.value.slice(0, 100);
		}

		saveBoard($autoSave.checked);

		return false;
	};

	//Save our board for later retrival
	function saveBoard(autosave){

		//console.log(GAME.board);
		$.post( WPURLS.templateUrl+"/external/save-board.php", {board: GAME.board.tiles, autosave: autosave}, function( data ) {
			//console.log(data);
			if(data.success){
				//success
				GAME.board.id = data.success;
			}else{
				//error
			}
		},'json');

	}

	//Save our board for later retrival
	function loadBoard(board_id){
		
		$.get( WPURLS.templateUrl+"/external/get-board.php?board_id="+board_id, function( data ) {
			//console.log(data);
			if(data.success){
				//success
				//GAME.board = JSON.parse(data.success);
				//console.log(data.success);
				//buildBoard();
			}else{
				//error
			}
		},'json');
	}
	*/
};