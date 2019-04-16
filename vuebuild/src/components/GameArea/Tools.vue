<template>
<div id="dm-tools" class="user-game-actions">
    <div id="tool-select" class="dm-tool">
        <div id="tool-holder" class="dm-holder">
            <div id="current-tile" class="tile" :class="[backgroundType, activeTexture]" :style="{ backgroundColor: currentColor }"></div>

            <div class="tool-spacer"></div>
<!--
            <HandTool :activeTool="activeTool"></HandTool>
            <PaintTool :activeTool="activeTool"></PaintTool>
-->
     
            <Tool v-for="(tool, key) in tools" :key="key" :activeTool="activeTool" :tool="tool"></Tool>

            <div class="tool-spacer"></div>

            <div id="color-tool" class="visual-tool" title="Color"><input id="tile-color" type="color" @change="updateCurrentColor" v-model="mutatedCurrentColor" class="fas fa-palette" /></div>
            <div id="texture-tool" class="visual-tool" title="Texture" v-b-modal.modal-textures><i class="fas fa-images"></i></div>

            <!--
            <div id="hand-tool" class="tool active" title="Normal"><i class="fas fa-hand-paper"></i></div>
            <div id="paint-tool" class="tool" title="Paint"><i class="fas fa-fw fa-paint-roller"></i></div>
            <div id="fill-tool" class="tool" title="Fill"><i class="fas fa-fw fa-fill-drip"></i></div>
            <div id="erase-tool" class="tool" title="Erase"><i class="fas fa-fw fa-eraser"></i></div>
            <div id="highlight-tool" class="tool" title="Highlight"><i class="fas fa-fw fa-highlighter"></i></div>
            <div id="sample-tool" class="tool" title="Sample"><i class="fas fa-eye-dropper"></i></div>
            
            <div class="tool-spacer"></div>
           
            <div id="color-tool" class="visual-tool" title="Color"><input id="tile-color" type="color" @change="updateCurrentColor" v-model="currentColor" class="fas fa-palette" /></div>
            <div id="texture-tool" class="visual-tool" title="Texture" data-toggle="modal" data-target="#texture-modal"><i class="fas fa-images"></i></div>
           
            <div class="tool-spacer"></div>
            
            <div id="new-tool" class="tool" title="New"><i class="fas fa-file-plus"></i></div>
            -->
            <!--
                <div class="tool-spacer"></div>
                <div id="save-tool" class="visual-tool" title="Save" data-toggle="modal" data-target="#save-modal"><i class="fas fa-save"></i></div>
                
                <div id="load-tool" class="visual-tool" title="Open"><i class="fas fa-folder-open"></i></div>
                <div id="new-tool" class="visual-tool" title="New"><i class="fas fa-file-plus"></i></div>
                <div id="copy-tool" class="visual-tool" title="Duplicate"><i class="fas fa-files-medical"></i></div>
                <div id="import-tool" class="visual-tool" title="Import"><i class="fas fa-file-import"></i></div>
                <div id="clear-tool" class="visual-tool" title="Clear"><i class="fas fa-undo-alt"></i></div>
                <div id="new-layer-tool" class="visual-tool" title="New Layer"><i class="fas fa-layer-plus"></i></div>
            -->
        </div>
    </div>
</div>
</template>

<script>
/**
 * TODO: I might need to setup a different component for EACH tool
 * 
 *  
 */

import Tool from "./Tool.vue";

/*
import HandTool from "./Tools/HandTool.vue";
import PaintTool from "./Tools/PaintTool.vue";
*/

export default {
  props: ['defaults', 'currentColor', 'activeTool', 'activeTexture', 'backgroundType'],
  data () {
    return {
        mutatedCurrentColor: this.currentColor,
        tools: [
            {
                name: 'hand',
                title: 'Normal',
                icon: 'hand-paper',
                visual: 0,
                active: 1,
                toolFunction(){
                    //console.log('hand function');
                }
            },
            {
                name: 'paint',
                title: 'Paint',
                icon: 'paint-roller',
                visual: 0,
                active: 0,
                toolFunction(){
                    //console.log('paint function');
                }
            },
            {
                name: 'fill',
                title: 'Fill',
                icon: 'fill-drip',
                visual: 0,
                active: 0,
                toolFunction(){
                    //console.log('fill function');
                }
            },
            {
                name: 'erase',
                title: 'Erase',
                icon: 'eraser',
                visual: 0,
                active: 0,
                toolFunction(){
                    //console.log('erase function');
                }
            },
            {
                name: 'highlight',
                title: 'Highlight',
                icon: 'highlighter',
                visual: 0,
                active: 0,
                toolFunction(){
                    //console.log('erase function');
                }
            },
            {
                name: 'sample',
                title: 'Sample',
                icon: 'eye-dropper',
                visual: 0,
                active: 0,
                toolFunction(){
                    //console.log('erase function');
                }
            }
        ]
    }
  },
  methods: {
      updateCurrentColor({ type, target }){
          this.$emit('updateCurrentColor', target.value);
      }
  },
  components: {
        Tool,
    //   HandTool,
    //   PaintTool
  }
}
</script>

<style scoped>

#dm-tools {
	position: absolute;
	top: 0;
	left: 0;
	overflow-y: hidden;
	z-index: 10;
}
#dm-tools .dm-tool {
	padding: 15px;
}
#dm-tools .dm-tool .dm-holder {
	display: flex;
}

#current-tile {
	width: 32px;
	height: 32px;
	display: inline-block;
}

#tool-select .tool, 
 #tool-select .visual-tool {
	cursor: pointer;
	width: 32px;
	height: 32px;
	text-align: center;
	padding: 5px;
    position: relative;
    font-size: 16px;
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
	
}

.tool input[type=color],
.visual-tool input[type=color] {
	background-color: transparent;
	border: 0;
	cursor: pointer;
	position: absolute;
	top: 1px;
	left: 1px;
	padding: 6px;
	height: 30px;
	width: 30px;
}

 #tool-select .tool-spacer {
	height: 32px;
	width: 10px;
}

</style>
