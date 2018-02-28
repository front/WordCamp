<?php

class Fact_Box_Functions {

	function __construct() {

		// Register Custom Post Type
		add_action('init', array( &$this, 'fact_box_create_post_type') );

		// Add the button to the content editor, next to the media button on any admin page in the array below
		if(in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'post-new.php'))) {
			// Register Button
			add_action('media_buttons', array( $this, 'add_fact_box_button'), 11);
		}
		// Add new fact_box
    add_action( 'wp_ajax_add-new-fact_box', array(&$this, 'add_new_fact_box') );
    add_action( 'wp_ajax_nopriv_add-new-fact_box', array(&$this, 'add_new_fact_box') );
		// Getting existing Facts
    add_action( 'wp_ajax_get-single-fact_box', array(&$this, 'get_single_fact_box') );
    add_action( 'wp_ajax_nopriv_get-single-fact_box', array(&$this, 'get_single_fact_box') );
		// After clicked on edit, open modal
		add_action( 'wp_ajax_open_fact_box_modal', array(&$this, 'open_fact_box_modal') );
    add_action( 'wp_ajax_nopriv_open_fact_box_modal', array(&$this, 'open_fact_box_modal') );
		// Live search function
    add_action( 'wp_ajax_live-searh-function', array(&$this, 'live_searh_function') );
    add_action( 'wp_ajax_nopriv_live-searh-function', array(&$this, 'live_searh_function') );
		// Edit Fact Box
    add_action( 'wp_ajax_edit_fact_box-function', array(&$this, 'edit_fact_box_function') );
    add_action( 'wp_ajax_nopriv_edit_fact_box-function', array(&$this, 'edit_fact_box_function') );

		add_action( 'print_media_templates', array(&$this, 'fact_box_print_media_templates') );
	}


	/**
	 * Adds Fact Box button above WP editor.
	 *
	 * @since    1.0.0
	 * @param
	 */
	function add_fact_box_button() {
		//the id of the container I want to show in the popup
		$popup_id = 'nfModal';
		//our popup's title
		$title = 'NewsFront Fact Box';
		//append the icon
		printf(
		'<a id="nf_fact_box" data-toggle="modal" data-target="#nfModal" title="%2$s" href="%3$s" class="%4$s"><span class="dashicons dashicons-media-text"></span> Fact Box</a>',
		esc_attr( $popup_id ),
		esc_attr( $title ),
		esc_url( '#' ),
		esc_attr( 'button add_media')
		);
	}

	/**
	 * Add new Fact Box.
	 * This function is called from ajax.
	 *
	 * @since    1.0.0
	 * @param
	 */
	function add_new_fact_box(){
		$fact_box_id = $_POST['fact_box_id'];

		$args = array(
		  'post_type' => 'fact_box',
		  'post_status' => 'publish',
		  'post_title'  => $_POST['title'],
		  'post_content' => $_POST['content']
		);

		if ( $fact_box_id ) {
			$args['ID'] = $fact_box_id;
			$new_fact_id = wp_update_post($args, true);
			echo $fact_box_id;
		} else {
			$new_fact_id = wp_insert_post($args);
			echo $new_fact_id;
		}

		die();
	}

	/**
	 * Get single Fact Box to be rendered inside post content.
	 * This function is called from ajax.
	 *
	 * @since    1.0.0
	 * @param
	 */
	function get_single_fact_box(){
		$post_id = $_POST['postid'];
		$post = get_post($post_id);
		$data = array('title' => $post->post_title, 'content' => $post->post_content, 'fact_id' => $post->ID);
		echo json_encode($data);
		die();
	}

	/**
	 * Opens modal for editing.
	 *
	 * @since    1.0.0
	 * @param
	 */

	function open_fact_box_modal(){
		$fact_box_id = $_POST['fact_box_id'];
		$fact_box = get_post($fact_box_id);
		$data = array('title' => $fact_box->post_title, 'content' => $fact_box->post_content, 'fact_id' => $fact_box->ID);
		echo json_encode($data);
		die();
	}

	/**
	 * While inserting new Fact Box to the post, user can perform a live search between existing facts.
	 * This function is called from ajax.
	 *
	 * @since    1.0.0
	 * @param
	 */
	function live_searh_function(){
		$post_title = $_POST['title'];
		$posts = $this->get_post_by_title($post_title);
		?><ul><?php
		foreach ($posts as $post) {
			?>
				<li id="<?php echo $post->ID; ?>" class="nf-fact-title"><?php echo $post->post_title; ?></li>
		<?php } ?>
		</ul><?php
		die();
	}

	/**
	 * Based on live_searh_function(), finds single facts.
	 * This function is called from live_searh_function().
	 *
	 * @since    1.0.0
	 * @param
	 */
	function get_post_by_title($post_title, $output = OBJECT) {
		global $wpdb;
		$querystr = "
			SELECT $wpdb->posts.*
	    FROM $wpdb->posts
			WHERE $wpdb->posts.post_type = 'fact_box'
	    AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->posts.post_title LIKE '%$post_title%'
			ORDER BY $wpdb->posts.post_date DESC
			Limit 10
		";
		$pageposts = $wpdb->get_results($querystr, OBJECT);
		return $pageposts;
	}

	/**
	 * Registers new custom post type.
	 *
	 * @since    1.0.0
	 * @param
	 */
  function fact_box_create_post_type() {
    $labels = array(
      'name'               => _x('Fact box', 'post type general name', 'fact-box'),
      'singular_name'      => _x('Fact Box', 'post type singular name', 'fact-box'),
      'menu_name'          => _x('Fact Boxes', 'admin menu', 'fact-box'),
      'name_admin_bar'     => _x('Fact Box', 'add new on admin bar', 'fact-box'),
      'add_new'            => _x('Add New', 'book', 'fact-box'),
      'add_new_item'       => __('Add New Fact Box', 'fact-box'),
      'new_item'           => __('New Fact Box', 'fact-box'),
      'edit_item'          => __('Edit Fact Box', 'fact-box'),
      'view_item'          => __('View Fact Box', 'fact-box'),
      'all_items'          => __('All Fact boxes', 'fact-box'),
      'search_items'       => __('Search Fact Box', 'fact-box'),
      'parent_item_colon'  => '',
      'not_found'          => __('No Fact box found.', 'fact-box'),
      'not_found_in_trash' => __('No Fact box found in Trash.', 'fact-box'),
    );

    $args = array(
      'labels'              => $labels,
      'description'         => __('Description.', 'fact-box'),
      'public'              => TRUE,
      'publicly_queryable'  => TRUE,
      'show_ui'             => TRUE,
      'show_in_menu'        => TRUE,
      'query_var'           => TRUE,
      'rewrite'             => array('slug' => 'fact-box'),
      'capability_type'     => 'post',
      'has_archive'         => TRUE,
      'hierarchical'        => FALSE,
      'menu_position'       => 5,
      'supports'            => array('title', 'editor', 'author'),
      'taxonomies'          => array(),
      'exclude_from_search' => TRUE
    );

    register_post_type('fact_box', $args);
  }

	/**
	 * Adds Fact Box template to media templates to be used for rendering Fact Box inside editor.
	 *
	 * @since    1.0.0
	 * @param
	 */
	function fact_box_print_media_templates() {

		?>
		<script type="text/html" id="tmpl-fact-box">
			<?php
				if ( unserialize(get_option('fact_box_options'))['fact_box_use_custom_style'] ) {
					echo '<style>' . unserialize(get_option('fact_box_options'))['fact_box_custom_css'] . '</style>';
				}
			?>
			<div class="fact-contentbox facts" contenteditable="false">
				<h1 class="title"><span>{{ data.fact_box.title }}</span></h1>
				{{{ data.fact_box.content }}}</div>
		</script>

		<?php
	}

}

new Fact_Box_Functions();
