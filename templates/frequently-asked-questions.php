<?php /* Template Name: Frequently Asked Questions */ ?>
<?php 
    get_header(); 

    function get_terms_with_images($taxonomy = 'faq-categories') {
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false, // Show empty categories too
            'meta_key'   => 'faq_category_order',
            'orderby'    => 'meta_value_num',
            'order'      => 'ASC'
        ]);
    
        $terms_with_images = [];
    
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $image_id  = get_term_meta($term->term_id, 'faq_category_image', true);
                $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    
                $terms_with_images[] = [
                    'id'    => $term->term_id,
                    'name'  => $term->name,
                    'slug'  => $term->slug,
                    'image' => $image_url,
                ];
            }
        }
    
        return $terms_with_images;
    }
    $faq_categories = get_terms_with_images();

    function get_faqs_by_category($category_slug) {
        $args = [
            'post_type'      => 'faqs',
            'posts_per_page' => -1, // Retrieve all posts
            'orderby'        => 'date',
            'order'          => 'ASC',
            'tax_query'      => [
                [
                    'taxonomy' => 'faq-categories', // Change to your taxonomy name
                    'field'    => 'slug', // You can use 'id' instead of 'slug' if needed
                    'terms'    => $category_slug, // Category slug or ID
                ],
            ],
        ];
    
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $faqs_parts = divideNumber($query->found_posts);
            $index = 0;
            echo '<div class="row">';
            while ($query->have_posts()) {
                $collapsed = $index > 0 ? 'collapsed' : '';
                $show = $index > 0 ? 'show' : '';

                // index - 0
                if( $index == 0 || $index == intval($faqs_parts[0]) ){
                    echo '<div class="col-12 col-md-6">';
                }

                $query->the_post();
                print_qa( $index, $category_slug );
                $index++;

                // index - 1
                if( $index == intval($faqs_parts[0]) ||  $index == $query->found_posts ){
                    echo '</div>';
                }
            }
            echo '</div>';
        } else {
            echo 'No FAQs found in this category.';
        }
    
        wp_reset_postdata();
    }

    function print_qa( $index, $category_slug ){
        echo '
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="heading-'.$category_slug.'-'.$index.'">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-'.$category_slug.'-'.$index.'" aria-expanded="false" aria-controls="collapse-'.$category_slug.'-'.$index.'">
                    '. get_the_title() .'
                </button>
                </h2>
                <div id="collapse-'.$category_slug.'-'.$index.'" class="accordion-collapse collapse" aria-labelledby="heading-'.$category_slug.'-'.$index.'" data-bs-parent="#faqAccordionArea">
                <div class="accordion-body">
                    '. get_the_content() .'
                </div>
                </div>
            </div>';
    }

    function divideNumber($num) {
        $part1 = ceil($num / 2);
        $part2 = $num - $part1;
        return [$part1, $part2];
    }
?>

<div class="page-heading" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/img/faq-banner.png')">
    <div class="container d-flex align-items-center h-100 text-white">
        <div>
            <h1 class="head-h1"><?php echo $language['FAQS']['TITLE']; ?></h1>
            <div class="row mt-3">
                <div class="col-md-6">
                    <h5 class="fs-6 ff-ga"><?php echo $language['FAQS']['TEXT']; ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="bg-cream py-10">
    <div class="container">
        <h1 class="head-h2 text-deep-ocean mb-5"><?php echo $language['FAQS']['TITLE_FULL']; ?></h1>

        <div class="row">
            <div class="accordion" id="faqAccordionArea">
                <?php
                    foreach ($faq_categories as $category) { 
                ?>
                    <div class="col-12 mb-5">
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <?php
                                if( isset( $category['image'] ) && !empty( $category['image'] ) ){
                                    $image = $category['image'];
                                    echo '<img src="'.$image.'" alt="" style="width:60px">';
                                }
                            ?>
                            <h4 class="m-0 text-deep-ocean"><?php echo $category['name']; ?></h4>
                        </div>
                        
                        <?php get_faqs_by_category($category['slug']); ?>
                    </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer();?>