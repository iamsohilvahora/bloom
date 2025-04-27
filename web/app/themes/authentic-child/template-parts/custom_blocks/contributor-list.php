<!-- contributor list section start -->
<?php 
$parallax_effect = get_field('show_parallax_effect'); // Parallax effect
$class = ($parallax_effect == true) ? "jarallax-keep-img" : "";
$default_image = get_field('default_image', 'options');
$args = array(
    'post_type' => 'post',
    'taxonomy'     => 'category',
    'hide_empty'   => 0,
    'orderby' => 'name',
    'order'   => 'ASC',
    'parent'  => 0,
);
$parent_categories = get_categories($args); // get list of parent category
$contributor_args = array(
    'role' => 'contributor',
    'orderby' => 'user_nicename',
    'order' => 'ASC'
);
$contributors = get_users($contributor_args); // get users (contributor only)
$selected_category_arr = []; // array of contributor's selected category    
foreach($contributors as $user){
    $selected_cat = get_field('select_post_category', 'user_' . $user->ID);
    if(!empty($selected_cat)){
        if(!in_array($selected_cat, $selected_category_arr)){
            $selected_category_arr[] = $selected_cat; 
        }
    }
}
if(!empty($parent_categories)): ?>
<section class="author-section">
    <?php 
    foreach($parent_categories as $category):
        $category_id = $category->term_id;
        if(in_array($category_id, $selected_category_arr)){
            $category_name = $category->name;
        }
        else{
            continue;
        } ?>
    <div class="container">
        <div class="custom-row">
            <?php if(!empty($category_name)): ?>
            <h2><?php echo $category_name; ?></h2>
            <?php endif;
            if(!empty($contributors)): ?>
            <div class="author-block-main">
                <?php 
                foreach($contributors as $user): 
                    $contributor_id = $user->ID;
                    $contributor_name = get_field('contributor_name', 'user_' . $contributor_id );
                    $designation = get_field('designation', 'user_' . $contributor_id);
                    $contributor_image = get_field('image', 'user_' . $contributor_id);
                    $author_url = get_author_posts_url($contributor_id);
                    $selected_category = get_field('select_post_category', 'user_' . $contributor_id);
                    if($selected_category == $category_id): ?>
                    <div class="author-block">
                        <div class="contributor-image">
                            <?php 
                                if(!empty($contributor_image)): ?>
                                    <a href="<?php echo $author_url; ?>" class="<?php echo $class; ?>" data-speed="0.7"><img src="<?php echo $contributor_image['url']; ?>" class="jarallax-img" alt="<?php echo $contributor_name; ?>" width="200" height="200" /></a>
                            <?php
                                else:
                                    if(!empty($default_image)): ?>  
                                        <a href="<?php echo $author_url; ?>" class="<?php echo $class; ?>" data-speed="0.7"><img src="<?php echo $default_image['url']; ?>" class="jarallax-img" alt="<?php echo $default_image['alt']; ?>" width="200" height="200" /></a>
                                    <?php endif; 
                                endif;  
                            ?>  
                        </div>
                        <div class="contributor-title">
                            <?php if(!empty($contributor_name)): ?>
                                <h3><a href="<?php echo $author_url; ?>"><?php echo $contributor_name; ?></a></h3>
                            <?php 
                            else: ?>
                                <h3><a href="<?php echo $author_url; ?>"><?php echo get_user_by('id', $contributor_id)->display_name; ?></a></h3>
                            <?php endif;
                            if(!empty($designation)): ?>
                                <p><?php echo $designation; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php 
                    endif;
                endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</section>
 <?php endif; ?>
<!-- contributor list section end -->