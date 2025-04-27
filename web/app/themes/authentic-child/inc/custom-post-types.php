<?php
/**
* This File Contains Custom Post Types 
*/
// Our Contributors CPT
function csco_custom_post_type(){
	$labels = array(
		'name'               => _x( 'Our Contributors', 'authentic' ),
		'singular_name'      => _x( 'Our Contributor', 'authentic' ),
		'menu_name'          => _x( 'Our Contributors', 'authentic'),
		'name_admin_bar'     => _x( 'Our Contributors', 'authentic' ),
		'add_new'            => _x( 'Add New Contributor', 'authentic' ),
		'add_new_item'       => __( 'Add New Contributor'),
		'new_item'           => __( 'New Contributor'),
		'edit_item'          => __( 'Edit Contributor'),
		'view_item'          => __( 'View Contributor'),
		'all_items'          => __( 'All Contributor'),
		'search_items'       => __( 'Search Contributor'),
		'parent_item_colon'  => __( 'Parent Contributor:'),
		'not_found'          => __( 'No Contributor found.'),
		'not_found_in_trash' => __( 'No Contributor found in Trash.' )
	);
	$args = array(
		'labels'             => $labels,
	 	'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => 'dashicons-businessperson',
        'show_in_rest' => true,
        'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes'),
        'show_admin_column' => true,
        'exclude_from_search' => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'show_in_menu'          => true,
        'can_export' => true,
        'publicly_queryable'    => true,
        'hierarchical' => true,
        'capability_type' => 'post',
        'menu_position' => 7,
        'rewrite' => array('slug' => 'our_contributors')
	);
	register_post_type('our_contributors', $args);
}
// add_action('init', 'csco_custom_post_type');

// Our Contributors taxonomy
function csco_our_contributors_taxonomies(){
	$labels = array(
		'name'              => _x( 'Contributor Categories', 'authentic' ),
		'singular_name'     => _x( 'Contributor Category', 'authentic' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Categories' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Contributor Category' ),
		'menu_name'         => __( 'Contributor Categories' ),
		'rewrite' => array('slug' => 'our_contributors_taxonomies'),
    );
    $args = array(
		'labels' => $labels,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite' => array('slug' => 'our_contributors_taxonomies'),
		'hierarchical' => true,
    );
    register_taxonomy('our_contributors_taxonomies', 'our_contributors', $args);
}
// add_action('init', 'csco_our_contributors_taxonomies', 0);

// Resources CPT
function csco_custom_resource_post_type(){
	$labels = array(
		'name'               => _x( 'Resources', 'authentic' ),
		'singular_name'      => _x( 'Resource', 'authentic' ),
		'menu_name'          => _x( 'Resources', 'authentic'),
		'name_admin_bar'     => _x( 'Resources', 'authentic' ),
		'add_new'            => _x( 'Add New Resource', 'authentic' ),
		'add_new_item'       => __( 'Add New Resource'),
		'new_item'           => __( 'New Resource'),
		'edit_item'          => __( 'Edit Resource'),
		'view_item'          => __( 'View Resource'),
		'all_items'          => __( 'All Resource'),
		'search_items'       => __( 'Search Resource'),
		'parent_item_colon'  => __( 'Parent Resource:'),
		'not_found'          => __( 'No Resource found.'),
		'not_found_in_trash' => __( 'No Resource found in Trash.' )
	);
	$args = array(
		'labels'             => $labels,
	 	'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => 'dashicons-cloud-upload',
        'show_in_rest' => true,
        'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes'),
        'show_admin_column' => true,
        'exclude_from_search' => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'show_in_menu'          => true,
        'can_export' => true,
        'publicly_queryable'    => true,
        'hierarchical' => true,
        'capability_type' => 'post',
        'menu_position' => 6,
        'rewrite' => array('slug' => 'resources')
	);
	register_post_type('resources', $args);
}
add_action('init', 'csco_custom_resource_post_type');

// Resources taxonomy
function csco_resources_taxonomies(){
	$labels = array(
		'name'              => _x( 'Resource Categories', 'authentic' ),
		'singular_name'     => _x( 'Resource Category', 'authentic' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Category' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Resource Category' ),
		'menu_name'         => __( 'Resource Categories' ),
		'rewrite' => array('slug' => 'resources_taxonomies'),
    );
    $args = array(
		'labels' => $labels,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite' => array('slug' => 'resources_taxonomies'),
		'hierarchical' => true,
    );
    register_taxonomy('resources_taxonomies', 'resources', $args);
}
add_action('init', 'csco_resources_taxonomies', 0);

// Course CPT
function csco_custom_course_post_type(){
	$labels = array(
		'name'               => _x( 'Course', 'authentic' ),
		'singular_name'      => _x( 'Course', 'authentic' ),
		'menu_name'          => _x( 'Course', 'authentic'),
		'name_admin_bar'     => _x( 'Course', 'authentic' ),
		'add_new'            => _x( 'Add New Course', 'authentic' ),
		'add_new_item'       => __( 'Add New Course'),
		'new_item'           => __( 'New Course'),
		'edit_item'          => __( 'Edit Course'),
		'view_item'          => __( 'View Course'),
		'all_items'          => __( 'All Course'),
		'search_items'       => __( 'Search Course'),
		'parent_item_colon'  => __( 'Parent Course:'),
		'not_found'          => __( 'No Course found.'),
		'not_found_in_trash' => __( 'No Course found in Trash.' )
	);
	$args = array(
		'labels'             => $labels,
	 	'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'show_in_rest' => true,
        'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes'),
        'show_admin_column' => true,
        'exclude_from_search' => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'show_in_menu'          => true,
        'can_export' => true,
        'publicly_queryable'    => true,
        'hierarchical' => true,
        'capability_type' => 'post',
        'menu_position' => 7,
        'rewrite' => array('slug' => 'courses')
	);
	register_post_type('courses', $args);
}
// add_action('init', 'csco_custom_course_post_type');

