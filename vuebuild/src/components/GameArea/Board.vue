<template>
  <div id="board" oncontextmenu="return false;">
    <div id="current-tile" class="tile" ></div>
    <Tile v-for="id in numTiles" :key="id" :count="id" :currentColor="currentColor"></Tile>
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
    }

  },
  data () {
    return {
      boardName: 'First Board',
      numTiles: (10 * 10),
      width: 10,
      height: 10,
      tiles: []

    }
  },
  methods: {
    changeName(){
      this.boardName = 'Board of Awesome';
    }
  },
  components: {
    'Tile': Tile
  }
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
