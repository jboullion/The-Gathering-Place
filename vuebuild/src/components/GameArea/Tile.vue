<template>
  <div class="tile" 
  :class="[ type, texture ]"
  
  @mousedown="mouseDown" 
  @mousemove="mouseMove" 
  :style="{ backgroundColor: color }" 
 ></div>
</template>

<script>
// :class="[backgroundType, activeTexture]"
import _ from 'lodash'

import { eventBus } from '../../main.js'

import Tile from "./Tile.vue";

export default {
  props: ['activeTool', 'paint'],
  data () {
    return {
      x: 0,
      y: 0,
      id: 'tile-'+this.x+'-'+this.y,
      passable: true,
      empty: true,
      name: 'Empty',
      mutablePainting: this.paint.painting,
      type: 'color', //color / texture
      color: '', //hex code
      texture: '', //background texture
      highlighted: false,
      tileSize: 32,
      throttlePaint: _.throttle( function (color) {
        this.tilePaint(color);
      },1000),
      throttleHighlight: _.throttle( function () {
        this.highlight();
      },1000),

    }
  },
  methods: {
    interval(func, wait, times){
        var interv = function(w, t){
            return function(){
                if(typeof t === "undefined" || t-- > 0){
                    setTimeout(interv, w);
                    try{
                        func.call(null);
                    }
                    catch(e){
                        t = 0;
                        throw e.toString();
                    }
                }
            };
        }(wait, times);

        setTimeout(interv, wait);
    },
    mouseDown(e){

      switch(this.activeTool){
        case 'hand':

          break;
        case 'paint':
          this.mutablePainting = true;
          eventBus.$emit('isPainting', this.mutablePainting);
          this.throttlePaint(this.paint.currentColor);
          break;
        case 'fill':
          eventBus.$emit('fill', {type: this.type, color:this.color, texture:this.texture});
          break;
        case 'erase':
          this.mutablePainting = true;
          eventBus.$emit('isPainting', this.mutablePainting);
          this.tilePaint('erase');
          break;
        case 'highlight':
          this.mutablePainting = true;
          eventBus.$emit('isPainting', this.mutablePainting);
          this.throttleHighlight();
          break;
        case 'sample':
          eventBus.$emit('newSample', {type: this.type, color:this.color});
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
        this.color = '';
        this.texture = ''
        this.type = 'color';
      }else if(this.paint.backgroundType === 'color'){
        this.color = color;
        this.texture = ''
        this.type = this.paint.backgroundType;
      }else{
        this.color = '';
        this.texture = this.paint.activeTexture;
        this.type = this.paint.backgroundType;
      }
      
    },
    highlight(){
    
      this.highlighted = true;
    
      this.interval(()=>{
        this.highlighted = false;
      }, 5000, 1);
    }
  },
  created(){
    //Fill every tile that matches the tile being filled
    eventBus.$on('fill', (fill) => {
      if(this.type == fill.type && 'color' == this.type && this.color == fill.color ){
        this.tilePaint(this.paint.currentColor);
      }else if(this.type == fill.type && 'texture' == this.type && this.texture == fill.texture){
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
