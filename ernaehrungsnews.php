<?php
/*
Plugin Name: Ernährungsnews
Plugin URI: http://webfuchs-entwicklung.de
Description: Die News müssen Beiträge mit der Kategorie-ID 3 sein!
Version: 0.1
Author: Christian Schade
Author URI: http://webfuchs-entwicklung.de
License: GPL
*/
/* Runs when plugin is activated */
register_activation_hook(__FILE__,'hello_world_install');

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'hello_world_remove' );


function hello_world_install() {
/* Creates new database field */
add_option("ernaehrungsnews_anzahl", '3', '', 'yes');
add_option("ernaehrungsnews_trimmer", '100', '', 'yes');

}

function hello_world_remove() {
/* Deletes the database field */
delete_option('ernaehrungsnews_anzahl');
delete_option('ernaehrungsnews_trimmer');
}


function hello_world_html_page() {
  ?>
  <div>
    <h2>Hello World Options</h2>

    <form method="post" action="options.php">
      <?php wp_nonce_field('update-options'); ?>

      <table width="510">
        <tr valign="top">
          <th width="92" scope="row">Anzahl News</th>
          <td width="406">
            <input name="ernaehrungsnews_anzahl" type="text" id="ernaehrungsnews_anzahl"
            value="<?php echo get_option('ernaehrungsnews_anzahl'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th width="92" scope="row">Anzahl Zeichen News</th>
            <td width="406">
              <input name="ernaehrungsnews_trimmer" type="text" id="ernaehrungsnews_trimmer"
              value="<?php echo get_option('ernaehrungsnews_trimmer'); ?>" />
              </td>
            </tr>
        </table>

        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="ernaehrungsnews_anzahl" />
        <input type="hidden" name="page_options" value="ernaehrungsnews_trimmer" />


        <p>
          <input type="submit" value="<?php _e('Save Changes') ?>" />
        </p>

      </form>
    </div>
    <?php
  }
  /* Call the html code */
  if ( is_admin() ){

    /* Call the html code */
    add_action('admin_menu', 'hello_world_admin_menu');

    function hello_world_admin_menu() {
      add_options_page('Hello World', 'Hello World', 'administrator',
      'hello-world', 'hello_world_html_page');
    }
  }

  function hello_world() {

    $posts = get_latest_posts();
    foreach ($posts as $post){
      $postings .= '<div class="orangi">' . $post['post_title'] .' <span class="eatnewsdate">' . $post["post_date"] . "</span></div>";
      $postings .= '<div class="eatnewsdesc"><a href="' . $post["guid"] .'">' . substr($post['post_content'],0,100) . "</a>...</div>";
    }
     return $postings;

  }

  function get_latest_posts()
  {

    $args = array(
      'numberposts' => 3,
      'offset' => 0,
      'category' => 3,
      'orderby' => 'post_date',
      'order' => 'DESC',
      'post_type' => 'post',
      'post_status' => 'draft, publish, future, pending, private',
      'suppress_filters' => true );

      return wp_get_recent_posts( $args);
  }

  add_shortcode( 'ernaehrungsnews', 'hello_world' );
