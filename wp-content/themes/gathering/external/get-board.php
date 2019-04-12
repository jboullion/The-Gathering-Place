<?php 
/**
 * Save a game board for later use!
 */

define( 'SHORTINIT', TRUE ); 
//define('WP_USE_THEMES', false);
require_once('../../../../wp-load.php');
header('Content-Type: application/json');

if(! empty($_GET['board_id']) && is_numeric($_GET['board_id']) ){
	global $wpdb;

	$table = 'app_boards';

	$user_id = 1; //get_current_user_id(); //$_SESSION['user_id']

	$stmt = $wpdb->prepare("SELECT board_data FROM {$table} WHERE board_id = %d", $_GET['board_id']);
	$result = $wpdb->get_var( $stmt );

	if ( ! empty($result) ){
		echo json_encode(array('success' => $result));
		exit;
	}else{
		echo json_encode(array('error' => 'Unable to Insert Board'));
		exit;
	}

}

echo json_encode(array('error' => 'Incorrect Information'));
exit;