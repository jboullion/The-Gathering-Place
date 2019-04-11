<div id="dm-tools" class="user-game-actions">
    <div id="tool-select" class="dm-tool">
        <h6>Tools</h6>
        <div id="tool-holder" class="dm-holder">
            <div id="paint-tool" class="tool"><i class="fas fa-fw fa-paint-roller"></i></div>
            <div id="fill-tool" class="tool"><i class="fas fa-fw fa-fill-drip"></i></div>
            <div id="erase-tool" class="tool"><i class="fas fa-fw fa-eraser"></i></div>
            <div id="highlight-tool" class="tool"><i class="fas fa-fw fa-highlighter"></i></div>
            <div id="sample-tool" class="tool"><i class="fas fa-eye-dropper"></i></div>
            
        </div>
    </div>
    <div id="dm-tile-area" class="dm-tool">
        <h6>Tiles</h6>
        
        <div class="form-group">
            <label for="current-tile">Current Tile</label><br />
            <div id="current-tile" class="tile" style="background-color: #ff0000"></div>
        </div>

        <div class="form-group">
            <label for="tile-color">Select Color</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <input id="tile-color" type="color" value="#ff0000">
                </div>
                <input id="current-color" type="text" class="form-control" value="#ff0000">
            </div>
        </div>
        <div class="form-group">
            <label for="tile-color">Select Texture</label>
            <div id="texture-holder" class="dm-holder">

            </div>
        </div>
    </div>
    <div id="npc-area" class="dm-tool">
        <h6>NPCs</h6>
        <div id="npc-holder" class="dm-holder">
            <div class="npc dm-tile " id="npc-1">
                <img src="https://via.placeholder.com/32x32/FF0000/FFFFFF/?text=1" alt="1" width="32" height="32" >
            </div>
            <div class="npc dm-tile" id="npc-2">
                <img src="https://via.placeholder.com/32x32/FF00FF/FFFFFF?text=3" alt="3" width="32" height="32" >
            </div>
            <div class="npc dm-tile" id="npc-3">
                <img src="https://via.placeholder.com/32x32/00FF00/FFFFFF?text=4" alt="4" width="32" height="32" >
            </div>
        </div>
    </div>
    
</div>