<div id="party-chat">
	<button id="toggle-menu" class=""><i class="fas fa-fw fa-chevron-left"></i></button>

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