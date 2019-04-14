<template>
  <div id="gamearea" class="hand-tool">
    <div id="gamearea-map-wrapper">
      <Tools :defaults="defaults" :currentColor="currentColor" @updateCurrentColor="currentColor = $event"></Tools>
      <Board :defaults="defaults" :currentColor="currentColor"></Board>
      
    </div>
  </div>
</template>

<script>
import Board from "./Board.vue";
import Tools from "./Tools.vue";

export default {
  data () {
    return {
      defaults: {
        color: '#526F35'
      }, 
      currentColor: '#526F35'
    }
  },
  components: {
    Board,
    'Tools': Tools
  },
  methods: {
    changeColor(){
      //this.defaults.color = '#'+Math.floor(Math.random()*16777215).toString(16);
    }
  }
}
</script>

<style scoped>
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
