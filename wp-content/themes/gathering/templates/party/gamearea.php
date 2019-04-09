<div id="party-gamearea">
	<div id="gamearea-map-wrapper">
		<div id="gamearea-map" oncontextmenu="return false;"></div>
	</div>
	
</div>

<script>
jQuery(document).ready(function($) {

	//Get our document elements. Label with $ for reference
	var $map = document.getElementById("gamearea-map"),
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
		$activeTool = $paintTool;


	//PURE JS elements
	var tileTpl = document.createElement("div"),
		textureTpl = document.createElement("div")
		tileHolder =  document.createDocumentFragment(), //'';
		tileColorVal = $tileColor.value;

	var tileDefaultCSS = "tile ",
		textureDefaultCSS = "tile sprite ";

	//Setup some basic attributes for our tiles
	tileTpl.setAttribute("class", tileDefaultCSS);
	textureTpl.setAttribute("class", "dm-tile sprite");

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
		element: null
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

	//Our NPCs are basically a type of tile so we need all the same values to start with
	var npc = {
		id: 0,
		x: 0,
		y: 0,
		hp: 10,
		passable: true,
		name: '',
		image: 'https://via.placeholder.com/32x32/FF0000/FFFFFF/?text=1', //NPC must always be an image
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

	//Createing a throttled function for painting. Improves FPS steadyness. Set to try and paint at 60fps / 17ms
	var throttlePaint = _.throttle(doPaintTile, 17);
	var debounceFill = _.debounce(fillTiles, 250);
	var throttleErase = _.throttle(doTileErase, 17);

	var highlightTiles = [];
	var throttleHighlight = _.throttle(doTileHighlight, 17);
	var debounceHighlight = _.debounce(cancelHighlight, 5000);
	

	//Initialize our game
	gameInit();

	//Set our tiles into our map
	$map.appendChild(tileHolder); //.innerHTML= tileHolder;

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

		/*
		$paintTool = document.getElementById("paint-tool"),
		$fillTool = document.getElementById("fill-tool"),
		$eraseTool = document.getElementById("erase-tool"),
		$highlightTool = document.getElementById("highlight-tool"),
		$sampleTool
		*/
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
			$unActive.classList.remove("active");

			$activeTool = this;
			this.classList.add("active");//.setAttribute("class", activeClass);

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
	 */
	function buildBoard(){
		//Build our board! //0 or 1 based?
		for(var w = 1; w <= board.width; w++){
			board.tiles[w] = [];
			for(var h = 1; h <= board.height; h++){

				var tmpTile = _.clone(tile);

				tmpTile.id = ++tmpTile.id;
				

				//Clone our default HTML element
				tmpTile.element = tileTpl.cloneNode(true);
				tmpTile.element.x = w;
				tmpTile.element.y = h;
				
				//Give it a unique ID
				tmpTile.element.setAttribute("id", 'tile-'+w+'-'+h);
				
				tmpTile.element.type = tmpTile.type;
				tmpTile.element.background = tmpTile.background;
				//tile.element.setAttribute('data-background', tile.background);

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
								board.painting = false;
								break;
						}
					}
				};

				//Put our tile into our state board
				board.tiles[w][h] = tmpTile;

				//Put our tile on the screen
				tileHolder.appendChild(board.tiles[w][h].element);
				
			}
		}

	}

	function fillTiles($element){

		var findTiles = [];
		for(var w = 1; w <= board.width; w++){
			for(var h = 1; h <= board.height; h++){
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
			$element.setAttribute("class", tileDefaultCSS);
		}else if(paintTile.type === 'texture'){
			$element.style.backgroundColor = '#FF0000';
			$element.style.backgroundImage = '';
			$element.setAttribute("class", textureDefaultCSS+paintTile.texture);
		}else if(paintTile.type === 'image'){
			$element.style.backgroundColor = '';
			$element.style.backgroundImage = 'url('+paintTile.background+')';
		}

		$element.background = paintTile.background;

	}
});
</script>