<?php
/**
 * Button Group For Clone
 */
function button_group($field_name){
    if(!empty($field_name) && is_array($field_name)){
		$button_link = '';
		$button_link_type = $field_name['button_link'];
		$internal_link = $field_name['button_internal_link'];
		$external_link = $field_name['button_external_link'];
		if(($button_link_type == 'button_internal_link') && !empty($internal_link)){
			$button_link = csco_external_link($internal_link, false);
		} 
		elseif(($button_link_type == 'button_external_link') && !empty($external_link)){
			$button_link = csco_external_link($external_link, true);
		}
		if(!empty($button_link)){
			return $button_link;
		} 
		else{
			return '';
		}
    } 
    else{
    	return;
    }
}
function csco_external_link($link = null, $target = null){
    if(empty($link)){
        return;
    }
    $href_link = null;
    if(!empty($link) && $link != null){
        if($link == '#' ){
            $href_link = $link;
            $target = '';
        } 
        else{
            $url =  trim($link);
            if(!preg_match("~^(?:f|ht)tps?://~i", $url)){
                $href_link= "http://" . $url;
            }
            else{
                $href_link = trim($link);
            }
        }
    }
    if ($target == true){
        return 'href="'.$href_link.'" target="_blank"';
    }
    else{
        return 'href="'.$href_link.'"';
    }
}
/**
 * Set acf option page
 */
