<?php
/**
 * Template for Knowledge Square Category taxonomy
 */

get_header(); 
$term = get_queried_object();
if ( !$term instanceof WP_Term ) {
    die('Invalid request');
}

$bgImage = 'ks-research-heading.png';
if( $term->slug == 'ks-manuals' ){
    $bgImage = 'ks-manual-heading.png';
}


$search_query = '';
$filter_by_topic = '';
if( isset($_GET) ){
    if( isset($_GET['query']) && !empty($_GET['query']) ){
        $search_query = sanitize_text_field($_GET['query']);
    }
    if( isset($_GET['topic']) && !empty($_GET['topic']) ){
        $filter_by_topic = $_GET['topic'];
    }
}

?>

<!-- <h1><?php echo esc_html($term->name); ?></h1>
<p>Slug: <?php echo esc_html($term->slug); ?></p>
<p>Term ID: <?php echo esc_html($term->term_id); ?></p>
<p>Taxonomy: <?php echo esc_html($term->taxonomy); ?></p>
<p>Description: <?php echo esc_html($term->description); ?></p> -->

<div class="page-heading text-white gap-10 bg-deep-ocean" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/img/knowledge-square/<?php echo $bgImage; ?>')">
	<div class="container flex-grow-1">
		<a href="<?php echo home_url() ?>/knowledge-square" class="text-white text-decoration-none d-flex align-items-center gap-1">
			<img class="w-24 <?php echo $isRTL ? 'rotate-180' : ''; ?>" loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/chevron-left-white.svg">
			<span><?php echo $language['KNOWLEDGE_SQUARE']['BACK_TO_KS']; ?></span>
		</a>
	</div>
	<div class="container mb-5">
		<h1 class="head-h2 text-white"><?php echo esc_html($term->name); ?></h1>
    </div>
</div>

