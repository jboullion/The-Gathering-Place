<?php

	class acf_field_database_driven_option extends acf_field {
		
		function __construct() {
			
			$this->name = 'database_driven_option';
			$this->label = 'Database Driven Options';
			$this->category = 'choice';
			$this->defaults = array(
				'database_field_type' => 'radio',
			);
			$this->l10n = array(
				'error'	=> 'Error! Please enter a higher value',
			);

			// do not delete!
	    	parent::__construct();
	    	
		}
		
		function render_field_settings($field) {
			
			acf_render_field_setting($field, array(
				'label'			=> 'Database Table',
				'instructions'	=> 'Table name that you would like to pull from',
				'type'			=> 'text',
				'name'			=> 'database_table',
				'required'		=> true,
			));

			acf_render_field_setting($field, array(
				'label'			=> 'Primary Key',
				'instructions'	=> 'Enter the column name of the primary key.<br />Will be saved as the value of your field.',
				'type'			=> 'text',
				'name'			=> 'database_primary_key',
				'required'		=> true,
			));

			acf_render_field_setting($field, array(
				'label'			=> 'Label Field',
				'instructions'	=> 'Enter the table name of the label field<br />Will be shown as the label for your field.',
				'type'			=> 'text',
				'name'			=> 'database_label_field',
				'required'		=> true,
			));

			acf_render_field_setting($field, array(
				'label'			=> 'Field Type',
				'type'			=> 'radio',
				'name'			=> 'database_field_type',
				'choices'		=> array('checkbox'=>'Checkbox', 'radio'=>'Radio Button', 'select'=>'Select'),
				'layout'			=> 'horizontal',
			));

			acf_render_field_setting($field, array(
				'label'			=> 'Field Type Layout',
				'type'			=> 'radio',
				'name'			=> 'layout',
				'layout'			=> 'horizontal', 
				'choices'		=> array(
					'vertical'	=> 'Vertical', 
					'horizontal'=> 'Horizontal',
				),
			));

		}
		
		function render_field($field) {
			$choices = $this->get_field_choices($field);
			$field['choices'] = $choices;

			if($field['choices']) {
				$field_class_name = $this->get_field_class_name($field);
				do_action('acf/render_field/type='.$field['database_field_type'], array(
					'type' => $field['database_field_type'],
					'name' => $field['name'],
					'value' => is_array($field['value']) ? $field['value'] : array_search($field['value'],$choices),
					'id' => $field['id'],
					'class' => $field['class'],
					'choices' => $field['choices']
				));
			} else {
				echo '<p style="background:#f55e4f; border-radius:3px; color:#fff; margin-bottom:0; padding:10px;">Values could not be retrieved from the database, please ensure that <a href="'.admin_url('post.php?post='.$field['parent'].'&action=edit').'" target="_blank" style="color:#fff;">the field is configured properly</a>.</p>';
			}
		}

		function load_value($value, $post_id, $field) {
			$choices = $this->get_field_choices($field);
			return is_array($value) ? $value : $choices[$value];
		}
			
		function get_field_choices($field) {
			global $wpdb;

			$r = array();
			if($results = $wpdb->get_results('SELECT '.esc_sql($field['database_primary_key']).' AS primary_key, '.esc_sql($field['database_label_field']).' AS field_label FROM '.esc_sql($field['database_table']))) {
				foreach($results as $result) {
					$r[$result->primary_key] = $result->field_label;
				}
			}
			return $r;
		}

		function get_field_class_name($field) {
			$r = 'acf_field_select';
			switch($field['database_field_type']) {
				case 'checkbox': $r = 'acf_field_checkbox'; break;
				case 'radio': $r = 'acf_field_radio'; break;
				case 'select': $r = 'acf_field_select'; break;
			}
			return $r;
		}
		
	}


	// create field
	new acf_field_database_driven_option();