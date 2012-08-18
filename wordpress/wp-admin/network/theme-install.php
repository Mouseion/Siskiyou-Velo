<?php
/**
 * Install theme network administration panel.
 *
 * @package WordPress
 * @subpackage Multisite
 * @since 3.1.0
 */

if ( isset( $_GET['tab'] ) && ( 'theme-information' == $_GET['tab'] ) )
	define( 'IFRAME_REQUEST', true );

/** Load WordPress Administration Bootstrap */
require_once('./admin.php');

if ( ! is_multisite() )
	wp_die( __( 'Multisite support is not enabled.' ) );

require('../theme-install.php');