// Course taxonomy
function csco_course_taxonomies(){
	$labels = array(
		'name'              => _x( 'Course Categories', 'authentic' ),
		'singular_name'     => _x( 'Course Category', 'authentic' ),
		'search_items'      => __( 'Search Categories' ),
		'all_items'         => __( 'All Category' ),
		'parent_item'       => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item'         => __( 'Edit Category' ),
		'update_item'       => __( 'Update Category' ),
		'add_new_item'      => __( 'Add New Category' ),
		'new_item_name'     => __( 'New Course Category' ),
		'menu_name'         => __( 'Course Categories' ),
		'rewrite' => array('slug' => 'courses_taxonomies'),
    );
    $args = array(
		'labels' => $labels,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite' => array('slug' => 'courses_taxonomies'),
		'hierarchical' => true,
    );
    register_taxonomy('courses_taxonomies', 'courses', $args);
}
// add_action('init', 'csco_course_taxonomies', 0);

// FAQ CPT
function csco_custom_faq_post_type(){
	$labels = array(
		'name'               => _x( 'FAQ', 'authentic' ),
		'singular_name'      => _x( 'FAQ', 'authentic' ),
		'menu_name'          => _x( 'FAQ', 'authentic'),
		'name_admin_bar'     => _x( 'FAQ', 'authentic' ),
		'add_new'            => _x( 'Add New FAQ', 'authentic' ),
		'add_new_item'       => __( 'Add New FAQ'),
		'new_item'           => __( 'New FAQ'),
		'edit_item'          => __( 'Edit FAQ'),
		'view_item'          => __( 'View FAQ'),
		'all_items'          => __( 'All FAQ'),
		'search_items'       => __( 'Search FAQ'),
		'parent_item_colon'  => __( 'Parent FAQ:'),
		'not_found'          => __( 'No FAQ found.'),
		'not_found_in_trash' => __( 'No FAQ found in Trash.' )
	);
	$args = array(
		'labels'             => $labels,
	 	'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'show_in_rest' => true,
        'supports' => array('title','excerpt', 'editor','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes'),
        'show_admin_column' => true,
        'exclude_from_search' => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'show_in_menu'          => true,
        'can_export' => true,
        'publicly_queryable'    => true,
        'hierarchical' => true,
        'capability_type' => 'post',
        'menu_position' => 8,
        'rewrite' => array('slug' => 'faq')
	);
	register_post_type('faq', $args);
}
add_action('init', 'csco_custom_faq_post_type');

// FAQ taxonomy (Category)
function csco_faq_cat_taxonomies(){
	$labels = array(
		'name'              => _x( 'FAQ Categories', 'authentic' ),
		'singular_name'     => _x( 'FAQ Category', 'authentic' ),
		'search_items'      => __( 'Search FAQ Category' ),
		'all_items'         => __( 'All FAQ Category' ),
		'parent_item'       => __( 'Parent FAQ Category' ),
		'parent_item_colon' => __( 'Parent FAQ Category:' ),
		'edit_item'         => __( 'Edit FAQ Category' ),
		'update_item'       => __( 'Update FAQ Category' ),
		'add_new_item'      => __( 'Add New FAQ Category' ),
		'new_item_name'     => __( 'New FAQ Category' ),
		'menu_name'         => __( 'FAQ Categories'),
		'rewrite' => array('slug' => 'faq_cat'),
    );
    $args = array(
		'labels' => $labels,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite' => array('slug' => 'faq_cat'),
		'hierarchical' => true,
    );
    register_taxonomy('faq_cat', 'faq', $args);
}
add_action('init', 'csco_faq_cat_taxonomies', 0);

// FAQ taxonomy (Tag)
function csco_faq_tag_taxonomies(){
	$labels = array(
		'name'              => _x( 'FAQ Tags', 'authentic' ),
		'singular_name'     => _x( 'FAQ Tag', 'authentic' ),
		'search_items'      => __( 'Search FAQ Tag' ),
		'all_items'         => __( 'All FAQ Tag' ),
		'parent_item'       => __( 'Parent FAQ Tag' ),
		'parent_item_colon' => __( 'Parent FAQ Tag' ),
		'edit_item'         => __( 'Edit FAQ Tag' ),
		'update_item'       => __( 'Update FAQ Tag' ),
		'add_new_item'      => __( 'Add New FAQ Tag' ),
		'new_item_name'     => __( 'New FAQ Tag' ),
		'menu_name'         => __( 'FAQ Tags' ),
		'rewrite' => array('slug' => 'faq_tag'),
    );
    $args = array(
		'labels' => $labels,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite' => array('slug' => 'faq_tag'),
		'hierarchical' => false,
    );
    register_taxonomy('faq_tag', 'faq', $args);
}
add_action('init', 'csco_faq_tag_taxonomies', 0);

?>