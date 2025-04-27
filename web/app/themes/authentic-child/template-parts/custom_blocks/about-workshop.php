<!-- homepage workshop section start -->
<?php 
$home_blocks = get_fields(); // get all fields in array
// left group
$workshop_title = $home_blocks['workshop_block']['left_group']['workshop_title'];
$workshop_description = $home_blocks['workshop_block']['left_group']['workshop_description'];
$workshop_button = $home_blocks['workshop_block']['left_group']['workshop_button'];
$workshop_button_label = $workshop_button['button_label'];
$workshop_button_link = button_group($workshop_button);
$parallax_effect = $home_blocks['workshop_block']['left_group']['show_parallax_effect']; // Parallax effect
$class = ($parallax_effect == true) ? "jarallax-keep-img" : "";
// right group
$workshop_image = $home_blocks['workshop_block']['right_group']['workshop_image']; ?>
<section class="workshop-section">
    <div class="container">
        <div class="row custom-row">
            <?php if(!empty($workshop_title)): ?>
            <div class="col-md-5 left-side-block">
                <div class="workshop-content-wrap">
                    <h2 class="h2"><?php echo $workshop_title; ?></h2>
                    <?php 
                    if(!empty($workshop_description)): ?>
                    <p class="workshop-text"><?php echo $home_blocks['workshop_block']['left_group']['workshop_description']; ?></p> 
                    <?php endif;
                    if(!empty($workshop_button_label) && !empty($workshop_button_link)): ?>
                        <div class="workshop-btn-wrapper">
                            <a class="workshop-btn btn-custom" <?php echo $workshop_button_link; ?>>
                                <?php echo $workshop_button_label; ?>
                            </a>
                        </div>
                   <?php endif; ?>
                </div>
            </div>
            <?php endif;
            if(!empty($workshop_image)): ?>
            <div class="col-md-7 right-side-block">
                <div class="workshop-video <?php echo $class; ?>" data-speed="0.7">
                    <img class="jarallax-img" src="<?php echo $workshop_image['url']; ?>" alt="<?php echo $workshop_image['alt']; ?>"  width="100%" height="auto">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!--homepage workshop Section end -->