<?php
/*
Plugin Name: WooCommerce NIP
Description: Dodaje pole NIP w formularzu zamówienia.
Version: 1.0
Author: Hostingowi.pl | Modified by Mieszalski Mateusz
Author URI: https://hostingowi.pl
Requires PHP: 7.1
License: GPLv3
*/

// Dodaj pole NIP na stronie płatności
add_action( 'woocommerce_after_checkout_billing_form', 'nip_field_checkout' );
function nip_field_checkout() {
    echo '<div class="nip-field">';
    woocommerce_form_field( 'nip', array(
        'type'        => 'text',
        'class'       => array('form-row-wide'),
        'label'       => __('NIP', 'woocommerce'),
        'placeholder' => __('Wpisz NIP', 'woocommerce'),
        'required'    => false,
    ), '');
    echo '</div>';
}

// Walidacja pola NIP
add_action('woocommerce_checkout_process', 'nip_field_validation');
function nip_field_validation() {
    if ( ! empty( $_POST['nip'] ) && ! check_nip( $_POST['nip'] ) ) {
        wc_add_notice( __( 'Nieprawidłowy numer NIP.', 'woocommerce' ), 'error' );
    }
}

// Zapisz wartość pola NIP w zamówieniu
add_action( 'woocommerce_checkout_update_order_meta', 'nip_field_update_order_meta' );
function nip_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['nip'] ) ) {
        update_post_meta( $order_id, 'NIP', sanitize_text_field( $_POST['nip'] ) );
    }
}

// Wyświetl wartość pola NIP w szczegółach zamówienia
add_action( 'woocommerce_order_details_after_order_table', 'nip_field_order_details' );
function nip_field_order_details( $order ) {
    $nip = get_post_meta( $order->get_id(), 'NIP', true );
    if ( $nip ) {
        echo '<p><strong>' . __( 'NIP', 'woocommerce' ) . ':</strong> ' . $nip . '</p>';
    }
}
