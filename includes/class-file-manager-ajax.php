<?php

/* Enqueue Script */
add_action( 'wp_enqueue_scripts', 'wp_filemanager_ajax_scripts' );

/**
 * Scripts
 */
function wp_filemanager_ajax_scripts() {
	/* Plugin DIR URL */
	$url = trailingslashit( plugin_dir_url( __FILE__ ) );
 
    wp_register_script( 'wp-filemanager-ajax-read-files', $url . "js/ajax.filemanager.read.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-read-files', 'read_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-filemanager-ajax-read-files' );
	
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
    $html[] = "<table>";
    foreach($files as $file){
      $pathfilezise = $object_id.'/'.$file;
      $filesize = filesize($pathfilezise);
      if ( is_dir($object_id.'/'.$file) == true ) {
        $html[] .= "<tr><td><input type='checkbox' name='$file'/></td><td id='file-id' class='filemanager-click' data-object-id='$object_id/$file'>$file</td><td>$filesize</td></tr>";
      } else {
        $html[] .= "<tr><td><input type='checkbox' name='$file'/></td><td id='file-id' class='filemanager-click-file' data-object-id='$object_id/$file'>$file</td><td>$filesize</td></tr>";
      }
    }
    $html[] .= "</table>";

	return wp_send_json ( implode($html) );

}

$n=10;
function getName($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
  
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
  
    return $randomString;
}

/* AJAX action callback */
add_action( 'wp_ajax_read_filemanager_files', 'read_filemanager_files' );
add_action( 'wp_ajax_nopriv_read_filemanager_files', 'read_filemanager_files' );

function read_filemanager_files($posts) {

  $object_id = $_POST['object_id'];
  $getname = getName(8);
  $target =  $object_id;
  $direname = dirname($object_id);
  $basename = basename($object_id);

  $link = get_home_path() .'/files/'. $getname;
  echo exec('mkdir "'. get_home_path() .'/files"');
  echo exec('rm "'. $link .'"');
  echo exec('ln -s "' . $target . '" "' . $link .'"');

  $html[0] = '<div id="top-wrapper"><div class="filemanager-click-back" data-object-id="' . $direname . '">Back</div><div class="filemanager-basename">' . $basename . '</div></div>';
  $html[1] = '<div id="dplayer"></div>';
  $html[2] .= get_home_url() . '/files/' . $getname;

	return wp_send_json ( $html );

}