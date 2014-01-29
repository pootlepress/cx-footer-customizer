<?php
if (!function_exists('footerWidgetCSS')) {
	function footerWidgetCSS() {
		// Footer Options
		$footer_widget_font_title = get_option('pootlepress-footer-font-title');
		$footer_widget_font_text = get_option('pootlepress-footer-font-text');
		$footer_widget_font_link_text = get_option('pootlepress-footer-font-link-text');
		$footer_widget_font_linkhovertext = get_option('pootlepress-footer-font-link-hovertext');
		$footer_widget_bg_colour = get_option('pootlepress-footer-background-colour');
		$footer_widget_bg_image = get_option('pootlepress-footer-background-image');
		$footer_widget_bg_image_repeat = get_option('pootlepress-footer-background-image-repeat');
		$footer_widget_bg_image_position = get_option('pootlepress-footer-background-image-position');
		$footer_widget_disable_mobile = get_option('pootlepress-footer-disable-mobile');
		$footer_widget_disable_canvas = get_option('pootlepress-footer-disable-canvas');
		
		$footer_widget_css = "";
		if ($footer_widget_disable_mobile=="true") {
			$footer_widget_css .= "@media screen and (min-width: 0px) and (max-width: 720px) {\n	#footer-widgets {\n		display: none !important;\n	}\n}\n";
		}
		if ($footer_widget_disable_canvas=="true") {
			$footer_widget_css .= "#footer {\n	display: none !important;\n}\n";
		}
		if($footer_widget_font_title) {
			$footer_widget_css .= ".widget h3 {\n	".woo_generate_font_css( $footer_widget_font_title, 1.4 )."\n}\n";
		}
		if($footer_widget_font_text) {
			$footer_widget_css .= ".widget {\n	".woo_generate_font_css( $footer_widget_font_text, 1.4 )."\n}\n";
		}
		if($footer_widget_font_link_text) {
			$footer_widget_css .= ".widget a:link, a:visited {\n	color:".$footer_widget_font_link_text."\n}\n";
		}
		if($footer_widget_font_linkhovertext) {
			$footer_widget_css .= ".widget a:hover {\n	color:".$footer_widget_font_linkhovertext."\n}\n";
		}
		
		#All Background CSS goes under #footer-widgets
		$footer_widget_css .= "#footer-widgets {\n";		
		if($footer_widget_bg_colour) {
			$footer_widget_css .= "	background-color:".$footer_widget_bg_colour.";\n";
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
		
		$footer_widget_css .= "}";
		
		echo "<style>".$footer_widget_css."</style>";
	}
}
?>