<section class="bg-cream py-10">
    <div class="container">
        <div class="row mb-5 gap-4 gap-md-0">
            <div class="col-md-4">
                <h2 class="head-h2 m-0"><?php echo $language['KNOWLEDGE_SQUARE']['REPORTS']; ?></h2>
            </div>
            <div class="col-md-8">
                <form class="d-flex flex-column flex-md-row justify-content-end gap-2 align-items-center filter-form">
                    <div>
                        <!-- <input type="text" class="form-control form-control-lg rounded-pill" placeholder="Search" /> -->
                        <div class="input-group input-group-lg rounded-pill overflow-hidden border">
                            <input type="text" class="form-control border-0" placeholder="<?php echo $language['LINKS']['SEARCH']; ?>" name="query" value="<?php echo $search_query; ?>">
                            <button class="btn btn-default bg-orange text-white position-relative" type="submit" style="width:50px">
                                <div class="position-absolute start-50 translate-middle" style="top:19px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 26 26" fill="none">
                                        <path d="M11.9167 20.5833C16.7031 20.5833 20.5833 16.7031 20.5833 11.9167C20.5833 7.1302 16.7031 3.25 11.9167 3.25C7.1302 3.25 3.25 7.1302 3.25 11.9167C3.25 16.7031 7.1302 20.5833 11.9167 20.5833Z" stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M22.7496 22.7496L18.0371 18.0371" stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                    <?php
                        $args = array(
                            'posts_per_page' => 20,
                            'taxonomy'   => 'post_tag',  // Tags taxonomy
                            'post_type'  => 'knowledge-square',  // Custom post type
                            'orderby'    => 'name',  // Optional: Order tags by name
                            'order'      => 'ASC',   // Optional: Ascending order
                            'hide_empty' => false,   // Show even empty tags
                        );
                        $tags = get_terms($args);
                        
                        if (!is_wp_error($tags) && !empty($tags)) {
                            $options = "<option value=''>".$language['KNOWLEDGE_SQUARE']['FILTER_BY_TOPIC']."</option>";
                            foreach ($tags as $tag) {
                                $options .= '<option value="'.$tag->slug.'" '.($filter_by_topic == $tag->slug ? 'selected' : '').'>'.$tag->name.'</option>';
                            }
                            echo '<div><select name="topic" id="" class="form-select form-control-lg rounded-pill px-4 filter-input" style="width: 200px">'.$options.'</select/></div>';
                        }
                    ?>
                </form>
            </div>
        </div>

        <div class="row">
            <?php
                $args = array(
                    'posts_per_page' => 20,
                    'post_type' => 'knowledge-square',
                    'orderby' => 'date',
                    'order' =>  'DESC',
                    'post_status' => 'publish',
                    's'           => $search_query,
                    'tag' => $filter_by_topic,
                    'tax_query' => array(
                        array(
                            'taxonomy' => $term->taxonomy, // Replace with your taxonomy if custom
                            'field'    => 'id',     // Can be 'slug', 'id', or 'name'
                            'terms'    => $term->term_id,
                        ),
                    ),
                );
                $query = new WP_Query($args);
                if ( $query->have_posts() ) :
                    while ($query->have_posts()) : 
                        $query->the_post();
                        $tags = get_the_terms(get_the_ID(), 'post_tag');
                        $featured_image = get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'img-fluid preview'));
                        $file = get_post_meta(get_the_ID(), '_knowledge_square_pdf', true);
                     
            ?>
                    <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-4">
                        <div class="project-card shadow-sm">
                            <?php
                                if ($featured_image) {
                                    echo $featured_image;
                                } else {
                                    echo '<div class="img-fluid preview bg-deep-ocean position-relative text-white">
                                            <div class="position-absolute top-50 start-50 translate-middle fs-5">'.$language["LINKS"]["NO_IMAGE"].'</div>
                                        </div>';
                                }
                            ?>
                            <div class="content bg-white p-4 flex-grow-1 d-flex flex-column">
                                <div class="d-flex gap-2 align-items-center mb-3">
                                    <div><h6 class="fw-bold m-0 fs-6"><?php echo esc_html($term->name); ?></h6></div>
                                    <div class="border-top border-3 rounded-pill border-aqua-marine" style="width:54px; height:0px"></div>
                                </div>
                                <h4 class="ff-macky fs-4 m-0 flex-grow-1"><?php the_title(); ?></h4>
                                <?php
                                    if (!empty($tags) && !is_wp_error($tags)) {
                                ?>
                                        <div class="mt-3">
                                            <h6 class="fw-bold mb-2 fs-6"><?php echo $language['KNOWLEDGE_SQUARE']['TOPICS']; ?></h6>
                                            <div>
                                <?php
                                            foreach ($tags as $tag) {
                                                echo '<span class="badge rounded-pill text-bg-transparent mb-2 text-xs me-1">' . esc_html($tag->name) . '</span>'; // Display tag name
                                            }
                                ?>
                                            </div>
                                        </div>
                                <?php
                                    }
                                    if(empty($file)){
                                        echo '<p class="text-center mt-3 mb-0">Attachment not available</p>';
                                    }
                                ?>
                                <div class="d-flex align-items-center justify-content-between icons gap-2 mt-3 <?php echo empty($file) ? 'd-none' : ''; ?>">
                                    <button data-file="<?php echo $file; ?>" class="preview-pdf btn btn-default border-dark rounded-pill flex-grow-1 d-flex align-items-center justify-content-center gap-1" data-bs-toggle="modal" data-bs-target="#pdfPreviewModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="22" viewBox="0 0 21 20" fill="none">
                                            <g clip-path="url(#clip0_3224_35587)">
                                            <path d="M1.80566 10.0006C1.80566 10.0006 5.139 3.33398 10.9723 3.33398C16.8057 3.33398 20.139 10.0006 20.139 10.0006C20.139 10.0006 16.8057 16.6673 10.9723 16.6673C5.139 16.6673 1.80566 10.0006 1.80566 10.0006Z" stroke="#00041C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10.9717 12.5C12.3524 12.5 13.4717 11.3807 13.4717 10C13.4717 8.61929 12.3524 7.5 10.9717 7.5C9.59097 7.5 8.47168 8.61929 8.47168 10C8.47168 11.3807 9.59097 12.5 10.9717 12.5Z" stroke="#00041C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_3224_35587">
                                            <rect width="20" height="20" fill="white" transform="translate(0.97168)"/>
                                            </clipPath>
                                            </defs>
                                        </svg>
                                        <?php echo $language['LINKS']['VIEW']; ?>
                                    </button>
                                    <a download href="<?php echo $file; ?>" class="btn btn-default bg-orange text-white rounded-pill flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                                        <svg width="21" height="22" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17.5283 12.4282V15.6663C17.5283 16.0957 17.3527 16.5075 17.0402 16.8112C16.7276 17.1148 16.3037 17.2854 15.8617 17.2854H4.19499C3.75296 17.2854 3.32904 17.1148 3.01648 16.8112C2.70391 16.5075 2.52832 16.0957 2.52832 15.6663V12.4282" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M5.86133 8.38135L10.028 12.429L14.1947 8.38135" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10.0283 12.4277V2.71338" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <?php echo $language['LINKS']['DOWNLOAD']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p class="text-center">'.$language["LINKS"]["NOT_FOUND"].'</p>';
                endif;
            ?>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="pdfPreviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="pdfPreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="pdfPreviewModalLabel">PDF Preview</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pdf-container"> </div>
    </div>
  </div>
</div>

<?php get_footer();?>

<script>
    jQuery(document).ready(function(){
        jQuery('.filter-form select[name="topic"]').change((e) => {
            e.preventDefault();
            jQuery('.filter-form').submit();
        });

        jQuery('.preview-pdf').on('click', (e) => {
            const file = jQuery(e.target).data('file');
            jQuery('.pdf-container').html('<object  data="'+file+'" type="application/pdf" width="100%" style="height: 80vh" ></object>');
        });
    });
</script>



<div class="container d-none">
    <h1><?php single_term_title(); ?></h1>
    <div class="taxonomy-description">
        <?php echo term_description(); ?>
    </div>

    <div class="taxonomy-posts">
        <?php
        $term = get_queried_object();
        $args = array(
            'post_type' => 'knowledge-square',
            'tax_query' => array(
                array(
                    'taxonomy' => 'knowledge_square_category',
                    'field'    => 'slug',
                    'terms'    => 'ks-manuals',
                ),
            ),
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No posts found in this category.</p>';
        endif;
        ?>
    </div>
</div>
