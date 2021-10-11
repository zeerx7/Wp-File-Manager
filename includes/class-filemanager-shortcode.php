<?php

class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
    // add top level menu page
	add_menu_page(
            'File Manager', 
            'File Manager', 
            'manage_options', 
            'my-setting-admin', 
            array( $this, 'create_admin_page' )
        );
         // seconde option du sous-menu
         add_submenu_page( 
            "my-setting-admin",   // slug du menu parent
            __( "Mon thème - Mon sous-menu - Configuration", "montheme" ),  // texte de la balise <title>
            __( "Workplace", "montheme" ),   // titre de l'option de sous-menu
            "manage_options",  // droits requis pour voir l'option de menu
            'edit.php?post_type=workplace',
            false // fonction de rappel pour créer la page
         );
        // seconde option du sous-menu
        add_submenu_page( 
            "my-setting-admin",   // slug du menu parent
            __( "Mon thème - Mon sous-menu - Configuration", "montheme" ),  // texte de la balise <title>
            __( "Shares Links", "montheme" ),   // titre de l'option de sous-menu
            "manage_options",  // droits requis pour voir l'option de menu
            'edit.php?post_type=shares',
            false // fonction de rappel pour créer la page
        );
        add_submenu_page( 
            "my-setting-admin",   // slug du menu parent
            __( "Mon thème - Mon sous-menu - Configuration", "montheme" ),  // texte de la balise <title>
            __( "Disk", "montheme" ),   // titre de l'option de sous-menu
            "manage_options",  // droits requis pour voir l'option de menu
            'edit.php?post_type=disk',
            false // fonction de rappel pour créer la page
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
    //Get the active tab from the $_GET param
    $default_tab = null;
    $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
    $my_option_name = get_option('my_option_name');
    $blogusers = get_users();
    $i = 0;
    $m = 0;
  
    ?>
    <!-- Our admin page content should all be inside .wrap -->
    <div class="wrap">
      <!-- Print the page title -->
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <!-- Here are our tabs -->
      <nav class="nav-tab-wrapper">
        <a href="?page=my-setting-admin" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Default Tab</a>
        <a href="?page=my-setting-admin&tab=settings" class="nav-tab <?php if($tab==='settings'):?>nav-tab-active<?php endif; ?>">Settings</a>
        <a href="?page=my-setting-admin&tab=tools" class="nav-tab <?php if($tab==='tools'):?>nav-tab-active<?php endif; ?>">Tools</a>
      </nav>
  
        <div class="tab-content">
            <?php switch($tab) :
            case 'settings':
                echo 'Settings'; //Put your HTML here
                break;
            case 'tools':
                echo 'Tools';
                break;
            default:
                // Set class property
                $this->options = get_option( 'my_option_name' ); ?>
                <div class="wrap">
                    <h1>My Settings</h1>
                    <form method="post" action="options.php">  
                        <?php settings_fields( 'my_option_group' ); ?>
                        <div id="my-setting-workplace">
                            <br>
                            <select name='my_option_name[selected_page]'>
                                <option value='0'><?php _e('Select a Page', 'textdomain'); ?></option>
                                <?php $pages = get_pages(); ?>
                                <?php foreach( $pages as $page ) { ?>
                                    <option value='<?php echo $page->ID; ?>' <?php selected( $my_option_name['selected_page'], $page->ID ); ?> ><?php echo $page->post_title; ?></option>
                                <?php }; ?>
                            </select> 
                            <br><br>
                            <?php printf(
                                '<input type="text" id="title" name="my_option_name[title]" value="%s" />',
                                isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
                            ); ?>
                            <br>
                            <?php
                            echo '</br>Acces to path</br>';
                            foreach ($blogusers as $bloguser) {
                                $read_ = false;
                                $write_ = false;
                                echo $bloguser->user_login . ' - ';
                                $my_option_read_path = $my_option_name['read-path'];
                                foreach ( $my_option_read_path as $read) {
                                    if ($read == $bloguser->ID) {
                                        $read_ = true;
                                    }
                                }
                                if ($read_ == true) {
                                    echo 'Read<input type="checkbox" id="read-path" name="my_option_name[read-path][' . $m . ']" value="' . $bloguser->ID .'" checked>';
                                } else {
                                    echo 'Read<input type="checkbox" id="read-path" name="my_option_name[read-path][' . $m . ']" value="' . $bloguser->ID .'">';
                                }

                                $my_option_write_path = $my_option_name['write-path'];
                                foreach ( $my_option_write_path as $write) {
                                    if ($write == $bloguser->ID) {
                                        $write_ = true;
                                    }
                                }
                                if ($write_ == true) {
                                    echo 'Write<input type="checkbox" id="write-path" name="my_option_name[write-path][' . $m . ']" value="' . $bloguser->ID .'" checked>';
                                } else {
                                    echo 'Write<input type="checkbox" id="write-path" name="my_option_name[write-path][' . $m . ']" value="' . $bloguser->ID .'">';
                                }
                                $m++;
                            } ?>
                        <?php
                        submit_button();
                    ?>
                    </form>
                </div> <?php
                break;
            endswitch; ?>
        </div>
    </div>
    <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        $i = 0;           
        foreach ($input['id_name'] as $id_number) {
            if( isset( $id_number ) ) {
                $new_input['id_name'][$i] = sanitize_text_field( $id_number );
                $new_input['id_path'][$i] = sanitize_text_field( $input['id_path'][$i] );
                $i++;
            }
            $x = 0; 
            foreach ($input['read-'.$id_number] as $id_read) {
                if( isset( $id_read ) ) {
                    $new_input['read-'.$id_number][$x] .= $id_read.',';
                    $x++;
                }
            }
            $y = 0;
            foreach ($input['write-'.$id_number] as $id_write) {
                if( isset( $id_write ) ) {
                    $new_input['write-'.$id_number][$y] .= $id_write.',';
                    $y++;
                }
            }
        }

        $m = 0;
        foreach ($input['read-path'] as $id_number) {
            if( isset( $id_number ) ) {
                $new_input['read-path'][$m] .= sanitize_text_field( $id_number );
                $m++;
            }
        }

        $n = 0;
        foreach ($input['write-path'] as $id_number) {
            if( isset( $id_number ) ) {
                $new_input['write-path'][$n] .= sanitize_text_field( $id_number );
                $n++;
            }
        }

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        if( isset( $input['selected_page'] ) )
            $new_input['selected_page'] = $input['selected_page'];
            

        return $new_input;
    }
}

