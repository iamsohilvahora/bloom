<?php 
/*
 * Template Name: Ask a question
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
		$expert_title = get_field('expert_title');
		$question_title = get_field('question_title');
		$header_description = get_field('header_description'); ?>

		<!-- Banner section start -->
		<section class="ask-a-question-banner">
			<div class="container">
				<div class="ask-a-question-banner-img">
					<div id="" class="jarallax-keep-img">
						<img class="jarallax-img" width="1920" height="380" src="<?php echo $banner_url; ?>" class="" alt="<?php echo $page_title; ?>">
					</div>
				</div>
				<div class="ask-a-question-banner-content">
					<?php if(!empty($expert_title)): ?>
					<h3><?php echo $expert_title; ?></h3>
					<?php endif;
					if(!empty($question_title)): ?>
					<h1><?php echo $question_title; ?></h1>
					<?php endif; ?>
					<p><?php echo $header_description; ?></p>
				</div>	
				<?php if(!empty($header_description)): ?>
				
				<?php endif; ?>
			</div>	
		</section>
		<!-- Banner section end -->

		<section class="question-form-sec">
			<div class="container">
				<div class="custom-row">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
	</main>
</div>

<?php get_footer(); ?>