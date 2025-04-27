<!-- homepage category section start -->
<?php
$args = array(
	'post_type' => 'post',
    'taxonomy'     => 'category',
    'hide_empty'   => 0,
    'orderby' => 'name',
	'order'   => 'ASC',
	'parent'  => 0,
);
$parent_categories = get_categories($args); // get list of parent category
$number_of_category = !empty(get_field('number_of_category')) ? get_field('number_of_category') : 3;
$category_title = get_field('category_title');
$default_image = get_field('default_image', 'options');
$parallax_effect = get_field('show_parallax_effect'); // Parallax effect
$class = ($parallax_effect == true) ? "jarallax-keep-img" : "";
if(!empty($parent_categories)): ?>
<section class="post-category-section">
	<div class="container">
	    <div class="row">
        <?php
        	if(!empty($category_title)): ?>
        		<h2><?php echo $category_title; ?></h2>
        	<?php endif; ?>	
			<div class="slider-category">
            <?php
        	$count = 0;
            foreach($parent_categories as $cat): 
				$image_id = get_term_meta($cat->term_id, 'powerkit_featured_image', true);
				$post_thumbnail_img = wp_get_attachment_image_src($image_id, 'thumbnail');
				if($count == $number_of_category):
					break;
				endif; ?>
				<div class="col-md-12">
					<?php
					if(!empty($post_thumbnail_img[0])){ ?>
						<div class="slider-category-img">
    						<a class="<?php echo $class; ?>" data-speed="0.7" href="<?php echo get_category_link($cat->term_id); ?>"><img class="jarallax-img" src="<?php echo $post_thumbnail_img[0]; ?>" alt="<?php echo $cat->name; ?>"  width="100%" height="auto" /></a>
						</div>
					<?php }else{ ?>
						<div class="slider-category-img">
    						<a class="<?php echo $class; ?>" data-speed="0.7" href="<?php echo get_category_link($cat->term_id); ?>"><img class="jarallax-img" src="<?php echo $default_image['url']; ?>" alt="<?php echo $cat->name; ?>"  width="100%" height="auto" /></a>
						</div>
					<?php } ?>
					<a class="category_title-link" href="<?php echo get_category_link($cat->term_id); ?>"><h3><?php echo $cat->name; ?></h3></a>
				</div>
			<?php 
			$count++;
			endforeach; ?>
			</div>
	    </div>
	</div>
</section>
<?php endif; ?>
<!-- homepage category section end -->