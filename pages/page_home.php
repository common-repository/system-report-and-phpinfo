<?php

class TwistPress_Developer_Tools_Page_Home extends TwistPress_Developer_Tools_Page
{
	public function __construct()
	{
		$this->title='Developer Tools <nobr><small>by TwistPress</small></nobr>';
	}

	public function page()
	{

		$this->page_start();
		echo $this->page_data();
		$this->page_end('');
	}

	public function page_data()
	{
        $TwistPressDeveloperTools = TwistPress_Developer_Tools::get_instance();
		ob_start();
		?>
		<div id="welcome-panel" class="welcome-panel">
			<div class="welcome-panel-content">
				<h2>
					<?php _e('Welcome to Developer Tools by TwistPress!','twistpress_debug');?>
				</h2>

				<p class="about-description">
					<?php _e('Use the tabs on top to use the available functions:','twistpress_debug');?>
				</p>
				<?php
				foreach($TwistPressDeveloperTools->pages_config as $page) {
					if('home' == $page) { continue; }
					?>
					<p>
					<h3>
						<a href="<?php menu_page_url( 'twistpress_debug_'.$page);?>"><?php echo $TwistPressDeveloperTools->pages[$page]->title;?></a>
					</h3>
					<?php echo $TwistPressDeveloperTools->pages[$page]->description;?>
					</p>
					<?php
				}
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function twistpress_debug_buttons($buttons)
	{
		return array();
	}
}

// EOFpage_phpinfo.php