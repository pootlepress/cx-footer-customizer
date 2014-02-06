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
 * private $_menu_style
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
	private $_menu_style;

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
	} // End __construct()

	/**
	 * Add theme options to the WooFramework.
	 * @access public
	 * @since  1.0.0
	 * @param array $o The array of options, as stored in the database.
	 */	
	public function add_theme_options ( $o ) {
		$o[] = array(
				'name' => 'Footer Widget Manager', 
				'type' => 'subheading'
				);
		$o[] = array(
				'name' => 'Widget Settings',
				'desc' => '',
				'id' => 'pootlepress-footer-widget-manager-notice',
				'std' => __( 'Footer Widget Manager settings will over-rise the Canvas widget settings for the footer area only', 'woothemes' ),
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
		$o[] =	array(
				'name' => 'Footer Widget Area Background Colour',
				'desc' => 'Select the background colour you want for your footer widget area. This will override the background colour in "styling and layout > widgets". You can also set the background colour of the full-width footer widget area in the "full-width styling and layout > full width layout" options',
				'id' => 'pootlepress-footer-area-background-colour',
				'std' => '',
				'type' => 'color'
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

	/**
	 * Add CSS to header
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_footer_widget_manager () {
		add_action('wp_head', 'footerWidgetCSS');
	} // End load_footer_widget_manager()
} // End Class


