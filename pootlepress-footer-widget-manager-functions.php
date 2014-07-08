<?php
$health = 'ok';

if (!function_exists('check_main_heading')) {
    function check_main_heading() {
        global $health;
        if (!function_exists('woo_options_add') ) {
            function woo_options_add($options) {
                $cx_heading = array( 'name' => __('Canvas Extensions', 'pootlepress-canvas-extensions' ),
                    'icon' => 'favorite', 'type' => 'heading' );
                if (!in_array($cx_heading, $options))
                    $options[] = $cx_heading;
                return $options;
            }
        } else {	// another ( unknown ) child-theme or plugin has defined woo_options_add
            $health = 'ng';
        }
    }
}

add_action( 'admin_init', 'poo_commit_suicide' );

if(!function_exists('poo_commit_suicide')) {
    function poo_commit_suicide() {
        global $health;
        $pluginFile = str_replace('-functions', '', __FILE__);
        $plugin = plugin_basename($pluginFile);
        $plugin_data = get_plugin_data( $pluginFile, false );
        if ( $health == 'ng' && is_plugin_active($plugin) ) {
            deactivate_plugins( $plugin );
            wp_die( "ERROR: <strong>woo_options_add</strong> function already defined by another plugin. " .
                $plugin_data['Name']. " is unable to continue and has been deactivated. " .
                "<br /><br />Please contact PootlePress at <a href=\"mailto:support@pootlepress.com?subject=Woo_Options_Add Conflict\"> support@pootlepress.com</a> for additional information / assistance." .
                "<br /><br />Back to the WordPress <a href='".get_admin_url(null, 'plugins.php')."'>Plugins page</a>." );
        }
    }
}

if (!function_exists('footerWidgetCSS')) {
	function footerWidgetCSS() {
		// Footer Options
		$footer_widget_font_title = get_option('pootlepress-footer-font-title');
		$footer_widget_font_title_border = get_option('pootlepress-footer-font-title-border');
		$footer_widget_font_text = get_option('pootlepress-footer-font-text');
		$footer_widget_font_link_text = get_option('pootlepress-footer-font-link-text');
		$footer_widget_font_linkhovertext = get_option('pootlepress-footer-font-link-hovertext');
		$footer_widget_padding_tb = get_option('pootlepress-footer-padding-tb');
		$footer_widget_padding_lr = get_option('pootlepress-footer-padding-lr');
		$footer_widget_border = get_option('pootlepress-footer-border');
		$footer_widget_border_radius = get_option('pootlepress-footer-border-radius');
		$footer_widget_bg_colour = get_option('pootlepress-footer-background-colour');
		$footer_widget_area_bg_colour = get_option('pootlepress-footer-area-background-colour');
		$footer_widget_bg_image = get_option('pootlepress-footer-background-image');
		$footer_widget_bg_image_repeat = get_option('pootlepress-footer-background-image-repeat');
		$footer_widget_bg_image_position = get_option('pootlepress-footer-background-image-position');
		$footer_widget_disable_mobile = get_option('pootlepress-footer-disable-mobile');
		$footer_widget_disable_canvas = get_option('pootlepress-footer-disable-canvas');
		
		$footer_widget_full_width = get_option('woo_footer_full_width');
		
		$footer_widget_css = "";
		if ($footer_widget_disable_mobile=="true") {
			$footer_widget_css .= "@media screen and (min-width: 0px) and (max-width: 720px) {\n	#footer-widgets {\n		display: none !important;\n	}\n}\n";
		}
		if ($footer_widget_disable_canvas=="true") {
			$footer_widget_css .= "#footer {\n	display: none !important;\n}\n";
		}
		if($footer_widget_font_title) {
			$footer_widget_css .= "#footer-widgets .block .widget h3 {\n	".woo_generate_font_css( $footer_widget_font_title, 1.4 )."\n}\n";
		}
		if($footer_widget_font_title_border) {
			$footer_widget_css .= "#footer-widgets .block .widget h3 {\n	border-bottom: ".$footer_widget_font_title_border['width']."px ".$footer_widget_font_title_border['style']." ".$footer_widget_font_title_border['color'].";\n}\n";
		}

		if($footer_widget_font_text) {
			$footer_widget_css .= "#footer-widgets .block .widget p {\n	".woo_generate_font_css( $footer_widget_font_text, 1.4 )."\n}\n";
			$footer_widget_css .= "#footer-widgets .block .textwidget {\n	".woo_generate_font_css( $footer_widget_font_text, 1.4 )."\n}\n";
		}
		if($footer_widget_font_link_text) {
			$footer_widget_css .= "#footer-widgets a:link,#footer-widgets a:visited {\n	color:".$footer_widget_font_link_text."\n}\n";
		}
		if($footer_widget_font_linkhovertext) {
			$footer_widget_css .= "#footer-widgets a:hover {\n	color:".$footer_widget_font_linkhovertext."\n}\n";
		}

		
		if ($footer_widget_padding_tb || $footer_widget_padding_lr) {
			$footer_widget_css .= "#footer-widgets .block .widget {\n	padding:".$footer_widget_padding_tb."px ".$footer_widget_padding_lr."px !important;\n}\n";
		}
		if ($footer_widget_border["width"] >= 0 ) {		// v.1.0.4 - was width > 0
			$footer_widget_css .= "#footer-widgets .block .widget {\n	border:".$footer_widget_border['width']."px ".$footer_widget_border['style']." ".$footer_widget_border['color'].";\n}\n";
			if ($footer_widget_border_radius) {
				$footer_widget_css .= "#footer-widgets .block .widget {\n	border-radius:".$footer_widget_border_radius.";-moz-border-radius:".$footer_widget_border_radius.";-webkit-border-radius:".$footer_widget_border_radius.";\n}\n";
			}
		}
		
		if($footer_widget_bg_colour) {
			$footer_widget_css .= "#footer-widgets .block .widget {\n	background-color:".$footer_widget_bg_colour." !important;\n}\n";
		} else {
			$footer_widget_css .= "#footer-widgets .block .widget {\n	background: none !important;\n}\n";
		}		
		/*if($footer_widget_area_bg_colour) {
			if ($footer_widget_full_width=='true') {
				$footer_widget_css .= "#footer-widgets-container {\n	background-color:".$footer_widget_area_bg_colour." !important;\n}\n";
			} else {
				$footer_widget_css .= "#footer-widgets {\n	background-color:".$footer_widget_area_bg_colour." !important;\n}\n";
			}
		}*/
		
			
		#Check if full-width footer is enabled, if yes - set background image under footer-container id
		if ($footer_widget_full_width == 'true') {	
			$footer_widget_css .= "#footer-widgets-container {\n";
		} else {
			$footer_widget_css .= "#footer-widgets {\n";	
		}
		
		if ($footer_widget_bg_image) {
			$footer_widget_css .= "	background-image:url('".$footer_widget_bg_image."');\n";
		}		
		if ($footer_widget_bg_image_repeat) {
			$footer_widget_css .= "	background-repeat:".$footer_widget_bg_image_repeat.";\n";
		}
		if ($footer_widget_bg_image_position) {
			$footer_widget_css .= "	background-position:".$footer_widget_bg_image_position.";\n";
		}
		
		$footer_widget_css .= "}\n";		

		
		echo "<style>".$footer_widget_css."</style>";
	}
}
?>