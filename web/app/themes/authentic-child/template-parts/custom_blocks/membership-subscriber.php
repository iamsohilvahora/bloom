<?php 
$subscriber_blocks = get_fields(); // get all fields in array
$subscriber_title = $subscriber_blocks['subscriber_title']; // get title

// Subscriber section
$membership_subscriber_contents = $subscriber_blocks['membership_subscriber_contents']; // get slider content ?>
<section class="subscriber-section">
    <div class="container">
       
        	<!-- Title start -->
            <?php if(!empty($subscriber_title)): ?>
            <div class="subscriber-title">
                <h2><?php echo $subscriber_title; ?></h2>    
            </div>
            <?php endif; ?>
        	<!-- Title end -->
            <div class="row custom-row">
            <!-- Left slider section start -->
            <?php if(!empty($membership_subscriber_contents)): ?>
            <div class="col-md-5 subscriber-left-block">
            	<?php foreach($membership_subscriber_contents as $slider_content): ?>
                <div class="slider-image">
                    <img class="jarallax-img" src="<?php echo $slider_content['choose_image']['url']; ?>"  width="300" height="auto" />
                </div>
            	<?php endforeach; ?>
            </div>
            <?php endif; ?>
            <!-- Left slider section end -->

            <!-- Right subscriber section start -->
            <?php if(!empty($membership_subscriber_contents)): ?>
            <div class="col-md-7 subscriber-right-block">
                <?php 
                foreach($membership_subscriber_contents as $slider_content): 
                	$page_title = $slider_content['page_title'];
                	$page_content = $slider_content['page_content'];
                	$select_page_link = $slider_content['select_page_link']; ?>
	                <div class="slider-content">
	               		<div class="Description_content">
	               			<?php if(!empty($page_title)): ?>
	               				<span class="Description_name"><?php echo $page_title; ?></span>
	               			<?php endif;

	               			if(!empty($page_content)): ?>
	               				<span class="Description_description"><?php echo $page_content; ?></span>
	               			<?php endif;

	               			if(!empty($select_page_link)): ?>
	               				<a href="<?php echo $select_page_link; ?>">Learn more</a>
	               			<?php endif; ?>
	               		</div>
	                </div>
				<?php endforeach; ?>
            </div>
            <?php endif; ?>
            <!-- Right subscriber section end -->
        </div>
    </div>
</section>