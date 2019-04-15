<template>
  <div class="tile" 
  @mousedown="mouseDown" 
  @mousemove="mouseMove" 
  :style="{ backgroundColor: color }" 
 ></div>
</template>

<script>
import _ from 'lodash'

import { eventBus } from '../../main.js'

import Tile from "./Tile.vue";

export default {
  props: ['currentColor', 'activeTool', 'painting', 'fill'],
  data () {
    return {
      x: 0,
      y: 0,
      id: 'tile-'+this.x+'-'+this.y,
      passable: true,
      empty: true,
      name: 'Empty',
      mutablePainting: this.painting,
      type: 'color', //color / texture
      color: '', //hex code
      background: '', //background texture
      tileSize: 32,
      textureclass: "sprite",
      //throttlePaint: _.throttle(this.paint, 107),
      throttlePaint: _.throttle( function (color) {
        this.paint(color);
      },1000)

    }
  },
  methods: {
    mouseDown(e){

      switch(this.activeTool){
        case 'hand':

          break;
        case 'paint':
          console.log('down fill');
          this.mutablePainting = true;
          eventBus.$emit('isPainting', this.mutablePainting);
          //this.paint(this.currentColor)
          this.throttlePaint(this.currentColor);
          break;
        case 'fill':
          eventBus.$emit('fill', {type: this.type, color:this.color});
          break;
      }
      
    },
    mouseMove(e){
      switch(this.activeTool){
        case 'hand':

          break;
        case 'paint':
          //console.log(e);
          if(this.painting){
            //this.paint(this.currentColor)
            this.throttlePaint(this.currentColor);
          }
          break;
      }
    },
    mouseUp(e){
      this.mutablePainting = false;
      eventBus.$emit('isPainting', this.mutablePainting);
    },
    paint(color){
      this.color = color;
    }
  },
  created(){
    //Fill every tile that matches the tile being filled
    eventBus.$on('fill', (fill) => {
      if('color' == fill.type && this.color == fill.color){
        this.paint(this.currentColor);
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
  -webkit-animation: AnimationName 1s ease infinite;
  -moz-animation: AnimationName 1s ease infinite;
  animation: AnimationName 1s ease infinite;
}

.tile.path:before {
  background-color: rgba(255, 0, 0, 0.5);
}
</style>
