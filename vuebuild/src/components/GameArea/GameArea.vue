<template>
  <div id="gamearea" class="hand-tool">
    <div id="gamearea-map-wrapper">
      <Tools 
      :defaults="defaults" 
      :activeTool="activeTool"
      :paint="paint"
      @updateCurrentColor="updateCurrentColor"></Tools>

    

      <Board 
      :defaults="defaults" 
      :activeTool="activeTool"
      :paint="paint"
      heldNPC></Board>
      
    </div>
    <Textures></Textures>
  </div>
</template>

<script>
import { eventBus } from '../../main.js'

import Board from "./Board.vue";
import Tools from "./Tools.vue";


import Textures from "./Modals/Textures.vue";

export default {
  data () {
    return {
      defaults: {
        color: '#526F35'
      }, 
      activeTool: 'hand',
      paint: {
        backgroundType: 'color', // texture
        currentColor: '#526F35',
        activeTexture: '',
        painting: false
      },
      heldNPC: null
    }
  },
  components: {
    Board,
    Tools,
    Textures
  },
  created(){
    eventBus.$on('activateTool', (activeTool) => {
      this.activeTool = activeTool;
    });

    eventBus.$on('activateTexture', (activeTexture) => {
      this.paint.activeTexture = activeTexture;
      this.paint.backgroundType = 'texture';
    });

    eventBus.$on('newSample', (sample) => {
      if('color' == sample.type){
        this.paint.currentColor = sample.color;
        this.paint.backgroundType = 'color';
      }
    });

    eventBus.$on('isPainting', (painting) => {
      this.paint.painting = painting;
    });
  },
  methods: {
    updateCurrentColor(color){
      this.paint.currentColor = color;
      this.paint.backgroundType = 'color';
    },
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
  height: 100vh;
  max-height: 100vh;
  width: 100%;
  overflow: auto; 
}

.texture {
	display: inline-block;
	overflow: hidden;
	background-repeat: no-repeat;
	background-image: url("/images/terrain_spritesheet.png");
}
.dirt01 {
	width: 64px;
	height: 64px;
	background-position: -0px -0px;
}
.dirt02 {
	width: 64px;
	height: 64px;
	background-position: -64px -0px;
}
.grass01 {
	width: 64px;
	height: 64px;
	background-position: -128px -0px;
}
.grass02 {
	width: 64px;
	height: 64px;
	background-position: -192px -0px;
}
.stone01 {
	width: 64px;
	height: 64px;
	background-position: -256px -0px;
}
.stone02 {
	width: 64px;
	height: 64px;
	background-position: -320px -0px;
}
.stone03 {
	width: 64px;
	height: 64px;
	background-position: -384px -0px;
}
.stone04 {
	width: 64px;
	height: 64px;
	background-position: -448px -0px;
}
.wall01 {
	width: 64px;
	height: 64px;
	background-position: -512px -0px;
}
.wall02 {
	width: 64px;
	height: 64px;
	background-position: -576px -0px;
}
.wall03 {
	width: 64px;
	height: 64px;
	background-position: -640px -0px;
}
.rock01 {
	width: 64px;
	height: 64px;
	background-position: -704px -0px;
}
.rock02 {
	width: 64px;
	height: 64px;
	background-position: -768px -0px;
}
</style>
