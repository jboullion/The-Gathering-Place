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

body,
html { 
  min-height: 100%;
  background-color: #e5e5e0;
  font-size: 16px;
}

</style>
