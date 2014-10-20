<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Pootlepress_Footer_Widget_Manager Class
 *
 * Base class for the Pootlepress Footer Widget Manager.
 *
 * @package WordPress
 * @subpackage Pootlepress_Footer_Widget_Manager
 * @category Core
 * @author Pootlepress
 * @since 0.1.0
 *
 * TABLE OF CONTENTS
 *
 * public $token
 * public $version
 * 
 * - __construct()
 * - add_theme_options()
 * - load_localisation()
 * - check_plugin()
 * - load_plugin_textdomain()
 * - activation()
 * - register_plugin_version()
 * - load_footer_widget_manager()
 */
class Pootlepress_Footer_Widget_Manager {
	public $token = 'pootlepress-footer-widget-manager';
	public $version;
	private $file;

    private $stickyWidgetAreaDesktopEnabled;
    private $stickyWidgetAreaMobileEnabled;
    private $stickyFooterDesktopEnabled;
    private $stickyFooterMobileEnabled;

	/**
	 * Constructor.
	 * @param string $file The base file of the plugin.
	 * @access public
	 * @since  0.1.0
	 * @return  void
	 */
	public function __construct ( $file ) {
		$this->file = $file;
		$this->load_plugin_textdomain();
		add_action( 'init','check_main_heading', 0 );
		add_action( 'init', array( &$this, 'load_localisation' ), 0 );

		// Run this on activation.
		register_activation_hook( $file, array( &$this, 'activation' ) );

		// Add the custom theme options.
		add_filter( 'option_woo_template', array( &$this, 'add_theme_options' ) );

		// Lood for a method/function for the selected style and load it.
		add_action('init', array( &$this, 'load_footer_widget_manager' ) );

        add_action('wp_enqueue_scripts', array($this, 'frontend_script'));

        $this->stickyWidgetAreaDesktopEnabled = get_option('pootlepress-footer-sticky-widget-area-desktop', 'false') == 'true';
        $this->stickyWidgetAreaMobileEnabled = get_option('pootlepress-footer-sticky-widget-area-mobile', 'false') == 'true';
        $this->stickyFooterDesktopEnabled = get_option('pootlepress-footer-sticky-footer-desktop', 'false') == 'true';
        $this->stickyFooterMobileEnabled = get_option('pootlepress-footer-sticky-footer-mobile', 'false') == 'true';

	} // End __construct()

