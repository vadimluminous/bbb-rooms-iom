<div class='col-lg-4 one-card'>
	<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>">
		<div id="room-block" data-path="/b/adm-fqa-pcf/update_settings" data-room-access-code="" class="card">
			<div class="cardbody p-1">
				<table class="table table-hover table-vcenter text-wrap table-no-border">
					<tbody class="no-border-top">
						<tr><td>
							<span class="stamp stamp-md bg-primary">
								<i class="fas fa-home"></i>
							</span>
						</td>
						<td>
							<div id="room-name">
								<h4 id="room-name-text" class="m-0 force-text-normal" contenteditable="false"><?php the_title(); ?></h4>
							</div>
							<div id="room-name-editable" style="display: none">
								<input id="room-name-editable-input" class="form-control input-sm w-100 h-4" value="Home Room">
							</div>
						</td>
						<td class="text-right">          
							<div class="item-action dropdown" data-display="static">
								<a href="javascript:void(0)" data-toggle="dropdown" data-display="static" class="icon" aria-expanded="false">
									<i class="fas fa-ellipsis-v"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-md-left">
									<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
										<input type="hidden" name="submitted" id="submitted" value="true" />
										<input type="hidden" name="room_id" value="<?php echo get_the_id(); ?>">
										<input type="hidden" name="action" value="delete_room">
									    <button class='dropdown-item' type="submit"><i class="dropdown-icon far fa-trash-alt"></i><?php _e('Delete', 'framework') ?></button>
									    <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
									</form>
								</div>
							</div>         
						</td>
					</tr></tbody>
				</table>
			</div>
		</div>
	</a>
</div>