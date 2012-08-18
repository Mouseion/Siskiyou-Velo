<?php
/*
Simple:Press Admin
Ahah form loader - Forums
$LastChangedDate: 2012-02-06 14:49:15 -0700 (Mon, 06 Feb 2012) $
$Rev: 7881 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

spa_admin_ahah_support();

global $SPSTATUS;
if ($SPSTATUS != 'ok') {
	echo $SPSTATUS;
	die();
}

include_once(SF_PLUGIN_DIR.'/admin/panel-forums/spa-forums-display.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-forums/support/spa-forums-prepare.php');
include_once(SF_PLUGIN_DIR.'/admin/panel-forums/support/spa-forums-save.php');
include_once(SF_PLUGIN_DIR.'/admin/library/spa-tab-support.php');

global $adminhelpfile;
$adminhelpfile = 'admin-forums';
# --------------------------------------------------------------------

# ----------------------------------
# Check Whether User Can Manage Forums
if (!sp_current_user_can('SPF Manage Forums')) {
	if (!is_user_logged_in()) {
		spa_etext('Access denied - are you logged in?');
	} else {
		spa_etext('Access denied - you do not have permission');
	}
	die();
}

if (isset($_GET['loadform'])) {
	spa_render_forums_container($_GET['loadform']);
	die();
}

if (isset($_GET['saveform'])) {
	if ($_GET['saveform'] == 'creategroup') {
		echo spa_save_forums_create_group();
		die();
	}
	if ($_GET['saveform'] == 'createforum') {
		echo spa_save_forums_create_forum();
		die();
	}
	if ($_GET['saveform'] == 'globalperm') {
		echo spa_save_forums_global_perm();
		die();
	}
	if ($_GET['saveform'] == 'removeperms') {
		echo spa_save_forums_remove_perms();
		die();
	}
	if ($_GET['saveform'] == 'mergeforums') {
		echo spa_save_forums_merge();
		die();
	}
	if ($_GET['saveform'] == 'globalrss') {
		echo spa_save_forums_global_rss();
		die();
	}
	if ($_GET['saveform'] == 'globalrssset') {
		echo spa_save_forums_global_rssset();
		die();
	}
	if ($_GET['saveform'] == 'grouppermission') {
		echo spa_save_forums_group_perm();
		die();
	}
	if ($_GET['saveform'] == 'editgroup') {
		echo spa_save_forums_edit_group();
		die();
	}
	if ($_GET['saveform'] == 'deletegroup') {
		echo spa_save_forums_delete_group();
		die();
	}
	if ($_GET['saveform'] == 'editforum') {
		echo spa_save_forums_edit_forum();
		die();
	}
	if ($_GET['saveform'] == 'deleteforum') {
		echo spa_save_forums_delete_forum();
		die();
	}
	if ($_GET['saveform'] == 'addperm') {
		echo spa_save_forums_forum_perm();
		die();
	}
	if ($_GET['saveform'] == 'editperm') {
		echo spa_save_forums_edit_perm();
		die();
	}
	if ($_GET['saveform'] == 'delperm') {
		echo spa_save_forums_delete_perm();
		die();
	}
	if ($_GET['saveform'] == 'customicons') {
		echo spa_save_forums_custom_icons();
		die();
	}
}

die();
?>