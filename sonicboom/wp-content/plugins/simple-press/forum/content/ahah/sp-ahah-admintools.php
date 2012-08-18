<?php
/*
Simple:Press
Edit Tools - Move Topic/Move Post
$LastChangedDate: 2012-05-20 04:45:45 -0700 (Sun, 20 May 2012) $
$Rev: 8551 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

sp_forum_api_support();

# get out of here if no action specified
if (empty($_GET['action'])) die();
$action = sp_esc_str($_GET['action']);

if ($action == 'move-topic') sp_move_topic_popup();
if ($action == 'move-post') sp_move_post_popup();
if ($action == 'edit-title') sp_edit_title_popup();
if ($action == 'reassign') sp_reassign_post_popup();
if ($action == 'properties') sp_show_properties();
if ($action == 'sort-forum') sp_forum_sort_order();
if ($action == 'sort-topic') sp_topic_sort_order();

die();

function sp_move_topic_popup() {
	$topicid = sp_esc_int($_GET['topicid']);
	$forumid = sp_esc_int($_GET['forumid']);
	if (!sp_get_auth('move_topics', $forumid)) {
		if (!is_user_logged_in()) {
			sp_etext('Access denied - are you logged in?');
		} else {
			sp_etext('Access denied - you do not have permission');
		}
		die();
	}

	$thistopic = spdb_table(SFTOPICS, "topic_id=$topicid", 'row');
	$thisforum = spdb_table(SFFORUMS, "forum_id=$forumid", 'row');
    if (empty($thistopic) || empty($thisforum)) die();

	include_once(SPAPI.'sp-api-common-display.php');
?>
	<div id="spMainContainer" class="spForumToolsPopup">
		<div class="spForumToolsHeader">
			<div class="spForumToolsHeaderTitle"><?php sp_etext('Select new forum for this topic').':'; ?></div>
			<div class="spForumToolsHeaderTitle"><?php echo sp_filter_title_display($thistopic->topic_name); ?></div>
		</div>
		<form action="<?php echo sp_build_url($thisforum->forum_slug, '', 1, 0); ?>" method="post" name="movetopicform">
			<input type="hidden" name="currenttopicid" value="<?php echo $topicid; ?>" />
			<input type="hidden" name="currentforumid" value="<?php echo $forumid; ?>" />
			<div class="spCenter">
				<?php echo sp_render_group_forum_select(false, false, true, true, sp_text('Select forum'), 'forumid', 'spSelect');	?><br /><br />
				<input type="submit" class="spSubmit" name="maketopicmove" value="<?php sp_etext('Move Topic to Selected Forum') ?>" />
				<input type="button" class="spSubmit" name="cancel" value="<?php sp_etext('Cancel') ?>" onclick="jQuery('#dialog').dialog('close');" />
			</div>
		</form>
	</div>
<?php
}

function sp_edit_title_popup() {
	global $spThisUser, $sfvars;

	$topicid = sp_esc_int($_GET['topicid']);
	$forumid = sp_esc_int($_GET['forumid']);
	$userid  = sp_esc_int($_GET['userid']);
	$thistopic = spdb_table(SFTOPICS, "topic_id=$topicid", 'row');

	if (!(sp_get_auth('edit_own_topic_titles', $forumid) && $userid == $spThisUser->ID) && !sp_get_auth('edit_any_topic_titles', $forumid)) {
		if (!is_user_logged_in()) {
			sp_etext('Access denied - are you logged in?');
		} else {
			sp_etext('Access denied - you do not have permission');
		}
		die();
	}
	$thisforum = spdb_table(SFFORUMS, "forum_id=$thistopic->forum_id", 'row');

	$out = '<div id="spMainContainer" class="spForumToolsPopup">';
    $page = (isset($sfvars['page'])) ? $sfvars['page'] : 1;
	$out.= '<form action="'.sp_build_url($thisforum->forum_slug, '', $page, 0).'" method="post" name="edittopicform">';
	$out.= '<input type="hidden" name="tid" value="'.$thistopic->topic_id.'" />';
    $out.= '<div class="spCenter">';
	$out.= '<div class="spHeaderName">'.sp_text('Topic Title').':</div>';
	$out.= '<div><textarea class="spControl" name="topicname" rows="2">'.esc_textarea($thistopic->topic_name).'</textarea></div>';
	$out.= '<div class="spHeaderName">'.sp_text('Topic Slug').':</div>';
	$out.= '<div><textarea class="spControl" name="topicslug" rows="2">'.esc_textarea($thistopic->topic_slug).'</textarea></div>';
    $out = apply_filters('sph_topic_title_edit' , $out, $thistopic);
	$out.= '<div class="spCenter"><br />';
	$out.= '<input type="submit" class="spSubmit" name="edittopic" value="'.sp_text('Save').'" />';
	$out.= '<input type="button" class="spSubmit" name="cancel" value="'.sp_text('Cancel').'" onclick="jQuery(\'#dialog\').dialog(\'close\');" />';
	$out.= '</div>';
    $out.= '</div>';
	$out.= '</form>';
	$out.= '</div>';
    echo $out;
}

function sp_reassign_post_popup() {
	$thispost = sp_esc_int($_GET['pid']);
	$thisuser = sp_esc_int($_GET['uid']);
    $thistopic = sp_esc_int($_GET['id']);
    if (empty($thispost) || empty($thistopic)) die();

	$thistopic = spdb_table(SFTOPICS, "topic_id=$thistopic", 'row');
	if (!sp_get_auth('reassign_posts', $thistopic->forum_id)) {
		if (!is_user_logged_in()) {
			sp_etext('Access denied - are you logged in?');
		} else {
			sp_etext('Access denied - you do not have permission');
		}
		die();
	}

	$thisforum = spdb_table(SFFORUMS, "forum_id=$thistopic->forum_id", 'row');
?>
	<div id="spMainContainer" class="spForumToolsPopup">
		<div class="spForumToolsHeader">
			<div class="spForumToolsHeaderTitle"><?php echo sp_text('Reassign post to new user').' ('.sp_text('current ID').': '.$thisuser.')'; ?></div>
		</div>
		<form action="<?php echo sp_build_url($thisforum->forum_slug, $thistopic->topic_slug, 1, 0); ?>" method="post" name="reassignpostform">
            <div class="spCenter">
    			<input type="hidden" name="postid" value="<?php echo $thispost; ?>" />
    			<input type="hidden" name="olduserid" value="<?php echo $thisuser; ?>" />
    			<?php sp_etext('New user ID'); ?>
    			<input type="text" class="spControl" size="80" name="newuserid" value="" /><br /><br />
    			<input type="submit" class="spSubmit" name="makepostreassign" value="<?php sp_etext('Reassign Post') ?>" />
    			<input type="button" class="spSubmit" name="cancel" value="<?php sp_etext('Cancel') ?>" onclick="jQuery('#dialog').dialog('close');" />
            </div>
		</form>
	</div>
<?php
}

function sp_show_properties() {
	global $spThisUser;

    $forumid = sp_esc_int($_GET['forum']);
    $topicid = sp_esc_int($_GET['topic']);
    if (empty($forumid) || empty($topicid)) die();

	$thistopic = spdb_table(SFTOPICS, "topic_id=$topicid", 'row');

	if (!$spThisUser->admin && !$spThisUser->moderator) {
		if (!is_user_logged_in()) {
			sp_etext('Access denied - are you logged in?');
		} else {
			sp_etext('Access denied - you do not have permission');
		}
		die();
	}
	$thisforum = spdb_table(SFFORUMS, "forum_id=$forumid", 'row');

	if (isset($_GET['post'])) {
		$groupid = sp_esc_int($thisforum->group_id);
		$thisgroup = spdb_table(SFGROUPS, "group_id=$groupid", 'row');
	} else {
        $groupid = sp_esc_int($_GET['group']);
        if (empty($groupid)) die();
		$thisgroup = spdb_table(SFGROUPS, "group_id=$groupid", 'row');
	}

	$posts = spdb_table(SFPOSTS, "topic_id=$thistopic->topic_id", '', 'post_id');
	if ($posts) {
		$first = $posts[0]->post_id;
		$last  = $posts[count($posts) - 1]->post_id;
	}

	# set timezone onto the started date
	$topicstart = sp_apply_timezone($thistopic->topic_date);

?>
	<div id="spMainContainer">
	<table class="spPopupTable">
		<tr><td class="spLabel" width="35%"><?php sp_etext('Group ID'); ?></td><td colspan="2" class="spLabel"><?php echo $thisgroup->group_id; ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Group Title'); ?></td><td colspan="2" class="spLabel"><?php echo sp_filter_title_display($thisgroup->group_name); ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Forum ID'); ?></td><td class="spLabel"><?php echo $thisforum->forum_id; ?></td><td class="sfdata"><?php echo sp_rebuild_forum_form($thisforum->forum_id, $thistopic->topic_id, $thisforum->forum_slug, $thistopic->topic_slug); ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Forum Title'); ?></td><td colspan="2" class="spLabel"><?php echo sp_filter_title_display($thisforum->forum_name); ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Forum Slug'); ?></td><td colspan="2" class="spLabel"><?php echo $thisforum->forum_slug; ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Topics in Forum'); ?></td><td colspan="2" class="spLabel"><?php echo $thisforum->topic_count; ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Topic ID'); ?></td><td class="spLabel"><?php echo $thistopic->topic_id; ?></td><td class="sfdata"><?php echo sp_rebuild_topic_form($thisforum->forum_id, $thistopic->topic_id, $thisforum->forum_slug, $thistopic->topic_slug); ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Topic Title'); ?></td><td colspan="2" class="spLabel"><?php echo sp_filter_title_display($thistopic->topic_name); ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Topic Slug'); ?></td><td colspan="2" class="spLabel"><?php echo $thistopic->topic_slug; ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Posts in Topic'); ?></td><td colspan="2" class="spLabel"><?php echo $thistopic->post_count; ?></td></tr>

		<tr><td class="spLabel"><?php sp_etext('Topic Started'); ?></td><td colspan="2" class="spLabel"><?php echo $topicstart; ?></td></tr>

		<tr><td class="spLabel"><?php sp_etext('First Post ID'); ?></td><td colspan="2" class="spLabel"><?php echo $first; ?></td></tr>
		<tr><td class="spLabel"><?php sp_etext('Last Post ID'); ?></td><td colspan="2" class="spLabel"><?php echo $last; ?></td></tr>
<?php
		if (isset($_GET['post'])) {
			$postid = sp_esc_int($_GET['post']);
			$post = spdb_table(SFPOSTS, "post_id=$postid");
?>
			<tr><td class="spLabel"><?php sp_etext('This Post ID'); ?></td><td colspan="2" class="spLabel"><?php echo $postid; ?></td></tr>
			<tr><td class="spLabel"><?php sp_etext('Poster ID'); ?></td><td colspan="2" class="spLabel"><?php echo $post[0]->user_id; ?></td></tr>
			<tr><td class="spLabel"><?php sp_etext('Poster IP'); ?></td><td colspan="2" class="spLabel"><?php echo $post[0]->poster_ip; ?></td></tr>
<?php
		}
?>
	</table>
	</div>
<?php
}

function sp_forum_sort_order() {
	global $spThisUser, $sfglobals;

	$forumid = sp_esc_int($_GET['forumid']);
	if (!$spThisUser->admin) {
		sp_etext('Access denied - you do not have permission');
		die();
	}

    # make sure we have valid forum
	$thisforum = spdb_table(SFFORUMS, "forum_id=$forumid", 'row');
    if (empty($thisforum)) die();

    # if already reversed remove flag or reverse if not
    $key = array_search($forumid, (array) $sfglobals['sort_order']['forum']);
    if ($key === false) {
        $sfglobals['sort_order']['forum'][] = $forumid;
    } else {
        unset($sfglobals['sort_order']['forum'][$key]);
        $sfglobals['sort_order']['forum'] = array_keys($sfglobals['sort_order']['forum']);
    }
    sp_add_sfmeta('sort_order', 'forum', $sfglobals['sort_order']['forum'], 1);
    sp_redirect(sp_build_url($thisforum->forum_slug, '', 1));

    die();
}

function sp_topic_sort_order() {
	global $spThisUser, $sfglobals;

	$topicid = sp_esc_int($_GET['topicid']);
	if (!$spThisUser->admin) {
		sp_etext('Access denied - you do not have permission');
		die();
	}

    # make sure we have valid forum
	$thistopic = spdb_table(SFTOPICS, "topic_id=$topicid", 'row');
	$thisforum = spdb_table(SFFORUMS, "forum_id=$thistopic->forum_id", 'row');
    if (empty($thistopic)) die();

    # if already reversed remove flag or reverse if not
    $key = array_search($topicid, (array) $sfglobals['sort_order']['topic']);
    if ($key === false) {
        $sfglobals['sort_order']['topic'][] = $topicid;
    } else {
        unset($sfglobals['sort_order']['topic'][$key]);
        $sfglobals['sort_order']['topic'] = array_keys($sfglobals['sort_order']['topic']);
    }
    sp_add_sfmeta('sort_order', 'topic', $sfglobals['sort_order']['topic'], 1);
    sp_redirect(sp_build_url($thisforum->forum_slug, $thistopic->topic_slug, 1));

    die();
}

function sp_rebuild_forum_form($forumid, $topicid, $forumslug, $topicslug) {
	$out = '<form action="'.sp_build_url($forumslug, $topicslug, 1, 0).'" method="post" name="forumrebuild">'."\n";
	$out.= '<input type="hidden" name="forumid" value="'.$forumid.'" />'."\n";
	$out.= '<input type="hidden" name="topicid" value="'.$topicid.'" />'."\n";
	$out.= '<input type="hidden" name="forumslug" value="'.esc_attr($forumslug).'" />'."\n";
	$out.= '<input type="hidden" name="topicslug" value="'.esc_attr($topicslug).'" />'."\n";
	$out.= '<input type="submit" class="spSubmit" name="rebuildforum" value="'.sp_text('Verify').'" />';
	$out.= '</form>'."\n";
	return $out;
}

function sp_rebuild_topic_form($forumid, $topicid, $forumslug, $topicslug) {
	$out = '<form action="'.sp_build_url($forumslug, $topicslug, 1, 0).'" method="post" name="topicrebuild">'."\n";
	$out.= '<input type="hidden" name="forumid" value="'.$forumid.'" />'."\n";
	$out.= '<input type="hidden" name="topicid" value="'.$topicid.'" />'."\n";
	$out.= '<input type="hidden" name="forumslug" value="'.esc_attr($forumslug).'" />'."\n";
	$out.= '<input type="hidden" name="topicslug" value="'.esc_attr($topicslug).'" />'."\n";
	$out.= '<input type="submit" class="spSubmit" name="rebuildtopic" value="'.sp_text('Verify').'" />';
	$out.= '</form>'."\n";
	return $out;
}

function sp_move_post_popup() {
	$thispost = sp_esc_int($_GET['pid']);
	$topicid = sp_esc_int($_GET['id']);
	$thispostindex = sp_esc_int($_GET['pix']);
	$thistopic = spdb_table(SFTOPICS, "topic_id=$topicid", 'row');
    if (empty($thispost) || empty($thistopic)) die();

	$thisforum = spdb_table(SFFORUMS, "forum_id=$thistopic->forum_id", 'row');
	if (!sp_get_auth('move_posts', $thistopic->forum_id)) {
		if (!is_user_logged_in()) {
			sp_etext('Access denied - are you logged in?');
		} else {
			sp_etext('Access denied - you do not have permission');
		}
		die();
	}
?>
	<div id="spMainContainer" class="spForumToolsPopup">
		<div class="spForumToolsHeader">
			<div class="spForumToolsHeaderTitle"><?php echo sp_text('Move post'); ?></div>
		</div>

		<form action="<?php echo sp_build_url($thisforum->forum_slug, $thistopic->topic_slug, 1, 0); ?>" method="post" name="movepostform">

			<input type="hidden" name="postid" value="<?php echo $thispost; ?>" />
			<input type="hidden" name="oldtopicid" value="<?php echo $topicid; ?>" />
			<input type="hidden" name="oldforumid" value="<?php echo $thisforum->forum_id; ?>" />
			<input type="hidden" name="oldpostindex" value="<?php echo $thispostindex; ?>" />

			<fieldset><legend><?php sp_etext('Select Operation'); ?></legend>

				<input type="radio" name="moveop" id="single" value="single" checked="checked" />
				<label for="single">&nbsp;<?php sp_etext('Move this post only'); ?></label><br />

				<input type="radio" name="moveop" id="tostart" value="tostart" />
				<label for="tostart">&nbsp;<?php sp_etext('Move this post and ALL preceding posts'); ?></label><br />

				<input type="radio" name="moveop" id="toend" value="toend" />
				<label for="toend">&nbsp;<?php sp_etext('Move this post and ALL succeeding posts'); ?></label><br />

				<input type="radio" name="moveop" id="select" value="select" />
				<label for="select">&nbsp;<?php sp_etext('Move the posts listed below'); ?>:</label><br />

				<label for="idList"><?php sp_etext('Post Numbers to move - separated by commas'); ?></label><br />
				<input type="text" class="spControl" name="idlist" value="<?php echo $thispostindex; ?>," /><br /><br />

				<span>
				<input type="button" class="spSubmit" name="movetonew" value="<?php echo sp_split_button_label(sp_text('Move to a NEW topic'), 2); ?>" onclick="jQuery('#oldtopic').hide(); jQuery('#newtopic').show();" />
				<input type="button" class="spSubmit" name="movetoold" value="<?php echo sp_split_button_label(sp_text('Move to an EXISTING topic'), 2); ?>" onclick="jQuery('#newtopic').hide(); jQuery('#oldtopic').show();" />
				<input type="button" class="spSubmit" name="cancel" value="<?php echo sp_split_button_label(sp_text('Cancel Move'), 0) ?>" onclick="jQuery('#dialog').dialog('close');" />
				</span>

			</fieldset>

			<div id="newtopic" class="spCenter" style="display:none;">
				<p class="spCenter" ><b><?php sp_etext('Move to a NEW topic'); ?></b></p>
				<?php echo sp_render_group_forum_select(false, false, true, true, sp_text('Select forum'), 'forumid', 'spSelect');	?><br /><br />
				<p class="spCenter"><?php sp_etext('New topic name'); ?></p>
				<input type="text" class="spControl" size="80" name="newtopicname" value="" /><br /><br />
				<?php do_action('sph_move_post_form', $thispost, $topicid); ?>
				<input type="submit" class="spSubmit" name="makepostmove1" value="<?php sp_etext('Move') ?>" />
			</div>

			<div id="oldtopic" class="spCenter" style="display:none;">
				<p class="spCenter" ><b><?php sp_etext('Move to a EXISTING topic'); ?></b></p>
				<p class="spCenter" ><?php sp_etext('Click on the Move button below and when the page refreshes navigate to the target topic to complete the move'); ?></p>
				<?php do_action('sph_move_post_form', $thispost, $topicid); ?>
				<input type="submit" class="spSubmit" name="makepostmove2" value="<?php sp_etext('Move') ?>" />
			</div>

		</form>

	</div>
<?php
}

?>