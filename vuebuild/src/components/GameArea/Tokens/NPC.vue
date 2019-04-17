<template>
  <div  :id="id"
        class="npc" 
        draggable="true"
        @dragstart="dragStart" 
        @dragend="dragEnd"
        @click="click" 
        :title="name"
        :class="[ classes ]"
        :style="{ }" 
 ></div>
</template>

<script>
import { eventBus } from '../../../main.js'

export default {
  props: [],
  data () {
    return {
        id: 'npc-1',
        lvl: 1,
        x: 0,
        y: 0,
        passable: false,
        hp: 10,
        movement: 10,
        alignment: 1,
        faction: 1,
        friendly: true,
        classes: [],
        name: 'NPC',
        image: 'https://via.placeholder.com/28x28/FF0000/FFFFFF/?text=NPC 1'
    }
  },
  methods: {
    mouseDown(e){
      
    },
    mouseMove(e){

    },
    mouseUp(e){
    },
    dragStart(e){
        //save the id of our NPC for talking to the dropped tile. This NPC will then be moved to that tile if it is available
        //console.log(e);
        //e.dataTransfer.setData("text", e.target.id);
        this.classes.push("movement");

        //This is the new start point for aStar
        //aStarStart = this;

        //Set our active tool to the hand tool
        eventBus.$emit('activateTool', 'hand');
        eventBus.$emit('heldNPC', this);
	},
	dragEnd(e){
        this.classes.remove("movement");
        eventBus.$emit('heldNPC', null);
    },
	click(e){
        console.log('click NPC: '+this.x+', '+this.y);
    }
  },
  created(){
 
  },
  components: {
  }
}
</script>

<style scoped>
    .npc {
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        background-color: #98c4f3;
        border: 2px dotted #231f20;
        border-radius: 50%;
        cursor: grab;
        width: 28px;
        height: 28px;
    }

    .npc.movement {
        display: block;
        content: '';
        background-color: rgba(200,100,100,0.5);
    }
</style>
