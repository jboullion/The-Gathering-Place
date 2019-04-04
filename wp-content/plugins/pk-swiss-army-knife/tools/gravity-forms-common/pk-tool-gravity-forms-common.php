<?php
	
	// if gravity forms isn't enabled, get out
	if(!class_exists('GFForms')) { return; }
	
	// replaces the default <input type="submit"> element with a <button> element
	add_filter('gform_submit_button', 'pk_tool_pk_form_submit_button', 10, 5);
	if(!function_exists('pk_tool_pk_form_submit_button')){
		function pk_tool_pk_form_submit_button($button, $form) {
			if($form['button']['type'] == 'text' || !$form['button']['imageUrl']) {

				// change element to a button
				$button = str_replace('<input', '<button', $button);
				$button = str_replace('/>', '>'.apply_filters('pk_form_submit_button', '<span><span>'.$form['button']['text'].'</span></span>', $form['button']['text']).'</button>', $button);

				// add our classes
				$button = str_replace('class=\'', 'class=\'btn btn-primary '.apply_filters('pk_form_submit_button_classes', '').' ', $button);
			}
			return $button; // otherwise it returns the default
		}
	}

	// font awesome icons based on class names
	add_filter('gform_field_input', 'pk_tool_pk_gform_field_input', 10, 5);
	if(!function_exists('pk_tool_pk_gform_field_input')){
		function pk_tool_pk_gform_field_input($input, $field, $value, $lead_id, $form_id) {
			if(!is_admin()) {
				if(in_array($field['type'], array('email', 'phone', 'number', 'text')) && preg_match('/pk-(fa-[^\s]+)/', $field['cssClass'], $matches)) {
					switch($field['type']) {
						case 'phone': $input_type = 'text'; break;
						default: $input_type = $field['type']; break;
					}
					return '<div class="input-group'.($field['cssClass'] ? ' '.$field['cssClass'] : '').'"><span class="input-group-addon" id="pk-fa'.$field['id'].'"><i class="fa '.$matches[1].'"></i></span><input type="'.$input_type.'" tabindex="1" class="form-control" value="'.$value.'" name="input_'.$field['id'].'" id="input_'.$form_id.'_'.$field['id'].'" aria-describedby="pk-fa'.$field['id'].'" /></div>';
				}
			}
		}
	}
	
	// adding placeholder attribute - manipulate the settings in the admin view
	add_action('gform_field_standard_settings', 'pk_tool_pk_gform_placeholder_settings', 10, 2);
	if(!function_exists('pk_tool_pk_gform_placeholder_settings')){
		function pk_tool_pk_gform_placeholder_settings($position, $form_id) {
			if($position == 25) { ?>
				<li class="label_setting field_setting">
					<label for="field_placeholder">
						<?php _e('Placeholder', 'gravityforms'); ?>
						<?php gform_tooltip('field_placeholder') ?>
					</label>
					<input class="fieldwidth-3" size="35" type="text" id="field_placeholder" onkeyup="SetFieldProperty('placeholder', this.value);" />
				</li>
			<?php }
		}
	}
		
	// adding placeholder attribute - admin javascript
	add_action('gform_editor_js', 'pk_tool_pk_gform_placeholder_js');
	if(!function_exists('pk_tool_pk_gform_placeholder_js')){
		function pk_tool_pk_gform_placeholder_js() {
			?>
			<script type="text/javascript">
				// adding setting to fields of type "text"
				fieldSettings['text'] += ', .field_placeholder';
				
				// binding to the load field settings event to initialize the new fields
				jQuery(document).bind('gform_load_field_settings', function(event, field, form) {
					jQuery('#field_placeholder').val(field['placeholder']);
				});
			</script><?php
		}
	}
	
	// adding placeholder attribute - admin tooltips
	add_filter('gform_tooltips', 'pk_tool_pk_gform_placeholder_tooltips');
	if(!function_exists('pk_tool_pk_gform_placeholder_tooltips')){
		function pk_tool_pk_gform_placeholder_tooltips($tooltips) {
			$tooltips['field_placeholder'] = '<h6>Placeholder</h6>HTML5 Placeholder';
			return $tooltips;
		}
	}
	
	// adding placeholder attribute - injexts placeholder attribute into the Form
	add_filter('gform_field_content', 'pk_tool_pk_gform_placeholder_inject', 10, 5);
	if(!function_exists('pk_tool_pk_gform_placeholder_inject')){
		function pk_tool_pk_gform_placeholder_inject($content, $field, $value, $lead_id, $form_id) {
			
			// write placeholder attribute
			if(!empty($field['placeholder'])) {
				$content = preg_replace('/\<(input|select|textarea){1,1}/i', '<$1 placeholder="'.htmlentities($field['placeholder']).'"', $content);
			}
			
			// return field content
			return $content;
		}
	}

	// add a specific class for the field type on the <li> wrapper
	add_filter('gform_field_css_class', 'pk_gform_field_css_class', 10, 3);
	if(!function_exists('pk_gform_field_css_class')){
		function pk_gform_field_css_class($css_class, $field, $form) {
			return $css_class.' pk-gfield-wrap-type-'.$field->type;
		}
	}