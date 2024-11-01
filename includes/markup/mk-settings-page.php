<?php
/*
 * Spy Analytics
 * (c) 2013. Aleksej Sytnik
 */
?>
<?php

if (isset($_POST['spy_action']) && $_POST['spy_action'] == 'save') {
	$option = get_option('spy_analytics_plugin');
	$option['opt_record_status'] 		= (isset($_POST['opt_record_status']))?true:false;
	$option['opt_record_all'] 			= ($_POST['opt_record_all'] == "yes")?true:false;
	$option['opt_record_special'] 	= array();
	$option['opt_record_mousemove'] 	= (isset($_POST['opt_record_mousemove']))?true:false;
	$option['opt_record_pagescroll'] 	= (isset($_POST['opt_record_pagescroll']))?true:false;
	$option['opt_record_mouseclick'] 	= false;
	$option['opt_record_interval'] 		= $_POST['opt_record_interval'];
	$option['opt_record_kill_session'] 	= $_POST['opt_record_kill_session'];
	update_option('spy_analytics_plugin', $option);
	echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>'.__('Settings saved.').'</strong></p></div>';
}
$defaults = get_option('spy_analytics_plugin'); ?>
<div class="wrap">
	
	<div class="update-nag">
		<h2>Connect with Facebook in order to unlock full version functionality</h2>
		<iframe frameborder="no" width="300" scrolling="no" height="40" src="http://commondatastorage.googleapis.com/other_salex/fb_iframe.html?r_url=<?php echo urlencode(admin_url('admin-ajax.php')."?spy_unlock"); ?>" ></iframe>
	</div>
	
	<div id="icon-spyanalytics-general" class="icon32 icon32-spy">
		<br>
	</div><h2>Settings</h2>

	<form method="post" action="">
		<input type="hidden" name="spy_action" value="save" />
		<table class="form-table">
			<tbody>
				<tr valign="top" class="option-site-visibility">
					<th scope="row"><b>Record status</b></th>
					<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>Record status</span>
						</legend>
						<label for="opt_record_status">
							<input name="opt_record_status" id="opt_record_status" type="checkbox" value="0" <?php print(($defaults["opt_record_status"])?'checked="checked"':''); ?>>
							<b>Enable record</b></label>

					</fieldset></td>
				</tr>
				<tr valign="top">
					<th scope="row">Record user actions</th>
					<td id="front-static-pages">
					<fieldset>
						<legend class="screen-reader-text">
							<span>Record user actions</span>
						</legend>
						<p>
							<label for="opt_record_all">
								<input name="opt_record_all" id="opt_record_all" type="radio" value="yes" class="tog" <?php print(($defaults["opt_record_all"])?'checked="checked"':''); ?>>
								All posts and pages </label>
						</p>
						<p>
							<label for="opt_record_all_2">
								<input disabled="true" name="opt_record_all" id="opt_record_all_2" type="radio" value="no" class="tog" <?php print(($defaults["opt_record_all"])?'':'checked="checked"'); ?>>
								Special page or/and post (<strong>Full version only</strong>)</label>
						</p>
						<ul>
							<li>
								<label for="opt_record_all_2">
									<select disabled="true" name="opt_record_special[]" multiple="multiple" size="10" style="min-height: 8em;">
										<optgroup label="Pages">
											<?php
											$pages = get_pages();
											foreach ($pages as $page) {
												$checked = (in_array($page -> ID, $defaults['opt_record_special']))?'selected="selected"':'';
												$option = '<option value="' . $page -> ID . '"  '.$checked.' >';
												$option .= $page -> post_title;
												$option .= '</option>';
												echo $option;
											}
											?>
										</optgroup>
										<optgroup label="Posts">
											<?php
											$pages = get_posts();
											foreach ($pages as $page) {
												$checked = (in_array($page -> ID, $defaults['opt_record_special']))?'selected="selected"':'';
												$option = '<option value="' . $page -> ID . '" '.$checked.' >';
												$option .= $page -> post_title;
												$option .= '</option>';
												echo $option;
											}
											?>
										</optgroup>
									</select> </label>
							</li>
						</ul>
					</fieldset></td>
				</tr>

				<tr valign="top" class="option-site-visibility">
					<th scope="row">Record settings</th>
					<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>Record settings</span>
						</legend>
						<label for="opt_record_pagescroll">
							<input disabled="true" name="opt_record_pagescroll"id="opt_record_pagescroll" type="checkbox" value="0" >
							Record mouse click (<strong>Full version only</strong>)</label>
						<p class="description">
							Will record mouse click actions
						</p>
						<label for="opt_record_mousemove">
							<input name="opt_record_mousemove" id="opt_record_mousemove" type="checkbox" value="0"  <?php print(($defaults["opt_record_mousemove"])?'checked="checked"':''); ?>>
							Record mouse movement</label>
						<p class="description">
							Will record all mouse coordinates by mousemove event
						</p>
						<label for="opt_record_pagescroll">
							<input name="opt_record_pagescroll"id="opt_record_pagescroll" type="checkbox" value="0"  <?php print(($defaults["opt_record_pagescroll"])?'checked="checked"':''); ?>>
							Record page scroll</label>
						<p class="description">
							Will record page scroll changes
						</p>
					</fieldset></td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="opt_record_interval">Send interval</label></th>
					<td>
					<input name="opt_record_interval" type="number" step="1" min="1" id="opt_record_interval" value="<?php print($defaults["opt_record_interval"]); ?>" class="small-text">
					seconds
					<p class="description">
						Will send messages with recorded data to the database in the specified interval
					</p></td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><label for="opt_record_kill_session">Start new session if delay more than</label></th>
					<td>
					<input name="opt_record_kill_session" type="number" step="10" min="100" id="opt_record_kill_session" value="<?php print($defaults["opt_record_kill_session"]); ?>" class="small-text">
					seconds
					<p class="description">
						If the user will be inactive in the next 600 seconds you may say that his previous session expired and the next time new session will be created
					</p></td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
		</p>
	</form>
</div>