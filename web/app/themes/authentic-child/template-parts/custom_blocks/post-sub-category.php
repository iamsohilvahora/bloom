<!-- Post sub category block section start -->
<?php 
$post_category_blocks = get_fields(); 
$category_title = $post_category_blocks['post_sub_category_title'];
$category_image = $post_category_blocks['post_sub_category_banner_image']['url'];
$category_desc = $post_category_blocks['post_sub_category_description']; 
$default_image = get_field('default_image', 'options'); ?>
<section class="post-category-section">
	<?php if(!empty($category_image)): ?>
    <div class="category-banner" style="background-image: url('<?php if(!empty($category_image)){ echo $category_image; } ?>');">
		<?php if(!empty($category_title)): ?>
        <div class="category-title">
            <h1 class="h1"><?php echo $category_title; ?></h1>
        </div>
		<?php endif; ?>
    </div>
	<?php endif;
	if(!empty($category_desc)): ?>
	<div class="category-description">
		<p class="category-text"><?php echo $category_desc; ?></p>
	</div>
	<?php endif; ?>

	<div class="container">
	    <div class="row">
	        <?php
	        	$taxonomy = "category";
				$args = array(
					'post_type' => 'post',
				    'taxonomy'     => $taxonomy,
				    'hide_empty'   => 0,
				    'orderby' => 'name',
					'order'   => 'ASC',
					'parent'  => 0,
				);
	            $parent_categories = get_categories($args);
	            if(!empty($parent_categories)):
		            foreach($parent_categories as $cat): 
						$category_id = $cat->term_id;
						$args2 = array(
						        'taxonomy'     => $taxonomy,
						        'child_of'     => 0,
						        'parent'       => $category_id,
						        'orderby' => 'name',
								'order'   => 'ASC', 
						        'hide_empty'   => false,
						);
						$sub_cats = get_categories($args2);
						if($sub_cats):
                        	foreach($sub_cats as $sub_category): ?>
                        		<div id="sub-cat-<?php echo $sub_category->term_id; ?>"><?php echo $sub_category->name; ?></div>
                        	<?php endforeach;
						endif;                        	
						?>
					<?php 
					endforeach;
				endif; ?>
	    </div>
	</div>

	<?php if(!empty($parent_categories)): ?>
	<div>	
        <?php foreach($parent_categories as $cat): 
			$category_id = $cat->term_id;
			$args2 = array(
			        'taxonomy'     => $taxonomy,
			        'child_of'     => 0,
			        'parent'       => $category_id,
			        'orderby' => 'name',
					'order'   => 'ASC', 
			        'hide_empty'   => false,
			);
			$sub_cats = get_categories($args2);
			if($sub_cats):
            	foreach($sub_cats as $sub_category): 
            		$post_args = array(
            		    'post_type' => 'post',
            		    'post_status' => 'publish',
            		    'cat' => $sub_category->term_id, 
            		    'posts_per_page' => -1,
            		);
					$arr_posts = new WP_Query($post_args);
					if($arr_posts->have_posts()): ?>
						<div id="sub-cat-<?php echo $sub_category->term_id; ?>">
					    <?php while($arr_posts->have_posts()): $arr_posts->the_post(); ?>
					            <div>
					                <?php 
					                    if(has_post_thumbnail(get_the_id())):
					                        $thumb_img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'medium'); ?>
					                    <img src="<?php echo $thumb_img[0]; ?>" alt="<?php echo get_the_title(); ?>" />
					                <?php
					                    else:
					                    	if(!empty($default_image)): ?>	
					                        	<img src="<?php echo $default_image['url']; ?>" alt="<?php echo $default_image['alt']; ?>" width="200" height="200" />
					                        <?php endif; 
					                    endif;  
					                ?>  
					                <div class="post-title">
					                    <h1><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h1>
					                </div>
					                <div class="post-content">
					                    <?php echo get_the_excerpt(); ?>
					                    <a href="<?php echo get_the_permalink(); ?>">Read More</a>
					                </div>
					            </div>
					            <?php
					    endwhile; 
					    wp_reset_postdata(); ?>
						</div>
					<?php endif; ?>
            	<?php endforeach;
			endif;
			?>
		<?php 
		endforeach;
	?>
	</div>
	<?php endif; ?>
</section>
<!-- Post sub category block section end -->