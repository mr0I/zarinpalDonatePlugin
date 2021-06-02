<?php defined('ABSPATH') or die('&lt;h3&gt;Access denied!'); ?>

<?php

function payDonate_callback(){
	check_ajax_referer( '28=(n6i|R|CMQ/', 'security' );

	if ( wp_verify_nonce($_POST['nonce'], 'donate-select-nonce')) {

		//$ids = $_POST['selectedDonatesIds'];



		$result['result'] = 'Success';
		$result['product_name'] = 'name';
		wp_send_json( $result );
		//exit();
	} else {
//		$result['result'] = 'ok';
//		wp_send_json( $result );
//		exit();
	}

}
add_action( 'wp_ajax_payDonate', 'payDonate_callback' );
add_action( 'wp_ajax_nopriv_payDonate', 'payDonate_callback' );
