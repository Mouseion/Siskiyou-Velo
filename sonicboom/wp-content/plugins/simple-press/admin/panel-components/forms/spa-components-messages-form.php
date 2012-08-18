<?php
/*
Simple:Press
Admin Components Custom Messages Form
$LastChangedDate: 2011-07-18 06:05:28 -0700 (Mon, 18 Jul 2011) $
$Rev: 6712 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function spa_components_messages_form() {
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#sfmessagesform').ajaxForm({
		target: '#sfmsgspot',
		success: function() {
			jQuery('#sfmsgspot').fadeIn();
			jQuery('#sfmsgspot').fadeOut(6000);
		}
	});
});
</script>
<?php

	$sfcomps = spa_get_messages_data();

    $ahahURL = SFHOMEURL.'index.php?sp_ahah=components-loader&amp;sfnonce='.wp_create_nonce('forum-ahah').'&amp;saveform=messages';
?>
	<form action="<?php echo $ahahURL; ?>" method="post" id="sfmessagesform" name="sfmessages">
	<?php echo sp_create_nonce('forum-adminform_messages'); ?>
<?php

	spa_paint_options_init();

#== CUSTOM MESSAGES Tab ============================================================

	spa_paint_open_tab(spa_text('Components').' - '.spa_text('Custom Messages'));
		spa_paint_open_panel();
			spa_paint_open_fieldset(spa_text('Custom Message Above Editor'), true, 'editor-message');
				$submessage = spa_text('Text you enter here will be displayed above the editor (new topic and/or new post)');
				spa_paint_wide_textarea(spa_text('Custom message'), 'sfpostmsgtext', $sfcomps['sfpostmsgtext'], $submessage, 4);
				spa_paint_checkbox(spa_text('Display for new topic'), 'sfpostmsgtopic', $sfcomps['sfpostmsgtopic']);
				spa_paint_checkbox(spa_text('Display for new post'), 'sfpostmsgpost', $sfcomps['sfpostmsgpost']);
			spa_paint_close_fieldset();
		spa_paint_close_panel();

		do_action('sph_components_messages_left_panel');

	spa_paint_tab_right_cell();

		spa_paint_open_panel();
			spa_paint_open_fieldset(spa_text('Custom Intro Text in Editor'), true, 'editor-intro');
				$submessage = spa_text('Text you enter here will be displayed inside the editor (new topic only)');
				spa_paint_wide_textarea(spa_text('Custom intro message'), 'sfeditormsg', $sfcomps['sfeditormsg'], $submessage, 4);
			spa_paint_close_fieldset();
		spa_paint_close_panel();

		spa_paint_close_panel();

		do_action('sph_components_messages_right_panel');

	spa_paint_close_tab();

?>
	<div class="sfform-submit-bar">
	<input type="submit" class="sfform-panel-button" id="saveit" name="saveit" value="<?php spa_etext('Update Custom Messages Component'); ?>" />
	</div>
	</form>
<?php
}
?>