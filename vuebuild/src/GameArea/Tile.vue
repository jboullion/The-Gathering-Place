<template>
  <div class="tile" :id="id" @click="changeColor" v-bind:style="{ backgroundColor: color }" v-bind:title="count"></div>
</template>

<script>
import Tile from "./Tile.vue";

export default {
  props: ['count'],
  data () {
    return {
      x: 0,
      y: 0,
      id: 'tile-'+this.x+'-'+this.y,
      passable: true,
      empty: true,
      type: 'color', //color / texture
      color: '#526F35', //hex code
      background: '', //background texture
      tileSize: 32,
      textureclass: "sprite"
    }
  },
  methods: {
    changeColor(){
      this.color = '#FF0000';
    }
  },
  components: {
  }
}
</script>

<style>
.tile {
  border: 1px solid #e5e5e0;
  border-top: 0;
  border-right: 0;
  background-color: #FFFFFF;
  height: 32px;
  width: 32px;
  position: relative;
  box-sizing: border-box;
}

.tile:before {
  content: '';
  display: block;
  width: 32px;
  height: 32px;
  margin: -1px;
}

.tile.highlight:before {
  background-color: rgba(255, 0, 0, 0.3);
  background-size: 100% 100%;
  -webkit-animation: AnimationName 1s ease infinite;
  -moz-animation: AnimationName 1s ease infinite;
  animation: AnimationName 1s ease infinite;
}

.tile.path:before {
  background-color: rgba(255, 0, 0, 0.5);
}

</style>
