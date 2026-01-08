<?php /* Template Name: Media Center */ ?>
<?php 
    get_header(); 
    $search_query = '';
    $order_by = 'DESC';
    if( isset($_GET) ){
        if( isset($_GET['query']) && !empty($_GET['query']) ){
            $search_query = sanitize_text_field($_GET['query']);
        }
        if( isset($_GET['order']) && !empty($_GET['order']) ){
            $order_by = $_GET['order'];
        }
    }
?>

<div class="page-heading" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/img/media-center-head.jpg')">
    <div class="container d-flex flex-column align-items-start text-white">
        <h1 class="head-h1"><?php echo $language['MEDIA_CENTER']['BANNER']['TITLE']; ?></h1>
        <h5 class="fs-6 ff-ga m-0 mt-3"><?php echo $language['MEDIA_CENTER']['BANNER']['TEXT']; ?></h5>
    </div>
</div>

<section class="bg-cream py-10">
    <div class="container">
        <div class="row mb-5 gap-4 gap-md-0 align-items-center">
            <div class="col-md-4">
                <h2 class="display-4 m-0 text-orange ff-macky"><?php echo $language['MEDIA_CENTER']['NEWS']; ?></h2>
                <!-- <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Images</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Videos</a>
                    </li>
                </ul> -->
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
                    <div>
                        <select name="order" id="" class="form-select form-control-lg rounded-pill px-4 filter-input" style="width:120px">
                            <option value="DESC" <?php echo $order_by == 'DESC' ? 'selected' : '' ?>><?php echo $language['LINKS']['LATEST']; ?></option>
                            <option value="ASC" <?php echo $order_by == 'ASC' ? 'selected' : '' ?>><?php echo $language['LINKS']['OLDEST']; ?></option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <?php
                $args = array(
                    'posts_per_page' => 30,
                    'post_type' => 'media-center-post',
                    //'posts_per_page' => 10,  // Limit to 10 posts
                    'orderby' => 'date',
                    'order' =>  $order_by,
                    'post_status' => 'publish',
                    's'           => $search_query,
                );
                $query = new WP_Query($args);

                if ( $query->have_posts() ) :
                    while ($query->have_posts()) : 
                        $query->the_post();

                        $post_slug = get_post_field('post_name', get_post());
                        $featured_image = get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'img-fluid preview'));
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
                            
                            <div class="content p-4 flex-grow-1 d-flex flex-column gap-2">
                                <small><?php echo get_the_date('j F Y'); ?></small>
                                <div class="flex-grow-1 mb-3">
                                    <h5 class="fs-5 m-0 fw-semibold text-deep-ocean"><?php the_title(); ?></h5>
                                </div>
                                <a href="<?php echo home_url();?>/media-center/<?php echo $post_slug; ?>" class="text-orange text-decoration-none d-flex align-items-center gap-2">
                                    <?php echo $language['LINKS']['READ_MORE']; ?>
                                    <img class="<?php echo $isRTL ? 'rotate-180' : ''; ?>" loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/chevron-right-orange.svg">
                                </a>
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


<?php include_once get_template_directory() . "/components/media-inquiry.php"; ?>
<?php get_footer();?>

<script>
    jQuery(document).ready(function(){
        jQuery('.filter-form select[name="order"]').change((e) => {
            e.preventDefault();
            jQuery('.filter-form').submit();
        });
    });
</script>