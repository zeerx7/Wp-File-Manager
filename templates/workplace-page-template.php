<?php /* Template Name: CustomPageT1 */ ?>
 
<?php get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
    <div id="loop-container" class="loop-container">
        <div class="page type-page status-publish hentry entry">
	        <article>
				<div class="post-container">
                <div class="post-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                </div>
                <div class="post-content">
                    <?php the_content(); ?>
                    <?php if ( is_user_logged_in() ) {
                        $user = wp_get_current_user();   
                    } else {
                        $user->ID = '-1';
                    }

                    $workplace = $_GET['workplaces'];
                    global $wp;
                    $workplace_strpos = false;
                    $workplace_last = false;
                    $read_path = false;
                    $write_path = false;
                    $i = 0;
                    $x = 0;

                    if (!isset($workplace)) {
                        $workplace_path = get_post_meta( get_queried_object_id(), '_workplace_path', true);
                        ?><script>location.href='?workplaces=<?php echo $workplace_path?>'</script><?php
                    }
                    
                    if (isset($workplace)) {
                        $workplacepath = get_post_meta( get_queried_object_id(), "_workplace_path", true);
                        $workplaceright = get_post_meta( get_queried_object_id(), "_workplace_right", true);
                        $id_path_ = rtrim($workplacepath, "/");
                        $workplace_ = rtrim($workplace, "/");
                        if (strpos($workplace_, $id_path_) !== false) {
                            $workplace_strpos = true;
                            if ($workplace_ == $id_path_) {
                                $workplace_last = true;
                            }
                            if($workplaceright[$user->ID]['read'] == 1) {
                                $read_path = true;
                            }
                            if($workplaceright[$user->ID]['write'] == 1) {
                                $write_path = true;
                            }
                        }
                        $path_implode = $workplace;
                    } 
                    
                    echo "<div id='sequentialupload' class='sequentialupload' data-object-id='$path_implode'></div>"; 
                    ?><script type="text/javascript">document.getElementById("sequentialupload").style.display = "none";</script><?php
                    ?><div id='filemanager-wrapper' class='filemanager-wrapper'><?php
                    if (!post_password_required($post->ID) && $workplace_strpos == true && $read_path == true){
                        if ($dh = opendir($path_implode)) {
                            while (($file = readdir($dh)) !== false) {
                                $allFiles[] = $file;
                            }
                            closedir($dh);
                        }
                        $files = array_diff($allFiles, array('.', '..'));
            
                        $perpage = 100;
                        $page = $_GET['pages'];
                        if(!$page) $page = 1;
                        $offset = ($page-1)*$perpage;
            
                        $total_files = sizeof($files);
                        $total_pages = ceil($total_files/$perpage);
                        $files = array_slice($files, $offset, $perpage);
            
                        sort($files);
            
                        $path_parts = explode("/", $path_implode);
                        if ( is_dir($path_implode) == true ) {
                            if ($write_path == true) { ?>
                                <div id='filemanagerbtnup' class='filemanagerbtn'>
                                    <div class='navbar'>
                                        <div id="uploadfiles" onclick="myFile()"><input type="file" id="pickerfiles" name="fileList" multiple style="display: none;">Upload files</div>
                                        <div id="uploaddir" onclick="mydir()"><input type="file" id="pickerdir" name="fileList" webkitdirectory multiple style="display: none;">Upload Dir</div>
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
                                        <div class='btnrename'>Rename</div>
                                        <div class='btndelete'>Delete</div>
                                        <div class='subnav subnavzip'>
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
                                </div>
                            <script type="text/javascript">document.getElementById("filemanagerbtnup").style.display = "block";</script>
                            <?php } ?>
                                <div id='filemanagerbtndown' class='filemanagerbtn'>
                                    <div class='navbar'>
                                        <?php if ($path_parts[1] != '' && $workplace_strpos == true && $workplace_last != true){
                                                if (isset($home)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?home=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                                if (isset($workplace)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?workplaces=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                                if (isset($path)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?path=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                                if (isset($sharepath)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request)  . "/?share=" . $share . "&sharepath=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                            } 
                                            if ($path_parts[1] == '' || $workplace_last == true || $workplace_strpos != true) { ?>
                                            <a class='btnback_home' href='<?php echo home_url($wp->request) ?>/'>Home</a>
                                        <?php } ?>
                                        <div class='subnav subnavsearch'>
                                            <button class='subnavbtn btnsearch'>Search</button>
                                            <div id='subnav-content-search' class='subnav-content'>
                                                <span>
                                                    <input type='text' id='lnamesearch'></span>
                                                    <button class='newsearch'>Search</button>
                                                <span>
                                            </div>
                                        </div>
                                        <div class='btninfo'>Info</div>
                                    </div>
                                </div>
                                <?php $path_parts_count = count($path_parts); ?>
                                <?php $p = 1; ?>
                                <div class='filepath'><?php foreach($path_parts as $path_part) {
                                    $path_part_ .= '/'.$path_part;
                                    if (isset($home)) { ?><a href='<?php  echo home_url($wp->request) . "/?home=" .  realpath($path_part_)?>'><?php echo $path_part; ?></a><?php }
                                    if (isset($workplace)) { ?><a href='<?php  echo home_url($wp->request) . "/?workplaces=" .  realpath($path_part_)?>'><?php echo $path_part; ?></a><?php }
                                    if (isset($path)) { ?><a href='<?php  echo home_url($wp->request) . "/?path=" .  realpath($path_part_)?>'><?php echo $path_part; ?></a><?php }
                                    if (isset($sharepath)) { ?><a href='<?php  echo home_url($wp->request) . "/?share=" . $share . "&sharepath=" .  realpath($path_part_)?>'><?php echo $path_part; ?></a><?php }
                                    if($p != $path_parts_count){
                                        echo '/';
                                    } 
                                    $p++;
                                }
                                ?><div class='filepathcount'><?php
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
                                echo count($files);
                                if(count($files) <= 1) {
                                    echo ' Element : ';
                                } else {
                                    echo ' Elements : ';
                                }
                                echo $directorycount;
                                if($directorycount <= 1) {
                                    echo ' Directorie - ';
                                } else {
                                    echo ' Directories - ';
                                }
                                echo $filecount;
                                if($filecount <= 1) {
                                    echo ' File ';
                                } else {
                                    echo ' Files ';
                                }
                                ?></div></div><?php
                                ?><div class='file-table'><table id='file-table'>
                                    <tr></td><td class='checkboxall'><input class='checkboxall' type='checkbox' name=''/></td><td class='checkboxall'>Filename</td><td class='checkboxall'>Size</td></tr><?php
                                    foreach($files as $file){
                                        $pathfilezise = $path_implode.'/'.$file;
                                        $filesize = formatSizeUnits(filesize($pathfilezise));
                                        $realpath = realpath($path_implode.'/'.$file);
                                        if ( is_dir($realpath) == true ) {
                                            if (isset($home)) {
                                                echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?home=$realpath'>$file</a></td><td>$filesize</td></tr>";
                                            }
                                            if (isset($workplace)) { 
                                                echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?workplaces=$realpath'>$file</a></td><td>$filesize</td></tr>";
                                            }
                                            if (isset($path)) {
                                                echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?path=$realpath'>$file</a></td><td>$filesize</td></tr>";
                                            }
                                            if (isset($sharepath)) {
                                                echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?share=$share&sharepath=$realpath'>$file</a></td><td>$filesize</td></tr>";
                                            }
                                        } else {
                                            $getname = getName(32);
                                            $getoauth = uniqid(time().'||'.$getname.'||'.$realpath.'||'.$_SERVER["HTTP_CF_CONNECTING_IP"].'||',TRUE);
                                            if (isset($home)) {
                                                echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?home=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
                                            }
                                            if (isset($workplace)) { 
                                                echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?workplaces=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
                                            }
                                            if (isset($path)) {
                                                echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?path=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
                                            }
                                            if (isset($sharepath)) {
                                                echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?share=$share&sharepath=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
                                            }
                                            
                                                // Include the configuration file
                                                require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/config.php';
            
                                                // Create a protected directory to store keys
                                                if(!is_dir(TOKEN_DIR)) {
                                                    mkdir(TOKEN_DIR);
                                                    $file = fopen(TOKEN_DIR.'/.htaccess','w');
                                                    fwrite($file,"Order allow,deny\nDeny from all");
                                                    fclose($file);
                                                }
                                                
                                                // Write the key to the keys list
                                                $file = fopen(TOKEN_DIR.'/oauth','a');
                                                fwrite($file, "{$getoauth}\n");
                                                fclose($file);
                                            }
                                    }
                                    if ($files == null) {
                                        echo "<tr><td><input class='checkbox' type='checkbox' /></td><td class='filemanager-table'><a id='file-id' class='filemanager-click'>No file found</a></td></tr>";
                                    }
                                echo "</table></div>";

                                if (isset($home)) { $arg = 'home'; }

                                if (isset($workplace)) { $arg = 'workplaces'; }
            
                                if (isset($path)) { $arg = 'path'; }
            
                                if (isset($share)) { $arg = 'share'; }
            
                                if($total_pages > 1) {
                                    echo '<div class="filemanagerpagination">';
                                    if (isset($share)) {
                                        echo '<a class="page" href="?'.$arg.'='.$share.'&sharepath='.$sharepath.'&pages='.(($page-1>1)?($page-1):1).'"><<</a>';
                                        for($p=1; $p<=$total_pages; $p++) {
                                            echo ' <a class="page" href="?'.$arg.'='.$share.'&sharepath='.$sharepath.'&pages='.$p.'">'.$p.'</a> ';                      
                                        }
                                        echo '<a class="page" href="?'.$arg.'='.$share.'&sharepath='.$sharepath.'&pages='.(($page+1>$total_pages)?$total_pages:($page+1)).'">>></a>';
                                    } else {
                                        echo '<a class="page" href="?'.$arg.'='.$path_implode.'&pages='.(($page-1>1)?($page-1):1).'"><<</a>';
                                        for($p=1; $p<=$total_pages; $p++) {
                                            echo ' <a class="page" href="?'.$arg.'='.$path_implode.'&pages='.$p.'">'.$p.'</a> ';                      
                                        }
                                        echo '<a class="page" href="?'.$arg.'='.$path_implode.'&pages='.(($page+1>$total_pages)?$total_pages:($page+1)).'">>></a>';
                                    }
                                    echo "</div>";
                                }
            
                            } else {
                                // Include the configuration file
                                require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/config.php';
                                include_once(plugin_dir_path( dirname( __FILE__ ) ) . '/includes/id3/getid3.php');
                    
                                $object_id = $path_implode;
                                $getname = getName(6);
                                $target =  $object_id;
                                $direname = dirname($object_id);
                                $basename = basename($object_id);
                                $path_ = pathinfo($basename);
                                $ext = strtolower($path_['extension']);
                                $key = trim($_GET['oauth']);
                                $getID3 = new getID3;
                                $fileID3 = $getID3->analyze($object_id);
                    
                                // Retrieve the keys from the tokens file
                                $keys = file(TOKEN_DIR.'/oauth');
                                $match = false;
                    
                                // Loop through the keys to find a match
                                // When the match is found, remove it
                                foreach($keys as &$one){
                                    $keyone = explode('||',$one);
                                    $currentTime = time();
                                    $expTime = strtotime(EXPIRATION_TIME, $keyone[0]);            
                                    if($key==$keyone[1]){
                                        if($currentTime >= $expTime) {
                                            $one = '';
                                        } else {
                                            $one = $one;
                                            if($basename == basename($keyone[2])) {
                                                if($keyone[3] == $_SERVER["HTTP_CF_CONNECTING_IP"]) {
                                                    $match = true;   
                                                }
                                            }
                                        }
                                    }
                                }
                    
                                // Put the remaining keys back into the tokens file
                                file_put_contents(TOKEN_DIR.'/oauth',$keys);
                    
                                // Verify the oauth password
                                if($match != true){
                                    echo "false";
                                    // Return 404 error, if not a correct path
                                    header("HTTP/1.0 404 Not Found");
                                    exit;
                                }else{    
                                    // If the files exist
                                    if($key){
                                        // Generate download link
                                        $download_link = DOWNLOAD_PATH."?key=".$key; 
                                    }
                                }  
                                
                                ?><script type="text/javascript">document.getElementById("filemanagerbtnup").style.display = "none";</script><?php
                    
                                echo "<div class='navbarfilewrapper'><div class='navbar navbarfile'>";
                                    if ($path_parts[1] != '' && $workplace_strpos == true && $workplace_last != true){
                                        if (isset($home)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?home=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                        if (isset($workplace)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?workplaces=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                        if (isset($path)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?path=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                        if (isset($sharepath)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request)  . "/?share=" . $share . "&sharepath=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                    } 
                                echo "</div>";
            
                                if(!$share){
                                    echo "<div class='navbar navbarfile'>
                                        <div class='subnav'>
                                            <button class='subnavbtn btnshare'>Share file</button>
                                            <div id='subnav-content-share' class='subnav-content'>
                                                <span>
                                                    <input type='text' id='lnameshare' readonly></span>
                                                    <button class='newsharelink'>Create share link</button>
                                                <span>
                                            </div>
                                        </div>
                                    </div>";
                                }
            
                                $u = 0;
                                $b = 0;
            
                                if (isset($home)) { $arg = 'home'; }
            
                                if (isset($workplace)) { $arg = 'workplaces'; }
            
                                if (isset($path)) { $arg = 'path'; }
            
                                if (isset($share)) { $arg = 'share'; }
                                
                                if($home || $workplace || $path || ($share && $sharepath)) {
            
                                    if ($dh = opendir($direname)) {
                                        while (($file = readdir($dh)) !== false) {
                                            if(!is_dir($direname.'/'.$file)) {
                                                $allFiles[] = $file;
                                            }
                                        }
                                        closedir($dh);
                                    }
                                    $files = array_diff($allFiles, array('.', '..'));
            
                                    sort($files);
                                    
                                    foreach($files as $file) {
            
                                        if($file == basename($object_id)) {
                                            $u = $b;
                                        }
                                        $b++;
                                    }
            
                                    $before = $files[$u-1];
                                    $next = $files[$u+1];
                                    $current_url = explode("?", $_SERVER['REQUEST_URI']);
                                    
                                    $getnamebefore = getName(32);
                                    $getoauth = uniqid(time().'||'.$getnamebefore.'||'.$direname.'/'.$before.'||'.$_SERVER["HTTP_CF_CONNECTING_IP"].'||',TRUE);
                                
                                    // Include the configuration file
                                    require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/config.php';
            
                                    // Create a protected directory to store keys
                                    if(!is_dir(TOKEN_DIR)) {
                                        mkdir(TOKEN_DIR);
                                        $file = fopen(TOKEN_DIR.'/.htaccess','w');
                                        fwrite($file,"Order allow,deny\nDeny from all");
                                        fclose($file);
                                    }
                                    
                                    // Write the key to the keys list
                                    $file = fopen(TOKEN_DIR.'/oauth','a');
                                    fwrite($file, "{$getoauth}\n");
                                    fclose($file);
            
                                    $getnamenext = getName(32);
                                    $getoauth = uniqid(time().'||'.$getnamenext.'||'.$direname.'/'.$next.'||'.$_SERVER["HTTP_CF_CONNECTING_IP"].'||',TRUE);
                                
                                    // Include the configuration file
                                    require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/config.php';
            
                                    // Create a protected directory to store keys
                                    if(!is_dir(TOKEN_DIR)) {
                                        mkdir(TOKEN_DIR);
                                        $file = fopen(TOKEN_DIR.'/.htaccess','w');
                                        fwrite($file,"Order allow,deny\nDeny from all");
                                        fclose($file);
                                    }
                                    
                                    // Write the key to the keys list
                                    $file = fopen(TOKEN_DIR.'/oauth','a');
                                    fwrite($file, "{$getoauth}\n");
                                    fclose($file);
            
                                    if($sharepath) {
                                        echo '<div class="file-info"><div class="file-next-before">';
                                        if ($before) {
                                            echo '<a href="'. $current_url[0] .'?'. $arg .'='. $share . '&sharepath=' . $direname.'/'.$before .'&oauth='. $getnamebefore .'" class="">Previous</a>';
                                        }
                                        echo '<div class="file-info file-info-name">' . basename($object_id) . '</div>';
                                        if ($next) {
                                            echo '<a href="'. $current_url[0] .'?'. $arg .'='. $share . '&sharepath=' . $direname.'/'.$next .'&oauth='. $getnamenext .'" class="">Next</a>';
                                        }
                                        echo '</div></div>';
                                    } else {
                                        echo '<div class="file-info"><div class="file-next-before">';
                                        if ($before) {
                                            echo '<a href="'. $current_url[0] .'?'. $arg .'='. $direname.'/'.$before .'&oauth='. $getnamebefore .'" class="">Previous</a>';
                                        }
                                        echo '<div class="file-info file-info-name">' . basename($object_id) . '</div>';
                                        if ($next) {
                                            echo '<a href="'. $current_url[0] .'?'. $arg .'='. $direname.'/'.$next .'&oauth='. $getnamenext .'" class="">Next</a>';
                                        }
                                        echo '</div></div>';
                                    }
                                
                                } else {
                                    echo '<div class="file-next-before"><div class="file-info file-info-name">' . basename($object_id) . '</div></div>';
                                }
                            
                                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'bmp' || $ext == 'png' || $ext == 'gif') {
                                    echo '<img src="' . $download_link . '"></img>';
                                } elseif ($ext == 'mp4' || $ext == 'mkv' || $ext == 'avi' ) {
                                    ?><video id='filemanagervideo' controls="controls" preload="auto" controlsList="nodownload" name="media">
                                        <source src="<?php echo $download_link ?>" type="video/mp4">
                                    </video>
                                    <div class="" style="font-size: 1.25em;font-weight: 600;"><?php echo basename($object_id); ?></div>
                                    <div class=""><?php echo $direname; ?></div>
                                    <div class=""><?php echo formatSizeUnits(filesize($object_id)); ?></div>
                                    <br>
                                    <?php echo("Video"); ?> 
                                    <br>
                                    <?php echo("Duration: ".$fileID3['playtime_string']); ?>
                                    <br>
                                    <?php echo("Codec: ".$fileID3['video']['dataformat']); ?>                       
                                    <br>
                                    <?php echo("Dimensions: ".$fileID3['video']['resolution_x']."x".$fileID3['video']['resolution_y']); ?>
                                    <br>
                                    <?php echo("Frame rate: ".$fileID3['video']['frame_rate']); ?>
                                    <br><br>
                                    <?php echo("Audio"); ?>
                                    <br>
                                    <?php echo("Codec: ".$fileID3['audio']['dataformat']); ?>   
                                    <br>
                                    <?php echo("Bits per sample: ".$fileID3['audio']['bits_per_sample']); ?>    
                                    <br>
                                    <?php echo("Channelmode: ".$fileID3['audio']['channelmode']); ?>
                                    <br>
                                    <?php echo("Sample rate: ".$fileID3['audio']['sample_rate']); ?>
                                    <br>
                                    <?php 
                                } elseif ($ext == 'pdf') {
                                    echo '<div id="pdf"></div>';
                                    ?><script type="text/javascript">PDFObject.embed('<?php echo $download_link ?>', "#pdf");</script><?php
                                } elseif ($ext == 'txt' || $ext == 'html' || $ext == 'php' || $ext == 'js' || $ext == 'log') { 
                                    if ($write_path == true) {
                                        echo '<div class="navbar navbarfile"><div id="savefile" onclick="savefile();">Save</div></div></div>';
                                    } else {
                                        echo '</div>'; 
                                    }
                                    echo '<div id="editor"> </div>';
                                ?><script>
                                var myCodeMirror = CodeMirror(
                                document.getElementById('editor'), {
                                    lineNumbers: true,
                                    mode: "text/html"
                                });
                                fetch('<?php echo $download_link ?>')
                                    .then(response => response.text())
                                    .then(data => {
                                        // Do something with your data
                                        console.log(data);
                                        myCodeMirror.setValue(data);
                                    });
                                function savefile() {
                                    var getValue = myCodeMirror.getValue();
                                    jQuery.ajax({
                                        type: 'post',
                                        url: save_filemanager_ajax_url,
                                        data: {
                                            'object_id': getValue,
                                            'link': '<?php echo $target ?>',
                                            'action': 'save_filemanager_files'
                                        },
                                        dataType: 'json',
                                        success: function(data){
                                            console.log(data);
                                        },
                                        error: function(errorThrown){
                                            //error stuff here.text
                                        }
                                    });
                                    console.log(textFileAsBlob);
                                }
                                </script> <?php
                                } else { ?>
                                    <div class='navbar navbarfile'>
                                        <a href="<?php echo $download_link ?>" class="no-smoothState">Download</a>
                                    </div></div><?php   
                                    echo '<div class="">' . basename($object_id) . '</div>';
                                    echo '<div class="">' . formatSizeUnits(filesize($object_id)) . '</div>';
            
                                }
                                echo "</div>";
                            }
                    
                        } else {
                            if (!post_password_required(  $post->ID ) ) {
                                echo "You don't have right: permission denied";
                            }
                        } ?>
                    </div>
                </div>
            </article>
        </div>
    </div>

	<?php endwhile; // end of the loop. ?>

<?php get_footer();