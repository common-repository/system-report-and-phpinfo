<?php

class TwistPress_Developer_Tools_Page_PHPinfo extends TwistPress_Developer_Tools_Page
{
	public $file_extension = 'html';
	public $file_mime = 'text/html';

	public function __construct()
	{
		$this->title 		= 'PHPinfo()';
		$this->description	= __('Gain full insight into your hosting environment with phpinfo() convieniently embedded at your fingertips. Exports as HTML file.', 'twistpress_debug');
	}

	public function page()
	{
		wp_enqueue_style('twistpress_debug_phpinfo', TwistPress_Developer_Tools::assets_path().'css/phpinfo.css');

		$this->page_start('phpinfo');

		// output phpinfo() into a variable and extract contents of the body tag
		$phpinfo = $this->page_data();
		preg_match("/<body[^>]*>(.*?)<\/body>/is", $phpinfo, $matches);

		echo '<div id="systemreport-phpinfo">', $matches[1], '</div>';

		$this->page_end();
	}

	public function page_data()
	{
		ob_start();
		phpinfo();
		return ob_get_clean();
	}
}

// EOF