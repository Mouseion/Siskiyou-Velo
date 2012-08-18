<?php
/*
Simple:Press
Admin Components Login Form
$LastChangedDate: 2012-03-28 04:31:45 -0700 (Wed, 28 Mar 2012) $
$Rev: 8241 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_components_login_form() {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sfloginform').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
});
</script>
<?php

	$sfcomps = spa_get_login_data();

    $ahahURL = SFHOMEURL.'index.php?sp_ahah=components-loader&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;saveform=login';
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfloginform" name="sflogin">
	<?php echo sp_create_nonce('forum-adminform_login'); ?>
<?php

	spa_paint_options_init();

#== LOGIN Tab ============================================================

	spa_paint_open_tab(spa_text('Components').' - '.spa_text('Login And Registration'));
		spa_paint_open_panel();
			if (false == get_option('users_can_register')) {
				spa_paint_open_panel();
					spa_paint_open_fieldset(spa_text('Member Registrations'), true, 'no-login', false);
						echo '<div class="sfoptionerror">';
						spa_etext('Your site is currently not set to allow users to register. Click on the help icon for details of how to turn this on');
						echo '</div>';
					spa_paint_close_fieldset(false);
				spa_paint_close_panel();
			}

			spa_paint_open_panel();
				spa_paint_open_fieldset(spa_text('User Registration'), true, 'user-registration');
					spa_paint_checkbox(spa_text('Use spam tool on registration form'), 'sfregmath', $sfcomps['sfregmath']);
				spa_paint_close_fieldset();
			spa_paint_close_panel();

			spa_paint_open_panel();
				$submessage='';
				spa_paint_open_fieldset(spa_text('Login/Registration Redirects'), true, 'login-registration-urls');
					spa_paint_wide_textarea(spa_text('Login redirect'), 'sfloginurl', $sfcomps['sfloginurl'], $submessage);
					spa_paint_wide_textarea(spa_text('Logout redirect'), 'sflogouturl', $sfcomps['sflogouturl'], $submessage);
					spa_paint_wide_textarea(spa_text('Registration redirect'), 'sfregisterurl', $sfcomps['sfregisterurl'], $submessage);
					spa_paint_wide_textarea(spa_text('Login URL in new user email'), 'sfloginemailurl', $sfcomps['sfloginemailurl'], $submessage);
				spa_paint_close_fieldset();
			spa_paint_close_panel();

			spa_paint_open_panel();
				spa_paint_open_fieldset(spa_text('Tracking Timeout'), true, 'tracking-timeout');
			        spa_paint_input(spa_text('Tracking Timeout (minutes)'), 'sptimeout', $sfcomps['sptimeout'], false, true);
				spa_paint_close_fieldset();
			spa_paint_close_panel();

			do_action('sph_components_login_left_panel');

			spa_paint_tab_right_cell();

			spa_paint_open_panel();
				spa_paint_open_fieldset(spa_text('RPX 3rd Party Login'), true, 'rpx-login');
					spa_paint_checkbox(spa_text('Enable RPX support'), 'sfrpxenable', $sfcomps['sfrpxenable']);
        			echo '<tr><td colspan="2">';
        			spa_etext('Please enter your RPX API key. If you haven\'t yet created one, please create one at').' <a href="https://rpxnow.com" target="_blank">RPX</a>';
        			echo '</td></tr>';
			        spa_paint_input(spa_text('RPX API key'), 'sfrpxkey', $sfcomps['sfrpxkey'], false, true);
    				$submessage=spa_text('Force a redirect to a specific page on RPX login.  Leave blank to have SPF/RPX determine redirect location');
					spa_paint_wide_textarea(spa_text('URL to redirect to after RPX login'), 'sfrpxredirect', $sfcomps['sfrpxredirect'], $submessage);
				spa_paint_close_fieldset();
			spa_paint_close_panel();

			spa_paint_open_panel();
				spa_paint_open_fieldset(spa_text('Sneak Peek Statement'), true, 'sneak-peek');
					$submessage=spa_text('If you are allowing guests to view forum and topic lists, but not see the actual Posts, this message is displayed to encourage them to sign up');
					spa_paint_wide_textarea(spa_text('Sneak peek statement'), 'sfsneakpeek', $sfcomps['sfsneakpeek'], $submessage);
    				$submessage=spa_text('Force a redirect to a specific page instead of displaying the sneak peek message.');
					spa_paint_wide_textarea(spa_text('URL to redirect to for sneak peek'), 'sfsneakredirect', $sfcomps['sfsneakredirect'], $submessage);
				spa_paint_close_fieldset();
			spa_paint_close_panel();

			spa_paint_open_panel();
				spa_paint_open_fieldset(spa_text('Admin view statement'), true, 'admin-view');
					$submessage=spa_text('If you are inhibiting usergroups from seeing admin posts, this message is displayed to them');
					spa_paint_wide_textarea(spa_text('Admin view statement'), 'sfadminview', $sfcomps['sfadminview'], $submessage);
				spa_paint_close_fieldset();
			spa_paint_close_panel();
		spa_paint_close_panel();

		do_action('sph_components_login_right_panel');
	spa_paint_close_tab();

?>
	<div class="sfform-submit-bar">
	<input type="submit" class="sfform-panel-button" id="saveit" name="saveit" value="<?php spa_etext('Update Login and Registration Component'); ?>" />
	</div>
	</form>
<?php
}
?>