<?php
/**
 * The template for displaying the dashboard footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MAJRA
 */
global $language;
?>


		<?php wp_footer(); ?>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script src="<?php bloginfo('template_directory'); ?>/assets/js/script.js"></script>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-T954F0HQ77"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            
            gtag('config', 'G-T954F0HQ77');
        </script>
        <!-- Toast Container -->
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
             <div id="liveToast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto" id="toastTitle">Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" id="toastMessage">
                    <!-- Message here -->
                </div>
            </div>
        </div>

    </body>
    <script>
        // Global Toast Helper
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            if (toastEl) {
                const toastTitle = document.getElementById('toastTitle');
                const toastBody = document.getElementById('toastMessage');
                
                toastBody.textContent = message;
                
                if (type === 'error') {
                   // toastEl.classList.add('bg-danger', 'text-white');
                    toastTitle.textContent = 'Error';
                    toastTitle.className = 'me-auto text-danger fw-bold';
                } else {
                    // toastEl.classList.remove('bg-danger', 'text-white');
                    toastTitle.textContent = 'Success';
                    toastTitle.className = 'me-auto text-success fw-bold';
                }

                // Robust Bootstrap Check
                let bs = window.bootstrap;
                if (!bs && typeof bootstrap !== 'undefined') {
                    bs = bootstrap;
                }

                if (bs && bs.Toast) {
                    const toast = new bs.Toast(toastEl);
                    toast.show();
                } else {
                    console.error('Bootstrap 5 not found or Toast not available.');
                    // Fallback to manual display
                    toastEl.classList.add('show');
                    toastEl.style.display = 'block'; 
                    setTimeout(() => {
                        toastEl.classList.remove('show');
                        toastEl.style.display = 'none';
                    }, 5000);
                }
            }
        }

        $(document).ready(function(){
            $('.show-about-us-text').click(function(){
                const $this = $(this);
                $('.about-us-text').slideToggle();
                $('.show-about-us-text').addClass('d-none');
            });
            $('.hide-about-us-text').click(function(){
                $('.about-us-text').slideToggle();
                $('.show-about-us-text').removeClass('d-none');
            });

            // Check for success param
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') === 'org_created') {
                showToast("<?php echo $language['DASHBOARD']['ORG_FORM']['ORG_CREATED_SUCCESS']; ?>", 'success');
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</html>
