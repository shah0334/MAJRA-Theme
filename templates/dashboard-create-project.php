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
        
        <?php if ($current_step <= 6): ?>
        <div class="stepper-container bg-cp-cream-light mb-5 px-4">
             <div class="d-flex align-items-start w-100">
                <?php foreach ($steps as $step_num => $step_name): ?>
                    <?php 
                        $is_last = $step_num == 6;
                        $is_completed = $step_num < $current_step;
                        $is_active = $step_num == $current_step;
                        
                        // Circle Styles
                        $circle_cls = 'bg-white text-secondary border border-2 border-secondary-subtle'; // Default Inactive
                        $text_cls = 'text-secondary opacity-50'; // Default Inactive Text
                        $circle_style = 'width: 38px; height: 38px; transition: all 0.3s ease;';

                        if ($is_completed || $is_active) {
                             $circle_cls = 'text-white border-0'; // Removed bg class
                             $text_cls = 'text-cp-deep-ocean fw-bold';
                             $circle_style .= ' background-color: #3bc4bd;'; // Apply Teal directly
                        }
                        
                        // Line Styles (Line connects THIS step to NEXT step)
                         $line_color = ($step_num < $current_step) ? '#3bc4bd' : '#E5E7EB';
                    ?>
                    
                    <div class="step-item d-flex align-items-top <?php echo $is_last ? '' : 'flex-fill'; ?>">
                        <div class="d-flex flex-column align-items-center" style="width: 180px;"> <!-- Width to contain label text -->
                             <a href="?step=<?php echo $step_num; ?>" class="text-decoration-none d-flex flex-column align-items-center">
                                 <div class="step-circle rounded-circle d-flex align-items-center justify-content-center fw-bold fs-6 mb-3 <?php echo $circle_cls; ?>" style="<?php echo $circle_style; ?>">
                                    <?php if ($is_completed): ?>
                                       <i class="bi bi-check-lg"></i>
                                    <?php else: ?>
                                        <?php echo $step_num; ?>
                                    <?php endif; ?>
                                </div>
                                <span class="step-label text-center font-mackay small <?php echo $text_cls; ?>" style="font-size: 14px; line-height: 1.2;">
                                    <?php echo $step_name; ?>
                                </span>
                            </a>
                        </div>
                        
                        <?php if (!$is_last): ?>
                            <!-- Connector Line -->
                             <div class="step-line flex-fill mx-2 rounded-pill mt-3" style="height: 4px; background-color: <?php echo $line_color; ?>;"></div>
                        <?php endif; ?>
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
