<?php

abstract class TwistPress_Developer_Tools_Page
{
    public $title = NULL;

    public $file_extension = 'txt';
    public $file_mime = 'text/plain';

    public $menu_slug = 'SLUG';

    public $description = 'Amazing Features Here';

    public function page_start($export_key = '')
    {
        $TwistPressDeveloperTools = TwistPress_Developer_Tools::get_instance();

        wp_enqueue_style('twistpress_debug_common', $TwistPressDeveloperTools::assets_path().'/css/common.css');
        ?>
        <h2><?php echo __('Developer Tools by TwistPress','twistpress_debug'); ?></h2>
        <div class="wrap twistpress_debug-wrap">
        <div class="twistpress_debug-content">
        <h3 class="nav-tab-wrapper wp-clearfix">
            <?php
            foreach($TwistPressDeveloperTools->pages_config as $page) {
                $active = ('twistpress_debug_' . $page == $_GET['page']) ? 'nav-tab-active' :'';
                printf('<a href="%s" class="nav-tab %s">%s</a>', menu_page_url( 'twistpress_debug_'.$page, FALSE ), $active, $TwistPressDeveloperTools->pages[$page]->title);
            }
            ?>
        </h3>

        <?php
        if($export_key) {
            // Export Button
            $TwistPressDeveloperTools_buttons = array();
            ob_start();
            ?>
            <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
                <input type="hidden" name="export_content" value="<?php echo $export_key; ?>">
                <input type="hidden" name="system_report_export" value="1">
                <input type="submit" value="<?php echo __('&darr; Export', 'wordpress-system-report'); ?>"
                       class="button button-primary">
            </form>
            <?php
            $buttons['export'] = ob_get_clean();

            // Reload button
            ob_start();
            ?>
            <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
                <input type="submit" value="<?php echo __('&#8634; Refresh', 'wordpress-system-report'); ?>"
                       class="button button-secondary">
            </form>
            <?php
            $buttons['reload'] = ob_get_clean();

            $buttons = apply_filters('twistpress_debug_buttons', $buttons);


            if (count($buttons)) {
                printf('<div class="twistpress_debug-action-buttons">%s</div>', implode(' ', $buttons));
            }
        }
    }

    public function page_end()
    {
        if(class_exists('PeepSo') && 1==PeepSo::get_option('bundle',0)) {
            $bundle = FALSE;
        } else {

            $bundle = get_transient('peepso_config_licenses_bundle');

            if (!strlen($bundle)) {

                $peepso_url = 'https://www.peepso.com';
                if (class_exists('PeepSoAdmin')) {
                    $peepso_url = PeepSoAdmin::PEEPSO_URL;
                }
                $url = $peepso_url . '/peepsotools-integration-json/developer_tools_peepso_offer.html';

                // Attempt contact with PeepSo.com without sslverify
                $resp = wp_remote_get(add_query_arg(array(), $url), array('timeout' => 10, 'sslverify' => FALSE));

                // In some cases sslverify is needed
                if (is_wp_error($resp)) {
                    $resp = wp_remote_get(add_query_arg(array(), $url), array('timeout' => 10, 'sslverify' => TRUE));
                }

                if (is_wp_error($resp)) {

                } else {
                    $bundle = $resp['body'];
                    set_transient('peepso_config_licenses_bundle', $bundle, 3600 * 24);
                }
            }
        }
        ?>
        </div>

        <?php if($bundle) { ?>
        <div class="twistpress_debug-side">
            <?php echo $bundle; ?>
        </div>
        <?php } ?>

        </div>
        <?php
    }

    public static function twistpress_debug_buttons($buttons){
        return $buttons;
    }

    abstract public function page();

    abstract public function page_data();
}