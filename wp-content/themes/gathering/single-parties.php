<?php 
	get_header(); 
	the_post(); 

	pk_set('fields', get_fields());
?>
<div id="party-page" class="page">
	<div id="party-header">
	</div>
	<div id="party-wrapper">
		<div id="party-sidebar">
			<h3>Logo <i class="fas fa-ellipsis-v"></i></h3>

			<nav>
				<div class="menu-item"><span>Character Sheet</span></div>
				<div class="menu-item"><span>Party Screen</span></div>

				<div class="menu-section"><span>Player Messages</span></div>

				<div class="menu-item"><span><i class="fas fa-user-crown"></i> Player 1</span></div>
				<div class="menu-item"><span><i class="fas fa-user"></i> Player 2</span></div>
				<div class="menu-item"><span><i class="fas fa-user"></i> Player 3</span></div>
				<div class="menu-item"><span><i class="fas fa-user"></i> Player 4</span></div>
			</nav>
		</div>
		<div id="party-chat">
			<div class="chat-messagearea">
				<div class="chat-message">
					<div class="chat-image">
						<i class="fas fa-user"></i>
					</div>
					<div class="chat-content">
						<div class="chat-name"><span>Player 1</span></div>
						<div class="chat-text">
							<span>This could be all sorts of things</span>
						</div>
					</div>
				</div>

				<div class="chat-message">
					<div class="chat-image">
						<i class="fas fa-user"></i>
					</div>
					<div class="chat-content">
						<div class="chat-name"><span>Player 2</span></div>
						<div class="chat-text">
							<span>This could be all sorts of things</span>
						</div>
					</div>
				</div>

				<div class="chat-message">
					<div class="chat-image">
						<i class="fas fa-user"></i>
					</div>
					<div class="chat-content">
						<div class="chat-name"><span>Player 3</span></div>
						<div class="chat-text">
							<span>This could be all sorts of things</span>
						</div>
					</div>
				</div>
			</div>
			<div class="chat-send-message">
				<div class="input-group">
					<div class="input-group-prepend">
						<button class="btn btn-outline-secondary" type="button" id="message-options"><i class="fas fa-plus"></i></button>
					</div>
					<input type="text" class="form-control" id="message" aria-label="send message">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary" type="button" id="add-emoji"><i class="fas fa-smile-plus"></i></button>
					</div>
				</div>
			</div>
		</div>
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
	</div>
</div>
<?php 
get_footer();