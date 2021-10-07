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
            'show_in_menu'          => false,
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

  function create_posttype_workplaces() {
    register_post_type( 'workplace',
      array(
        'labels' => array(
          'name' => __( 'Workplace path' ),
          'singular_name' => __( 'Workplace' )
        ),
            'rewrite' => array('slug' => 'workplace'),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,		
            'exclude_from_search'   => false,
            'publicly_queryable'    => false,
            'supports'      => array( 'title', 'page-attributes', 'editor'),
      )
    );
  }
  add_action( 'init', 'create_posttype_workplaces' );

  

  ?>