if(function_exists('acf_add_options_page')){
    acf_add_options_page(array(
        'page_title'    => 'Options',
        'menu_title'    => 'Options',
        'menu_slug'     => 'acf-options',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}

/**
 * Allowed mime types
 */
function csco_custom_mime_types($mimes){ 
    $mimes['svg']  = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'csco_custom_mime_types');

/**
 * add image size
 */
add_image_size('custom-img-size', 200, 300, true );

/**
 * Fire AJAX action for both logged in and non-logged in users (Load more button)
 */
add_action('wp_ajax_get_more_posts', 'csco_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_get_more_posts', 'csco_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}

function csco_loadmore_ajax_handler(){
    $posts_per_page = (isset($_POST['post_per_page'])) ? $_POST['post_per_page'] : 5;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;
    $pagedata = $posts_per_page * $page - $posts_per_page;
    $category_id = (isset($_POST['category_id'])) ? $_POST['category_id'] : "";
    $default_image = get_field('default_image', 'options');
    $sub_categorie_arr = get_categories(array(
                        'orderby' => 'name',
                        'order'   => 'ASC',
                        'parent'  => $category_id,
                        'fields' => 'ids' 
                    ));
    // get total post if sub category exists
    if(!empty($sub_categorie_arr)){
        $sub_categorie_arr[] = $category_id; // add parent category in sub-category array
        $total_post_args = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'category__in' => $sub_categorie_arr,
                );
        $the_query = new WP_Query($total_post_args);
        $totalpost = $the_query->found_posts;
    }
    // get total post of parent if sub catgory does not exists
    else{   
        $total_post_args = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'cat' => $category_id, 
                );
        $the_query = new WP_Query($total_post_args);
        $totalpost = $the_query->found_posts;
    }
    $taxonomy = "category";
    $args = array(
            'taxonomy'     => $taxonomy,
            'child_of'     => 0,
            'parent'       => $category_id,
            'orderby' => 'name',
            'order'   => 'ASC', 
            'hide_empty'   => false,
    );
    $sub_cats = get_categories($args);

    ob_start();
    if($sub_cats && !empty($sub_categorie_arr)):
        $post_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'category__in' => $sub_categorie_arr,          
            'posts_per_page' => $posts_per_page,
            'offset' => $pagedata,
        );
        $arr_posts = new WP_Query($post_args);
        if($arr_posts->have_posts()):
            while($arr_posts->have_posts()): $arr_posts->the_post();
                $categories = get_the_category(get_the_id()); // get category ids 
                $cat_class = "";
                if(!empty($categories)){
                    foreach ($categories as $cat) {
                        if(in_array($cat->term_id, $sub_categorie_arr)){
                            $cat_class .= $cat->term_id . " "; 
                        }
                    }
                } ?>
                <article class="sight-portfolio-entry sight-portfolio-custom <?php echo $cat_class; ?>">
                    <div class="sight-portfolio-entry-outer">
                        <?php 
                            if(has_post_thumbnail(get_the_id())):
                                $thumb_img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'medium'); ?>
                            <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                    <img width="1160" height="1741" src="<?php echo $thumb_img[0]; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                    <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                </figure>
                            </div>
                        <?php
                            else:
                                if(!empty($default_image)): ?>
                                    <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                        <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                            <img width="1160" height="1741" src="<?php echo $default_image['sizes']['custom-img-size']; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                            <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                        </figure>
                                    </div>
                                <?php endif; 
                            endif;
                        ?>
                        <div class="sight-portfolio-entry__inner sight-portfolio-entry__content">                       
                            <div class="sight-portfolio-entry__title">
                                <h3 class="sight-portfolio-entry__heading">
                                    <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                                </h3>
                            </div>
                            <div class="sight-portfolio-entry__meta">
                                <?php 
                                $post_categories = get_the_category();
                                if(!empty($post_categories)): ?>
                                <div class="sight-portfolio-meta-category">
                                    <?php foreach ($post_categories as $post_category): ?>
                                    <a href="<?php echo get_term_link($post_category->term_id); ?>" rel="category tag"><span class="sight-portfolio-meta-item"><?php echo $post_category->name; ?></span></a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <div class="sight-portfolio-meta-date">
                                    <?php echo get_the_date("F j, Y"); ?>               
                                </div>
                            </div>
                            <div class="sight-portfolio-entry__caption"><p><?php echo get_the_excerpt(); ?></p></div>
                        </div>
                    </div>
                </article>
            <?php
            endwhile; 
            wp_reset_postdata();
        endif;
    else: 
        $post_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'cat' => $category_id, 
            'posts_per_page' => $posts_per_page,
            'offset' => $pagedata,
        );
        $arr_posts = new WP_Query($post_args);
        if($arr_posts->have_posts()):
            while($arr_posts->have_posts()): $arr_posts->the_post(); ?>
                <article class="sight-portfolio-entry sight-portfolio-custom <?php echo $category_id; ?>">
                    <div class="sight-portfolio-entry-outer">
                        <?php 
                            if(has_post_thumbnail(get_the_id())):
                                $thumb_img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'medium'); ?>
                            <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                    <img width="1160" height="1741" src="<?php echo $thumb_img[0]; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                    <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                </figure>
                            </div>
                        <?php
                            else:
                                if(!empty($default_image)): ?>
                                    <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                        <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                            <img width="1160" height="1741" src="<?php echo $default_image['sizes']['custom-img-size']; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                            <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                        </figure>
                                    </div>
                                <?php endif; 
                            endif;
                        ?>
                        <div class="sight-portfolio-entry__inner sight-portfolio-entry__content">                       
                            <div class="sight-portfolio-entry__title">
                                <h3 class="sight-portfolio-entry__heading">
                                    <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                                </h3>
                            </div>
                            <div class="sight-portfolio-entry__meta">
                                <?php 
                                $post_categories = get_the_category();
                                if(!empty($post_categories)): ?>
                                <div class="sight-portfolio-meta-category">
                                    <?php foreach ($post_categories as $post_category): ?>
                                    <a href="<?php echo get_term_link($post_category->term_id); ?>" rel="category tag"><span class="sight-portfolio-meta-item"><?php echo $post_category->name; ?></span></a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <div class="sight-portfolio-meta-date">
                                    <?php echo get_the_date("F j, Y"); ?>               
                                </div>
                            </div>
                            <div class="sight-portfolio-entry__caption"><p><?php echo get_the_excerpt(); ?></p></div>
                        </div>
                    </div>
                </article>
            <?php
            endwhile; 
            wp_reset_postdata(); 
        else: ?>
            <div><p>No post found</p></div>
        <?php 
        endif;    
    endif;
    $post_html = ob_get_contents();
    ob_end_clean();
    echo json_encode(array(  
        'max_pages' => ceil($totalpost/$posts_per_page),
        'page' => $page,
        'total_post'=> $totalpost,
        'posts_per_page' => $posts_per_page,
        'content' => $post_html,
    ));
    exit;
}

