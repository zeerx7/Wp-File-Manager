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

  if ($urlParams == 'path') {
    $path = $_POST['object_id'];
  }
  if ($urlParams == 'home') {
    $home = $_POST['object_id'];
  }
  if ($urlParams == 'workplace') {
    $workplace = $_POST['object_id'];
  }
  if ($urlParams == 'sharepath') {
    $sharepath = $_POST['object_id'];
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
    $posts = get_posts( array(
      'post_type'      => 'workplace',
      'posts_per_page' => -1,
      'orderby'        => 'modified',
      'no_found_rows'  => true
    ));
    foreach($posts as $post) {
        $title = get_the_title($post->ID); 
        $workplacepath[$post->ID] = get_post_meta( $post->ID, "_workplace_path", true);
        $workplaceright[$post->ID] = get_post_meta( $post->ID, "_workplace_right", true);
    }    
    foreach ($workplacepath as $id_path) {
        $id_path_ = rtrim($id_path, "/");
        $workplace_ = rtrim($workplace, "/");
        if (strpos($workplace_, $id_path_) !== false) {
            $workplace_strpos = true;
            if ($workplace_ == $id_path_) {
                $workplace_last = true;
            }
            foreach($workplaceright as $userright) {
                if($userright[$user->ID]['read'] == 1) {
                    $read_path = true;
                }
                if($userright[$user->ID]['write'] == 1) {
                    $write_path = true;
                }
            }
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

  if (isset($share) && isset($sharepath)) {
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'shares',
        'meta_query' => array(
            array(
                'key' => '_share_key',
                'value' => $share,
                'compare' => 'LIKE'
            )
        )
    );

    $my_query = get_posts( $args );

    if ($my_query) {
        foreach ( $my_query as $post ) { 
            $path_share = get_post_meta( $post->ID, '_share_path', true);
            $share_right = get_post_meta( $post->ID, '_share_right', true);
        }                   
    }

    if ($path_share) {
        $id_path_ = rtrim($path_share, "/");
        $sharepath_ = rtrim($sharepath, "/");
        if (strpos($sharepath_, $id_path_) !== false) {
            $workplace_strpos = true;
        }
        if ($sharepath_ == $id_path_) {
            $workplace_last = true;
        }
        if($share_right['read'] == 1){
            $read_path = true;
        }
        if($share_right['write'] == 1){
            $write_path = true;
        }
        $path_implode = $sharepath;
    }
  }

    if ($workplace_strpos == true && $read_path == true){
      if ($dh = opendir($path_implode)) {
        while (($file = readdir($dh)) !== false) {
            $allFiles[] = $file;
        }
        closedir($dh);
      } 
    }
    $files = array_diff($allFiles, array('.', '..'));
    $path_parts = explode("/", $path_implode);

    sort($files);

    if ($write_path == true) {
      $html[] = "<div id='filemanagerbtnup' class='filemanagerbtn'>
        <div class='navbar'>
            <div id='uploadfiles' onclick='myFile()'><input type='file' id='pickerfiles' name='fileList' multiple style='display: none;'>Upload files</div>
            <div id='uploaddir' onclick='mydir()'><input type='file' id='pickerdir' name='fileList' webkitdirectory multiple style='display: none;'>Upload Dir</div>
            <div class='subnav'>
                <button class='subnavbtn btnnewfile'>Create file</button>
                <div id='subnav-content-file' class='subnav-content'>
                    <span>
                        <input type='text' id='lnamefile' name='lname'></input>
                        <button class='newfile'>Create</button>
                    <span>
                </div>
            </div>
            <div class='subnav'>
                <button class='subnavbtn btnnewdir'>Create dir</button>
                <div id='subnav-content-dir' class='subnav-content'>
                    <span>
                        <input type='text' id='lname' name='lname'></input>
                        <button class='newdir'>Create</button>
                    <span>
                </div>
            </div>
            <div class='subnav'>
              <button class='subnavbtn btncopy'>Copy</button>
              <div id='subnav-content-copy' class='subnav-content'>
                  <span>
                      <input type='text' id='lnamecopy' name='lname'></input>
                      <button class='copy'>Copy</button>
                  <span>
              </div>
            </div>
            <div class='subnav'>
                <button class='subnavbtn btnmoveto'>Move</button>
                <div id='subnav-content-moveto' class='subnav-content'>
                    <span>
                        <input type='text' id='lnamemoveto' name='lname'></input>
                        <button class='moveto'>move</button>
                    <span>
                </div>
            </div>
            <div class='btnrename'>Rename</div>";
            $html[] .= "<div class='btndelete'>Delete</div>";
            $html[] .= "<div class='subnav subnavzip'>
              <button class='subnavbtn btnzip'>Create zip</button>
              <div id='subnav-content-zip' class='subnav-content'>
                  <span>
                      <input type='text' id='lnamezip' name='lname'></input>
                      <button class='zipbtn'>create</button>
                  <span>
              </div>
          </div>
          <div class='subnav subnavzip'>
            <button class='subnavbtn btnshare'>Share dir</button>
            <div id='subnav-content-share' class='subnav-content'>
                <span>
                    <input type='text' id='lnameshare' readonly></span>
                    <button class='newsharelink'>Create share link</button>
                <span>
            </div>
          </div>
        </div>
      </div>";
    }

    $html[] .= "<div id='filemanagerbtn' class='filemanagerbtn'>
                  <div class='navbar'>";
                          if ($path_parts[1] != '' && $workplace_strpos == true && $workplace_last != true){
                              if (isset($home)) { $html[] .= "<a class='btnback_' href='" . $link . "?home=" . dirname($path_implode) . "'>Parent directory</a>"; }
                              if (isset($workplace)) {  $html[] .= "<a class='btnback_' href='" . $link . "?workplace=" . dirname($path_implode) . "'>Parent directory</a>"; }
                              if (isset($path)) {  $html[] .= "<a class='btnback_' href='" . $link . "?path=" . dirname($path_implode) . "'>Parent directory</a>"; }
                              if (isset($sharepath)) { $html[] .= "<a class='btnback_' href='" . $link . "?share=" . $share . "&sharepath=" . dirname($path_implode) . "'>Parent directory</a>"; }
                          } 
                          if ($path_parts[1] == '' || $workplace_last == true || $workplace_strpos != true) {
                          $html[] .= "<a class='btnback_home' href='" . $link . "/'>Home</a>";
                          }
                          $html[] .= "<div class='btninfo'>Info</div>";
                          $html[] .= "<div class='subnav subnavsearch'>
                                        <button class='subnavbtn btnsearch'>Search</button>
                                        <div id='subnav-content-search' class='subnav-content'>
                                            <span>
                                                <input type='text' id='lnamesearch'></span>
                                                <button class='newsearch'>Search</button>
                                            <span>
                                        </div>
                                      </div>";
                        $html[] .= "</div>";
                      $html[] .= "</div>";
              $html[] .= "<div class='filepath'>";
              foreach($path_parts as $path_part) {
                  $path_part_ .= '/'.$path_part;
                  if (isset($home)) { $html[] .= "<a href='" . $link . "?home=" .  realpath($path_part_) . "'>" . $path_part . "</a>"; }
                  if (isset($workplace)) { $html[] .= "<a href='" . $link . "?workplace=" .  realpath($path_part_) . "'>" . $path_part . "</a>"; }
                  if (isset($path)) { $html[] .= "<a href='" . $link . "?path=" .  realpath($path_part_) . "'>" . $path_part ."</a>"; }
                  if (isset($sharepath)) { $html[] .= "<a href='" . $link . "?share=" . $share . "&sharepath=" .  realpath($path_part_) ."'>" . $path_part . "</a>"; }
                  $html[] .= '/';
              }

              $filecount = 0;
              $directorycount = 0;
              foreach($files as $file){
                  $realpath = realpath($path_implode.'/'.$file);
                  if(is_dir($realpath)) {
                      $directorycount++;
                  } else {
                      $filecount++;
                  }
              }
              $html[] .= "<div class='filepathcount'>";
              $html[] .= count($files);
              if(count($files) <= 1) {
                $html[] .= ' Element : ';
              } else {
                $html[] .= ' Elements : ';
              }
              $html[] .= $directorycount;
              if($directorycount <= 1) {
                $html[] .= ' Directorie - ';
              } else {
                $html[] .= ' Directories - ';
              }
              $html[] .= $filecount;
              if($filecount <= 1) {
                $html[] .= ' File ';
              } else {
                $html[] .= ' Files ';
              }
              $html[] .= "</div></div>";
              $html[] .= "<div class='file-table'><table id='file-table'>";
              $html[] .= "<tr></td><td class='checkboxall'><input class='checkboxall' type='checkbox' name=''/></td><td class='checkboxall'>Filename</td><td class='checkboxall'>Size</td></tr>";
                  foreach($files as $file){
                      $pathfilezise = $path_implode.'/'.$file;
                      $filesize = formatSizeUnits(filesize($pathfilezise));
                      $realpath = realpath($path_implode.'/'.$file);
                      if ( is_dir($realpath) == true ) {
                        if (isset($home)) {
                          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "?home=$realpath'>$file</a></td><td>$filesize</td></tr>";
                        }
                        if (isset($workplace)) { 
                          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "?workplace=$realpath'>$file</a></td><td>$filesize</td></tr>";
                        }
                        if (isset($path)) {
                          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "?path=$realpath'>$file</a></td><td>$filesize</td></tr>";
                        }
                        if (isset($sharepath)) {
                          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "/?share=$share&sharepath=$realpath'>$file</a></td><td>$filesize</td></tr>";
                        }
                      } else {
                        $getname = getName(32);
                        $getoauth = uniqid(time().'||'.$getname.'||'.$realpath.'||'.$_SERVER["HTTP_CF_CONNECTING_IP"].'||',TRUE);
                        if (isset($home)) {
                          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "?home=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
                        }
                        if (isset($workplace)) { 
                          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "?workplace=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
                        }
                        if (isset($path)) {
                          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "?path=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";                        
                        }
                        if (isset($sharepath)) {
                          $html[] .= "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . $link . "/?share=$share&sharepath=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
                        }
                      $TOKEN_DIR = get_home_path() . 'wp-content/plugins/file-manager/includes/tokens';

                      // Create a protected directory to store keys
                      if(!is_dir($TOKEN_DIR)) {
                          mkdir(TOKEN_DIR);
                          $file = fopen($TOKEN_DIR.'/.htaccess','w');
                          fwrite($file,"Order allow,deny\nDeny from all");
                          fclose($file);
                      }
                      
                      // Write the key to the keys list
                      $file = fopen($TOKEN_DIR.'/oauth','a');
                      fwrite($file, "{$getoauth}\n");
                      fclose($file);
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

  if (!is_dir($link)) {
    $getname = getName(32);
    $getoauth = uniqid(time().'||'.$getname.'||'.$new_filename.'||'.$_SERVER["HTTP_CF_CONNECTING_IP"].'||',TRUE);
    $TOKEN_DIR = get_home_path() . 'wp-content/plugins/file-manager/includes/tokens';

    // Create a protected directory to store keys
    if(!is_dir($TOKEN_DIR)) {
        mkdir(TOKEN_DIR);
        $file = fopen($TOKEN_DIR.'/.htaccess','w');
        fwrite($file,"Order allow,deny\nDeny from all");
        fclose($file);
    }
    
    // Write the key to the keys list
    $file = fopen($TOKEN_DIR.'/oauth','a');
    fwrite($file, "{$getoauth}\n");
    fclose($file);
  }

  if(rename($path, $new_filename) == true) {
    if (!is_dir($new_filename)) {
      $html[] = "<a id='file-id' class='filemanager-click' href='$link?path=$new_filename&oauth=$getname'>$filename</a>";
    } else {
      $html[] = "<a id='file-id' class='filemanager-click' href='$link?path=$new_filename'>$filename</a>";
    }
  } else {
    $html[] = "error";
  }

	return wp_send_json ( implode($html) );

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

  $array['read'] = true;
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