if( is_admin() )
    $my_settings_page = new MySettingsPage();

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

    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();   
    } else {
        $user->ID = '-1';
    }

    $path = $_GET['path'];
    $home = $_GET['home'];
    $workplace = $_GET['workplaces'];
    $share = $_GET['share'];
    $sharepath = $_GET['sharepath'];
    $my_option_name = get_option('my_option_name');
    global $wp;
    $workplace_strpos = false;
    $workplace_last = false;
    $read_path = false;
    $write_path = false;
    $i = 0;
    $x = 0;
    $u = 0;
    $v = 0;

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
            $workplacepath = get_post_meta( $post->ID, "_workplace_path", true);
            $workplaceright = get_post_meta( $post->ID, "_workplace_right", true);
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
    
    if (isset($share) && !isset($sharepath)) {
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
                $path_implode = get_post_meta( $post->ID, '_share_path', true);
            }                   
        }

        if ($path_implode) {
            $getname = getName(32);
            $getoauth = uniqid(time().'||'.$getname.'||'.$path_implode.'||'.$_SERVER["HTTP_CF_CONNECTING_IP"].'||',TRUE);
        
            // Include the configuration file
            require_once dirname(__FILE__) . '/config.php';

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
            $read_path = true;
            $workplace_strpos = true;
            if(!$_GET['oauth']){
                echo "<script>location.href='?share=".$_GET['share']."&oauth=".$getname."';</script>";
            }
        }
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
                $password = $post->post_password;
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

    echo "<div id='sequentialupload' class='sequentialupload' data-object-id='$path_implode'></div>"; 
    ?><script type="text/javascript">document.getElementById("sequentialupload").style.display = "none";</script>
    <?php

    ?><div id='errorlog'></div><?php
    ?><div id='filemanager-wrapper' class='filemanager-wrapper'><?php
    
    if ($path == null && $home == null && $workplace == null && $share == null && $sharepath == null) {
    
        echo "<div id='filemanager-home' class='filemanager-home'>";
        if ($user->ID != '-1') {
            ?><a id='file-id' class='filemanager-home-click' href='<?php echo home_url($wp->request) . '/?home=/home/' . esc_html($user->user_login) ?>'>Home</a></br><?php
        }
    
        $posts = get_posts( array(
            'post_type'      => 'workplace',
            'posts_per_page' => -1,
            'orderby'        => 'modified',
            'no_found_rows'  => true
        ));
    
        foreach($posts as $post) {
            $workplacepath[$post->ID] = get_post_meta( $post->ID, "_workplace_path", true);
            $workplaceright[$post->ID] = get_post_meta( $post->ID, "_workplace_right", true);
        }
        foreach ($workplacepath as $postid=>$id_path){
            foreach($workplaceright[$postid] as $userid=>$right) {
                if ($user->ID == $userid || $userid == '-1') {
                    ?><a id='file-id' class='filemanager-home-click' href='<?php echo home_url($wp->request) . '/?workplaces=' . esc_html( $id_path ) ?>'><?php echo get_the_title( $postid ) ?></a></br><?php
                }
            }
        }
    
        if (in_array($user->ID, $id_read_path)) {
            ?><a id='file-id' class='filemanager-home-click' href='<?php echo home_url($wp->request) . '/?path=' . esc_html( ABSPATH ) ?>'>Path</a><?php
        }

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'disk',
        );

        $my_query = get_posts( $args );

        if ($my_query) {
            echo "<div class='diskinfowrapper'>";
            foreach ( $my_query as $post ) { 
                $path_share = get_post_meta( $post->ID, '_disk_path', true);
                echo "<div class='diskinfo'>";
                // set partition
                $fs = $path_share;
                // display available and used space
                echo get_the_title($post->ID). " \r\n<br />";
                echo "Total available space: " . 
                round(disk_total_space($fs) / (1024*1024)) . " MB\r\n<br />"; 
                echo "Total free space: " . round(disk_free_space($fs) / (1024*1024)) . " MB\r\n<br />";
                // calculate used space
                $disk_used_space =
                round((disk_total_space($fs) - disk_free_space($fs)) / (1024*1024)); 
                echo "Total used space: " . $disk_used_space . " MB\r\n<br />";
                // calculate % used space
                echo "% used space: " . round((disk_total_space($fs) -
                disk_free_space($fs)) / disk_total_space($fs) * 100) . " %";
                echo "</div>";
            }      
            echo "</div>";             
        }

    } else {

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
                if ($write_path == true) {
                    ?>
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
                                    require_once dirname(__FILE__) . '/config.php';

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
                    require_once dirname(__FILE__) . '/config.php';
                    include_once(dirname(__FILE__) . '/id3/getid3.php');
        
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
                                <div id='subnav-content-share' class='subnav-content subnav-content-share'>
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
                        require_once dirname(__FILE__) . '/config.php';

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
                        require_once dirname(__FILE__) . '/config.php';

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
                if ( post_password_required(  $post->ID ) ) {
                    echo get_the_password_form();
                } else {
                    echo "You don't have right: permission denied";
                }
            }   

        }

    ?></div><?php

}

    
add_shortcode('filemanager-shortcode', 'filemanager_shortcode');

?>