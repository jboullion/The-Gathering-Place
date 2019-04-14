<template>
  <div id="board" 
  oncontextmenu="return false;"
  @mouseup="mouseUp"
  @mouseleave="mouseLeave">
    <div id="current-tile" class="tile" ></div>
    <Tile v-for="id in numTiles" :key="id" 
    :currentColor="currentColor" 
    :activeTool="activeTool"
    :painting="painting"></Tile>
  </div>
</template>

<script>
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
    currentColor: {
      type: String,
      required: true,
      default: '#FFFFFF'
    },
    activeTool: {
      type: String,
      required: true,
      default: 'hand'
    }

  },
  data () {
    return {
      boardName: 'First Board',
      numTiles: (10 * 10),
      width: 10,
      height: 10,
      tiles: [],
      painting: false
    }
  },
  methods: {
    changeName(){
      this.boardName = 'Board of Awesome';
    },
    mouseUp(e){
      this.painting = false;
    },
    mouseLeave(e){
      this.painting = false;
    }
  }, 
  created(){
    eventBus.$on('isPainting', (painting) => {
      this.painting = painting;
    });
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
