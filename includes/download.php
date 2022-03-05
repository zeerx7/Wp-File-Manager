<?php

// Include the configuration file
require_once 'config.php';

//Include the wp-load.php file
require_once realpath('../../../../wp-load.php');
require_once realpath('../../../../wp-blog-header.php');

// Get the file ID & key from the URL
$read_path = true;
$arg = trim($_GET['arg']);
$key = trim($_GET['key']);
$uniqid = trim($_GET['uniqid']);

if ( is_user_logged_in() ) {
    $user = wp_get_current_user();   
} else {
    $user->ID = '-1';
}

if($arg == 'share'){
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'shares',
        'meta_query' => array(
            array(
                'key' => '_share_key',
                'value' => $key,
                'compare' => 'LIKE'
            )
        )
    );
    
    $my_query = get_posts( $args );
    
    if ($my_query) {
        foreach ( $my_query as $post ) { 
            $share_path = get_post_meta( $post->ID, '_share_path', true);
            $share_right = get_post_meta( $post->ID, '_share_right', true);
        }        
        if($share_right[-1]['read'] == 0) {
            $read_path = false;
        }
        if($share_right[$user->ID]['read'] == 0) {
            $read_path = false;
        }          
        if($share_right[-1]['read'] == 1) {
            $read_path = true;
        } 
        if($share_right[$user->ID]['read'] == 1) {
            $read_path = true;
        }    
    }
}

// Retrieve the keys from the tokens file
$meta_name = 'oauth-'.$uniqid;
$keys = get_post_meta($uniqid, $meta_name, true);
$match = false;

// Loop through the keys to find a match
// When the match is found, remove it
foreach($keys as &$one){
    $keyone = explode('||',$one);
    $currentTime = time();  
    if($key==$keyone[1]){
        $keyfetch = $keyone;
        $expTime = strtotime('+12 hours', $keyone[0]);       
        if($currentTime <= $expTime) {
            $path_implode = $keyone[2];
        }
    }
}

if($arg == 'workplace') {
    $posts = get_posts( array(
        'post_type'      => 'workplace',
        'posts_per_page' => -1,
        'orderby'        => 'modified',
        'no_found_rows'  => true
    ));

    foreach($posts as $post) {
        $workplacepath = get_post_meta( $post->ID, "_workplace_path", true);
        $workplaceright = get_post_meta( $post->ID, "_workplace_right", true);
        $id_path_ = rtrim($workplacepath, "/");
        $workplace_ = rtrim($path_implode, "/");

        if (strpos($workplace_, $id_path_) !== false) {
            if($workplaceright[-1]['read'] == 0) {
                $read_path = false;
            }
            if($workplaceright[$user->ID]['read'] == 0) {
                $read_path = false;
            }
            if($workplaceright[-1]['read'] == 1) {
                $read_path = true;
            }
            if($workplaceright[$user->ID]['read'] == 1) {
                $read_path = true;
            }
        }
    }

}

if($read_path == false) {
    $match = false;
} else if ($read_path == true) {
    $match = true;
}

if(!isset($arg)) {
    $match = false;
}

// If match found and the link is not expired
if($match !== false && $currentTime <= $expTime){
    // If the file is found in the file's array
    if(!empty($keyfetch) && !is_dir($keyfetch[2])){
        // Get the file data
        $fileName = basename($keyfetch[2]);
        $filePath = $keyfetch[2];
        $path_parts = pathinfo($filePath);
        $ext = $path_parts['extension'];
        $contentType = 'application/'.$ext;

        if($ext == 'mp4' || $ext == 'mkv' || $ext == 'avi'){
            if (isset($_SERVER['HTTP_RANGE'])) {
                ob_get_clean();
                ob_start();
                $file = $filePath;
                $fp = @fopen($file, 'rb');

                $size   = filesize($file); // File size
                $length = $size;           // Content length
                $start  = 0;               // Start byte
                $end    = $size - 1;       // End byte
                
                header("Cache-Control: No-Store");
                header("Content-Disposition: attachment; filename=$fileName");
                header('Content-type: video/mp4');
                header("Accept-Ranges: 0-$length");
                header("Content-Transfer-Encoding: binary\n");
                header('Connection: close');
            
                $c_start = $start;
                $c_end   = $end;
            
                list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                if (strpos($range, ',') !== false) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$size");
                    exit;
                }
                if ($range == '-') {
                    $c_start = $size - substr($range, 1);
                }else{
                    $range  = explode('-', $range);
                    $c_start = $range[0];
                    $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $range[0]+(2048 * 1024);
                }
                $c_end = ($c_end > $end) ? $end : $c_end;
                if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes $start-$end/$size");
                    exit;
                }
                $start  = $c_start;
                $end    = $c_end;
                fseek($fp, $start);
                header('HTTP/1.1 206 Partial Content');
                header("Content-Range: bytes $start-$end/$size");
                $buffer = 2048 * 128;
                while(!feof($fp) && ($p = ftell($fp)) <= $end) {
                    if ($p + $buffer > $end) {
                        $buffer = $end - $p + 1;
                    }
                    set_time_limit(60);
                    echo fread($fp, $buffer);
                    flush();
                    ob_flush();
                }
                fclose($fp);
                exit();
            } else {
                $response = 'Video download are not allowed.';
            }
        } elseif($ext == 'pdf') {
            header("Content-Description: PDF");
            header("Content-type: $contentType");
            header("Content-Disposition: inline; filename=$fileName");
            readfile($filePath);
        } else {
            header("Content-Description: File Transfer");
            header("Content-type: $contentType");
            header("Content-Disposition: inline; filename=$fileName");
            readfile($filePath);
        }
    }else{
        $response = 'Download link is not valid.';
    }
}else{
    // If the file has been downloaded already or time expired
    $response = 'Download link is expired.';
}
if($response){
    ?>
    <html>
    <head>
        <title><?php echo $response; ?></title>
    </head>
    <body>
        <h1><?php echo $response; ?></h1>
    </body>
    </html>
    <?php
}