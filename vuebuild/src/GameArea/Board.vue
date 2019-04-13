<template>
  <div id="board-wrapper" oncontextmenu="return false;">
    <h1>Board Name: {{ boardName }}</h1>
    <hr />
    <button @click="changeName">Change Name</button>
    <hr />

    <Tile></Tile>
  </div>
</template>

<script>
import Tile from "./Tile.vue";

export default {
  data () {
    return {
      boardName: 'First Board'
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


</style>
