<?php

/**
 * Plugin Name: TinyMCE Try It Textbox
 * Plugin URI: https://github.com/lumenlearning/tinymce-tryit-textbox
 * Version: 1.0
 * Author: Lumen Learning
 * Author URI: http://lumenlearning.com
 * Description: TinyMCE Plugin that adds a "Try It" textbox button to the Visual Editor
 * License: MIT
 */

class TinyMCE_Tryit_Textbox {

  /**
   * Constructor: Called when the plugin is initialized.
   */
  function __construct() {
    if ( is_admin() ) {
      add_action( 'admin_head', array( &$this, 'setup_plugin' ) );
      add_action( 'init', array( &$this, 'add_editor_stylesheet' ) );
    }
  }

  /**
   * Called by Constructor: Check if the current user can edit Posts or Pages, and is
   * using the Visual Editor. If so, add filters so we can register plugin.
   */
  function setup_plugin() {

  	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
  		return;
  	}

  	if ( get_user_option( 'rich_editing' ) !== 'true' ) {
  		return;
  	}

  	add_filter( 'mce_external_plugins', array( &$this, 'add_tryit_toolbar_script' ) );
  	add_filter( 'mce_buttons_2', array( &$this, 'add_tryit_toolbar_button' ) );

  }

  /**
   * Called by setup_plugin(): Adds the Plugin JS file to the Visual Editor instance.
   *
   * @param array $plugin_array Array of registered TinyMCE Plugins
   * @return array Modified array of registered TinyMCE Plugins
   */
  function add_tryit_toolbar_script( $plugin_array ) {

  	$plugin_array['tryit_textbox'] = plugin_dir_url( __FILE__ ) . 'tinymce-tryit-textbox.js';
  	return $plugin_array;

  }

  /**
   * Called by setup_plugin(): Adds a button to the Visual Editor which users can
   * click to add a hide-answer CSS class.
   *
   * @param array $buttons Array of registered editor buttons
   * @return array $buttons Modified array of registered editor buttons
   */
  function add_tryit_toolbar_button( $buttons ) {

  	$p = array_search( 'textboxes', $buttons );
  	array_splice( $buttons, $p + 1, 0, 'tryit_textbox' );

  	return $buttons;

  }

  /**
   * Called by Constructor: Adds tryit textbox styles for admin and pages
   */
  function add_editor_stylesheet() {
    add_editor_style( plugin_dir_url( __FILE__ ) . 'tinymce-tryit-textbox.css' );
  }

}

$tinymce_tryit_textbox = new TinyMCE_Tryit_Textbox;