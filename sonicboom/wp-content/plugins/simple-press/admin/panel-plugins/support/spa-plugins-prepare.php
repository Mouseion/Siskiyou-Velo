<?php
/*
Simple:Press
Admin plugins prepare Support Functions
$LastChangedDate: 2011-07-09 12:15:36 -0700 (Sat, 09 Jul 2011) $
$Rev: 6611 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

/**
* Get the list of plugins
*/
function spa_get_plugins_list_data() {
    $plugins = sp_get_plugins();
    return $plugins;
}

?>