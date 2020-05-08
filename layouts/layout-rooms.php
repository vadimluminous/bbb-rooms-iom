<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package grassywp
 */


    $user = wp_get_current_user();
get_header(); ?>
</div>
</div>
<!-- Main content Start -->

<div class="main-content"> 
  <!-- Breadcrumbs Start -->
<?php
if ( in_array( 'silver', (array) $user->roles ) || in_array( 'gold', (array) $user->roles ) || in_array( 'platinum', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) ) { 
?>
<section id='bbb-room-single'>
	<div class='container'>
		<div class='row row-eq-height' id='startarea'>
			<div class='col-lg-8'>
				<h1><?php echo the_title(); ?></h1>
				<p class='invite'>Invite Participants</p>
				<div class='row'>
					<div class='col-lg-7'>
						<div class='room-link'>
							<span><i class="fas fa-link"></i></span>
							<input type="text" value="<?php echo do_shortcode('[roomlink]');?>" id="myInput">
						</div>
					</div>
					<div class='col-lg-5'>
						<div class='row'>
							<div class='col-sm-6'>
								<button id='copybutton' onclick="myFunction()" onmouseout="outFunc()">
								  	<i class="fas fa-copy"></i> Copy
								</button>
							</div>
							<div class='col-sm-6'></div>
						</div>
					</div>
				</div>
			</div>
			<div class='col-lg-4 force-bottom'>
				<?php if( is_single()) { ?>
				<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
			  		<input type="text" name="fullName" placeholder='Your Name' required>
					<input type="hidden" name="submitted" id="submitted" value="true" />
					<input type="hidden" name="token" value="<?php echo the_id(); ?>">
					<input type="hidden" name="clientpage" value="true">
					<input type="hidden" name="action" value="join_meeting">
			        <button type="submit" class='start-button'><?php _e('Start', 'framework') ?></button>
			        <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
				</form>
				<?php } ?>
			</div>
		</div>
		<div class='row mt40'>
			<?php echo do_shortcode('[recentposts]'); ?>
			<div class='col-lg-4' id="show">
				<div class='cardbody createroom' id='create-room-block'>
					<div class='cardinterior row'>
						<div class='col-lg-5'>
							<div class='card-icon'>
								<div class='stamp-md'>
									<i class="fas fa-plus"></i>
								</div>
							</div>
						</div>
						<div class='col-lg-7'>
							<div class='card-data'>
								Create Room
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section id='recordings'>
	<?php echo do_shortcode('[recordings]'); ?>
</section>
</div>
<div id='createRoomModal' class='hideform'>
	<div class='modal-dialog-centered'>
		<div class="createForm">
			<h3>Create New Room</h3>
        	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" id="primaryPostForm" method="post">
		    	<div class='input-icon mb-2'>
		    		<span class="input-icon-addon">
                		<i class="fas fa-chalkboard-teacher"></i>
              		</span>
		        	<input id='create-room-name meetingName' class='form-control text-center' type="text" name="meetingName" value="<?php if ( isset( $_POST['meetingName'] ) ) echo $_POST['meetingName']; ?>" placeholder="Enter a room name.." required>
		        </div>
		        <input type="hidden" name="submitted" id="submitted" value="true" />
                <input type="hidden" name="action" value="create_meeting">
        		<button type="submit"><?php _e('Create Room', 'framework') ?></button>
		        <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
		    </form>
		</div>
	</div>
</div>
<div class="modal-backdrop hideform"></div>

<?php } else { ?>
<section id='bbb-room-single not-logged'>
	<div class='container mb-2'>
		<div class='row'>
			<div class='col-lg-4'>
				<h1>Rooms</h1>
				<p>To create a virtual classroom, please login or register.</p>
				<div class='row'>
					<div class='col-lg-6'>
						<h4><a href='#'>Login</a></h4>
					</div>
					<div class='col-lg-6'>
						<h4><a href='#'>Register</a></h4>
					</div>
				</div>
			</div>
			<div class='col-lg-8'><img src='https://getvirtualclass.com/wp-content/uploads/2020/05/working-man-is-typing1.jpg'></div>

		</div>
	</div>
</div>
<?php } ?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
function myFunction() {
	var copyText = document.getElementById("myInput");
	copyText.select();
	copyText.setSelectionRange(0, 99999);
	document.execCommand("copy");
    copyText.setSelectionRange(0, 0);
    copyText.blur(); 
	  
	var tooltip = document.getElementById("copybutton");
	tooltip.innerHTML = "<i class='fas fa-check'></i> Copied";
	setTimeout(function() {
	    tooltip.innerHTML = "<i class='fas fa-copy'></i> Copy";
	}, 3000);
}

$( "#show" ).click(function () {
    $( "#createRoomModal" ).slideDown( "slow" );
	$('.modal-backdrop').fadeIn( "slow" );
});



$( document ).on( 'keydown', function ( e ) {
    if ( e.keyCode === 27 ) { // ESC
        $( "#createRoomModal" ).slideUp( "slow" );
        $('.modal-backdrop').fadeOut( "slow");
    }
});

var mouse_is_inside = false;

$(document).ready(function()
{
    $('.createForm').hover(function(){ 
        mouse_is_inside=true; 
    }, function(){ 
        mouse_is_inside=false; 
    });

    $("body").mouseup(function(){ 
        if(! mouse_is_inside) $('#createRoomModal').slideUp( "slow" );
        if(! mouse_is_inside) $('.modal-backdrop').fadeOut( "slow");

    });
});

</script>
<?php
get_footer();
