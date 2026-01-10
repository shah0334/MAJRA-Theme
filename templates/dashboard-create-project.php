<?php
/* Template Name: Dashboard - Create Project */

get_header('dashboard');
global $language;

// Get current step from URL, default to 1
$current_step = isset($_GET['step']) ? intval($_GET['step']) : 1;
if ($current_step < 1) $current_step = 1;
if ($current_step > 6) $current_step = 6; // 6 steps total including completion

// Steps configuration
$steps = $language['DASHBOARD']['PROJ_WIZARD']['STEPS'];

?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        
        <!-- Stepper (Visible for steps 1-6 only, excluding completion) -->
        <?php if ($current_step <= 6): ?>
        <div class="stepper-container bg-cp-cream-light border-bottom border-light-gray mb-5 pb-4">
             <div class="d-flex justify-content-between align-items-center position-relative">
                <!-- Connector Line -->
                <div class="position-absolute w-100 top-50 start-0 translate-middle-y z-0" style="height: 2px; background-color: #E5E7EB; margin-top: -15px;"></div>
                
                <?php foreach ($steps as $step_num => $step_name): ?>
                    <?php 
                        $status_class = ''; // default
                        $circle_class = 'bg-light text-secondary';
                        
                        if ($step_num < $current_step) {
                            $status_class = 'completed';
                             $circle_class = 'bg-cp-aqua-marine text-white';
                        } elseif ($step_num == $current_step) {
                            $status_class = 'active';
                             $circle_class = 'bg-cp-aqua-marine text-white';
                        }
                    ?>
                    
                    <div class="step-item text-center z-1 position-relative d-flex flex-col align-items-center <?php echo $status_class; ?>" style="background: #f7fafb; padding: 0 10px;">
                        <a href="?step=<?php echo $step_num; ?>" class="text-decoration-none">
                             <div class="step-circle rounded-circle d-flex align-items-center justify-content-center fw-bold fs-6 mb-3 mx-auto <?php echo $circle_class; ?>" style="width: 32px; height: 32px; transition: all 0.3s ease;">
                                <?php if ($step_num < $current_step): ?>
                                    <i class="bi bi-check-lg"></i>
                                <?php else: ?>
                                    <?php echo $step_num; ?>
                                <?php endif; ?>
                            </div>
                            <span class="step-label d-block small fw-bold font-mackay <?php echo $step_num == $current_step ? 'text-cp-deep-ocean' : 'text-secondary'; ?>" style="font-size: 13px;">
                                <?php echo $step_name; ?>
                            </span>
                        </a>
                    </div>
                <?php endforeach; ?>
             </div>
        </div>
        <?php endif; ?>

        <!-- Step Content -->
        <div class="step-content">
            <?php 
            switch ($current_step) {
                case 1:
                    get_template_part('template-parts/dashboard/create-project/step', '1');
                    break;
                case 2:
                     get_template_part('template-parts/dashboard/create-project/step', '2');
                    break;
                case 3:
                     get_template_part('template-parts/dashboard/create-project/step', '3');
                    break;
                 case 4:
                     get_template_part('template-parts/dashboard/create-project/step', '4');
                    break;
                case 5:
                     get_template_part('template-parts/dashboard/create-project/step', '5');
                    break;
                case 6:
                     get_template_part('template-parts/dashboard/create-project/step', '6');
                    break;
                default:
                    get_template_part('template-parts/dashboard/create-project/step', '1');
            }
            ?>
        </div>

    </div>
</main>

<?php
get_footer('dashboard');
?>