	/**
	 * Add theme options to the WooFramework.
	 * @access public
	 * @since  1.0.0
	 * @param array $o The array of options, as stored in the database.
	 */	
	public function add_theme_options ( $o ) {
		$o[] = array(
				'name' => 'Footer Customizer',
				'type' => 'subheading',
                'desc' => '',
				);
		$o[] = array(
				'name' => 'Widget Settings',
				'desc' => '',
				'id' => 'pootlepress-footer-widget-manager-notice',
				'std' => __( 'Footer Widget Manager settings will over-ride the Canvas widget settings for the footer area only', 'woothemes' ),
				'type' => 'info'
				);
		$o[] = array(
				'name' => 'Footer Widget Title',
				'desc' => 'Select typography for footer title',
				'id' => 'pootlepress-footer-font-title',
				'std' => array('size' => '13','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#999999'),
				'type' => 'typography'
				);
		$o[] = array(
				'name' => 'Footer Widget Title Bottom Border',
				'desc' => 'Specify border property for the footer widget title.',
				'id' => 'pootlepress-footer-font-title-border',
				'std' => array('width' => '1','style' => 'solid','color' => '#e6e6e6'),
				'type' => 'border'
				);		
		$o[] = array(
				'name' => 'Footer Widget Text',
				'desc' => 'Select typography for footer text',
				'id' => 'pootlepress-footer-font-text',
				'std' => array('size' => '13','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#999999'),
				'type' => 'typography'
				);
		$o[] =	array(
				'name' => 'Footer Widget Link Text Colour',
				'desc' => 'Select the colour for the text links',
				'id' => 'pootlepress-footer-font-link-text',
				'std' => '#428BCA',
				'type' => 'color'
				);
		$o[] =	array(
				'name' => 'Footer Widget Link Text Hover Colour',
				'desc' => 'Select the colour for the text hover links',
				'id' => 'pootlepress-footer-font-link-hovertext',
				'std' => '#FF4800',
				'type' => 'color'
				);
		$o[] =	array(
					'name' => 'Footer Widget Padding',
					'desc' => 'Enter an integer value i.e. 20 for the desired widget padding. Will override the padding options in "Styling and Layout > Widgets"',
					'id' => 'pootlepress-footer-padding',
					'std' => '',
					'type' =>	array(
									array(
										'id' => 'pootlepress-footer-padding-tb',
										'type' => 'text',
										'std' => '',
										'meta' => 'Top/Bottom'
										),
									array( 
										'id' => 'pootlepress-footer-padding-lr',
										'type' => 'text',
										'std' => '',
										'meta' => 'Left/Right'
										)
									)
					);
		$o[] = array(
				'name' => 'Footer Widget Border',
				'desc' => 'Specify border properties for widgets.',
				'id' => 'pootlepress-footer-border',
				'std' => array('width' => '0','style' => 'solid','color' => '#dbdbdb'),
				'type' => 'border'
				);

		$o[] = array(
				'name' => 'Footer Widget Rounded Corners',
				'desc' => 'Set amount of pixels for border radius (rounded corners). Will only show in CSS3 compatible browser.',
				'id' => 'pootlepress-footer-border-radius',
				'type' => 'select',
				'options' => array('1px','2px','3px','4px','5px','6px','7px','8px','9px','10px','11px','12px','13px','14px','15px','16px','17px','18px','19px','20px')
				);
					
		$o[] =	array(
				'name' => 'Footer Widget Background Colour',
				'desc' => 'Select the background colour you want for footer widgets.',
				'id' => 'pootlepress-footer-background-colour',
				'std' => '',
				'type' => 'color'
				);
		/*$o[] =	array(
				'name' => 'Footer Widget Area Background Colour',
				'desc' => 'Select the background colour you want for your footer widget area. This will override the background colour in "styling and layout > widgets". You can also set the background colour of the full-width footer widget area in the "full-width styling and layout > full width layout" options',
				'id' => 'pootlepress-footer-area-background-colour',
				'std' => '',
				'type' => 'color'
				);*/
		$o[] =	array(
				'name' => 'Footer Widget Background Colour & Area Colour',
				'std' => 'Select the background colour and widget area colour in "Theme Options > Styling & Options > Full Width"',
                'desc' => '',
				'id' => 'pootlepress-footer-background-colour',
				'type' => 'info'
				);
		$o[] = array(
				'name' => 'Footer Widget Background Image',
 				'desc' => 'Upload a background image for your footer widget area',
 				'id' => 'pootlepress-footer-background-image',
				'std' => '',
				'type' => 'upload'
				);
		$o[] = array(
				'name' => 'Background Image Repeat',
				'desc' => 'Select how you want your background image to display.',
				'id' => 'pootlepress-footer-background-image-repeat',
				'type' => 'select',
				'options' => array( 'No Repeat' => 'no-repeat', 'Repeat' => 'repeat','Repeat Horizontally' => 'repeat-x', 'Repeat Vertically' => 'repeat-y' )
				);
		$o[] = array(
				'name' => 'Background image position',
				'desc' => 'Select how you would like to position the background',
				'id' => 'pootlepress-footer-background-image-position',
				'std' => 'top left',
				'type' => 'select',
				'options' => array( 'top left', 'top center', 'top right', 'center left', 'center center', 'center right', 'bottom left', 'bottom center', 'bottom right' )
				);				
		$o[] = array(
				'name' => 'Disable Footer Widget Area on mobile',
				'desc' => 'Disable the footer widget area on mobile',
 				'id' => 'pootlepress-footer-disable-mobile',
  				'std' => 'true',
				'type' => 'checkbox'
				);
		$o[] = array(
				'name' => 'Disable Canvas Footer',
				'desc' => 'Disable the standard Canvas footer at the very bottom of the page',
 				'id' => 'pootlepress-footer-disable-canvas',
  				'std' => 'true',
				'type' => 'checkbox'
				);

        $o[] = array(
            'name' => 'Make the footer widget area sticky on desktop',
            'desc' => 'full width footer must be enabled in Canvas',
            'id' => 'pootlepress-footer-sticky-widget-area-desktop',
            'std' => 'false',
            'type' => 'checkbox'
        );
        $o[] = array(
            'name' => 'Make the footer widget area sticky on mobile',
            'desc' => 'full width footer must be enabled in Canvas',
            'id' => 'pootlepress-footer-sticky-widget-area-mobile',
            'std' => 'false',
            'type' => 'checkbox'
        );
        $o[] = array(
            'name' => 'Make the footer sticky on desktop',
            'desc' => '',
            'id' => 'pootlepress-footer-sticky-footer-desktop',
            'std' => 'false',
            'type' => 'checkbox'
        );
        $o[] = array(
            'name' => 'Make the footer sticky on mobile',
            'desc' => '',
            'id' => 'pootlepress-footer-sticky-footer-mobile',
            'std' => 'false',
            'type' => 'checkbox'
        );
		return $o;
	} // End add_theme_options()
	
