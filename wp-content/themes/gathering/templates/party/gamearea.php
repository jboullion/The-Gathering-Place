<div id="party-gamearea">
	<div id="gamearea-map-wrapper">
		<div id="gamearea-map">
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
				<div id="highligjt-tool" class="tool"><i class="fas fa-fw fa-highlighter"></i></div>
			</div>
		</div>
		<div id="dm-tile-area" class="dm-tool">
			<h6>Tiles</h6>
			<div id="tile-holder" class="dm-holder"> 
				<div class="dm-tile active">
					<input id="tile-color" type="color" value="#ff0000">
				</div>
				<div class="dm-tile">
					<img src="https://via.placeholder.com/32/00FF00/FFFFFF" alt="1" width="32" height="32" >
				</div>
				<div class="dm-tile">
					<img src="https://via.placeholder.com/32/00FF00/FFFFFF" alt="1" width="32" height="32" >
				</div>
				<div class="dm-tile">
					<img src="https://via.placeholder.com/32/00FF00/FFFFFF" alt="1" width="32" height="32" >
				</div>
				<div class="dm-tile">
					<img src="https://via.placeholder.com/32/00FF00/FFFFFF" alt="1" width="32" height="32" >
				</div>
				<div class="dm-tile">
					<img src="https://via.placeholder.com/32/00FF00/FFFFFF" alt="1" width="32" height="32" >
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

<script id="tile-template" type="text/template">
	<div class="tile contain-background" >
	</div>
</script>




<script>
jQuery(document).ready(function($) {
	var $map = document.getElementById("gamearea-map");
	var tileTpl = document.createElement("div"); // document.getElementById("tile-template").innerHTML;
	var tileHolder =  document.createDocumentFragment(); //'';
	var tileColor = document.getElementById("tile-color"),
		tileColorVal = tileColor.value;


	tileTpl.setAttribute("class", "tile contain-background")

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
				console.log("OK Down!");
				this.style.backgroundColor = tileColorVal;//tileColorVal;
			};

			tile.element.onmousemove = function(e){
				if(board.painting){
					this.style.backgroundColor = tileColorVal;//tileColorVal;
				}
			};
/*
			//Setup a click listener on this tile
			tile.element.addEventListener('ondragover', function(e){
				console.log('ondragover!');
				e.dataTransfer.dropEffect = "move"
			});

			//Setup a click listener on this tile
			tile.element.addEventListener('ondrop', function(e){
				var data = e.dataTransfer.getData("text/plain");

				//document.getElementById()
				console.log('ondrop! '+data);
			});
*/

			//Put our tile into our state board
			board.tiles[w][h] = tile;

			//Put our tile on the screen
			tileHolder.appendChild(tile.element);
		}
	}

	//Set our tiles into our map
	$map.appendChild(tileHolder); //.innerHTML= tileHolder;

	$map.onmousedown = function(e){
		board.painting = true;
	};
	$map.onmouseup = function(e){
		board.painting = false;
	};
});

</script>