/**
 * Fire AJAX action for both logged in and non-logged in users (Load more button)
 */
add_action('wp_ajax_get_more_course', 'csco_loadmore_course_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_get_more_course', 'csco_loadmore_course_ajax_handler'); // wp_ajax_nopriv_{action}

function csco_loadmore_course_ajax_handler(){
    $posts_per_page = (isset($_POST['post_per_page'])) ? $_POST['post_per_page'] : 6;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;
    $pagedata = $posts_per_page * $page - $posts_per_page;
    $category_id = (isset($_POST['category_id'])) ? $_POST['category_id'] : "";
    $default_image = get_field('default_image', 'options');
    $taxonomy = "pmpro_course_category";

    $sub_categorie_arr = get_categories(array(
                        'orderby' => 'name',
                        'order'   => 'ASC',
                        'parent'  => $category_id,
                        'fields' => 'ids' 
                    ));
    // get total post if sub category exists
    if(!empty($sub_categorie_arr)){
        $total_post_args = array(
                    'post_type' => 'pmpro_course',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                                'taxonomy' => $taxonomy,
                                'terms' => $sub_categorie_arr
                            ),
                        )
                );
        $the_query = new WP_Query($total_post_args);
        $totalpost = $the_query->found_posts;
    }
    // get total post of parent if sub catgory does not exists
    else{   
        $total_post_args = array(
                    'post_type' => 'pmpro_course',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => $taxonomy,
                            'terms' => $category_id
                        ),
                    ) 
                );
        $the_query = new WP_Query($total_post_args);
        $totalpost = $the_query->found_posts;
    }
    
    $args = array(
            'taxonomy'     => $taxonomy,
            'child_of'     => 0,
            'parent'       => $category_id,
            'orderby' => 'name',
            'order'   => 'ASC', 
            'hide_empty'   => false,
    );
    $sub_cats = get_categories($args);

    ob_start();
    if($sub_cats && !empty($sub_categorie_arr)):
        $post_args = array(
            'post_type' => 'pmpro_course',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'terms' => $sub_categorie_arr
                ),
            ),
            'posts_per_page' => $posts_per_page,
            'offset' => $pagedata,
        );
        $arr_posts = new WP_Query($post_args);
        if($arr_posts->have_posts()):
            while($arr_posts->have_posts()): $arr_posts->the_post();
                $categories = get_the_terms(get_the_id(), $taxonomy); // get category ids
                $cat_class = "";
                if(!empty($categories)){
                    foreach ($categories as $cat) {
                        if(in_array($cat->term_id, $sub_categorie_arr)){
                            $cat_class .= $cat->term_id . " "; 
                        }
                    }
                } ?>
                <article class="sight-portfolio-entry sight-portfolio-custom <?php echo $cat_class; ?>">
                    <div class="sight-portfolio-entry-outer">
                        <?php 
                            if(has_post_thumbnail(get_the_id())):
                                $thumb_img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'medium'); ?>
                            <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                    <img width="1160" height="1741" src="<?php echo $thumb_img[0]; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                    <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                </figure>
                            </div>
                        <?php
                            else:
                                if(!empty($default_image)): ?>
                                    <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                        <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                            <img width="1160" height="1741" src="<?php echo $default_image['sizes']['custom-img-size']; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                            <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                        </figure>
                                    </div>
                                <?php endif; 
                            endif;
                        ?>
                        <div class="sight-portfolio-entry__inner sight-portfolio-entry__content">                       
                            <div class="sight-portfolio-entry__title">
                                <h3 class="sight-portfolio-entry__heading">
                                    <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                                </h3>
                            </div>
                            <div class="sight-portfolio-entry__meta">
                                <?php 
                                $post_categories = get_the_terms(get_the_id(), $taxonomy);
                                if(!empty($post_categories)): ?>
                                <div class="sight-portfolio-meta-category">
                                    <?php foreach ($post_categories as $post_category): ?>
                                    <a href="<?php echo get_term_link($post_category->term_id); ?>" rel="category tag"><span class="sight-portfolio-meta-item"><?php echo $post_category->name; ?></span></a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <div class="sight-portfolio-meta-date">
                                    <?php echo get_the_date("F j, Y"); ?>               
                                </div>
                            </div>
                            <div class="sight-portfolio-entry__caption"><p><?php echo get_the_excerpt(); ?></p></div>
                        </div>
                    </div>
                </article>
            <?php
            endwhile; 
            wp_reset_postdata();
        endif;
    else: 
        $post_args = array(
            'post_type' => 'pmpro_course',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'terms' => $category_id
                ),
            ),
            'posts_per_page' => $posts_per_page,
            'offset' => $pagedata,
        );
        $arr_posts = new WP_Query($post_args);
        if($arr_posts->have_posts()):
            while($arr_posts->have_posts()): $arr_posts->the_post(); ?>
                <article class="sight-portfolio-entry sight-portfolio-custom <?php echo $category_id; ?>">
                    <div class="sight-portfolio-entry-outer">
                        <?php 
                            if(has_post_thumbnail(get_the_id())):
                                $thumb_img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'medium'); ?>
                            <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                    <img width="1160" height="1741" src="<?php echo $thumb_img[0]; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                    <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                </figure>
                            </div>
                        <?php
                            else:
                                if(!empty($default_image)): ?>
                                    <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                        <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                            <img width="1160" height="1741" src="<?php echo $default_image['sizes']['custom-img-size']; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                            <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                        </figure>
                                    </div>
                                <?php endif; 
                            endif;
                        ?>
                        <div class="sight-portfolio-entry__inner sight-portfolio-entry__content">                       
                            <div class="sight-portfolio-entry__title">
                                <h3 class="sight-portfolio-entry__heading">
                                    <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                                </h3>
                            </div>
                            <div class="sight-portfolio-entry__meta">
                                <?php 
                                $post_categories = get_the_terms(get_the_id(), $taxonomy);
                                if(!empty($post_categories)): ?>
                                <div class="sight-portfolio-meta-category">
                                    <?php foreach ($post_categories as $post_category): ?>
                                    <a href="<?php echo get_term_link($post_category->term_id); ?>" rel="category tag"><span class="sight-portfolio-meta-item"><?php echo $post_category->name; ?></span></a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <div class="sight-portfolio-meta-date">
                                    <?php echo get_the_date("F j, Y"); ?>               
                                </div>
                            </div>
                            <div class="sight-portfolio-entry__caption"><p><?php echo get_the_excerpt(); ?></p></div>
                        </div>
                    </div>
                </article>
            <?php
            endwhile; 
            wp_reset_postdata(); 
        else: ?>
            <div><p>No post found</p></div>
        <?php 
        endif;    
    endif;
    $post_html = ob_get_contents();
    ob_end_clean();
    echo json_encode(array(  
        'max_pages' => ceil($totalpost/$posts_per_page),
        'page' => $page,
        'total_post'=> $totalpost,
        'posts_per_page' => $posts_per_page,
        'content' => $post_html,
    ));
    exit;
}

