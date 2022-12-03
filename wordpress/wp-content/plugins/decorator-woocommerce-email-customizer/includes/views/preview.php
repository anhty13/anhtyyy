<?php

/**
 * View for email preview
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

    <head>

        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width" />

        <title><?php echo __('Decorator', 'decorator-woocommerce-email-customizer'); ?></title>

        <style type="text/css" id="rp_decorator_custom_css">
            <?php echo RP_Decorator_Customizer::opt('custom_css'); ?></style>

    </head>

    <body>

        <div id="rp_decorator_preview_wrapper" style="display: table; margin: 70px auto;">

            <?php RP_Decorator_Preview::print_preview_email(); ?>

        </div>

        <?php wp_footer(); ?>

    </body>

</html>
