<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Mockup WooCommerce Product class
 *
 * @class RP_Decorator_Mockup_Product
 * @package Decorator
 * @author WebToffee
 */
if (!class_exists('RP_Decorator_Mockup_Product')) {

class RP_Decorator_Mockup_Product
{

    /**
     * Constructor class
     *
     * @access public
     * @param int $id
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;

    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_sku()
    {
        return '';
    }

    public function exists()
    {
        return true;
    }

    public function is_downloadable()
    {
        return false;
    }

    public function get_image_id()
    {
        return 0;
    }

    public function is_visible()
    {
        return true;
    }

}
}
