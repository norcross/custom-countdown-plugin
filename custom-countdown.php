<?php
/*
Plugin Name: Custom Countdown
Plugin URI: http://andrewnorcross.com/plugins/
Description: Loads a 'coming soon' page for non-logged in users
Version: 1.0
Author: norcross
Author URI: http://andrewnorcross.com/
License: GPL v2

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


*/
class ccdPlugin
{

	/**
	 * This is our constructor
	 *
	 * @return ccdPlugin
	 */
	public function __construct() {
		add_action					( 'template_redirect',		array( $this, 'template_redirect'	) );
		add_action					( 'admin_menu',				array( $this, 'ccd_settings'		) );
		add_action					( 'admin_init', 			array( $this, 'reg_settings'		) );
		add_action					( 'admin_enqueue_scripts',	array( $this, 'admin_scripts'		) );
		add_action					( 'wp_enqueue_scripts',		array( $this, 'front_scripts'		) );
	}


	/**
	 * build out settings page
	 *
	 * @return ccdPlugin
	 */


	public function ccd_settings() {
	    add_submenu_page('options-general.php', 'Countdown Options', 'Countdown Options', 'manage_options', 'custom-countdown', array( $this, 'ccd_setup_display' ));
	}

	/**
	 * Register settings
	 *
	 * @return ccdPlugin
	 */


	public function reg_settings() {
		register_setting( 'ccd_options', 'ccd_options');		

	}


	/**
	 * Display main options page structure
	 *
	 * @return ccdPlugin
	 */
	 
	public function ccd_setup_display() { ?>
	
		<div class="wrap">
    	<div class="icon32" id="icon-ccd"><br></div>
		<h2>Custom Countdown Settings</h2>
        
	        <div class="ccd_options">
            	<div class="ccd_form_text">

                </div>
                
                <div class="ccd_form_options">
	            <form method="post" action="options.php">
			    <?php
                settings_fields( 'ccd_options' );
				$ccd_options	= get_option('ccd_options');

				// make a default date 2 weeks from now
				$default_t	= time() + (14 * 24 * 60 * 60);
				$twoweeks	= date('m/d/Y', $default_t);

				// make defaults
				$ex_banner 	= plugins_url('/layout/images/banner-default.png', __FILE__);
				$ex_title	= get_bloginfo('name').' | '.get_bloginfo('description');

				// set defaults for fields
				$launch		= (isset($ccd_options['launch']) ? $ccd_options['launch'] : $twoweeks);
				$title		= (isset($ccd_options['title']) ? $ccd_options['title'] : $ex_title );
				$banner		= (isset($ccd_options['banner']) ? $ccd_options['banner'] : $ex_banner);

				?>

				<table class="form-table ccd-table">
				<tbody>

				<tr valign="top" class="ccd_choice">
					<th scope="row">
						<label for="ccd_options[launch]">Launch Date</label>
					</th>
					<td>
						<input type="text" id="ccd_launch" name="ccd_options[launch]" class="timepicker" value="<?php echo $launch; ?>" />
					</td>
				</tr>

				<tr valign="top" class="ccd_choice">
					<th scope="row">
						<label for="ccd_options[title]">Title Tag</label>
					</th>
					<td>
						<input type="text" id="ccd_title" name="ccd_options[title]" class="regular-text" value="<?php echo esc_attr($title); ?>" />
						<p class="description">Enter the text you'd like to display in the browser title tag.</p>
					</td>
				</tr>

				<tr valign="top" class="ccd_choice">
					<th scope="row">
						<label for="ccd_options[banner]">Banner Image</label>
					</th>
					<td>
						<input type="text" id="ccd_banner" name="ccd_options[banner]" class="regular-text code" value="<?php echo esc_url($banner); ?>" />
						<p class="description">Enter the full URL of the banner image you want to use.</p>
					</td>
				</tr>


				</tbody>
				</table>	
               
    
	    		<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
				</form>
                </div>
    
            </div>

        </div>    
	
	<?php }

	/**
	 * load scripts adn style for post or page editor
	 *
	 * @return ccdPlugin
	 */


	public function admin_scripts() {
		$current_screen = get_current_screen();
		if ( 'settings_page_custom-countdown' == $current_screen->base ) {
			wp_enqueue_style( 'ccd-admin', plugins_url('/lib/css/ccd-admin.css', __FILE__), array(), null, 'all' );

			wp_enqueue_script( 'jquery-ui-core');
			wp_enqueue_script( 'jquery-ui-datepicker');
			wp_enqueue_script( 'ccd-admin', plugins_url('/lib/js/ccd.admin.js', __FILE__) , array('jquery'), null, true );
		}
	}


	/**
	 * load scripts for front end
	 *
	 * @return ccdPlugin
	 */


	public function front_scripts() {

		if(!is_admin() && is_user_logged_in())
			return;

		wp_enqueue_script( 'countdown', plugins_url('/layout/js/jquery.countdown.js', __FILE__) , array('jquery'), null, true );
    	wp_enqueue_script( 'ccd-init', plugins_url('/layout/js/ccd.init.js', __FILE__) , array('jquery'), null, true );

	}

	/**
	 * display layout for page
	 *
	 * @return ccdPlugin
	 */


	public function layout() {
		// grab some variables
		$ccd_options	= get_option('ccd_options');

		// make a default date 2 weeks from now
		$default_t	= time() + (14 * 24 * 60 * 60);
		$twoweeks	= date('m/d/Y', $default_t);

		// make defaults
		$ex_banner 	= plugins_url('/layout/images/banner-default.png', __FILE__);
		$ex_title	= get_bloginfo('name').' | '.get_bloginfo('description');

		// set defaults for fields
		$launch		= (isset($ccd_options['launch']) ? $ccd_options['launch'] : $twoweeks);
		$title		= (isset($ccd_options['title'])  ? $ccd_options['title']  : $ex_title );
		$banner		= (isset($ccd_options['banner']) ? $ccd_options['banner'] : $ex_banner);	
		?>

		<!DOCTYPE html>
		<html>
		    <head>
		        <meta charset="utf-8" />
		        <title><?php echo $title; ?></title>
		        
		        <!-- Our CSS stylesheet file -->
		        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" />
		        <link rel="stylesheet" href="<?php echo plugins_url( '/layout/style.css', __FILE__ ); ?>" type="text/css" />
		        
		        
		    <!--[if lt IE 9]>
		        <link rel="stylesheet" type="text/css" href="<?php echo plugins_url( '/layout/ie.css', __FILE__ ); ?>" />
		    <![endif]-->
		        <?php wp_head(); ?>
		    </head>

		<body>
		<div id="wrapper">
		<header>
		    <h1 class="logo"><img src="<?php echo $banner; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>"></h1>
		</header>

			<div id="countdown"></div>

			<p id="note"></p>

				<footer>
				<input type="hidden" id="ccd_launch" value="<?php echo $launch; ?>">	
				<?php wp_footer() ; ?>	
		        </footer>
			
			</div> <!-- closing wrapper started in header -->
		    </body>
		</html>	

	<?php }

	/**
	 * Check for user login and load coming soon
	 *
	 * @return ccdPlugin
	 */


	public function template_redirect() {
		if(!is_admin() && is_user_logged_in())
			return;

		$this->layout();
		// include (plugins_url( '/ccd-layout/ccd-page.php', __FILE__ ));
        exit;

	}



/// end class
}


// Instantiate our class
$ccdPlugin = new ccdPlugin();