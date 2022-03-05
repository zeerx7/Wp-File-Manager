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

$n=10;
function getNumber($n) {
    $characters = '123456789';
    $randomString = '';
  
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
  
    return $randomString;
}

add_action( 'init', "sc_set_timer_cookie", 1);
function sc_set_timer_cookie() {
    if(!isset($_COOKIE["uniqid"])) {
        setcookie( "uniqid", getNumber(6), time()+3600 );
    }
	return ;
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

    $arg = null;
    $home = $_GET['home'];
    $workplace = $_GET['workplace'];
    $path = $_GET['path'];
    $share = $_GET['share'];
    $sharepath = $_GET['sharepath'];
    if (isset($home)) { $arg = 'home'; }
    if (isset($workplace)) { $arg = 'workplace'; }
    if (isset($path)) { $arg = 'path'; }
    if (isset($share)) { $arg = 'share'; }
    $get_arg = trim($_GET[$arg]);
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

    foreach( $my_option_name['read-path'] as $read_path_id ){
            $id_read_path[] .= $read_path_id;
    }
            
    foreach( $my_option_name['write-path'] as $write_path_id ){
            $id_write_path[] .= $write_path_id;
    }

    $meta_name = 'oauth-'.$_COOKIE['uniqid'];
    $get_oauth = get_post_meta($_COOKIE['uniqid'], $meta_name, true);
    foreach($get_oauth as $oauth){
        $keyone[] = explode('||',$oauth);    
        $currentTime = time();  
        $expTime = strtotime('+12 hours', $oauth[0]);       
        if($currentTime >= $expTime) {
            $_data[] .= $oauth;
        }
    }

    if ($arg == 'home') {

        $path_default = $my_option_name['title'].$user->user_login;

        $tree_path = $path_default;
        
        $keys = $_data;

        // Loop through the keys to find a match
        // When the match is found, remove it
        foreach($keys as &$one){
            $keyone = explode('||',$one);         
            if($get_arg==$keyone[1]){
                $path_implode = $keyone[2];
            }
        }

        if ($path_implode == $path_default) {
            $workplace_last = true;
        }
        if (strpos($path_implode, $path_default) !== false) {
            $workplace_strpos = true;
        }
        $read_path = true;
        $write_path = true;

        $treepath_ = rtrim($treepath, "/");
        if (strpos($treepath_, $path_default) !== false) {
            $treepath_strpos = true;
            $read_tree = true;
        } 
    } 
    
    if ($arg == 'workplace') {

        $keys = $_data;

        // Loop through the keys to find a match
        // When the match is found, remove it
        foreach($keys as &$one){
            $keyone = explode('||',$one);     
            if($get_arg==$keyone[1]){
                $path_implode = $keyone[2];
            }            
        }
        
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
                $path_default = $workplacepath;
                $workplace_strpos = true;
                $treepath = $id_path_;
                if ($workplace_ == $id_path_) {
                    $workplace_last = true;
                }
                if($workplaceright[-1]['read'] == 1) {
                    $read_path = true;
                }
                if($workplaceright[-1]['write'] == 1) {
                    $write_path = true;
                }
                if($workplaceright[$user->ID]['read'] == 1) {
                    $read_path = true;
                }
                if($workplaceright[$user->ID]['write'] == 1) {
                    $write_path = true;
                }
            }
            $treepath_ = rtrim($treepath, "/");
            if (strpos($treepath_, $id_path_) !== false) {
                $treepath_strpos = true;
                if($workplaceright[$user->ID]['read'] == 1) {
                    $read_tree = true;
                }
            }  
        }

    }
    
    if ($arg == 'path') {

        $keys = $_data;

        // Loop through the keys to find a match
        // When the match is found, remove it
        foreach($keys as &$one){
            $keyone = explode('||',$one);   
            if($get_arg==$keyone[1]){
                $path_implode = $keyone[2];
            }
        }

        if (in_array($user->ID, $id_read_path)) {
            $path_default = '/';
            $read_path = true;
            $workplace_strpos = true;
            $treepath_strpos = true;
            $read_tree = true;
            $treepath = '/';
        }
        if (in_array($user->ID, $id_write_path)) {
            $write_path = true;
        }
    } 
    
    if ($arg == 'share') {
        
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'shares',
            'meta_query' => array(
                array(
                    'key' => '_share_key',
                    'value' => $get_arg,
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
        }

        if($share_right[-1]['read'] == 1) {
            $read_path = true;
        }
        if($share_right[-1]['write'] == 1) {
            $write_path = true;
        }
        if($share_right[$user->ID]['read'] == 1) {
            $read_path = true;
        }
        if($share_right[$user->ID]['write'] == 1) {
            $write_path = true;
        }

        if($read_path) {
            // Retrieve the keys from the tokens file
            $keys = $_data;

            $id_path_ = rtrim($share_path, "/");

            // Loop through the keys to find a match
            // When the match is found, remove it
            foreach($keys as &$one){
                $keyone = explode('||',$one);         
                if($get_arg==$keyone[1] || $sharepath==$keyone[1]){
                    $path_implode = $keyone[2];
                }
            }

            if(!isset($path_implode)){
                if(is_dir($share_path)) {
                    $getname = getName(32);
                    $getoauth = time().'||'.$getname.'||'.$share_path.'||'.$id_path_;
                    $_data[] .= $getoauth;
                    update_post_meta($_COOKIE['uniqid'], $meta_name, $_data);
                    return "<script>location.href='?share=".$_GET['share']."&sharepath=".$getname."'</script>";
                } else {
                    $getoauth = time().'||'.$share.'||'.$share_path.'||'.$id_path_;
                    $_data[] .= $getoauth;
                    update_post_meta($_COOKIE['uniqid'], $meta_name, $_data);
                    return "<script>location.href='?share=".$_GET['share']."'</script>";
                }
            }

            if ($arg == 'share' && !isset($sharepath) && is_dir($path_implode)) {
                $getname = getName(32);
                $getoauth = uniqid(time().'||'.$getname.'||'.$path_implode.'||'.$_SERVER["HTTP_CF_CONNECTING_IP"].'||',TRUE);
                $_data[] .= $getoauth;
                update_post_meta($_COOKIE['uniqid'], $meta_name, $_data);
                return "<script>location.href='?share=".$_GET['share']."&sharepath=".$getname."'</script>";
            }
        }

        if ($path_implode) {
            $sharepath_ = rtrim($path_implode, "/");
            if($id_path_ == ''){
                $id_path_ = '/';
            }
            if($sharepath_ == ''){
                $sharepath_ = '/';
            }
            if (strpos($sharepath_, $id_path_) !== false) {
                $path_default = $id_path_;
                $workplace_strpos = true;
            }
            if ($sharepath_ == $id_path_) {
                $workplace_last = true;
            }
            $treepath = $id_path_;
            $treepath_ = rtrim($treepath, "/");
            if($treepath_ == ''){
                $treepath_ = '/';
            }
            if (strpos($treepath_, $id_path_) !== false) {
                $treepath_strpos = true;
                if($share_right[-1]['read'] == 1) {
                    $read_tree = true;
                }
                if($share_right[$user->ID]['read'] == 1) {
                    $read_tree = true;
                }
            } 
        } 
    }

    ?><div id='errorlog'></div><?php
    echo "<div id='sequentialupload' class='sequentialupload' data-object-id='$path_implode'></div>"; 
    ?><script type="text/javascript">document.getElementById("sequentialupload").style.display = "none";</script><?php

    ?><div id='filemanager-wrapper' class='filemanager-wrapper'><?php
    
    if ($arg == null) {
   
        echo "<div id='filemanager-home' class='filemanager-home'>";
        if ($user->ID != '-1') {
            $homepath = '/home/' . esc_html($user->user_login);
            $getname = getName(32);
            $getoauth = time().'||'.$getname.'||'.$homepath.'||'.$homepath;
            $_data[] .= $getoauth;
            ?><a id='file-id' class='filemanager-home-click' href='<?php echo home_url($wp->request) . '/?home='. $getname ?>'>Home</a></br><?php
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
            $l = 0;
            $getname = getName(32);
            $getoauth = time().'||'.$getname.'||'.$id_path.'||'.$id_path;
            $_data[] .= $getoauth;
            foreach($workplaceright[$postid] as $userid=>$right) {
                if($l <= 0){    
                    if ($userid == '-1' || $user->ID == $userid ) {
                        ?><a id='file-id' class='filemanager-home-click' href='<?php echo home_url($wp->request) . '/?workplace=' . esc_html( $getname ) ?>'><?php echo get_the_title( $postid ) ?></a></br><?php
                        $l++;
                    }
                }
            }
        }
    
        if (in_array($user->ID, $id_read_path)) {
            $getname = getName(32);
            $getoauth = time().'||'.$getname.'||'.ABSPATH.'||/';
            $_data[] .= $getoauth;
            ?><a id='file-id' class='filemanager-home-click' href='<?php echo home_url($wp->request) . '/?path=' . esc_html( $getname ) ?>'>Path</a><?php
        }

        update_post_meta($_COOKIE['uniqid'], $meta_name, $_data);

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

        update_post_meta($_COOKIE['uniqid'], $meta_name, $_data);

    } else {

        if (!post_password_required($post->ID) && $workplace_strpos == true && $read_path == true){

            if ( is_dir($path_implode) == true ) {

                if(!get_current_user_id()) {
                    if(isset($_COOKIE['userid'])) {
                        $userid = intval($_COOKIE['userid']);
                    }
                } else {
                    $userid = get_current_user_id();
                }                            
                $viewtype = implode(get_user_meta($userid, 'view_type_'));

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
                <div id='filemanagerwrapper'>
                    <div id='filemanagerpath' style='display: none;'><?php echo $path_implode; ?></div>
                    <div id='filemanagerbtndown' class='filemanagerbtn'>
                        <div class='navbar'>
                            <?php if ($path_parts[1] != '' && $workplace_strpos == true && $workplace_last != true){

                                    $getname = getName(32);
                                    $getoauth = time().'||'.$getname.'||'.dirname($path_implode).'||'.$path_default;
                                    if($sharepath) {
                                        ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?".$arg."=" . $share . "&sharepath=" . $getname ?>'>Parent directory</a> <?php
                                    } else {
                                        ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?".$arg."=" . $getname ?>'>Parent directory</a> <?php
                                    }
                                    $_data[] .= $getoauth;
                                    
                                } 
                                if ($path_parts[1] == '' || $workplace_last == true || $workplace_strpos != true) { ?>
                                <a class='btnback_home' href='<?php echo home_url($wp->request) ?>/'>Home</a>
                            <?php } ?>
                            <label class="switch">
                                <?php if(!$viewtype || $viewtype == 'list') {?>
                                    <input type="checkbox" id="viewtype" name="viewtype" value="list">
                                <?php } elseif($viewtype == 'grid'){ ?>
                                    <input type="checkbox" id="viewtype" name="viewtype" value="grid" checked="checked">
                                <?php } ?>
                                <span class="sliderbox">
                                    <span id="viewtypetext" class="sliderboxtext">
                                        <?php if(!$viewtype || $viewtype == 'list') { echo 'List'; }?>
                                        <?php if($viewtype == 'grid') { echo 'Grid'; } ?>
                                    </span>
                                </span>
                            </label>
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

                        $getname = getName(32);
                        $getoauth = time().'||'.$getname.'||'.$path_part_.'||'.$path_default;

                       ?><a href='<?php  echo home_url($wp->request) . "/?".$arg."=" . $getname ?>'><?php echo $path_part; ?></a><?php
                        if($p != $path_parts_count){
                            echo '/';
                        } 
                        $p++;
                        $_data[] .= $getoauth;

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
                    ?><div class='file-table'>
                        <?php if(!$viewtype || $viewtype == 'list') {
                            ?><table id='file-table'>
                            <tr></td><td class='checkboxall'><input class='checkboxall' type='checkbox' name=''/></td><td class='checkboxall'>Filename</td><td class='checkboxall'>Size</td></tr><?php
                                foreach($files as $file){
                                    $realpath = realpath($path_implode.'/'.$file);
                                    $filesize = formatSizeUnits(filesize($realpath));

                                    $getname = getName(32);
                                    $getoauth = time().'||'.$getname.'||'.$realpath.'||'.$path_default;
                                    if($sharepath){
                                        echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?".$arg."=".$get_arg."&sharepath=".$getname."'>$file</a></td><td>$filesize</td></tr>"; 
                                    } else {
                                        echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?".$arg."=$getname'>$file</a></td><td>$filesize</td></tr>";    
                                    }           
                                    $_data[] .= $getoauth;
                                }
                                if ($files == null) {
                                    echo "<tr><td><input class='checkbox' type='checkbox' /></td><td class='filemanager-table'><a id='file-id' class='filemanager-click'>No file found</a></td><td></td></tr>";
                                }
                            echo "</table>";
                        } elseif ($viewtype == 'grid') {
                            ?><div class='grid-container'><?php
                                foreach($files as $file){
                                    $realpath = realpath($path_implode.'/'.$file);
                                    $filesize = formatSizeUnits(filesize($realpath));

                                    $getname = getName(32);
                                    $getoauth = time().'||'.$getname.'||'.$realpath.'||'.$path_default;
                                    if($sharepath){
                                        echo "<div class='grid-item'><input class='checkbox' type='checkbox' name='$realpath'/></input><div class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?".$arg."=".$get_arg."&sharepath=".$getname."'>$file</a></div><div>$filesize</div></div>"; 
                                    } else {
                                        echo "<div class='grid-item'><input class='checkbox' type='checkbox' name='$realpath'/></input><div class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?".$arg."=$getname'>$file</a></div><div>$filesize</div></div>";    
                                    }           
                                    $_data[] .= $getoauth;
                                }
                                if ($files == null) {
                                    echo "<div class='grid-item'><input class='checkbox' type='checkbox' /></td><td class='filemanager-table'><a id='file-id' class='filemanager-click'>No file found</a></td><td></td></tr>";
                                }
                            echo "</div>";
                        }
                        if($total_pages > 1) {
                            echo '<div class="filemanagerpagination">';
                            if (isset($sharepath)) {
                                echo '<a class="page" href="?'.$arg.'='.$share.'&sharepath='.$get_arg.'&pages='.(($page-1>1)?($page-1):1).'"><<</a>';
                                for($p=1; $p<=$total_pages; $p++) {
                                    echo ' <a class="page" href="?'.$arg.'='.$share.'&sharepath='.$get_arg.'&pages='.$p.'">'.$p.'</a> ';                      
                                }
                                echo '<a class="page" href="?'.$arg.'='.$share.'&sharepath='.$get_arg.'&pages='.(($page+1>$total_pages)?$total_pages:($page+1)).'">>></a>';
                            } else {
                                echo '<a class="page" href="?'.$arg.'='.$get_arg.'&pages='.(($page-1>1)?($page-1):1).'"><<</a>';
                                for($p=1; $p<=$total_pages; $p++) {
                                    echo ' <a class="page" href="?'.$arg.'='.$get_arg.'&pages='.$p.'">'.$p.'</a> ';                      
                                }
                                echo '<a class="page" href="?'.$arg.'='.$get_arg.'&pages='.(($page+1>$total_pages)?$total_pages:($page+1)).'">>></a>';
                            }
                            echo "</div>";
                        }
                    echo "</div></div></div>";
                    ?><div id='treeview' class='treeview'><?php
                        if ($treepath_strpos == true && $read_tree == true) {
                            $ffs = scandir($treepath);

                            unset($ffs[array_search('.', $ffs, true)]);
                            unset($ffs[array_search('..', $ffs, true)]);
                        
                            // prevent empty ordered elements
                            if (count($ffs) < 1)
                                return;
                        
                            echo '<ul>';
                            foreach($ffs as $ff){
                                $getname = getName(32);                            
                                $realpath = realpath($treepath.'/'.$ff);
                                $getoauth = time().'||'.$getname.'||'.dirname($path_implode).'||'.$path_default;
                                if(is_dir($treepath.'/'.$ff)) {
                                    echo '<li class="isfolder" path="'.$treepath.'/'.$ff.'">'.$ff.'</li>';
                                    echo '<ul></ul>';
                                } else {
                                    echo "<li><a href='" . home_url($wp->request) . "/?".$arg."=$getname'>$ff</a></li>";
                                }
                                $_data[] .= $getoauth;
                            }
                            echo '</ul>';
                        }
                        ?></div><?php

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
                    $getID3 = new getID3;
                    $fileID3 = $getID3->analyze($object_id);
        
                    // Retrieve the keys from the tokens file
                    $keys = $_data;
                    $match = false;
        
                    // Loop through the keys to find a match
                    // When the match is found, remove it
                    foreach($keys as &$one){
                        $keyone = explode('||',$one);
                        if($get_arg==$keyone[1]){
                            $download_link = DOWNLOAD_PATH."?key=".$get_arg."&uniqid=".$_COOKIE['uniqid']."&arg=".$arg; 
                        } else if ($sharepath==$keyone[1]) {
                            $download_link = DOWNLOAD_PATH."?key=".$sharepath."&uniqid=".$_COOKIE['uniqid']."&arg=".$arg; 
                        }
                    }
                    
                    ?><script type="text/javascript">document.getElementById("filemanagerbtnup").style.display = "none";</script><?php
                    ?><div id='filemanagerpath' style='display: none;'><?php echo $path_implode; ?></div><?php

                    echo "<div class='navbarfilewrapper'><div class='navbar navbarfile'>";
                        if ($workplace_strpos == true && $workplace_last != true){
                            $getname = getName(32);
                            $getoauth = time().'||'.$getname.'||'.dirname($path_implode).'||'.$path_default;
                            if($sharepath) {
                                ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?".$arg."=" . $share . "&sharepath=" . $getname ?>'>Parent directory</a> <?php
                            } else {
                                ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?".$arg."=" . $getname ?>'>Parent directory</a> <?php
                            }
                            $_data[] .= $getoauth;
                        } 
                    echo "</div>";

                    if (isset($sharepath) || isset($workplace) || isset($path) || isset($home)) {
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
                        $getoauth = time().'||'.$getnamebefore.'||'.$direname.'/'.$before.'||'.$path_default;
                        $_data[] .= $getoauth;

                        $getnamenext = getName(32);
                        $getoauth = time().'||'.$getnamenext.'||'.$direname.'/'.$next.'||'.$path_default;
                        $_data[] .= $getoauth;

                        if($sharepath) {
                            echo '<div class="file-info"><div class="file-next-before">';
                            if ($before) {
                                echo '<a href="'. $current_url[0] .'?'. $arg .'='. $share . '&sharepath=' . $getnamebefore . '" class="filenamebefore">Previous</a>';
                            }
                            echo '<div class="file-info file-info-name">' . basename($object_id) . '</div>';
                            if ($next) {
                                echo '<a href="'. $current_url[0] .'?'. $arg .'='. $share . '&sharepath=' . $getnamenext . '" class="filenamenext">Next</a>';
                            }
                            echo '</div></div>';
                        } else {
                            echo '<div class="file-info"><div class="file-next-before">';
                            if ($before) {
                                echo '<a href="'. $current_url[0] .'?'. $arg .'='. $getnamebefore . '" class="filenamebefore">Previous</a>';
                            }
                            echo '<div class="file-info file-info-name">' . basename($object_id) . '</div>';
                            if ($next) {
                                echo '<a href="'. $current_url[0] .'?'. $arg .'='. $getnamenext . '" class="filenamenext">Next</a>';
                            }
                            echo '</div></div>';
                        }
                    
                    }
                    if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'bmp' || $ext == 'png' || $ext == 'gif') {
                        echo '</div>';
                        ?><div class="" style="font-size: 1.25em;font-weight: 600;"><?php echo basename($object_id); ?></div>
                        <div class=""><?php echo $direname; ?></div>
                        <div class=""><?php echo formatSizeUnits(filesize($object_id)); ?></div><?php
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
                        ?><div class='navbar navbarfile'>
                        <a href="<?php echo $download_link ?>" class="no-smoothState">Download</a>
                        </div><?php
                        echo '</div><div id="pdf"></div>';
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
                                    if(data != false){
                                        jQuery("#errorlog").append('File saved ');
                                    }
                                },
                                error: function(errorThrown){
                                    //error stuff here.text
                                }
                            });
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

            update_post_meta($_COOKIE['uniqid'], $meta_name, $_data);

        }

        if(!get_current_user_id()) {
            if(isset($_COOKIE['userid'])) {
                $userid = intval($_COOKIE['userid']);
            }
        } else {
            $userid = get_current_user_id();
        }         

        ?><div id='userid'><?php echo $userid ?></div><?php

    ?></div><?php

}

    
add_shortcode('filemanager-shortcode', 'filemanager_shortcode');

?>