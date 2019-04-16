<template>
    <!-- Modal Component -->
    <b-modal id="modal-textures" title="Select Texture" hide-footer>
        <div id="texture-holder" data-dismiss="modal" class="dm-holder">
            <div class="dm-tile texture" v-for="(texture, key) in textures" :key="key" :class="texture" @click="setActive(texture)"></div>
        </div>
    </b-modal>
</template>



<!-- 
<div id="texture-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-window-close" aria-hidden="true"></i>
                </button>

                <h5 class="modal-title">Select Texture</h5>
                <div id="texture-holder" data-dismiss="modal" class="dm-holder">
                    <div class="dm-tile sprite dirt01"></div>
                </div>
            </div>
        </div>
    </div>
</div>
-->



<script>
import { eventBus } from '../../../main.js'

export default {
  props: [],
  data () {
    return {
        textures: ['dirt01','dirt02',
					'grass01','grass02',
					'stone01','stone02','stone03','stone04',
					'wall01','wall02','wall03',
					'rock01','rock02']
    }
  },
  methods: {
    setActive(texture){
        eventBus.$emit('activateTexture', texture);
	}
  },
  computed: {
  }
}
</script>

<style scoped>
#modal-textures .dm-holder {
	display: flex;
	margin: 15px 0;
	flex-wrap: wrap;
}
#modal-textures .dm-holder .dm-tile {
	cursor: pointer;
	width: 32px;
	height: 32px;
	margin: 5px 5px 5px 0;
}
#modal-textures .dm-holder .dm-tile img, #party-page #modal-textures .dm-holder .dm-tile input {
	max-width: 100%;
	height: 32px;
	width: 32px;
}
#modal-textures .dm-holder .dm-tile.active {
	box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.8);
}
</style>
