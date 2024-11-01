<?php

class TwistPress_Developer_Tools_Page_PeepSo_Log extends TwistPress_Developer_Tools_Page
{
    public function __construct()
    {
        $this->title 		= 'PeepSo Log';
        $this->description	= __('Preview PeepSo logs in real time', 'twistpress_debug');
    }

    public function page()
    {
        $this->page_start('peepso_log');

        $hash = md5(time());
        if(class_exists('PeepSo')) {
            ?>

            <script>
                jQuery(function ($) {
                    function getTail() {
                        $.get('admin-ajax.php?action=peepso_log&hash=<?php echo $hash;?>').done(function (data) {
                            var $tail = $('#tail').append(data),
                                $btn = $('#peepso_log_auto_scroll');
                            if ($btn[0].checked) {
                                $tail[0].scrollTop = $tail[0].scrollHeight;
                            }
                        }).always(function () {
                            setTimeout(getTail, 1000);
                        });
                    }

                    getTail();
                });
            </script>
            <pre id="tail" style="width:100%;height:800px;overflow:scroll"></pre>
            <?php
        } else {
            echo __('PeepSo needs to be installed and activated', 'twistpress_debug');
        }
        $this->page_end();
    }

    public function page_data()
    {
        ob_start();
        if(class_exists('PeepSo') && PeepSo::is_admin()) {
            readfile(PeepSo::get_peepso_dir() . 'peepso.log');

        }

        return ob_get_clean();
    }

    public static function twistpress_debug_buttons($buttons)
    {
        unset($buttons['reload']);
        $buttons['autoscroll']='<form><input type="checkbox" id="peepso_log_auto_scroll" checked /> ' .  __('Auto scroll','twistpress_debug').'</form>';
        return $buttons;
    }
}

// EOF