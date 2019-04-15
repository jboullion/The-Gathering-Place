<template>
   <div 
		:id="tool.name+'-tool'" 
		class="tool"
		:class="{ active: (activeTool==tool.name)}" 
		:title="tool.title"
		@click="setActive">
			<i :class="setClass"></i>
		</div>
</template>

<script>
import { eventBus } from '../../main.js'

export default {
  props: ['activeTool', 'tool', 'toolFunction'],
  data () {
    return {
        name: 'paint',
        isActive: false
    }
  },
  methods: {
    setActive(){
        this.isActive = true;
				eventBus.$emit('activateTool', this.tool.name);
				this.tool.toolFunction();
		}
  },
  computed: {
		setClass(){
			return "fas fa-fw fa-"+this.tool.icon;
		}
  }
}
</script>

<style scoped>

</style>
