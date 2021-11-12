<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://jerl92.tk
 * @since      1.0.0
 *
 * @package    File_Manager
 * @subpackage File_Manager/admin/partials
 */

function custom_meta_box_share($object)
{
wp_nonce_field(basename(__FILE__), "meta-box-nonce");
$blogusers = get_users();
?>
    <div style="text-align: center;">
        <label>Share Path</label>
        <br>
        <?php $share_path = get_post_meta($object->ID, "_share_path", true); ?>
        <input name="share-path-textarea" type="text" id="share-path-textarea" value="<?php echo $share_path; ?>" size="30">
        <br>
        <label>Share key</label>
        <br>
        <?php $share_key = get_post_meta($object->ID, "_share_key", true); ?>
        <input name="share-key-textarea" type="text" id="share-key-textarea" value="<?php echo $share_key; ?>" size="30">
        <br>
        <label>Permision</label>
        <br>
        <?php 
        $share_right = get_post_meta($object->ID, "_share_right", true); ?>
        <?php foreach ($blogusers as $bloguser) {
            $read_ = false;
            $write_ = false;
            echo $bloguser->user_login . ' - ';
            $my_option_read = $share_right[$bloguser->ID]['read'];
            if ($my_option_read == 1) {
                $read_ = true;
            }
            if ($read_ == true) {
                echo 'Read<input type="checkbox" id="read" name="read[]" value="' . $bloguser->ID .'" checked>';
            } else {
                echo 'Read<input type="checkbox" id="read" name="read[]" value="' . $bloguser->ID .'">';
            }

            $my_option_write = $share_right[$bloguser->ID]['write'];
            if ($my_option_write == 1) {
                $write_ = true;
            }
            if ($write_ == true) {
                echo 'Write<input type="checkbox" id="write" name="write[]" value="' . $bloguser->ID .'" checked>';
            } else {
                echo 'Write<input type="checkbox" id="write" name="write[]" value="' . $bloguser->ID .'">';
            }
            echo "<br>";
        }
        $read_ = false;
        $write_ = false;
        echo 'Public - ';
        $my_option_read = $share_right[-1]['read'];
        if ($my_option_read == 1) {
            $read_ = true;
        }
        if ($read_ == true) {
            echo 'Read<input type="checkbox" id="read" name="read[]" value="-1" checked>';
        } else {
            echo 'Read<input type="checkbox" id="read" name="read[]" value="-1">';
        }

        $my_option_write = $share_right[-1]['write'];
        if ($my_option_write == 1) {
            $write_ = true;
        }
        if ($write_ == true) {
            echo 'Write<input type="checkbox" id="write" name="write[]" value="-1" checked>';
        } else {
            echo 'Write<input type="checkbox" id="write" name="write[]" value="-1">';
        }
        echo "<br>"; ?>
        <br>
    </div>

<?php  
}

function add_share_meta_box()
{
    add_meta_box("date-meta-box", "Share link meta data", "custom_meta_box_share", "shares", "normal", "low", null);
}
add_action("add_meta_boxes", "add_share_meta_box");

function save_share_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
    return $post_id;
    if(!current_user_can("edit_post", $post_id))
        return $post_id;
    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    $slug = "shares";
    if($slug != $post->post_type)
        return $post_id;

    if( ! isset( $_POST['share-path-textarea'] ) )
    return; 
    update_post_meta( $post_id, "_share_path", $_POST['share-path-textarea'] );

    if( ! isset( $_POST['share-key-textarea'] ) )
    return; 
    update_post_meta( $post_id, "_share_key", $_POST['share-key-textarea'] );

    if( isset( $_POST['read'] ) )
    foreach($_POST['read'] as $read_id) {
        $array[$read_id]['read'] = true;
    }
   
    if( isset( $_POST['write'] ) )
    foreach($_POST['write'] as $read_id) {
        $array[$read_id]['write'] = true;
    }

    update_post_meta( $post_id, "_share_right", $array );
    
}
add_action("save_post", "save_share_meta_box", 10, 3);

function custom_meta_box_workplace($object)
{
wp_nonce_field(basename(__FILE__), "meta-box-nonce");
$blogusers = get_users();
?>
    <div style="text-align: center;">
        <label>workplace Path</label>
        <br>
        <?php $workplace_path = get_post_meta($object->ID, "_workplace_path", true); ?>
        <input name="workplace-path-textarea" type="text" id="workplace-path-textarea" value="<?php echo $workplace_path; ?>" size="30">
        <br>
        <label>User right</label>
        <br>
        <?php $workplace_right = get_post_meta($object->ID, "_workplace_right", true); ?>
        <?php foreach ($blogusers as $bloguser) {
            $read_ = false;
            $write_ = false;
            echo $bloguser->user_login . ' - ';
            $my_option_read = $workplace_right[$bloguser->ID]['read'];
            if ($my_option_read == 1) {
                $read_ = true;
            }
            if ($read_ == true) {
                echo 'Read<input type="checkbox" id="read" name="read[]" value="' . $bloguser->ID .'" checked>';
            } else {
                echo 'Read<input type="checkbox" id="read" name="read[]" value="' . $bloguser->ID .'">';
            }

            $my_option_write = $workplace_right[$bloguser->ID]['write'];
            if ($my_option_write == 1) {
                $write_ = true;
            }
            if ($write_ == true) {
                echo 'Write<input type="checkbox" id="write" name="write[]" value="' . $bloguser->ID .'" checked>';
            } else {
                echo 'Write<input type="checkbox" id="write" name="write[]" value="' . $bloguser->ID .'">';
            }
            echo "<br>";
        }
        $read_ = false;
        $write_ = false;
        echo 'Public - ';
        $my_option_read = $workplace_right[-1]['read'];
        if ($my_option_read == 1) {
            $read_ = true;
        }
        if ($read_ == true) {
            echo 'Read<input type="checkbox" id="read" name="read[]" value="-1" checked>';
        } else {
            echo 'Read<input type="checkbox" id="read" name="read[]" value="-1">';
        }

        $my_option_write = $workplace_right[-1]['write'];
        if ($my_option_write == 1) {
            $write_ = true;
        }
        if ($write_ == true) {
            echo 'Write<input type="checkbox" id="write" name="write[]" value="-1" checked>';
        } else {
            echo 'Write<input type="checkbox" id="write" name="write[]" value="-1">';
        }
        echo "<br>";?>
        <br>
    </div>

<?php  
}

