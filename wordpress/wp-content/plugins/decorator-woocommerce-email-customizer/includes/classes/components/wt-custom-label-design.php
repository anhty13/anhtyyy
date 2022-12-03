<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
	exit;
}


if ( !class_exists( 'WP_Customize_Control' )){
include_once ABSPATH . 'wp-includes/class-wp-customize-control.php';
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_labels_Control' ) ) {
    
	class WP_Customize_labels_Control extends WP_Customize_Control {
		public $type = 'labels';

		public function enqueue() {
                    //wp_enqueue_style( 'customizer-toggle-switch-control-css', RP_DECORATOR_PLUGIN_URL . '/assets/css/customizer-toggle-switch-control.css', array(), RP_DECORATOR_VERSION );

		}

		public function render_content() {
			?>
                    <label>
                    <hr>
                    <div style="display:flex;flex-direction: row;justify-content: flex-start; align-items: center;background-color: #f5f5f5;">
                    <span class="customize-control-title" style="flex: 2 0 0; vertical-align: middle; margin:10px 0;text-align: center;font-size: 16px"><?php echo esc_html( $this->label ); ?></span>
                    </div>
                    <hr>
                    </label>
		<?php
		}
	}
}