/**
 * Fire AJAX action for both logged in and non-logged in users (Load more button)
 */
add_action('wp_ajax_get_more_affiliates_course', 'csco_loadmore_affiliates_course_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_get_more_affiliates_course', 'csco_loadmore_affiliates_course_ajax_handler'); // wp_ajax_nopriv_{action}

function csco_loadmore_affiliates_course_ajax_handler(){
    $posts_per_page = (isset($_POST['post_per_page'])) ? $_POST['post_per_page'] : 6;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;
    $pagedata = $posts_per_page * $page - $posts_per_page;
    $category_id = (isset($_POST['category_id'])) ? $_POST['category_id'] : "";
    $default_image = get_field('default_image', 'options');
    $taxonomy = "pmpro_course_category";

    $sub_categorie_arr = get_categories(array(
                        'orderby' => 'name',
                        'order'   => 'ASC',
                        'parent'  => $category_id,
                        'fields' => 'ids' 
                    ));
    // get total post if sub category exists
    if(!empty($sub_categorie_arr)){
        $total_post_args = array(
                    'post_type' => 'pmpro_course',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                                'taxonomy' => $taxonomy,
                                'terms' => $sub_categorie_arr
                            ),
                        )
                );
        $the_query = new WP_Query($total_post_args);
        $totalpost = $the_query->found_posts;
    }
    // get total post of parent if sub catgory does not exists
    else{   
        $total_post_args = array(
                    'post_type' => 'pmpro_course',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => $taxonomy,
                            'terms' => $category_id
                        ),
                    ) 
                );
        $the_query = new WP_Query($total_post_args);
        $totalpost = $the_query->found_posts;
    }
    
    $args = array(
            'taxonomy'     => $taxonomy,
            'child_of'     => 0,
            'parent'       => $category_id,
            'orderby' => 'name',
            'order'   => 'ASC', 
            'hide_empty'   => false,
    );
    $sub_cats = get_categories($args);

    ob_start();
    if($sub_cats && !empty($sub_categorie_arr)):
        $post_args = array(
            'post_type' => 'pmpro_course',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'terms' => $sub_categorie_arr
                ),
            ),
            'posts_per_page' => $posts_per_page,
            'offset' => $pagedata,
        );
        $arr_posts = new WP_Query($post_args);
        if($arr_posts->have_posts()):
            while($arr_posts->have_posts()): $arr_posts->the_post();
                $categories = get_the_terms(get_the_id(), $taxonomy); // get category ids
                $cat_class = "";
                if(!empty($categories)){
                    foreach ($categories as $cat) {
                        if(in_array($cat->term_id, $sub_categorie_arr)){
                            $cat_class .= $cat->term_id . " "; 
                        }
                    }
                } ?>
                <article class="sight-portfolio-entry sight-portfolio-custom <?php echo $cat_class; ?>">
                    <div class="sight-portfolio-entry-outer">
                        <?php 
                            if(has_post_thumbnail(get_the_id())):
                                $thumb_img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'medium'); ?>
                            <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                    <img width="1160" height="1741" src="<?php echo $thumb_img[0]; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                    <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                </figure>
                            </div>
                        <?php
                            else:
                                if(!empty($default_image)): ?>
                                    <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                        <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                            <img width="1160" height="1741" src="<?php echo $default_image['sizes']['custom-img-size']; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                            <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                        </figure>
                                    </div>
                                <?php endif; 
                            endif;
                        ?>
                        <div class="sight-portfolio-entry__inner sight-portfolio-entry__content">                       
                            <div class="sight-portfolio-entry__title">
                                <h3 class="sight-portfolio-entry__heading">
                                    <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                                </h3>
                            </div>
                            <div class="sight-portfolio-entry__meta">
                                <?php 
                                $post_categories = get_the_terms(get_the_id(), $taxonomy);
                                if(!empty($post_categories)): ?>
                                <div class="sight-portfolio-meta-category">
                                    <?php foreach ($post_categories as $post_category): ?>
                                    <a href="<?php echo get_term_link($post_category->term_id); ?>" rel="category tag"><span class="sight-portfolio-meta-item"><?php echo $post_category->name; ?></span></a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <div class="sight-portfolio-meta-date">
                                    <?php echo get_the_date("F j, Y"); ?>               
                                </div>
                            </div>
                            <div class="sight-portfolio-entry__caption"><p><?php echo get_the_excerpt(); ?></p></div>
                        </div>
                    </div>
                </article>
            <?php
            endwhile; 
            wp_reset_postdata();
        endif;
    else: 
        $post_args = array(
            'post_type' => 'pmpro_course',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'terms' => $category_id
                ),
            ),
            'posts_per_page' => $posts_per_page,
            'offset' => $pagedata,
        );
        $arr_posts = new WP_Query($post_args);
        if($arr_posts->have_posts()):
            while($arr_posts->have_posts()): $arr_posts->the_post(); ?>
                <article class="sight-portfolio-entry sight-portfolio-custom <?php echo $category_id; ?>">
                    <div class="sight-portfolio-entry-outer">
                        <?php 
                            if(has_post_thumbnail(get_the_id())):
                                $thumb_img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'medium'); ?>
                            <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                    <img width="1160" height="1741" src="<?php echo $thumb_img[0]; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                    <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                </figure>
                            </div>
                        <?php
                            else:
                                if(!empty($default_image)): ?>
                                    <div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
                                        <figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
                                            <img width="1160" height="1741" src="<?php echo $default_image['sizes']['custom-img-size']; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                                            <a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>  
                                        </figure>
                                    </div>
                                <?php endif; 
                            endif;
                        ?>
                        <div class="sight-portfolio-entry__inner sight-portfolio-entry__content">                       
                            <div class="sight-portfolio-entry__title">
                                <h3 class="sight-portfolio-entry__heading">
                                    <a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                                </h3>
                            </div>
                            <div class="sight-portfolio-entry__meta">
                                <?php 
                                $post_categories = get_the_terms(get_the_id(), $taxonomy);
                                if(!empty($post_categories)): ?>
                                <div class="sight-portfolio-meta-category">
                                    <?php foreach ($post_categories as $post_category): ?>
                                    <a href="<?php echo get_term_link($post_category->term_id); ?>" rel="category tag"><span class="sight-portfolio-meta-item"><?php echo $post_category->name; ?></span></a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <div class="sight-portfolio-meta-date">
                                    <?php echo get_the_date("F j, Y"); ?>               
                                </div>
                            </div>
                            <div class="sight-portfolio-entry__caption"><p><?php echo get_the_excerpt(); ?></p></div>
                        </div>
                    </div>
                </article>
            <?php
            endwhile; 
            wp_reset_postdata(); 
        else: ?>
            <p>No post found</p>
        <?php 
        endif;    
    endif;
    
    $post_html = ob_get_contents();
    ob_end_clean();
    echo json_encode(array(  
        'max_pages' => ceil($totalpost/$posts_per_page),
        'page' => $page,
        'total_post'=> $totalpost,
        'posts_per_page' => $posts_per_page,
        'content' => $post_html,
    ));
    exit;
}

