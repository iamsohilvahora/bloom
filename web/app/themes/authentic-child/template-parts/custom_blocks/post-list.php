<!-- Category page - post list (Bloom's top picks) section start -->
<?php
	$category_blocks = get_fields(); // get all fields in array
	$default_image = get_field('default_image', 'options'); // get default post image
    $blooms_top_picks_section_title = $category_blocks['blooms_top_picks_section_title'];
    $display_option = $category_blocks['select_display_option'];
	$post_orientation = $category_blocks['post_orientation'];
	if($post_orientation == "landscape"){
		$orientation_class = "landscape";
	}
	else if($post_orientation == "portrait"){
		$orientation_class = "portrait";
	} 
    $parallax_effect = get_field('show_parallax_effect'); // Parallax effect
    $class = ($parallax_effect == true) ? "jarallax-keep-img" : ""; ?>
<section class="contributor-section  <?php echo $orientation_class; ?>">
    <div class="container">
        <div class="row custom-row">
            <?php if(!empty($blooms_top_picks_section_title)): ?>
			<h2><?php echo $blooms_top_picks_section_title; ?></h2>
            <?php endif;    
            	if($display_option == "show_bloom_post"){
            		$selected_posts = $category_blocks['select_bloom_post'];
                    if(!empty($selected_posts)): ?>
            			<div class="col-md-12 contributor-row-custom">
            		    <?php 
                            foreach ($selected_posts as $bloom_post):
                                $post_id = $bloom_post->ID;
                                $post_title = $bloom_post->post_title;
                                $post_excerpt = $bloom_post->post_excerpt;
                                $post_type = $bloom_post->post_type;
                            if($post_type == "resources"){
                                $permalink = get_field('add_resource_external_url', $post_id);
                            }
                            else{
                                $permalink = get_the_permalink($post_id);
                            }
            		    	$target = ($post_type == "resources") ? "_blank" : ""; ?>
        		            <div class="contributor-box">
        		                <?php 
        		                    if(has_post_thumbnail($post_id)):
        		                        $thumb_img = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'medium'); ?>
        		                    <a href="<?php echo $permalink; ?>" target="<?php echo $target; ?>" class="<?php echo $class; ?>" data-speed="0.7"><img src="<?php echo $thumb_img[0]; ?>" alt="<?php echo $post_title; ?>" width="200" height="200" class="jarallax-img" /></a>
        		                <?php
        		                    else:
        		                    	if(!empty($default_image)): ?>	
        		                        	<a href="<?php echo $permalink; ?>" target="<?php echo $target; ?>" class="<?php echo $class; ?>" data-speed="0.7"><img src="<?php echo $default_image['url']; ?>" alt="<?php echo $default_image['alt']; ?>" width="200" height="200" class="jarallax-img" /></a>
        		                        <?php endif; 
        		                    endif; ?>
								<div class="post-contributor-content">
        		                    <?php if(!empty($post_title)): ?>  
            		                <div class="post-title">
            		                    <h3><a href="<?php echo $permalink; ?>" target="<?php echo $target; ?>"><?php echo $post_title; ?></a></h3>
            		                </div>
            		            	<?php endif;
            		                if(!empty($post_excerpt)): ?>
            		                <div class="post-content">
            		                    <?php echo $post_excerpt; ?>
            		                </div>
                                    <?php endif; ?>
								</div>
        		            </div>
        		            <?php
                        endforeach;
                        ?>
            			</div>
            		<?php else: ?>
						<p>Post not found</p>
					<?php endif; 
            	}
            	else if($display_option == "custom_data"){
            		$post_details = $category_blocks['add_custom_data'];
            		if(!empty($post_details)): ?>
                        <div class="col-md-12 contributor-row-custom">
                        <?php
            			foreach($post_details as $post):
            				$post_title = $post['post_title'];
            				$post_content = $post['post_content'];
            				$post_image = $post['post_image'];
            				$post_url = $post['post_url']; ?>
            				<div class="contributor-box">
            				        <?php 
            				            if(!empty($post_image)): ?>
            				            <a href="<?php echo $post_url; ?>" target="_blank" class="<?php echo $class; ?>" data-speed="0.7"><img src="<?php echo $post_image['url']; ?>" alt="<?php echo $post_title; ?>"  width="200" height="200" class="jarallax-img" /></a>
            				        <?php
            				            else:
            				                if(!empty($default_image)): ?>  
            				                    <a href="<?php echo $post_url; ?>" target="_blank" class="<?php echo $class; ?>" data-speed="0.7"><img src="<?php echo $default_image['url']; ?>" alt="<?php echo $default_image['alt']; ?>" width="200" height="200" class="jarallax-img" /></a>
            				                <?php endif; 
            				            endif;  
            				        ?>  
                                <div class="post-contributor-content">
            				    <?php if(!empty($post_title)): ?>
            				    <div class="post-title">
            				        <h3><a href="<?php echo $post_url; ?>" target="_blank"><?php echo $post_title; ?></a></h3>
            				    </div>
            				    <?php endif; ?>
            				    <?php if(!empty($post_content)): ?>
        		                <div class="post-content">
        		                    <?php echo $post_content; ?>
        		                </div>
        		            	<?php endif; ?>
                                </div>
            				</div>
            			<?php	
            			endforeach; ?>
                        </div>
            		<?php else: ?>
            			<p>Post not found</p>		
            		<?php endif;	
            	} ?>
           
        </div>
    </div>
</section>
<!-- Category page - post list section end -->