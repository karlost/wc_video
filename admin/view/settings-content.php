<?php
namespace wc_video\admin\submenu\content;
if (!defined('ABSPATH')) {

    exit;
}

if (!class_exists('submenuContentWDS')) {
    class submenuContentWDS
    {
        //hook functions
        public function __construct()
        {
        }

        /**
         * Zobrazení testovacího formuláře
         *
         * @param none
         * 
         * @author Wedesin
         * @return true/false
         */
        function form_page_contents()
        {

            $builder = new \wc_video\framework\forms_builder\formsBuilderWDS;
            //$builder = new formsBuilderWDS("test_form_mini");

            //$builder->display_form("test_form_mini");
            //$builder->display_form("test_form_mini_2");
            $builder->display_form("test_form_all");
        }

        /**
         * Zobrazení ukázkového formuláře
         *
         * @param none
         * 
         * @author Wedesin
         * @return true/false
         */
        function my_admin_page_contents()
        {   

            //Získat aktivní tab z parametru $_GET 
            $default_tab = null;
            $builder = new \wc_video\framework\forms_builder\formsBuilderWDS();
            $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab; ?>
            <div class="wrap wds-admin">

                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

                <?php settings_errors(); ?>
               

                <div class="row">
                    <div class="column content">
                        <nav class="nav-tab-wrapper">
                            <a href="?page=wds_wc_video" class="nav-tab <?php if ($tab === null) : ?>nav-tab-active<?php endif; ?>"> <?php echo wds_get_svg(TM_URLWCVIDEO . "assets/icons/home-line.svg"); ?> Default Tab</a>
                            <a href="?page=wds_wc_video&tab=settings" class="nav-tab <?php if ($tab === 'settings') : ?>nav-tab-active<?php endif; ?>"><?php echo wds_get_svg(TM_URLWCVIDEO . "assets/icons/slider-line.svg"); ?>Settings</a>
                            <a href="?page=wds_wc_video&tab=tools" class="nav-tab <?php if ($tab === 'tools') : ?>nav-tab-active<?php endif; ?>"><?php echo wds_get_svg(TM_URLWCVIDEO . "assets/icons/settings-line.svg"); ?>Tools</a>
                            <?php
                                $mailingForm = $builder->get_fields_form("emails_settings");
                            if (!empty($mailingForm)){
                                ?>
                                <a href="?page=wds_wc_video&tab=emails" class="nav-tab <?php if ($tab === 'emails') : ?>nav-tab-active<?php endif; ?>"><?php echo wds_get_svg(TM_URLWCVIDEO . "assets/icons/link-line.svg"); ?>Emaily</a>
                                <?php 
                            } ?>
                        </nav>
                        <div class="content-box">
                            <?php switch ($tab):
                                    // Default tab
                                default:
                            ?>

                          
                                    <h2>Nastavení pluginu</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac dictumst phasellus nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.</p>
                                    <hr>
                                    
                                <?php
                                    break;

                                    //Tab 2
                                case 'settings':

                                    $builder->display_form("test_form_mini");

                                    break;

                                    //Tab 3
                                case 'tools':
                                ?>
                                    Tools tab content
                            <?php
                                    break;
                                case 'emails':
                                    if (!empty($mailingForm)){
                                        $builder->display_form("emails_settings");
                                    }
                                    break;

                            endswitch; ?>
                        </div>
                    </div>
                    <div class="column sidebar">
                    
                    <?php 
                    $zobrazeni = new \wc_video\admin\info_box\view\viewAdminBoxWDS;
                    $zobrazeni->wedesin_dashboard_widget_display('pluginsnews');
                    ?>
                
                 
                        <?php   
                        $zobrazeni->wedesin_dashboard_widget_display('plugins');
                        ?>
                        <h3><?php echo wds_get_svg(WDS_BRANDURL . "assets/icons/info-standard-line.svg"); ?>
                    
            
                    </div>
                </div>
            </div>
<?php
        }
    }

    new \wc_video\admin\submenu\content\submenuContentWDS;
}