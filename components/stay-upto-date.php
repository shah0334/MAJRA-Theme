<section class="subs-sec" id="stay-upto-date">
	<img loading="lazy" class="subs-bg" src="<?php bloginfo('template_directory'); ?>/assets/img/Subscribe.jpg">
	<div class="container">
		<div class="row">
			<div class="col-md-7"></div>
			<div class="col-md-5">
				<div class="side-con-main text-white">
					<h3 class="subhead text-white mb-2"><?php echo $language['SUBSCRIBE']['SUB_HEADING']; ?></h3>
					<h2 class="head-h2 text-light-orange mb-2"><?php echo $language['SUBSCRIBE']['HEADING']; ?></h2>
					<p class="para text-white mb-4"><?php echo $language['SUBSCRIBE']['DETAIL']; ?></p>
						<!-- <a href="" class="main-btn">See full calendar</a> -->
					<!-- <?= do_shortcode('[contact-form-7 id="fc93cbe" title="Contact form 1"]'); ?> -->
					<?= do_shortcode('[subscribe-form]'); ?>
				</div>
			</div>
		</div>
	</div>
</section>