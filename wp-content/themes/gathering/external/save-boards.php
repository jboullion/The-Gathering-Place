<?php 
/**
 * Save a game board
 * 
 * TODO: Check Session AND current_userid === user_id
 */

define( 'SHORTINIT', TRUE ); 
//define('WP_USE_THEMES', false);
require_once('../../../../wp-load.php');
header('Content-Type: application/json');

if(! empty($_POST['user_id']) && is_numeric($_POST['user_id']) 
&& ! empty($_POST['board']) && is_array($_POST['board']) ){
	global $wpdb;
	
	if(empty($_POST['board_name'])){
		$_POST['board_name'] = 'New Board';
	}

	
	if( empty($_POST['board']['id']) || ! is_numeric($_POST['board']['id']) ){
		
		$result = $wpdb->insert( 
			$table, 
			array( 
				'user_id' => $_POST['user_id'], 
				'party_id' => $_POST['party_id'],
				'board_name' => $_POST['board_name'],
				'board_data' => json_encode($_POST['board'])
			), 
			array( 
				'%d', 
				'%d',
				'%s'
			)
		);

		if ( $result ){
			echo json_encode(array('success' => $wpdb->insert_id));
			exit;
		}else{
			echo json_encode(array('error' => 'Unable to Insert Board'));
			exit;
		}

	}else{
		$result = $wpdb->update( 
			$table, 
			array( 'board_name' => $_POST['board_name'], 'board_data' => json_encode($_POST['board']) ), 
			array( 'user_id' => $_POST['user_id'], 'board_id' => $_POST['board']['id'] ), 
			array( '%s'), 
			array( '%d', '%d' ) 
		);

		if ( $result ){
			echo json_encode(array('success' => 'Board Saved'));
			exit;
		}else{
			//This does not mean
			echo json_encode(array('warning' => 'Attempted Update'));
			exit;
		}
	}


}

echo json_encode(array('error' => 'Incorrect Information'));
exit;