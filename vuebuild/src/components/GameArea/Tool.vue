<template>
    <div :id="tool.name+'-tool'" :class="{ active: tool.active, 'tool': !tool.visual, 'visual-tool': tool.visual }" :title="tool.title"><i :class="['fas fa-' + tool.icon]"></i></div>
</template>

<script>
import Tool from "./Tool.vue";

export default {
  props: ['tool'],
  data () {
    return {
    }
  },
  methods: {
      updateCurrentColor({ type, target }){
          this.$emit('updateCurrentColor', target.value);
      }
  },
  components: {

  }
}
</script>

<style scoped>
#tool-select .tool, 
 #tool-select .visual-tool {
	cursor: pointer;
	width: 32px;
	height: 32px;
	text-align: center;
	padding: 0.7rem;
	position: relative;
}

#tool-select .tool.active, 
#tool-select .tool:hover, 
#tool-select .visual-tool.active,
#tool-select .visual-tool:hover {
	background-color: #98c4f3;
}

#tool-select .tool i, 
.visual-tool i {
	display: block;
	font-size: 1.6rem;
}

.tool input[type=color],
.visual-tool input[type=color] {
	background-color: transparent;
	border: 0;
	cursor: pointer;
	position: absolute;
	top: 1px;
	left: 1px;
	font-size: 1.6rem;
	padding: 6px;
	height: 30px;
	width: 30px;
}

 #tool-select .tool-spacer {
	height: 32px;
	width: 10px;
}
</style>
