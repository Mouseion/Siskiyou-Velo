<?php
/*
Simple:Press Admin
Ahah form loader - Profiles
$LastChangedDate: 2011-07-17 14:08:22 -0700 (Sun, 17 Jul 2011) $
$Rev: 6704 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

spa_admin_ahah_support();

global $SPSTATUS;
if ($SPSTATUS != 'ok') {
	echo $SPSTATUS;
	die();
}

include_once(SF_PLUGIN_DIR.'/admin/panel-profiles/spa-profiles-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-profiles/support/spa-profiles-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-profiles/support/spa-profiles-save.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');

global $adminhelpfile;
$adminhelpfile = 'admin-profiles';
# --------------------------------------------------------------------

# ----------------------------------
# Check Whether User Can Manage Profiles
if (!sp_current_user_can('SPF Manage Profiles')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

if (isset($_GET['loadform'])) {
	spa_render_profiles_container($_GET['loadform']);
	die();
}

if (isset($_GET['saveform'])) {
	switch($_GET['saveform']) {
		case 'global':
			echo spa_save_global_data();
			break;

		case 'tabs-menus':
			echo spa_save_tabs_menus_data();
			break;

		case 'options':
			echo spa_save_options_data();
			break;

		case 'avatars':
			echo spa_save_avatars_data();
			break;
	}
}

die();

?>