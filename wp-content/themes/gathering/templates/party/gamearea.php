<div id="party-gamearea" class="">
	<div id="gamearea-map-wrapper">
		<div id="gamearea-map" oncontextmenu="return false;"></div>
	</div>
	
</div>

<script>
jQuery(document).ready(function($) {

	//Get our document elements. Label with $ for reference
	var $gameArea = document.getElementById("party-gamearea"),
		$map = document.getElementById("gamearea-map"),
		$tileColor = document.getElementById("tile-color"),
		$textureHolder = document.getElementById("texture-holder"), 
		$currentTile = document.getElementById("current-tile"),
		$currentColor = document.getElementById("current-color");

	var $paintTool = document.getElementById("paint-tool"),
		$fillTool = document.getElementById("fill-tool"),
		$eraseTool = document.getElementById("erase-tool"),
		$highlightTool = document.getElementById("highlight-tool"),
		$sampleTool = document.getElementById("sample-tool"),
		$toolSelect = document.getElementById("tool-select"),
		$tools = document.getElementsByClassName("tool"),
		$activeTool = null;

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

	//This is the example / current tile that will do the painting
	var defaultPaintTile = {
		type: 'color', //color / image
		background: '#FF0000', //hex code or background image
		texture: ''
	}

	var paintTile = defaultPaintTile;

	var NPCs = [];

	//Our NPCs are basically a type of tile so we need all the same values to start with
	var defaultNpc = {
		id: 0,
		lvl: 1,
		alignment: 1, //Do a [3][3] array for alignments? (Good Neutral Evil) x (Lawful Neutral Chaotic)
		faction: 1,
		x: 0,
		y: 0,
		hp: 10,
		movement: 5,
		passable: false,
		classes: "npc speed-5",
		name: 'npc 1',
		image: 'https://via.placeholder.com/28x28/FF0000/FFFFFF/?text=NPC 1', //NPC must always be an image
	}; //_.clone(tile);

	var dmTiles = {
		id: 0,
		passable: true,
		type: 'color', //color / image
		background: '#FF0000', //hex code or background image
		textures: []
	};

	//Our main board object that knows it's own state
	var board = {
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

	//Used for AStar Searches
	var boardGraphArray = [];
	var currentPath = [];
	var AstarStart = null;
	var AstarEnd = null;

	//Createing a throttled function for painting. Improves FPS steadyness. Set to try and paint at 60fps / 17ms
	var throttlePaint = _.throttle(doPaintTile, 17);
	var debounceFill = _.debounce(fillTiles, 250);
	var throttleErase = _.throttle(doTileErase, 17);

	var highlightTiles = [];
	var throttleHighlight = _.throttle(doTileHighlight, 17);
	var debounceHighlight = _.debounce(cancelHighlight, 5000);

	var throttleAstar = _.throttle(doAstar, 1000);
	var throttleBuildGraph = _.throttle(buildGraphArray, 2000);

	//Setup some basic attributes for our tiles
	tileTpl.setAttribute("class", defaultTile.classes);
	
	textureTpl.setAttribute("class", "dm-tile sprite");

	npcTpl.setAttribute("class", defaultNpc.classes);
	npcTpl.setAttribute("draggable", "true");
	

	/*
	document.body.oncontextmenu = function(){
		console.log('concontextmenu')
		document.body.setAttribute("style",'cursor:default;');
	}
	*/

	//Initialize our game
	gameInit();

	$map.onmousedown = function(e){
		
		//"painting" means any of our single tile tools that have a drag / draw effect
		board.painting = true;

		switch($activeTool){
			case $paintTool:
				break;
			case $fillTool:
				board.painting = false;
				break;
			case $eraseTool:
				break;
			case $highlightTool:
				break;
			case $sampleTool:
				board.painting = false;
				break;
		}

	};

	$map.onmouseup = function(e){
		board.painting = false;
	};

	$map.onmouseleave = function(e){
		board.painting = false;
	};


	/**
	 * Setup Tools
	 */
	//ES6
	var unActiveClass = 'tool';
	var activeClass = 'tool active';
	for(let i = 0; i < $tools.length; i++) {
		$tools[i].addEventListener("click", function() {

			$unActive = $toolSelect.querySelector('.active');
			if($unActive){
				$unActive.classList.remove("active");
			}

			$activeTool = this;
			this.classList.add("active");//.setAttribute("class", activeClass);

			$gameArea.setAttribute("class", this.getAttribute('id'));
		})
	}
	
	/**
	 * Initialize our game
	 */
	function gameInit(){

		//Create our board
		buildBoard();

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
		//Build our board! //0 or 1 based?
		for(var w = 0; w < board.width; w++){
			board.tiles[w] = [];
			NPCs[w] = [];
			for(var h = 0; h < board.height; h++){	

				var tmpTile = _.clone(tile);

				tmpTile.id++;
				tmpTile.x = w;
				tmpTile.y = h;

				//Clone our default HTML element
				tmpTile.element = tileTpl.cloneNode(true);
				tmpTile.element.x = w;
				tmpTile.element.y = h;
				
				//Give it a unique ID
				tmpTile.element.setAttribute("id", 'tile-'+w+'-'+h);

				tmpTile.element.type = tmpTile.type;
				tmpTile.element.background = tmpTile.background;
				//tile.element.setAttribute('data-background', tile.background);

				tmpTile.element.ondrop = function(e){
					e.preventDefault();

					//Move whatever was dropped here
					var data = e.dataTransfer.getData("text");
					e.target.appendChild(AstarStart);

					//Since our AstarStart is a reference to an actual NPC we can update it's XY so we know where to start our next Astar path for this NPC
					AstarStart.x = this.x;
					AstarStart.y = this.y;
				};

				tmpTile.element.ondragover = function(e){
					if (this.firstChild) {
						// tile has a child element
						// NOT empty
					}else{
						//This ALLOWs dropping here
						//We only drop here when this tile has no children. No two things can occupy the same space
						e.preventDefault();

						//Move whatever was dropped here
						//var data = e.dataTransfer.getData("text");
						//console.log('ondragover');
						
						var path = throttleAstar(this);

						//console.log(board.tiles);

						if(path){
							for(var p = 0; p < path.length; p++){
								//console.log(board.tiles[path[p].x][path[p].y].element.classList);
								board.tiles[path[p].x][path[p].y].element.classList.add('path')
								currentPath.push(board.tiles[path[p].x][path[p].y].element);
							}
						}
						
						//e.target.appendChild(document.getElementById(data));
					}
				};

				//Setup a click listener on this tile
				//tile.element.addEventListener('click', colorTile);
				tmpTile.element.onmousedown = function(e){
					
					switch($activeTool){
						case $paintTool:
							throttlePaint(this);
							break;
						case $fillTool:
							debounceFill(this);
							break;
						case $eraseTool:
							throttleErase(this);
							break;
						case $highlightTool:
							throttleHighlight(this);
							break;
						case $sampleTool:
							sampleTile(this);
							break;

					}
				};

				//Some tools need to function while moving over
				tmpTile.element.onmousemove = function(e){
					if(board.painting){
						switch($activeTool){
							case $paintTool:
								throttlePaint(this);
								break;
							case $fillTool:
								
								break;
							case $eraseTool:
								throttleErase(this);
								break;
							case $highlightTool:
								throttleHighlight(this);
								break;
							case $sampleTool:
								
								break;
						}
					}
				};

				//Put our tile into our state board
				board.tiles[w][h] = tmpTile;
				
				//Displaying a bunch of random NPCs
				if( (w + h) % 10 == 0){
					var tmpNPC = _.clone(defaultNpc);
					tmpNPC.id++;
					tmpNPC.x = w;
					tmpNPC.y = h;

					//Clone our default HTML element
					tmpNPC.element = npcTpl.cloneNode(true);

					tmpNPC.element.x = w;
					tmpNPC.element.y = h;
					tmpNPC.element.movement = tmpNPC.movement;

					//Give it a unique ID
					tmpNPC.element.setAttribute("id", 'npc-'+w+'-'+h);

					tmpNPC.element.ondragstart = function(e){
						//save the id of our NPC for talking to the dropped tile. This NPC will then be moved to that tile if it is available
						e.dataTransfer.setData("text", e.target.id);
						this.classList.add("movement");

						AstarStart = this;
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
						//e.stopPropagation();
						//e.preventDefault();

						//console.log('down NPC: '+this.id);
					};

					//Some tools need to function while moving over
					tmpNPC.element.onmousemove = function(e){
						//e.stopPropagation();
						//e.preventDefault();

						//console.log('move NPC: '+this.id);
					};

					NPCs[w][h] = tmpNPC;
				}

				//tileHolder.appendChild(board.tiles[w][h].element);
			}
		}

		displayBoard(board);
		displayNPCs(NPCs);

	}

	//Build our AStar Graph for fast A* searching through our tiles
	function buildGraphArray(){
		//Our grid is read in the oppirite order we built it
		for(var h = 0; h < board.height; h++){
			boardGraphArray[h] = [];
			for(var w = 0; w < board.width; w++){	
				if (board.tiles[h][w].element.firstChild || ! board.tiles[h][w].passable) {
					//not passable
					boardGraphArray[h][w] = 0;
				}else{
					//passable
					boardGraphArray[h][w] = 1;
				}
			}
		}
	}

	/**
	 * Build a 0,1 two dimentional array to represent passable tiles. Run an Astar function on it to find the shortest route between two things
	 */
	function doAstar($element){
		
		var path = [];
		AstarEnd = $element;

		//This will search our board for passable tiles and turn it into a Grid optimized for path searches
		//throttleBuildGraph();
		buildGraphArray();

		var boardGraph = new Graph(boardGraphArray);
		
		//Do we have both our points to check?
		if(AstarStart && AstarEnd){

			// Get the distance for these two elements
			var distance = Math.hypot(AstarEnd.x - AstarStart.x, AstarEnd.y - AstarStart.y);
			
			//This is not a perfect check but should catch most long
			if(AstarStart.movement < distance ){
				//Too far away dont try to move there
				return path;
			}

			var start = boardGraph.grid[AstarStart.x][AstarStart.y];
			var end = boardGraph.grid[AstarEnd.x][AstarEnd.y];

			//OUR ASTAR SEARCH! This is a super fast search even over long distances.
			//We are hopefully removing anything TOO distance by only running this when the end target is close enough to be moved to
			path = astar.search(boardGraph, start, end);
			
			//Don't return a longer path than the user has movement
			if(path.length > AstarStart.movement){
				path = path.slice(0, AstarStart.movement);
			}

		}

		clearAstarPath();

		return path;
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

	function displayNPCs(npcs){
		npcs.forEach(function(w) {
			w.forEach(function(h) {
				board.tiles[h.x][h.y].element.appendChild(h.element);
			});
		});

	}

	//Paint Bucket Fill all similar tiles
	function fillTiles($element){

		var findTiles = [];
		for(var w = 0; w < board.width; w++){
			for(var h = 0; h < board.height; h++){
				if($element.background === board.tiles[w][h].element.background){
					//If we set our background color here we only fill UP TO the location we clicked.
					findTiles.push(board.tiles[w][h].element);
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
			$element.style.backgroundColor = '#FF0000';
			$element.style.backgroundImage = '';
			$element.setAttribute("class", defaultTile.textureclass+paintTile.texture);
		}else if(paintTile.type === 'image'){
			$element.style.backgroundColor = '';
			$element.style.backgroundImage = 'url('+paintTile.background+')';
		}

		$element.background = paintTile.background;

	}
});
</script>