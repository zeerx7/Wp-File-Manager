<?php

function filemanager_shortcode() { 

    $path = get_home_path();
    $files = scandir($path);
    echo "<div id='sequentialupload' data-object-id='$path'></div>";
    echo "<div class='filemanager-wrapper'>";
        echo "<table>";
            foreach($files as $file){
                $pathfilezise = $path.'/'.$file;
                $filesize = filesize($pathfilezise);
                if ( is_dir($path.'/'.$file) == true ) {
                    echo "<tr><td><input type='checkbox' name='$file'/></td><td id='file-id' class='filemanager-click' data-object-id='$path$file'>$file</td><td>$filesize</td></tr>";
                } else {
                    echo "<tr><td><input type='checkbox' name='$file'/></td><td id='file-id' class='filemanager-click-file' data-object-id='$path$file'>$file</td><td>$filesize</td></tr>";
                }
            }
        echo "</table>";
    echo "</div>";

}
    
add_shortcode('filemanager-shortcode', 'filemanager_shortcode');

?>