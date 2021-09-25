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

    wp_register_script( 'wp-filemanager-ajax-save-files', $url . "js/ajax.filemanager.save.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-save-files', 'save_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-save-files' );

    wp_register_script( 'wp-filemanager-ajax-delete-files', $url . "js/ajax.filemanager.delete.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-delete-files', 'delete_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-delete-files' );

    wp_register_script( 'wp-filemanager-ajax-createfile-files', $url . "js/ajax.filemanager.createfile.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-createfile-files', 'createfile_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-createfile-files' );

    wp_register_script( 'wp-filemanager-ajax-createdir-files', $url . "js/ajax.filemanager.createdir.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-createdir-files', 'createdir_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-createdir-files' );
    
    wp_register_script( 'wp-filemanager-ajax-rename-files', $url . "js/ajax.filemanager.rename.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-rename-files', 'rename_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-rename-files' );

    wp_register_script( 'wp-filemanager-ajax-moveto-files', $url . "js/ajax.filemanager.moveto.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-moveto-files', 'moveto_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-moveto-files' );
        
    wp_register_script( 'wp-filemanager-ajax-info-files', $url . "js/ajax.filemanager.info.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-info-files', 'info_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
	  wp_enqueue_script( 'wp-filemanager-ajax-info-files' );
	
}

/* AJAX action callback */
add_action( 'wp_ajax_get_filemanager_files', 'get_filemanager_files' );
add_action( 'wp_ajax_nopriv_get_filemanager_files', 'get_filemanager_files' );

function get_filemanager_files($posts) {

    global $wp;
    $object_id = $_POST['object_id'];
    $link = $_POST['link'];
    $urlParams = $_POST['urlParams'];

    if ($urlParams == 'path') {
      $path = $_POST['object_id'];
    }
    if ($urlParams == 'home') {
      $home = $_POST['object_id'];
    }
    if ($urlParams == 'workplace') {
      $workplace = $_POST['object_id'];
    }
    $my_option_name = get_option('my_option_name');
    $user = wp_get_current_user();   
    global $wp;
    $i = 0;
    $x = 0;

    foreach ($my_option_name['id_name'] as $id_name){
      foreach( $my_option_name['read-'.$id_name] as $read_id ){
          $id_read[$id_name] .= $read_id;
      }
  }

  foreach ($my_option_name['id_name'] as $id_name){
      foreach( $my_option_name['write-'.$id_name] as $write_id ){
          $id_write[$id_name] .= $write_id;
      }
  }

  foreach( $my_option_name['read-path'] as $read_path_id ){
          $id_read_path[] .= $read_path_id;
  }
          
  foreach( $my_option_name['write-path'] as $write_path_id ){
          $id_write_path[] .= $write_path_id;
  }

    if (isset($home)) {
      $blogusers = get_users();
      $path_implode = $home;
      $path_default = $my_option_name['title'].$user->user_login;
      if ($path_implode == $path_default) {
          $workplace_last = true;
      }
      if (strpos($path_implode, $path_default) !== false) {
          $workplace_strpos = true;
      }
      $read_path = true;
      $write_path = true;
  }

  if (isset($workplace)) { 
      foreach ($my_option_name['id_path'] as $id_path) {
          $id_path_ = rtrim($id_path, "/");
          $workplace_ = rtrim($workplace, "/");
          if (strpos($workplace_, $id_path_) !== false) {
              if (in_array($user->ID, str_split($id_read[$my_option_name['id_name'][$i]]))) {
                  $read_path = true;
              }
              if (in_array($user->ID, str_split($id_write[$my_option_name['id_name'][$i]]))) {
                  $write_path = true;
              }
          }
          $i++;
      }
      foreach($my_option_name['id_path'] as $id_path) {
          $id_path_ = rtrim($id_path, "/");
          $workplace_ = rtrim($workplace, "/");
          if ($workplace_ == $id_path_) {
              $workplace_last = true;
          }
          if (strpos($workplace, $id_path_) !== false) {
              $workplace_strpos = true;
          }
      }
      $path_implode = $workplace;
  }

  if (isset($path)) {
      if (in_array($user->ID, $id_read_path)) {
          $read_path = true;
          $workplace_strpos = true;
      }
      if (in_array($user->ID, $id_write_path)) {
          $write_path = true;
      }
      $path_implode = $path;
  }

    if ($workplace_strpos == true && $read_path == true){
      $allFiles = scandir($object_id);
    }
    $files = array_diff($allFiles, array('.', '..'));
    $path_parts = explode("/", $path_implode);

    $html[] = "<div id='filemanagerbtn' class='filemanagerbtn'>
                  <div class='navbar'>";
                          if ($path_parts[1] != '' && $workplace_strpos == true && $workplace_last != true){
                              if (isset($home)) { $html[] .= "<a class='btnback_' href='" . $link . "/?home=" . dirname($path_implode) . "'>Parent directory</a>"; }
                              if (isset($workplace)) {  $html[] .= "<a class='btnback_' href='" . $link . "/?workplace=" . dirname($path_implode) . "'>Parent directory</a>"; }
                              if (isset($path)) {  $html[] .= "<a class='btnback_' href='" . $link . "/?path=" . dirname($path_implode) . "'>Parent directory</a>"; }
                          } 
                          if ($path_parts[1] == '' || $workplace_last == true || $workplace_strpos != true) {
                          $html[] .= "<a class='btnback_home' href='" . $link . "'>Home</a>";
                          }
                          if ($write_path != true) {
                            $html[] .= "<div class='btninfo'>info</div>";
                          }
                $html[] .= "</div>";
              $html[] .= "</div>";
              $html[] .= "<div class='filepath'>";
              foreach($path_parts as $path_part) {
                  $path_part_ .= '/'.$path_part;
                  if (isset($home)) { $html[] .= "<a href='" . $link . "/?home=" .  realpath($path_part_) . "'>" . $path_part . "</a>"; }
                  if (isset($workplace)) { $html[] .= "<a href='" . $link . "/?workplace=" .  realpath($path_part_) . "'>" . $path_part . "</a>"; }
                  if (isset($path)) { $html[] .= "<a href='" . $link . "/?path=" .  realpath($path_part_) . "'>" . $path_part . "</a>"; }
                  $html[] .= '/';
              }
              $html[] .="</div>";
              $html[] .="<div class='file-table'><table id='file-table'>";
                  foreach($files as $file){
                      $pathfilezise = $path_implode.'/'.$file;
                      $filesize = formatSizeUnits(filesize($pathfilezise));
                      $realpath = realpath($path_implode.'/'.$file);
                      if (isset($home)) {
                        $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "/?home=$realpath'>$file</a></td><td>$filesize</td></tr>";
                      }
                      if (isset($workplace)) { 
                        $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "/?workplace=$realpath'>$file</a></td><td>$filesize</td></tr>";
                      }
                      if (isset($path)) {
                        $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "/?path=$realpath'>$file</a></td><td>$filesize</td></tr>";
                      }
                  }
                  if ($files == null) {
                    $html[] .= "<tr><td><input class='checkbox' type='checkbox' /></td><td class='filemanager-table'><a id='file-id' class='filemanager-click'>No file found</a></td></tr>";
                  }
            $html[] .= "</table></div>";

	return wp_send_json ( implode($html) );

}

/* AJAX action callback */
add_action( 'wp_ajax_save_filemanager_files', 'save_filemanager_files' );
add_action( 'wp_ajax_nopriv_save_filemanager_files', 'save_filemanager_files' );

function save_filemanager_files($posts) {

  $object_id = stripslashes($_POST['object_id']);
  $link = $_POST['link'];

  $myfile = fopen($link, "w");
  fwrite($myfile, $object_id);
  fclose($myfile);

	return wp_send_json ( $object_id );

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
add_action( 'wp_ajax_createfile_filemanager_files', 'createfile_filemanager_files' );
add_action( 'wp_ajax_nopriv_createfile_filemanager_files', 'createfile_filemanager_files' );

function createfile_filemanager_files($posts) {

  $object_id = $_POST['inputVal'];

  $myfile = fopen($object_id, "w");
  fwrite($myfile, "");
  fclose($myfile);

	return wp_send_json ( $object_id );

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

/* AJAX action callback */
add_action( 'wp_ajax_moveto_filemanager_files', 'moveto_filemanager_files' );
add_action( 'wp_ajax_nopriv_moveto_filemanager_files', 'moveto_filemanager_files' );

function moveto_filemanager_files($posts) {

  global $wp;

  $paths = $_POST['path'];
  $object_id = $_POST['inputVal'];

  foreach ($paths as $path) {
    $path_part = explode("/", $path);
    $filename = end($path_part);
    rename($path, $object_id.$filename);
    $html[] .= $object_id.$filename;
  }

	return wp_send_json ( implode($html) );

}

function countFiles($path) {
  $size = 0;
  $ignore = array('.','..');
  $files = scandir($path);
  foreach($files as $t) {
    if(in_array($t, $ignore)) continue;
    if (is_dir(rtrim($path, '/') . '/' . $t)) {
      $size += countFiles(rtrim($path, '/') . '/' . $t);
    } else {
      $size++;
    }   
  }
  return $size;
}

function GetDirectorySize($path){
  $bytestotal = 0;
  $path = realpath($path);
  if($path!==false && $path!='' && file_exists($path)){
      foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
          $bytestotal += $object->getSize();
      }
  }
  return $bytestotal;
}

/* AJAX action callback */
add_action( 'wp_ajax_info_filemanager_files', 'info_filemanager_files' );
add_action( 'wp_ajax_nopriv_info_filemanager_files', 'info_filemanager_files' );
function info_filemanager_files($posts) {

  $paths = $_POST['path'];

  foreach ( $paths as $path ) {
    if ( $path != '') {
      $i = 0;
      $direname = dirname($path);
      $basename = strtolower(basename($path));
      $getpwuid = posix_getpwuid(fileowner($path));
      $html .= "<div class='info-window'>";
      $html .= "<div class='info-window-close'>X</div>";
        $html .= "<div class='info-window-name'>" . $basename . "</div>";
        $html .= "<div class='info-window-dir-name'>" . $direname . "</div>";
        $html .= "<div class='info-window-fileperms'>" . fileperms($path) . "</div>";
        foreach ($getpwuid as $username) {
          if ($i <= 0) {
            $html .= "<div class='info-window-getpwuid'>" . $username . "</div>";
          }
          $i++;
        }
        if (is_dir($path)) {
          $html .= "<div class='info-window-count'>" . countFiles($path) . "</div>";
          $html .= "<div class='info-window-count-size'>" . formatSizeUnits(GetDirectorySize($path)) . "</div>";
        } else {
          $html .= "<div class='info-window-size'>" . formatSizeUnits(filesize($path)) . "</div>";
        }
      $html .= "</div>";
    }
  }

	return wp_send_json ( $html );

}