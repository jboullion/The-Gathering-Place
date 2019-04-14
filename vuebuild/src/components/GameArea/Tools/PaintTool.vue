<template>
   <div id="paint-tool" @click="setActive" :class="{tool: true, active: activeTool==name}" title="Paint"><i class="fas fa-fw fa-paint-roller"></i></div>
</template>

<script>
import { eventBus } from '../../../main.js'

export default {
  props: ['activeTool'],
  data () {
    return {
        name: 'paint',
        isActive: false
    }
  },
  methods: {
    setActive(){
        this.isActive = true;
        eventBus.$emit('activateTool', this.name);
    }
  },
  components: {

  }
}
</script>

<style scoped>

</style>
