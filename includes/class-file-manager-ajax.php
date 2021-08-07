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

    wp_register_script( 'wp-filemanager-ajax-upload-files', $url . "js/ajax.filemanager.upload.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-upload-files', 'upload_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-upload-files' );
	
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
  $getname = getName(32);
  $target =  $object_id;
  $direname = dirname($object_id);
  $basename = basename($object_id);
  $ext = pathinfo($basename, PATHINFO_EXTENSION);

  $link = get_home_path() .'/files/'. $getname .'.'. $ext;
  echo exec('mkdir "'. get_home_path() .'/files"');
  echo exec('rm "'. $link .'"');
  echo exec('ln -s "' . $target . '" "' . $link .'"');

  if ( $ext == 'mkv' || $ext == 'mp4'  ){
    $html[0] = $ext;
    $html[1] .= '<div id="top-wrapper"><div class="filemanager-click-back" data-object-id="' . $direname . '">Back</div><div class="filemanager-basename">' . $basename . '</div></div>';
    $html[2] .= '<div id="dplayer"></div>';
    $html[3] .= get_home_url() . '/files/' . $getname . '.' . $ext;
  } elseif ( $ext == 'pdf' ){
    $html[0] = $ext;
    $html[1] .= '<div id="top-wrapper"><div class="filemanager-click-back" data-object-id="' . $direname . '">Back</div><div class="filemanager-basename">' . $basename . '</div></div>';
    $html[2] .= '<div id="pdf"></div>';
    $html[3] .= get_home_url() . '/files/' . $getname . '.' . $ext;
  } elseif ( $ext == 'bmp' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png' || $ext == 'bmp' ){
    $html[0] = $ext;
    $html[1] .= '<div id="top-wrapper"><div class="filemanager-click-back" data-object-id="' . $direname . '">Back</div><div class="filemanager-basename">' . $basename . '</div></div>';
    $html[2] .= '<img id="img" src=' . get_home_url() . '/files/' . $getname . '.' . $ext .'></img>';
  } else {
    $html[0] = $ext;
    $html[1] .= '<div id="top-wrapper"><div class="filemanager-click-back" data-object-id="' . $direname . '">Back</div><div class="filemanager-basename">' . $basename . '</div></div>';
    $html[2] .= '<div id="filecontent">' . file_get_contents(get_home_url() . '/files/' . $getname . '.' . $ext) . '</div>';
  }

	return wp_send_json ( $html );

}

/* AJAX action callback */
add_action( 'wp_ajax_upload_filemanager_files', 'upload_filemanager_files' );
add_action( 'wp_ajax_nopriv_upload_filemanager_files', 'upload_filemanager_files' );

function upload_filemanager_files($posts) {

  $object_id = $_POST['object_id'];

  $files = scandir($object_id);
  $html[] .= "<table>";
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