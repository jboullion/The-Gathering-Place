<div id="party-gamearea">
	<div id="gamearea-map-wrapper">
		<div id="gamearea-map" oncontextmenu="return false;">
			<!-- <h3>Generate this with JS. Store each cell in a 2d array as it's own object.</h3>
			<p>Should each square be 32x32 (or 64x64, not sure) pixels in size with a 1px border</p>
			<p>We will generate more map tiles than will fit in game area and then the user can scroll around if needed</p>
			<p>Maybe even a "zoom" in / out function?...that can come later</p> -->
		</div>
	</div>
	<div id="dm-tools" class="user-game-actions">
		<div id="tool-select" class="dm-tool">
			<h6>Tools</h6>
			<div id="tool-holder" class="dm-holder">
				<div id="paint-tool" class="tool active"><i class="fas fa-fw fa-paint-roller"></i></div>
				<div id="fill-tool" class="tool"><i class="fas fa-fw fa-fill-drip"></i></div>
				<div id="erase-tool" class="tool"><i class="fas fa-fw fa-eraser"></i></div>
				<div id="highlight-tool" class="tool"><i class="fas fa-fw fa-highlighter"></i></div>
				<div id="sample-tool" class="tool"><i class="fas fa-eye-dropper"></i></div>
				
			</div>
		</div>
		<div id="dm-tile-area" class="dm-tool">
			<h6>Tiles</h6>
			
			<div class="form-group">
				<label for="current-tile">Current Tile</label><br />
				<div id="current-tile" class="tile" style="background-color: #ff0000"></div>
			</div>

			<div class="form-group">
				<label for="tile-color">Select Color</label>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<input id="tile-color" type="color" value="#ff0000">
					</div>
					<input id="current-color" type="text" class="form-control" value="#ff0000">
				</div>
			</div>
			<div class="form-group">
				<label for="tile-color">Select Texture</label>
				<div id="texture-holder" class="dm-holder">

				</div>
			</div>
		</div>
		<div id="npc-area" class="dm-tool">
			<h6>NPCs</h6>
			<div id="npc-holder" class="dm-holder">
				<div class="npc dm-tile " id="npc-1">
					<img src="https://via.placeholder.com/32x32/FF0000/FFFFFF/?text=1" alt="1" width="32" height="32" >
				</div>
				<div class="npc dm-tile" id="npc-2">
					<img src="https://via.placeholder.com/32x32/FF00FF/FFFFFF?text=3" alt="3" width="32" height="32" >
				</div>
				<div class="npc dm-tile" id="npc-3">
					<img src="https://via.placeholder.com/32x32/00FF00/FFFFFF?text=4" alt="4" width="32" height="32" >
				</div>
			</div>
		</div>
		
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
	var tile = {
		id: 0,
		x: 0,
		y: 0,
		passable: true,
		empty: true,
		name: '',
		type: 'color', //color / image
		background: '#FFFFFF', //hex code or background image
		tileSize: 32,
		element: null
	};

	var textures = ['dirt01','dirt02',
					'grass01','grass02',
					'stone01','stone02','stone03','stone04',
					'wall01','wall02','wall03',
					'rock01','rock02'];

	//This is the example / current tile that will do the painting
	var paintTile = {
		type: 'color', //color / image
		background: '#FF0000', //hex code or background image
	}

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
		height: 10,
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

	//Build our board! //0 or 1 based?
	for(var w = 1; w <= board.width; w++){
		board.tiles[w] = [];
		for(var h = 1; h <= board.height; h++){
			tile.id = ++tile.id;
			tile.x = w;
			tile.y = h;

			//Clone our default HTML element
			tile.element = tileTpl.cloneNode(false);

			//Give it a unique ID
			tile.element.setAttribute("id", 'tile-'+w+'-'+h);

			//Setup a click listener on this tile
			//tile.element.addEventListener('click', colorTile);
			tile.element.onmousedown = function(e){
				doPaintTile(this);
			};

			tile.element.onmousemove = function(e){
				if(board.painting){
					doPaintTile(this);
				}
			};

			//Put our tile into our state board
			board.tiles[w][h] = tile;

			//Put our tile on the screen
			tileHolder.appendChild(tile.element);
		}
	}

	//Setup our painting textures
	for(var t = 0; t < textures.length; t++){
		//Clone our default HTML element
		dmTiles.textures[t] = textureTpl.cloneNode(false);

		//Add our texture class to this element
		dmTiles.textures[t].classList.add(textures[t]);

		//setup our click event for this texture tool
		dmTiles.textures[t].onclick = (function(t) {return function() {
			setPaintTile('texture', t);
		};})(t);


		//console.log(dmTiles.textures[t]);

		$textureHolder.appendChild(dmTiles.textures[t]);
	}

	//Set our tiles into our map
	$map.appendChild(tileHolder); //.innerHTML= tileHolder;

	$map.onmousedown = function(e){
		board.painting = true;
	};
	$map.onmouseup = function(e){
		board.painting = false;
	};

	/**
	 * Change the paintTile
	 */
	function setPaintTile(type, value){
		paintTile.type = type;

		

		if(type === 'color'){
			paintTile.background = value;
		}else if(type === 'texture'){
			console.log('value: '+value);
			console.log('textures: '+textures);
			console.log('textures[value]: '+textures[value]);
			paintTile.background = textures[value];
		}else if(type === 'image'){
			paintTile.background = value;
		}

		doPaintTile($currentTile);
	}

	//Set the new tool color
	$tileColor.addEventListener('input', function(e){
		setPaintTile('color', this.value);

		$currentColor.value = this.value;
	}, false);

	

	/**
	* Paint our map! Update the background of a tile
	*/
	function doPaintTile(element){
		if(paintTile.type === 'color'){
			element.style.backgroundColor = paintTile.background;
			element.setAttribute("class", tileDefaultCSS);
		}else if(paintTile.type === 'texture'){
			element.style.backgroundColor = '#FF00FF';
			element.setAttribute("class", textureDefaultCSS+paintTile.background);
		}else if(paintTile.type === 'image'){
			element.style.backgroundImage = 'url('+paintTile.background+')';
		}
	}
});
</script>