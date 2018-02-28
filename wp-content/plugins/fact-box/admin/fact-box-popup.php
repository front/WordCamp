<?php
$args = array(
  'post_type'       => 'fact_box',
  'posts_per_page'  => 10
);
$posts = get_posts($args);

?>

<div id="nfModal" class="nf-modal">

  <div class="nf-modal-content">
    <div class="nf-modal-header">
      <span class="nf-modal-close">×</span>
      <h2><?php _e('Insert Fact Box', 'fact-box') ?></h2>
    </div>
    <div class="nf-modal-body">
      <div class="nf-fact-container">
        <div id="selectFacts">
          <h2><?php _e('Select from existing', 'fact-box') ?></h2>
          <input type="search" name="search_fact" placeholder="Search existing Facts">
          <div id="fact_results">
            <ul>
              <?php foreach ($posts as $post) { ?>
              <li id="<?php echo $post->ID; ?>" class="nf-fact-title"><?php echo $post->post_title; ?></li>
              <?php } ?>
            </ul>
          </div>

          <hr />
          <button type="button" class="add-new" id="showNewFactForm"><?php _e('Add new', 'fact-box') ?></button>
        </div>


        <div id="newFactBox">
        <a id="backToSearch"><span class="dashicons dashicons-arrow-left-alt"></span> <?php _e('Back to select', 'fact-box') ?></a>
          <div class="new-form">
            <h2><?php _e('Add new Fact Here', 'fact-box') ?></h2>
            <form id="factForm" action="" method="post">
              <input type="text" name="fact_title" placeholder="<?php _e('Fact box title', 'fact-box') ?>" >
              <input type="hidden" name="fact_id" placeholder="<?php _e('Fact box ID', 'fact-box') ?>" >
              <?php
              $content = '';
              $editor_id = 'factcontentid';
              $settings = array(
                'textarea_name' => 'fact_content',
                'editor_height' => 200,
                'teeny'=> true,
                'media_buttons' => false);
                wp_editor( $content, $editor_id, $settings );
               ?>
              <p class="tinymceSelector"></p>
              <button type="button" class="add-new" id="addNewFactBox"><?php _e('Insert', 'fact-box') ?></button>
            </form>
          </div>
        </div>

      </div>
    </div>
      <div class="nf-modal-footer">
        <h4 class="insert-existing"><?php _e('Just click on post title to insert into post body', 'fact-box') ?></h4>
        <h4 class="hide-element insert-new"><?php _e('Here you can add new fact box', 'fact-box') ?></h4>
      </div>
  </div>
</div>
