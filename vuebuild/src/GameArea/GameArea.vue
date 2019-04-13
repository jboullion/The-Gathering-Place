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

</style>
