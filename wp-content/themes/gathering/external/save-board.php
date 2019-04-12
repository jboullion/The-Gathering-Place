<?php 
/**
 * Save a game board
 * 
 * TODO: Check Session AND current_userid === user_id
 */

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

define( 'SHORTINIT', TRUE ); 
//define('WP_USE_THEMES', false);
require_once('../../../../wp-load.php');
require_once('database-functions.php');
header('Content-Type: application/json');


if( ! empty($_POST['board']) 
&& isset($_POST['autosave']) && $_POST['autosave'] < 2 ){
	//&& is_array($_POST['board'])
	global $wpdb;

	$wpdb->show_errors = true;

	$table = 'app_boards';
/*
	$board_error = json_validate($_POST['board']);
	if($board_error !== false){
		echo json_encode(array('error' => $board_error));
		exit;
	}
*/	

	$board = json_decode($_POST['board']);

	$user_id = 1; //get_current_user_id(); //$_SESSION['user_id']
	$party_id = 1; //get_current_party(); // $_SESSION['party_id']

	if(empty($board->name)){
		$board->name = 'New Board';
	}
	
	if( empty($board->id) || ! is_numeric($board->id) ){
		/*
	$stmt = $wpdb->prepare("INSERT INTO {$table} (`user_id`, `party_id`, `board_name`, `board_data`, `autosave`) 
	VALUES (%d, %d, %s, '{$_POST['board']}', %d)", $user_id, $party_id, $_POST['board']['name'], $_POST['autosave']);

	

	$result = $wpdb->get_results($stmt);
	echo $wpdb->last_query;
	*/
	
		$result = $wpdb->insert( 
			$table, 
			array( 
				'user_id' => $user_id, 
				'party_id' => $party_id,
				'board_name' => $board->name,
				'board_data' => json_encode($board),
				'autosave' => empty($_POST['autosave'])?0:1
			)
		);
	

		if ( $result ){
			echo json_encode(array('success' => $wpdb->insert_id));
			exit;
		}else{
			//echo $wpdb->last_error;
			echo json_encode(array('error' => 'Unable to Insert'));
			exit;
		}

	}else{
		$result = $wpdb->update( 
			$table, 
			array( 'board_name' => $board->name, 'board_data' => json_encode($_POST['board']), 'autosave' => empty($_POST['autosave'])?0:1 ), 
			array( 'user_id' => $user_id, 'board_id' => $board->id ), 
			array( '%s'), 
			array( '%d', '%d' ) 
		);

		if ( $result ){
			echo json_encode(array('success' => $board->id));
			exit;
		}else{
			//This does not mean failure necessarily, only that nothing changed
			echo json_encode(array('warning' => 'Attempted Update'));
			exit;
		}
	}


}

echo json_encode(array('error' => 'Incorrect Information'));
exit;