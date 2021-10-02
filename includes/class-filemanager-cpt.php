 <?php

function create_posttype_shares() {
    register_post_type( 'shares',
      array(
        'labels' => array(
          'name' => __( 'Share files' ),
          'singular_name' => __( 'Share' )
        ),
            'rewrite' => array('slug' => 'shares'),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,		
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'supports'      => array( 'title', 'page-attributes', 'editor'),
      )
    );
  }
  add_action( 'init', 'create_posttype_shares' );

  ?>