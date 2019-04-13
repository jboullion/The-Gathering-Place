<template>
  <div id="board" oncontextmenu="return false;">
    
    <Tile v-for="id in tiles" v-bind:key="id" v-bind:count="id"></Tile>
  </div>
</template>

<script>
import Tile from "./Tile.vue";

export default {
  data () {
    return {
      boardName: 'First Board',
      tiles: (10 * 10),
      width: 10,
      height: 10
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

<style>
  #board { 
    width: 320px;
    padding: 100px;
    display: flex;
    flex-wrap: wrap;
    overflow: hidden;
  }

</style>
