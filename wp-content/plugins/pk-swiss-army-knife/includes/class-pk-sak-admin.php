<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * PK SWISS ARMY KNIFE ADMIN
 *
 * @author  Nick Kalscheur
 */
class Powderkeg_Swiss_Army_Knife_Admin {

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings co and menu.
	 */
	public function __construct() {
		// Add the settings submenu.
		add_action( 'admin_menu', array( $this, 'add_plugin_settings_submenu' ),1 );

		// Init plugin options.
		add_action( 'admin_init', array( $this, 'plugin_settings' ) );

		// Admin scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __PKSAK_USR_FILE__ ) . 'powderkeg-swiss-army-knife' . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	* Load admin scripts.
	*
	* @return void
	*/
	public function admin_scripts( $hook ) {
		
		// Checks if is the settings page.
		if ( 'settings_page_pk-swiss-army-knife' == $hook ) {
		
			wp_enqueue_style( 'pk-sak-admin', plugins_url( 'lib/css/admin.css', plugin_dir_path( __FILE__ ) ), '', Powderkeg_Swiss_Army_Knife::VERSION, 'all' );

			wp_enqueue_script( 'pk-sak-admin', plugins_url( 'lib/js/admin.js', plugin_dir_path( __FILE__ ) ), array( 'jquery'), Powderkeg_Swiss_Army_Knife::VERSION, true );

			wp_enqueue_style( 'pk-sak-admin-lity', plugins_url( 'lib/css/lity.min.css', plugin_dir_path( __FILE__ ) ), '', null, 'all' );

			wp_enqueue_script( 'pk-sak-admin-lity', plugins_url( 'lib/js/lity.min.js', plugin_dir_path( __FILE__ ) ), array( 'jquery'),null, true );
		}
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @return void
	 */
	public function display_plugin_settings_page() {
		include_once 'views/admin.php';
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Settings menu.
	 *
	 * @return void
	 */
	public function add_plugin_settings_submenu() {
		add_submenu_page(
			'options-general.php',
			'Powderkeg Swiss Army Knife Settings',
			'PK Swiss Army Knife',
			'manage_options',
			'pk-swiss-army-knife',
			array( $this, 'display_plugin_settings_page' )
		);
	}

	/**
	 * Plugin settings form fields.
	 *
	 * @return void
	 */
	public function plugin_settings() {
		// Set the settings section.
		add_settings_section(
			'tools-section',
			'Tools Enable / Disable',
			'__return_false',
			'pk-swiss-army-knife'
		);

		// Sent to.
		add_settings_field(
			'send_to',
			'Tools',
			array( $this, 'tools_callback' ),
			'pk-swiss-army-knife',
			'tools-section',
			array(
				'id'          => 'tools_list',
				'description' => sprintf( '<p>Select which tools / functionality you would like enabled</p>' ),
				'default'     => ''
			)
		);

		// Register settings.
		register_setting( 'pk-swiss-army-knife', 'pk_sak', array( $this, 'validate_options' ) );
	}

	/**
	 * Get option value.
	 *
	 * @param  string $id      Option ID.
	 * @param  string $default Default option.
	 *
	 * @return array           Option value.
	 */
	protected function get_option_value( $id, $default = '' ) {
		$options = get_option( 'pk_sak' );

		if ( isset( $options[ $id ] ) ) {
			$default = $options[ $id ];
		}

		return $default;
	}

	/**
	 * Check if one of the tools is enabled
	 *
	 * @param  string $id      Option ID.
	 * @param  string $default Default option.
	 *
	 * @return array           Option value.
	 */
	protected function check_tool_enabled( $id ) {
		$options = get_option( 'pk_sak' );
		if(!is_array($options) || !array_key_exists('pk_sak_tools', $options)){ return false; }

		$default = false;
		if ( isset( $options['pk_sak_tools'][ $id ] ) ) {
			$default = $options['pk_sak_tools'][ $id ];
		}

		return $default;
	}

	/**
	 * Text field callback.
	 *
	 * @param  array $args Arguments from the option.
	 *
	 * @return string      Text input field HTML.
	 */
	public function text_callback( $args ) {
		$id = $args['id'];

		// Sets current option.
		$current = esc_html( $this->get_option_value( $id, $args['default'] ) );

		$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="regular-text" />', $id, 'notify_users_email', $current );

		// Displays the description.
		if ( $args['description'] ) {
			$html .= sprintf( '<div class="description">%s</div>', $args['description'] );
		}

		echo $html;
	}

	/**
	* Tools field callback.
	*
	* @param  array $args Arguments from the option.
	*
	* @return string      Text input field HTML.
	*/
	public function tools_callback( $args ) {
		$tools = __PKSAK_USR_DIR__.'/tools/';
		$dir = array_diff(scandir($tools), array('..', '.'));
		$valid = array();
		$html = '';

		// Displays the description.
		if ( $args['description'] ) {
			$html .= sprintf( '<div class="plugin-description">%s</div>', $args['description'] );
		}

		$html .= '<div class="tools-wrap">';

		foreach($dir as $temp){
			if(!is_dir($tools.$temp)){ continue; } //Not a directory, get out
			if(!file_exists($tools.$temp.'/pk-tool-'.$temp.'.php')){ continue; } //relevant pk file doesn't exist, get out
			$enable = file_exists($tools.$temp.'/.enabled') || file_exists($tools.$temp.'/pk_enabled.txt') ? true : false; //Should always be enabled

			//Look for info.json and load some values
			$name_file = file_exists($tools.$temp.'/info.json') ? $tools.$temp.'/info.json' : false;
			$name = $description = $dependencies = $author = $version = $screenshot = '';

			if($name_file){
				$json_str = file_get_contents($name_file);
				$json = json_decode($json_str, true);
				$name = isset($json['name']) ? $json['name'] : '';
				$description = isset($json['description']) ? $json['description'] : '';
				$dependencies = isset($json['dependencies']) ? $json['dependencies'] : '';
				$author = isset($json['author']) ? $json['author'] : '';
				$version = isset($json['version']) ? $json['version'] : '';
				$screenshot = isset($json['screenshot']) ? $json['screenshot'] : '';
			}

			//Load the tool header data
			$data = get_plugin_data($tools.$temp.'/pk-tool-'.$temp.'.php');

			$name = $name ? $name : 'pk-tool-'.$temp;
			$valid['pk-tool-'.$temp] = array(
				'name'=>!empty($data['Name']) ? $data['Name'] : $name,
				'description'=>!empty($data['Description']) ? $data['Description'] : $description,
				'dependencies'=>$dependencies,
				'author'=>!empty($data['Author']) ? $data['Author'] : $author,
				'version'=>!empty($data['Version']) ? $data['Version'] : $version,
				'screenshot'=>($screenshot ? plugins_url( '/tools/'.$temp.'/', __DIR__ ).$screenshot : ''),
				'always_enabled'=>$enable
			);
		}

		foreach($valid as $id => $info){
			$enable_class = $info['always_enabled'] ? ' alwaysenabled' : '';
			// Sets current options.
			$checked = esc_html( $this->check_tool_enabled( $id ) ) ? ' checked="checked" ' : '';

			$html .= '<div class="tool'.$enable_class.'"><div class="inner">';

				/* Label and Slider */
				$html .= sprintf( '
					<h3 class="tool-name">%4$s:</h3>
					<div class="onoffswitch">
				   	<input type="checkbox" name="%2$s[pk_sak_tools][%1$s]" %3$sclass="tool-checkbox onoffswitch-checkbox" id="%1$s" />
				   	<label class="onoffswitch-label" for="%1$s">
				         <span class="onoffswitch-inner"></span>
				      	<span class="onoffswitch-switch"></span>
				    	</label>
					</div>', $id, 'pk_sak', $checked, $info['name'] );
				/* End Label and Slider */

				/* Tool Meta */
				$html .= 	'<div class="meta">';
				$html .= !$info['description'] ? '' : sprintf('<div class="description"><strong>Description: </strong>%1$s</div>', $info['description']);
				$html .= !$info['dependencies'] ? '' : sprintf('<div class="dependencies"><strong>Dependencies: </strong>%1$s</div>', $info['dependencies']);
				$html .= !$info['author'] ? '' : sprintf('<div class="author"><strong>Adder: </strong>%1$s</div>', $info['author']);
				$html .= !$info['screenshot'] ? '' : sprintf('<div class="screenshot"><a data-lity href="%1$s">Screenshot</a></div>', $info['screenshot']);
				$html .= 	'</div>';
				/* End Tool Meta */

			$html .= '</div></div>';
		}	

		$html .= '</div>';	

		echo $html;
	}

	/**
	 * Editor field callback.
	 *
	 * @param  array $args Arguments from the option.
	 *
	 * @return string      Editor field HTML.
	 */
	public function editor_callback( $args ) {
		$id = $args['id'];

		// Sets current option.
		$current = $this->get_option_value( $id, $args['default'] );

		echo '<div style="width: 600px;">';
				wp_editor( $current, $id, array( 'textarea_name' => 'notify_users_email' . '[' . $id . ']', 'textarea_rows' => 10 ) );
		echo '</div>';

		// Displays the description.
		if ( $args['description'] ) {
			echo sprintf( '<div class="description">%s</div>', $args['description'] );
		}
	}

	/**
	 * Valid options.
	 *
	 * @param  array $input Options to valid.
	 *
	 * @return array        Validated options.
	 */
	public function validate_options( $input ) {
		$output = array();

		foreach ( $input as $key => $value ) {
			if ( isset( $input[ $key ] ) ) {
				if ( in_array( $key, array( 'body_post', 'body_comment' ) ) ) {
					//$output[ $key ] = wp_kses( $input[ $key ], array() );
					$output[ $key ] = $input[ $key ];
				} else {
					$output[ $key ] =  $input[ $key ];
				}
			}
		}

		return $output;
	}

	/**
	 * Add settings action link to the plugins page.
	 */
	public function add_action_links( $links ) {
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=pk-swiss-army-knife' ) . '">' .'Settings'.'</a>'
			),
			$links
		);
	}
}

new Powderkeg_Swiss_Army_Knife_Admin();