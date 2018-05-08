<?php
/* --- Enqueue Styles & Scripts --- */
function eiq_enqueue_scripts(){		
	
	/* -- Enqueue CSS File -- */
	$query_args = array(
		'family' => 'Amiri',
		'subset' => 'latin,arabic',
	);
	wp_enqueue_style( 'amiri-google-fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
	
	/* -- Enqueue CSS File -- */			
	wp_enqueue_style( 'ins-q-style', EIQ_PLUGIN_URL.'/assets/css/insert-quran-styles.css');		

}
add_action( 'wp_enqueue_scripts', 'eiq_enqueue_scripts' );

/* --- Register WYSIWYG Button --- */
function eiq_button_init() {
	if (! current_user_can ( 'edit_posts' ) && ! current_user_can ( 'edit_pages' ) && get_user_option ( 'rich_editing' ) == 'true')
		return;
	
	add_filter("mce_external_plugins", "eiq_enqueue_plugin_scripts");
	add_filter("mce_buttons", "eiq_register_buttons_editor");
}
add_action ( 'init', 'eiq_button_init' );

function eiq_enqueue_plugin_scripts($plugin_array) {
    //enqueue TinyMCE plugin script with its ID.
    $plugin_array["insert_quran_plugin"] = EIQ_PLUGIN_URL.'/assets/js/button.js';
    return $plugin_array;
}
function eiq_register_buttons_editor($buttons) {
    //register buttons with their id.
    array_push($buttons, "eiq_button");
    return $buttons;
}

/* --- Add Setting Link to Plugin Page --- */
function eiq_insert_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=eiq-options">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
add_filter( 'plugin_action_links_'.EIQ_PLUGIN_BASENAME, 'eiq_insert_settings_link' );