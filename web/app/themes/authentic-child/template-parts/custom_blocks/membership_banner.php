<?php 
$banner_blocks = get_fields(); // get all fields in array

// left group
$membership_plan_title = $banner_blocks['membership_banner_block']['left_group']['membership_plan_title']; // get plan title
$membership_plan_list = $banner_blocks['membership_banner_block']['left_group']['membership_plan_list']; // get plan list
$member_description = $banner_blocks['membership_banner_block']['left_group']['description']; // get small description

$subscribe_button = $banner_blocks['membership_banner_block']['left_group']['button_title']; // get annual value

// right group
$parallax_effect = $banner_blocks['membership_banner_block']['right_group']['show_parallax_effect']; // Parallax effect
$class = ($parallax_effect == true) ? "jarallax-keep-img" : "";

$choose_image_or_video = $banner_blocks['membership_banner_block']['right_group']['choose_image_or_video']; // choose image/video

$banner_image = $banner_blocks['membership_banner_block']['right_group']['member_banner_image']['url']; // get image
$iframe = $banner_blocks['membership_banner_block']['right_group']['member_banner_video']; // get video
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
$iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe); 

$pmpro_levels = pmpro_sort_levels_by_order(pmpro_getAllLevels(false, true));
$pmpro_levels = apply_filters('pmpro_levels_array', $pmpro_levels); ?>

<!-- Banner section start -->
<section class="membership-banner-sec">
    <div class="container">
        <div class="row custom-row">
            <!-- Left section - subscription detail start -->
            <div class="col-md-6 banner-left-block">
                <?php if(!empty($membership_plan_title)): ?>
                <div class="banner-content-wrap">
                    <h1 class="h1"><?php echo $membership_plan_title; ?></h1>
                    
                    <?php endif; ?>

                    <?php if(!empty($membership_plan_list)): ?>
                 
                        <ul>
                            <?php foreach($membership_plan_list as $member): ?>
                                <li><?php echo $member['list']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="plan-toggle-wrap">
                        <div class="toggle-inner">
                            <input id="ce-toggle" type="checkbox">
                            <span class="custom-toggle"></span>
                            <?php
                            foreach($pmpro_levels as $level){
                                if($level->id == 2){ 
                                    $class = "t-month";
                                    $title = $level->name;
                                }
                                if($level->id == 5){ 
                                    $class = "t-year";
                                    $title = $level->name;
                                } ?>
                            <span class="<?php echo $class; ?>"><?php echo $title; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-content">
                    <?php
                    foreach($pmpro_levels as $level){
                        if($level->id == 2){
                            $billing_amount = $level->billing_amount; 
                            $title = "month";
                            $id = "monthly";
                            $style = "";
                        }
                        if($level->id == 5){ 
                            $billing_amount = $level->billing_amount;
                            $title = "year";
                            $id = "yearly";
                            $style = "display:none;";
                        } ?>
                        <div id="<?php echo $id; ?>" style="<?php echo $style; ?>">$<?php echo $billing_amount; ?>/<?php echo $title; ?></div>
                    <?php } ?>    
                    </div>

                    <?php
                    foreach($pmpro_levels as $level){
                        if($level->id == 2){
                            $class = "monthly";
                        }
                        if($level->id == 5){
                            $class = "yearly";
                            $style = "style='display:none;'";
                        } 
                        ?>
                        <a class="<?php echo $class; ?> btn-custom" href="<?php echo esc_url(pmpro_url("checkout", "?level=" . $level->id, "https")) ?>" <?php echo $style; ?>><?php echo $subscribe_button; ?></a>
                    <?php }
                    
                    if(!empty($member_description)): ?>
                    <div>
                        <p><?php echo $member_description; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Left section - subscription detail end -->

            <!-- Right section - image / video section start -->
            <div class="col-md-6 banner-right-block">
                <?php if(!empty($banner_image) && $choose_image_or_video == 'choose_image'): ?>
                <div class="banner-image jarallax-keep-img">
                    <img class="jarallax-img" src="<?php echo $banner_image; ?>"  width="300" height="auto" />
                </div>
                <?php endif; ?>

                <?php if(!empty($iframe) && $choose_image_or_video == 'choose_video'): ?>
                <div class="banner-video">
                    <?php echo $iframe; ?>
                </div>
                <?php endif; ?>
            </div>
            <!-- Right section - image / video section end -->

        </div>
    </div>
</section>
<!-- Banner section end -->