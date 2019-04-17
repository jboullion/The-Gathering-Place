<template>
  <div id="board" 
  oncontextmenu="return false;"
  @mouseup="mouseUp"
  @mouseleave="mouseLeave"
  :style="{width: boardWidthPixels+'px' }">
    <Tile v-for="(tile, key) in displayTiles" :key="key" 
    :activeTool="activeTool"
    :paint="paint"
    :tile="tile"
    ></Tile>
  </div>
</template>

<script>
/**
 * 
 * NEXT STEPS:
 * Resize Board
 * Create NPCs
 * Add NPCs
 * Drag NPCs
 * aStar NPCs
 * Info Box NPC
 * 
 * 
 * TODO: Turn the board into one giant SVG might be interesting...
 */
import _ from 'lodash'

import { eventBus } from '../../main.js'

import Tile from "./Tile.vue";

export default {
  props: {
    defaults: {
      type: Object,
      required: true,
      // default:function(){
      //   return {

      //   }
      // }
    },
    paint: {
      type: Object,
      required: true,
      // default:function(){
      //   return {

      //   }
      // }
    },
    activeTool: {
      type: String,
      required: true,
      default: 'hand'
    },
  },
  data () {
    return {
      boardName: 'First Board',
      boardWidthPixels: 0,
      boardheightPixels: 0,
      numTiles: (10 * 10),
      width: 20,
      height: 20,
      defaultTile: {
        x: 0,
        y: 0,
        id: '', //'tile-'+this.x+'-'+this.y,
        passable: true,
        empty: true,
        name: 'Empty',
        type: 'color', //color / texture
        color: '', //hex code
        texture: '', //background texture
        highlighted: false,
      },
      tiles: [],
      displayTiles: [],
      tileSize: 32,
      boardPadding: 200
    }
  },
  methods: {
    /**
     * Build our tiles array based on our board size
     */
    buildBoard(){
      this.boardWidthPixels = (this.width * this.tileSize);// + this.boardPadding; //padding
      this.boardheightPixels = (this.height * this.tileSize);// + this.boardPadding; //padding

      for(var w = 0; w < this.width; w++){
        this.tiles[w] = [];
        //GAME.NPCs[w] = [];
        for(var h = 0; h < this.height; h++){	

          var tile = _.clone(this.defaultTile);

          tile.x = w;
          tile.y = h;

          //Put our tile into our state board
          this.tiles[w][h] = tile;

          this.displayTiles.push(this.tiles[w][h]);

          //Creating a bunch of random GAME.NPCs
          // if( (w + h) % 10 == 0){
          //   GAME.NPCs[w][h] = createNPC(w,h);
          // }

        }
      }

      //console.log(this.tiles);
      //console.log(this.displayTiles);
/*
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
      */
    },
    mouseUp(e){
      eventBus.$emit('isPainting', false);
    },
    mouseLeave(e){
      eventBus.$emit('isPainting', false);
    }
  }, 
  beforeMount(){
    this.buildBoard();
  },
  components: {
    'Tile': Tile
  },
}
</script>

<style scoped>

  #board { 
    width: 320px;
    padding: 100px;
    display: flex;
    flex-wrap: wrap;
    overflow: hidden;
    box-sizing: content-box;
  }

</style>
