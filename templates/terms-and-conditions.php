<?php /* Template Name: Terms & Conditions */ ?>
<?php get_header(); ?>

<section class="bg-cream py-10">
    <div class="container">
        <!-- <h2 class="head-h2 mb-5" data-aos="fade-up" data-aos-duration="1000">Terms & Conditions</h2> -->
        <h2 class="head-h2 mb-5" data-aos="fade-up" data-aos-duration="1000">Terms & Conditions</h2>
        <div class="row">
            <div class="col-12">
                <?php
                    $page_slug = 'terms-conditions';
                    $page = get_page_by_path($page_slug);
                    // Retrieve the content of the page
                    $page_content = apply_filters('the_content', $page->post_content);

                    if( !empty( $page_content) ){
                        echo $page_content;
                    }else{
                        echo '<p class="text-center">No content found</p>';
                    }
                ?>
            </div>
        </div>
    </div>  
</section>

<?php get_footer();?>