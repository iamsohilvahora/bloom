<?php
/** 
 *	This file contains registration of blocks and it's category
 */
function csco_custom_category_blocks($categories){
    $custom_category = array(
        'slug'  => 'bloom-blocks',
        'title' => __('Bloom Custom Blocks', 'authentic'),
    );
    array_unshift($categories, $custom_category);
    return $categories;
}
add_action('block_categories_all', 'csco_custom_category_blocks', 10, 100);

function csco_register_blocks(){
    // Banner Block
    acf_register_block([
        'name'            => 'banner_block',
        'title'           => __('Banner Block', 'authentic'),
        'category' => 'bloom-blocks',
        'render_template' => 'template-parts/custom_blocks/banner-section.php',
        'enqueue_style' => get_stylesheet_directory_uri() . '/assets/css/custom-blocks-css/banner-section.css',
        'enqueue_script' => get_stylesheet_directory_uri() . '/assets/js/custom-blocks-js/banner-section.js'
    ]);

    // Logo Block
    acf_register_block([
        'name'            => 'logo_block',
        'title'           => __('Logo Block', 'authentic'),
        'category' => 'bloom-blocks',
        'render_template' => 'template-parts/custom_blocks/logo-section.php',
        'enqueue_style' => get_stylesheet_directory_uri() . '/assets/css/custom-blocks-css/logo-section.css',
        'enqueue_script' => get_stylesheet_directory_uri() . '/assets/js/custom-blocks-js/logo-section.js'
    ]);

    // Post Category Block
    acf_register_block([
        'name'            => 'category_block',
        'title'           => __('Category Block', 'authentic'),
        'category' => 'bloom-blocks',
        'render_template' => 'template-parts/custom_blocks/posts-category.php',
        'enqueue_style' => get_stylesheet_directory_uri() . '/assets/css/custom-blocks-css/posts-category.css',
        'enqueue_script' => get_stylesheet_directory_uri() . '/assets/js/custom-blocks-js/posts-category.js'
    ]);

    // Our Contributor Block
    acf_register_block([
        'name'            => 'our_contributor',
        'title'           => __('Contributor Block', 'authentic'),
        'category' => 'bloom-blocks',
        'render_template' => 'template-parts/custom_blocks/our-contributor.php',
        'enqueue_style' => get_stylesheet_directory_uri() . '/assets/css/custom-blocks-css/our-contributor.css',
        'enqueue_script' => get_stylesheet_directory_uri() . '/assets/js/custom-blocks-js/our-contributor.js'
    ]);

     // Workshop Block
    acf_register_block([
        'name'            => 'workshop_block',
        'title'           => __('Workshop Block', 'authentic'),
        'category' => 'bloom-blocks',
        'render_template' => 'template-parts/custom_blocks/about-workshop.php',
        'enqueue_style' => get_stylesheet_directory_uri() . '/assets/css/custom-blocks-css/about-workshop.css',
        'enqueue_script' => get_stylesheet_directory_uri() . '/assets/js/custom-blocks-js/about-workshop.js'
    ]);

    // Post List Block
    acf_register_block([
        'name'            => 'post_list_block',
        'title'           => __('Bloom\'s top picks', 'authentic'),
        'category' => 'bloom-blocks',
        'render_template' => 'template-parts/custom_blocks/post-list.php',
        'enqueue_style' => get_stylesheet_directory_uri() . '/assets/css/custom-blocks-css/post-list.css',
        'enqueue_script' => get_stylesheet_directory_uri() . '/assets/js/custom-blocks-js/post-list.js'
    ]);

    // Contributor List Block
    acf_register_block([
        'name'            => 'contributor_list',
        'title'           => __('Contributor List Block', 'authentic'),
        'category' => 'bloom-blocks',
        'render_template' => 'template-parts/custom_blocks/contributor-list.php',
        'enqueue_style' => get_stylesheet_directory_uri() . '/assets/css/custom-blocks-css/contributor-list.css',
        'enqueue_script' => get_stylesheet_directory_uri() . '/assets/js/custom-blocks-js/contributor-list.js'
    ]);

    // Membership Banner Block
    acf_register_block([
        'name'            => 'membership_banner',
        'title'           => __('Membership Banner Block', 'authentic'),
        'category' => 'bloom-blocks',
        'render_template' => 'template-parts/custom_blocks/membership_banner.php',
        'enqueue_style' => get_stylesheet_directory_uri() . '/assets/css/custom-blocks-css/membership_banner.css',
        'enqueue_script' => get_stylesheet_directory_uri() . '/assets/js/custom-blocks-js/membership_banner.js'
    ]);

    // Membership Subscriber Block
    acf_register_block([
        'name'            => 'membership_subscriber',
        'title'           => __('Membership Subscriber Slider Block', 'authentic'),
        'category' => 'bloom-blocks',
        'render_template' => 'template-parts/custom_blocks/membership-subscriber.php',
        'enqueue_style' => get_stylesheet_directory_uri() . '/assets/css/custom-blocks-css/membership-subscriber.css',
        'enqueue_script' => get_stylesheet_directory_uri() . '/assets/js/custom-blocks-js/membership-subscriber.js'
    ]);
}
add_action('init', 'csco_register_blocks');

/** 
 *  Assign post type to select_posts field
 */
function csco_acf_load_select_posts_field_choices($field){
    // reset choices
    $field['choices'] = array();
    
    // get list of post type
    $args = array(
        'public' => true,
    );

    $output = 'names'; // names or objects, here names is the default
    $operator = 'and'; // 'and' or 'or'

    $choices = get_post_types($args, $output, $operator); 

    // remove any unwanted white space
    $choices = array_map('trim', $choices);

    // loop through array and add to field 'choices'
    if(is_array($choices)){
        foreach($choices as $choice){
            if($choice == 'page' || $choice == 'attachment') continue;
            $field['choices'][$choice] = $choice;
        }
    }
    return $field;
}
// add_filter('acf/load_field/name=select_posts', 'csco_acf_load_select_posts_field_choices');

function csco_acf_load_post_category_list_choice($field){
  // Create an empty array
  $field['choices'] = array();
  // Get parent categories and order by name
  $categories = get_categories( 
    array(
        'parent'  => 0,
        'hide_empty'   => 0,
        'orderby' => 'name',
        'order'   => 'ASC',
    ) 
  );
  // Create a counter variable for the array
  $i = 0;
  $field['choices'][''] = 'Select post category';
  foreach($categories as $category){
    // Add each category to the array
    $field['choices'][$category->term_id] = $category->name;
    // Increment the loop counter
    $i++;
  }  
  // Return the result
  return $field;
}

add_filter('acf/load_field/name=select_post_category', 'csco_acf_load_post_category_list_choice');