<template>
  <div id="app">
    <GameArea></GameArea>
  </div>
</template>

<script>
import GameArea from "./GameArea/GameArea.vue";

export default {
  name: 'app',
  data () {
    return {
    }
  },
  components: {
    'GameArea': GameArea
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;

}

</style>