/**
 * WooCommerce support in theme
 */
function csco_wc_setup(){
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'csco_wc_setup', 100);

// Load FAQ post on click of load more button or category or searach
function csco_load_faq_post_action_callback(){
    global $wpdb;
    $size = (isset($_POST['size']) && $_POST['size'] != "") ? $_POST['size'] : 0;
    $paged = $_REQUEST['page'];
    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 6;
    $faq_search = (isset($_POST["faq_search"])) ? $_POST["faq_search"] : "";
    $search_keyword = (isset($_POST["search_keyword"])) ? $_POST["search_keyword"] : "";
    if(isset($_POST['category_post']) && $_POST['category_post'] != "" && $size == 0){
        $offset = '';
    }
    else{ 
        $offset = (($paged * $ppp) - $ppp);    
    }
    if($size != 0){
        if($faq_search && $faq_search !== "" && $search_keyword !== ""){
            $query = array(
                'post_type'         => 'faq',
                'posts_per_page'    =>  $ppp,
                'paged'             => $paged,
                'post_status'       => 'publish',
                's' => $search_keyword,
            );
            $size = explode(',', $size);
            $term_size = array(
                array(
                    'taxonomy'  => 'faq_cat',
                    'field'     => 'term_id',
                    'terms'     => $size,
                ),
            );
            $query['tax_query'] = $term_size;
        }
        else{
            $query = array(
                'post_type'         => 'faq',
                'posts_per_page'    =>  $ppp,
                'paged'             => $paged,
                'post_status'       => 'publish',
            );
            $size = explode(',', $size);
            $term_size = array(
                array(
                    'taxonomy'  => 'faq_cat',
                    'field'     => 'term_id',
                    'terms'     => $size,
                ),
            );
            $query['tax_query'] = $term_size;
        }
        $category_status = 1;
    }
    else if($faq_search && $faq_search !== "" && $search_keyword !== ""){
        $query = array(
            'post_type'         => 'faq',
            'posts_per_page'    => $ppp,
            'offset'            => $offset, 
            'paged'             => $paged,
            'post_status'       => 'publish',
            's' => $search_keyword,
        );
    }
    else{
        $query = array(
            'post_type'         => 'faq',
            'posts_per_page'    => $ppp,
            'offset'            => $offset, 
            'paged'             => $paged,
            'post_status'       => 'publish',
        );
    }

    // FAQ tag search list
    if($faq_search == "faq_onkeyup_search"): 
        $tag_tax = 'faq_tag';
        $tag_terms = get_terms([
            'taxonomy' => $tag_tax,
            'hide_empty' => false,
        ]);
        $tag_count = count($tag_terms);
    endif;    

    global $post;
    $query_list = new WP_Query($query); // run query
    $totalpost = $query_list->found_posts; // get total post
    
    ob_start(); 
    if($query_list->have_posts()):
            if($faq_search == "faq_onkeyup_search"):
                if($tag_count > 0): 
                    foreach($tag_terms as $term):
                        if(preg_match("/{$search_keyword}/i", $term->name)): ?>
                            <p class="search_result"><?php echo $term->name; ?></p>
                        <?php 
                        endif;
                    endforeach;   
                endif;
            else: 
                while($query_list->have_posts()): 
                    $query_list->the_post();
                    if(has_post_thumbnail(get_the_id())){
                        $post_featured_image = wp_get_attachment_url(get_post_thumbnail_id(get_the_id()), 'thumbnail');
                    }
                    else{
                        $post_featured_image = get_field('default_image', 'options')['url']; 
                    }
                    $content = get_the_content();
                    $contentText = substr($content, 0, 300); ?>
                <div class="category-col">
                    <div class="category-col-img">
                        <a href="<?php echo get_the_permalink(); ?>">
                            <div class="jarallax-keep-img">
                                <img width="1160" height="1741" src="<?php echo $post_featured_image; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
                            </div>
                        </a>
                    </div>
                    <div class="category-col-text">
                            <h3 class=""><?php echo get_the_title(); ?></h3>
                            <p><?php echo $contentText; ?></p>
                            <a href="<?php echo get_the_permalink(); ?>" class="btn-custom">Read More</a>
                    </div>
                </div>
            <?php 
                endwhile;
            endif;
        wp_reset_postdata();
        if($category_status == 1 && $paged > 1){
            $status = 2;
        }
        else{
            $status = 1;
        }
    else:
        $status = 0; ?>
        <?php if($faq_search == "faq_onkeyup_search"):
        else: ?>
        <p class="content_not_found">No FAQ Found</p>
        <?php endif; ?>
    <?php 
    endif;
    $output_string = ob_get_contents();
    ob_end_clean();
    echo json_encode(
        array("status" => $status,
              'ppp' => $ppp, 
              'content' => $output_string,
              'max_pages' => ceil($totalpost/$ppp),
              'page'=> $paged + 1,
        ));
    exit();
}

add_action('wp_ajax_size_ajax_action', 'csco_load_faq_post_action_callback');
add_action('wp_ajax_nopriv_size_ajax_action', 'csco_load_faq_post_action_callback');