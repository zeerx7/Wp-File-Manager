<?php
// Include the configuration file
require_once 'config.php';

// Get the file ID & key from the URL
$key = trim($_GET['key']);

// Retrieve the keys from the tokens file
$keys = file(TOKEN_DIR.'/oauth');
$match = false;

// Loop through the keys to find a match
// When the match is found, remove it
foreach($keys as &$one){
    $keyone = explode('||',$one);
    $currentTime = time();  
    if($key==$keyone[1]){
        $keyfetch = $keyone;
        $expTime = strtotime(EXPIRATION_TIME, $keyone[0]);       
        if($currentTime <= $expTime) {
            $match = true;
        }
    }
}

// If match found and the link is not expired
if($match !== false && $currentTime <= $expTime){
    // If the file is found in the file's array
    if($keyfetch[3] == $_SERVER["HTTP_CF_CONNECTING_IP"]) {
        if(!empty($keyfetch)){
            // Get the file data
            $contentType = 'application/'.$ext;
            $fileName = basename($keyfetch[2]);
            $filePath = $keyfetch[2];
            $path_parts = pathinfo($filePath);
            $ext = $path_parts['extension'];

            if($ext == 'mp4' || $ext == 'mkv' || $ext == 'avi'){
                $file = $filePath;
                $fp = @fopen($file, 'rb');

                $size   = filesize($file); // File size
                $length = $size;           // Content length
                $start  = 0;               // Start byte
                $end    = $size - 1;       // End byte
                
                header("Content-Disposition: attachment; filename=\"{$fileName}\"");
                header('Content-type: video/mp4');
                header("Accept-Ranges: 0-$length");
                if (isset($_SERVER['HTTP_RANGE'])) {
                
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
                        $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
                    }
                    $c_end = ($c_end > $end) ? $end : $c_end;
                    if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                        header('HTTP/1.1 416 Requested Range Not Satisfiable');
                        header("Content-Range: bytes $start-$end/$size");
                        exit;
                    }
                    $start  = $c_start;
                    $end    = $c_end;
                    $length = $end - $start + 1;
                    fseek($fp, $start);
                    header('HTTP/1.1 206 Partial Content');
                }
                header("Content-Range: bytes $start-$end/$size");
                header("Content-Length: ".$length);
                
                
                $buffer = 1024 * 8;
                while(!feof($fp) && ($p = ftell($fp)) <= $end) {
                
                    if ($p + $buffer > $end) {
                        $buffer = $end - $p + 1;
                    }
                    set_time_limit(0);
                    echo fread($fp, $buffer);
                    flush();
                }
                
                fclose($fp);
            } elseif($ext == 'pdf') {
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename="' . $fileName . '"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                readfile($filePath);
            } else {
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=\"{$fileName}\"");
                header("Content-type: {$contentType}"); 
                header("Content-Length: " . filesize($filePath));
                header('Pragma: public');
                header("Expires: 0");
                readfile($filePath);
            }
            exit;
        }else{
            $response = 'Download link is not valid.';
        }
    }
}else{
    // If the file has been downloaded already or time expired
    $response = 'Download link is expired.';
}
?>

<html>
<head>
    <title><?php echo $response; ?></title>
</head>
<body>
    <h1><?php echo $response; ?></h1>
</body>
</html>