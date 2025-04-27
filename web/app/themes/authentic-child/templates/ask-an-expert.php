<?php 
/*
 * Template Name: Ask an Expert
*/
get_header(); ?>
<div id="primary" class="">
	<main id="main" class="site-main" role="main">
		<?php
		$page_id = get_the_id();
		$page_title = get_the_title();
		if(has_post_thumbnail($page_id)){
			$banner_url = wp_get_attachment_url(get_post_thumbnail_id($page_id), 'large');
		}
		else{
			$banner_url = get_field('default_image', 'options')['url']; 
		} 
		$header_sub_title = get_field('header_sub_title'); // get header sub title
		$question_page_title = get_field('question_page_title'); // question page title
		$question_page_tagline = get_field('question_page_tagline'); // question page tagline
		$choose_question_page = get_field('choose_question_page'); // get question page url ?>

		<!-- Banner section start -->
		<section class="ask-expert-banner">
			<div class="ask-expert-banner-img">
				<div id="" class="jarallax-keep-img">
					<img class="jarallax-img" width="1920" height="380" src="<?php echo $banner_url; ?>" class="" alt="<?php echo $page_title; ?>">
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php if(!empty($page_title)): ?>
						<div class="ask-expert-banner-content">
							<div class="ask-expert-banner-text"><h1><?php echo $page_title; ?></h1>
								<?php if(!empty($header_sub_title)): ?>
								<p><?php echo $header_sub_title; ?></p>
								<?php endif; ?>
							</div>
							<?php endif; ?>
							<div id="faq_serach_form">
								<form method="post">
									<input type="text" name="faq_serach" class="faq_serach" required>
									<button class="close-btn" id="cleanSearch" type="button" style="display: none;"><i class="cs-icon cs-icon-cross"></i></button>
									<button class="search-btn" id="faqBtnSearch" type="submit">Search</button>
								</form>
								<!-- Search result list -->
							<div id="faq_result">
							<div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<!-- Banner section end -->

		<!-- Main content section start -->
		<section>
			<?php the_content(); ?>
		</section>
		<!-- Main content section end -->

		<!-- FAQ List start -->
		<?php	
	    global $wpdb;
	    $posts_per_page = 6;
	    $args = array(  
	            'post_status' => 'publish',
	            'posts_per_page' => $posts_per_page,  
	            'post_type' => 'faq',
			);
	    $loop = new WP_Query($args); ?>
		
		<section class="category-filter-sec">
			<div class="container">
				<div class="category-filter-main">
					<?php 
					$sizes = get_terms(
						array('taxonomy' => 'faq_cat', 'hide_empty' => false
					));
					if ($sizes) { ?>
						<fieldset>
						<legend class="active">Category:</legend>
						<div class="category-sel-main" style="display: block;">
							<div class="checkbox_group-main">
								<?php foreach($sizes as $size) { ?>
									<div class="checkbox_group">
										<input type="checkbox"  name="size[]"  class="filter-size" attr-id="<?php echo $size->term_id; ?>" value="<?php echo $size->term_id; ?>">
										<label><i class="fa fa-tags "></i>  <?php echo $size->name; ?></label>
									</div>
								<?php } ?>
								<div class="reset-btn">
								<button id="resetCat" type="button">Reset</button>
								</div>
							</div>
						</div>
						</fieldset>
					<?php } ?>
					
				</div>
				<div class="">
					<div class="category-row" id="datafetch">
					<?php
						global $post;
						$args = array(  
							'post_status' => 'publish',
							'posts_per_page' => 6,  
							'post_type' => 'faq',
							);
						$loop = new WP_Query($args); 
						while($loop->have_posts()) : $loop->the_post(); 
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
						wp_reset_postdata(); ?>
						</div>
			    </div>
				<div class="casemorebtn">
			    	<button class="faq-pagination btn-custom" type="button">Load More</button>
				</div>
			</div>
			
		</section>
		<!-- FAQ List end -->

		<!-- What's your Question section start -->
		<section class="whats_your_quetion">
			<div class="container">
				<div class="quetion_inner_block">
					<h2><?php echo $question_page_title; ?></h2>
					<p><?php echo $question_page_tagline; ?></p>
					<a class="btn-custom" href="<?php echo $choose_question_page; ?>">Ask a Question</a>
				</div>
			</div>
		</section>
		<!-- What's your Question section end -->
	</main>
</div>

<?php get_footer(); ?>

<script>
	jQuery(document).on('ready', function(){
		jQuery(".category-filter-main legend").click(function(){
			jQuery(".category-sel-main").slideToggle();
			jQuery(this).toggleClass('active');
		});
	});
</script>