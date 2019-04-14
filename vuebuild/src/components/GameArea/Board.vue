<template>
  <div id="board" oncontextmenu="return false;">
    <div id="current-tile" class="tile" :style="{ backgroundColor: defaults.color }"></div>
    <Tile v-for="id in numTiles" :key="id" :count="id"></Tile>
  </div>
</template>

<script>
import Tile from "./Tile.vue";

export default {
  props: ['defaults'],
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
