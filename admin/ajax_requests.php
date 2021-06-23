<?php defined('ABSPATH') or die('&lt;h3&gt;Access denied!'); ?>

<?php

function payDonate_callback(){
	check_ajax_referer( '28=(n6i|R|CMQ/', 'security' );

	if ( wp_verify_nonce($_POST['nonce'], 'donate-select-nonce')) {
		$ids = $_POST['selectedDonatesIds'];

		global $wpdb;
		$table = $wpdb->prefix . TABLE_DONATE;
		$c = 0;
		foreach ($ids as $id){
			$update = $wpdb->update( $table, array(
				'paymentStatus'=> 'Paid'
			),
				array( 'DonateID' => absint($id)),
				array( '%s' ),
				array( '%d' )
			);
			if ($update) {$c++;}
		}
		$data=array( 'result' => 'OK' , 'count' => $c );
		echo json_encode($data);
		exit();
	} else {
		$data=array( 'result' => 'Authenticate Error' );
		echo json_encode($data);
		exit();
	}
}
add_action( 'wp_ajax_payDonate', 'payDonate_callback' );
add_action( 'wp_ajax_nopriv_payDonate', 'payDonate_callback' );
