<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.frontkom.no/
 * @since      1.0.0
 *
 * @package    Fact_Box
 * @subpackage Fact_Box/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fact_Box
 * @subpackage Fact_Box/public
 * @author     Foad Yousefi <foad@front.no>
 */
class Fact_Box_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if ( unserialize(get_option('fact_box_options'))['choose_fact_theme_layout'] ) {
			wp_enqueue_style( $this->plugin_name, FACT_BOX_URL . 'includes/assets/css/' . unserialize(get_option('fact_box_options'))['choose_fact_theme_layout'] . '.css', array(), $this->version, 'all' );
		} else {
			wp_enqueue_style( $this->plugin_name, FACT_BOX_URL . 'includes/assets/css/layout-plain.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fact-box-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register fact-box shortcode.
	 *
	 * @since    1.0.0
	 */
	function fact_box_shortcode_registration() {

		add_shortcode(
			'fact_box', function ($attr, $content = '') {
			if (empty($attr['fact_box_id'])) {
				return '';
			}

			$post  = get_post($attr['fact_box_id']);
			$title = $post->post_title;
			$body  = $post->post_content;
			$postid  = $post->ID;

			$content = '';
			if ( unserialize(get_option('fact_box_options'))['fact_box_use_custom_style'] ) {
				$content .= '<style>' . unserialize(get_option('fact_box_options'))['fact_box_custom_css'] . '</style>';
			}
			$content .= '<div class="fact-contentbox facts">';
			$content .= '<h1 class="title"><span>' . $title . '</span></h1>';
			$content .= wpautop( do_shortcode( get_post_field( 'post_content', $postid ) ) );
			$content .= '</div>';

			return $content;
		}
		);
	}

}
