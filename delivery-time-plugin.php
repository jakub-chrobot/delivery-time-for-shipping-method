<?php
/*
Plugin Name:  Delivery time for shipping method - FLATE RATE
Plugin URI:   
Description:  Plugin to help assign time delivery to shipping method - WORKS FOR ONLY FLATE RATE
Version:      0.1
Author:       Jakub Chrobot
Author URI:   https://jakubchrobot.pl
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  czas-dostawy
*/




// display filter
add_action( 'woocommerce_after_shipping_rate', 'display', 10, 2 );

// action hook
add_action('wp_enqueue_scripts', 'style_load');


use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'delivery_plugin_options' );
function delivery_plugin_options() {
    Container::make( 'theme_options', __( 'Delivery time for shipping method' ) )
        ->add_fields( array(
            Field::make( 'separator', 'separator_one', __( 'Custom description to delivery method' ) ),
            Field::make( 'complex', 'options', __( 'Assign description to delivery method' ) )
            ->add_fields( array(
                Field::make( 'rich_text', 'comse', __( 'Description' ) ),
                Field::make( 'text', 'id', __( 'Input ID for Flate Rate method Shipping' ) ),
    ) ),
            ));
}

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}


function display ( $method, $index ) {

    $option = carbon_get_theme_option( 'options' );

    foreach ($option as $optione){
       
        if( $method->id == $optione['id'] ) {
            $desc = $optione['comse'];
            echo "<br> <small> $desc </small>";
         }

    }

}


function style_load(){

    wp_enqueue_style( 'myStyles', plugins_url( 'simple-style.css', __FILE__ ) );

}