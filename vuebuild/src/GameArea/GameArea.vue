<template>
  <div id="gamearea" class="hand-tool">
    <div id="gamearea-map-wrapper">
      <Board></Board>
    </div>
  </div>
</template>

<script>
import Board from "./Board.vue";

export default {
  data () {
    return {
    }
  },
  components: {
    'Board': Board
  }
}
</script>

<style>
#gamearea {
  position: relative;
	max-width: none; 
  width: 100%;
  overflow: hidden;
}
#gamearea-map-wrapper {
  background-color: #e5e5e0;
  height: 100%;
  max-height: 100%;
  width: 100%;
  overflow: auto; 
}

</style>
