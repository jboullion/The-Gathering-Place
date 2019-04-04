<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACP_Sorting_Model_CustomField_Numeric extends ACP_Sorting_Model_CustomField {

	public function __construct( AC_Column_CustomField $column ) {
		parent::__construct( $column );

		$this->set_data_type( 'numeric' );
	}

}
