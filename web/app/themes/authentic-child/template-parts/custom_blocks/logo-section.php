<!-- homepage logo section start -->
<?php 
$home_blocks = get_fields(); // get all fields in array
$home_logos = $home_blocks['home_logos'];

if(!empty($home_logos)): ?>
<section class="logo-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php foreach($home_logos as $logo): ?>
                <div class="logo">
                   <img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" width="100%" height="auto">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<!--homepage logo section end -->