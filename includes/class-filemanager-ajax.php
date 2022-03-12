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

    wp_register_script( 'wp-filemanager-ajax-upload-files', $url . "js/ajax.filemanager.upload.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-upload-files', 'upload_filemanager_ajax_url', plugin_dir_url( __DIR__ ) . 'includes/upload.php' );
    wp_enqueue_script( 'wp-filemanager-ajax-upload-files' );

    wp_register_script( 'wp-filemanager-ajax-download-files', $url . "js/ajax.filemanager.download.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-get-files', 'download_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
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

    wp_register_script( 'wp-filemanager-ajax-copy-files', $url . "js/ajax.filemanager.copy.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-copy-files', 'copy_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-filemanager-ajax-copy-files' );

    wp_register_script( 'wp-filemanager-ajax-moveto-files', $url . "js/ajax.filemanager.moveto.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-moveto-files', 'moveto_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-filemanager-ajax-moveto-files' );
        
    wp_register_script( 'wp-filemanager-ajax-info-files', $url . "js/ajax.filemanager.info.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-info-files', 'info_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-filemanager-ajax-info-files' );

    wp_register_script( 'wp-filemanager-ajax-zip-files', $url . "js/ajax.filemanager.zip.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-zip-files', 'zip_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-filemanager-ajax-zip-files' );

    wp_register_script( 'wp-filemanager-ajax-share-files', $url . "js/ajax.filemanager.share.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-share-files', 'share_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-filemanager-ajax-share-files' );

    wp_register_script( 'wp-filemanager-ajax-search-files', $url . "js/ajax.filemanager.search.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-search-files', 'search_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-filemanager-ajax-search-files' );

    wp_register_script( 'wp-filemanager-ajax-tree-get-files', $url . "js/ajax.filemanager.tree.get.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-tree-get-files', 'tree_get_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-filemanager-ajax-tree-get-files' );
    
    wp_register_script( 'wp-filemanager-ajax-view-files', $url . "js/ajax.filemanager.view.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'wp-filemanager-ajax-view-files', 'viewtype_filemanager_ajax_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'wp-filemanager-ajax-view-files' );
  
}

/* AJAX action callback */
add_action( 'wp_ajax_get_filemanager_files', 'get_filemanager_files' );
add_action( 'wp_ajax_nopriv_get_filemanager_files', 'get_filemanager_files' );
function get_filemanager_files($posts) {

  global $wp;
  $object_id = $_POST['object_id'];
  $link = $_POST['link'];
  $urlParams = $_POST['urlParams'];
  $share = $_POST['sharekey'];
  $urlkey = $_POST['urlkey'];
  $uniqid = $_POST['uniqid'];
  $viewtype = $_POST['viewtype'];
  $pages = $_POST['pages'];

  $arg = $urlParams;
  $get_arg = $urlkey;
  $path_implode = $object_id;

  $meta_name = 'oauth-'.$uniqid;
  $get_oauth = get_post_meta($uniqid, $meta_name, true);
  foreach($get_oauth as $oauth){
    $keyone[] = explode('||',$oauth);    
    $currentTime = time();  
    $expTime = strtotime('+1 hour', $oauth[0]);       
    if($currentTime >= $expTime) {
      $_data[] .= $oauth;
    }
  }

  if ($dh = opendir($path_implode)) {
    while (($file = readdir($dh)) !== false) {
        $allFiles[] = $file;
    }
    closedir($dh);
  }
  $files = array_diff($allFiles, array('.', '..'));

  $perpage = 100;
  $page = $pages;
  if(!$page) $page = 1;
  $offset = ($page-1)*$perpage;

  $total_files = sizeof($files);
  $total_pages = ceil($total_files/$perpage);
  $files = array_slice($files, $offset, $perpage);

  sort($files);

  if($viewtype == 'list'){
    $html[] .= "<table id='file-table'><tr></td><td class='checkboxall'><input class='checkboxall' type='checkbox' name=''/></td><td class='checkboxall'>Filename</td><td class='checkboxall'>Size</td></tr>";
    foreach($files as $file){
        $realpath = realpath($path_implode.'/'.$file);
        $filesize = formatSizeUnits(filesize($realpath));
  
        $getname = getName(32);
        $getoauth = time().'||'.$getname.'||'.$realpath.'||'.$path_default;
        if($sharepath){
          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/filemanager/?".$arg."=".$get_arg."&sharepath=".$getname."'>$file</a></td><td>$filesize</td></tr>"; 
        } else {
          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/filemanager/?".$arg."=$getname'>$file</a></td><td>$filesize</td></tr>";    
        }           
        $_data[] .= $getoauth;
    }
    if ($files == null) {
      $html[] .= "<tr><td><input class='checkbox' type='checkbox' /></td><td class='filemanager-table'><a id='file-id' class='filemanager-click'>No file found</a></td><td></td></tr>";
    }
    $html[] .= "</table>";
  }elseif($viewtype == 'grid'){
    $html[] .= "<div class='grid-container'>";
    foreach($files as $file){
        $realpath = realpath($path_implode.'/'.$file);
        $filesize = formatSizeUnits(filesize($realpath));
  
        $getname = getName(32);
        $getoauth = time().'||'.$getname.'||'.$realpath.'||'.$path_default;
        if($sharepath){
          $html[] .= "<div class='grid-item'><input class='checkbox' type='checkbox' name='$realpath'/></input><div class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/filemanager/?".$arg."=".$get_arg."&sharepath=".$getname."'>$file</a></div><div>$filesize</div></div>"; 
        } else {
          $html[] .= "<div class='grid-item'><input class='checkbox' type='checkbox' name='$realpath'/></input><div class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/filemanager/?".$arg."=$getname'>$file</a></div><div>$filesize</div></div>";    
        }           
        $_data[] .= $getoauth;
    }
    if ($files == null) {
      $html[] .= "<div class='grid-item'>No file found</div>";
    }
    $html[] .= "</div>";
  }

  if($total_pages > 1) {
    $html[] .= '<div class="filemanagerpagination">';
    if (isset($sharepath)) {
      $html[] .= '<a class="page" href="?'.$arg.'='.$share.'&sharepath='.$get_arg.'&pages='.(($page-1>1)?($page-1):1).'"><<</a>';
        for($p=1; $p<=$total_pages; $p++) {
          $html[] .= ' <a class="page" href="?'.$arg.'='.$share.'&sharepath='.$get_arg.'&pages='.$p.'">'.$p.'</a> ';                      
        }
        $html[] .= '<a class="page" href="?'.$arg.'='.$share.'&sharepath='.$get_arg.'&pages='.(($page+1>$total_pages)?$total_pages:($page+1)).'">>></a>';
    } else {
      $html[] .= '<a class="page" href="?'.$arg.'='.$get_arg.'&pages='.(($page-1>1)?($page-1):1).'"><<</a>';
        for($p=1; $p<=$total_pages; $p++) {
          $html[] .= ' <a class="page" href="?'.$arg.'='.$get_arg.'&pages='.$p.'">'.$p.'</a> ';                      
        }
        $html[] .= '<a class="page" href="?'.$arg.'='.$get_arg.'&pages='.(($page+1>$total_pages)?$total_pages:($page+1)).'">>></a>';
    }
    $html[] .= "</div>";
}
  
  update_post_meta($uniqid, $meta_name, $_data);
  return wp_send_json ( implode($html) );

}

/* AJAX action callback */
add_action( 'wp_ajax_save_filemanager_files', 'save_filemanager_files' );
add_action( 'wp_ajax_nopriv_save_filemanager_files', 'save_filemanager_files' );
function save_filemanager_files($posts) {

  $object_id = stripslashes($_POST['object_id']);
  $link = $_POST['link'];

  $myfile = fopen($link, "w");
  $write = fwrite($myfile, $object_id);
  fclose($myfile);

  return wp_send_json ( $write );

}

/* AJAX action callback */
add_action( 'wp_ajax_delete_filemanager_files', 'delete_filemanager_files' );
add_action( 'wp_ajax_nopriv_delete_filemanager_files', 'delete_filemanager_files' );
function removeDirectory($path) {

  $files = glob($path . '/*');
  foreach ($files as $file) {
    is_dir($file) ? removeDirectory($file) : unlink($file);
  }
  rmdir($path);

  return;
}

function delete_filemanager_files($posts) {

  $object_id = $_POST['path'];

  foreach ($object_id as $file) {
    if (is_dir($file)) {
      removeDirectory($file);
    } else {
      unlink($file);
    }
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

  mkdir($object_id);

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
  $uniqid = $_POST['uniqid'];

  $meta_name = 'oauth-'.$uniqid;
  $get_oauth = get_post_meta($uniqid, $meta_name, true);
  foreach($get_oauth as $oauth){
    $keyone[] = explode('||',$oauth);    
    $currentTime = time();  
    $expTime = strtotime('+1 hour', $oauth[0]);       
    if($currentTime >= $expTime) {
      $_data[] .= $oauth;
    }
  }

  $getname = getName(32);
  $getoauth = time().'||'.$getname.'||'.$new_filename;

  if(rename($path, $new_filename) == true) {
    $html[] .= "<input class='checkbox' type='checkbox' name='$new_filename'>";
    $html[] .= "<a id='file-id' class='filemanager-click' href='$link?path=$getname'>$filename</a>";
  } else {
    $html[] .= "error";
  }

  $_data[] .= $getoauth;
  update_post_meta($uniqid, $meta_name, $_data);

  return wp_send_json ( $html );

}

function folder_exist($folder)
{
    // Get canonicalized absolute pathname
    $path = realpath($folder);

    // If it exist, check if it's a directory
    if($path !== false AND is_dir($path))
    {
        // Return canonicalized absolute pathname
        return $path;
    }

    // Path/folder does not exist
    return false;
}

/* AJAX action callback */
add_action( 'wp_ajax_copy_filemanager_files', 'copy_filemanager_files' );
add_action( 'wp_ajax_nopriv_copy_filemanager_files', 'copy_filemanager_files' );
function copy_filemanager_files($posts) {

  global $wp;

  $i = 0;
  $x = 0;
  $paths = $_POST['path'];
  $object_id = $_POST['inputVal'];

  foreach ($paths as $path) {
    $path_part = pathinfo($path);
    $path_implodes = explode('/', $object_id);
    $basename = $path_part['basename'];
    $filename = $path_part['filename'];
    $fileext = $path_part['extension'];

    foreach ($path_implodes as $path_implode) {
      $path_implode_count .= '/'.$path_implode;
      if(!folder_exist($path_implode_count)) {
        mkdir($path_implode_count);
      }
      $x++;
    }
    
    if ($path != '') {
      if (!file_exists($object_id.$basename)) {
        if (copy($path, $object_id.$basename) == false){
          $html[$i][0] .= $path;
          $html[$i][1] .= 'false';
          $i++;
        }
      } else {
        $filenamecopy = $filename.'-copy.'.$fileext;
        if (copy($path, $object_id.$filenamecopy) == false){
            $html[$i][0] .= $path;
            $html[$i][1] .= 'File already existe';
            $i++;
        }     
      }
    }
  }

  return wp_send_json ( $html );

}

/* AJAX action callback */
add_action( 'wp_ajax_moveto_filemanager_files', 'moveto_filemanager_files' );
add_action( 'wp_ajax_nopriv_moveto_filemanager_files', 'moveto_filemanager_files' );
function moveto_filemanager_files($posts) {

  global $wp;

  $i = 0;
  $paths = $_POST['path'];
  $object_id = $_POST['inputVal'];

  foreach ($paths as $path) {
    $path_part = pathinfo($path);
    $path_implodes = explode('/', $object_id);
    $basename = $path_part['basename'];
    $filename = $path_part['filename'];
    $fileext = $path_part['extension'];

    foreach ($path_implodes as $path_implode) {
      $path_implode_count .= '/'.$path_implode;
      if(!folder_exist($path_implode_count)) {
        mkdir($path_implode_count);
      }
      $x++;
    }

    if (rename($path, $object_id.$basename) == false){
      $html[$i][0] .= $path;
      $html[$i][1] .= 'false';
      $i++;
    }
  }

  return wp_send_json ( $html );

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
          try {
            $html .= "<div class='info-window-count-size'>" . formatSizeUnits(GetDirectorySize($path)) . "</div>";
          } catch (Exception $e) {
            $html .= $e.'<br>';
          }
        } else {
          $html .= "<div class='info-window-size'>" . formatSizeUnits(filesize($path)) . "</div>";
        }
      $html .= "</div>";
    }
  }

  if($html != null) {
    return wp_send_json ( $html );
  } else {
    return wp_send_json ( '' );
  }

}

/* AJAX action callback */
add_action( 'wp_ajax_zip_filemanager_files', 'zip_filemanager_files' );
add_action( 'wp_ajax_nopriv_zip_filemanager_files', 'zip_filemanager_files' );
function zip_filemanager_files($posts) {

  $paths = array_values(array_filter($_POST['path']));
  $inputVal = $_POST['inputVal'];
  $direname = pathinfo($paths[0]);
  $zipfilepath = $direname['dirname'].'/'.$inputVal;
  $zip = new ZipArchive();

  $html[] = $zipfilepath;
  $zip->open($zipfilepath, ZipArchive::CREATE);
  foreach ( $paths as $path ) {
      if(is_dir($path)) {
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);
        foreach ($iterator as $info) {
          if($info->getFilename() != "." && $info->getFilename() != "..") {
            $workingdir = str_replace($direname['dirname'], '',  $info->getPathname());
            $workingdirltrim = ltrim($workingdir, '/');
            $zip->addFile($info->getPathname(), $workingdirltrim);
            $html[] .= $workingdirltrim;
          }
        }
      } else {
        $path_parts = pathinfo($path);
        $zip->addFile($path, $path_parts['basename']);
        $html[] .= $path_parts['basename'];
      }
  }
  $zip->close();

  return wp_send_json ( $html );

}

/* AJAX action callback */
add_action( 'wp_ajax_share_filemanager_files', 'share_filemanager_files' );
add_action( 'wp_ajax_nopriv_share_filemanager_files', 'share_filemanager_files' );
function share_filemanager_files($posts) {

  $path = $_POST['path'];
  $path_parts = pathinfo($path);
  $sharekey = getName(32);

  $new_post = array(
  'post_title' => $path_parts['basename'],
  'post_content' => '',
  'post_status' => 'publish',
  'post_author' => get_current_user_id(),
  'post_type' => 'shares'
  );

  $post_id = wp_insert_post($new_post);

  add_post_meta( $post_id, '_share_key', $sharekey);
  $html[0] = $sharekey;

  add_post_meta( $post_id, '_share_path', $path );
  if(is_dir($path)) {
    $html[1] = $path;
  }

  $array['-1']['read'] = true;
  add_post_meta( $post_id, '_share_right', $array );

  return wp_send_json ( $html );

}

/* AJAX action callback */
add_action( 'wp_ajax_search_filemanager_files', 'search_filemanager_files' );
add_action( 'wp_ajax_nopriv_search_filemanager_files', 'search_filemanager_files' );
function search_filemanager_files($posts) {

  $path = $_POST['path'];
  $inputVal = $_POST['inputVal'];

  $html[] = "<div class='info-window'>";
  $html[] .= "<div class='info-window-close'>X</div>";
  try{
    $dir = new DirectoryIterator($path);
    foreach ($dir as $fileinfo) {
      if ($fileinfo->isDir()) {
        $dirname = $fileinfo->getFilename();
        if (stripos($dirname, esc_attr($inputVal)) || $dirname == $inputVal) {
          $html[] .= $fileinfo->getPathname().'<br>';
        }
      }
    }
    $directory = new \RecursiveDirectoryIterator($path);
    $iterator = new \RecursiveIteratorIterator($directory);
    foreach ($iterator as $info) {
      $filename = $info->getFilename();
      if (stripos($filename, esc_attr($inputVal)) || $filename == $inputVal) {
        $html[] .= $info->getPathname().'<br>';
      }
    }
  } catch (Exception $e) {
    $html[] .= $e.'<br>';
  }
  $html[] .= "</div>";

  return wp_send_json ( implode($html) );

}

/* AJAX action callback */
add_action( 'wp_ajax_tree_get_filemanager_files', 'tree_get_filemanager_files' );
add_action( 'wp_ajax_nopriv_tree_get_filemanager_files', 'tree_get_filemanager_files' );
function tree_get_filemanager_files($posts) {

  global $wp;
  $path = $_POST['path'];
  $link = $_POST['link'];
  $urlParams = $_POST['url_Params'];
  $sharekey = $_POST['sharekey'];

  $ffs = scandir($path);
                    
  unset($ffs[array_search('.', $ffs, true)]);
  unset($ffs[array_search('..', $ffs, true)]);

  // prevent empty ordered elements
  if (count($ffs) < 1)
      return;

      $meta_name = 'oauth-'.$_COOKIE['uniqid'];
      $get_oauth = get_post_meta($_COOKIE['uniqid'], $meta_name, true);
      foreach($get_oauth as $oauth){
          $currentTime = time();  
          $expTime = strtotime('+1 hour', $oauth[0]);       
          if($currentTime >= $expTime) {
              $_data[] .= $oauth;
          }
      }

      foreach($ffs as $ff){
        if(is_dir($path.'/'.$ff)) {
            $html[] = '<li class="isfolder" path="'.$path.'/'.$ff.'">'.$ff.'</li>';
            $html[] = '<ul></ul>';
        } else {       
          $getname = getName(32);                            
          $realpath = realpath($path.'/'.$ff);
          $getoauth = time().'||'.$getname.'||'.$realpath;
          if ($urlParams == 'sharepath') {
              $html[] = "<li><a href='" . $link . "?share=" . $sharekey . "&sharepath=" . $getname . "'>$ff</a></li>";
          } else {
            $html[] = "<li><a href='" . $link . "?".$urlParams."=" . $getname . "'>$ff</a></li>";
          }
          $_data[] .= $getoauth;
      }
    }

  update_post_meta($_COOKIE['uniqid'], $meta_name, $_data);

  return wp_send_json ( implode($html) );

}


/* AJAX action callback */
add_action( 'wp_ajax_viewtype_filemanager_files', 'viewtype_filemanager_files' );
add_action( 'wp_ajax_nopriv_viewtype_filemanager_files', 'viewtype_filemanager_files' );
function viewtype_filemanager_files($posts) {
  $viewtype = $_POST['viewtype'];
  $userid = $_POST['userid'];

  update_user_meta($userid, 'view_type_', $viewtype);

  return wp_send_json ( $viewtype );

}

?>