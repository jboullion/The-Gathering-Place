<template>
  <div class="tile" 
  :class="[ typeMute, textureMute ]"
  
  @mousedown="mouseDown" 
  @mousemove="mouseMove" 
  @dragover="dragover"
  @drop="drop"
  :style="{ backgroundColor: colorMute }" 
 >
 </div>
</template>

<script>
// :class="[backgroundType, activeTexture]"
import _ from 'lodash'

import { eventBus } from '../../main.js'

export default {
  props: ['activeTool', 'paint', 'tile'],
  data () {
    return {
      xMute: this.tile.x,
      yMute: this.tile.y,
      idMute: 'tile-'+this.tile.x+'-'+this.tile.y,
      passableMute: this.tile.passable,
      emptyMute: this.tile.empty,
      nameMute: this.tile.name,
      paintingMute: this.paint.painting,
      typeMute: this.tile.type, //color / texture
      colorMute: this.tile.color, //hex code
      textureMute: this.tile.texture, //background texture
      highlightedMute: this.tile.highlighted,

      throttlePaint: _.throttle( function (color) {
        this.tilePaint(color);
      },1000),
      throttleHighlight: _.throttle( function () {
        this.highlight();
      },1000),

    }
  },
  methods: {
    mouseDown(e){

      switch(this.activeTool){
        case 'hand':

          break;
        case 'paint':
          this.paintingMute = true;
          eventBus.$emit('isPainting', this.paintingMute);
          this.throttlePaint(this.paint.currentColor);
          break;
        case 'fill':
          eventBus.$emit('fill', {type: this.typeMute, color:this.colorMute, texture:this.textureMute});
          break;
        case 'erase':
          this.paintingMute = true;
          eventBus.$emit('isPainting', this.paintingMute);
          this.tilePaint('erase');
          break;
        case 'highlight':
          this.paintingMute = true;
          eventBus.$emit('isPainting', this.paintingMute);
          this.throttleHighlight();
          break;
        case 'sample':
          eventBus.$emit('newSample', {type: this.typeMute, color:this.colorMute});
          break;
      }
      
    },
    mouseMove(e){
      switch(this.activeTool){
        case 'hand':

          break;
        case 'paint':
          if(this.paint.painting){
            this.throttlePaint(this.paint.currentColor);
          }
          break;
        case 'erase':
          if(this.paint.painting){
            this.throttlePaint('erase');
          }
          break;
        case 'highlight':
          if(this.paint.painting){
            this.throttleHighlight();
          }
          break;
      }
    },
    mouseUp(e){
      this.mutablePainting = false;
      eventBus.$emit('isPainting', this.mutablePainting);
    },
    tilePaint(color){
      if(color === 'erase'){
        this.colorMute = '';
        this.textureMute = ''
        this.typeMute = 'color';
      }else if(this.paint.backgroundType === 'color'){
        this.colorMute = color;
        this.textureMute = ''
        this.typeMute = this.paint.backgroundType;
      }else{
        this.colorMute = '';
        this.textureMute = this.paint.activeTexture;
        this.typeMute = this.paint.backgroundType;
      }
      
    },
    highlight(){
    
      this.highlighted = true;
    
      interval(()=>{
        this.highlighted = false;
      }, 5000, 1);
    },
    dragover(e){
      e.preventDefault();
    },
    drop(e){
      console.log(e);
      //Move whatever was dropped here
      //var data = e.dataTransfer.getData("text");

      //e.target.appendChild('<div style="background-color: red;">test</div>');
      
      //Since our aStarStart is a reference to an actual NPC we can update it's XY so we know where to start our next Astar path for this NPC
     // aStarStart.x = this.x;
     // aStarStart.y = this.y;

      //After we drop something we need to update our graph for aStar searches
    //  buildGraphArray();
     // clearAstarPath();
    }
  },
  created(){
    //Fill every tile that matches the tile being filled
    eventBus.$on('fill', (fill) => {
      if(this.typeMute == fill.type && 'color' == this.typeMute && this.colorMute == fill.color ){
        this.tilePaint(this.paint.currentColor);
      }else if(this.typeMute == fill.type && 'texture' == this.typeMute && this.textureMute == fill.texture){
        this.tilePaint(this.paint.currentColor);
      }
    });
  },
  components: {
  }
}
</script>

<style scoped>

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
  -webkit-animation: flicker 1s ease infinite;
  -moz-animation: flicker 1s ease infinite;
  animation: flicker 1s ease infinite;
}

.tile.path:before {
  background-color: rgba(255, 0, 0, 0.5);
}

@-webkit-keyframes flicker {
	0% {
			background-position: 0% 50%;
			opacity: 0.5;
 }
	50% {
			background-position: 100% 50%;
			opacity: 1;
 }
	100% {
			background-position: 0% 50%;
			opacity: 0.5;
 }
}
@-moz-keyframes flicker {
	0% {
			background-position: 0% 50%;
			opacity: 0.5;
 }
	50% {
			background-position: 100% 50%;
			opacity: 1;
 }
	100% {
			background-position: 0% 50%;
			opacity: 0.5;
 }
}
@keyframes flicker {
	0% {
			background-position: 0% 50%;
			opacity: 0.5;
 }
	50% {
			background-position: 100% 50%;
			opacity: 1;
 }
	100% {
			background-position: 0% 50%;
			opacity: 0.5;
 }
}
</style>
