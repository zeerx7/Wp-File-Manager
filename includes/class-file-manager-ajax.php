<?php

/* Enqueue Script */
add_action( 'wp_enqueue_scripts', 'wp_filemanager_ajax_scripts' );

/**
 * Scripts
 */
function wp_filemanager_ajax_scripts() {
	/* Plugin DIR URL */
	$url = trailingslashit( plugin_dir_url( __FILE__ ) );
	
    wp_register_script( 'wp-filemanager-ajax-get-files', $url . "js/ajax.filemanager.get.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-get-files', 'get_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	wp_enqueue_script( 'wp-filemanager-ajax-get-files' );
	
}

/* AJAX action callback */
add_action( 'wp_ajax_get_filemanager_files', 'get_filemanager_files' );
add_action( 'wp_ajax_nopriv_get_filemanager_files', 'get_filemanager_files' );

function get_filemanager_files($posts) {

    $object_id = $_POST['object_id'];

    $files = scandir($object_id);
    foreach($files as $file){
      $html[] .= "<div id='file-id' class='filemanager-click' data-object-id='$object_id/$file'>$file</div></br>";
    }

	return wp_send_json ( $html );

}
