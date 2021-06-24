<?php

defined ( 'WP_UNINSTALL_PLUGIN' ) || die('sorry, you can not access to this file directly') ;

global $wpdb;
$table = $wpdb->prefix . TABLE_DONATE;
$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
