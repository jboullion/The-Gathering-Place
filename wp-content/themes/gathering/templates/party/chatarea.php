<div id="party-chat">
	<div id="chat-message-area">
		<div class="chat-message-align">
			<div class="chat-date-hr" data-content="Today"></div>
			
			<div class="chat-message">
				<div class="chat-image">
					<i class="fas fa-user"></i>
				</div>
				<div class="chat-content">
					<div class="chat-info"><span class="name">Player 1</span> <span class="time">4:08pm</span></div>
					<div class="chat-text">
						This could be all sorts of things
					</div>
				</div>
			</div>

			<div class="chat-message">
				<div class="chat-image">
					<i class="fas fa-user"></i>
				</div>
				<div class="chat-content">
					<div class="chat-info"><span class="name">Player 2</span> <span class="time">4:08pm</span></div>
					<div class="chat-text">
						This could be all sorts of things
					</div>
				</div>
			</div>

			<div class="chat-message">
				<div class="chat-image">
					<i class="fas fa-user"></i>
				</div>
				<div class="chat-content">
					<div class="chat-info"><span class="name">Player 3</span> <span class="time">4:08pm</span></div>
					<div class="chat-text">
						This could be all sorts of things
					</div>
				</div>
			</div>
		</div>
	</div>

	
	<div id="chat-send-message">
		<div class="input-group">
			<div class="input-group-prepend">
				<button class="send-btn" type="button" id="message-options"><i class="fas fa-plus"></i></button>
			</div>
			<input type="text" class="form-control" id="message" aria-label="send message">
			<div class="input-group-append">
				<button class="send-btn" type="button" id="add-emoji"><i class="fas fa-smile-plus"></i></button>
			</div>
		</div>
	</div>


	<div id="dm-tools" class="user-game-actions">
		<div id="tool-select" class="dm-tool">
			<h6>Tools</h6>
			<div id="tool-holder" class="dm-holder">
				<div id="paint-tool" class="tool active"><i class="fas fa-fw fa-paint-roller"></i></div>
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
</div>

<script id="message-template" type="text/template">
	<div class="chat-message">
		<div class="chat-image"><%image%></div>
		<div class="chat-content">
			<div class="chat-info"><span class="name"><%name%></span> <span class="date"><%date%></span></div>
			<div class="chat-text">
				<%message%>
			</div>
		</div>
	</div>
</script>

<script>
jQuery(document).ready(function($){
	var messageTemplate = $('#message-template').html();
	/*
	var newMessage = JBTemplateEngine(messageTemplate, {
		image: 1,
		name: 'James',
		date: '1234567',
		message: 'This is a message'
	});
	*/
});
</script>