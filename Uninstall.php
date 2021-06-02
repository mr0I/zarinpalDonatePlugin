<?php

defined ( 'WP_UNINSTALL_PLUGIN' ) || die('sorry, you can not access to this file directly') ;

global $wpdb;
$table = $wpdb->prefix . 'erima_donate';
$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
