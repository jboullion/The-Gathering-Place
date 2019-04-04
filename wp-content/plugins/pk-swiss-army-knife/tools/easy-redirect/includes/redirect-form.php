<?php 
	echo '<div class="wrap"><h2 style="margin-bottom:10px;">Easy Redirect</h2>';
	echo '<div class="tablenav top" align="center">';
	echo '<form action="?page=pk-redirection" method="post" id="baby-search-form">';
	echo '<span style="font-size: 16px;">Search </span>';
	echo '<input id="redirect-search" style="width: 15em;" type="text" value="'.(isset($_POST['esearch'])? $_POST['esearch']:'').'" name="esearch"></input>';
	echo '<select name="ecolumn" style="position: relative; top: -2px;">';
	echo '<option value="source" '.($_POST['ecolumn'] == 'source'? "selected":"").'>Source</option>';
	echo '<option value="target" '.($_POST['ecolumn'] == 'target'? "selected":"").'>Target</option>';
	echo '</select>';
	echo '<input class="button action" type="submit" value="Search" name="search-btn"></input>';
	echo '</form></div>';

	echo '<div id="poststuff">
		<div class="metabox-holder columns-2" id="post-body">
			<div id="post-body-content">';

				if($remove_redirect) {
					echo '<div class="error below-h2" id="message">
								<p>
									Are you sure you would like to delete <strong>'.$remove_redirect->source.'</strong>?<br />
									<a href="?page=pk-redirection&delete_confirm='.$remove_redirect->redirect_id.'" style="font-weight:bold;">Yes, delete '.$remove_redirect->source.'</a> <br />
									<a href="?page=pk-redirection">No, don\'t delete '.$remove_redirect->source.'.</a>
								</p>
							</div>';
				}

				if($removed_redirect) {
					echo '<div class="updated below-h2" id="message">
								<p>
									<strong>'.$removed_redirect->source.'</strong> has been successfully deleted.
								</p>
							</div>';
				}

				if($disabled_redirect) {
					echo '<div class="updated below-h2" id="message">
								<p>
									<strong>'.$disabled_redirect->source.'</strong> redirect has been disabled.
								</p>
							</div>';
				}
				
				if($enabled_redirect) {
					echo '<div class="updated below-h2" id="message">
								<p>
									<strong>'.$enabled_redirect->source.'</strong> redirect has been enabled.
								</p>
							</div>';
				}
				
				$where = '';
				if(! empty($_POST['esearch']) && ! empty($_POST['ecolumn'])){
					$where = 'WHERE '.$_POST['ecolumn'].' LIKE "%'.$_POST['esearch'].'%"';
				}
				
				echo $insert_result ? '<div class="updated below-h2" id="message"><p>Redirection added for: '.$cleaned_source.' to '.$cleaned_target.'</p></div>' : '';
				echo $update_result ? '<div class="updated below-h2" id="message"><p>Redirection update for: '.$cleaned_source.'</p></div>' : '';
				
				
				//paginate vars
				$per_page = 50; $current_page = max(1, $_GET['paged']);
				$total = ceil($wpdb->get_var('SELECT COUNT(redirect_id) FROM '.$redirect_table.' '.$where) / $per_page);
				$pagination_html = '<div style="float:right; padding:0 5px 5px 0;">'.paginate_links(array('base'=>preg_replace('/&paged=\d*$/', '', $_SERVER['REQUEST_URI']).'&paged=%#%', 'format'=>'paged=%#%', 'total'=>$total, 'current'=>$current_page)).'</div><div class="clearfix"></div>';
				
				
				//get the redirection list
				$query = 'SELECT * FROM '.$redirect_table.' '.$where.' ORDER BY redirect_id DESC LIMIT '.(($current_page - 1) * $per_page).', '.$per_page;

				$result = $wpdb->get_results($query, ARRAY_A);

				//if any exist
				if(count($result)) {
					$site_url = get_site_url();
					echo $pagination_html;
					echo '<table class="wp-list-table widefat fixed posts">
								<thead>
									<tr>
										<th>Vanity</th>
										<th>Destination</th>
										<th style="width: 50px;">Type</th>
										<th style="width: 50px;">Hits</th>
										<th>Last Hit</th>
										<th style="text-align: center; width: 80px;">Enabled</th>
										<th style="text-align: center; width: 50px;">Delete</th>
										<th style="text-align: center; width: 50px;">Edit</th>
									</tr>
								</thead>';
					foreach($result as $key => $row) {
						$baby_img_url = $upload_dir['baseurl'].'/baby_submission/'.$row['baby_image'];
						echo '<tr'.(++$i % 2 ? ' class="alternate"' : '').'>
									<td>'.$row['source'].'</td>
									<td><a href="'.$site_url.$row['target'].'" target="_blank">'.$row['target'].'</a></td>
									<td>'.$row['redirect_type'].'</td>
									<td>'.$row['hits'].'</td>
									<td>'.(strtotime($row['last_hit'])?date('m-d-y @ g:ia', strtotime($row['last_hit'])):'').'</td>
									<td style="text-align:center;">';
									
						if($row['enabled'] == 1){
							echo '<a href="?page=pk-redirection&disable_id='.$row['redirect_id'].'">Disable</a>';
						}else{
							echo '<a href="?page=pk-redirection&enable_id='.$row['redirect_id'].'">Enable</a>';
						}
										
						echo '		</td>
									<td style="text-align:center;">
										<a href="?page=pk-redirection&delete_id='.$row['redirect_id'].'">Delete</a>
									</td>
									<td style="text-align:center;">
										<a href="#TB_inline?width=280&height=200&inlineId=edit-modal" data-key="'.$key.'" class="thickbox">Edit</a>
									</td>
								</tr>';
					}
					echo '</table>'.$pagination_html.'';
					
					$js_array = json_encode($result);
					echo '<script type="text/javascript">
							var redirect_array = '. $js_array . ';
						</script>';
				}
				else
				{
					echo '<p>No Redirects found.</p>';
				}
	echo '</div><!-- /post-body-content -->'
	?>	
		<div class="postbox-container" id="postbox-container-1">
			<div class="postbox">
				<h3 style="cursor:default;"><span>Add Redirect</span></h3>
				<div class="inside">
					<form action="" method="post" id="add-redirect">
						<table>
							<tr>
								<td valign="top"><label>Vanity</label></td>
								<td valign="top">
									<input type="text" name="source" id="source" />
									<small>Example: newspaper-ad</small>
								</td>
							</tr>
							<tr>
								<td valign="top"><label>Destination</label></td>
								<td valign="top">
									<input type="text" name="target" id="target" />
									<small>Example: /target/url/for/ad/ </small>
								</td>
								
							</tr>
							<tr>
								<td valign="top"><label>Type</label></td>
								<td valign="top">
									<select name="redirect_type">
										<option value="301">Permanent 301</option>
										<option value="302">Temporary 302</option>
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" name="submit" class="button button-primary" value="Add Redirect" /></td>
							</tr>
						</table>
						
					</form>
				</div>
			</div>
			<div class="postbox">
				<h3 style="cursor:default;"><span>Upload CSV</span></h3>
				<div class="inside">
					<p>CSV should be in the format of: <br />Source, Target, Type</p>
					<p>ex: /test, /testing, 301</p>
					<form enctype="multipart/form-data" action="" method="post">
						<input type="file" name="csv" id="csv" /><br /><br />
						<label><input type="checkbox" name="use_first" value="1" checked="checked" /> Use first row?</label><br />
						<span style="font-size: 80%">If checked the first row of the CSV will be used, otherwise it will be skipped.</span>
						<br /><br />
						<input type="submit" name="submit" class="button button-primary" value="Upload" />
					</form>
				</div>
			</div>
		</div>
		<style>
			#TB_window { max-width: 300px !important; margin-left: -150px !important; }
		</style>
		<script>
			jQuery(document).ready(function($){
				$('.thickbox').click(function(e){
					var resultKey = $(this).attr('data-key');
					$('#edit_source').val(redirect_array[resultKey].source); 
					$('#edit_target').val(redirect_array[resultKey].target);
					$('#edit_redirect_type').val(redirect_array[resultKey].redirect_type);
					$('#edit_redirect_id').val(redirect_array[resultKey].redirect_id);
				})
			});
		</script>
		<div id="edit-modal" style="display:none;">
		    <form action="" method="post" id="update-redirect">
				<table>
					<tr>
						<td valign="top"><label>Source URL</label></td>
						<td valign="top">
							<input type="text" name="edit[source]" id="edit_source" value="" /><br />
							<small>Example: /newspaper-ad/</small>
						</td>
					</tr>
					<tr>
						<td valign="top"><label>Target</label></td>
						<td valign="top">
							<input type="text" name="edit[target]" id="edit_target" value="" /><br />
							<small>Example: /target/url/for/ad/ </small>
						</td>
						
					</tr>
					<tr>
						<td valign="top"><label>Type</label></td>
						<td valign="top">
							<select name="edit[redirect_type]" id="edit_redirect_type">
								<option value="301">Permanent 301</option>
								<option value="302">Temporary 302</option>
							</select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="hidden" name="edit[redirect_id]" id="edit_redirect_id" value="0" />
							<input type="submit" name="submit" class="button button-primary" value="Update Redirect" />
						</td>
					</tr>
				</table>
			</form>
		</div>
	<?php
	echo '</div><!-- /post-body --><br class="clear"></div></div>';