function add_workplace_meta_box()
{
    add_meta_box("date-meta-box", "Workplace Path", "custom_meta_box_workplace", "workplace", "normal", "low", null);
}
add_action("add_meta_boxes", "add_workplace_meta_box");

function save_workplace_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
    return $post_id;
    if(!current_user_can("edit_post", $post_id))
        return $post_id;
    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    $slug = "workplace";
    if($slug != $post->post_type)
        return $post_id;

    if( ! isset( $_POST['workplace-path-textarea'] ) )
    return; 
    update_post_meta( $post_id, "_workplace_path", $_POST['workplace-path-textarea'] );

    
    if( isset( $_POST['read'] ) )
    foreach($_POST['read'] as $read_id) {
        $array[$read_id]['read'] = true;
    }
   
    if( isset( $_POST['write'] ) )
    foreach($_POST['write'] as $read_id) {
        $array[$read_id]['write'] = true;
    }

    update_post_meta( $post_id, "_workplace_right", $array );

}
add_action("save_post", "save_workplace_meta_box", 10, 3);

function custom_meta_box_disk($object)
{
wp_nonce_field(basename(__FILE__), "meta-box-nonce");
?>
    <div style="text-align: center;">
        <label>disk Path</label>
        <br>
        <?php $disk_path = get_post_meta($object->ID, "_disk_path", true); ?>
        <input name="disk-path-textarea" type="text" id="disk-path-textarea" value="<?php echo $disk_path; ?>" size="30">
        <br>
    </div>

<?php  
}

function add_disk_meta_box()
{
    add_meta_box("date-meta-box", "disk link meta data", "custom_meta_box_disk", "disk", "normal", "low", null);
}
add_action("add_meta_boxes", "add_disk_meta_box");

function save_disk_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
    return $post_id;
    if(!current_user_can("edit_post", $post_id))
        return $post_id;
    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    $slug = "disk";
    if($slug != $post->post_type)
        return $post_id;

    if( ! isset( $_POST['disk-path-textarea'] ) )
    return; 
    update_post_meta( $post_id, "_disk_path", $_POST['disk-path-textarea'] );   
    
}
add_action("save_post", "save_disk_meta_box", 10, 3);

function my_edit_shares_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title' ),
        'author' => __( 'Author' ),
        'share_path' => __( 'Path' ),
        'share_key' => __( 'Key' ),
        'share_link' => __( 'Link' )
    );

    return $columns;
}
add_filter( 'manage_edit-shares_columns', 'my_edit_shares_columns' );

function my_manage_shares_columns( $column, $post_id ) {
    global $post;
    $my_option_name = get_option('my_option_name');
    $slug = get_post_field( 'post_name', $my_option_name['selected_page'] );
    
    switch( $column ) {

        case 'author' :
            echo the_author();
        break;

        case 'share_path' :
            echo get_post_meta( $post->ID, '_share_path', true);
        break;

        case 'share_key' :
            echo get_post_meta( $post->ID, '_share_key', true);
        break;

        case 'share_link' :
            echo "<div class='sharelinkbtn' value='".get_home_url()."/".$slug."/?share=".get_post_meta( $post->ID, '_share_key', true)."'>".__( 'Copy to clipboard' )."</div>";
        break;

        /* Just break out of the switch statement for everything else. */
        default :
        break;
    }

    ?><script>
    var elements = document.getElementsByClassName('sharelinkbtn');

    var myFunction = function(event) {
        var url = this.getAttribute('value');
        console.log(this.getAttribute('value'));
        navigator.clipboard.writeText(url);
    };

    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener('click', myFunction, false);
    }
  </script><?php    

}
add_action( 'manage_shares_posts_custom_column', 'my_manage_shares_columns', 10, 2 );

function my_edit_workplace_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title' ),
        'author' => __( 'Author' ),
        'workplace_path' => __( 'Path' )
    );

    return $columns;
}
add_filter( 'manage_edit-workplace_columns', 'my_edit_workplace_columns' ) ;

function my_manage_workplace_columns( $column, $post_id ) {
    global $post;

    switch( $column ) {

        case 'author' :
            echo the_author();
        break;

        case 'workplace_path' :
            echo get_post_meta( $post->ID, '_workplace_path', true);
        break;

        /* Just break out of the switch statement for everything else. */
        default :
        break;
    }
}
add_action( 'manage_workplace_posts_custom_column', 'my_manage_workplace_columns', 10, 2 );
?>
