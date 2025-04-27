<!-- homepage our contributor section start -->
<?php 
$home_blocks = get_fields(); // get all fields in array
// left group
$contributor_top_title = $home_blocks['contributor_block']['left_group']['contributor_top_title'];
$contributor_title = $home_blocks['contributor_block']['left_group']['contributor_title'];
$contributor_content = $home_blocks['contributor_block']['left_group']['contributor_content'];
$contributor_button = $home_blocks['contributor_block']['left_group']['contributor_button'];
$contributor_button_label = $contributor_button['button_label'];
$contributor_button_link = button_group($contributor_button);
$parallax_effect = $home_blocks['contributor_block']['left_group']['show_parallax_effect']; // Parallax effect
$class = ($parallax_effect == true) ? "jarallax-keep-img" : "";
// right group
$contributor_lists = $home_blocks['contributor_block']['right_group']['select_contributor'];
$default_image = get_field('default_image', 'options'); ?>
<section class="contributor-section">
    <div class="container">
        <?php if(!empty($contributor_top_title)): ?>
        <div class="contributor-title-top">
            <h2 class="h2"><?php echo $contributor_top_title; ?></h2> 
        </div>
        <?php endif; ?>
        <div class="row custom-row">
            <?php if(!empty($contributor_title)): ?>
            <div class="col-md-3 left-side-block-con">
                <div class="contributor-content-wrap">
                    <div class="contributor-btn-wrapper">
                        <h3><?php echo $contributor_title; ?></h3>
                        <?php if(!empty($contributor_content)): ?>
                        <p><?php echo $contributor_content; ?></p>
                        <?php endif;
                        if(!empty($contributor_button_label) && !empty($contributor_button_link)): ?>
                        <a class="contributor-btn btn-custom" <?php echo $contributor_button_link; ?>>
                            <?php echo $contributor_button_label; ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif;
            if(!empty($contributor_lists)): ?>
            <div class="col-md-9 right-side-block-con contributor-slide-main">
                <?php foreach ($contributor_lists as $contributor_id):
                    $contributor_name = get_field('contributor_name', 'user_' . $contributor_id );
                    $contributor_image = get_field('image', 'user_' . $contributor_id );
                    $author_url = get_author_posts_url($contributor_id); ?>
                    <div class="contributor-slide">
                        <div class="contributor-image">
                            <?php 
                                if(!empty($contributor_image)): ?>
                                    <a class="<?php echo $class; ?>" data-speed="0.7" href="<?php echo $author_url; ?>"><img class="jarallax-img" src="<?php echo $contributor_image['url']; ?>" alt="<?php echo $contributor_name; ?>"  width="100%" height="auto" /></a>
                            <?php
                                else:
                                    if(!empty($default_image)): ?>  
                                        <a class="<?php echo $class; ?>" data-speed="0.7" href="<?php echo $author_url; ?>"><img class="jarallax-img" src="<?php echo $default_image['url']; ?>" alt="<?php echo $default_image['alt']; ?>" width="100%" height="auto" /></a>
                                    <?php endif; 
                                endif;  
                            ?>  
                        </div>
                        <div class="contributor-title">
                            <?php if(!empty($contributor_name)): ?>
                                <h3><a href="<?php echo $author_url; ?>"><?php echo $contributor_name; ?></a></h3>
                            <?php 
                            else: ?>
                                <h3><a href="<?php echo $author_url; ?>"><?php echo get_user_by('id', $contributor_id)->display_name; ?></a></h3>
                            <?php endif;
                            if(!empty(get_field('designation', 'user_' . $contributor_id))): ?>
                            <p><?php echo get_field('designation', 'user_' . $contributor_id) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- homepage our contributor section end -->