<?php

/*
Plugin Name: ACF Simple WYSIWYG
Description: Adds a new simple WYSIWYG editor option for the ACF fields. Can be modified / added to with the acf/fields/wysiwyg/toolbars filter
Version: 1.0.0
Author: Nick Kalscheur
Author URI: https://kalscheur.info/
*/

//Adds a new WYSIWYG editor option for the ACF field
/*
*  Ideally this is for places where we want the client to be able to edit
*  very basic things about the text without giving them full access to
*  stuff that enables them to kill the site
*/

if ( defined( 'ABSPATH' ) && !class_exists( 'PK_Tool_ACF_WYSIWYG' ) ) {

	class PK_Tool_ACF_WYSIWYG {	

		public function __construct() {
			add_filter( 'acf/fields/wysiwyg/toolbars' , array( $this, 'pk_add_acf_wysiwyg' )  );
		}

		public function pk_add_acf_wysiwyg($toolbars){
			$toolbars['Very Simple' ] = array();
			$toolbars['Very Simple' ][1] = array('bold' , 'italic' , 'underline', 'link', 'unlink' );
			return $toolbars;		
		}

	}

	new PK_Tool_ACF_WYSIWYG;
}