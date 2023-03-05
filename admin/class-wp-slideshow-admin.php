<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://profiles.wordpress.org/samsonani
 * @since      1.0.0
 *
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Slideshow
 * @subpackage Wp_Slideshow/admin
 * @author     Sam Sonani <samsonani79@gmail.com>
 */
class Wp_Slideshow_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $wpss_options ) {

		$this->wpss_options = $wpss_options;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'library/css/jquery-ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-slideshow-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_media();
		wp_register_script( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'library/js/jquery-ui.js', array( 'jquery' ), $this->version, true );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-slideshow-admin.js', array( 'jquery', 'jquery-ui' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name );
	}

	/**
	 * Register wp slideshow admin menu. 
	 * 
	 * @since	1.0.0
	 */
	public function register_admin_menu() {
		add_menu_page(
			__( 'WP SlideShow' ),
			'WP SlideShow',
			'manage_options',
			'wp-slideshow',
			array( &$this, 'wp_slideshow_main' ),
			'dashicons-slides',
			6
		);
	}

	public function wp_slideshow_main() { ?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Admin Page Test', $this->plugin_name ); ?></h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'wpss_options_group' ); ?>
				<?php 
					do_settings_sections( 'wpss_general_section' ); 
					submit_button( 'Save Settings' );
				?>
			</form>
		<?php
	}

	/**
	 * Register settings for admin menu. 
	 * 
	 * @since	1.0.0
	 */
	public function register_admin_init() {
		$args = array(
			'sanitize_callback' => array( &$this, 'wpss_sanitize_fields' ),
			'default' => NULL
		);
    	register_setting( 'wpss_options_group', 'wpss_options', $args );

		add_settings_section(
			'wpss_general_setting',
			__( 'General Setting', $this->plugin_name ),
			array(),
			'wpss_general_section'
		);

		add_settings_field(
			'gallery_slideshow',
			__('Slider Images', $this->plugin_name ),
			array( &$this, 'gallery_slideshow_field_call'),
			'wpss_general_section',
			'wpss_general_setting', 
			[
				'label_for' => 'gallery_slideshow'
			]
		);
	}

	public function gallery_slideshow_field_call($args) {
		$values = isset($this->wpss_options[$args['label_for']]) ? $this->wpss_options[$args['label_for']] : array(); ?>
		<div class="wpss-input">
			<div class="wpss-gallery">
				<div class="wpss-gallery-main">	
					<div class="wpss-gallery-attachments">
						<?php
						if(!empty($values)) {
							foreach ($values as $key => $attachment_id) { ?>
								<div class="wpss-gallery-attachment" data-id="<?php esc_attr_e( $attachment_id ); ?>">
									<input type="hidden" value="<?php esc_attr_e( $attachment_id ); ?>" name="wpss_options[<?php esc_attr_e( $args['label_for'] ); ?>][]">
									<div class="margin">
										<div class="thumbnail">
											<img src="<?php echo esc_url( wp_get_attachment_thumb_url( $attachment_id ) ); ?>" alt="thumbnail image">
										</div>
									</div>
									<div class="actions">
										<a class="wpss-icon -cancel dark wpss-gallery-remove" href="#" data-id="<?php esc_attr_e( $attachment_id ); ?>" title="Remove"><span class="dashicons dashicons-remove"></span></a>
									</div>
								</div>
							 <?php
							}
						} ?>
					</div>
					<div class="wpss-gallery-toolbar">
						<ul>
							<li>
								<input id="wpss-button" data-name="wpss_options[<?php esc_attr_e( $args['label_for'] ); ?>][]" type="button" class="button" value="Upload Image" />
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Sanitize fields callback
	 */
	public function wpss_sanitize_fields($input) {
		
		$new_input = array();
		if(isset($input) && !empty($input)) {
			foreach ($input as $key => $value) {
				if(is_array($value)) {
					$new_input[$key] = array_map("sanitize_text_field",$value);
				}
			}

		}

		return $input;
	}


}
