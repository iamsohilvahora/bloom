<?php get_header(); ?>
<div id="primary" class="">
	<main id="main" class="site-main" role="main">
		<?php
		$category = get_queried_object(); 
		$category_id = $category->term_id; // get current category id
		$image_id = get_term_meta($category_id, 'csco_category_thumbnail', true);
		$category_banner = wp_get_attachment_url($image_id);
		$category_title = get_cat_name($category_id);
		$category_desc = category_description($category_id);
		$default_image = get_field('default_image', 'options'); ?>
		<div class="sight-block-portfolio sight-block-portfolio-id sight-block-portfolio-layout-justified">
			<div class="sight-portfolio-area">
				<?php
				$posts_per_page = 5;
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
				// get parent category list only
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
				if($sub_cats): ?>
				<div class="sight-portfolio-area-filter" id="myBtnContainer">
					<div class="sight-portfolio-area-filter__title">Categories</div>
					<ul class="sight-portfolio-area-filter__ul_list">
						<li class="sight-portfolio-area-filter__list-item sight-category-list sight-filter-all sight-filter-active" data-id="<?php echo $category_id; ?>">
							<a href="#" class="btn active">All</a>
						</li>
	                	<?php 
	                	foreach($sub_cats as $sub_category): ?>
	                		<li class="sight-portfolio-area-filter__list-item sight-category-list" data-id="<?php echo $sub_category->term_id; ?>">
								<a href="#" class="btn"><?php echo $sub_category->name; ?></a>
							</li>
	                	<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
				<div class="sight-portfolio-area__outer">
					<div class="sight-portfolio-area__main">
					<?php
					if($sub_cats && !empty($sub_categorie_arr)):
	            		$post_args = array(
	            		    'post_type' => 'post',
	            		    'post_status' => 'publish',
	            		    'order' => 'DESC',
	            		    'category__in' => $sub_categorie_arr,
	            		    'posts_per_page' => $posts_per_page,
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
													<span class="sight-portfolio-view-more"> 
														<span class="sight-portfolio-view-more-label">View Details</span>
													</span>
													<a class="sight-portfolio-overlay-link" href="<?php echo get_the_permalink(); ?>"></a>		
												</figure>
											</div>
										<?php
										    else:
										    	if(!empty($default_image)): ?>
										        	<div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay">
														<figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
															<img width="1160" height="1741" src="<?php echo $default_image['sizes']['custom-img-size']; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
															<span class="sight-portfolio-view-more"> 
																<span class="sight-portfolio-view-more-label">View Details</span>
															</span>
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
							wp_reset_postdata(); ?>
						<?php 
						endif;
					else: 
		        		$post_args = array(
		        		    'post_type' => 'post',
		        		    'post_status' => 'publish',
		        		    'cat' => $category_id, 
		        		    'posts_per_page' => $posts_per_page,
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
										        	<div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay ">
														<figure class="sight-portfolio-overlay-background jarallax-keep-img" data-speed="0.7">
															<img width="1160" height="1741" src="<?php echo $default_image['sizes']['custom-img-size']; ?>" class="attachment-large size-large jarallax-img" alt="" decoding="async" loading="lazy">
															<span class="sight-portfolio-view-more"> 
																<span class="sight-portfolio-view-more-label">View Details</span>
															</span>
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
					endif; ?>
					</div>
				</div>
				<?php if($totalpost != "" && $totalpost > $posts_per_page && $posts_per_page > 0): ?>
				<div class="sight-portfolio-area__pagination">
					<button class="sight-portfolio-load-more-btn load-btn" data-total-post="5" data-category-id="<?php echo $category_id; ?>">Load More</button>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</main>
</div>

<style>		
	.layout-sidebar .main-content {
		display: block;	
	}
	.sight-block-portfolio-id {
		--sight-portfolio-area-grid-image-height: 300px;
	}
	@media (max-width: 1019px) {
		.sight-block-portfolio-id {
			--sight-portfolio-area-grid-image-height: 250px;
		}
	}
	@media (max-width: 599px) {
		.sight-block-portfolio-id {
			--sight-portfolio-area-grid-image-height: 200px;
		}
	}
	.sight-block-portfolio-id {
		--sight-portfolio-area-grid-gap: 40px;
	}
	@media (max-width: 1019px) {
		.sight-block-portfolio-id {
			--sight-portfolio-area-grid-gap: 40px;
		}
	}
	@media (max-width: 599px) {
		.sight-block-portfolio-id {
			--sight-portfolio-area-grid-gap: 40px;
		}
	}
	@media (min-width: 600px){
		.sight-portfolio-area-filter__ul_list {
			margin-top: 0 !important;
		}
		.sight-portfolio-area-filter__ul_list {
			display: flex;
			flex-wrap: wrap;
			margin: 0 0 0 !important;
			padding: 0 !important;
		}
	}
	 .sight-portfolio-area-filter__list {
	    margin-top: 0 !important;
	}
	.sight-portfolio-area-filter__list {
	    display: flex;
	    flex-wrap: wrap;
	    margin: 16px 0 0 !important;
	    padding: 0 !important;
	}
	.sight-portfolio-area__pagination .sight-portfolio-load-more-btn {
	    font-family: Poppins;
	    font-size: 0.75rem;
	    font-weight: 700;
	    letter-spacing: 0.1125em;
	    text-transform: uppercase;
	    color: #fff;
	}
	.sight-portfolio-load-more-btn {
	    background-color: #f73832;
	}
	.sight-portfolio-area__pagination .sight-portfolio-load-more-btn:hover, .sight-portfolio-area__pagination .sight-portfolio-load-more-btn:focus {
	    background-color: #000000;
	}
	.sight-portfolio-entry .sight-portfolio-entry__caption p {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
	}
	.sight-block-portfolio-layout-justified .sight-portfolio-area__main {
		gap:20px;
		padding: 20px;
		
	}
	.sight-block-portfolio-layout-justified .sight-portfolio-area__main .sight-portfolio-custom {
		margin: 0;
		flex: 0 0 auto;
	}
	.sight-portfolio-area__main .sight-portfolio-custom {
		width:31.6%;
	}
	.sight-portfolio-area__main .sight-portfolio-custom:nth-child(1), 
	.sight-portfolio-area__main .sight-portfolio-custom:nth-child(13) {
		width:60%;
	}
	.sight-portfolio-area__main .sight-portfolio-custom:nth-child(2),
	.sight-portfolio-area__main .sight-portfolio-custom:nth-child(14) {
		width:37.5%;
	}
	.sight-portfolio-area__main .sight-portfolio-custom:nth-child(6),.sight-portfolio-area__main .sight-portfolio-custom:nth-child(7),
	.sight-portfolio-area__main .sight-portfolio-custom:nth-child(11),.sight-portfolio-area__main .sight-portfolio-custom:nth-child(12){
		width:48.7%;
	}
	.sight-portfolio-area__main .sight-portfolio-custom:nth-child(8) {
		width:100%;
	}
	.sight-portfolio-area__main .sight-portfolio-custom:nth-child(9) {
		width:37.5%;
	}
	.sight-portfolio-area__main .sight-portfolio-custom:nth-child(10) {
		width:60%;
	}
	.category-custom-block .sight-portfolio-entry__caption p {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 4;
		-webkit-box-orient: vertical;
	}
	.powerkit_widget_posts .thumbnail-rounded .post-outer .post-inner:first-child {
		flex: 0 0 90px;
		width: 90px;
		max-width: 90px;
	} 
	.sight-portfolio-area__pagination .sight-portfolio-load-more-btn {
		border-radius: 50px;
	}
	@media (max-width: 1024px){
		.sight-portfolio-area__main .sight-portfolio-custom {
			width: 31.3%;
		}
		.sight-portfolio-area__main .sight-portfolio-custom:nth-child(2), 
		.sight-portfolio-area__main .sight-portfolio-custom:nth-child(14) {
			width: 37%;
		}
		.sight-portfolio-area__main .sight-portfolio-custom:nth-child(6), 
		.sight-portfolio-area__main .sight-portfolio-custom:nth-child(7), 
		.sight-portfolio-area__main .sight-portfolio-custom:nth-child(11), 
		.sight-portfolio-area__main .sight-portfolio-custom:nth-child(12) {
			width: 48.5%;
		}
		.sight-portfolio-area__main .sight-portfolio-custom:nth-child(9) {
			width: 37%;
		}
	}
	@media (max-width:767px){ 
		.sight-portfolio-area__main .sight-portfolio-custom {
			width: 48% !important;
		}
	}
	@media (max-width:575px){ 
		.sight-portfolio-area__main .sight-portfolio-custom {
			width: 100% !important;
		}
	}
</style>

<script>
	// Add active class to the current button (highlight it)
	var btnContainer = document.getElementById("myBtnContainer");
	var btns = btnContainer.getElementsByClassName("btn");
	for (var i = 0; i < btns.length; i++) {
	  btns[i].addEventListener("click", function(){
	    var current = document.getElementsByClassName("active");
	    // add and remove parent list active class
	    const list = current[0].closest("li");
		list.classList.remove("sight-filter-active");
	    this.closest("li").className += " sight-filter-active";
	    // add and remove active class from button
	    current[0].className = current[0].className.replace(" active", "");
	    this.className += " active"; 
	  });
	}
</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>