<?php 
/**
 * Save a game board for later use!
 */

define( 'SHORTINIT', TRUE ); 
//define('WP_USE_THEMES', false);
require_once('../../../../wp-load.php');
header('Content-Type: application/json');

if(! empty($_POST['user_id']) && is_numeric($_POST['user_id']) 
&& ! empty($_POST['board_id']) && is_numeric($_POST['board_id']) ){
	global $wpdb;

	$table = 'app_boards';

	$stmt = $wpdb->prepare("SELECT board_name, board_data FROM {$table} WHERE board_id = %d", $_POST['board_id'])
	$result = $wpdb->get_row( $stmt );

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