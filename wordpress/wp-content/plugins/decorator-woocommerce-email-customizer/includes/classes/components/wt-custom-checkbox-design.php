<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
	exit;
}


if ( !class_exists( 'WP_Customize_Control' )){
include_once ABSPATH . 'wp-includes/class-wp-customize-control.php';
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Toggleswitch_Control' ) ) {
    
	class WP_Customize_Toggleswitch_Control extends WP_Customize_Control {
		public $type = 'toggleswitch';

		public function enqueue() {
                    wp_enqueue_style( 'customizer-toggle-switch-control-css', RP_DECORATOR_PLUGIN_URL . '/assets/css/customizer-toggle-switch-control.css', array(), RP_DECORATOR_VERSION );

		}

		public function render_content() {
			?>
			<label>
				<div style="display:flex;flex-direction: row;justify-content: flex-start; align-items: center;">
					<span class="customize-control-title" style="flex: 2 0 0; vertical-align: middle; margin:20px 0;"><?php echo esc_html( $this->label ); ?></span>
                                        <div class="checkbox switch">
                                            <label>
                                              <input type="checkbox" class="switch-control" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?>/>
                                              <span class="switch-label"><?php echo $this->description; ?></span>
                                            </label>
                                         </div>
				</div>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>
			</label>

			<?php
		}
	}
}