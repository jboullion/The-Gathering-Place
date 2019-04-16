<template>
    <div id="texture-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-window-close" aria-hidden="true"></i>
                    </button>
                    
                    <h5 class="modal-title">Select Texture</h5>
                    <div id="texture-holder" data-dismiss="modal" class="dm-holder"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { eventBus } from '../../main.js'

export default {
  props: ['name'],
  data () {
    return {
        textures: []
    }
  },
  methods: {
    setActive(){
            // this.isActive = true;
            // eventBus.$emit('activateTool', this.tool.name);
            // this.tool.toolFunction();
		}
  },
  computed: {
		
  }
}
</script>

<style scoped>

</style>
