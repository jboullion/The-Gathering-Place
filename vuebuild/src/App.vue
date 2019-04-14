<template>
  <div id="app">
    <GameArea></GameArea>
  </div>
</template>

<script>
import GameArea from "./components/GameArea/GameArea.vue";

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
html {
}

body {

}

body,
html { 
  min-height: 100%;
  background-color: #e5e5e0;
  font-size: 10px;
}

* {

}

#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;

}



</style>
