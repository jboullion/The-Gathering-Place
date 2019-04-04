<?php

/*
Plugin Name: Woocommerce Email Preview Tool
Description: Preview woocommerce emails from a submenu
Version: 1.0.1
Author: Nick Kalscheur
Author URI: https://kalscheur.info/
*/

if ( defined( 'ABSPATH' ) && !class_exists( 'PK_Tool_Woo_Emails' ) && class_exists( 'WooCommerce' ) ) {

	class PK_Tool_Woo_Emails {		

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_woo_email_submenu' ),20 );
		}

		public function add_woo_email_submenu(){
			add_submenu_page('tools.php', 'Woo Email Tester', 'Woo Email Tester', 'edit_posts', 'woo-email-test', array($this,'woo_email_test'));
		}

		public function woo_email_test(){
			if (is_admin()) {
				global $woocommerce;

				if(!empty($_GET['order'])){
					$orderId = $_GET['order'];
				}else{
					$orderId = $this->get_default_order();
					if(!$orderId){ $orderId = 1; }
				}

				if(!empty($_GET['file'])){
					$file = $_GET['file'];
				}else{
					$file = "admin-new-order.php";
				}

				echo '<div style="margin:15px 0;">Showing: <strong>'.$file.'</strong></div>';

			 	$default_path = WC()->plugin_path() . '/templates/';

		      $files = scandir($default_path . 'emails');
		      $exclude = array( '.DS_Store','.', '..', 'email-header.php', 'email-footer.php','plain','email-styles.php' );
		      $list = array_diff($files,$exclude); 
		   ?>
		      <form method="get">
		      	<input type="hidden" name="page" value="woo-email-test" />
					<input type="hidden" name="action" value="previewemail">
					<input placeholder="Order #" type="number" name="order" value="<?php echo $orderId; ?>" />
		         <select name="file">
		         	<option value="">Select Template</option>
		         <?php
		         foreach( $list as $item ){ 
		         	if($file == $item){ $selected = " selected"; }else{ $selected = ""; }
		         ?>
		         	<option<?php echo $selected; ?> value="<?php echo $item; ?>"><?php echo str_replace('.php', '', $item); ?></option>
		         <?php } ?>
		         </select><input type="submit" value="Go"></form>
		   <?php
		      global $order;
		      if(function_exists('wc_get_order')){
		      	$order = wc_get_order($orderId);
		      }else{
		      	$order = new WC_Order($orderId);
		      }
		     	$order = wc_get_order($orderId);
		     	$order = is_object($order) ? $order : wc_get_order($this->get_default_order());
		     	$order = $order ? $order : $this->create_dummy_order();
		   ?>
		      <style>
		      <?php wc_get_template( 'emails/email-styles.php', array( 'order' => $order ) ); ?>
		      </style>
		   <?php
		      wc_get_template( 'emails/email-header.php', array( 'order' => $order ) );
		      wc_get_template( 'emails/'.$file, array( 'order' => $order ) );
		      wc_get_template( 'emails/email-footer.php', array( 'order' => $order ) );
			} 
			return null; 
		}

		private function get_default_order(){
			$args = array(
				'post_type'			=> 'shop_order',
				'post_status' 		=> 'any',
	      	'posts_per_page' => 1,
			);
			$o = get_posts($args);
			if($o){
				return $o[0]->ID;
			}else{
				return 1;
			}
		}

		private function get_product_for_order(){
			$args = array(
				'post_type'			=> 'product',
				'post_status' 		=> 'any',
	      	'posts_per_page' => 1,
			);
			$p = get_posts($args);
			return $p ? $p[0]->ID : false;
		}

		private function create_dummy_order(){
			$address = array(
			   'first_name' => 'Dominic',
			   'last_name'  => 'Reginald',
			   'company'    => 'Powderkeg',
			   'email'      => 'testing@testing.com',
			   'phone'      => '555-555-4514',
			   'address_1'  => '330 Locust Drive',
			   'address_2'  => '',
			   'city'       => 'Verona',
			   'state'      => 'WI',
			   'postcode'   => '53593',
			   'country'    => 'US'
			);

			// Now we create the order
			$order = wc_create_order();

			// The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
			if($prod_id = $this->get_product_for_order()){
				$order->add_product( get_product($prod_id), 1); // This is an existing SIMPLE product
			}
			$order->set_address( $address, 'billing' );
			//
			//$order->calculate_totals();
			$order->update_status("Completed", 'Imported order', TRUE); 
			return $order;
		}

	}

	new PK_Tool_Woo_Emails;
}
