<!-- homepage banner section start -->
<?php 
$home_blocks = get_fields(); // get all fields in array
// left group
$parallax_effect = $home_blocks['banner_block']['left_group']['show_parallax_effect']; // Parallax effect
$class = ($parallax_effect == true) ? "jarallax-keep-img" : "";
$banner_image = $home_blocks['banner_block']['left_group']['banner_image']['url'];
$banner_title = $home_blocks['banner_block']['left_group']['banner_title'];
$banner_description = $home_blocks['banner_block']['left_group']['banner_description'];
$banner_button = $home_blocks['banner_block']['left_group']['banner_button'];
$banner_button_label = $banner_button['button_label'];
$banner_button_link = button_group($banner_button);
// right group
$iframe = $home_blocks['banner_block']['right_group']['banner_video'];
// Use preg_match to find iframe src.
preg_match('/src="(.+?)"/', $iframe, $matches);

if(!empty($matches[1])){
    $src = $matches[1];
}
else{
    $src = "";
}
// Add extra parameters to src and replace HTML.
$params = array(
    'controls'  => 0,
    'hd'        => 1,
    'autohide'  => 1,
    'autoplay' => 1,
);
$new_src = add_query_arg($params, $src);
$iframe = str_replace($src, $new_src, $iframe);
// Add extra attributes to iframe HTML.
$attributes = 'frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen';
$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe); ?>
<section class="banner-section <?php echo $class; ?>" data-speed="0.7" style="background-image: url('<?php if(empty($class)){ echo $banner_image; } ?>');">
    <?php if(!empty($class)): ?>
    <img class="jarallax-img" src="<?php if(!empty($banner_image)){ echo $banner_image; } ?>"  width="100%" height="auto" />
    <?php endif; ?>
    <div class="container">
        <div class="row custom-row">
            <?php if(!empty($banner_title)): ?>
            <div class="col-md-5 banner-left-block">
                <div class="banner-content-wrap">
                    <h1 class="h1"><?php echo $banner_title; ?></h1>
                    <?php 
                    if(!empty($banner_description)): ?>
                    <p class="banner-text"><?php echo $home_blocks['banner_block']['left_group']['banner_description']; ?></p> 
                    <?php endif;
                    if(!empty($banner_button_label) && !empty($banner_button_link)): ?>
                        <div class="banner-btn-wrapper">
                            <a class="banner-btn" <?php echo $banner_button_link; ?>>
                                <?php echo $banner_button_label; ?>
                            </a>
                        </div>
                   <?php endif; ?>
                </div>
            </div>
            <?php endif;
            if(!empty($iframe)): ?>
            <div class="col-md-7 banner-right-block">
                <div class="banner-video">
                    <?php echo $iframe; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!--homepage Banner Section end -->