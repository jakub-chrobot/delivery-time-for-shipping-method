<?php
/*
Plugin Name:  Delivery time for shipping method - FLATE RATE
Plugin URI:   https://github.com/jakub-chrobot/delivery-time-for-shipping-method
Description:  Plugin to help assign time delivery to shipping method - WORKS FOR ONLY FLATE RATE
Version:      0.1
Author:       Jakub Chrobot
Author URI:   https://jakubchrobot.pl
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  czas-dostawy
*/




// hook
add_action( 'woocommerce_after_shipping_rate', 'display', 10, 2 );
add_action('wp_enqueue_scripts', 'style_load');
add_action( 'carbon_fields_register_fields', 'delivery_plugin_options' );
add_action( 'after_setup_theme', 'crb_load' );



use Carbon_Fields\Container;
use Carbon_Fields\Field;

function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}


function delivery_plugin_options() {

    $delivery_id = array();
    $delivery_name = array();
    $i = 0; 

    foreach ( delivermethods() as $dm ) {
        $zone_shipping_methods = $dm->get_shipping_methods();
        foreach ( $zone_shipping_methods as $index => $method ) {
           $method_title = $method->get_title();
           $method_rate_id = $method->get_rate_id(); 
           $delivery_id[$i] = $method_rate_id;
           $delivery_name[$i] = $method_title;
           $i++;
        }
     }


     $result = array_combine($delivery_id, $delivery_name);

    Container::make( 'theme_options', __( 'Delivery time for shipping method' ) )
        ->add_fields( array(
            Field::make( 'separator', 'separator_one', __( 'Custom description to delivery method' ) ),
            Field::make( 'complex', 'options', __( 'Assign description to delivery method' ) )
            ->add_fields( array(
                Field::make( 'rich_text', 'comse', __( 'Description' ) ),
                Field::make( 'select', 'id', __( 'Choose shipping method' ) )
                ->add_options( $result )
                ) ),
                ));
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

function delivermethods() {
    $store = WC_Data_Store::load( 'shipping-zone' );
    $raw = $store->get_zones();
    foreach ( $raw as $zone ) {
       $zones[] = new WC_Shipping_Zone( $zone );
    }
    $zones[] = new WC_Shipping_Zone( 0 );
    return $zones;
 
    foreach ( delivermethods() as $zone ) {
        $zone_id = $zone->get_id();
        $zone_name = $zone->get_zone_name();
        $zone_order = $zone->get_zone_order();
        $zone_locations = $zone->get_zone_locations();
        $zone_formatted_location = $zone->get_formatted_location();
        $zone_shipping_methods = $zone->get_shipping_methods();
     }

}