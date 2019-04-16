<template>
  <div id="gamearea" class="hand-tool">
    <div id="gamearea-map-wrapper">
      <Tools 
      :defaults="defaults" 
      :currentColor="currentColor" 
      :activeTool="activeTool"
      :activeTexture="activeTexture"
      :backgroundType="backgroundType"
      @updateCurrentColor="updateCurrentColor"></Tools>
      <Board 
      :defaults="defaults" 
      :currentColor="currentColor"
      :activeTool="activeTool"
      :backgroundType="backgroundType"
      :activeTexture="activeTexture"></Board>
      
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
      backgroundType: 'color', // texture
      currentColor: '#526F35',
      activeTexture: '',
      activeTool: 'hand',
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
      this.activeTexture = activeTexture;
      this.backgroundType = 'texture';
    });

    eventBus.$on('newSample', (sample) => {
      if('color' == sample.type){
        this.currentColor = sample.color;
        this.backgroundType = 'color';
      }
    });
  },
  methods: {
    updateCurrentColor(color){
      this.currentColor = color;
      this.backgroundType = 'color';
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
  height: 100%;
  max-height: 100%;
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
