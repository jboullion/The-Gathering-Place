<div id="party-gamearea">
    <div class="gamearea-map-wrapper">
        <div id="gamearea-map">
            <h3>Generate this with JS. Store each cell in a 2d array as it's own object.</h3>
            <p>Should each square be 32x32 (or 64x64, not sure) pixels in size with a 1px border</p>
            <p>We will generate more map tiles than will fit in game area and then the user can scroll around if needed</p>
            <p>Maybe even a "zoom" in / out function?...that can come later</p>
        </div>
    </div>
    <div id="dm-tools" class="user-game-actions">
        <div id="npc-area">
            <h3>NPCs</h3>
            <div id="npc-holder">
                <div class="npc" draggable="true" ondragstart="">
                    <img src="https://via.placeholder.com/32x32/FF0000/FFFFFF/?text=1" alt="1" width="32" height="32" >
                </div>
                <div class="npc" draggable="true" ondragstart="">
                    <img src="https://via.placeholder.com/32x32/FF00FF/FFFFFF?text=3" alt="3" width="32" height="32" >
                </div>
                <div class="npc" draggable="true" ondragstart="">
                    <img src="https://via.placeholder.com/32x32/00FF00/FFFFFF?text=4" alt="4" width="32" height="32" >
                </div>
            </div>
        </div>
        <div class="tile-area">
            <h3>Tiles</h3>
            <div id="tile-holder">
                <div class="tile" draggable="true" ondragstart="">
                    <img src="https://via.placeholder.com/32x32/00FF00/FFFFFF" alt="1" width="32" height="32" >
                </div>
                <div class="tile" draggable="true" ondragstart="">
                    <img src="https://via.placeholder.com/32x32/00FF00/FFFFFF" alt="1" width="32" height="32" >
                </div>
                <div class="tile" draggable="true" ondragstart="">
                    <img src="https://via.placeholder.com/32x32/00FF00/FFFFFF" alt="1" width="32" height="32" >
                </div>
                <div class="tile" draggable="true" ondragstart="">
                    <img src="https://via.placeholder.com/32x32/00FF00/FFFFFF" alt="1" width="32" height="32" >
                </div>
                <div class="tile" draggable="true" ondragstart="">
                    <img src="https://via.placeholder.com/32x32/00FF00/FFFFFF" alt="1" width="32" height="32" >
                </div>
            </div>
        </div>
    </div>
</div>