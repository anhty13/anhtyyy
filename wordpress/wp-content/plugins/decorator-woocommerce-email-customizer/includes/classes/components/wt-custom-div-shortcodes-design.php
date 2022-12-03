<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
	exit;
}
if ( !class_exists( 'WP_Customize_Control' )){
include_once ABSPATH . 'wp-includes/class-wp-customize-control.php';
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WP_Customize_Shortcode_Control' ) ) {
	class WP_Customize_Shortcode_Control extends WP_Customize_Control {

		public function render_content() {
			?>
                        <div style="    border: 1px solid #e1e1e1;padding: 0px 7px 12px;">
			<label>
				<h3><?php echo esc_html( $this->label ); ?></h3>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span><?php echo $this->description; ?></span>
				<?php endif; ?>
                        </label></div>
			<?php
		}
	}
}