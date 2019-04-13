new Vue({
	el: '#app',
	data: {
		board: {}, //This is the MAIN game board. Basically everything relates to this object
		defaultTile: {},
		defaultColor: '#526F35',
		textures: ['dirt01','dirt02', 'grass01','grass02', 'stone01','stone02','stone03','stone04', 'wall01','wall02','wall03', 'rock01','rock02'],
		defaultPaintTile: {
			type: 'color', //color / image
			background: this.defaultColor, //hex code or background image
			texture: ''
		},
		defaultNpc: {
			id: 0,
			lvl: 1,
			alignment: 1, //Do a [3][3] array for alignments? (Good Neutral Evil) x (Lawful Neutral Chaotic)
			faction: 1,
			x: 0,
			y: 0,
			hp: 10,
			movement: 10,
			passable: false,
			classes: "npc",
			name: 'NPC',
			image: 'https://via.placeholder.com/28x28/FF0000/FFFFFF/?text=NPC 1', //NPC must always be an image
		},
		dmTiles: {
			id: 0,
			passable: true,
			type: 'color', //color / image
			background: this.defaultColor, //hex code or background image
			textures: []
		}
	}
});