	/**
	 * Load the plugin's localisation file.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_localisation () {
		load_plugin_textdomain( $this->token, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation()
	
	/**
	 * Load the plugin textdomain from the main WordPress "languages" folder.
	 * @access public
	 * @since  1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = $this->token;
	    // The "plugin_locale" filter is also used in load_plugin_textdomain()
	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	 
	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain()

	/**
	 * Run on activation.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * Register the plugin's version.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	private function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( $this->token . '-version', $this->version );
		}
	} // End register_plugin_version()

    public function frontend_script() {
        wp_enqueue_script('footer-customizer', plugin_dir_url($this->file) . '/scripts/footer-customizer.js', array('jquery'));

        $isFooterFullWidth = get_option('woo_footer_full_width') == 'true';

        wp_localize_script('footer-customizer', 'PPFooterCustomizer', array(
            'isFooterFullWidth' => $isFooterFullWidth,
            'stickyFooterDesktop' => $this->stickyFooterDesktopEnabled,
            'stickyFooterMobile' => $this->stickyFooterMobileEnabled,
            'stickyWidgetAreaDesktop' => $this->stickyWidgetAreaDesktopEnabled,
            'stickyWidgetAreaMobile' => $this->stickyWidgetAreaMobileEnabled
        ));
    }

	/**
	 * Add CSS to header
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_footer_widget_manager () {
		add_action('wp_head', array($this, 'option_css'));
	} // End load_footer_widget_manager()

    public function option_css() {
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

        $stickyCss = '';
        if ($this->stickyFooterDesktopEnabled) {
            $stickyCss .= "@media only screen and (min-width: 768px) {\n";

            $footerElement = '';
            if ($footer_widget_full_width == 'true') {
                $footerElement = '#footer-container';
            } else {
                $footerElement = '#footer';
            }

            $stickyCss .= "$footerElement {\n";
            $stickyCss .= "\t" . "position: fixed;\n";
            $stickyCss .= "\t" . "bottom: 0;\n";
            $stickyCss .= "\t" . "width: 100%;\n";
            $stickyCss .= "\t" . "z-index: 9100;\n"; //#navigation z-index is 9000;
            $stickyCss .= "}\n";

            $stickyCss .= "}\n";
        }

        if ($this->stickyWidgetAreaDesktopEnabled) {
            if ($footer_widget_full_width == 'true') {
                $stickyCss .= "@media only screen and (min-width: 768px) {\n";

                $stickyCss .= "#footer-widgets-container {\n";
                $stickyCss .= "\t" . "position: fixed;\n";
                $stickyCss .= "\t" . "bottom: 0;\n";
                $stickyCss .= "\t" . "width: 100%;\n";
                $stickyCss .= "\t" . "z-index: 9100;\n"; //#navigation z-index is 9000;
                $stickyCss .= "}\n";

                // sticky the bottom part too
                $stickyCss .= "#footer-container {\n";
                $stickyCss .= "\t" . "position: fixed;\n";
                $stickyCss .= "\t" . "bottom: 0;\n";
                $stickyCss .= "\t" . "width: 100%;\n";
                $stickyCss .= "\t" . "z-index: 9100;\n"; //#navigation z-index is 9000;
                $stickyCss .= "}\n";

                $stickyCss .= "}\n";

            }
        }

        if ($this->stickyFooterMobileEnabled) {

            $footerElement = '';
            if ($footer_widget_full_width == 'true') {
                $footerElement = '#footer-container';
            } else {
                $footerElement = '#footer';
            }

            $stickyCss .= "@media only screen and (max-width: 767px) {\n";

            $stickyCss .= "$footerElement {\n";
            $stickyCss .= "\t" . "position: fixed;\n";
            $stickyCss .= "\t" . "bottom: 0;\n";
            $stickyCss .= "\t" . "width: 100%;\n";
            $stickyCss .= "\t" . "left: 0;\n";
            $stickyCss .= "\t" . "z-index: 9100;\n"; //#navigation z-index is 9000;
            $stickyCss .= "}\n";

            $stickyCss .= "}\n";
        }

        if ($this->stickyWidgetAreaMobileEnabled) {
            if ($footer_widget_full_width == 'true') {
                $stickyCss .= "@media only screen and (max-width: 767px) {\n";

                $stickyCss .= "#footer-widgets-container {\n";
                $stickyCss .= "\t" . "position: fixed;\n";
                $stickyCss .= "\t" . "bottom: 0;\n";
                $stickyCss .= "\t" . "width: 100%;\n";
                $stickyCss .= "\t" . "z-index: 9100;\n"; //#navigation z-index is 9000;
                $stickyCss .= "}\n";

                // sticky the bottom part too
                $stickyCss .= "#footer-container {\n";
                $stickyCss .= "\t" . "position: fixed;\n";
                $stickyCss .= "\t" . "bottom: 0;\n";
                $stickyCss .= "\t" . "width: 100%;\n";
                $stickyCss .= "\t" . "z-index: 9100;\n"; //#navigation z-index is 9000;
                $stickyCss .= "}\n";

                $stickyCss .= "}\n";

            }
        }

        echo "<style>\n".
            $footer_widget_css.
            $stickyCss .
            "</style>\n";
    }
} // End Class


