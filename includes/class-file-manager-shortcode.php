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

function filemanager_shortcode() { 

    $path = $_GET['path'];

    if ($path == null) {
        $path = get_home_path_();
    }

    $files = scandir($path);
    global $wp;

    echo "<div id='sequentialupload' class='sequentialupload' data-object-id='$path'></div>"; 
    echo "<div id='filemanager-wrapper' class='filemanager-wrapper'>";
    echo "<div id='filemanagerbtn'><div class='navbar'><a class='btndelete'>Delete</a><div class='subnav'><button class='subnavbtn btnnewdir'>Create dir</i></button><div id='subnav-content' class='subnav-content'><span><input type='text' id='lname' name='lname'></input><button class='newdir'>ok</button><span></div></div></div></div>";
    if ( is_dir($path.'/'.$file) == true ) {
        echo "<table>";
            foreach($files as $file){
                $pathfilezise = $path.'/'.$file;
                $filesize = filesize($pathfilezise);
                $realpath = realpath($path.'/'.$file);
                if ( is_dir($path.'/'.$file) == true ) {
                    echo "<tr><td><input class='checkbox' type='checkbox' name='$path/$file'/></td><td><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?path=$realpath'>$file</a></td><td>$filesize</td></tr>";
                } else {
                    echo "<tr><td><input class='checkbox' type='checkbox' name='$path/$file'/></td><td><a id='file-id' class='filemanager-click-file' href='" . home_url($wp->request) . "/?path=$realpath'>$file</a></td><td>$filesize</td></tr>";
                }
            }
        echo "</table>";
    } else {
        $object_id = $path;
        $getname = getName(32);
        $target =  $object_id;
        $direname = dirname($object_id);
        $basename = strtolower(basename($object_id));
        $ext = pathinfo($basename, PATHINFO_EXTENSION);

        $link = get_home_path_() .'/files/'. $getname .'.'. $ext;
        echo exec('mkdir "'. get_home_path_() .'/files"');
        echo exec('rm "'. $link .'"');
        echo exec('ln -s "' . $target . '" "' . $link .'"');

        if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'bmp') {
            echo '<img src="' . get_home_url() . '/files/' . $getname . '.' . $ext . '"></img>';
        }

        if ($ext == 'mp4' || $ext == 'mkv' || $ext == 'avi' ) {
            echo '<video width="100%" height="575" controls src="' . get_home_url() . '/files/' . $getname . '.' . $ext . '" type="video/' . $ext . '"></video>';
        }

        if ($ext == 'pdf') {
            echo '<div id="pdf"></div>';
            ?><script type="text/javascript">PDFObject.embed('<?php echo get_home_url() . '/files/' . $getname . '.' . $ext ?>', "#pdf");</script><?php
        }
    }
    echo "</div>";

}
    
add_shortcode('filemanager-shortcode', 'filemanager_shortcode');

?>