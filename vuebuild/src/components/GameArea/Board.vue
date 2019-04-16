<template>
  <div id="board" 
  oncontextmenu="return false;"
  @mouseup="mouseUp"
  @mouseleave="mouseLeave">
    <Tile v-for="id in numTiles" :key="id" 
    :activeTool="activeTool"
    :paint="paint"
    ></Tile>
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
      numTiles: (10 * 10),
      width: 10,
      height: 10,
      tiles: [],
    }
  },
  methods: {
    changeName(){
      this.boardName = 'Board of Awesome';
    },
    mouseUp(e){
      eventBus.$emit('isPainting', false);
    },
    mouseLeave(e){
      eventBus.$emit('isPainting', false);
    }
  }, 
  created(){
    
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
