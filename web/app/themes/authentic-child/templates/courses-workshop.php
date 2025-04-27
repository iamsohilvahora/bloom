<?php 
/*
 * Template Name: Courses & Workshop
*/
get_header(); ?>
<div id="primary" class="">
	<main id="main" class="site-main" role="main">
		<?php
		$category_id1 = 167; // get Course & workshop id
		$taxonomy = "pmpro_course_category";
		$category_id2 = 175; // get Affiliates id 
		$section2_title = get_field('section_2_title');
		$section3_title = get_field('section_3_title');
		$default_image = get_field('default_image', 'options'); 
		$events = tribe_get_events([ 'posts_per_page' => -1, 'start_date' => 'now' ] ); // get all events ?>

		<!-- Section-1 start -->
		<div class="workshop-banner">	
			<div class="workshop-banner-inner">
				<div class="workshop-slider-banner">
				<!-- start loop through event  -->
				<?php 
					foreach($events as $event): 
						$event_id = $event->ID;
						$event_link = get_the_permalink($event_id);
		   				$event_title = $event->post_title; ?>
						<div class="owl-item">
							<article class="">
								<div class="post-outer overlay slide-parallax">
									<div class="overlay-media" style="z-index: 0;">
										<a href="<?php echo $event_link; ?>" class="overlay-link"></a>
										<div id="" class="jarallax-keep-img" data-speed="0.7">
											<?php
		   										if(has_post_thumbnail($event_id)):
		   	    									$thumb_img = wp_get_attachment_image_src(get_post_thumbnail_id($event_id), 'large');
		   	    									$slider_url = $thumb_img[0];
		   										else:
											   		if(!empty($default_image)):     
											   			$slider_url = $default_image['sizes']['custom-img-size'];
											   		endif;
											   	endif;	
											?>
											<img class="jarallax-img" width="800" height="1198" src="<?php echo $slider_url; ?>">
										</div>
									</div>
									<div class="overlay-outer post-inner">
										<div class="overlay-inner">
											<h2 class="entry-title">
												<a href="<?php echo $event_link; ?>"><?php echo $event_title; ?></a>
											</h2>
											<div class="view-post-button">
												<div class="wp-block-button">
													<a class="wp-block-button__link  button-effect" href="<?php echo $event_link; ?>" target="">
													<span>Sign up</span>
													<span><i class="cs-icon cs-icon-arrow-right"></i></span>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</article>
						</div>
				<?php endforeach; ?>
				<!-- end loop through event  -->
		   		</div>
		   </div>
		</div>
		<!-- Section-1 end -->


		<!-- Section-2 start -->
		<div class="course-workshop-portfolio-sec">
			<div class="custom-container">
				<?php if(!empty($section2_title)): ?>
				<h3 id="three-column-grid-with-navigation"><?php echo $section2_title; ?></h3>
				<?php endif; ?>

				<div class="course-workshop-portfolio sight-block-portfolio sight-block-portfolio-id sight-block-portfolio-layout-justified">
					<div class="sight-portfolio-area">
						<?php
						$posts_per_page = 6;
						$sub_categorie_arr = get_categories(array(
							'taxonomy' => 'pmpro_course_category',
							'orderby' => 'name',
							'order'   => 'ASC',
							'parent'  => $category_id1,
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
												'terms' => $category_id1
											),
										) 
									);
							$the_query = new WP_Query($total_post_args);
							$totalpost = $the_query->found_posts;
						}
						// get parent category list only
						$args = array(
								'taxonomy'     => $taxonomy,
								'child_of'     => 0,
								'parent'       => $category_id1,
								'orderby' => 'name',
								'order'   => 'ASC', 
								'hide_empty'   => false,
						);
						$sub_cats = get_categories($args);
						if($sub_cats): ?>
						<div class="sight-portfolio-area-filter" id="myBtnContainer">
							<div class="sight-portfolio-area-filter__title">Categories</div>
							<ul class="sight-portfolio-area-filter__ul_list">
								<li class="sight-portfolio-area-filter__list-item courses1-category-list sight-filter-all sight-filter-active" data-id="<?php echo $category_id1; ?>">
									<a href="#" class="btn active">All</a>
								</li>
								<li class="sight-portfolio-area-filter__list-item courses1-category-list" data-id="<?php echo $category_id1; ?>">
									<a href="#" class="btn"><?php echo get_term($category_id1, $taxonomy)->name; ?></a>
								</li>
								<?php 
								foreach($sub_cats as $sub_category): ?>
									<li class="sight-portfolio-area-filter__list-item courses1-category-list" data-id="<?php echo $sub_category->term_id; ?>">
										<a href="#" class="btn"><?php echo $sub_category->name; ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<?php endif; ?>
						<div class="sight-portfolio-area__outer">
							<div class="sight-portfolio-area__main sight-portfolio-area__main_course">
							<?php
							if($sub_cats && !empty($sub_categorie_arr)):
								$post_args = array(
									'post_type' => 'pmpro_course',
									'post_status' => 'publish',
									'order' => 'DESC',
									'posts_per_page' => $posts_per_page,
									'tax_query' => array(
										array(
											'taxonomy' => $taxonomy,
											'terms' => $sub_categorie_arr
										),
									)
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
									wp_reset_postdata(); ?>
								<?php 
								endif;
							else:
								$post_args = array(
									'post_type' => 'pmpro_course',
									'post_status' => 'publish',
									'posts_per_page' => $posts_per_page,
									'tax_query' => array(
										array(
											'taxonomy' => $taxonomy,
											'terms' => $category_id1
										),
									)
								);
								$arr_posts = new WP_Query($post_args);
								if($arr_posts->have_posts()):
									while($arr_posts->have_posts()): $arr_posts->the_post(); ?>
										<article class="sight-portfolio-entry sight-portfolio-custom <?php echo $category_id1; ?>">
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
							endif; ?>
							</div>
						</div>
						<?php if($totalpost != "" && $totalpost > $posts_per_page && $posts_per_page > 0): ?>
						<div class="sight-portfolio-area__pagination sight-portfolio-area__pagination_course">
							<button class="sight-portfolio-load-more-btn course1-load-btn" data-total-post="6" data-category-id="<?php echo $category_id1; ?>">Load More</button>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
        <!-- Section-2 end -->

        <!-- Section-3 start -->
		<div class="course-workshop-portfolio-sec">
			<div class="custom-container">
				<?php if(!empty($section3_title)): ?>
				<h3 id="three-column-grid-with-navigation"><?php echo $section3_title; ?></h3>
				<?php endif; ?>

				<div class="course-workshop-portfolio sight-block-portfolio sight-block-portfolio-id sight-block-portfolio-layout-justified">
					<div class="sight-portfolio-area">
						<?php
						$posts_per_page = 6;
						$sub_categorie_arr = get_categories(array(
							'taxonomy' => 'pmpro_course_category',
							'orderby' => 'name',
							'order'   => 'ASC',
							'parent'  => $category_id2,
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
												'terms' => $category_id2
											),
										) 
									);
							$the_query = new WP_Query($total_post_args);
							$totalpost = $the_query->found_posts;
						}
						// get parent category list only
						$args = array(
								'taxonomy'     => $taxonomy,
								'child_of'     => 0,
								'parent'       => $category_id2,
								'orderby' => 'name',
								'order'   => 'ASC', 
								'hide_empty'   => false,
						);
						$sub_cats = get_categories($args);
						if($sub_cats): ?>
						<div class="sight-portfolio-area-filter" id="myBtnContainer2">
							<div class="sight-portfolio-area-filter__title">Categories</div>
							<ul class="sight-portfolio-area-filter__ul_list">
								<li class="sight-portfolio-area-filter__list-item courses2-category-list sight-filter-all sight-filter-active" data-id="<?php echo $category_id2; ?>">
									<a href="#" class="btn active">All</a>
								</li>
								<li class="sight-portfolio-area-filter__list-item courses2-category-list" data-id="<?php echo $category_id2; ?>">
									<a href="#" class="btn"><?php echo get_term($category_id2, $taxonomy)->name; ?></a>
								</li>
								<?php 
								foreach($sub_cats as $sub_category): ?>
									<li class="sight-portfolio-area-filter__list-item courses2-category-list" data-id="<?php echo $sub_category->term_id; ?>">
										<a href="#" class="btn"><?php echo $sub_category->name; ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<?php endif; ?>
						<div class="sight-portfolio-area__outer">
							<div class="sight-portfolio-area__main sight-portfolio-area__main_affiliates_course">
							<?php
							if($sub_cats && !empty($sub_categorie_arr)):
								$post_args = array(
									'post_type' => 'pmpro_course',
									'post_status' => 'publish',
									'order' => 'DESC',
									'posts_per_page' => $posts_per_page,
									'tax_query' => array(
										array(
											'taxonomy' => $taxonomy,
											'terms' => $sub_categorie_arr
										),
									)
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
									wp_reset_postdata(); ?>
								<?php 
								endif;
							else:
								$post_args = array(
									'post_type' => 'pmpro_course',
									'post_status' => 'publish',
									'posts_per_page' => $posts_per_page,
									'tax_query' => array(
										array(
											'taxonomy' => $taxonomy,
											'terms' => $category_id2
										),
									)
								);
								$arr_posts = new WP_Query($post_args);
								if($arr_posts->have_posts()):
									while($arr_posts->have_posts()): $arr_posts->the_post(); ?>
										<article class="sight-portfolio-entry sight-portfolio-custom <?php echo $category_id2; ?>">
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
							endif; ?>
							</div>
						</div>
						<?php if($totalpost != "" && $totalpost > $posts_per_page && $posts_per_page > 0): ?>
						<div class="sight-portfolio-area__pagination sight-portfolio-area__pagination_affiliates">
							<button class="sight-portfolio-load-more-btn course2-load-btn" data-total-post="6" data-category-id="<?php echo $category_id2; ?>">Load More</button>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<!-- Section-3 end -->
	</main>
</div>

<style>	
	/* container */
		.page-template-courses-workshop .site-content  {
			margin-top:0px;
			margin-bottom:0px;
		}
		.page-template-courses-workshop .cs-container {
			max-width: 100%;
			padding-right: 0px;
    		padding-left: 0px;
		}
		.page-template-courses-workshop .cs-container .main-content {
			margin-right: 0;
    		margin-left: 0;
		}
	/* container */
	/* workshop-slider-banner css start */
		.workshop-banner {
			overflow: hidden;
		}
		.workshop-slider-banner {
			margin-bottom:60px;
		}
		.workshop-slider-banner .overlay {
			height:700px;
		}
		.workshop-slider-banner .jarallax-keep-img {
			height:100%;
			z-index: -1;
		}
		.workshop-slider-banner .jarallax-keep-img img {
			z-index: -1;
			margin:0 !important;
			height:816.7px !important;
		}
		.workshop-slider-banner .slick-track {
			margin: 0 25px;
		}
		.workshop-slider-banner .slick-next {
			right: 0;
		}
		.workshop-slider-banner .slick-prev {
			left: 0;
		}
		.workshop-slider-banner .slick-next,.workshop-slider-banner .slick-prev{
			height: 100%;
			width: 10%;
			z-index: 9;
		}
		.workshop-slider-banner .slick-next:before, .workshop-slider-banner .slick-prev:before {
			content: initial;
		}
		.workshop-slider-banner .slick-next:hover, .workshop-slider-banner .slick-prev:hover{
			cursor: pointer;
		}
		@media (max-width: 1199px) { 
			.workshop-slider-banner .slick-track {
				margin: 0px;
			}
			.workshop-slider-banner .jarallax-keep-img img {
				height:960px !important;
			}
		}
		@media (max-width: 991px) { 
			.workshop-slider-banner .jarallax-keep-img img {
				height:836px !important;
			}
		}
		@media (max-width: 767px) {
			.workshop-slider-banner .overlay {
				height:400px;
			}
			.workshop-slider-banner .jarallax-keep-img img {
				height:580px !important;
			}
			.workshop-slider-banner .overlay .overlay-inner {
				padding: 20px;
			}
			.workshop-slider-banner .overlay .entry-title {
				font-size:26px;
			}
	}


	/* workshop-slider-banner css end */
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
	/* course-workshop-portfolio-sec css start */
	.course-workshop-portfolio-sec .custom-container {
		max-width: 1200px;
		width: 100%;
		padding-right: 20px;
		padding-left: 20px;
		margin-right: auto;
		margin-left: auto;
	}
	.course-workshop-portfolio.sight-block-portfolio {
   		padding-bottom: 50px;
		padding-top:15px;
	}
	.course-workshop-portfolio.sight-block-portfolio-layout-justified .sight-portfolio-area__main {
		padding-bottom: 0px;
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
	.course-workshop-portfolio .sight-portfolio-area__pagination {
		margin-top: 30px;
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
	}
	.sight-portfolio-area__main .sight-portfolio-custom {
		width:31.6%;
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
	/* footer-section  */
	.site-footer .cs-container, .site-footer .footer-instagram {
		padding-right: 20px;
		padding-left: 20px;
		max-width: 1200px;
		margin-left: auto;
    	margin-right: auto;
	} 
</style>

<script>

jQuery(document).ready(function($){
   // workshop-slider-banner js start
   $('.workshop-slider-banner').slick({
      dots: false,
      arrows: false,
      infinite: true,
      slidesToShow: 3.7,
      slidesToScroll: 1,
    //   autoplay: true,
    //   autoplaySpeed: 1000,
      responsive: [
          {
          breakpoint: 1199,
          settings: {
              slidesToShow: 2,
          }
          },
          {
          breakpoint: 991,
          settings: {
              slidesToShow: 1,
          }
          }
      ]
  });
//   workshop-slider-banner js end
});

	// Add active class to the current button (highlight it) (Course & workshop)
	var btnContainer = document.getElementById("myBtnContainer");
	var btns = btnContainer.getElementsByClassName("btn");
	for (var i = 0; i < btns.length; i++) {
	  btns[i].addEventListener("click", function(){
	    var current = btnContainer.getElementsByClassName("active");
	    // add and remove parent list active class
	    const list = current[0].closest("li");
		list.classList.remove("sight-filter-active");
	    this.closest("li").className += " sight-filter-active";
	    // add and remove active class from button
	    current[0].className = current[0].className.replace(" active", "");
	    this.className += " active"; 
	  });
	}

	// Add active class to the current button (highlight it) (Affiliates)
	var btnContainer2 = document.getElementById("myBtnContainer2");
	var btns2 = btnContainer2.getElementsByClassName("btn");
	for (var i = 0; i < btns2.length; i++) {
	  btns2[i].addEventListener("click", function(){
	    var current = btnContainer2.getElementsByClassName("active");
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
<?php get_footer(); ?>