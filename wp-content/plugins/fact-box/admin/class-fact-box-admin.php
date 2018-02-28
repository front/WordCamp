<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.frontkom.no/
 * @since      1.0.0
 *
 * @package    Fact_Box
 * @subpackage Fact_Box/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fact_Box
 * @subpackage Fact_Box/admin
 * @author     Foad Yousefi <foad@front.no>
 */
class Fact_Box_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( unserialize(get_option('fact_box_options'))['choose_fact_theme_layout'] ) {
			add_editor_style( FACT_BOX_URL . 'includes/assets/css/' . unserialize(get_option('fact_box_options'))['choose_fact_theme_layout'] . '.css', array(), $this->version, 'all' );
		} else {
			add_editor_style( FACT_BOX_URL . 'includes/assets/css/layout-plain.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( $this->plugin_name, FACT_BOX_URL . 'admin/css/fact-box-admin.css', array(), $this->version, 'all' );
		add_editor_style( FACT_BOX_URL . 'admin/css/fact-box-editor.css' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, FACT_BOX_URL . 'admin/js/fact-box-admin.js', array( 'jquery' ), $this->version, false );
	}


	/**
	 * Adds tinymce plugin.
	 *
	 * @since    		1.0.0
	 */
	function fact_box_tinymce_plugin( $plgs ) {
		$plgs['fact_box'] = FACT_BOX_URL .'admin/js/editor-plugin.js' ;
		return $plgs;
	}

	/**
	 * Adds tinymce media button.
	 *
	 * @since    		1.0.0
	 */
	function fact_box_popup( $plgs ) {
		include( FACT_BOX_PATH . 'admin/fact-box-popup.php');
	}

	/**
	 * Plugin Setting page in admin
	 *
	 * @since    		1.0.0
	 */
	public function fact_box_titan_create_options() {
		$titan = TitanFramework::getInstance( 'fact_box' );

		$fact_box_panel = $titan->createAdminPanel(
			array(
				'name' 			=> __( 'Fact Box', 'fact-box' ),
				'title' 		=> __( 'Fact Box Options', 'fact-box' ),
				'parent' 	=> 'edit.php?post_type=fact_box',
			)
		);

		// Styling
		$fact_box_styling_tab = $fact_box_panel->createTab(
			array(
				'name' => __( 'Styling', 'fact-box' ),
				'id' => 'fact_box_styling',
			)
		);

		$fact_box_styling_tab->createOption(
			array(
				'name'	=> __( 'Choose theme', 'fact-box' ),
				'id' 		=> 'choose_fact_theme_layout',
				'desc'	=> __( 'Choose from existing themes.', 'fact-box' ),
				'type' => 'radio-image',
				'options' => array(
					'layout-titleout' => FACT_BOX_URL . 'includes/assets/img/layout-titleout.png',
					'layout-plain' => FACT_BOX_URL . 'includes/assets/img/layout-plain.png',
					'layout-orange' => FACT_BOX_URL . 'includes/assets/img/layout-orange.png',
					'layout-exclamation' => FACT_BOX_URL . 'includes/assets/img/layout-exclamation.png',
					'layout-background' => FACT_BOX_URL . 'includes/assets/img/layout-background.png',
				),
				'default' => 'layout-titleout'
			)
		);

		$fact_box_styling_tab->createOption(
			array(
				'name' => __('Use custom styling', 'fact-box'),
				'id' => 'fact_box_use_custom_style',
				'desc' => __('You can use your own custom styling.', 'fact-box'),
				'type' => 'enable',
				'default' => false,
			)
		);

		$fact_box_styling_tab->createOption(
			array(
				'name' => __('Custom css', 'fact-box'),
				'id' => 'fact_box_custom_css',
				'desc' => __('Here you can add your own custom css styling or just override some parts of existing layout.', 'fact-box'),
				'type' => 'code',
				'lang' => 'css',
				'height' => 400,
				'default' => file_get_contents( FACT_BOX_URL . 'includes/assets/css/' . unserialize(get_option('fact_box_options'))['choose_fact_theme_layout'] . '.css' ),
			)
		);

		$fact_box_styling_tab->createOption(
			array(
				'type' => 'save',
			)
		);

	}


}
