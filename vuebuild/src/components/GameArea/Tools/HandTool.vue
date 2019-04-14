<template>
   <div id="hand-tool" @click="setActive" :class="{tool: true, active: activeTool==name}" title="Normal"><i class="fas fa-fw fa-hand-paper"></i></div>
</template>

<script>

import { eventBus } from '../../../main.js'

export default {
  props: ['activeTool'],
  data () {
    return {
        name: 'hand',
        isActive: true
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
