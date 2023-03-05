<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://profiles.wordpress.org/samsonani
 * @since      1.0.0
 *
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/public
 * @author     Sam Sonani <samsonani79@gmail.com>
 */
class Wp_Slideshow_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $wpss_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $wpss_options ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->wpss_options = $wpss_options;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Slideshow_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Slideshow_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'library/css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'swiper', plugin_dir_url( __FILE__ ) . 'library/css/swiper-bundle.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-slideshow-public.css', array('bootstrap'), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Slideshow_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Slideshow_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'library/js/bootstrap.min.js', array('jquery'), $this->version, false );
		wp_enqueue_script( 'swiper', plugin_dir_url( __FILE__ ) . 'library/js/swiper-bundle.min.js', array('jquery'), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-slideshow-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Generates slider shortcode HTML   
	 *
	 * @since    1.0.0
	 */
	public function init() {

		add_shortcode('wp_slider', array(&$this, 'wp_slider_html'));
	}

	/**
	 * Generates slider shortcode HTML   
	 *
	 * @since    1.0.0
	 */
	public function wp_slider_html($args) {

		$slider_html = '';
		$slider_html .= '<h3>No images selected in Gallery, To select <a target="_blank" href="' . admin_url('admin.php?page=wp-slideshow') . '">click here</a> </h3>';

		$gallery_slides = isset($this->wpss_options['gallery_slideshow']) ? $this->wpss_options['gallery_slideshow'] : array();
		if(!empty($gallery_slides)) : 
			$slider_html = '';
			$slider_html .= '<div class="gallery">';
			$slider_html .= '<div class="swiper-container gallery-slider">';
			$slider_html .= '<div class="swiper-wrapper">'; 
			foreach($gallery_slides as $index => $attachment_id) { 
				$slider_html .= '<div class="swiper-slide"><img src="' . esc_url( wp_get_attachment_url( $attachment_id ) ) . '" alt=""></div>';							
			}
			$slider_html .= '</div>';
			$slider_html .= '<div class="swiper-button-prev"></div>';
			$slider_html .= '<div class="swiper-button-next"></div>';
			$slider_html .= '</div>';

			$slider_html .= '<div class="swiper-container gallery-thumbs">';
			$slider_html .= '<div class="swiper-wrapper">';			
			foreach($gallery_slides as $index => $attachment_id) {
				$slider_html .= '<div class="swiper-slide"><img src="' . esc_url( wp_get_attachment_thumb_url( $attachment_id ) ) . '" alt=""></div>';	
			}
			$slider_html .= '</div>';
			$slider_html .= '</div>';
			$slider_html .= '</div>';
		endif;

		return $slider_html;
	}

}
