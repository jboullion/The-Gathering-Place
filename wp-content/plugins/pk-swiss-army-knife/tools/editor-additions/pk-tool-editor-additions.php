<?php

	// TinyMCE options
	add_filter('tiny_mce_before_init', 'pk_tool_pk_editor_tiny_mce_init');
	if(!function_exists('pk_tool_pk_editor_tiny_mce_init')){
		function pk_tool_pk_editor_tiny_mce_init($init) {
		
			// default blocks
			$blocks = array(
				'p' => 'Paragraph',
				'h2' => 'Heading 2',
				'h3' => 'Heading 3',
				'h4' => 'Heading 4',
				'h5' => 'Heading 5',
				'h6' => 'Heading 6',
			);
			
			// default classes
			$classes = array(
				'pull-left' => 'Pull Left',
				'pull-right' => 'Pull Right'
			);
			
			// custom blocks
			$blocks = apply_filters('pk_editor_blocks', $blocks);
			
			// custom classes
			$classes = apply_filters('pk_editor_classes', $classes);
			
			// add blocks
			if(is_array($blocks) && (count($blocks) > 0)) {
				$formats = '';
				foreach($blocks as $type=>$name) {
					$formats .= $name.'='.$type.';';
				}

				$init['block_formats'] = rtrim($formats, ';');
			}
			
			// add classes
			if(is_array($classes) && (count($classes) > 0)) {
				$formats = array();
				foreach($classes as $type=>$name) {
					$formats[] = array('title' => $name, 'inline' => 'span', 'classes' => $type);
				}
				$init['toolbar2'] .= ',styleselect';
				$init['style_formats'] = json_encode($formats);
			}
			
			// body class
			$init['body_class'] = 'content-left';
			
			return $init;
		}
	}
	
	// post-process content submission
	add_filter('wp_insert_post_data', 'pk_tool_pk_editor_filter_post_data', 99, 2);
	add_filter('wp_update_post_data', 'pk_tool_pk_editor_filter_post_data', 99, 2);
	if(!function_exists('pk_tool_pk_editor_filter_post_data')){
		function pk_tool_pk_editor_filter_post_data($data, $postarr) {

			// hook to disable this function and just return the unmanipulated data
			if(apply_filters('pk_disable_pk_tool_pk_editor_filter_post_data_function', false)) { return $data; }

			if(is_array($data) && isset($data['post_content'])) {
				$content = $data['post_content'];
				
				// convert absolute site links to site-relative
				$content = str_replace(rtrim(get_bloginfo('url'),'/').'/', '/', $content);
				
				// convert editor-relative links to site-relative
				$content = str_replace('"../', '"/', $content);
				
				$data['post_content'] = $content;
			}
			
			return $data;
		}
	}