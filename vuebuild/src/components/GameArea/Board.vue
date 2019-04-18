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
    :heldNPC="heldNPC"
    ><NPC v-if="tile.npc" :npc="npc"></NPC>
    </Tile>
  </div>
</template>



<script>
/**
 * 
 * NEXT STEPS:
 * Resize Board * (Add inputs, sliders?, to dynamically edit the board size)
 * Create NPCs * (Create Tool to Drag NPCs onto board)
 * Add NPCs * 
 * Drag and Drop NPCs
 * aStar NPCs
 * Info Box NPC
 * 
 * 
 * TODO: Turn the board into one giant SVG might be interesting...
 */
import _ from 'lodash'

import { eventBus } from '../../main.js'

import Tile from "./Tile.vue";
import NPC from "./Tokens/NPC.vue";

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
      width: 100,
      height: 100,
      defaultTile: {
        x: 0,
        y: 0,
        id: '', //'tile-'+this.x+'-'+this.y,
        passable: true,
        npc: null,
        name: 'Empty',
        type: 'color', //color / texture
        color: '', //hex code
        texture: '', //background texture
        highlighted: false,
      },
      tiles: [],
      displayTiles: [],
      tileSize: 32,
      boardPadding: 200,
      heldNPC: null,
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

          if( (w + h) % 10 == 0){
             //GAME.NPCs[w][h] = createNPC(w,h);
             tile.npc = true;//tile;
          }

          this.displayTiles.push(this.tiles[w][h]);

          //Creating a bunch of random GAME.NPCs
          //if( (w + h) % 10 == 0){
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
  created(){
    eventBus.$on('heldNPC', (npcComponent) => {
      //console.log(npc);
      //this.heldNPC = npc;
    });

    eventBus.$on('attachNPC', (tileComponent) => {
      //console.log(tile);
      //console.log(this.tiles[tileComponent.tile.x][tileComponent.tile.y]);
      //this.tiles[tileComponent.tile.x][tileComponent.tile.y].npc = this.heldNPC;
    });
  },
  components: {
    Tile,
    NPC
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
