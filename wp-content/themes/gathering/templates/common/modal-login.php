<div id="login-modal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title">Login</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<div class="modal-body">
			<form id="login" action="login" method="post">
				<p class="status"></p>
				<div class="form-group">
					<label for="username">Username</label>
					<input id="username" class="form-control" type="text" name="username">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input id="password" class="form-control" type="password" name="password">
				</div>
				<div class="form-group">
					<a class="lost" href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a>
				</div>
				
				<input class="btn btn-primary" type="submit" value="Login" name="submit">
				<?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
			</form>
			</div>
		</div>
	</div>
</div>
<script>
jQuery(document).ready(function($) {

	// Perform AJAX login on form submit
	$('form#login').on('submit', function(e){
		$('form#login p.status').show().text(ajax_login_object.loadingmessage);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_login_object.ajaxurl,
			data: { 
				'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
				'username': $('form#login #username').val(), 
				'password': $('form#login #password').val(), 
				'security': $('form#login #security').val() },
			success: function(data){
				$('form#login p.status').text(data.message);
				if (data.loggedin == true){
					document.location.href = ajax_login_object.redirecturl;
				}
				//Redirect to party screen
			}
		});
		e.preventDefault();
	});

});
</script>