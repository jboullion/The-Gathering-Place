<template>
    <!-- Modal Component -->
    <b-modal id="modal-textures" title="Select Texture" ref="modal-textures" hide-footer>
        <div id="texture-holder" data-dismiss="modal" class="dm-holder">
            <div class="dm-tile texture" v-for="(texture, key) in textures" :key="key" :class="texture" @click="setActive(texture)"></div>
        </div>
    </b-modal>
</template>

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
        this.$refs['modal-textures'].hide();
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
