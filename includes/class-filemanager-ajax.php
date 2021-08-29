<?php

/* Enqueue Script */
add_action( 'wp_enqueue_scripts', 'wp_filemanager_ajax_scripts' );

/**
 * Scripts
 */
function wp_filemanager_ajax_scripts() {
	/* Plugin DIR URL */
	$url = trailingslashit( plugin_dir_url( __FILE__ ) );
 
    wp_register_script( 'wp-filemanager-ajax-get-files', $url . "js/ajax.filemanager.upload.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-get-files', 'get_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-get-files' );

    wp_register_script( 'wp-filemanager-ajax-delete-files', $url . "js/ajax.filemanager.delete.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-delete-files', 'delete_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-delete-files' );

    wp_register_script( 'wp-filemanager-ajax-createdir-files', $url . "js/ajax.filemanager.createdir.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-createdir-files', 'createdir_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-createdir-files' );
    
    wp_register_script( 'wp-filemanager-ajax-rename-files', $url . "js/ajax.filemanager.rename.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-rename-files', 'rename_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-rename-files' );
	
}

/* AJAX action callback */
add_action( 'wp_ajax_get_filemanager_files', 'get_filemanager_files' );
add_action( 'wp_ajax_nopriv_get_filemanager_files', 'get_filemanager_files' );

function get_filemanager_files($posts) {

    global $wp;
    $object_id = $_POST['object_id'];
    $link = $_POST['link'];

    $allFiles = scandir($object_id);
    $files = array_diff($allFiles, array('.', '..'));

    $html[] = "<div id='filemanagerbtn' class='filemanagerbtn'>
                  <div class='navbar'>
                      <div class='btndelete'>Delete</div>
                      <div class='subnav'>
                          <button class='subnavbtn btnnewdir'>Create dir</button>
                          <div id='subnav-content' class='subnav-content'>
                              <span>
                                  <input type='text' id='lname' name='lname'></input>
                                  <button class='newdir'>ok</button>
                              <span>
                          </div>
                      </div>
                      <div class='btnrename'>Rename</div>
                  </div>
              </div>
              <div id='filemanagerbtn' class='filemanagerbtn'>
                  <div class='navbar'>
                      <a class='btnback_' href='" . $link . "/?path=" . dirname($object_id) . "'>Parent directory</a>
                  </div>
              </div>";
    $html[] .= "<div class='file-table'><table id='file-table'>";
    foreach($files as $file){
      $pathfilezise = $object_id.'/'.$file;
      $filesize = formatSizeUnits(filesize($pathfilezise));
      $filepath = $link . '?path=' . $object_id . '/' . $file;
      if ( is_dir($object_id.'/'.$file) == true ) {
        $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$object_id/$file'/></td><td><a id='file-id' class='filemanager-click' href='$filepath'>$file</a></td><td>$filesize</td></tr>";
      } else {
        $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$object_id/$file'/></td><td><a id='file-id' class='filemanager-click-file' href='$filepath'>$file</a></td><td>$filesize</td></tr>";
      }
    }
    $html[] .= "</table></div>";

	return wp_send_json ( implode($html) );

}

/* AJAX action callback */
add_action( 'wp_ajax_delete_filemanager_files', 'delete_filemanager_files' );
add_action( 'wp_ajax_nopriv_delete_filemanager_files', 'delete_filemanager_files' );

function delete_filemanager_files($posts) {

  $object_id = $_POST['path'];

  foreach ($object_id as $file) {
    echo exec('rm -r "'. $file .'"');
    $html[] = $file;
  }

	return wp_send_json ( implode($html) );

}

/* AJAX action callback */
add_action( 'wp_ajax_createdir_filemanager_files', 'createdir_filemanager_files' );
add_action( 'wp_ajax_nopriv_createdir_filemanager_files', 'createdir_filemanager_files' );

function createdir_filemanager_files($posts) {

  $object_id = $_POST['inputVal'];

  echo exec('mkdir "'. $object_id .'"');

	return wp_send_json ( $object_id );

}

/* AJAX action callback */
add_action( 'wp_ajax_rename_filemanager_files', 'rename_filemanager_files' );
add_action( 'wp_ajax_nopriv_rename_filemanager_files', 'rename_filemanager_files' );

function rename_filemanager_files($posts) {

  global $wp;
  $filename = $_POST['filename'];
  $path = $_POST['path'];
  $link = $_POST['link'];
  $dirname = dirname($path);
  $new_filename = $dirname . '/' . $filename;

  if(rename($path, $new_filename) == true) {
    $html[] = "<a id='file-id' class='filemanager-click' href='" . $link . "?path=$new_filename'>$filename</a>";
  } else {
    $html[] = "error";
  }

	return wp_send_json ( implode($html) );

}