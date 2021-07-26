<?php

function filemanager_shortcode() { 

    $path = "/var/www/";
    $files = scandir($path);
    echo "<div class='filemanager-wrapper'>";
        foreach($files as $file){
        echo "<div id='file-id' class='filemanager-click' data-object-id='$path$file'>$file</div></br>";
        }
    echo "</div>";

}
    
add_shortcode('filemanager-shortcode', 'filemanager_shortcode');

?>