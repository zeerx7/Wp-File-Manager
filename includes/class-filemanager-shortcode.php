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
        add_action( 'wp_ajax_my_action', array( $this, 'my_action') );
        add_action( 'wp_ajax_id_remove', array( $this, 'id_remove') );
        add_action( 'admin_footer', array( $this, 'my_action_javascript') ); // Write our JS below here
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
    // add top level menu page
	add_menu_page(
            'Settings Admin', 
            'My Settings', 
            'manage_options', 
            'my-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    function my_action() {
        global $wpdb; // this is how you get access to the database

        $my_option_name = get_option('my_option_name');
    
        $count = intval( $_POST['count'] );

        $html = '<div>';
    
        $html .= '<input type="text" id="id_name" class="id_name" name="my_option_name[id_name][' . $count . ']" value="' . $my_option_name['id_name'][$count] . '" />';

        $html .= '<input type="text" id="id_path" class="id_path" name="my_option_name[id_path][' . $count . ']" value="' . $my_option_name['id_path'][$count] . '" />';
    
        $html .= '</div>';
    
        return wp_send_json ( $html );
    }

    function id_remove() {
        global $wpdb; // this is how you get access to the database

        $count = intval( $_POST['dataid'] );
        $i = 0;
        
        $my_option_name = get_option('my_option_name');
        
        unset($my_option_name['id_name'][$count]);
        unset($my_option_name['id_path'][$count]);

        Sort($my_option_name['id_name']);
        Sort($my_option_name['id_path']);

        update_option('my_option_name',  $my_option_name);

        foreach ( $my_option_name['id_name'] as $id_name ) {
            $html .= '<table>';
                $html .= '<td><input type="text" id="id_name" class="id_name" name="my_option_name[id_name][' . $i . ']" value="' . $id_name . '" /></td>';
                $html .= '<td><input type="text" id="id_path" class="id_path" name="my_option_name[id_path][' . $i . ']" value="' . $my_option_name['id_path'][$i] . '" /></td>';                           
                $html .= '<td class="id_remove_btn" data-id="' . $i . '">X</td>';
            $html .= '</table>';
            $i++;
        }

        return wp_send_json ( $html );
    }
    
    
    
    function my_action_javascript() { ?>
        <script type="text/javascript" >
        function add_chart_data($) {
    
            $("#add-workspace-btn").on( "click", function(event) {
            event.preventDefault();
    
                var data = {
                    'action': 'my_action',
                    'postid': $("#chart-data-table").attr('data-chartid'),
                    'whatever': $(".tr-char").length,
                    'count_del': $(".meta-box-btn-remove").length,
                    'count': $(".id_name").length
                };
    
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                    console.log(response);
                    $("#my-setting-workplace").append(response);
                });
    
            });
    
        }

        function remove_btn_data($) {
        $(".id_remove_btn").on( "click", function(event) {
            event.preventDefault();
    
                var data = {
                    'action': 'id_remove',
                    'dataid': $(this).attr('data-id')
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                    console.log(response);
                    $("#my-setting-workplace").empty();
                    $("#my-setting-workplace").append(response);
                    remove_btn_data($);
                });
    
            });
        }

        jQuery(document).ready(function($) {
            add_chart_data($);
            remove_btn_data($);
        });
        </script> <?php
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
                    <?php
                        // This prints out all hidden setting fields
                        settings_fields( 'my_option_group' );
                        do_settings_sections( 'my-setting-admin' );
                        ?><div id="my-setting-workplace">
                            <?php foreach ($my_option_name['id_name'] as $id_name ) { ?>
                            <table>
                                <?php echo '<td><input type="text" id="id_name" class="id_name" name="my_option_name[id_name][' . $i . ']" value="' . $id_name . '" /></td>'; ?>
                                <?php echo '<td><input type="text" id="id_path" class="id_path" name="my_option_name[id_path][' . $i . ']" value="' . $my_option_name['id_path'][$i] . '" /></td>'; ?>                             
                                <?php echo '<td class="id_remove_btn" data-id="' . $i . '">X</td>'; ?>
                                <?php $i++; ?>
                            </table>
                            <?php foreach ($blogusers as $bloguser) {
                                    $read_ = false;
                                    $write_ = false;
                                    echo $bloguser->user_login . ' - ';
                                    $my_option_read = $my_option_name['read-'.$id_name];
                                    foreach ( $my_option_read as $read) {
                                        if ($read == $bloguser->ID) {
                                            $read_ = true;
                                        }
                                    }
                                    if ($read_ == true) {
                                        echo 'Read<input type="checkbox" id="read-' . $id_name . '" name="my_option_name[read-' . $id_name . '][' . $m . ']" value="' . $bloguser->ID .'" checked>';
                                    } else {
                                        echo 'Read<input type="checkbox" id="read-' . $id_name . '" name="my_option_name[read-' . $id_name . '][' . $m . ']" value="' . $bloguser->ID .'">';
                                    }

                                    $my_option_write = $my_option_name['write-'.$id_name];
                                    foreach ( $my_option_write as $write) {
                                        if ($write == $bloguser->ID) {
                                            $write_ = true;
                                        }
                                    }
                                    if ($write_ == true) {
                                        echo 'Write<input type="checkbox" id="write-' . $id_name . '" name="my_option_name[write-' . $id_name . '][' . $m . ']" value="' . $bloguser->ID .'" checked>';
                                    } else {
                                        echo 'Write<input type="checkbox" id="write-' . $id_name . '" name="my_option_name[write-' . $id_name . '][' . $m . ']" value="' . $bloguser->ID .'">';
                                    }
                                    $m++;
                                }
                                $read_ = false;
                                $write_ = false;
                                echo 'Public - ';
                                $my_option_read = $my_option_name['read-'.$id_name];
                                foreach ( $my_option_read as $read) {
                                    if ($read == -1) {
                                        $read_ = true;
                                    }
                                }
                                if ($read_ == true) {
                                    echo 'Read<input type="checkbox" id="read-' . $id_name . '" name="my_option_name[read-' . $id_name . '][' . $m . ']" value="-1" checked>';
                                } else {
                                    echo 'Read<input type="checkbox" id="read-' . $id_name . '" name="my_option_name[read-' . $id_name . '][' . $m . ']" value="-1">';
                                }

                                $my_option_write = $my_option_name['write-'.$id_name];
                                foreach ( $my_option_write as $write) {
                                    if ($write == -1) {
                                        $write_ = true;
                                    }
                                }
                                if ($write_ == true) {
                                    echo 'Write<input type="checkbox" id="write-' . $id_name . '" name="my_option_name[write-' . $id_name . '][' . $m . ']" value="-1" checked>';
                                } else {
                                    echo 'Write<input type="checkbox" id="write-' . $id_name . '" name="my_option_name[write-' . $id_name . '][' . $m . ']" value="-1">';
                                }
                                $m++;
                            }
                            ?></div><?php
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

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );

        add_settings_field(
            'title', 
            'Home path', 
            array( $this, 'title_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
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

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print '<div id="add-workspace-btn">Add Workplace</div>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="my_option_name[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
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
    $workplace = $_GET['workplace'];
    $share = $_GET['share'];
    $my_option_name = get_option('my_option_name');
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
    } elseif (isset($workplace)) { 
        foreach ($my_option_name['id_path'] as $id_path) {
            $id_path_ = rtrim($id_path, "/");
            $workplace_ = rtrim($workplace, "/");
            if (strpos($workplace_, $id_path_) !== false) {
                $explodeidread = explode(',', $id_read[$my_option_name['id_name'][$i]]);
                if (in_array($user->ID, $explodeidread)) {
                    $read_path = true;
                }
                $explodeidwrite = explode(',', $id_write[$my_option_name['id_name'][$i]]);
                if (in_array($user->ID, $explodeidwrite)) {
                    $write_path = true;
                }
            }
            $i++;
        }
        foreach($my_option_name['id_path'] as $id_path) {
            $id_path_ = rtrim($id_path, "/");
            $workplace_ = rtrim($workplace, "/");
            if ($workplace_ == $id_path_) {
                $workplace_last = true;
            }
            if (strpos($workplace, $id_path_) !== false) {
                $workplace_strpos = true;
            }
        }
        $path_implode = $workplace;
    } elseif (isset($path)) {
        if (in_array($user->ID, $id_read_path)) {
            $read_path = true;
            $workplace_strpos = true;
        }
        if (in_array($user->ID, $id_write_path)) {
            $write_path = true;
        }
        $path_implode = $path;
    } elseif (isset($share)) {
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
    } else {
        $path_implode = null;
    }

    echo "<div id='sequentialupload' class='sequentialupload' data-object-id='$path_implode'></div>"; 
    ?><script type="text/javascript">document.getElementById("sequentialupload").style.display = "none";</script><?php

    ?><div id='errorlog'></div><?php
    ?><div id='filemanager-wrapper' class='filemanager-wrapper'><?php

    if ($path == null && $home == null && $workplace == null && $share == null) {

    echo "<div id='filemanager-home' class='filemanager-home'>";
    if ($user->ID != '-1') {
        ?><a id='file-id' class='filemanager-home-click' href='<?php echo home_url($wp->request) . '/' . get_post_field( 'post_name' ); ?>/?home=<?php echo $my_option_name['title'] . esc_html($user->user_login) ?>'>Home</a></br><?php
    }

    foreach ($my_option_name['id_name'] as $id_name){
        $explodeidread = explode(',', $id_read[$id_name]);
        if (in_array($user->ID, $explodeidread)) {
            ?><a id='file-id' class='filemanager-home-click' href='<?php echo home_url($wp->request) ?>/?workplace=<?php echo $my_option_name['id_path'][$x] ?>'><?php echo $id_name ?></a></br><?php
            $i++;
        }
        $x++;
    }

    if (in_array($user->ID, $id_read_path)) {
        ?><a id='file-id' class='filemanager-home-click' href='<?php echo home_url($wp->request) . '/' . get_post_field( 'post_name' ); ?>/?path=<?php echo ABSPATH ?>'>Path</a><?php
    }

    echo "</div>";

    } else {

        if ($workplace_strpos == true && $read_path == true){
            $allFiles = scandir($path_implode);
            $files = array_diff($allFiles, array('.', '..'));
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
                                <?php if($user->ID != '-1'){ ?>
                                    <div class='btndelete'>Delete</div>
                                <?php } ?>
                                <div class='subnav subnavzip'>
                                    <button class='subnavbtn btnzip'>Create zip</button>
                                    <div id='subnav-content-zip' class='subnav-content'>
                                        <span>
                                            <input type='text' id='lnamezip' name='lname'></input>
                                            <button class='zipbtn'>create</button>
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
                                    if (isset($workplace)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?workplace=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                    if (isset($path)) { ?> <a class='btnback_' href='<?php echo home_url($wp->request) . "/?path=" . dirname($path_implode) ?>'>Parent directory</a> <?php }
                                } 
                                if ($path_parts[1] == '' || $workplace_last == true || $workplace_strpos != true) { ?>
                                <a class='btnback_home' href='<?php echo home_url($wp->request) ?>'>Home</a>
                            <?php } ?>
                            <div class='btninfo'>Info</div>
                        </div>
                    </div>
                    <div class='filepath'><?php foreach($path_parts as $path_part) {
                        $path_part_ .= '/'.$path_part;
                        if (isset($home)) { ?><a href='<?php  echo home_url($wp->request) . "/?home=" .  realpath($path_part_)?>'><?php echo $path_part; ?></a><?php }
                        if (isset($workplace)) { ?><a href='<?php  echo home_url($wp->request) . "/?workplace=" .  realpath($path_part_)?>'><?php echo $path_part; ?></a><?php }
                        if (isset($path)) { ?><a href='<?php  echo home_url($wp->request) . "/?path=" .  realpath($path_part_)?>'><?php echo $path_part; ?></a><?php }
                        echo '/';
                    }?></div><?php
                    ?><div class='file-table'><table id='file-table'><?php
                        foreach($files as $file){
                            $pathfilezise = $path_implode.'/'.$file;
                            $filesize = formatSizeUnits(filesize($pathfilezise));
                            $realpath = realpath($path_implode.'/'.$file);
                            if ( is_dir($realpath) == true ) {
                                if (isset($home)) {
                                    echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?home=$realpath'>$file</a></td><td>$filesize</td></tr>";
                                }
                                if (isset($workplace)) { 
                                    echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?workplace=$realpath'>$file</a></td><td>$filesize</td></tr>";
                                }
                                if (isset($path)) {
                                    echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?path=$realpath'>$file</a></td><td>$filesize</td></tr>";
                                }
                            } else {
                                $getname = getName(32);
                                $getoauth = uniqid(time().'||'.$getname.'||'.$realpath.'||'.$_SERVER["HTTP_CF_CONNECTING_IP"].'||',TRUE);
                                if (isset($home)) {
                                    echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?home=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
                                }
                                if (isset($workplace)) { 
                                    echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?workplace=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
                                }
                                if (isset($path)) {
                                    echo "<tr><td><input class='checkbox' type='checkbox' name='$realpath'/></td><td class='filemanager-table'><a id='file-id' class='filemanager-click' href='" . home_url($wp->request) . "/?path=$realpath&oauth=$getname'>$file</a></td><td>$filesize</td></tr>";
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
                    echo "</table></div></div>";

                } elseif ( isset($path) || isset($workplace) || isset($home) || isset($share)) {
                    // Include the configuration file
                    require_once dirname(__FILE__) . '/config.php';
        
                    $object_id = $path_implode;
                    $getname = getName(6);
                    $target =  $object_id;
                    $direname = dirname($object_id);
                    $basename = basename($object_id);
                    $path_ = pathinfo($basename);
                    $ext = $path_['extension'];
                    $key = trim($_GET['oauth']);
        
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
                        if($share){
                            echo "<script>location.href='?share=".$_GET['share']."';</script>";
                        } else {
                            echo "false";
                            // Return 404 error, if not a correct path
                            header("HTTP/1.0 404 Not Found");
                            exit;
                        }
                    }else{    
                        // If the files exist
                        if($key){
                            // Generate download link
                            $download_link = DOWNLOAD_PATH."?key=".$key; 
                        }
                    }  
                      
                    ?><script type="text/javascript">document.getElementById("filemanagerbtnup").style.display = "none";</script><?php
        
                    if(!$share){
                        echo "<div class='navbar'>
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

                    ?><script type="text/javascript">jQuery(document).ready(function($) {
                        filemanager_share_files($);
                    });</script><?php
                
                    if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'bmp' || $ext == 'png' || $ext == 'gif') {
                        echo '<img src="' . $download_link . '"></img>';
                    } elseif ($ext == 'mp4' || $ext == 'mkv' || $ext == 'avi' ) {
                        echo '<div class="file-info">' . basename($object_id) . '</div>';
                        ?><video id='filemanagervideo' controls="controls" preload="auto" controlsList="nodownload" name="media">
                            <source src="<?php echo $download_link ?>" type="video/mp4">
                        </video><?php
                    } elseif ($ext == 'pdf') {
                        echo '<div id="pdf"></div>';
                        ?><script type="text/javascript">PDFObject.embed('<?php echo $download_link ?>', "#pdf");</script><?php
                    } elseif ($ext == 'txt' || $ext == 'html' || $ext == 'php' || $ext == 'log') { 
                        if ($write_path == true) {
                            echo '<div class="navbar"><div id="savefile" onclick="savefile();">Save</div></div>';
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
                    } else {
                        echo '<a href="' . $download_link . '">Download</a>';
                    }
                    echo "</div>";
                }
        
             } else { 
        
            echo "<script> location.href='" . home_url($wp->request) . "/'; </script>";
            exit;

            }
        }

    ?></div><?php

}

    
add_shortcode('filemanager-shortcode', 'filemanager_shortcode');

?>