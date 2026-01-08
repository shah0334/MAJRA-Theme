<section class="subs-sec bg-deep-ocean">
	<img class="subs-bg" src="<?php bloginfo('template_directory'); ?>/assets/img/events-and-press.jpg">
	<div class="container text-white">
		<div class="row justify-content-around align-items-center gap-5 gap-md-0">
			<div class="col-md-6 col-lg-4">
                <h3 class="fs-3 mb-3"><?php echo $language['EVENTS']['SUB_HEADING']; ?></h3>
                <h2 class="ff-macky head-h2 text-light-orange mb-3"><?php echo $language['EVENTS']['HEADING']; ?></h2>
                <h5 class="m-0"><?php echo $language['EVENTS']['DETAIL']; ?></h5>
            </div>
			<div class="col-md-6 col-lg-4">
				<?= do_shortcode('[events-press-form]'); ?>
			</div>
		</div>
	</div>
</section>