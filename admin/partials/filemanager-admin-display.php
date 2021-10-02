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
    update_post_meta( $post_id, "_share_path", $_POST['share-key-textarea'] );

    
    
}
add_action("save_post", "save_share_meta_box", 10, 3);

function my_edit_shares_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title' ),
        'author' => __( 'Author' ),
        'share_path' => __( 'Path' ),
        'share_key' => __( 'Key' )
    );

    return $columns;
}
add_filter( 'manage_edit-shares_columns', 'my_edit_shares_columns' ) ;

function my_manage_shares_columns( $column, $post_id ) {
    global $post;

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

        /* Just break out of the switch statement for everything else. */
        default :
        break;
    }
}
add_action( 'manage_shares_posts_custom_column', 'my_manage_shares_columns', 10, 2 );
?>
