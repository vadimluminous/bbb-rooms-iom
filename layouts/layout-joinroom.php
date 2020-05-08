
<?php
  $token = $_REQUEST['token'];

?>
<section id='join-meeting' class='bbb-page'>

<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
<div class='container'>
	<div class='row'>
		<div class='col-lg-12'>
			<h4>Your Name</h4>
  			<input type="text" name="fullName">
  		</div>
  	</div>
 	<div class='row'>
		<div class='col-lg-12'>
		<input type="hidden" name="submitted" id="submitted" value="true" />
		<input type="hidden" name="token" value="<?php echo $token; ?>">
		<input type="hidden" name="action" value="join_meeting">
        <button type="submit"><?php _e('Join Room', 'framework') ?></button>
        <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
  		</div>
  	</div>
</div>
</form>
</section>
