<?php

function get_home_path_() {
    $home    = set_url_scheme( get_option( 'home' ), 'http' );
    $siteurl = set_url_scheme( get_option( 'siteurl' ), 'http' );
 
    if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
        $wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
        $pos                 = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
        $home_path           = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
        $home_path           = trailingslashit( $home_path );
    } else {
        $home_path = ABSPATH;
    }
 
    return str_replace( '\\', '/', $home_path );
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

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function filemanager_shortcode() { 

    $path = realpath($_GET['path']);

    if ($path == null) {
        $path = realpath(get_home_path_());
    }

    $allFiles = scandir($path);
    $files = array_diff($allFiles, array('.', '..'));

    $path_parts = explode("/", $path);

    global $wp;

    if ( (is_dir($path) == true) && (is_user_logged_in()) ) {
        echo "<div id='sequentialupload' class='sequentialupload' data-object-id='$path'></div>"; 
    }
    echo "<div id='filemanager-wrapper' class='filemanager-wrapper'>";
    if ( (is_dir($path) == true) && (is_user_logged_in()) ) {
        ?><script type="text/javascript">document.getElementById("sequentialupload").style.display = "none";</script><?php
        ?><div id='filemanagerbtn' class='filemanagerbtn'>
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
                    <div class='btninfo'>info</div>
                </div>
            </div>
            <div id='filemanagerbtn' class='filemanagerbtn'>
                <div class='navbar'>
                    <a class='btnback_' href='<?php echo home_url($wp->request) . "/?path=" . dirname($path) ?>'>Parent directory</a>
                </div>
            </div>
            <div class='filepath'><?php foreach($path_parts as $path_part) {
                    $path_part_ .= $path_part.'/';
                    ?><a href='<?php  echo home_url($wp->request) . "/?path=" .  $path_part_ ?>'><?php echo $path_part; ?></a><?php
                    echo '/';
                }?></div>
            <?php
    }
    if ( is_dir($path) == true ) {
        ?><div class='file-table'><table id='file-table'><?php
            foreach($files as $file){
                $pathfilezise = $path.'/'.$file;
                $filesize = formatSizeUnits(filesize($pathfilezise));
                $realpath = realpath($path.'/'.$file);
                if ( is_dir($path.'/'.$file) == true ) {
                    echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?path=$realpath'>$file</a></td><td>$filesize</td></tr>";
                } else {
                    echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?path=$realpath'>$file</a></td><td>$filesize</td></tr>";
                }
            }
        echo "</table></div>";
    } else {
        $object_id = $path;
        $getname = getName(32);
        $target =  $object_id;
        $direname = dirname($object_id);
        $basename = strtolower(basename($object_id));
        $ext = pathinfo($basename, PATHINFO_EXTENSION);

        ?><script type="text/javascript">document.getElementById("sequentialupload").style.display = "none";</script><?php

        $link = get_home_path_() .'/files/'. $getname .'.'. $ext;
        echo exec('mkdir "'. get_home_path_() .'/files"');
        echo exec('rm "'. $link .'"');
        echo exec('ln -s "' . $target . '" "' . $link .'"');

        if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'bmp') {
            echo '<img src="' . get_home_url() . '/files/' . $getname . '.' . $ext . '"></img>';
        } elseif ($ext == 'mp4' || $ext == 'mkv' || $ext == 'avi' ) {
            echo '<div class="file-info">' . basename($object_id) . '</div>';
            echo '<div id="dplayer"></div>';
            ?><script type="text/javascript">
            window.dp1 = new DPlayer({
                container: document.getElementById('dplayer'),
                preload: 'none',
                volume: 0.7,
                screenshot: true,
                video: {
                    url:  '<?php echo get_home_url() . '/files/' . $getname . '.' . $ext ?>',
                    pic:  null,
                    thumbnails: null
                },
                subtitle: {
                    url: null
                }
            });</script><?php
        } elseif ($ext == 'pdf') {
            echo '<div id="pdf"></div>';
            ?><script type="text/javascript">PDFObject.embed('<?php echo get_home_url() . '/files/' . $getname . '.' . $ext ?>', "#pdf");</script><?php
        } else {
            echo '<a href="' . get_home_url() . '/files/' . $getname . '.' . $ext . '">Download</a>';
        }
    }
    echo "</div>";

}
    
add_shortcode('filemanager-shortcode', 'filemanager_shortcode');

?>