<?php
/**
 * Plugin name: The Drops – Customizations
 * Description: All customizations fall in this plugin
 * Author: TeknoFlair
 * Author URI: https://teknoflair.com
 * Version: 1.0.0
 * Text Domain: thedrops-customizations
 */

defined('ABSPATH') || exit;

//1.3.2024 Chekcout discount
function droppoints_discount_shortcode() {
    ob_start();

    $points_type = 'droppoints';

    $discount_points = 0;
    $cart_fees = []; 

    if( function_exists( 'WC' ) && isset( WC()->cart ) && ! is_null( WC()->cart ) ) {

        $cart_fees = WC()->cart->get_fees();
    }
    
    if( $cart_fees ) {

        foreach( $cart_fees as $key => $cart_fee ) {

            if( stripos( $key, $points_type ) !== false ) {

                $discount_points = $cart_fee->points;
            }
        }
        if( $discount_points ) {

            echo '<input style="display:none;" type="text" id="droppoints-input" value="'.$discount_points.'" />';
            return ob_get_clean();
        }
    }
    $current_user_id = get_current_user_id();
    $current_points = gamipress_get_user_points( $current_user_id, $points_type );
    $ex_opts = get_post_meta( 2704, '_gamipress_wc_partial_payments_conversion', true );
    $ex_points = isset( $ex_opts['points'] ) ? ( float ) $ex_opts['points'] : 0;
    $ex_value = isset( $ex_opts['money'] ) ? ( float ) $ex_opts['money'] : 0;
    $curr_symbol = get_woocommerce_currency_symbol();
    // $field_bg_color = ! is_checkout() ? '#fff' : '#f6f7fc';
    $border_rads = is_checkout() ? '4px' : '2px';
    ?>
    <div style="width: 100%; font-family: Arial, sans-serif; font-size: 14px;">
        <div id="droppoints-container" style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; padding: 8px 0 0 0;">
            <h4 style="font-size:19px;font-weight:700;color:#132e5d;font-family: 'Satoshi-Variable'">DropPoints discount</h4>
            <span id="toggle-arrow" style="font-size: 14px; transform: rotate(0deg); transition: transform 0.2s; color: #1a2a57;"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" aria-hidden="true" class="wc-block-components-panel__button-icon" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg></span>
        </div>
        <div id="droppoints-content" style="margin-top: -4px; display: none;">
            <p style="color: rgba(19, 46, 93, 0.4);font-size: 16px;font-weight: 500;font-family: 'Satoshi-Variable'">Your account balance is: <?php echo $current_points ?> DP</p>
            <div style="position: relative; width: 100%;">
            <input 
                type="number" 
                id="droppoints-input"
                placeholder="100"
                style="width: 100%;height: 46px;padding: 8px 85px 8px 10px; border-radius: <?php echo $border_rads; ?>;font-family: 'Satoshi-Variable';font-size: 18px;background-color: #fff;font-weight: 500; " 
                min="0" 
                max="<?php echo $current_points; ?>" 
            />
                <button 
                    id="droppoints-apply" 
                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); padding: 7px 18px; background-color: #6184c2; color: #f7ece3 ; border: none; border-radius: 2px; font-size: 14px; cursor: pointer;font-family: Satoshi-Variable;font-weight: 700;" 
                >
                    Apply
                </button>
            </div>
            <p style="color: rgba(19, 46, 93, 0.4);font-size: 16px;font-weight: 500;margin-top:0px;font-family: 'Satoshi-Variable'"><span class="drop_info"><?php echo $ex_points; ?></span> DropPoints = <?php echo $curr_symbol; ?><span class="drop_amount"><?php echo  str_replace( '.', ',', number_format($ex_value,2) ); ?></span> discount</p>
        </div>
    </div>    
    <?php
    return ob_get_clean();
}
add_shortcode('droppoints_discount', 'droppoints_discount_shortcode');

/**
 * Change flag in en_US
 */ 
function thdp_change_en_flag( $flags_path, $language_code ) {
    
    if( $language_code == 'en_US' ) {
        
        $flags_path = 'https://thedrops.eu/wp-content/uploads/';
    }
    return $flags_path;
}
add_filter( 'trp_flags_path', 'thdp_change_en_flag', 1000, 2 );

add_action('wp_footer', 'footer_hook');
function footer_hook(){ 
    // if (is_checkout()) {

        $ex_opts_value = get_post_meta( 2704, '_gamipress_wc_partial_payments_conversion', true );
        $ex_points_value = isset( $ex_opts_value['points'] ) ? ( float ) $ex_opts_value['points'] : 0;
        $ex_money_value = isset( $ex_opts_value['money'] ) ? ( float ) $ex_opts_value['money'] : 0; ?>
        <style>
            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Remove arrows in number input for Firefox */
            input[type="number"] {
                -moz-appearance: textfield;
            }
            div#gamipress-wc-partial-payments {
                display: none;
            }
            .gamipress-wc-partial-payments-remove{
                font-weight: 700;
                text-decoration: underline;
                border-color: #5284c2;
                color: #5284c2;
                cursor: pointer;
                font-size: 16px;
            }
            span.wc-block-components-totals-item__label {
                display: grid;
            }
            #droppoints-input {
                border: 1px solid rgba(19, 46, 93, 0.4) !important;
                outline: none; /* Remove default focus outline */
            }
            #droppoints-input:focus {
                background-color: #f6f7fc !important;
            }
            #wc-block-components-totals-coupon__input-0:focus{
                background-color: #f6f7fc !important;
            }


            </style>
        <script>
            jQuery(function($) {

            
                // Toggle Droppoints Discount Content
                $(document).on('click', '#droppoints-container', function() {
                    const content = $('#droppoints-content');
                    const arrow = $('#toggle-arrow svg path');

                    if (content.is(':visible')) {
                        content.hide(); // Hide content
                        arrow.attr('d', 'M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z'); // Down arrow
                    } else {
                        content.show(); // Show content
                        arrow.attr('d', 'M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z'); // Up arrow
                    }
                });

                // Synchronize Input and Trigger Button Click using document delegation
                $(document).on('input', '#droppoints-input', function() {
                    const value = $(this).val(); // Get input value
                    const numericValue = parseFloat(value);
                    let money = '<?php echo $ex_money_value; ?>';
                    let points = '<?php echo $ex_points_value; ?>';
                    if( ! money || ! points ) {
                        return;
                    }
                    const conversionRate = money/points;//<?php //echo $ex_money_value / $ex_points_value; ?>; // Conversion rate per point
                    const calculatedValue = (numericValue * conversionRate).toFixed(2);
                     // Divide by 100 and keep 2 decimal places
                    const formattedValue = calculatedValue.replace('.', ',');
            
                    if(value > 0 || value != ''){
                        $('#gamipress-wc-partial-payments-points-droppoints').val(value); // Assign to hidden field
                        $('.drop_info').text(value);
                        $('.drop_amount').text(formattedValue); 
                    } else {
                        $('.drop_info').text('<?php echo $ex_points_value; ?>'); // Fallback for invalid input
                        $('.drop_amount').text('<?php echo str_replace( '.', ',', number_format($ex_money_value,2) ); ?>'); // Fallback for invalid input
                    }
                });

                $(document).on('click', '#droppoints-apply', function() {
                    $('#gamipress-wc-partial-payments-button').trigger('click');
                });

                $(window).on('load', function () {
                    $('.gamipress-wc-partial-payments-remove').each(function () {
                        var text = $(this).text().trim(); // Get the text and trim spaces
                        var updatedText = text.replace(/[\[\]]/g, ''); // Remove square brackets
                        $(this).text(updatedText); // Set the updated text
                    });
                });
            });
    



        </script>
        <?php
    // }
}
//1.3.2024 Chekcout discount end

function tdc_url_has($string) {
    $current_url = $_SERVER['REQUEST_URI'];

    if (stripos($current_url, $string) !== false) {
        return true;
    }

    return false;
}

// Login page background 
add_action( 'bricks_before_header', 'tdc_login_bg' );
function tdc_login_bg() {

    if( stripos( $_SERVER['REQUEST_URI'], '/login' ) !== false ) {
        
        echo '<div class="thdp-login-bg"></div>';
    }
}

add_action('wp_head', 'tdc_custom_css');
function tdc_custom_css() {

    // Show warning & quality tested section conditionally - 20-dec-24
    if( is_product() ) {

        global $post;
        $show_warning = get_field( 'show_warning', $post->ID );
        $qt_section = get_field( 'show_quality_tested_section', $post->ID );

        if( ! $show_warning || is_null( $show_warning ) ) { ?>
            <style>
                #brxe-rwgxdq {
                    display: none;
                }
            </style>
            <?php
        }
        if( ! $qt_section || is_null( $qt_section ) ) { ?>
            <style>
                #brxe-peehap {
                    display: none;
                    padding-top: 0;
                }
            </style>
            <?php
        }
    ?>
        <style>
            @media (max-width: 768px) {
                #brxe-ojukob {
                    display: inline !important;
                }
            }
        </style>
    <?php
    } 

    if( is_login() || is_checkout() ) { ?>
        <style>
            #brxe-a37ec8 a:nth-child() {
                display: none !important;
            }
        </style>
    <?php
    } else { ?>
        <style>
            #brxe-a37ec8 a:first-child {
                /*display: none;*/
            }
        </style>
    <?php
    }

    /* Lang switcher conitional css End */
    ?>
    <style>
        /* Lang switcher Start */
        .brxe-block.profile-column .trp-language-switcher {
            height: 45px;
        }
        .brxe-block.profile-column .trp_language_switcher_shortcode .trp-ls-shortcode-language {
            width: 69px;
        }
        .brxe-block.profile-column .trp_language_switcher_shortcode .trp-language-switcher:focus .trp-ls-shortcode-current-language, 
        .brxe-block.profile-column .trp_language_switcher_shortcode .trp-language-switcher:hover .trp-ls-shortcode-current-language {
            visibility: visible;
        }
        .brxe-block.profile-column .trp_language_switcher_shortcode .trp-language-switcher:hover .trp-ls-shortcode-language {
            background: #F7ECE3 !important;
            border: 0.4px solid #9CA0AD !important;
            box-shadow: 0.7px 0.7px 0.7px rgba(19, 46, 93, 0.3);
            border-radius: 2px;
            height: max-content;
            top: 42px;
        }
        .brxe-block.profile-column .trp-ls-shortcode-language .trp-ls-shortcode-disabled-language.trp-ls-disabled-language {
            display: none !important;
        }
        .brxe-block.profile-column .trp-language-switcher .trp-ls-shortcode-language a img {
            border-radius: 100px !important;
            width: 22px !important;
            height: 22px !important;
            vertical-align: text-top;
            margin-top: -1px;
            margin-left: 5px;
        }
        .brxe-block.profile-column .trp_language_switcher_shortcode .trp-language-switcher .trp-ls-shortcode-language {
            padding-top: 0px !important;
        }
        .brxe-block.profile-column .trp-ls-shortcode-current-language .trp-ls-shortcode-disabled-language.trp-ls-disabled-language {
            display: flex;
            align-items: center;
            margin-top: 10px;
            /*top: 10px;*/
            /*position: relative;*/
        }
        .brxe-block.profile-column .trp-ls-shortcode-current-language .trp-ls-shortcode-disabled-language.trp-ls-disabled-language img {
            margin-right: 8px;
        }
        .brxe-block.profile-column .trp-ls-shortcode-language,
        .brxe-block.profile-column .trp-ls-shortcode-current-language {
            background: transparent !important;
            border: none !important;
        }

        .brxe-block.profile-column .trp_language_switcher_shortcode .trp-language-switcher a {
            /*color: #fff;*/
            padding: 3px 0px !important;
            padding-bottom: 26px !important;
            width: 23px;
            height: 16px;
            font-family: 'Satoshi-Variable';
            font-style: normal !important;
            font-weight: 500 !important;
            font-size: 14px !important;
            line-height: 15px;
            background: transparent !important;
            color: #132e5d;
        }
        .brxe-block.profile-column .trp_language_switcher_shortcode.thdp-dark-header .trp-language-switcher .trp-ls-shortcode-current-language a {
            color: #fff !important;
        }
        .brxe-block.profile-column .trp-language-switcher .trp-ls-shortcode-language a {
            font-family: 'Satoshi-Variable';
            font-style: normal;
            font-weight: 400;
            font-size: 14px;
            line-height: 16px;
            color: rgba(19, 46, 93, 0.6) !important;
            width: 100%;
            border-bottom: 0.434874px solid #9CA0AD;
            border-radius: 1.44958px 1.44958px 0px 0px;
/*          padding: 8px 0px !important; */
            padding-top: 6px !important;
        }

        .brxe-block.profile-column .trp-language-switcher a img {
            border-radius: 100px !important;
            width: 28px !important;
            height: 23px !important;
        }

        #brxe-xjujep,
        #brxe-sveswy {
            width: 25%;
            height: 30px;
        }

        .brxe-block.profile-column .trp-language-switcher {
            width: 78px;
        }

        .brxe-block.profile-column .trp-ls-shortcode-language,
        .brxe-block.profile-column .trp-ls-shortcode-current-language {
            padding: 0 !important;
        }

        .brxe-block.profile-column .trp-language-switcher:hover .trp-ls-shortcode-language {
            width: 70px;
            height: 100px;
            overflow: hidden;
        }

        @media only screen and ( max-width: 1024px ) {
            
            .brxe-block.profile-column .trp_language_switcher_shortcode .trp-language-switcher .trp-ls-shortcode-language {
                width: 68px !important;
            }
            .brxe-block.profile-column .trp-ls-shortcode-current-language {
                width: 100%;
            }
            #brxe-xjujep, #brxe-sveswy {
                width: 37%;
            }
        }
        @media only screen and ( max-width: 768px ) {
            #brxe-a37ec8 {
                justify-content: flex-end;
            }
            #brxe-sveswy {
                width: 26%;
            }
        }
        @media only screen and ( max-width: 700px ) {
            #brxe-a37ec8 {
                justify-content: flex-end;
            }
            #brxe-sveswy {
                width: 28%;
            }
        }
        @media only screen and ( max-width: 599px ) {
            .brxe-block.profile-column .trp-language-switcher .trp-ls-shortcode-current-language a {
                font-size: 0 !important;
            }
            .brxe-block.profile-column .trp-language-switcher.trp-language-switcher-container,
            .brxe-block.profile-column .trp-language-switcher .trp-ls-shortcode-current-language,
            #brxe-sveswy {
                width: max-content !important;
            }
            .brxe-block.profile-column .trp_language_switcher_shortcode .trp-language-switcher:hover .trp-ls-shortcode-language {
                left: unset;
                right: 0;
            }
        }
        @media only screen and ( max-width: 520px ) {
            #brxe-ozgcun,
            #brxe-sveswy {
                display: none;
            }
        }
        /*@media only screen and ( max-width: 329px ) {*/
        @media only screen and ( max-width: 349px ) {
            /*#brxe-440e4e,*/
            a[rel="thdp-acc-link"],
            #brxe-ebea19,
            :where(.brxe-nav-menu) .bricks-mobile-menu-toggle::before {
                display: none;
            }

        }
        #brxe-xjujep,
        #brxe-sveswy {
            height: 100%;
            align-self: center;
        }

        .brxe-block.profile-column .trp_language_switcher_shortcode .trp-ls-shortcode-current-language, 
        .brxe-block.profile-column .trp_language_switcher_shortcode .trp-ls-shortcode-language {
             padding-top: 9px !important; 
            /*padding-top: 7px !important;*/
        }

        a:has(#brxe-7d2ec7), a:has(svg.my-account), a:has(#brxe-vogmpi) {
/*             align-self: flex-end; */
                top: 4px;
                position: relative;
        }

        /**** Lang switcher End ****/

        #droppoints-input::focus,
        #droppoints-input::focus-within,
        #droppoints-input::focus-visible {
            background-color: #f6f7fc;
        }
        /* 14/jan/25 - hide place order text  */
        .wc-block-checkout__actions_row .wc-block-components-checkout-place-order-button::before {
            content: '';
        }

        html,
        body {
            scrollbar-gutter: stable;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.4) transparent;
        }
        body {
            overflow: overlay;
        }
        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        html::-webkit-scrollbar-thumb,
        body::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.4);
            border-radius: 10px;
        }
        html::-webkit-scrollbar-thumb:hover,
        body::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.6);
        }
        html::-webkit-scrollbar-track,
        body::-webkit-scrollbar-track {
            background: transparent;
        }
        html.wfc-lock-scrolling body {
            height: auto !important;
        }
        body.brx-body {
			background: #f7ece3;
		}
        body.mini-cart-opened {
            overflow: hidden !important;
        }
        #brx-header {
            background-color: var(--bricks-color-hkwrlr);
        }
        .brxe-nav-menu .bricks-mobile-menu-wrapper {
            transition: opacity 0.3s ease-out !important;
            height: calc(100% - var(--wp-admin--admin-bar--height, 0px) - 85px) !important;
        }
        @media (max-width: 809px) {
            .brxe-nav-menu .bricks-mobile-menu-wrapper {
                height: calc(100% - var(--wp-admin--admin-bar--height, 0px) - 111px) !important;
            }
        }
        /* Footer 430-650px fix */
        @media only screen and (max-width: 650px) {
            #brxe-afcfa4 {
                column-gap: 50px;
            }
        }
        @media only screen and (max-width: 500px) {
            #brxe-afcfa4 {
                max-width: calc(100% - 40px);
                flex-wrap: wrap;
                row-gap: 80px;
                column-gap: 40px;
                padding-top: 172px;
                justify-content: space-between;
            }
        }
        /* Footer 430-650px fix */
        @media (max-width: 430px) {
            .brxe-nav-menu .bricks-mobile-menu-wrapper {
                height: calc(100% - var(--wp-admin--admin-bar--height, 0px) - 123px) !important;
            }
        }
        .custom-menu-template-container > .brxe-section:first-child {
            margin-left: inherit;
        }
        .custom-menu-template-container {
            overflow-y: auto;
        }
        .custom-menu-template-container nav ul a {
            position: relative;
			-webkit-text-stroke: 2px #000;
		}
        .custom-menu-template-container nav ul a::before {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            color: inherit;
            width: 100%;
            height: 100%;
            padding: inherit;
            text-shadow: none;
            -webkit-text-stroke: 0;
		}
        .menu-column .brxe-nav-menu .bricks-mobile-menu-toggle {
            height: 18px !important;
            min-width: 20px !important;
        }
        .menu-column .brxe-nav-menu.show-mobile-menu .bricks-mobile-menu-toggle span {
            height: 1.8px !important;
        }
        .menu-column .brxe-nav-menu:not(.show-mobile-menu) .bricks-mobile-menu-toggle span.bar-top {
            top: 3px !important;
        }
        .menu-column .brxe-nav-menu:not(.show-mobile-menu)  .bricks-mobile-menu-toggle span.bar-center {
            top: 9px !important;
        }
        .menu-column .brxe-nav-menu:not(.show-mobile-menu)  .bricks-mobile-menu-toggle span.bar-bottom {
            top: 15px !important;
        }
        :where(.brxe-nav-menu) .bricks-mobile-menu-toggle::before {
            content: "Menu" !important;
            left: 28px !important;
            width: fit-content !important;
            height: fit-content !important;
        }
        @media (max-width: 430px) {
            .menu-column .menu-text {
                display: none !important;
            }
            .profile-column .language-switcher {
                display: none !important;
            }
        }
        body.menu-opened .logo-column {
            z-index: 1000;
        }
        .profile-column .brxe-woocommerce-mini-cart.mini-cart .mini-cart-link {
			width: 100%;
			height: 100%;
			align-content: center;
			text-align: center;
			display: flex;
			align-items: center;
			justify-content: center;
		}
        .profile-column .brxe-woocommerce-mini-cart.mini-cart .cart-count {
			background: #db6c36;
			right: 2.5px;
  			top: 7px;
		}
        .profile-column > a:last-child {
            width: 36px;
            height: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        @media (max-width: 430px) {
            .profile-column .brxe-woocommerce-mini-cart.mini-cart.show-cart-details {
                position: static;
            }
            .profile-column .brxe-woocommerce-mini-cart.mini-cart.show-cart-details .cart-detail {
                left: 50%;
                transform: translateX(-50%);
                top: 75%;
                width: unset;
                width: 225px;
            }
            .profile-column > a:last-child {
                width: 24px;
                height: 24px;
            }
        }
        @media (max-width: 360px) {
            .profile-column .brxe-woocommerce-mini-cart.mini-cart.show-cart-details .cart-detail {
                height: calc(100vh - 80px - 24px);
            }
        }
        .brxe-woocommerce-mini-cart .cart-detail {
            background-color: #fffcfa;
            border: 1.5px solid #132e5d;
            box-shadow: 12px 12px 10.7px rgba(19, 46, 93, 0.8);
            border-radius: 2px;
            min-width: fit-content;
        }
        .brxe-woocommerce-mini-cart.show-cart-details .mini-cart-link {
            border: 1.5px solid #132e5d;
            border-radius: 50%;
            background-color: #fffcfa;
            color: #132e5d;
        }
        .brxe-woocommerce-mini-cart.show-cart-details .mini-cart-link svg path {
            stroke: #132e5d !important;
        }
        body.mini-cart-opened .header-main-section,
        body.mini-cart-opened .brxe-woocommerce-mini-cart {
            z-index: 999 !important;
        }
        body.mini-cart-opened .mini-cart-overlay {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: rgba(19, 46, 93, 0.7);
            z-index: 99;
        }
        .brxe-woocommerce-mini-cart .cart-detail .widget_shopping_cart_content {
            padding: 24px;
            padding-top: 32px;
            min-width: fit-content;
        }
        @media (max-width: 430px) {
            .brxe-woocommerce-mini-cart .cart-detail .widget_shopping_cart_content {
                padding-left: 20px;
                padding-right: 20px;
            }
        }
        @media (max-width: 390px) {
            .brxe-woocommerce-mini-cart .cart-detail .widget_shopping_cart_content {
                padding-left: 16px;
                padding-right: 16px;
            }
        }
        @media (max-width: 360px) {
            .brxe-woocommerce-mini-cart .cart-detail .widget_shopping_cart_content {
                height: fit-content;
            }
        }
        .brxe-woocommerce-mini-cart .cart-detail .mini-cart-heading {
            font-family: 'Satoshi-Variable';
            font-size: 21px;
            font-weight: 700;
            line-height: 23.4px;
            margin-bottom: 32px;
            color: #132E5D;
        }
        .brxe-woocommerce-mini-cart .cart-detail .mini-cart-close-btn {
            right: 24px;
            top: 32px;
            background: transparent;
            border: 2px solid #132e5d;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        @media (max-width: 430px) {
            .brxe-woocommerce-mini-cart .cart-detail .mini-cart-close-btn {
                right: 20px;
            }
        }
        @media (max-width: 390px) {
            .brxe-woocommerce-mini-cart .cart-detail .mini-cart-close-btn {
                right: 16px;
            }
        }
        .brxe-woocommerce-mini-cart .cart-detail li {
            width: 375px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(19, 46, 93, 0.4);
            margin-bottom: 20px;
        }
        @media (max-width: 430px) {
            .brxe-woocommerce-mini-cart .cart-detail li {
                width: 347px;
            }
        }
        @media (max-width: 390px) {
            .brxe-woocommerce-mini-cart .cart-detail li {
                width: 327px;
            }
        }
        @media (max-width: 360px) {
            .brxe-woocommerce-mini-cart .cart-detail li {
                width: 328px;
            }
        }
        @media (max-width: 320px) {
            .brxe-woocommerce-mini-cart .cart-detail li {
                width: 288px;
            }
        }
        /* 8-jan-25 */
        .brxe-woocommerce-mini-cart .cart-detail .woocommerce-mini-cart-item dl.variation {
            display: none;
        }
        .brxe-woocommerce-mini-cart .cart-detail li:last-child {
            border-bottom: none;
        }
        .brxe-woocommerce-mini-cart .cart-detail li > a {
            font-family: 'Satoshi-Variable';
            font-size: 16px;
            font-weight: 500;
            line-height: 110%;
            color: #132E5D;
        }
        .brxe-woocommerce-mini-cart .cart-detail li > a img {
            width: 82px;
            height: auto;
            border: 0.72px solid #000;
            border-radius: 1.72px;
            box-shadow: 0.72px 0.72px 0 0 #132e5d;
            background-color: #f7ece3;
            margin-right: 36px;
        }
        @media (max-width: 430px) {
            .brxe-woocommerce-mini-cart .cart-detail li > a {
                font-size: 14px;
            }
            .brxe-woocommerce-mini-cart .cart-detail li > a img {
                width: 69px;
                margin-right: 24px;
            }
        }
        .brxe-woocommerce-mini-cart .cart-detail li > a span {
            display: block;
        }
        .brxe-woocommerce-mini-cart .cart-detail li > a span.bundle-text {
            line-height: 24px;
        }
        .brxe-woocommerce-mini-cart .cart-detail li > a span.bottle-size {
            line-height: 24px;
            color: rgba(19, 35, 93, 0.4);
        }
        @media (max-width: 430px) {
            .brxe-woocommerce-mini-cart .cart-detail li > a span {
                margin-bottom: 2px;
            }
            .brxe-woocommerce-mini-cart .cart-detail li > a span.bundle-text {
                line-height: 110%;
            }
            .brxe-woocommerce-mini-cart .cart-detail li > a span.bottle-size {
                line-height: 110%;
                margin-bottom: 4px;
            }
        }
        .brxe-woocommerce-mini-cart .cart-detail li .cart-item-controls {
            position: absolute;
            right: 0;
            display: flex;
            flex-direction: column;
            gap: 11px;
        }
        .brxe-woocommerce-mini-cart .cart-detail li .quantity-controls {
            width: 82px;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0 8px;
            border: 0.62px solid #132e5d;
            border-radius: 1.62px;
            box-shadow: 1.33px 1.33px 0.86px rgba(19, 35, 93, 0.3);
            transform: translateZ(0); /* Added on 27-2-2025 */
        }
        .brxe-woocommerce-mini-cart .cart-detail li .quantity-controls button,
        .brxe-woocommerce-mini-cart .cart-detail li .quantity-controls .qty {
            background: transparent;
            font-size: 11.87px;
            font-family: "Satoshi-Variable";
            font-weight: 600;
            color: rgb(19, 35, 93);
            border: none;
            outline: none;
        }
        .brxe-woocommerce-mini-cart .cart-detail li .quantity-controls .qty {
            -moz-appearance: textfield;
            text-align: center;
        }
        .brxe-woocommerce-mini-cart .cart-detail li .quantity-controls .qty::-webkit-outer-spin-button,
        .brxe-woocommerce-mini-cart .cart-detail li .quantity-controls .qty::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .brxe-woocommerce-mini-cart .cart-detail li .quantity-controls .qty:focus-visible {
            outline: none;
        }
        .brxe-woocommerce-mini-cart .cart-detail li .remove_from_cart_button {
            font-family: "Satoshi-Variable";
            font-size: 14px;
            font-weight: 500;
            color: #132E5D;
            line-height: 150%;
            text-decoration: underline;
            opacity: 1;
            position: static;
        }
        .brxe-woocommerce-mini-cart .cart-detail li .amount {
            display: block;
            color: rgba(19, 35, 93, 1);
            font-weight: 500;
            font-size: 16px;
            line-height: 17.6px;
            font-family: "Satoshi-Variable";
        }
        @media (max-width: 430px) {
            .brxe-woocommerce-mini-cart .cart-detail li .amount {
                font-size: 14px;
                line-height: 110%;
            }
        }
        .brxe-woocommerce-mini-cart .cart-detail li .amount .sale-price {
            color: rgba(19, 35, 93, 0.4);
        }
        .brxe-woocommerce-mini-cart .cart-detail .total {
            border-top: none;
            margin: 0;
            padding: 10px 0;
            font-family: "Satoshi-Variable";
            font-size: 19px;
            line-height: 23.4px;
            font-weight: 700;
            color: #132e5d;
        }
        @media (max-width: 430px) {
            .brxe-woocommerce-mini-cart .cart-detail .total {
                padding: 0;
            }
        }
        .brxe-woocommerce-mini-cart .cart-detail .total strong {
            font-weight: 700;
        }
        .brxe-woocommerce-mini-cart .cart-detail .woocommerce-mini-cart__buttons {
            gap: 16px;
            margin-top: 36px;
            grid-auto-columns: 1fr;
        }
        @media (max-width: 430px) {
            .brxe-woocommerce-mini-cart .cart-detail .woocommerce-mini-cart__buttons {
                grid-auto-flow: row;
            }
        }
        @media (max-width: 390px) {
            .brxe-woocommerce-mini-cart .cart-detail .woocommerce-mini-cart__buttons {
                margin-top: 64px;
            }
        }
        .brxe-woocommerce-mini-cart .cart-detail .woocommerce-mini-cart__buttons a.button {
            background-color: #6184c2;
            color: #F7ECE3;
            font-family: "Dynapuff";
            font-size: 16px;
            letter-spacing: 2%;
            padding: 16px 0;
            line-height: 1;
            border: 1px solid #000;
            box-shadow: 2px 2px 1.3px 0 rgba(19, 35, 93, 0.3);
            -webkit-text-stroke: 0.7px #272525;
            text-shadow: 0.5px 1.7px #272525;
            text-transform: uppercase;
            white-space: nowrap;
            position: relative;
        }
        .brxe-woocommerce-mini-cart .cart-detail .woocommerce-mini-cart__buttons a.button::before {
            color: inherit;
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            width: 100%;
            height: 100%;
            padding: inherit;
            text-shadow: none;
            -webkit-text-stroke: 0;
        }
        .brxe-woocommerce-mini-cart .cart-detail .woocommerce-mini-cart__buttons a.button.checkout {
            background-color: #db6c36;
        }
        svg.my-account {
			width: 24px;
			height: 25px;
		}
        main#brx-content {
            background-color: var(--bricks-color-hkwrlr);
            overflow: hidden;
            flex-direction: column;
        }
        .ticker {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }
        .ticker-wrapper {
            position: relative;
        }
        .ticker-content-items {
            max-width: unset;
        }
        .ticker-content-items li.repeater-item {
            flex: none;
        }
        .button-order-now,
        .heading-faqs,
        .heading-our-shop {
            position: relative;
            -webkit-text-stroke: 1px #132e5d;
		}
        .heading-faqs,
        .heading-our-shop {
			-webkit-text-stroke-width: 2px;
		}
        .button-order-now::before,
        .heading-faqs::before,
        .heading-our-shop::before {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            color: inherit;
            width: 100%;
            height: 100%;
            padding: inherit;
            text-shadow: none;
            -webkit-text-stroke: 0;
		}
        .faq-accordion .accordion-item {
            box-shadow: 2px 2px 1.3px 0 rgba(19, 46, 93, 0.3);
            border-radius: 13.8638px;
        }
        .faq-accordion .accordion-item .accordion-content-wrapper {
            margin-top: 1px;
        }
        body.home #brxe-b476cc .accordion-item {
            background-color: rgba(255, 255, 255, 0.8);
        }
        .footer-menu p {
            margin-bottom: 14px;
        }
        .wc-fast-cart__page-overlay[aria-hidden="false"]:not(.closing) + .wc-fast-cart__page-overlay-background {
            background-color: rgba(19, 46, 93, 0.7);
        }
        .wc-fast-cart__page-overlay {
            padding: 80px 0;
            top: 0 !important;
        }
        .wc-fast-cart {
            width: 1200px;
            max-width: 1200px;
            margin: 0 auto !important;
            border: 1px solid #000;
            border-radius: 24px;
            box-shadow: 1px 1px 0 #132E5D;
            background-color: #FFFCFA;
            padding: 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 96px;
        }
        @media (max-width: 1280px) {
            .wc-fast-cart__page-overlay {
                padding-left: 40px;
                padding-right: 40px;
            }
            .wc-fast-cart {
                width: 100%;
                max-width: 1200px !important;
            }
        }
        @media (max-width: 1024px) {
            .wc-fast-cart__page-overlay {
                padding-left: 32px;
                padding-right: 32px;
            }
            .wc-fast-cart {
                max-width: 960px !important;
                padding-left: 32px;
                padding-right: 32px;
            }
        }
        @media (max-width: 809px) {
            .wc-fast-cart__page-overlay {
                padding-left: 48px;
                padding-right: 48px;
            }
            .wc-fast-cart {
                max-width: 713px !important;
                padding-left: 48px;
                padding-right: 48px;
                gap: 84px;
            }
        }
        @media (max-width: 768px) {
            .wc-fast-cart__page-overlay {
                padding-left: 32px;
                padding-right: 32px;
            }
            .wc-fast-cart {
                max-width: 704px !important;
                padding-left: 32px;
                padding-right: 32px;
            }
        }
        @media (max-width: 430px) {
            .wc-fast-cart__page-overlay {
                padding: 0 !important;
            }
            .wc-fast-cart {
                max-width: 100% !important;
                padding-left: 24px;
                padding-right: 24px;
                top: 0 !important;
                border-radius: 0 !important;
                gap: 96px;
                padding-top: 96px;
            }
        }
        @media (max-width: 390px) {
            .wc-fast-cart {
                padding-left: 20px;
                padding-right: 20px;
            }
        }
        @media (max-width: 360px) {
            .wc-fast-cart {
                padding-left: 16px;
                padding-right: 16px;
            }
        }
        .wc-fast-cart__close-btn {
            right: 0 !important;
            top: 0 !important;
            transform: translate(-50%,50%) !important;
            background: transparent;
            border: 2px solid #132e5d;
            border-radius: 50%;
        }
        .wc-fast-cart__close-btn:hover {
            background: transparent !important;
        }
        .wc-fast-cart__close-btn svg {
            fill: #132e5d;
        }
        .wc-fast-cart > h2 {
            font-size: 63px;
            line-height: 91%;
            font-weight: 500;
            font-family: "Dynapuff";
            text-transform: uppercase;
            color: #db6c36;
            word-spacing: -1px;
            text-shadow: 0px 4px #132e5d;
            text-align: center;
            padding-bottom: 0;
            border: none;
            position: relative;
			-webkit-text-stroke: 1px #132e5d;
        }
        .wc-fast-cart > h2::before {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            color: inherit;
            width: 100%;
            height: 100%;
            padding: inherit;
            text-shadow: none;
            -webkit-text-stroke: 0;
		}
        @media (max-width: 809px) {
            .wc-fast-cart > h2 {
                font-size: 56.75px;
                line-height: 82%;
                word-spacing: -0.9px;
            }
        }
        @media (max-width: 430px) {
            .wc-fast-cart > h2 {
                font-size: 46px;
                line-height: 61.8%;
                word-spacing: -0.68px;
            }
        }
        .wc-fast-cart__inner-contents {
            display: flex;
            justify-content: space-between;
            max-height: 100%;
            overflow: hidden;
        }
        .wfc-cart-form.wfc {
            flex: 1 1 auto;
            max-width: 539px;
            display: flex;
            flex-direction: column;
            max-height: 100%;
            padding-bottom: 24px;
            margin-top: 0 !important;
            gap: 36px;
        }
        @media (max-width: 1280px) {
            .wfc-cart-form.wfc {
                flex: 1 1 49.63%;
                max-width: 49.63%;
            }
        }
        @media (max-width: 1024px) {
            .wc-fast-cart__inner-contents {
                gap: 48px;
            }
            .wfc-cart-form.wfc {
                flex: 1 1 50%;
                max-width: 50%;
            }
        }
        @media (max-width: 809px) {
            .wc-fast-cart__inner-contents {
                flex-direction: column;
                gap: 72px;
            }
            .wfc-cart-form.wfc {
                flex: auto;
                max-width: 100%;
                margin-bottom: 0;
                padding-bottom: 0;
            }
        }
        .wc-fast-cart__inner-contents .woocommerce-notices-wrapper {
            margin-bottom: 16px;
        }
        .wfc-cart-items {
            display: block;
            margin: 0;
            width: 100%;
            line-height: inherit;
            max-height: 471px;
            overflow-y: auto;
            flex: auto;
        }
        .wfc-cart-items tbody {
            height: 100%;
            overflow-y: auto;
            display: flex !important;
            flex-direction: column;
            gap: 36px;
        }
        .wfc-cart-items td,
        .wfc-cart-items th {
            display: block;
            padding: 0;
            position: relative;
        }
        @media (max-width: 430px) {
            .wfc-cart-items td {
                display: flex !important;
                flex-direction: column;
                gap: 16px;
            }
        }
        .wfc-cart-form__cart-item.cart_item {
            border-bottom: 1px solid rgba(19, 46, 93, 0.4);
            padding-bottom: 24px;
            list-style: none;
            position: relative;
            width: 100%;
            margin-bottom: 0 !important;
            display: block !important;
        }
        .wfc-cart-form__cart-item.cart_item:nth-last-child(1 of .cart_item) {
            border-bottom: none;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-controls {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 11px;
        }
        @media (max-width: 430px) {
            .wfc-cart-form__cart-item.cart_item .cart-item-controls {
                position: static;
                display: flex;
                align-items: center;
                order: 2;
                transform: unset !important;
                width: 100%;
                gap: 48px;
                flex-direction: row;
            }
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-controls .quantity {
            width: 95px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 7px;
            padding: 0 10px;
            border: 0.82px solid #132e5d;
            border-radius: 2px;
            box-shadow: 1.64px 1.64px 1.06px rgba(19, 35, 93, 0.3);
        }
        @media (max-width: 430px) {
            .wfc-cart-form__cart-item.cart_item .cart-item-controls .quantity {
                width: 114px;
            }
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-controls .quantity label {
            display: none;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-controls .quantity > * {
            background: transparent;
            font-size: 14.65px;
            font-family: "Satoshi-Variable";
            font-weight: 600;
            color: rgb(19, 35, 93);
            border: none;
            outline: none;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-controls .quantity > .action.minus {
            order: 1;
            cursor: pointer;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-controls .quantity > input {
            -moz-appearance: textfield;
            text-align: center;
            order: 2;
            padding: 0 !important;
            width: 40px;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-controls .quantity > .action.plus {
            order: 3;
            cursor: pointer;
        }
        .wfc-cart__remove {
            font-family: "Satoshi-Variable";
            font-size: 15px;
            /*font-weight: 600;*/
            font-weight: 500;
            color: #132E5D;
            line-height: 150%;
            text-decoration: underline;
            opacity: 1;
            visibility: visible;
            height: auto;
            text-align: center;
        }
        .wfc-cart__remove .wfc-sr-text {
            position: static;
            visibility: visible;
            opacity: 1;
            clip: unset;
            clip-path: unset;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-info {
            display: flex;
            gap: 60px;
        }
        @media (max-width: 809px) {
            .wfc-cart-form__cart-item.cart_item .cart-item-info {
                gap: 96px;
            }
        }
        @media (max-width: 430px) {
            .wfc-cart-form__cart-item.cart_item .cart-item-info {
                gap: 48px;
            }
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-info > a {
            display: block;
            min-width: fit-content;
            min-height: fit-content;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-info img {
            width: 114px;
            height: auto;
            border: 1px solid #000;
            border-radius: 2px;
            box-shadow: 1px 1px 0 0 #132e5d;
            background-color: #f7ece3;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-info > span {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-info > span > span {
            display: block;
            font-family: 'Satoshi-Variable';
            font-size: 16px;
            /*font-weight: 600;*/
            font-weight: 500;
            line-height: 24px;
            color: #132E5D;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-info > span > span.product-name {
            font-size: 30px;
            line-height: 22px;
            margin-bottom: 12px;
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-info > span > span.bottle-size {
            color: rgba(19, 35, 93, 0.4);
        }
        .wfc-cart-form__cart-item.cart_item .cart-item-info > span > span.amount {
            font-size: 26px;
            line-height: 19px;
            margin-top: 12px;
        }
        @media (max-width: 430px) {
            .wfc-cart-form__cart-item.cart_item .cart-item-info > span > span.product-name {
                font-size: 26px;
                line-height: 19px;
            }
            .wfc-cart-form__cart-item.cart_item .cart-item-info > span > span.amount {
                font-size: 24px;
            }
        }
        .wfc-cart__actions {
            display: flex;
            z-index: 0;
        }
        .wfc-cart-collaterals {
            flex: 1 1 auto;
            max-width: 455px;
            padding: 24px;
            background-color: #F7ECE3;
            border: 1px solid #132e5d;
            border-radius: 2px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        @media (max-width: 1280px) {
            .wfc-cart-collaterals {
                flex: 1 1 41.89%;
                max-width: 41.89%;
            }
        }
        @media (max-width: 1024px) {
            .wfc-cart-collaterals {
                flex: 1 1 50%;
                max-width: 50%;
            }
        }
        @media (max-width: 809px) {
            .wfc-cart-collaterals {
                flex: auto;
                max-width: 100%;
            }
        }
        .wc-fast-cart .wfc-coupon {
            max-width: 100% !important;
        }
        .wc-fast-cart .wfc-coupon h2 {
            margin: 0;
            padding: 10px 0;
            height: auto;
            font-size: 19px;
            line-height: 23.4px;
            font-family: "Satoshi-Variable";
            font-weight: 700;
            color: #132e5d;
        }
        .wc-fast-cart .wfc-coupon__inner-contents {
            position: relative;
        }
        .wc-fast-cart .wfc-coupon input.input-text[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid rgba(19, 46, 93, 0.4);
            border-radius: 2px;
            background: #FDF9F5;
            font-family: "Satoshi-Variable";
            font-size: 18px;
            line-height: 23.4px;
            font-weight: 500;
            color: rgba(19, 46, 93, 0.4);
            outline: none;
        }
        .wc-fast-cart .wfc-coupon input.input-text[type="text"]::placeholder {
            color: rgba(19, 46, 93, 0.4);
        }
        .wc-fast-cart .wfc-coupon button.wfc-button {
            position: absolute;
            right: 9px;
            top: 50%;
            transform: translateY(-50%);
            max-height: 32px;
            border: none;
            background-color: #6184c2;
            color: #F7ECE3;
            font-family: "Satoshi-Variable";
            font-size: 16px;
            font-weight: 700;
            border-radius: 2px;
            padding: 10px 16px;
            line-height: 0;
        }
        .wfc-cart-totals {
            margin-left: 0 !important;
            margin-top: 0 !important;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .wfc-cart-totals__table {
            max-width: 100% !important;
        }
        .wfc-cart-totals__table > tbody {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .wfc-cart-totals__table .cart-subtotal {
            margin-bottom: 0;
            padding: 10px 0;
            font-family: "Satoshi-Variable";
            font-size: 19px;
            line-height: 23.4px;
            color: #132e5d;
            font-weight: 700;
        }
        .wfc-cart-totals__table .shipping {
            padding: 10px 0;
            margin-bottom: 0;
            font-family: "Satoshi-Variable";
            font-size: 19px;
            line-height: 23.4px;
            font-weight: 700;
            color: #132e5d;
        }
        .wfc-cart-totals__table .shipping th {
            font-family: "Satoshi-Variable";
            font-size: 19px;
            font-weight: 700;
            line-height: 24px;
            text-align: left;
            color: rgba(19, 46, 93, 1);
        }
        .wfc-cart-totals__table .shipping th span {
            font-size: 16px;
            font-weight: 500;
            display: block;
            color: rgba(19, 46, 93, 0.4);
        }
        .wfc-cart-totals__table .shipping td {
            display: flex;
            align-items: center;
            justify-content: end;
        }
        .wfc-cart-totals__table .shipping .woocommerce-shipping-methods,
        .wfc-cart-totals__table .shipping .woocommerce-shipping-destination {
            display: none;
        }
        .wc-fast-cart .shipping-message {
            margin-bottom: 0;
        }
        .wc-fast-cart .shipping-message td {
            width: 100%;
        }
        .wc-fast-cart .shipping-message td label {
            width: 100%;
            position: relative;
            background-color: #FDF9F5;
            padding: 16px;
            padding-left: 54px;
            border: 1px solid rgba(19, 46, 93, 0.4);
            border-radius: 2px;
            font-size: 19px;
            line-height: 24px;
            font-family: "Satoshi-Variable";
            font-weight: 500;
            color: #132e5d;
        }
        .wc-fast-cart .shipping-message td label #shipping-fee {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            border-radius: 50%;
            padding: 0;
            border: 1px solid rgba(19, 46, 93, 0.4);
            background: transparent;
        }
        .wc-fast-cart .shipping-message td label #shipping-fee:checked::after {
            content: '';
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #132e5d;
            display: block;
            position: absolute;
            top: 3px;
            left: 3px;
        }
        .wc-fast-cart .shipping-message td label > span {
            display: block;
        }
        .wc-fast-cart .shipping-message td label > span.message,
        .wc-fast-cart .shipping-message td label > span.fees {
            font-size: 16px;
            font-weight: 500;
            color: rgba(19, 46, 93, 0.4);
        }
        .wfc-cart-totals__table .order-total {
            margin-top: 34px;
            margin-bottom: 0;
            font-family: "Satoshi-Variable";
            font-size: 19px;
            line-height: 23.4px;
            font-weight: 700;
            color: #132e5d;
        }
        .wfc-cart-totals__table .order-total th {
            font-size: 19px;
            font-weight: 700;
            line-height: 24px;
            text-align: left;
            color: rgba(19, 46, 93, 1);
        }
        .wfc-cart-totals__table .order-total .includes_tax {
            font-size: 16px;
            font-weight: 500;
            display: block;
            color: rgba(19, 46, 93, 0.4);
        }
        .wfc-cart-totals__table .order-total td {
            display: flex;
            align-items: center;
        }
        .wfc-cart-collaterals .wfc-proceed-to-checkout .wfc-checkout-buttons {
            max-width: 100% !important;
            flex-direction: row !important;
            margin-bottom: 0 !important;
        }
        @media (max-width: 430px) {
            .wfc-cart-collaterals .wfc-proceed-to-checkout .wfc-checkout-buttons {
                flex-direction: column !important;
            }
        }
        .wfc-cart-collaterals .wfc-proceed-to-checkout a.wfc-button {
            background-color: #db6c36;
            color: #F7ECE3;
            font-family: "Dynapuff";
            font-size: 24px;
            letter-spacing: 2%;
            padding: 16px 26px;
            line-height: 18px;
            border: 1px solid #000;
            box-shadow: 2px 2px 1.3px 0 rgba(19, 35, 93, 0.3);
            -webkit-text-stroke: 0.7px #272525;
            text-shadow: 0.5px 1.7px #272525;
            text-transform: uppercase;
            white-space: nowrap;
            flex: 0 1 auto !important;
            font-weight: 600;
            height: auto;
            position: relative;
        }
        .wfc-cart-collaterals .wfc-proceed-to-checkout a.wfc-button::before {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            color: inherit;
            width: 100%;
            height: 100%;
            padding: inherit;
            text-shadow: none;
            -webkit-text-stroke: 0;
		}
    </style>
    <?php
    
    // 3 14 2025 and 3 17 2025
    // droppoints css starts
    if (tdc_url_has('/droppoints-2')){
    ?>
    <style>


.thdp-sg-droppoints-1st-section {
     padding: 100px 16px;
     gap: 45px;
     align-items: center;
 }

 .thdp-sg-droppoints-1st-section-1st-container {
     gap: 30px;
     align-items: center;
 }

 .thdp-sg-droppoints-heading {

    font-size: 74px;
    line-height: 85px;
    -webkit-text-stroke: 2.6px #000000;
    text-shadow: 0px 5px #132E5D;
     width: 100%;
     color: #6184C2;
     position: relative;
     text-transform: uppercase;
     white-space: nowrap;
     text-decoration: none;
     display: inline-flex;
     align-items: center;
     justify-content: center;
     font-family: 'DynaPuff';
     font-style: normal;
     font-weight: 600;
     leading-trim: both;
     text-edge: cap;
     text-align: center;
     letter-spacing: 0px;
 }

 .thdp-sg-droppoints-heading::before {
     color: inherit;
     content: attr(data-text);
     position: absolute;
     top: 0;
     left: 0;
     z-index: 1;
     width: 100%;
     height: 100%;
     padding: inherit;
     -webkit-text-stroke: 0;
     text-shadow: none;
 }

 .thdp-sg-droppoints-text {
     font-family: 'Satoshi-Variable';
     font-weight: 500;
     font-size: 22px;
     line-height: 135%;
     letter-spacing: 0em;
     text-align: center;
     color: #132E5D;
 }

 .thdp-sg-droppoints-btn {
     /* width: 187.7892303466797;
                height: 42.82402420043945; */
     padding-top: 14px;
     padding-right: 20px;
     padding-bottom: 14px;
     padding-left: 20px;
     gap: 13.26px;
     border-width: 1px;
     border-radius: 2px;
     background-color: #6184C2;
     border: #272525 1px solid;
     box-shadow: 1.73px 1.73px 1.13px 0px #132E5D4D;
     position: relative;

     font-family: 'DynaPuff';
     font-weight: 600;
     font-size: 18px;
     leading-trim: Cap height;
     line-height: 100%;
     letter-spacing: 0.03em;
     text-transform: uppercase;
     color: #F7ECE3;
     -webkit-text-stroke: 1.8px #272525;
     text-shadow: 0.79px 1.82px #132E5D;
 }
.thdp-sg-droppoints-btn::before{
      color: inherit;
     content: attr(data-text);
     position: absolute;
     top: 0;
     left: 0;
     z-index: 1;
     width: 100%;
     height: 100%;
     padding: inherit;
     -webkit-text-stroke: 0;
     text-shadow: none;
}

 .thdp-sg-droppoints-2nd-section {
     padding-right: 18px;
     padding-left: 18px;
     padding-top: 80px;
     padding-bottom: 72px;
     gap: 56px;
     align-items: center;
 }
    .thdp-sg-droppoints-3bottles-2-img {
        display: block;
        width: 342px;
        height: auto;
    }

 .thdp-sg-droppoints-2nd-section-1st-container {
     gap: 83px;
     align-items: center;
 }

 .thdp-sg-droppoints-2nd-section-2nd-container {
     gap: 20px;
     align-items: center;
 }

 .thdp-sg-droppoints-title {
    position: relative;
     font-family: 'DynaPuff';
     font-weight: 500;
     font-size: 43px;
     leading-trim: Cap height;
     line-height: 54px;
     letter-spacing: -1px;
     text-align: center;
     text-transform: uppercase;
     color: #DB6C36;
     -webkit-text-stroke: 2.2px #132E5D;
     text-shadow: 1px 3px #132E5D;
 }

 .thdp-sg-droppoints-title::before{
     color: inherit;
     content: attr(data-text);
     position: absolute;
     top: 0;
     left: 0;
     z-index: 1;
     width: 100%;
     height: 100%;
     padding: inherit;
     -webkit-text-stroke: 0;
     text-shadow: none;
 }

 .thdp-sg-droppoints-table li .title {
     display: inline-block;
     position: relative;
     font-family: 'Satoshi-Variable';
     font-weight: 700;
     font-size: 20px;
     leading-trim: Cap height;
     line-height: 140%;
     letter-spacing: 0em;
     color: #132E5D;
     margin: 0px;
 }

 .thdp-sg-droppoints-table li .content {
     justify-content: left;
 }

 .thdp-sg-droppoints-table li .icon {
     height: 27px;
     width: 27px;
     margin: 8px 13.9px 8px 0;
 }

 .thdp-sg-droppoints-table li:nth-child(2) .icon {
     height: 27.8px;
     width: 27.8px;
     margin-bottom: 20px;
 }

 .thdp-sg-droppoints-table li {
         padding-bottom: 14px;
    }

 .thdp-sg-droppoints-table li:nth-child(7) {
     padding-bottom: 0px;
 }


 .thdp-sg-droppoints-table li .title::after {}

 .thdp-sg-droppoints-table li:nth-child(1) .title::after {
     content: " 250 points";
     font-weight: normal;
     color: inherit;
     /* Matches text color */
 }

 .thdp-sg-droppoints-table li:nth-child(2) .title::after {
     content: " 10 points per €1,00 spent";
     font-weight: normal;
     color: inherit;
     /* Matches text color */
 }

 .thdp-sg-droppoints-table li:nth-child(3) .title::after {
     content: " 250 points (max once per month)";
     font-weight: normal;
     color: inherit;
     /* Matches text color */
 }

 .thdp-sg-droppoints-table li:nth-child(4) .title::after {
     content: "\A So 1,000 DP = €10 discount";
     white-space: pre;
     /* Ensures the line break is respected */
 }

 .thdp-sg-droppoints-table li:nth-child(4) .icon {
    padding-bottom: 21px;
 }

 .thdp-sg-droppoints-table li:nth-child(3) .icon {
 }

 .thdp-sg-droppoints-signup-btn {
     /* width: 141.00015258789062px;
                height: 47.99993133544922px; */
     gap: 10px;
     padding-top: 6px;
     padding-right: 16px;
     padding-bottom: 6px;
     padding-left: 8px;
     border-radius: 100px;
     border-width: 1px;
     background-color: #DB6C36;
     border: 1px solid #272525;
     box-shadow: 1.73px 1.73px 1.13px 0px #132E5D4D;

     font-family: 'DynaPuff';
     font-weight: 600;
     font-size: 18px;
     leading-trim: Cap height;
     line-height: 100%;
     letter-spacing: 0.03em;
     text-transform: uppercase;
     color: #F7ECE3;
     -webkit-text-stroke: 1.8px #272525;
     text-shadow: 0.79px 1.82px #132E5D;
 }

.thdp-sg-droppoints-signup-btn img {
     width: 36px;
     height: 36px;
     vertical-align: middle;
 }
.thdp-sg-droppoints-signup-btn > p {
    position: relative; 
}

.thdp-sg-droppoints-signup-btn > p::before {
    color: inherit;
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    padding: inherit;
    text-shadow: none;
    -webkit-text-stroke: 0;
}


 .thdp-sg-droppoints-btn-list {
     font-family: 'Satoshi-Variable';
     font-weight: 400;
     font-size: 20px;
     leading-trim: Cap height;
     line-height: 140%;
     letter-spacing: 0em;
     color: #132E5D;
 }

 .thdp-sg-droppoints-btn-list-amount {
     font-family: 'Satoshi-Variable';
     font-weight: 700;
     font-size: 20px;
     leading-trim: Cap height;
     line-height: 140%;
     letter-spacing: 0em;
     color: #132E5D;
 }


.thdp-sg-droppoints-btn-list-text-2nd {
    margin-left: 7.2px;
}

.thdp-sg-droppoints-btn-list-icon {
    margin-left: 7.2px;
    margin-right: 7.2px;
}


 /* need more help section */

 .thdp-sg-droppoints-3rd-section {
     align-items: center;
     gap: 48px;
     padding-top: 135px;
     margin-bottom: -309px;
     padding-bottom: 43px;
 }

 .thdp-sg-droppoints-3rd-section-2nd-container {
     align-items: center;
    gap: 38px;
 }

 .thdp-sg-droppoints-3rd-section-3rd-container {
     gap: 40px;
     align-items: center;
     padding-left: 16px;
     padding-right: 16px;
 }

.thdp-sg-droppoints-cloud-1 {
         /* top: -596px;
         left: 888px; */
         position: relative;
         transform: rotate(0deg);
         width: 270px;
         height: auto;
     }


 .thdp-sg-droppoints-3rd-section-4th-container {
    gap: 50px;
     align-items: center;
 }

 .thdp-sg-droppoints-3rd-section-title {
     font-family: 'DynaPuff';
     position: relative;
     line-height: 48px;
     letter-spacing: 0px;
     text-align: center;
     text-transform: uppercase;
     color: #6184C2;
     -webkit-text-stroke: 2px #000000;
     text-shadow: -0.31px 2.33px #272525;
    font-size: 74px;
    font-weight: 600;
 }
 .thdp-sg-droppoints-3rd-section-title::before {
    color: inherit;
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    padding: inherit;
    -webkit-text-stroke: 0;
    text-shadow: none;
}

 .thdp-sg-droppoints-3rd-section-text {
     font-family: 'Satoshi-Variable';
     font-size: 20px;
     letter-spacing: 0px;
     text-align: center;
     color: #132E5D;
    font-weight: 500;
    line-height: 29px;
    width: 380px;
    }

 .thdp-sg-droppoints-contactus-btn {
     gap: 10px;
     border-width: 1px;
     border-radius: 100px;
     padding-top: 6px;
     padding-right: 16px;
     padding-bottom: 6px;
     padding-left: 8px;
     background-color: #6184C2;
     border: 1px solid var(--Black, #272525);
     box-shadow: 1.73px 1.73px 1.13px 0px #132E5D4D;
    font-family: 'DynaPuff';
     font-weight: 600;
     font-size: 18px;
     leading-trim: Cap height;
     line-height: 100%;
     letter-spacing: 0.03em;
     text-transform: uppercase;
     color: #F7ECE3;
     -webkit-text-stroke: 1.8px #272525;
     text-shadow: 0.79px 1.82px #132E5D;
 }

 .thdp-sg-droppoints-contactus-btn img {
     width: 36px;
     height: 36px;
     vertical-align: middle;
 }
 .thdp-sg-droppoints-contactus-btn > p {
    position: relative; 
}

 .thdp-sg-droppoints-contactus-btn > p::before {
    color: inherit;
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    padding: inherit;
    text-shadow: none;
    -webkit-text-stroke: 0;
}
 .thdp-sg-droppoints-3rd-section-5th-container {
     width: 236px;
     height: 460px;
     position: absolute;
     align-self: center;
 }

 .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(1) {
     position: absolute;
     width: 32.894757px;
    height: auto;
    top: 157px;
    right: -308px;
 }

 .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(2) {
    width: 35.787137px;
    height: auto;
    top: 647px;    
    right: -362px;
    position: absolute;
    z-index: 100;
 }

 .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(3) {
     width: 36.513002px;
    height: auto;
    top: 585px;
    left: -350px;
    position: absolute;
    z-index: 100;
 }

 .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(4) {
    width: 35.796305px;
    height: auto;
    top: 198px;
    left: -529px;
    position: absolute;
 }

 .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(5) {
    position: absolute;
    width: 40.133874px;
    height: auto;
    top: 16.84px;
    left: -299px;
 }

.thdp-sg-droppoints-cloud-1 {
    top: -596px;
    /* left: 0px; */
    position: relative;
    right: -891px;
}


        .thdp-sg-droppoints-1st-section-desgin-1 {
         display: unset;
         top: 433.25px;
        right: -12px;
         position: absolute;
     }

     .thdp-sg-droppoints-1st-section-desgin-2 {
         display: unset;
         top: 654.68px;
         left: -18px;
         position: absolute;
     }

     .thdp-sg-droppoints-cloud-2 {
         display: unset;
         top: -402px;
         left: -516px;
         position: relative;
         width: 287px;
         z-index: 1000;
     }

     .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(6) {
         width: 35.787137px;
         height: auto;
         top: 357px;
         right: -507px;
         position: absolute;
     }

     .thdp-sg-droppoints-2nd-section {
        position: relative;
     }
     .thdp-sg-droppoints-2nd-section-design {
         display: unset;
         /*top: 1548px;
         left: 162px;*/
         left: calc(50% - 865.21px / 2 - 116.58px);
         bottom: 140px;
         position: absolute;
     }



     .thdp-sg-droppoints-rich-text {
         padding-right: 195px;
         padding-left: 195px;
          display: unset;
         font-family: 'Satoshi-Variable';
         font-weight: 500;
         font-size: 22px;
         line-height: 135%;
         letter-spacing: 0em;
         text-align: center;
         color: #132E5D;
     }


     .thdp-sg-droppoints-readmore-btn {
         display: inline-flex;
         gap: 10px;
         padding-top: 6px;
         padding-right: 16px;
         padding-bottom: 6px;
         padding-left: 8px;
         border-radius: 100px;
         border-width: 1px;
         background-color: #6184C2;
         border: 1px solid var(--Black, #272525);
         box-shadow: 1.73px 1.73px 1.13px 0px #132E5D4D;

         font-family: 'DynaPuff';
         font-weight: 600;
         font-size: 18px;
         line-height: 100%;
         letter-spacing: 0.03em;
         text-transform: uppercase;
         color: #F7ECE3;
         -webkit-text-stroke: 1.8px #272525;
         text-shadow: 0.79px 1.82px #132E5D;
     }

      .thdp-sg-droppoints-readmore-btn img {
     width: 36px;
     height: 36px;
     vertical-align: middle;
 }
 .thdp-sg-droppoints-readmore-btn > p {
    position: relative; 
}

 .thdp-sg-droppoints-readmore-btn > p::before {
    color: inherit;
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    padding: inherit;
    text-shadow: none;
    -webkit-text-stroke: 0;
}
.thdp-sg-droppoints-readmore-btn-book
{
    display: none;
}
     .thdp-sg-droppoints-logo {
         width: 422px;
     }

      .thdp-sg-droppoints-title {
     font-family: 'DynaPuff';
     font-weight: 500;
     leading-trim: Cap height;
     letter-spacing: -1px;
     text-align: center;
     text-transform: uppercase;
     color: #DB6C36;
     -webkit-text-stroke: 1px #132E5D;
     text-shadow: 1px 3px #132E5D;
     font-size: 63px;
    line-height: 91px;
 }

      .thdp-sg-droppoints-bottlebox-container {
         display: flex;
         flex-direction: row;
         width: auto !important;
         gap: 60px;
     }


   .thdp-sg-droppoints-table {
         width: 490px;
     }
     



.thdp-sg-droppoints-btn {
         display: none;
     }
     .thdp-sg-droppoints-text {
         display: none;
     }

     .thdp-sg-droppoints-list-hide{
        display: none;
     }






     /* Breakpoints */



     
    @media (max-width: 1280px) {

        .thdp-sg-droppoints-btn-list-icon {
    margin-left: 13.2px;
    margin-right: 8.2px;
}
.thdp-sg-droppoints-btn-list-text-2nd {
    margin-left: 14.2px;
}
        .thdp-sg-droppoints-1st-section{
            padding-top: 74px;

        }

        .thdp-sg-droppoints-1st-section-desgin-2{
            top: 641.68px;
            left: 0px;
        }
        .thdp-sg-droppoints-2nd-section{
            padding-top: 33px;
        }

        /*.thdp-sg-droppoints-2nd-section-design{
            top: 1480px;
            left: unset;
            right: 920px;
        }*/
        .thdp-sg-droppoints-3rd-section-title {
        font-size: 68px;
    }

    .thdp-sg-droppoints-3rd-section-2nd-container{
        gap: 58px;
    }
    .thdp-sg-droppoints-3rd-section-4th-container{
        gap: 40px;
    }
    .thdp-sg-droppoints-cloud-2{
            top: -407px;
    left: -468px;
    }
    .thdp-sg-droppoints-cloud-1 {
        top: -603px;
        /* left: 775px; */
        right: -776px;
    }


    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(1) {
        top: 160px;
        right: -182px;

    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(2) {
        top: 651px;
        right: -240px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(3) {
        top: 588px;
        left: -208px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(4) {
        top: 203px;
        left: -388px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(5) {
        top: 20.84px;
        left: -159px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(6) {
        top: 364px;
        right: -394px;
    }

    .thdp-sg-droppoints-1st-section-desgin-1 {
        top: 391.25px;
        right: -1px;
    }
        .thdp-sg-droppoints-readmore-btn {
        
            display: none;
        }

    .thdp-sg-droppoints-readmore-btn-book
{
    display: inline-flex;
}

    
    
    }

    @media (max-width: 1024px) {
        .thdp-sg-droppoints-1st-section-desgin-2{
            left: -158px;
        }
        .thdp-sg-droppoints-2nd-section-design {
            left: calc(50% - 787.21px / 2 - 116.58px);
        }
    }

    @media (max-width: 809px) {
        .thdp-sg-droppoints-2nd-section-design {
            left: calc(50% - 665.21px / 2 - 116.58px);
        }
        /*.thdp-sg-droppoints-2nd-section-design{
            top: 1600px;
            left: -103px;
        }*/
    }


      @media (max-width: 768px) {

        .thdp-sg-droppoints-1st-section-desgin-2{
            display: none;
        }
        .thdp-sg-droppoints-bottlebox-container img{
            display: none;
        }
        .thdp-sg-droppoints-2nd-section-design{
            display: none;
        }

     .thdp-sg-droppoints-2nd-section {
         padding-top: 61px;

     }

     .thdp-sg-droppoints-table li {
         padding-bottom: 14px;
     }

     .thdp-sg-droppoints-2nd-section {
         gap: 56px;
     }

     .thdp-sg-droppoints-2nd-section-1st-container {
         gap: 79px;
     }

     .thdp-sg-droppoints-3rd-section {
         padding-top: 138px;
     }

     .thdp-sg-droppoints-logo {
         width: 422px;
     }

     .thdp-sg-droppoints-3bottles-2-img {
         display: block;
         width: 342px;
         height: auto;
     }


     .thdp-sg-droppoints-heading {
         font-size: 74px;
         line-height: 85px;
         -webkit-text-stroke: 1.6px #000000;
         text-shadow: 0px 5px #132E5D;
     }

     .thdp-sg-droppoints-text {
         display: none;
     }

     .thdp-sg-droppoints-rich-text {
         display: unset;
         font-family: 'Satoshi-Variable';
         font-weight: 500;
         font-size: 22px;
         line-height: 135%;
         letter-spacing: 0em;
         text-align: center;
         color: #132E5D;
         padding-left: 10px;
         padding-right: 10px;
     }

     .thdp-sg-droppoints-text-bold {
         font-weight: 700;
     }

     .thdp-sg-droppoints-btn {
         display: none;
     }

     .thdp-sg-droppoints-readmore-btn {
         display: none;
     }

      .thdp-sg-droppoints-readmore-btn-book {
        
        display: inline-flex;
    }


     .thdp-sg-droppoints-title {
         font-size: 63px;
         line-height: 91px;
     }

     .thdp-sg-droppoints-table {
         width: 485px;
     }

     .thdp-sg-droppoints-table li:nth-child(4) .content .title {
         width: 300px;
     }

     .thdp-sg-droppoints-2nd-section-2nd-container {
         flex-direction: row;
         justify-content: center;
     }

     .thdp-sg-droppoints-3rd-section-title {
         font-size: 68px;
     }

     .thdp-sg-droppoints-3rd-section-text {
         font-weight: 500;
         line-height: 29px;
         width: 380px;
         font-size: 18px;
     }




     .thdp-sg-droppoints-cloud-2 {
         display: unset;
         top: -134px;
         left: -242px;
         position: relative;
         width: 194px;
     }

     .thdp-sg-droppoints-cloud-1 {
         width: 231px;
         height: auto;
         position: relative;
         transform: rotate(0deg);
         top: -596px;
        right: -459px;     }



     .thdp-sg-droppoints-3rd-section-5th-container {
         width: 354px;
         height: 624px;
     }

     .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(1) {
         width: 34.894757px;
         height: auto;
         top: 134px;
         right: -40px;
     }

     .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(2) {
         width: 34.787137px;
         height: 34.618704px;
         top: 624px;
         right: -28px;
     }

     .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(3) {
         width: 37.513002px;
         height: auto;
         top: 675px;
         left: -126px;
     }

     .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(4) {
         width: 36.796305px;
         height: auto;
         top: 253px;
         left: -186px;
     }

     .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(5) {
         width: 41.133874px;
         height: auto;
         top: 93.84px;
         left: -88px;
     }

     .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(6) {
         display: unset;
         width: 35.787137px;
         height: auto;
         top: 295px;
         right: -100px;
     }


     .thdp-sg-droppoints-3rd-section-4th-container {
         gap: 36px;
         margin-bottom: 16px;
     }
     .thdp-sg-droppoints-3rd-section-3rd-container{
        gap: 23px;
     }
     .thdp-sg-droppoints-cloud-2{
        top: -228px;
     }


     .thdp-sg-droppoints-3rd-section {
         margin-bottom: -226px;
     }

    .thdp-sg-droppoints-1st-section-desgin-1{
        display: none;
    }

    #brx-content{
        /* causing problems */
        /* overflow: visible !important; */
    }

 }


@media ( max-width: 599px ) {
     .thdp-sg-droppoints-heading{
        font-size: 60px;

     }           
            .thdp-sg-droppoints-cloud-1 {right: -365px;}
            .thdp-sg-droppoints-1st-section-desgin-1{right: -100px;}

    }
    @media ( max-width: 520px ) {
    .thdp-sg-droppoints-heading{
        font-size: 50px;
     }
    .thdp-sg-droppoints-3rd-section-title{
        font-size: 50px;
     }


}

@media  ( max-width: 360px ) {
    .thdp-sg-droppoints-heading{
        font-size: 46.09px !important;
     }
}

 @media (max-width: 390px) {

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(3),
    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(2) {
        display: none;
    }
    #brx-footer .thdp-ft-flw-1 {
        display: block;
        left: 44px;
        position: absolute;
        width: 33px;
    }
    #brx-footer .thdp-ft-flw-2 {
        display: block;
        position: absolute;
        top: 80px;
        right: 60px;
        width: 35px;
    }

    .thdp-sg-droppoints-1st-section-desgin-1{
        display: none;
    }
    .thdp-sg-droppoints-readmore-btn{
        display: none;
    }
    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(6){
        display: none;
    }

    .thdp-sg-droppoints-btn{
        display: inline-flex;
    }

    .thdp-sg-droppoints-heading{
        font-size: 50px;
        -webkit-text-stroke: 0.7px #132E5D;
        text-shadow: 1.38px 3.25px #132E5D;
    }

        .thdp-sg-droppoints-1st-section {
        padding-top: 83px;
    }
    .thdp-sg-droppoints-rich-text{
        display: none;
    }
    .thdp-sg-droppoints-text{
        display: unset;
    }
    .thdp-sg-droppoints-title {
    font-family: 'DynaPuff';
    font-weight: 500;
    font-size: 43px;
    leading-trim: Cap height;
    line-height: 48px;
    letter-spacing: -1px;
    text-align: center;
    text-transform: uppercase;
    color: #DB6C36;
    -webkit-text-stroke: 1px #132E5D;
    text-shadow: 1px 3px #132E5D;
}



    .thdp-sg-droppoints-table li {
        padding-bottom: 16px;
    }
    .thdp-sg-droppoints-3rd-section-3rd-container{
        gap: 8px;
    }
    .thdp-sg-droppoints-cloud-2{
        display: none;
    }

    .thdp-sg-droppoints-2nd-section {
        padding-top: 14px;
    }

    .thdp-sg-droppoints-table>li:nth-child(4)>div>span.icon {
        width: 27px;
        height: auto;
    }

    .thdp-sg-droppoints-2nd-section-1st-container {
        gap: 54px;
    }

    .thdp-sg-droppoints-table li:nth-child(4) .title::after {
        content: "\A So 1,000 DP = €10 discount";
        white-space: pre;
        /* Ensures the line break is respected */
    }

    /* .thdp-sg-droppoints-table li:nth-child(3) {
        margin-bottom: -31px;

    }

    .thdp-sg-droppoints-table li:nth-child(4) {
        margin-bottom: -28px;
    } */

    .thdp-sg-droppoints-1st-section {
        gap: 46px;
    }

    .thdp-sg-droppoints-logo {
        width: 308px;
    }

    .thdp-sg-droppoints-3bottles-2-img {
        display: block;
        width: 332px;
        height: auto;
    }


    .thdp-sg-droppoints-heading {
        font-size: 50px;
        width: 100%;
        color: #6184C2;
        /* padding: 16px 26px; */
        /*-webkit-text-stroke: 0.7px #272525;
                text-shadow: 0.5px 1.7px #272525;*/
        /* -webkit-text-stroke: 2.2px #272525;
                text-shadow: 1.5px 1.7px #272525; */
        -webkit-text-stroke: 0.7px #132E5D;
        text-shadow: 1.38px 3.25px #132E5D;
        position: relative;
        text-transform: uppercase;
        white-space: nowrap;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: 'DynaPuff';
        font-style: normal;
        font-weight: 600;
        line-height: 60px;
        leading-trim: both;
        text-edge: cap;
        text-align: center;
        letter-spacing: 0px;
    }


    .thdp-sg-droppoints-cloud-1 {
        width: 160px;
        height: auto;
        transform: rotate(-2deg);
        top: -565.33px;
        right: -192px;
    }

    .thdp-sg-droppoints-3rd-section-title {
        font-family: 'DynaPuff';
        font-weight: 500;
        font-size: 40px;
        line-height: 85.12px;
        letter-spacing: 0px;
        text-align: center;
        text-transform: uppercase;
        color: #6184C2;
        -webkit-text-stroke: 1px #000000;
        text-shadow: -0.31px 2.33px #272525;
    }


    .thdp-sg-droppoints-3rd-section-5th-container {
        width: 354px;
        height: 624px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(1) {
        width: 30px;
        height: auto;
        top: 92px;
        right: 0px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(2) {
        width: 38px;
        height: auto;
        top: 680px;
        right: 25px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(3) {
        width: 35px;
        height: auto;
        top: 595px;
        left: 11px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(4) {
        width: 37px;
        height: auto;
        top: 262px;
        left: 88px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(5) {
        width: 40px;
        height: auto;
        top: -3.16px;
        left: 3px;
    }

    .thdp-sg-droppoints-2nd-section {
        padding-bottom: 0px;
    }

    .thdp-sg-droppoints-3rd-section {
        padding-top: 141px;
        padding-bottom: 340px;
        margin-bottom: -368px;
    }

    .thdp-sg-droppoints-3rd-section-text {
    font-family: 'Satoshi-Variable';
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    letter-spacing: 0px;
    text-align: center;
    color: #132E5D;
}

    .thdp-sg-droppoints-3rd-section-3rd-container {
        padding-left: 18px;
        padding-right: 18px;
    }

    .thdp-sg-droppoints-3rd-section-2nd-container {
        gap: 36px;
    }

    .thdp-sg-droppoints-3rd-section-4th-container {
        gap: 0px;
    }

    .thdp-sg-droppoints-text {
        padding-left: 8px;
        padding-right: 8px;
    }


}


 @media (max-width: 320px) {
 
    .thdp-sg-droppoints-heading{    
        font-size: 46.09px;
    }

    .thdp-sg-droppoints-text{padding-left: 0px; padding-right: 0px;}
    .thdp-sg-droppoints-1st-section{gap: 56px;}
    .thdp-sg-droppoints-2nd-section{padding-top: 65px;}
     .thdp-sg-droppoints-table li:nth-child(4) .title::after {
     content: "\A So 1,000 DP = €10 \A discount";
     white-space: pre;
     /* Ensures the line break is respected */
 }
 .thdp-sg-droppoints-table li{
    padding-bottom:19px;
 }
 .thdp-sg-droppoints-table > li:nth-child(4) > div > span.icon{
    margin-bottom: 53px;
 }
 .thdp-sg-droppoints-3rd-section{
    padding-top: 184px;
 }
 .thdp-sg-droppoints-3rd-section-2nd-container{gap: 41px;}
 .thdp-sg-droppoints-3rd-section-4th-container{gap: 5px;}

 .thdp-sg-droppoints-cloud-1 {
        width: 143px;
        height: auto;
        transform: rotate(0deg);
        top: -622.33px;
        right: -157px;
    }


    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(1){
        top: 156px;
        right: 17px;
    }
        .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(2) {
        width: 35px;
        height: auto;
        top: 672px;
        right: 42px;
    }

        .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(3) {
        width: 33px;
        height: auto;
        top: 574px;
        left: 3px;
    }

    .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(4) {
        width: 32px;
        height: auto;
        top: 242px;
        left: 109px;
    }

        .thdp-sg-droppoints-3rd-section-5th-container>img:nth-child(5) {
        width: 34px;
        height: auto;
        top: 46.84px;
        left: 44px;
    }


}

    


    </style>
    <?php
    }


    // 3 20 2025 and 3 17 2025
    // wholesale page css starts
    if (tdc_url_has('/wholesale-2')){
    ?>
    <style>

.thdp-sg-wholesale-1st-section{
    align-items: center;
    gap: 84px;
    padding-top: 76px; 
}
.thdp-sg-wholesale-1st-section-title{
    align-items: center;
    gap: 24px;
}


    .thdp-sg-wholesale-1st-section-title-heading{
        position: relative;
        font-family: 'DynaPuff';
        font-weight: 600;
        font-size: 74px;
        line-height: 85px;
        letter-spacing: 0px;
        text-align: center;
        text-transform: uppercase;
        color: #6184C2;
        -webkit-text-stroke: 2.4px #132E5D;
        text-shadow: 2.44px 1px #132E5D;
    }
    .thdp-sg-wholesale-1st-section-title-heading::before{
        color: inherit;
        content: attr(data-text);
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
        width: 100%;
        height: 100%;
        padding: inherit;
        -webkit-text-stroke: 0;
        text-shadow: 0;
    }

    .thdp-sg-wholesale-1st-section-title-text{
        font-family: 'Satoshi-Variable';
        font-weight: 500;
        font-size: 22px;
        line-height: 135%;
        letter-spacing: 0em;
        text-align: center;
        color: #132E5D;
    }


    .thdp-sg-wholesale-1st-section-title-btn{
        margin-top: 12px;
        gap: 10px;
         padding-top: 6px;
         padding-right: 16px;
         padding-bottom: 6px;
         padding-left: 8px;
         border-radius: 100px;
         border-width: 1px;
         background-color: #6184C2;
         border: 1px solid var(--Black, #272525);
         box-shadow: 1.73px 1.73px 1.13px 0px #132E5D4D;

         font-family: 'DynaPuff';
         font-weight: 600;
         font-size: 18px;
         line-height: 100%;
         letter-spacing: 0.03em;
         text-transform: uppercase;
         color: #F7ECE3;
         -webkit-text-stroke: 1.8px #272525;
         text-shadow: 0.79px 1.82px #132E5D;
    }

 .thdp-sg-wholesale-1st-section-title-btn img {
     width: 36px;
     height: 36px;
     vertical-align: middle;
 }
 .thdp-sg-wholesale-1st-section-title-btn > p {
    position: relative; 
}

 .thdp-sg-wholesale-1st-section-title-btn > p::before {
    color: inherit;
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    padding: inherit;
    text-shadow: none;
    -webkit-text-stroke: 0;
}

.thdp-sg-wholesale-1st-section-bottle-img{
    width: 432px;
    height: auto;
    margin-left: 70px;
}

.thdp-sg-wholesale-1st-section-desgin-1{
    position: absolute;
    top: 391px;
    right: -60px;
}

.thdp-sg-wholesale-2nd-section{
    /*padding: 163px 328px 256px 328px; */
    padding: 163px 285px 256px 285px;
}

    .thdp-sg-wholesale-2nd-section-rich-txt {
        font-family: 'DynaPuff';
        font-weight: 400;
        font-size: 24px;
        line-height: 147%;
        letter-spacing: 0em;
        text-align: center;
        color: #132E5D;
    }
.thdp-sg-wholesale-2nd-section-rich-txt .bold{
    font-weight: 700;
}

.thdp-sg-wholesale-2nd-section-desgin-1 {
    position: absolute;
    top: 697px;
    left: -49px;
}

.thdp-sg-wholesale-3rd-section
{
    gap: 61px;
}
.thdp-sg-wholesale-3rd-section-heading
{
    text-align: center;
    position: relative;
font-family: 'DynaPuff';
font-weight: 500;
font-size: 63px;
leading-trim: Cap height;
line-height: 91px;
letter-spacing: -1px;
text-transform: uppercase;
color: #DB6C36;
-webkit-text-stroke: 2.2px  var(--Blue-Dark, #132E5D);
text-shadow: 2.5px 2px #132E5D;
}

.thdp-sg-wholesale-3rd-section-heading::before{
     color: inherit;
     content: attr(data-text);
     position: absolute;
     top: 0;
     left: 0;
     z-index: 1;
     width: 100%;
     height: 100%;
     padding: inherit;
     -webkit-text-stroke: 0;
     text-shadow: 0;
}

.thdp-sg-wholesale-3rd-section-content
{
    gap: 52px;
    flex-direction: row;
    width: 692px;
}

.thdp-sg-wholesale-3rd-section-content-liquid-img
{
    width: 159px;
    height: auto;
    transform: rotate(-2deg);
}




.thdp-sg-wholesale-table{
    width: 490px;
    padding-top: 16px;
    z-index: 1;
}
 .thdp-sg-wholesale-table li .title {
     display: inline-block;
     position: relative;
     font-family: 'Satoshi-Variable';
     font-weight: 700;
     font-size: 20px;
     leading-trim: Cap height;
     line-height: 140%;
     letter-spacing: 0em;
     color: #132E5D;
     margin: 0px;
 }

 .thdp-sg-wholesale-table li .content {
     justify-content: left;
 }

 .thdp-sg-wholesale-table li .icon {
     height: 27px;
     width: 27px;
     margin: 8px 13.9px 20px 0;
 }

 .thdp-sg-wholesale-table li:nth-child(1) > div > span.icon {
     margin-bottom: 51px;
 }

 .thdp-sg-wholesale-table li:nth-child(2) > div > span.icon {
     height: 27.8px;
     width: 27.8px;
     margin-bottom: 13px;
 }
 .thdp-sg-wholesale-table li:nth-child(3) > div > span.icon {
     height: 27.8px;
     width: 27.8px;
     margin-bottom: 13px;
 }
 .thdp-sg-wholesale-table li:nth-child(4) > div > span.icon {
     height: 27.8px;
     width: 27.8px;
     margin-bottom: 13px;
 }

 .thdp-sg-wholesale-table li:nth-child(5) > div > span.icon {
     height: 27.8px;
     width: 27.8px;
     margin-bottom: 13px;
 }
 .thdp-sg-wholesale-table li:nth-child(6) > div > span.icon {
     height: 27.8px;
     width: 27.8px;
     margin-bottom: 13px;
 }

 .thdp-sg-wholesale-table li {
         padding-bottom: 12px;
    }

 .thdp-sg-wholesale-table li:nth-child(6) {
     padding-bottom: 0px;
 }



 .thdp-sg-wholesale-table li:nth-child(1) .title::after {
     content: " Receive promotional materials to help showcase The Drops in your store or webshop for 6 months.";
     font-weight: 400;
     color: inherit;
     /* Matches text color */
 }



 .thdp-sg-wholesale-table li:nth-child(3) .title::after {
     content: " ± 3 mg psilocin analog.";
     font-weight: 400;
     color: inherit;
     /* Matches text color */
 }

 .thdp-sg-wholesale-table li:nth-child(4) .title::after {
     content: " 90-100";
    font-weight: 400;
    }
 .thdp-sg-wholesale-table li:nth-child(5) .title::after {
     content: " 5ml (300mg active ingredient";
    font-weight: 400;
    }


.thdp-sg-wholesale-3rd-section-btn
{
     gap: 10px;
     padding-top: 6px;
     padding-right: 16px;
     padding-bottom: 6px;
     padding-left: 8px;
     border-radius: 100px;
     border-width: 1px;
     background-color: #DB6C36;
     border: 1px solid #272525;
     box-shadow: 1.73px 1.73px 1.13px 0px #132E5D4D;

     font-family: 'DynaPuff';
     font-weight: 600;
     font-size: 18px;
     leading-trim: Cap height;
     line-height: 100%;
     letter-spacing: 0.03em;
     text-transform: uppercase;
     color: #F7ECE3;
     -webkit-text-stroke: 1.8px #272525;
     text-shadow: 0.79px 1.82px #132E5D;
}
    
 .thdp-sg-wholesale-3rd-section-btn img {
     width: 36px;
     height: 36px;
     vertical-align: middle;
 }
 .thdp-sg-wholesale-3rd-section-btn > p {
    position: relative; 
}

 .thdp-sg-wholesale-3rd-section-btn > p::before {
    color: inherit;
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    padding: inherit;
    text-shadow: none;
    -webkit-text-stroke: 0;
}

.thdp-sg-wholesale-3rd-section-btn-container
{
    align-items: center;
    margin-top: 15px;
}


.thdp-sg-wholesale-3rd-section-design-1
{
    top: 1522px;
    /*right: -2px;*/
    right: 0px;
    position: absolute;
}

/*.thdp-sg-wholesale-3rd-section-design-2
{
         top: 1522px;
        left: 1162px;
         position: absolute;
}*/

.thdp-sg-wholesale-3rd-section-design-2 {
    /*top: 1915px;
    left: 231px;*/
    left: calc(50% - 665.21px / 2 - 116.58px);
    top: calc(50% - -680.21px / 2 - 116.58px);
    position: absolute;
}

.thdp-sg-wholesale-4th-section
{
    padding-top: 213px;
    padding-bottom: 164px; 
    padding-right: 328px;
    padding-left: 328px;
}




.thdp-sg-wholesale-4th-section-rich-txt {
font-family: 'DynaPuff';
font-weight: 400;
font-size: 24px;
line-height: 147%;
letter-spacing: 0em;
text-align: center;
color: #132E5D;
}
.thdp-sg-wholesale-4th-section-rich-txt .bold{
    font-weight: 700;
}

.thdp-sg-wholesale-3rd-section-design-3{
    display: none;
}


/* need more help */

.thdp-sg-wholesale-need-more-3rd-section {
     align-items: center;
     gap: 48px;
     padding-top: 80px;
     margin-bottom: -351px;
     padding-bottom: 43px;
 }

 .thdp-sg-wholesale-need-more-3rd-section-2nd-container {
     align-items: center;
    gap: 39px;
 }

 .thdp-sg-wholesale-need-more-3rd-section-3rd-container {
     gap: 40px;
     align-items: center;
     padding-left: 16px;
     padding-right: 16px;
 }

 .thdp-sg-wholesale-need-more-3bottles-2-img{
        display: block;
        width: 342px;
        height: auto;
 }

.thdp-sg-wholesale-need-more-cloud-1 {
             top: -667px;
    left: 474px;
         position: relative;
         transform: rotate(0deg);
         width: 270px;
         height: auto;
     }


 .thdp-sg-wholesale-need-more-3rd-section-4th-container {
    gap: 50px;
     align-items: center;
 }

 .thdp-sg-wholesale-need-more-3rd-section-title {
    position: relative;
     font-family: 'DynaPuff';
     line-height: 48px;
     letter-spacing: 0px;
     text-align: center;
     text-transform: uppercase;
     color: #6184C2;
     -webkit-text-stroke: 2px #000000;
     text-shadow: -0.31px 2.33px #272525;
    font-size: 74px;
    font-weight: 600;
 }

 .thdp-sg-wholesale-need-more-3rd-section-title::before{
     color: inherit;
     content: attr(data-text);
     position: absolute;
     top: 0;
     left: 0;
     z-index: 1;
     width: 100%;
     height: 100%;
     padding: inherit;
     -webkit-text-stroke: 0;
     text-shadow: 0;
 }

 .thdp-sg-wholesale-need-more-3rd-section-text {
     font-family: 'Satoshi-Variable';
     font-size: 20px;
     letter-spacing: 0px;
     text-align: center;
     color: #132E5D;
    font-weight: 500;
    line-height: 29px;
    width: 530px;
    }

 .thdp-sg-wholesale-need-more-contactus-btn {
     gap: 10px;
     border-width: 1px;
     border-radius: 100px;
     padding-top: 6px;
     padding-right: 16px;
     padding-bottom: 6px;
     padding-left: 8px;
     background-color: #6184C2;
     border: 1px solid var(--Black, #272525);
     box-shadow: 1.73px 1.73px 1.13px 0px #132E5D4D;



     font-family: 'DynaPuff';
     font-weight: 600;
     font-size: 18px;
     leading-trim: Cap height;
     line-height: 100%;
     letter-spacing: 0.03em;
     text-transform: uppercase;
     color: #F7ECE3;
     -webkit-text-stroke: 1.8px #272525;
     text-shadow: 0.79px 1.82px #132E5D;
 }


 .thdp-sg-wholesale-need-more-contactus-btn img {
     width: 36px;
     height: 36px;
     vertical-align: middle;
 }
 .thdp-sg-wholesale-need-more-contactus-btn > p {
    position: relative; 
}

 .thdp-sg-wholesale-need-more-contactus-btn > p::before {
    color: inherit;
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    padding: inherit;
    text-shadow: none;
    -webkit-text-stroke: 0;
}

 .thdp-sg-wholesale-need-more-3rd-section-5th-container {
     width: 236px;
     height: 460px;
     position: absolute;
     align-self: center;
 }

 .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(1) {
     position: relative;
     width: 32.894757px;
    height: auto;
    top: 153px;
    left: 511px;
 }

 .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(2) {
    width: 35.787137px;
    height: auto;
    top: 608px;
    left: 561px;
     position: relative;
 }

 .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(3) {
     width: 36.513002px;
    height: auto;
    top: 509px;
    left: -350px;
     position: relative;
 }

 .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(4) {
    width: 35.796305px;
    height: auto;
    top: 90px;
    left: -529px;
    position: relative;
 }

 .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(5) {
    position: relative;
    width: 40.133874px;
    height: auto;
    top: -122.16px;
    left: -299px;
 }

        .thdp-sg-wholesale-need-more-1st-section-desgin-1 {
         display: unset;
         top: 433.25px;
         left: 1172.11px;
         position: absolute;
     }

     .thdp-sg-wholesale-need-more-1st-section-desgin-2 {
         display: unset;
         top: 654.68px;
         left: -18px;
         position: absolute;
     }

     .thdp-sg-wholesale-need-more-cloud-2 {
         display: unset;
         top: -473px;
         left: -516px;
         position: relative;
         width: 287px;
     }

     .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(6) {
         width: 35.787137px;
         height: auto;
         top: 177px;
         left: 708px;
         position: relative;
     }




/* need more help end */
.thdp-sg-wholesale-2nd-section-bottle-img{
    display: none;
}


.thdp-sg-wholesale-2nd-section-rich-txt-mob{
    display: none;
}
    .thdp-sg-wholesale-2nd-section-rich-txt-mob-320{
    display: none;
}
.thdp-sg-wholesale-need-more-contactus-btn-hide{
    display: none;
}
.thdp-sg-wholesale-need-more-3rd-section-title-hide{
    display: none;
}



/* Breakpoints */
@media (max-width: 1280px) {
    .thdp-sg-wholesale-2nd-section{ padding: 168px 248px 255px 248px; }
    .thdp-sg-wholesale-4th-section { padding: 202px 248px 203px; }
    .thdp-sg-wholesale-need-more-3rd-section-title{font-size: 68px; font-weight: 500;}
    .thdp-sg-wholesale-need-more-3rd-section-2nd-container{ gap: 54px; }
    .thdp-sg-wholesale-need-more-3rd-section-4th-container{ gap: 40px; }
    .thdp-sg-wholesale-need-more-3rd-section-3rd-container{ gap: 38px; }
    .thdp-sg-wholesale-need-more-3rd-section{margin-bottom: -370px;}
    
    .thdp-sg-wholesale-1st-section-desgin-1{
        left: unset;
        right: -160px;
    }
    /*.thdp-sg-wholesale-3rd-section-design-1{left: 1021px;}*/
    /*.thdp-sg-wholesale-3rd-section-design-2{top: 1930px; left: 171px;}*/
    .thdp-sg-wholesale-need-more-cloud-1{top: -685px;left: 359px;}
    .thdp-sg-wholesale-need-more-cloud-2{top: -490px;left: -469px;}
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(1){left:385px;}
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(2){left: 442px;}
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(3){top: 511px;left: -209px;}
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(4){left: -389px;}
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(5){left: -160px;}
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(6){left: 593px;}
}
@media (max-width: 909px) {
    .thdp-sg-wholesale-1st-section-desgin-1{
        left: unset;
        right: -260px;
    }
    .thdp-sg-wholesale-3rd-section-design-1 {
        top: 1722px;
    }
}
@media (max-width: 809px) {
    .thdp-sg-wholesale-2nd-section{
        padding: 200px 120px 256px 120px; 
    }
    .thdp-sg-wholesale-3rd-section-design-2 {
        left: calc(50% - 610.21px / 2 - 116.58px);
        top: calc(50% - -750.21px / 2 - 116.58px);
    }
}
@media (max-width: 768px) {

    .thdp-sg-wholesale-3rd-section-design-1 {
        display: none;
    }
    .thdp-sg-wholesale-1st-section-desgin-1{display: none;}
    .thdp-sg-wholesale-1st-section{gap: 131px;}
    .thdp-sg-wholesale-1st-section-bottle-img{
        margin-left: 37px;
        width: 330px;
    }
    .thdp-sg-wholesale-2nd-section{
        padding: 210px 32px 173px 32px;
    }
    .thdp-sg-wholesale-2nd-section-rich-txt{font-size: 22px;}

    .thdp-sg-wholesale-3rd-section-heading{
        font-size: 56.42px;
        letter-spacing: -0.94px;
    }

    .thdp-sg-wholesale-3rd-section-content{flex-direction: row; gap: 62px;}


    .thdp-sg-wholesale-table {
        width: 514px;
        padding-left: 30px;
    }
    .thdp-sg-wholesale-3rd-section-btn-container {
        align-items: flex-start;
            padding-left: 47px;
            margin-top: 23px;
    }



    .thdp-sg-wholesale-3rd-section{gap: 28px;}
    .thdp-sg-wholesale-3rd-section-content{width: 730px;}
        .thdp-sg-wholesale-3rd-section-content-liquid-img {
        position: absolute;
        top: 1670px;
        /*left: 547px;*/
        left: unset;
        right: 58px;
        transform: rotate(14deg);
    }

    .thdp-sg-wholesale-3rd-section-btn::before{background-size: 22.25px;}
    .thdp-sg-wholesale-3rd-section-design-2{display: none;}
    .thdp-sg-wholesale-3rd-section-design-3{
        display: unset;
        position: absolute;
        top: 1392px;
        /*left: 582px;*/
        left: unset;
        right: 0;
    }

    .thdp-sg-wholesale-4th-section{
        padding: 153px 32px 140px;
    }
    .thdp-sg-wholesale-4th-section-rich-txt{
        font-size: 22px;
    }
    .thdp-sg-wholesale-need-more-3rd-section-text{
        font-size: 18px;
    }


    .thdp-sg-wholesale-2nd-section-desgin-1{top: 612px;left: -100px;}
    .thdp-sg-wholesale-need-more-cloud-1 {
        top: -680px;
        left: 189px;
        width: 234px;
    }

    .thdp-sg-wholesale-need-more-3rd-section{margin-bottom: -293px;}
    .thdp-sg-wholesale-need-more-cloud-2 {
        top: -260px;
        left: -244px;
        width: 200px;
    }
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(1){
        left: 300px;
        top: 133px;
    }

    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(2){
        left: 287px;
        top: 590px;
    }

    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(3){
        top: 607px;
        left: -185px;
    }

    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(4){
        left: -245px;
        top: 150px;
    }
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(5){
        left: -147px;
        top: -42.16px;
    }

    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(6){
        left: 360px;
        top: 118px;
    }
}
@media only screen and ( max-width: 656px ) {
    .thdp-sg-wholesale-table {
        width: 466px;
    }
    .thdp-sg-wholesale-1st-section-title-text {
        padding: 0px 65px;
    }
}
@media (max-width: 599px){
     .thdp-sg-wholesale-need-more-3rd-section-title{
        font-size: 50px;
     }

     .thdp-sg-wholesale-3rd-section-design-3,
     .thdp-sg-wholesale-3rd-section-content-liquid-img {
        display: none;
     }
     .thdp-sg-wholesale-table {
        width: 90%;
    }
}
@media (max-width: 476px){
     .thdp-sg-wholesale-need-more-3rd-section-title{
        font-size: 40px;
     }
}
@media (max-width: 390px) {

    #brx-footer .thdp-ft-flw-2 {
        display: block;
        position: absolute;
        top: 80px;
        right: 60px;
        width: 35px;
    }

    .thdp-sg-wholesale-1st-section-title-heading{
        font-size: 60.72px;
        letter-spacing: -0.76px;
    }

    .thdp-sg-wholesale-1st-section-title-text{
        font-size: 20px;
        padding-left: 25px;
        padding-right: 25px;
    }
    .thdp-sg-wholesale-1st-section-title{gap: 14px;}
    .thdp-sg-wholesale-1st-section-title-btn{margin-top: 21px;}
    .thdp-sg-wholesale-1st-section{gap: 104px;}

        .thdp-sg-wholesale-1st-section-bottle-img {
        width: 319px;
    }


    .thdp-sg-wholesale-2nd-section {
        padding: 215px 20px 100px 20px;
        gap: 90px;
    }

    .thdp-sg-wholesale-2nd-section-rich-txt-desk{
    display: none;
}
    .thdp-sg-wholesale-2nd-section-rich-txt-mob-320{
    display: none;
}
    .thdp-sg-wholesale-2nd-section-rich-txt-mob{
    display: unset;
}

    
    .thdp-sg-wholesale-3rd-section-content-liquid-img{display: none;}

    .thdp-sg-wholesale-2nd-section-bottle-img
{
    display: unset;
    width: 159px;
    height: auto;
    transform: rotate(8deg);
}
.thdp-sg-wholesale-3rd-section-heading{
    font-size: 43px;
    letter-spacing: -1px;
    text-align: center;
    width: 320px;
    line-height: 56px;

}

.thdp-sg-wholesale-3rd-section{
    gap: 38px;
}
.thdp-sg-wholesale-table{
    width: 354px;
    align-self: center;
    padding-left: 0px;
    /*padding-left: 5px;*/
}
.thdp-sg-wholesale-table li{
    padding-bottom: 17px;
}
.thdp-sg-wholesale-table li:nth-child(6) > div > span.icon{
    margin-bottom: 48px;
}
.thdp-sg-wholesale-3rd-section-content{flex-direction: column;}
.thdp-sg-wholesale-table li:nth-child(1) > div > span.icon{margin-bottom: 77px;}
.thdp-sg-wholesale-3rd-section-btn-container{
    align-items: center;
    margin-top: 15px;
    padding-left: 0;
}

    .thdp-sg-wholesale-4th-section {
        padding: 120px 32px 115px;
    }

    .thdp-sg-wholesale-need-more-3rd-section-title {
        font-size: 40px;
        letter-spacing: 0px;
    }


    .thdp-sg-wholesale-need-more-3rd-section-text::before {
    content: "We believe The Drops is a unique and profitable\A product that can enhance your offerings.\A Want to join us as a wholesaler?\A Here’s how to get started:";
    white-space: pre-line; /* Enables line breaks */
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
}

.thdp-sg-wholesale-need-more-3rd-section-text {
    visibility: hidden;
    position: relative;
}

.thdp-sg-wholesale-need-more-3rd-section-text::before {
    visibility: visible;
    position: absolute;
    top: 0;
    left: 11px;
}

.thdp-sg-wholesale-need-more-3rd-section-4th-container{gap: 18px;}
.thdp-sg-wholesale-need-more-3rd-section-3rd-container{gap: 0px;}
.thdp-sg-wholesale-need-more-contactus-btn{margin-top: -26px;}
.thdp-sg-wholesale-need-more-3rd-section{margin-bottom: -120px;}

    .thdp-sg-wholesale-2nd-section-desgin-1 {
        top: 653px;
        left: -76px;
    }
        .thdp-sg-wholesale-need-more-cloud-1 {
        top: -669px;
        left: 75px;
        width: 159px;
        transform: rotate(-1deg);
    }

    .thdp-sg-wholesale-need-more-cloud-2{display: none;}


    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(1) {
        left: 265px;
        top: 99px;
    }

        .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(2) {
        left: 232px;
        top: 654px;
        z-index: 100;
    }

        .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(3) {
        top: 536px;
        left: -47px;
        z-index: 100;
    }
    main#brx-content{
        /* causing probelms */
        /* overflow: visible !important; */
    }
    /*main#brx-content {
         overflow: unset;
         overflow-y: visible;
    }*/
    
        .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(4) {
        left: 29px;
        top: 170px;
    }
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(5) {
        left: -56px;
        top: -130.16px;
    }
    .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(6){display: none;}

}

/*@media (max-width: 320px) {*/

@media (max-width: 370px) {

    .thdp-sg-wholesale-need-more-3rd-section-title{
    display: none;
}

    .thdp-sg-wholesale-need-more-3rd-section-title-hide{
    display: block;
}

   .thdp-sg-wholesale-1st-section-title-heading{
    font-size: 47.69px !important; 
    letter-spacing: 0px;
}
    .thdp-sg-wholesale-1st-section{padding-top: 69px;}


    .thdp-sg-wholesale-1st-section-title-text > p::before {
    content: "Do you have\A a physical store or a webshop in The Netherlands,\A and are you excited to start selling The Drops?";
    white-space: pre-line; /* Enables line breaks */
}

.thdp-sg-wholesale-1st-section-title-text > p { 
    visibility: hidden;
    position: relative;
}

.thdp-sg-wholesale-1st-section-title-text > p::before {
    visibility: visible;
    position: absolute;
    top: 0;
    /*left: -37px;*/
}



.thdp-sg-wholesale-1st-section-title{
    /*align-items: flex-start;*/
    align-items: center;
    margin-left: 0;
}
.thdp-sg-wholesale-1st-section-title-text{
    /*padding-right: 35px;
    padding-left: 35px;*/
    padding-right: 25px;
    padding-left: 25px;
}
.thdp-sg-wholesale-1st-section-title-btn{
    align-self: center;
    margin-top: 40px;
    /*margin-left: -38px;*/

}
.thdp-sg-wholesale-1st-section-bottle-img{
    padding-right: 51px;

}
.thdp-sg-wholesale-1st-section{gap: 85px;}
.thdp-sg-wholesale-2nd-section-rich-txt > p{
    visibility: unset;
}
.thdp-sg-wholesale-2nd-section-rich-txt > p::before{visibility: unset;}
.thdp-sg-wholesale-2nd-section-rich-txt {
    font-size: 20px;

}

.thdp-sg-wholesale-2nd-section-rich-txt-desk{
    display: none;
}

.thdp-sg-wholesale-2nd-section-rich-txt-mob{
    display: none;
}

.thdp-sg-wholesale-2nd-section-rich-txt-mob-320{
    display: unset;
}

.thdp-sg-wholesale-2nd-section{
    padding-top: 286px;
    gap: 58px;
    padding-bottom: 53px;
}
.thdp-sg-wholesale-table{width: 284px;}

.thdp-sg-wholesale-table li:nth-child(1) > div > span.icon{
    margin-bottom: 104px;
}
.thdp-sg-wholesale-table li:nth-child(6) > div > span.icon{
    margin-bottom: 68px;
}
.thdp-sg-wholesale-need-more-3bottles-2-img{width: 312px;}


.thdp-sg-wholesale-need-more-3rd-section-title::before {
    content: "Need help?";
    font-weight: inherit;
    font-size: inherit; 
}

.thdp-sg-wholesale-need-more-3rd-section-2nd-container{gap: 70px;}

.thdp-sg-wholesale-need-more-3rd-section-text::before {
    content: "This is a short paragraph, just to introduce the product, it doesn’t need to be longer.";
    white-space: pre-line; /* Ensures line breaks */
}

.thdp-sg-wholesale-need-more-3rd-section-text {
    visibility: hidden;
    position: relative;
}

.thdp-sg-wholesale-need-more-3rd-section-text::before {
    visibility: visible;
    position: absolute;
    top: -23px;
    left: 0;
}
.thdp-sg-wholesale-need-more-3rd-section-4th-container{
    gap: 48px;
}
.thdp-sg-wholesale-need-more-contactus-btn{margin-top: -101px;}
.thdp-sg-wholesale-need-more-contactus-btn::before{
    background-image: url(https://demo18.wpengineers.com/wp-content/uploads/Vector-1-2.svg);
}

.thdp-sg-wholesale-need-more-contactus-btn{
    display: none;
}
.thdp-sg-wholesale-need-more-contactus-btn-hide{
    display: inline-flex;
}
.thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(1) {
        left: 231px;
        top: 157px;
        width: 30.894757px;
    }
        .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(2) {
        left: 202px;
        top: 645px;
        width: 31.7px;

    }   

        .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(3) {
        top: 515px;
        left: -39px;
        width: 32.51px;
    }
        .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(4) {
        left: 67px;
        top: 152px;
        width: 32.7px;
    }
        .thdp-sg-wholesale-need-more-3rd-section-5th-container>img:nth-child(5) {
        left: 2px;
        top: -75.16px;
        width: 37px;
    }
        .thdp-sg-wholesale-need-more-cloud-1 {
        top: -694px;
        left: 72px;
        width: 142px;
        transform: rotate(-1deg);
    }
        .thdp-sg-wholesale-2nd-section-desgin-1 {
        top: 668px;
        left: -39px;
    }

}

@media (max-width: 430px){
    .thdp-sg-wholesale-1st-section-title-heading{
        font-size: 50px;
    }
    
}





    </style>
    <?php
    }
    if(tdc_url_has('/wholesale-signup')){
        ?>
        <style>
            .thdr-login-form-cont {
            flex-direction: unset;
            justify-content: center;
        }
        .thdp-sg-wholesaler-subheading {
                /*font-family: 'Helvetica Neue';*/
                font-family: 'Helvetica-Neue-Bold';
                font-size: 24px;
                line-height: 29px;
                color: #132E5D;
                flex: none;
                order: 0;
                flex-grow: 0;
                text-transform: none;
                font-weight: 700;
                text-align: center;
                margin-bottom: 80px;
                width: 100%;
                display: inline-table;
                max-width: 310px;
                letter-spacing: 0em;
            }

            .thdp-sg-wholesaler-heading {
                width: 100%;
                text-align: center;
                color: #F7ECE3;
                /* padding: 16px 26px; */
                line-height: 18px;
                /*-webkit-text-stroke: 0.7px #272525;
                text-shadow: 0.5px 1.7px #272525;*/
                /* -webkit-text-stroke: 2.2px #272525;
                text-shadow: 1.5px 1.7px #272525; */
                -webkit-text-stroke: 2px #132E5D;
                text-shadow: 0px 4px #132E5D;
                position: relative;
                text-transform: uppercase;
                white-space: nowrap;
                text-align: center;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;

                font-family: 'DynaPuff';
                font-style: normal;
                font-weight: 600;
                font-size: 74px;
                line-height: 91px;
                leading-trim: both;
                text-edge: cap;
                text-align: center;
                letter-spacing: -1px;
                color: #DB6C36;
                margin-bottom: 50px;
                margin-top: 57px;
            }

            .thdp-sg-wholesaler-heading::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                -webkit-text-stroke: 0px;
                text-shadow: none;
            }

            .thdp-signup-wholesaler-form {
                box-sizing: border-box;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-left: 32px;
                padding-right: 32px;
                padding-top: 36px;
                padding-bottom: 36px;
                width: 700px;
                /*height: 776.64px;*/
                background: #FFFCFA;
                border: 0.896px solid #132E5D;
                box-shadow: 1.792px 1.792px 1.1648px rgba(19, 46, 93, 0.3);
                border-radius: 7.168px;
                flex: none;
                order: 0;
                flex-grow: 0;
                margin-bottom: 228px;
            }
            :where(.brxe-form) .form-group {
                padding-bottom: 16px !important;
            }


            .thdp-signup-wholesaler-form>.form-group:nth-child(1) label {
                font-size: 24px;
                line-height: 29px;
                leading-trim: both;
                text-edge: cap;
                color: #132E5D;
                flex: none;
                order: 0;
                flex-grow: 0;
                text-transform: none;
                font-weight: 700;
                text-align: center;
                margin-bottom: 35px;
                width: 100%;
                display: inline-table;
            }
             .thdp-signup-wholesaler-form>.form-group label {
                height: 12px;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 700;
                font-size: 16.128px;
                line-height: 20px;
                leading-trim: both;
                text-edge: cap;
                display: flex;
                align-items: center;
                color: #132E5D;
                flex: none;
                order: 0;
                flex-grow: 0;
                text-transform: none;
                margin-bottom: 15px;
            }

            .thdp-signup-wholesaler-form>.form-group input {
                display: flex;
                flex-direction: row;
                align-items: flex-start;
                padding: 12px 0px 12px 16px;
                gap: 10px;
                width: 100%;
                height: 46px;
                background: #FFFCFA;
                border: 0.6px solid #132E5D66;
                box-shadow: 1.4px 1.6px 1px #132E5D4D;
                border-radius: 2px;
                flex: none;
                order: 1;
                align-self: stretch;
                flex-grow: 0;
            }
            .thdp-signup-wholesaler-form > .form-group input::placeholder {
                color: #132E5D66;
                font-size: 16px;
                font-weight: 400;
                line-height: 22px;
                font-family: 'Satoshi-Variable';
            }

            .thdp-signup-wholesaler-form .submit-button-wrapper {
                width: 636px;
                padding-bottom: 20.61px !important;
            }

            .thdp-signup-wholesaler-form .submit-button-wrapper button {
                background-color: #DB6C36;
                border-radius: 100px;
                color: #F7ECE3;
                font-family: "Dynapuff";
                font-size: 18px;
                letter-spacing: 0.03em;
                padding: 14px 0px;
                line-height: 18px;
                border: 1px solid #000;
                box-shadow: 2px 2px 1.3px 0 rgba(19, 35, 93, 0.3);
                /*-webkit-text-stroke: 0.7px #272525;
                                text-shadow: 0.5px 1.7px #272525;*/
                -webkit-text-stroke: 1.71px #272525;
                text-shadow: 0.7px 1.62px #132E5D;
                position: relative;
                text-transform: uppercase;
                white-space: nowrap;
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                height: 42.62px;
            }

            .thdp-signup-wholesaler-form .submit-button-wrapper button .text::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            .thdp-signup-wholesaler-form .submit-button-wrapper button.sending .text::before {
                left: -15px;
            }
            .thdp-signup-wholesaler-form>.form-group:nth-child(8),
            .thdp-signup-wholesaler-form>.form-group:nth-child(9) { width: 48%; }

            .thdp-signup-wholesaler-form>.form-group:nth-child(1) p {
                font-family: "Satoshi-Variable";
                font-size: 16px;
                line-height: 22px;
                color: #132E5DCC;
                text-align: center;
            }
            .thdp-signup-wholesaler-form>.form-group:nth-child(1) {
                padding-bottom: 32px !important;
            }

            .thdp-signup-wholesaler-form>.form-group:nth-child(12) {
                order: 1;
                padding: 0 !important;
            }
            
            .thdp-signup-wholesaler-form>.form-group:nth-child(6) {
                padding-bottom: 34px !important;
            }

            .thdp-signup-wholesaler-form>.form-group:nth-child(7) {
                padding-bottom: 20px !important;
            }
            .thdp-signup-wholesaler-form>.form-group:nth-child(10) {
                padding-bottom: 48px !important;
            }
            .thdp-signup-wholesaler-form>.form-group:nth-child(11) {
                padding-bottom: 20.61px !important;
            }

            .thdp-signup-wholesaler-form>.form-group:nth-child(7) p {
                font-family: "Satoshi-Variable";
                font-size: 16px;
                line-height: 22px;
                color: #132E5DCC;
                font-weight: 400;
            }
            .thdp-signup-wholesaler-form>.form-group:nth-child(7) label {
                font-size: 19px;
                font-family: "Satoshi-Variable";
                font-weight: 700;
                line-height: 22px;
            }
            .thdp-signup-wholesaler-form>.form-group:nth-child(11) p {
                font-family: "Satoshi-Variable";
                font-size: 14px;
                line-height: 22px;
                color: #132E5DCC;
                text-align: center;
            }
            .thdp-signup-wholesaler-form>.form-group:nth-child(12) p {
                font-family: "Satoshi-Variable";
                font-size: 12.54px;
                line-height: 19.71px;
                color: #132E5DCC;
                font-weight: 400;
                letter-spacing: 0em;
                text-align: center;
            }
            .thdp-signup-wholesaler-form>.form-group:nth-child(12) p a {
                color: inherit;
                text-decoration: underline;
                font-weight: 500;
            }
             /* @media only screen and (max-width: 500px) {
                .thdp-signup-form {
                    width: 90%;
                }

                .thdp-signup-wholesaler-form {
                    width: 90%;
                }
            }
            @media only screen and (max-width: 320px) {
                .thdp-signup-wholesaler-form {
                    padding: 20px;
                }
            } */

            /* responsive breakpoints */
            @media only screen and ( max-width: 1280px ) {
                .thdp-signup-wholesaler-form {
                    width: 640px;
                    margin-bottom: 188px;
                }
                .thdp-signup-wholesaler-form .submit-button-wrapper {
                    width: 576px;
                }
            }
            @media ( max-width: 768px ) {
                .thdp-signup-wholesaler-form {
                    width: 580px;
                    margin-bottom: 140px;
                }

                .thdp-sg-wholesaler-heading{
                    font-size: 62px;
                    font-weight: 600;
                }
                .thdp-sg-wholesaler-heading {
                    margin-bottom: 40px;
                    margin-top: 46px;
                }
                .thdp-sg-wholesaler-subheading {
                    margin-bottom: 68px;
                }
                .thdp-signup-wholesaler-form .submit-button-wrapper {
                    width: 516px;
                }
                
            }
             @media only screen and ( max-width: 599px ) {
                .thdp-signup-wholesaler-form {
                    width: 480px;
                }
             }
             @media only screen and ( max-width: 520px ) {
                .thdp-signup-wholesaler-form {
                    width: 370px;
                }
             }

            @media only screen and ( max-width: 390px ) {

                .thdp-signup-wholesaler-form{
                    padding-left: 16px;
                    padding-right: 16px;
                }
                .thdp-sg-wholesaler-heading {
                    margin-bottom: 33px;
                    margin-top: 29px;
                }
                .thdp-sg-wholesaler-heading{
                    font-size: 50px;
                }
                .thdp-signup-wholesaler-form {
                    width: 350px;
                    margin-bottom: 174px;
                }
                .thdp-signup-wholesaler-form>.form-group:nth-child(8),
                .thdp-signup-wholesaler-form>.form-group:nth-child(9) { width: 100%; }
                .thdp-signup-wholesaler-form .submit-button-wrapper {
                    width: 318px;
                }
            }
            @media only screen and ( max-width: 360px ) {
                .thdp-signup-wholesaler-form {
                    width: 300px;
                }
                .thdp-signup-wholesaler-form>.form-group:nth-child(12) p{
                    white-space: nowrap;
                    margin-left: -7px !important;
                }
             }
            @media only screen and ( max-width: 320px ) {
                .thdp-signup-wholesaler-form .submit-button-wrapper{
                    padding-bottom: 22.61px !important;
                }
                .thdp-signup-wholesaler-form {
                    width: 288px;
                }
                .thdp-signup-wholesaler-form>.form-group:nth-child(12) p{
                    white-space: nowrap;
                    margin-left: -14px !important;
                }
                .thdp-signup-wholesaler-form .submit-button-wrapper {
                    width: 256px;
                }
            }

        </style>
        <?php
    }
    /* Articles page css starts here */
    if(tdc_url_has('/info')) {
    ?>
        <style>
            .thdp-article-desc a {
                text-decoration: underline;
                font-weight: 500;
            }
        </style>
    <?php
    }
    if(tdc_url_has('/customer-service-2')) {
    ?>
        <style>
            #brxe-kudhbn {
                padding: 20px;
                height: 200px; 
            }
            .thdp-cs-hd-wrap-1 {
                box-sizing: border-box;
                /*position: relative;*/
                position: absolute;
                width: 165.21px;
                height: 64.39px;
                left: calc(50% - 165.21px / 2 - 253.58px);
                top: 46.71px;
                background: #DB6C36;
                border: 1.01248px solid #272525;
                border-radius: 32.8702px;
                transform: matrix(0.97, -0.26, 0.26, 0.96, 0, 0);
            }
            .thdp-cs-hd-wrap-2 {
                box-sizing: border-box;
                /*position: relative;*/
                position: absolute;
                width: 243.5px;
                height: 64.34px;
                /*left: 168.27px;*/
                left: calc(50% - 165.21px / 2 - 116.58px);
                top: 85.87px;
                background: #6184C2;
                border: 1.01248px solid #272525;
                border-radius: 32.8702px;
                transform: matrix(0.99, 0.13, -0.13, 0.99, 0, 0);
            }
            .thdp-cs-hd-wrap-3 {
                box-sizing: border-box;
                /*position: relative;*/
                position: absolute;
                width: 169.64px;
                height: 64.33px;
                /*left: 126.78px;*/
                left: calc(50% - 165.21px / 2 - -84.42px);
                /*top: 46.19px;*/
                top: 49.19px;
                background: #008E53;
                border: 1.01248px solid #272525;
                border-radius: 32.8702px;
                transform: matrix(1, -0.08, 0.08, 1, 0, 0);
            }
            .thdp-cs-hd-wrap-4 {
                box-sizing: border-box;
                /*position: relative;*/
                position: absolute;
                width: 174.53px;
                height: 64.37px;
                /*left: 105.06px;*/
                left: calc(50% - 165.21px / 2 - -234.58px);
                top: 86.55px;
                background: #E18AB7;
                border: 1.01248px solid #272525;
                border-radius: 32.8702px;
                transform: matrix(0.99, 0.14, -0.14, 0.99, 0, 0);
                z-index: 1;
            }
            .thdp-cs-hd-text {
                color: #EDBC3C;
                font-family: "Dynapuff";
                letter-spacing: 2%;
                padding: 16px 26px;
                line-height: 18px;
                -webkit-text-stroke: 2px #272525;
                text-shadow: 1.5px 1.7px #272525;
                position: relative;
                /*text-transform: uppercase;*/
                white-space: nowrap;
                font-weight: 700;
                font-size: 57.4444px;
                text-align: center;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 64.39px;
                margin-bottom: 15px;
            }
            .thdp-cs-hd-text::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 6px;
                left: -6px;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            .thdp-cs-hd-wrap-3 .thdp-cs-hd-text {
                top: -2px;
            }
            .thdp-cs-hd-wrap-3 .thdp-cs-hd-text::before {
                left: -8px;
            }
            .thdp-cs-hd-wrap-4 .thdp-cs-hd-text {
                top: -4px;
            }
            .thdp-cs-hd-wrap-4 .thdp-cs-hd-text::before{
                top: 6px;
                left: -8px;
            }
            .thdp-cs-hd-mush {
                /*top: 36px;*/
                top: 42px;
                left: calc(50% - 165.21px / 2 - -338.58px);
                /*right: 364px;*/
                /*position: relative;*/
                position: absolute;
                /*z-index: 1;*/
            }
            #brxe-tznyyy {
                padding: 90px 0px;
                justify-content: space-evenly;
            }
            .thdp-cs-cold-2-hd-icon {
                width: 30px;
                margin-right: 10px;
            }
            .thdp-cs-col-2-sec-hd {
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 700;
                font-size: 24px;
                leading-trim: both;
                text-edge: cap;
                text-transform: uppercase;
                color: #132E5D;
                flex: none;
                order: 0;
                flex-grow: 0;
            }
            .thdp-cs-flw {
                position: absolute;
                width: 20px;
                top: 42px;
                left: 36px;
            }
            .thdp-cs-sidebar {
                background: url(/wp-content/uploads/Intersect-1-1.svg);
                background-size: cover;
                background-position: center;
                top: 0%;
                bottom: 17.12%;
                border-radius: 24px;
                box-sizing: border-box;
                /*width: 274px;*/
                width: 274px;
                /*height: 547px;*/
                height: 601px;
                margin: 0px;
                /*background: linear-gradient(0deg, rgba(233, 239, 254, 0.2), rgba(233, 239, 254, 0.2)), url(image.png);*/
                border: 1px solid #132E5D;
                /*padding: 60px 41px;*/
                position: relative;
            }
            .thdp-cs-sd-col-2 {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                padding: 0px;
                gap: 36px;
                /*width: 748px;*/
                width: 800px;
                /*height: 627px;*/
                height: 750px;
                flex: none;
                order: 0;
                align-self: stretch;
                flex-grow: 0;
                overflow-y: scroll;
                overflow-x: hidden;
                margin: 0px;
                padding-right: 45px;
            }
            .thdp-cs-sdb-mh {
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 700;
                font-size: 26px;
                line-height: 93px;
                leading-trim: both;
                text-edge: cap;
                letter-spacing: 1px;
                text-transform: uppercase;
                color: #132E5D;
                flex: none;
                order: 0;
                align-self: stretch;
                flex-grow: 0;
                text-align: center;
                /*margin: 35px;*/
                text-align: center;
                width: 187px;
                margin: 0 auto;
                margin-top: 38px;
                margin-bottom: 15px;
                z-index: 1;
            }
            .thdp-cs-sd-tab {
                width: 57px;
                height: 10px;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 500;
                font-size: 14.1838px;
                line-height: 95px;
                leading-trim: both;
                text-edge: cap;
                color: #132E5D;
                flex: none;
                order: 0;
                flex-grow: 0;

                box-sizing: border-box;
                display: flex;
                flex-direction: row;
                align-items: center;
                padding: 0px 9.51818px;
                gap: 6.35px;
                width: 187px;
                height: 39.66px;
                background: #F7ECE3;
                border: 0.793182px solid #132E5D;
                box-shadow: 1.58636px 1.58636px 1.03114px rgba(19, 46, 93, 0.3);
                border-radius: 2px;
                flex: none;
                order: 0;
                align-self: stretch;
                flex-grow: 0;
                margin: 0 auto;
                margin-bottom: 18px;
                cursor: pointer;
            }
            .thdp-cs-para-hd {
                /*width: 748px;*/
                width: 100%;
                height: 30px;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 500;
                font-size: 22px;
                line-height: 30px;
                display: flex;
                align-items: center;
                color: #132E5D;
                flex: none;
                order: 0;
                align-self: stretch;
                flex-grow: 0;
                margin-bottom: 15px;
            }
            .thdp-cs-col-2-wrap {
                padding-bottom: 22px;
                border-bottom: 1px solid orange;
            }
            .thdp-cs-para {
                /*width: 748px;*/
                /*height: 90px;*/
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 400;
                font-size: 18px;
                line-height: 30px;
                display: flex;
                align-items: center;
                color: #132E5D;
                flex: none;
                order: 1;
                align-self: stretch;
                flex-grow: 0;
                margin-bottom: 15px;
            }
            img.thdp-sc-tab-icon {
                width: 18px;
                margin-right: 6px;
            }
            .thdp-sc-tab-icon.thdp-arr {
                top: 2px;
                position: relative;
            }
            .thdp-cs-md-flw {
                position: absolute;
                top: 125px;
            }
            .thdp-cs-melting-divider {
                background-image: url(/wp-content/uploads/Loop-1.svg);
                background-position: bottom center;
                background-repeat: repeat-x;
                background-size: auto;
                row-gap: 50px;
                flex-direction: column;
                height: 380px;
                top: 1px;
                position: relative;
            }

            #brxe-b87691 {
                padding: 48px 72px 200px;
                background: #6184C2;
            }
            .thdp-nh-section {
                position: relative;
                padding-bottom: 150px;
            }
            .thdp-nh-heading {
                /*color: #6184C2;*/
                margin: 40px 0px;
            }
            .thdp-faq-sec-heading {
                color: #F7ECE3;
                font-family: "Dynapuff";
                letter-spacing: 2%;
                padding: 16px 26px;
                line-height: 18px;
                -webkit-text-stroke: 2px #272525;
                text-shadow: 1.5px 1.7px #272525;
                position: relative;
                text-transform: uppercase;
                white-space: nowrap;
                font-weight: 500;
                font-size: 57.4444px;
                text-align: center;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                margin-bottom: 15px;
            }
            .thdp-faq-sec-heading::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            #brxe-1fe511 {
                width: 100%;
                text-align: center;
                height: 55px;
                /* font-family: 'Nikukyu'; */
                font-family: "Satoshi-Variable";
                font-style: normal;
                font-weight: 400;
                font-size: 18px;
                line-height: 27px;
                text-align: center;
                /*color: #132E5D;*/
                color: #F7ECE3;
                margin: 20px 0px;
                margin-top: 25px;
            }
            #brxe-5cbfce {
                width: 100%;
                text-align: center;
            }
            .thdp-cont-btn {
                border-radius: 100px;
                padding: 16px 26px;
                line-height: 18px;
                border: 1px solid #000;
                box-shadow: 2px 2px 1.3px 0 rgba(19, 35, 93, 0.3);
                -webkit-text-stroke: 2px #272525;
                text-shadow: 2px 1.7px #272525;
                position: relative;
                white-space: nowrap;
                text-align: center;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-family: 'DynaPuff';
                font-style: normal;
                font-weight: 600;
                font-size: 20px;
                line-height: 24px;
                leading-trim: both;
                text-edge: cap;
                letter-spacing: 0.02em;
                text-transform: uppercase;
                color: #F7ECE3;
                /*background: #6184C2;*/
                background: #DB6C36;
                padding-left: 57px;
                margin: 0 auto;
                margin-top: 20px;

                box-sizing: border-box;
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                padding: 6px 16px 6px 52px;
                gap: 10px;
                width: 194.79px;
                height: 48px;
                background: #DB6C36;
                border: 1px solid #272525;
                box-shadow: 1.73372px 1.73372px 1.12692px rgba(19, 46, 93, 0.3);
                border-radius: 100px;
            }
            .thdp-cont-btn::before {
                color: inherit;
                /*content: attr(data-text);*/
                content: 'Contact Us';
                position: absolute;
                top: 5px;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            .thdp-con-wt-img {
                position: absolute;
                left: 7px;
                top: 4px;
            }
            .thdp-nh-cloud-1 {
                position: absolute;
                right: 100px;
            }
            .thdp-nh-cloud-2 {
                position: absolute;
                left: 88px;
                top: 362px;
            }
            .thdp-nh-flw-1 {
                position: absolute;
                left: 200px;
                top: 50px;
            }
            .thdp-nh-flw-2 {
                position: absolute;
                top: 200px;
                width: 20px;
                left: 97px;
            }
            .thdp-nh-flw-3 {
                position: absolute;
                bottom: 150px;
                left: 260px;
            }
            .thdp-nh-flw-4 {
                position: absolute;
                right: 300px;
                top: 171px;
            }
            .thdp-nh-flw-5 {
                position: absolute;
                right: 60px;
                top: 385px;
            }
            .thdp-nh-flw-6 {
                position: absolute;
                right: 200px;
                bottom: 75px;
                width: 30px;
            }
            .thdp-cs-3botls {
                margin: 0 auto;
            }
            #brxe-0bf965 {
                background-color: #6184C2;
            }
            .thdp-mob-nmh-hd {
                display: none;
            }
            .thdp-sp-brk {
                display: block;
            }
        </style>

    <?php
        // NL lang support 
        if(tdc_url_has('/nl')) {
    ?>
            <style>
                .thdp-cs-hd-wrap-1 {
                    width: 159.21px;
                    left: calc(50% - 265.21px / 2 - 253.58px);
                }
                .thdp-cs-hd-wrap-2 {
                    width: 358.5px;
                    left: calc(50% - 265.21px / 2 - 116.58px);
                }
                .thdp-cs-hd-wrap-3 {
                    left: calc(50% - 36.79px / 2 - -84.42px);
                }
                .thdp-cs-hd-wrap-4 {
                    left: calc(50% - 34.79px / 2 - -234.58px);
                }
                .thdp-cs-hd-wrap-4 .thdp-cs-hd-text::before {
                    left: -1px;
                }
                .thdp-cs-hd-mush {
                    left: calc(50% - 5.21px / 2 - -338.58px);
                }
                .thdp-cs-sdb-mh {
                    line-height: 48px;
                }
                .thdp-cont-btn::before {
                    content: 'Neem contact met ons op';
                }
                .thdp-cont-btn {
                    width: 345.79px;
                }
            </style>
    <?php
        } ?>
        <style>
            @media only screen and ( max-width: 1110px ) {
                .thdp-cs-sd-col-2 {
                    width: 60%;
                }
            }
            @media only screen and ( max-width: 785px ) { 
                .thdp-cs-sd-col-2 {
                    width: 50%;
                }
                .thdp-cs-sidebar {
                    width: 240px;
                }
                .thdp-nh-cloud-2 {
                    left: 28px;
                    top: 440px;
                    width: 200px;
                }
                .thdp-nh-flw-3 {
                    position: absolute;
                    bottom: 50px;
                    left: 160px;
                }
            }
            @media only screen and ( max-width: 768px ) {
                #brxe-jwfeqz {
                    flex-direction: row;
                }
                .thdp-nh-cloud-2 {
                    left: 28px;
                    top: 650px;
                    width: 200px;
                }
                .thdp-nh-flw-3 {
                    position: absolute;
                    bottom: 88px;
                    left: 63px;
                }
                .thdp-nh-flw-6 {
                    position: absolute;
                    right: 200px;
                    bottom: 150px;
                    width: 30px;
                }
            }
            @media only screen and ( max-width: 686px ) {
                
                .thdp-cs-hd-text {
                    font-size: 47.4444px;
                }
                .thdp-cs-hd-wrap-1 {
                    width: 142.21px;
                    left: calc(50% - 165.21px / 2 - 207.58px);
                }
                .thdp-cs-hd-wrap-2 {
                    width: 208.5px;
                    left: calc(50% - 165.21px / 2 - 95.58px);
                }
                .thdp-cs-hd-wrap-3 {
                    left: calc(50% - 165.21px / 2 - -72.42px);
                    top: 51.19px;
                    width: 150.64px;
                }
                .thdp-cs-hd-wrap-4 {
                    left: calc(50% - 165.21px / 2 - -199.58px);
                    width: 150.53px;
                }
                .thdp-cs-hd-mush {
                    left: calc(50% - 165.21px / 2 - -283.58px);
                }

                .thdp-nh-flw-5,
                .thdp-nh-cloud-2,
                .thdp-pay-img-1,
                #brxe-qkuqzp,
                #brxe-rplvlx,
                .brxe-image.thdp-ques-1 {
                    display: none;
                }
                .thdp-nh-cloud-1 {
                    z-index: 1;
                    right: 20px;
                    width: 150px;
                }
                .thdp-nh-flw-1 {
                    left: 20px;
                    top: 0;
                }
                .thdp-nh-flw-4 {
                    right: 20px;
                    top: 100px;
                }
                .thdp-nh-flw-2 {
                    top: 280px;
                    width: 20px;
                    left: 97px;
                }
                .thdp-nh-flw-6 {
                    right: 50px;
                    bottom: -100px;
                    width: 30px;
                    z-index: 100;
                }
                .thdp-nh-flw-3 {
                    position: absolute;
                    bottom: 50px;
                    left: 50px;
                }
                .thdp-nh-heading {
                    margin-bottom: 0px;
                }
            }
            @media only screen and ( max-width: 586px ) {
                .thdp-cs-sidebar {
                    width: 220px;
                }
                .thdp-cs-hd-wrap-1 {
                    left: calc(50% - 165.21px / 2 - 196.58px);
                    top: 40.71px;
                }
                .thdp-cs-hd-wrap-2 {
                    left: calc(50% - 165.21px / 2 - 91.58px);
                }
                .thdp-cs-hd-wrap-3 {
                    left: calc(50% - 165.21px / 2 - -79.42px);
                }
                .thdp-cs-hd-wrap-4 {
                    left: calc(50% - 165.21px / 2 - -206.58px);
                }
            }
            @media only screen and ( max-width: 560px ) {
                #brxe-tznyyy {
                    padding: 90px 20px;
                }
                .thdp-cs-sidebar {
                    width: 100%;
                    height: 356px;
                }
                #brxe-rceikh {
                    flex-direction: row;
                    justify-content: space-evenly;
                }
                .thdp-cs-sdb-mh {
                    margin-left: 34px;
                    text-align: left;
                    width: 100%;
                    height: 55px;
                }
                .thdp-cs-sd-tab {
                    margin-bottom: 0px;
                    margin: 0;
                }
                #brxe-jwfeqz {
                    margin-top: 80px;
                }
                .thdp-cs-sd-col-2 {
                    width: 100%;
                }
                .thdp-cs-hd-text {
                    font-size: 57.4444px;
                }
                .thdp-cs-sd-tab {
                    width: 141.8px;
                }
                .thdp-cs-hd-mush {
                    left: calc(50% - 165.21px / 2 - -207.58px);
                    top: 71px;
                }
                .thdp-cs-hd-wrap-1 {
                    left: calc(50% - 165.21px / 2 - 49.58px);
                    top: 40.71px;
                    width: 164.21px;
                }
                .thdp-cs-hd-wrap-2 {
                    width: 237.5px;
                    left: calc(50% - 165.21px / 2 - -24.42px);
                    top: 112.87px;
                    z-index: 1;
                }
                .thdp-cs-hd-wrap-3 {
                    top: 159.19px;
                    width: 168.64px;
                    left: calc(50% - 165.21px / 2 - 90.42px);
                    transform: matrix(1, 0, 0, 1, 0, 0);
                    z-index: 1;
                }
                .thdp-cs-hd-wrap-4 {
                    width: 174.53px;
                    left: calc(50% - 165.21px / 2 - -55.58px);
                    top: 214.55px;
                    transform: matrix(0.99, -0.1, 0.1, 1, 0, 0);
                }
                #brxe-kudhbn {
                    height: 300px;
                }
                .thdp-cs-melting-divider {
                    background-position: 70%;
                }
                .thdp-nh-heading {
                    font-size: 40px;
                }
                .thdp-des-nmh-hd.thdp-nh-heading.thdp-faq-sec-heading::before {
                    left: calc(50% - 165.21px / 2 - 114.58px);
                }
                .thdp-con-wt-img {
                    width: 37px;
                }
                .thdp-cont-btn {
                    font-size: 18px;
                }
                .thdp-sp-brk {
                    display: unset;
                }
                #brxe-b87691 {
                    padding: 48px 29px 200px;
                }
                /*#brxe-b87691 {
                    padding-bottom: 30px;
                }*/
                .thdp-nh-flw-3 {
                    position: absolute;
                    bottom: 108px;
                    left: 50px;
                }
                .thdp-nh-flw-6 {
                    right: 50px;
                    bottom: 30px;
                    width: 30px;
                    z-index: 100;
                }
                .thdp-cs-md-flw {
                    position: absolute;
                    top: 125px;
                    left: 140px;
                }
            }
            @media only screen and ( max-width: 450px ) {
                #brxe-b87691 {
                    padding-bottom: 10px;
                }
                #brxe-b87691 .thdp-nh-flw-3,
                #brxe-b87691 .thdp-nh-flw-6 {
                    display: none;
                }
                #brx-footer .thdp-ft-flw-1 {
                    display: block;
                    left: 9px;
                    position: absolute;
                    width: 45px;
                }
                #brx-footer .thdp-ft-flw-2 {
                    display: block;
                    right: 42px;
                    top: 58px;
                    position: absolute;
                    width: 45px;
                }
            }
            @media only screen and ( max-width: 350px ) {
                .thdp-cs-sd-tab {
                     width: 124.8px; 
                }
                .thdp-cs-hd-text {
                    font-size: 45.68px;
                }
                .thdp-cs-hd-wrap-1 {
                    width: 141.21px;
                    top: 44.71px;
                }
                .thdp-cs-hd-wrap-2 {
                    width: 204.5px;
                }
                .thdp-cs-hd-wrap-3 {
                    top: 152.19px;
                    width: 145.64px;
                    height: 61.33px;
                    left: calc(50% - 165.21px / 2 - 81.42px);
                }
                .thdp-cs-hd-wrap-4 {
                    width: 148.53px;
                    left: calc(50% - 165.21px / 2 - -55.58px);
                    top: 208.55px;
                    transform: matrix(0.99, -0.1, 0.1, 1, 0, 0);
                    height: 58.37px;
                }
                .thdp-cs-hd-mush {
                    left: calc(50% - 165.21px / 2 - -175.58px);
                }
                .thdp-des-nmh-hd {
                    display: none;
                }
                .thdp-mob-nmh-hd {
                    display: block;
                }
                .thdp-cs-melting-divider {
                    background-position: 64%;
                }
            }
        </style>
        <?php
            // NL lang support 
            if(tdc_url_has('/nl')) { ?>
                <style>
                    @media only screen and ( max-width: 785px ) { 
                        .thdp-cs-hd-wrap-1 {
                            width: 144.21px;
                            left: calc(50% - 169.21px / 2 - 253.58px);
                        }
                        .thdp-cs-hd-wrap-2 {
                            width: 316.5px;
                            left: calc(50% - 192.21px / 2 - 116.58px);
                        }
                        .thdp-cs-hd-wrap-3 {
                            width: 152.64px;
                            left: calc(50% - 29.79px / 2 - -84.42px);
                        }
                        .thdp-cs-hd-wrap-4 {
                            width: 128.53px;
                            left: calc(50% - 63.79px / 2 - -234.58px);
                        }
                        .thdp-cs-hd-text {
                            font-size: 50.4444px;
                        }
                        .thdp-cs-hd-mush {
                            left: calc(50% - 115.21px / 2 - -338.58px);
                        }
                    }
                    @media only screen and (max-width: 686px) {
                        .thdp-cs-hd-wrap-1 {
                            width: 142.21px;
                            left: calc(50% - 215.21px / 2 - 207.58px);
                        }
                        .thdp-cs-hd-wrap-2 {
                            width: 316.5px;
                            left: calc(50% - 201.21px / 2 - 95.58px);
                        }
                        .thdp-cs-hd-wrap-3 {
                            left: calc(50% - 0px / 2 - -72.42px);
                        }
                        .thdp-cs-hd-wrap-4 {
                            width: 122.53px;
                            left: calc(50% - 15.21px / 2 - -199.58px);
                            top: 95.55px;
                        }
                        .thdp-cs-hd-mush {
                            left: calc(50% - 63.21px / 2 - -283.58px);
                            top: 47px;
                        }
                        .thdp-faq-sec-heading::before {
                            left: calc(50% - 376.21px / 2 - 95.58px);
                        }
                    }
                    @media only screen and ( max-width: 586px ) {
                        
                        .thdp-cs-hd-wrap-1 {
                            left: calc(50% - -16.21px / 2 - 196.58px);
                            top: 59.71px;
                        }
                        .thdp-cs-hd-wrap-2 {
                            top: 131.87px;
                            left: calc(50% - 26.79px / 2 - 91.58px);
                        }
                        .thdp-cs-hd-wrap-3 {
                            left: calc(50% - 565.21px / 2 - -79.42px);
                            top: 173.19px;
                        }
                        .thdp-cs-hd-wrap-4 {
                            left: calc(50% - 531.21px / 2 - -206.58px);
                            width: 144.53px;
                            top: 225.55px;
                        }
                        .thdp-cs-hd-mush {
                            left: calc(50% - 263.21px / 2 - -283.58px);
                            top: 97px;
                        }
                    }
                    @media only screen and (max-width: 560px) {
                        .thdp-des-nmh-hd.thdp-nh-heading.thdp-faq-sec-heading::before {
                            left: calc(51% - 275.21px / 3 - 114.58px);
                        }
                    }
                    @media only screen and ( max-width: 420px ) {

                        .thdp-cs-hd-wrap-1 {
                            left: calc(50% - -26.21px / 2 - 196.58px);
                            top: 48.71px;
                        }
                        .thdp-cs-hd-wrap-2 {
                            top: 131.87px;
                            left: calc(50% - 83.79px / 2 - 91.58px);
                        }
                        .thdp-cs-hd-wrap-3 {
                            left: calc(50% - 541.21px / 2 - -79.42px);
                            top: 180.19px;
                        }
                        .thdp-cs-hd-wrap-4 {
                            left: calc(50% - 504.21px / 2 - -206.58px);
                            width: 144.53px;
                            top: 218.55px;
                        }
                    }
                    @media only screen and ( max-width: 380px ) {
                        .thdp-faq-sec-heading::before {
                            left: 0%;
                        }
                        .thdp-cont-btn {
                            font-size: 16px;
                            width: 286.79px;
                        }
                        .thdp-cs-sd-tab {
                            padding: 0px 6.51818px;
                        }
                        .thdp-cs-hd-wrap-1 {
                            width: 133.21px;
                            left: calc(50% - -79.21px / 2 - 196.58px);
                            top: 48.71px;
                        }
                        .thdp-cs-hd-wrap-2 {
                            width: 291.5px;
                            top: 133.87px;
                            left: calc(50% - 87.79px / 2 - 91.58px);
                        }
                        .thdp-cs-hd-wrap-3 {
                            width: 144.64px;
                            left: calc(50% - 483.21px / 2 - -79.42px);
                            top: 187.19px;
                        }
                        .thdp-cs-hd-wrap-4 {
                            left: calc(50% - 480.21px / 2 - -206.58px);
                            width: 119.53px;
                            top: 233.55px;
                        }
                        .thdp-cs-hd-text {
                            font-size: 45.4444px;
                        }
                        .thdp-cs-hd-mush {
                            left: calc(50% - 362.21px / 2 - -283.58px);
                        }
                </style>
        <?php
            }
        ?>
        <script>
            ( function( $ ) {

                $( document ).ready( function() {

                    $( document ).on( 'click', '.thdp-cs-sd-tab', function() {

                        let target = $( this ).find( 'a' ).attr( 'rel' );
                        let currUrl = location.href;
                        currUrl = window.location.href.split('#')[0];
                        currUrl = currUrl+'#'+target;

                        location.href = currUrl;
                        // window.history.pushState({ path: currUrl }, '', currUrl);

                        // var target = $(this).text(); // get the text of the span
                        // var scrollPos = $("#brxe-moevxi").position().bottom; // use the text of the span to create an ID and get the top position of that element
                        // $(this).click(function () { // when you click each span 
                        //     $('.thdp-cs-sd-col-2').animate({ // animate your right div
                        //         scrollTop: scrollPos // to the position of the target 
                        //     }, 400); 
                        // });

                        // $('.thdp-cs-sd-col-2').scrollTop($("#brxe-moevxi").offset().top);

                    } );

                } );

            } )( jQuery );
        </script>
    <?php
    }
    /* Articles page css starts here */
    if(tdc_url_has('/faq')) {
    ?>
        <style>
		#brxe-ac18c8 {
		    display: none;
		}
		#brx-header #brxe-8422fb {
		    background: #6184C2;
		}
		body:not(.menu-opened) .menu-column .brxe-nav-menu .bricks-mobile-menu-toggle {
		    color: #fff !important;
		}
		body:not(.menu-opened) .profile-column .brxe-woocommerce-mini-cart.mini-cart .mini-cart-link .cart-icon svg path, 
		body:not(.menu-opened) svg.my-account path {
		    stroke: #fff;
		}
		body:not(.menu-opened) .profile-column .separator {
		    background-color: #fff !important;
		}
		.thdp-faq-main-sec,
		.thdp-faq-cards {
		    background: #6184C2;
		}
		.thdp-faq-sec-heading,
		.thdp-faq-main-heading {
		    color: #F7ECE3;
		    font-family: "Dynapuff";
		    letter-spacing: 2%;
		    padding: 16px 26px;
		    line-height: 18px;
		    -webkit-text-stroke: 2px #272525;
		    text-shadow: 1.5px 1.7px #272525;
		    position: relative;
		    text-transform: uppercase;
		    white-space: nowrap;
		    font-weight: 500;
		    font-size: 57.4444px;
		    text-align: center;
		    text-decoration: none;
		    display: inline-flex;
		    align-items: center;
		    justify-content: center;
		    width: 100%;
		    margin-bottom: 15px;
		}
		.thdp-faq-sec-heading::before,
		.thdp-faq-main-heading::before {
		    color: inherit;
		    content: attr(data-text);
		    position: absolute;
		    top: 0;
		    left: 0;
		    z-index: 1;
		    width: 100%;
		    height: 100%;
		    padding: inherit;
		    text-shadow: none;
		    -webkit-text-stroke: 0;
		}
		.thdp-acc-ord-heading {
		    font-family: 'DynaPuff';
		    font-style: normal;
		    font-weight: 600;
		    font-size: 49.0619px;
		    line-height: 59px;
		    leading-trim: both;
		    text-edge: cap;
		    letter-spacing: 0.02em;
		    text-transform: uppercase;
		    color: #DB6C36;
		}
		.thdp-ship-heading {
		    font-family: 'DynaPuff';
		    font-style: normal;
		    font-weight: 600;
		    font-size: 49.0619px;
		    line-height: 59px;
		    leading-trim: both;
		    text-edge: cap;
		    letter-spacing: 0.02em;
		    text-transform: uppercase;
		    color: #CCE7DB;
		}
		.thdp-pay-heading {
		    color: #E18AB7;
		}
		.thpd-rr-heading {
		    color: #B8D2FF;
		}
		.thpd-rr-heading {
		    color: #B8D2FF;
		}
		.thdp-abt-heading {
		    color: #EDBC3C;
		}
		.thdp-nh-heading {
		    color: #6184C2;
		    margin: 40px 0px;
		}
		#brxe-fsrnfd {
		    position: relative;
		}
		#brxe-f5c483 {
		    position: relative;
		    padding: 50px 0px;
		}
		.brxe-image.thdp-ques-1 {
		    position: absolute;
		    left: 80px;
		    bottom: -18px;
		}
		.brxe-image.thdp-ques-2 {
		    position: absolute;
		    right: 62px;
		    bottom: 62px;
		}
		.thdp-cloud-img {
		    margin: 70px auto;
		}
		.thdp-melting-div {
		    background-repeat: repeat-x;
		    background-size: auto;
		    background-position: bottom center;
		    background-image: url(https://thedrops.eu/wp-content/uploads/Melting-Divider-3.svg);
		    height: 380px;
		    margin-top: -27px;
		}
		.thdp-faq-cards #brxe-mvlici {
		    flex-direction: unset;
		    flex-wrap: wrap;
		    justify-content: center;
		    padding: 45px 0px;
		}
		#brxe-mvlici {
		    margin: unset;
		}
		.thdp-faq-card {
		    box-sizing: border-box;
		    display: flex;
		    flex-direction: column;
		    justify-content: center;
		    /*align-items: center;*/
		    padding: 24px 16px;
		    gap: 10px;
		    width: 455px;
		    height: 152.25px;
		    background: #D5642D;
		    border: 1px solid #132E5D;
		    box-shadow: 2px 2px 1.3px rgba(19, 46, 93, 0.3);
		    border-radius: 2px;
		    flex: none;
		    order: 0;
		    align-self: stretch;
		    flex-grow: 0;
		    margin: 14px;
		}
		.thdp-faq-card-2 {
		    background: #008E53;
		}
		.thdp-faq-card-3 {
		    background: #D37AA8;
		}
		.thdp-faq-card-4 {
		    background: #809ED2;
		}
		.thdp-faq-card-5 {
		    background: #FCDF91;
		}
		.thdp-faq-card-6 {
		    background: #DDE6FE;
		}
		.thdp-card-title {
		    width: 100%;
		    height: 12px;
		    font-family: 'Satoshi-Variable';
		    font-style: normal;
		    font-weight: 700;
		    font-size: 16px;
		    line-height: 22px;
		    leading-trim: both;
		    text-edge: cap;
		    display: flex;
		    align-items: center;
		    text-transform: capitalize;
		    color: #F7ECE3;
		    flex: none;
		    order: 1;
		    flex-grow: 0;
		}
		.thdp-card-desc {
		    width: 100%;
		    height: 44px;
		    font-family: 'Satoshi-Variable';
		    font-style: normal;
		    font-weight: 400;
		    font-size: 16px;
		    line-height: 22px;
		    display: flex;
		    align-items: center;
		    text-transform: capitalize;
		    color: #F7ECE3;
		    flex: none;
		    order: 1;
		    align-self: stretch;
		    flex-grow: 0;
		}
		.thdp-faq-card-5 .thdp-card-title, 
		.thdp-faq-card-5 .thdp-card-desc, 
		.thdp-faq-card-6 .thdp-card-title, 
		.thdp-faq-card-6 .thdp-card-desc {
		    color: #132E5D;
		}
		/*.thdp-acc-ord-accr .accordion-title-wrapper {
		    padding: 16px;
		    background: rgba(219, 108, 54, 0.1) !important;
		    border-radius: 13.8638px;
		}   
		.thdp-acc-ord-accr .accordion-content-wrapper {
		    background: rgba(219, 108, 54, 0.1) !important;
		}*/
        .thdp-acc-ord-accr .accordion-item.listening {
            background: rgba(219, 108, 54, 0.1) !important;
        }
		/*.thdp-ship-accr .accordion-title-wrapper {
		    padding: 16px;
		    background: rgba(255, 255, 255, 0.8) !important;
		    border-radius: 13.8638px;
		}   
		.thdp-ship-accr .accordion-content-wrapper {
		    background: rgba(255, 255, 255, 0.8) !important;
		} */
        .thdp-ship-accr .accordion-item.listening {
            background: rgba(255, 255, 255, 0.8) !important;
        }
		/*.thdp-acc-ord-accr .accordion-title-wrapper {
		    padding: 16px;
		    background: rgba(219, 108, 54, 0.1) !important;
		    border-radius: 13.8638px;
		}   
		.thdp-acc-ord-accr .accordion-content-wrapper {
		    background: rgba(219, 108, 54, 0.1) !important;
		} */ 
		/*.thdp-pay-accr .accordion-title-wrapper {
		    padding: 16px;
		    background: rgba(225, 138, 183, 0.2) !important;
		    border-radius: 13.8638px;
		}   
		.thdp-pay-accr .accordion-content-wrapper {
		    background: rgba(225, 138, 183, 0.2) !important;
		}*/
        .thdp-pay-accr .accordion-item.listening {
            background: #E18AB733 !important;
        }

		/*.thdp-rr-accr .accordion-title-wrapper {
		    padding: 16px;
		    background: rgba(255, 255, 255, 0.8) !important;
		    border-radius: 13.8638px;
		}   
		.thdp-rr-accr .accordion-content-wrapper {
		    background: rgba(255, 255, 255, 0.8) !important;
		}*/
        .thdp-rr-accr .accordion-item.listening {
            background: rgba(255, 255, 255, 0.8) !important;
        }

		/*.thdp-abt-accr .accordion-title-wrapper {
		    padding: 16px;
		    background: rgba(252, 223, 145, 0.5) !important;
		    border-radius: 13.8638px;
		}   
		.thdp-abt-accr .accordion-content-wrapper {
		    background: rgba(252, 223, 145, 0.5) !important;
		}*/
        .thdp-abt-accr .accordion-item.listening {
            background: rgba(252, 223, 145, 0.5) !important;
        }

		/*.thdp-whl-accr .accordion-title-wrapper {
		    padding: 16px;
		    background: rgba(233, 239, 254, 0.85) !important;
		    border-radius: 13.8638px;
		}   
		.thdp-whl-accr .accordion-content-wrapper {
		    background: rgba(233, 239, 254, 0.85) !important;
		}*/
        .thdp-whl-accr .accordion-item.listening {
            background: rgba(233, 239, 254, 0.85) !important;
        }

		.thdp-ship-accr {
		    padding-bottom: 150px;
		}
		.brxe-section #brxe-tvclny,
		.brxe-section #brxe-kcpnzv {
		    row-gap: 0;
		}
		.thdp-pay-accr,
		#brxe-kpgkgw {
		    position: relative;
		}
		.thdp-ship-sec-mushroom {
		    position: absolute;
		    left: 70px;
		    bottom: 0;
		    width: 100px;
		}
		#brxe-rplvlx {
		    position: absolute;
		    left: 0;
		}
		#brxe-qkuqzp {
		    position: absolute;
		    bottom: 300px;
		    right: 0;
		}
		#brxe-kpgkgw {
		    background: url(https://thedrops.eu/wp-content/uploads/Container.png);
		    padding: 100px;
		    background-size: cover;
		    background-repeat: no-repeat;
		    background-position: center;
		    border-radius: 20px;
		}
		.thdp-pay-img-1 {
		    position: absolute;
		    left: 0;
		}
		.thdp-pay-img-2 {
		    position: absolute;
		    right: 0;
		    bottom: 126px;
		    z-index: 0;
		    width: 308px;
		}
		.thdp-pay-sec-flower {
		    position: absolute;
		    left: 150px;
		    bottom: 0;
		}
		.thdp-rr-accr {
		    padding-top: 80px;
		}
		.thdp-rr-accr #brxe-idyguq {
		    position: relative;
		    background: url(https://thedrops.eu/wp-content/uploads/Vector-8.svg);
		    padding: 180px 0px;
		    padding-bottom: 0px;
		    background-size: contain;
		    background-repeat: no-repeat;
		    /*max-width: 1300px;*/
		    height: 1330px;
		    width: 95%;
		}
		.thdp-abt-accr {
		    margin-top: -150px;
		}
		.thdp-rr-cloud-1 {
		    position: absolute;
		    left: 0;
		    top: 25px;
		    width: 300px;
		}
		.thdp-rr-cloud-2 {
		    right: 36px;
		    top: 888px;
		    position: absolute;
		    width: 150px;
		}
		.thdp-rr-drop-1 {
		    position: absolute;
		    right: 20px;
		    top: 105px;
		    width: 200px;
		}
		.thdp-rr-drop-2 {
		    position: absolute;
		    left: 0;
		    top: 400px;
		    width: 200px;
		}
		.brxe-section #brxe-wmokst {
		    row-gap: 25px;
		}
		#brxe-bwdpwo {
		    background: url(https://thedrops.eu/wp-content/uploads/Frame-1321315016.png);
		    padding: 100px;
		    background-size: cover;
		    background-repeat: no-repeat;
		    background-position: center;
		    border-radius: 20px;
		}
		#brxe-lyowld {
		    margin: 0 auto;
		}
		#brxe-fbztxx {
		    width: 100%;
		    text-align: center;
		    height: 55px;
		    /*font-family: 'Nikukyu';*/
		    font-style: normal;
		    font-weight: 400;
		    font-size: 18px;
		    line-height: 27px;
		    text-align: center;
		    color: #132E5D;
		}
		.thdp-cont-btn {
		    border-radius: 100px;
		    padding: 16px 26px;
		    line-height: 18px;
		    border: 1px solid #000;
		    box-shadow: 2px 2px 1.3px 0 rgba(19, 35, 93, 0.3);
		    /*-webkit-text-stroke: 0.7px #272525;
		    text-shadow: 0.5px 1.7px #272525;*/
		    -webkit-text-stroke: 2px #272525;
		    text-shadow: 2px 1.7px #272525;
		    position: relative;
		    white-space: nowrap;
		    text-align: center;
		    text-decoration: none;
		    display: inline-flex;
		    align-items: center;
		    justify-content: center;
		    font-family: 'DynaPuff';
		    font-style: normal;
		    font-weight: 600;
		    font-size: 20px;
		    line-height: 24px;
		    leading-trim: both;
		    text-edge: cap;
		    letter-spacing: 0.02em;
		    text-transform: uppercase;
		    color: #F7ECE3;
		    background: #6184C2;
		    padding-left: 57px;
		    margin: 0 auto;
		    margin-top: 30px;
		}
		.thdp-cont-btn::before {
		    color: inherit;
		    content: attr(data-text);
		    position: absolute;
		    top: 0;
		    left: 0;
		    z-index: 1;
		    width: 100%;
		    height: 100%;
		    padding: inherit;
		    text-shadow: none;
		    -webkit-text-stroke: 0;
		}
		.thdp-nh-section {
			position: relative;
		    padding-bottom: 150px;
		}
		.thdp-nh-flw-1 {
			position: absolute;
		    left: 200px;
		    top: 50px;
		}
		.thdp-nh-flw-2 {
			position: absolute;
		    top: 200px;
		    width: 20px;
		    left: 97px;
		}
		.thdp-nh-flw-3 {
		    position: absolute;
		    bottom: 150px;
		    left: 260px;
		}
		.thdp-nh-flw-4 {
		    position: absolute;
		    right: 300px;
		    top: 171px;
		}
		.thdp-nh-flw-5 {
		    position: absolute;
		    right: 60px;
		    top: 385px;
		}
		.thdp-nh-flw-6 {
		    position: absolute;
		    right: 200px;
		    bottom: 75px;
		    width: 30px;
		}
		#brxe-cqlekx {
		    left: 581px;
		    position: absolute;
		    bottom: 158px;
		    z-index: 1000;
		}
		.thdp-nh-cloud-1 {
		    position: absolute;
		    right: 100px;
		}
		.thdp-nh-cloud-2 {
		    position: absolute;
		    left: 88px;
		    top: 362px;
		}
		.thdp-pay-sec-flower-1 {
		    position: relative;
		    right: -200px;
		}
		.thdp-con-wt-img {
			position: absolute;
		    left: 7px;
		    top: 8px;
		}
		#brxe-utilbf {
			width: 100%;
		    text-align: center;
		}
		.thdp-faq-main-mob-heading-1,
		.thdp-faq-main-mob-heading-2 {
		    display: none;
		}
        .accordion-content-wrapper a {
            text-decoration: underline;
            font-weight: 500;
        }
		@media only screen and ( max-width: 1024px ) {

			.thdp-pay-img-2 {
			    right: -37px;
			    bottom: 126px;
			    z-index: 0;
			    width: 167px;
			}
			.thdp-rr-cloud-1 {
			    position: absolute;
			    left: 8px;
			    top: 48px;
			    width: 200px;
			    z-index: -1;
			}
			.thdp-rr-drop-1 {
			    position: absolute;
			    right: -44px;
			    top: 85px;
			    width: 200px;
			    z-index: -1;
			}
			.thdp-rr-drop-2 {
			    position: absolute;
			    left: -44px;
			    top: 360px;
			    width: 200px;
			    z-index: -1;
			}
			.thdp-rr-cloud-2 {
			    right: 36px;
			    top: 760px;
			    position: absolute;
			    width: 100px;
			}
			.thdp-rr-accr #brxe-idyguq {
				height: 1200px;
				padding-top: 80px;
			    background-size: cover;
			    background-position: center;
			}
			#brxe-rplvlx {
			    left: 0;
			    top: -40px;
			}
			#brxe-qkuqzp {
			    position: absolute;
			    bottom: 20px;
			    right: 0;
			    width: 200px;
			}
			.thdp-ship-sec-mushroom {
			    position: absolute;
			    left: 30px;
			    bottom: 2px;
			    width: 80px;
			}
			.thdp-rr-accr #brxe-wmokst {
		        row-gap: 20px;
		    }
		}
		@media only screen and ( max-width: 850px ) {

			.brxe-image.thdp-ques-1 {
			    left: 40px;
			    bottom: -32px;
			    width: 100px;
			}
			.brxe-image.thdp-ques-2 {
			    right: 21px;
			    bottom: 62px;
			    width: 100px;
			}
			.thdp-faq-card {
			    width: 45% !important;
				height: 193px;
			}
			.thdp-faq-card > img {
				margin-top: -8px;
			}
			.thdp-card-title {
				margin-top: 5px;
		    	margin-bottom: 10px;
			}
			#brxe-rplvlx {
			    position: absolute;
			    left: -43px;
			    top: -45px;
			}
			#brxe-qkuqzp {
			    position: absolute;
			    bottom: 19px;
			    right: -43px;
			    width: 200px;
			}
			#brxe-kpgkgw {
				padding-right: 40px;
		    	padding-left: 40px;
			}
			.thdp-ship-sec-mushroom {
			    left: 30px;
			    bottom: 3px;
			    width: 70px;
			}
			.thdp-pay-img-1 {
		    	position: absolute;
			    left: 0;
			    top: -81px;
			    width: 200px;
			}
			.thdp-pay-img-2 {
				display: none;
			}
			.thdp-rr-accr #brxe-idyguq {
			    width: 100% !important;
			    background-size: cover;
		        background-position: center;
			}
			.thdp-rr-accr #brxe-zwtsxa {
		        width: 90%;
		    }
		    .thdp-rr-cloud-1 {
			    position: absolute;
			    left: -72px;
			    top: -100px;
			    width: 200px;
			}
			.thdp-rr-drop-1 {
				display: none;
			}
			.thdp-rr-accr #brxe-idyguq {
			   	 padding: 100px 0px;
			}
			.thdp-rr-drop-2 {
				z-index: -1;
			}
			.thdp-rr-cloud-2 {
			    top: 670px;
			    width: 150px;
			    z-index: -1;
			}
			.thdp-rr-accr #brxe-idyguq {
				height: 1150px;
			}
			#brxe-bwdpwo {
				padding-left: 40px;
		    	padding-right: 40px;
			}
			.thdp-nh-cloud-2 {
			    left: 28px;
			    top: 440px;
			    width: 200px;
			}
			.thdp-nh-flw-3 {
			    position: absolute;
			    bottom: 50px;
			    left: 160px;
			}
		    @media only screen and ( max-width: 550px ) {
		        .thdp-nh-flw-5,
		        .thdp-nh-cloud-2,
		        .thdp-pay-img-1,
		        #brxe-qkuqzp,
		        #brxe-rplvlx,
		        /*.thdp-faq-sec-heading-dp,*/
		        .brxe-image.thdp-ques-1 {
		            display: none;
		        }
		        .thdp-faq-sec-heading-dp {
		            font-size: 52.4444px;
		            display: inline-block;
		            word-break: break-word;
		            white-space: unset;
		            line-height: 65px;
		            margin-bottom: 0;
		            padding: 16px 10px;
		        }
		        .thdp-faq-main-heading {
		            font-size: 52.4444px;
		        }
		        .thdp-faq-main-mob-heading {
		            display: block;
		        }   
		        .brxe-image.thdp-ques-2 {
		            right: 154px;
		            bottom: 250px;
		            width: 80px;
		        }
		        .thdp-faq-card {
		            width: 90% !important;
		        }
		        .thdp-ship-heading,
		        .thdp-faq-sec-heading {
		            font-size: 43px;
		        }
		        .thdp-ship-accr {
		            padding-bottom: 80px;
		        }
		        .thdp-ship-accr #brxe-kcpnzv {
		            row-gap: 0;
		        }
		        .thdp-ship-accr #brxe-kpgkgw {
		            padding-bottom: 120px;
		        }
		        .thdp-pay-sec-flower-1 {
		            right: -140px;
		        }
		        .thdp-pay-sec-flower {
		            left: 35px;
		            bottom: -50px;
		        }
		        .thdp-rr-accr #brxe-idyguq {
		            background: url(https://thedrops.eu/wp-content/uploads/Vector-8-1.svg); 
		            height: 1355px;
		            background-size: cover;
		            background-position: center; 
		        }
		        .thdp-rr-cloud-1 {
		            left: 0;
		            top: 7px;
		            width: 150px;
		            z-index: -1;
		        }
		        .thdp-rr-cloud-2 {
		            top: unset;
		            width: 116px;
		            z-index: -1;
		            bottom: 250px;
		            transform: rotate(16deg);
		        }
		        .thdp-rr-drop-2 {
		            left: 0px;
		            top: unset;
		            width: 150px;
		            z-index: -1;
		            bottom: 200px;
		            transform: rotate(7deg);
		        }
		        .thdp-abt-accr {
		            margin-top: -54px;
		        }
		        .thdp-whl-accr {
		            margin-top: 80px;
		        }
		        .thdp-whl-accr #brxe-bwdpwo {
		            padding-bottom: 100px;
		        }
		        .thdp-nh-cloud-1 {
		            z-index: 1;
		            right: 20px;
		            width: 150px;
		        }
		        .thdp-nh-flw-1 {
		            left: 20px;
		            top: 0;
		        }
		        .thdp-nh-flw-4 {
		            right: 20px;
		            top: 100px;
		        }
		        .thdp-nh-flw-2 {
		            top: 280px;
		            width: 20px;
		            left: 97px;
		        }
		        main#brx-content {
		            overflow: visible !important;
		        }
		        .thdp-nh-flw-6 {
		            right: 50px;
		            bottom: -100px;
		            width: 30px;
		            z-index: 100;
		        }
		        .thdp-nh-flw-3 {
		            position: absolute;
		            bottom: 50px;
		            left: 50px;
		        }
		        .thdp-melting-div {
		            background-position: 380px;
		        }
		        .thdp-faq-sec-heading {
		            white-space: unset;
		            display: inline-block;
		            word-break: break-word;
		            line-height: 50px;
		        }
		        .thdp-ship-heading {
		            padding: 0px;
		        }
		        .thdp-nh-heading {
		            margin-bottom: 0px;
		        }
		    }
		    @media only screen and ( max-width: 350px ) {
		        .thdp-faq-main-heading {
		            font-size: 44px;
		        }
		        .brxe-image.thdp-ques-2 {
		            right: 130px;
		        }
		        .thdp-card-title {
		            margin-bottom: 25px;
		        }
		        #brxe-kpgkgw,
		        #brxe-bwdpwo {
		            width: calc(100% - 17px) !important;
		        }
		        #brxe-bwdpwo,
		        #brxe-kpgkgw {
		            padding-right: 20px;
		            padding-left: 20px;
		        }
		        #brxe-fbztxx {
		            height: auto;
		        }
		    }
		}
        </style>
     
    <?php
    }
    /* FAQs page css ends here */
    /* Signup page css starts here */
    if(tdc_url_has('/sign-up')) {
    ?>  
        <style>
            #brxe-abaeb8 .submit-button-wrapper button.sending::before {
                display:none;
            }
            .thdp-apple-btn {
                display: none !important;
            }
            .thdp-sg-heading {
                width: 100%;
                text-align: center;
                color: #F7ECE3;
                padding: 16px 26px;
                line-height: 18px;
                /*-webkit-text-stroke: 0.7px #272525;
                text-shadow: 0.5px 1.7px #272525;*/
                -webkit-text-stroke: 2.2px #272525;
                text-shadow: 1.5px 1.7px #272525;
                position: relative;
                text-transform: uppercase;
                white-space: nowrap;
                text-align: center;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;

                font-family: 'DynaPuff';
                font-style: normal;
                font-weight: 500;
                font-size: 63px;
                line-height: 91px;
                leading-trim: both;
                text-edge: cap;
                text-align: center;
                letter-spacing: -1px;
                color: #DB6C36;
                margin-bottom: 50px;
            }
            .thdp-sg-heading::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            .thdr-login-form-cont {
                flex-direction: unset;
                justify-content: center;
            }
            .thdp-signup-form {
                box-sizing: border-box;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 32px;
                width: 448px;
                /*height: 776.64px;*/
                background: #FFFCFA;
                border: 0.896px solid #132E5D;
                box-shadow: 1.792px 1.792px 1.1648px rgba(19, 46, 93, 0.3);
                border-radius: 7.168px;
                flex: none;
                order: 0;
                flex-grow: 0;
                margin-bottom: 150px;
            }
            .thdp-signup-form > .form-group:nth-child(1) label {
                font-size: 24px;
                line-height: 29px;
                leading-trim: both;
                text-edge: cap;
                color: #132E5D;
                flex: none;
                order: 0;
                flex-grow: 0;
                text-transform: none;
                font-weight: 700;
                text-align: center;
                margin-bottom: 35px;
                width: 100%;
                display: inline-table;
            }
            .thdp-cwg-btn,
            .thdp-apple-btn {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                padding: 12px 0px;
                gap: 8.96px;
                /*width: 390.66px;*/
                width: 100%;
                height: 50px;
                background: #FFFCFA;
                border: 1px solid rgba(19, 46, 93, 0.4);
                box-shadow: 1px 1px 1px rgba(19, 46, 93, 0.2);
                /*border: 0.5376px solid rgb(19 46 93 / 50%);
                box-shadow: 1.792px 1.792px 1.1648px rgba(19, 46, 93, 0.3);*/
                border-radius: 1.792px;
                flex: none;
                order: 0;
                align-self: stretch;
                flex-grow: 0;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 20px;
                color: rgba(19, 46, 93, 0.8);
                margin-bottom: 15px;
            }
            .thdp-or-row {
                margin: 8px 0px;
            }
            .thdp-lg-or-l1 {
                width: 100%;
                display: inline-block;
                height: 2px;
                top: 2px;
                position: relative;
                background: rgb(19 46 93 / 11%);
            }
            .thdp-signup-form > .form-group label {
                height: 12px;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 700;
                font-size: 16.128px;
                line-height: 20px;
                leading-trim: both;
                text-edge: cap;
                display: flex;
                align-items: center;
                color: #132E5D;
                flex: none;
                order: 0;
                flex-grow: 0;
                text-transform: none;
                margin-bottom: 8px;
            }
            .thdp-signup-form > .form-group input {
                display: flex;
                flex-direction: row;
                align-items: flex-start;
                padding: 10.752px 0px 10.752px 14.336px;
                gap: 8.96px;
                width: 100%;
                height: 41.22px;
                background: #FFFCFA;
                border: 1px solid rgba(19, 46, 93, 0.4);
                box-shadow: 1px 1px 1px rgba(19, 46, 93, 0.2);
                border-radius: 1.792px;
                flex: none;
                order: 1;
                align-self: stretch;
                flex-grow: 0;
            }
            .thdp-signup-form > .form-group:nth-child(2),
            .thdp-signup-form > .form-group:nth-child(3) {
                width: 48%;
            }
            .thdp-signup-form .submit-button-wrapper {
                width: 100%;
                margin-top: 30px;
            }
            .thdp-signup-form .submit-button-wrapper button {
                background-color: #DB6C36;
                border-radius: 100px;
                color: #F7ECE3;
                font-family: "Dynapuff";
                font-size: 24px;
                letter-spacing: 2%;
                padding: 16px 26px;
                line-height: 18px;
                border: 1px solid #000;
                box-shadow: 2px 2px 1.3px 0 rgba(19, 35, 93, 0.3);
                /*-webkit-text-stroke: 0.7px #272525;
                text-shadow: 0.5px 1.7px #272525;*/
                -webkit-text-stroke: 2px #272525;
                text-shadow: 2px 1.7px #272525;
                position: relative;
                text-transform: uppercase;
                white-space: nowrap;
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .thdp-signup-form .submit-button-wrapper button::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            .thdp-signup-form > .form-group:nth-child(7) {
                order: 1;
                padding: 0;
            }
            .thdp-create-account-row {
                width: 100%;
                height: 20px;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 400;
                font-size: 12.544px;
                line-height: 20px;
                text-align: center;
                color: #132E5D;
                flex: none;
                order: 1;
                align-self: stretch;
                flex-grow: 0;
                margin-top: 24px;
            }
            .thdp-cya-span {
                font-weight: 500;
                text-decoration: underline;
            }
            #brx-footer #brxe-0bf965 {
                background-position: top center;
            }
            #brxe-afcfa4 {
                padding-top: 5px;
            }
            #brx-content {
                overflow: unset !important;
            }
            .thdp-login-flw {
                display: none;
                position: absolute;
                bottom: -31px;
                right: 45px;
                width: 100px;
                transform: rotate(6deg);
                z-index: 1;
            }
            @media only screen and ( max-width: 1440px ) {
                .thdp-login-flw {
                    display: block;
                }
            }
            @media only screen and ( max-width: 1280px ) {
                .thdp-login-flw {
                    bottom: -22px;
                }
            }
            @media only screen and ( max-width: 1024px ) {
                .thdp-login-flw {
                    bottom: -2px;
                    transform: rotate(1deg);
                }
                #brx-footer #brxe-0bf965 {
                    background-position: 20%;
                }
            }
            @media only screen and ( max-width: 768px ) {
                .thdp-login-flw {
                    bottom: -3px;
                    transform: rotate(358deg);
                }
            }
            @media only screen and ( max-width: 500px ) {
                .thdp-signup-form {
                    width: 90%;
                }
                .thdp-login-flw {
                    bottom: -49px;
                    transform: rotate(352deg);
                    right: 14px;
                    width: 70px;
                }
                #brxe-afcfa4 {
                    padding-top: 38px;
                }
                #brx-footer #brxe-0bf965 {
                    background-position: 23%;
                }
            }
            @media only screen and ( max-width: 320px ) {
                .thdp-signup-form {
                    padding: 20px;
                }
                .thdp-login-flw {
                    bottom: -52px;
                }
            }
        </style>
    <?php    
    }

    /* Menu logo change css ends here */
    if (tdc_url_has('/product') || tdc_url_has('/info') || tdc_url_has('/faqs') || tdc_url_has('/login')) {?>

        <style>
            <!-- Header -->
            body:not(.mini-cart-opened) #brx-header {
                z-index: 9;
            }

            .brxe-logo {
                transition: opacity 0.4s ease !important;
            }

            .brxe-logo:first-child,
            body.menu-opened .brxe-logo:nth-child(2) {
                display: none !important;
                opacity: 0 !important;
            }

            .brxe-logo:nth-child(2),
            body.menu-opened .brxe-logo:first-child {
                display: block !important;
                opacity: 1 !important;
            }
        </style>
    <?php
    }
    /* Menu logo change css ends here */

    /* Signup page css starts here */
    if(tdc_url_has('/login')) {
    ?>
        <style>
            #brxe-thnqfn .submit-button-wrapper button.sending::before {
                display:none;
            }
            .thdp-apple-btn {
                display: none !important;
            }
            .thdp-login-bg {
                background: url(/wp-content/uploads/Cover-Image-1.png);
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center left;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 1274px;
            }
            #brx-header,
            #brxe-bqcylh,
            #brxe-0bf965 {
                background-color: transparent;
            }
            body.page-id-3776 .brxe-logo:first-child {
                display: none;
            }
            body:not(.menu-opened) .header-main-section {
                background-color: #132E5D !important;
            }
            body:not(.menu-opened) .menu-column .brxe-nav-menu .bricks-mobile-menu-toggle {
                color: #fff !important;
            }
            body:not(.menu-opened) .profile-column .brxe-woocommerce-mini-cart.mini-cart .mini-cart-link .cart-icon svg path, 
            body:not(.menu-opened) svg.my-account path {
                stroke: #fff;
            }
            body:not(.menu-opened) .profile-column .separator {
                background-color: #fff !important;
            }
            main#brx-content {
                background-color: transparent;
            }
            .thdr-login-form-cont {
                flex-direction: unset;
                justify-content: end;
            }
            #brxe-thnqfn {
                width: 40%;
                margin: 100px 0px;
                margin-bottom: 130px;
                background-color: #FFFCFA;
                padding: 25px;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 28.672px;
                width: 448px;
                background: #FFFCFA;
                border: 0.896px solid #132E5D;
                box-shadow: 1.792px 1.792px 1.1648px rgba(19, 46, 93, 0.3);
                border-radius: 7.168px;
            }
            #brxe-thnqfn > .form-group:nth-child(1) label {
                font-size: 24px;
                line-height: 29px;
                leading-trim: both;
                text-edge: cap;
                color: #132E5D;
                flex: none;
                order: 0;
                flex-grow: 0;
                text-transform: capitalize;
                font-weight: 700;
                text-align: center;
                margin-bottom: 25px;
            }
            .thdp-cwg-btn,
            .thdp-apple-btn {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                padding: 12px 0px;
                gap: 8.96px;
                /*width: 390.66px;*/
                width: 100%;
                height: 50px;
                background: #FFFCFA;
                /*border: 0.5376px solid rgba(19, 46, 93, 0.8);*/
                border: 0.5376px solid rgb(19 46 93 / 50%);
                box-shadow: 1.792px 1.792px 1.1648px rgba(19, 46, 93, 0.3);
                border-radius: 1.792px;
                /* Inside auto layout */
                flex: none;
                order: 0;
                align-self: stretch;
                flex-grow: 0;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 20px;
                color: rgba(19, 46, 93, 0.8);
                margin-bottom: 15px;
            }
            .thdp-lg-or {
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 400;
                font-size: 14.336px;
                line-height: 20px;
                leading-trim: both;
                text-edge: cap;
                text-align: center;
                color: rgba(19, 46, 93, 0.4);
            }
            .thdp-or-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .thdp-lg-or-l1,
            .thdp-lg-or-l2 {
                width: 175px;
                display: inline-block;
                height: 2px;
                top: 2px;
                position: relative;
                background: rgb(19 46 93 / 15%);
            }
            .thdp-or-row {
                margin-top: 20px;
            }
            #brxe-thnqfn > .form-group:nth-child(6) {
                order: 1;
                padding: 0;
            }
            .thdp-create-account-row {
                width: 100%;
                height: 20px;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 400;
                font-size: 12.544px;
                line-height: 20px;
                text-align: center;
                color: #132E5D;
                flex: none;
                order: 1;
                align-self: stretch;
                flex-grow: 0;
                margin-top: 24px;
            }
            .thdp-cya-span {
                font-weight: 500;
                text-decoration: underline;
            }
            #brxe-thnqfn > .form-group:nth-child(2) {
                text-align: end;
                position: relative;
            }
            #brxe-thnqfn > .form-group:nth-child(2) label {
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 400;
                font-size: 13px;
                align-items: center;
                color: #132E5D;
            }
            #brxe-thnqfn > .form-group:nth-child(2) .options-wrapper {
                position: absolute;
                width: 100%;
                top: 18px;
            }
            #brxe-thnqfn > .form-group:nth-child(3) label,
            #brxe-thnqfn > .form-group:nth-child(5) label {
                height: 20px;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 700;
                font-size: 16.128px;
                line-height: 20px;
                display: flex;
                align-items: center;
                color: #132E5D;
                flex: none;
                order: 0;
                flex-grow: 0;
                text-transform: capitalize;
                margin-bottom: 10px;
            }
            #brxe-thnqfn > .form-group:nth-child(3) input,
            #brxe-thnqfn > .form-group:nth-child(5) input {
                display: flex;
                flex-direction: row;
                align-items: flex-start;
                padding: 10.752px 14.336px;
                gap: 8.96px;
                /*width: 390.66px;*/
                width: 100%;
                height: 41.22px;
                background: #FFFCFA;
                /*border: 0.5376px solid rgba(19, 46, 93, 0.8);*/
                border: 0.5376px solid rgb(19 46 93 / 50%);
                box-shadow: 1.792px 1.792px 1.1648px rgba(19, 46, 93, 0.3);
                border-radius: 1.792px;
                flex: none;
                order: 0;
                align-self: stretch;
                flex-grow: 0;
            }
            #brxe-thnqfn .submit-button-wrapper {
                width: 100%;
                margin-top: 30px;
            }
            #brxe-thnqfn .submit-button-wrapper button {
                background-color: #DB6C36;
                border-radius: 100px;
                color: #F7ECE3;
                font-family: "Dynapuff";
                font-size: 24px;
                letter-spacing: 2%;
                padding: 16px 26px;
                line-height: 18px;
                border: 1px solid #000;
                box-shadow: 2px 2px 1.3px 0 rgba(19, 35, 93, 0.3);
                -webkit-text-stroke: 0.7px #272525;
                text-shadow: 0.5px 1.7px #272525;
                position: relative;
                text-transform: uppercase;
                white-space: nowrap;
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            #brxe-thnqfn .submit-button-wrapper button::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            #brxe-thnqfn > .form-group:nth-child(4) {
                position: relative;
                padding: 5px 0;
            }
            .thdp-forgot-pass {
                position: absolute;
                width: 100%;
                text-align: end;
                top: 9px;
                height: 20px;
                font-family: 'Satoshi-Variable';
                font-style: normal;
                font-weight: 400;
                font-size: 13px;
                line-height: 20px;
                text-decoration-line: underline;
                color: #132E5D;
            }
            #brx-footer #brxe-0bf965 {
                background-position: top center;
            }
            #brxe-afcfa4 {
                /*margin-top: -24px;*/
                padding-top: 5px;
            }
            #brxe-0bf965 {
                overflow: unset;
            }
            .thdp-login-flw {
                display: none;
            }
            @media only screen and ( max-width: 1280px ) {
                #brxe-afcfa4 {
                    padding-top: 36px;
                }
            }
            @media only screen and ( max-width: 1120px ) {
                #brxe-thnqfn {
                    margin-right: 80px;
                }
            }
            @media only screen and ( max-width: 1024px ) {
                #brxe-thnqfn {
                    margin-right: unset;
                }
                .thdr-login-form-cont {
                    justify-content: center;
                }
                .thdp-login-bg {
                    background-position: 19%;
                }
            }
            @media only screen and ( max-width: 768px ) {
                #brx-footer #brxe-0bf965 {
                    background-position: 42%;
                }
                #brxe-afcfa4 {
                    margin-top: -20px;
                    padding-top: 0;
                }
                .thdp-login-flw {
                    display: block;
                    position: absolute;
                    bottom: -1px;
                    right: 16px;
                    width: 95px;
                }
                .thdp-login-bg {
                    background-position: 16%;
                }
            }
            @media only screen and ( max-width: 600px ) {
                #brxe-afcfa4 {
                    column-gap: 55px;
                }
            }
            @media only screen and ( max-width: 450px ) {
                #brxe-thnqfn {
                    width: 90%;
                }
                #brx-footer #brxe-0bf965 {
                    background-position: 31%;
                }
                #brxe-afcfa4 {
                    margin-top: 20px;
                    padding-top: 0;
                }
                .thdp-login-flw {
                    bottom: -28px;
                    right: 6px;
                    width: 80px;
                }
                .thdp-login-bg {
                    background-position: -343px 80px;
                }
                #brx-content {
                    overflow: unset !important;
                }
                #brxe-afcfa4 {
                    column-gap: 35px;
                }
            }
            @media only screen and ( max-width: 320px ) {
                .thdp-login-flw {
                    display: none;
                }
                #brxe-thnqfn > .form-group:nth-child(2) {
                    text-align: left;
                }
                #brxe-thnqfn > .form-group:nth-child(2) .options-wrapper {
                    top: 45px;
                }
                #brxe-thnqfn > .form-group:nth-child(3) input {
                    margin-top: 24px;
                }
            }
        </style>
    <?php
    /* Login page css ends here */
    }

    if (tdc_url_has('/product')) {
        ?>
        <style>
            /* Header */
            body:not(.mini-cart-opened) #brx-header {
                z-index: 9;
            }
            .brxe-logo {
                transition: opacity 0.4s ease !important;
            }
            .brxe-logo:first-child,
            body.menu-opened .brxe-logo:nth-child(2) {
                display: none !important;
                opacity: 0 !important;
            }
            .brxe-logo:nth-child(2),
            body.menu-opened .brxe-logo:first-child {
                display: block !important;
                opacity: 1 !important;
            }
            body .header-main-section {
                transition: background-color 0.4s ease !important;
            }
            body header > section:last-child { /* Meltdown effect */
                opacity: 1 !important;
                transition: opacity 0.4s ease !important;
            }
            body.mini-cart-opened header > section:last-child {
                z-index: unset !important;
            }
            body.menu-opened header > section:last-child { /* Meltdown effect */
                opacity: 0 !important;
                transition: opacity 0.4s ease !important;
            }
            body:not(.menu-opened) .header-main-section {
                background-color: var(--bricks-color-aksuoy) !important;
            }
            .header-inner-section {
                margin-bottom: -1px !important;
            }
            body .menu-column .brxe-nav-menu,
            body .menu-column .menu-text,
            body .profile-column .language-switcher {
                transition: color 0.5s ease !important;
            }
            body:not(.menu-opened) .menu-column .brxe-nav-menu,
            body:not(.menu-opened) .menu-column .brxe-nav-menu .bricks-mobile-menu-toggle,
            body:not(.menu-opened) .menu-column .menu-text {
                color: #fff !important;
            }
            body:not(.menu-opened) .menu-column .brxe-nav-menu.show-mobile-menu .bricks-mobile-menu-toggle {
                color: #fff !important;
            }
            body:not(.menu-opened) .profile-column .language-switcher {
                color: #fff !important;
            }
            body .profile-column .separator {
                transition: background-color 0.5s ease !important;
            }
            body:not(.menu-opened) .profile-column .separator {
                background-color: #fff !important;
            }
            body .profile-column .brxe-woocommerce-mini-cart.mini-cart .mini-cart-link .cart-icon svg path,
            body svg.my-account path {
                transition: stroke 0.5s ease;
            }
            body:not(.menu-opened) .profile-column .brxe-woocommerce-mini-cart.mini-cart .mini-cart-link .cart-icon svg path,
            body:not(.menu-opened) svg.my-account path {
                stroke: #fff;
            }
            .woocommerce-product-gallery {
                overflow: hidden;
                width: 100%;
                margin: 0 auto;
            }
            .woocommerce-product-gallery__wrapper {
                display: flex;
                flex-wrap: nowrap;
                transition: transform 0.5s ease;
            }
            .brxe-product-gallery .woocommerce-product-gallery .flex-viewport,
            .brxe-product-gallery .woocommerce-product-gallery .flex-control-nav {
                max-height: 430px !important;
            }
            .brxe-product-gallery .woocommerce-product-gallery .flex-viewport {
                height: calc(100% - 10px) !important;
            }
            .brxe-product-gallery .woocommerce-product-gallery .flex-viewport .woocommerce-product-gallery__wrapper,
            .brxe-product-gallery .woocommerce-product-gallery .flex-viewport .woocommerce-product-gallery__image {
                height: 100% !important;
            }
            .brxe-product-gallery .woocommerce-product-gallery .flex-viewport .woocommerce-product-gallery__image {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .brxe-product-gallery .woocommerce-product-gallery .flex-viewport,
            .brxe-product-gallery .woocommerce-product-gallery .flex-control-nav li,
            .brxe-product-gallery .brx-product-gallery-thumbnail-slider .flex-viewport .woocommerce-product-gallery__image {
                background-color: rgba(223, 225, 232, 0.4) !important;
            }
            .brxe-product-gallery .woocommerce-product-gallery .flex-viewport {
                border: 1px solid rgba(19, 35, 93, 0.4) !important;
                box-shadow: 3px 3px 1px 0 rgba(19, 35, 93, 0.4) !important;
            }
            .brxe-product-rating {
                font-weight: 700;
            }
            .woocommerce-product-details__short-description ul, .woocommerce-product-details__short-description ol {
                padding-inline-start: 15px;
            }
            @media (max-width: 430px) {
                .brxe-product-gallery.thumbnail-slider[data-pos="left"],
                .brxe-product-gallery.thumbnail-slider[data-pos="right"] {
                    flex-direction: column;
                }
            }
            .gmrbw-variation-image img {
                display: none;
            }
            .gmrbw-variation-radio {
                border-bottom: 1px solid #7c999b;
                padding: 10px 16px;
                padding-right: 32px;
            }
            .gmrbw-variations.gmrbw-variations-radio {
                border-top: 1px solid #7c999b;
                border-right: 1px solid #7c999b;
                border-left: 1px solid #7c999b;
            }
            .gmrbw-variation-description {
                display:block;
            }
            .gmrbw-instock .gmrbw-variation-availability {
                color: #afb2b5;
                font-family: 'Satoshi-Variable';
                font-size: 16px;
                font-weight: 500;
            }
            .woocommerce-variation.single_variation,
            .gmrbw-variation-price del,
            .woocommerce-variation-price,
            .woocommerce-variation-add-to-cart .quantity {
                display: none !important;
            }
            .gmrbw-variation-info-inner {
                justify-content: flex-start;
                color: #132E5D;
                font-weight: 800;
                font-size: 18px;
                text-decoration: none;
                font-family: 'Satoshi-Variable';
            }
            .gmrbw-variation-info-inner * {
                text-decoration: none;
            }
            .gmrbw-variation-price {
                margin-left: 3px;
            }
            .gmrbw-variation-selector {
                display: flex;
                align-items: center;
            }
            .gmrbw-variation-selector input[type="radio"] {
                width: 20px;
                height: 20px;
                accent-color: #000;
            }
            .brxe-product-add-to-cart .cart .single_add_to_cart_button,
            .heading-the-drops,
            .heading-people span {
                position: relative;
                -webkit-text-stroke: 1px #000;
            }
            .brxe-product-add-to-cart .cart .single_add_to_cart_button {
                box-shadow: 2px 2px 1.3px 0 rgba(19, 35, 93, 0.3);
                margin-top: 30px;
            }
            .heading-the-drops,
            .heading-people span {
                -webkit-text-stroke: 2px #132e5d;
            }
            .heading-people span {
                display: inline-block;
                font-family: "DynaPuff";
                font-weight: 700;
                color: #008e53;
                text-transform: uppercase;
                text-shadow: 1.5px 2.5px #132e5d;
            }
            .brxe-product-add-to-cart .cart .single_add_to_cart_button::before,
            .heading-the-drops::before,
            .heading-people span::before {
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                color: inherit;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            #brx-footer > section {
                background-color: #6184c2 !important;
            }
            .star-rating span:before {
                color: #DB6C36;
            }
            .woocommerce-product-rating .star-rating {
                margin-right: 10px;
            }
            .woocommerce-product-rating .woocommerce-review-link {
                /*padding-bottom: 20px;*/
                color: #132e5d;
                font-size: 14px;
                font-family: "Satoshi-Variable";
                text-decoration: underline;
                font-weight: 700;
            }
            .wfc-cart-form__cart-item .woocommerce-Price-currencySymbol {
                font-size: 17.63px;
                vertical-align: top;
            }
        </style>
        <?php
    }
    if (tdc_url_has('/products')) {
    ?>
        <style>
            #brx-footer>section {
                background-color: inherit !important;
            }
        </style>
    <?php
    }
    elseif (tdc_url_has('/checkout')) {
        ?>
        <style>
            body.woocommerce-checkout {
                background-color: #f7ece3;
            }
            #brx-content.wordpress {
                width: 100%;
                max-width: 1220px;
                margin-top: 60px;
                margin-bottom: 140px;
                padding: 0 48px;
            }
            @media (max-width: 1280px) {
                #brx-content.wordpress {
                    max-width: 1204px;
                    padding: 0 40px;
                }
            }
            @media (max-width: 1024px) {
                #brx-content.wordpress {
                    max-width: calc(100% - 64px);
                    padding: 0;
                }
            }
            @media (max-width: 809px) {
                #brx-content.wordpress {
                    width: calc(100% - 64px);
                    max-width: 476px;
                    margin-top: 72px;
                }
            }
            @media (max-width: 430px) {
                #brx-content.wordpress {
                    width: 100%;
                    max-width: calc(100% - 40px);
                }
            }
            @media (max-width: 390px) {
                #brx-content.wordpress {
                    max-width: calc(100% - 36px);
                }
            }
            @media (max-width: 320px) {
                #brx-content.wordpress {
                    max-width: calc(100% - 32px);
                }
            }
            h1 {
                text-align: center;
                font-family: "Dynapuff";
                font-size: 63px;
                line-height: 46px;
                font-weight: 500;
                color: #db6c36;
                text-shadow: 2px 4px 0 #132e5d;
                -webkit-text-stroke: 1px #132e5d;
                position: relative;
                text-transform: uppercase;
                word-spacing: -1px;
            }
            h1::before {
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                color: inherit;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            @media (max-width: 1024px) {
                h1 {
                    font-size: 59px;
                    line-height: 43px;
                    word-spacing: -0.94px;
                }
            }
            @media (max-width: 430px) {
                h1 {
                    font-size: 46px;
                    line-height: 34px;
                    word-spacing: -0.92px;
                }
            }
            .wc-block-checkout.wp-block-woocommerce-checkout {
                padding-top: 120px;
                max-width: 100%;
                width: 100%;
            }
            @media (max-width: 809px) {
                .wc-block-checkout.wp-block-woocommerce-checkout {
                    padding-top: 72px;
                }
            }
            .wc-block-checkout.wc-block-components-sidebar-layout {
                margin-bottom: 0;
                justify-content: space-between;
            }
            @media (max-width: 809px) {
                .wc-block-checkout.wc-block-components-sidebar-layout {
                    flex-direction: column;
                    gap: 60px;
                }
            }
            @media (max-width: 430px) {
                .wc-block-checkout.wc-block-components-sidebar-layout {
                    gap: 41px;
                }
            }
            .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-main {
                width: 476px;
                padding-right: 0;
            }
            .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-sidebar {
                width: 455px;
                padding-left: 0;
                margin-top: 0;
                margin-bottom: 0;
            }
            @media (max-width: 1024px) {
                .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-main {
                    width: calc(50% - 24px);
                }
                .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-sidebar {
                    width: calc(50% - 24px);
                }
            }
            @media (max-width: 809px) {
                .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-main {
                    width: 100%;
                }
                .wc-block-checkout.wc-block-components-sidebar-layout .wc-block-components-sidebar {
                    width: 100%;
                }
            }
            .wc-block-components-form .wc-block-components-checkout-step#contact-fields,
            .wc-block-components-form .wc-block-components-checkout-step#shipping-fields,
            .wc-block-components-form .wc-block-components-checkout-step#billing-fields {
                margin-bottom: 36px;
            }
            .wc-block-components-checkout-step__heading {
                margin: 0 !important;
                margin-bottom: 8px !important;
            }
            #shipping-option .wc-block-components-checkout-step__heading {
                margin-bottom: 20px !important;
            }
            #payment-method .wc-block-components-checkout-step__heading {
                margin-bottom: 16px !important;
            }
            .wc-block-components-title.wc-block-components-title {
                font-family: "Satoshi-Variable" !important;
                font-size: 21px !important;
                line-height: 22px !important;
                font-weight: 700 !important;
                color: #132e5d !important;
            }
            .wc-block-components-checkout-step__description {
                font-family: "Satoshi-Variable";
                font-size: 16px;
                line-height: 22px;
                color: rgba(19, 46, 93, 0.8);
                margin-bottom: 20px;
            }
            .wc-blocks-components-select .wc-blocks-components-select__container {
                box-shadow: none !important;
                border: none !important;
                background: transparent !important;
                border-radius: 0 !important;
                height: unset !important;
            }
            .wc-block-components-address-form__country .wc-blocks-components-select .wc-blocks-components-select__container {
                margin-top: 0 !important;
            }
            .wc-block-components-form input,
            .wc-block-components-form textarea,
            .wc-block-components-form select {
                padding: 12px 16px !important;
                border: 0.6px solid rgba(19, 46, 93, 0.4) !important;
                box-shadow: 2px 2px 1.3px rgba(19, 46, 93, 0.3) !important;
                background-color: #FFFCFA !important;
                outline: none;
                border-radius: 2px !important;
                font-size: 16px !important;
                line-height: 22px !important;
                font-family: "Satoshi-Variable" !important;
                color: #132e5d !important;
                height: auto !important;
            }
            .wc-block-components-form input::placeholder,
            .wc-block-components-form textarea::placeholder {
                color: rgba(19, 46, 93, 0.4) !important;
            }
            .wc-block-components-form .wc-block-components-text-input label,
            .wc-block-components-form .wc-blocks-components-select label {
                display: none;
            }
            .wc-block-components-address-form__address_2-toggle {
                display: block !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 18px !important;
                font-weight: 500 !important;
                line-height: 22px !important;
                color: #132e5d !important;
                margin-top: 20px !important;
            }
            .wc-block-components-checkbox .wc-block-components-checkbox__label {
                font-size: 16px !important;
                line-height: 22px !important;
                color: #132e5d !important;
                font-family: "Satoshi-Variable" !important;
                font-weight: 500 !important;
            }
            .wc-block-checkout__use-address-for-billing .wc-block-components-checkbox__label {
                font-size: 18px !important;
            }
            .wc-block-components-checkbox label {
                align-items: center !important;
            }
            .wc-block-components-checkbox .wc-block-components-checkbox__input {
                width: 16px !important;
                height: 16px !important;
                padding: 0 !important;
                margin-right: 8px !important;
                border: 1.5px solid rgba(19, 46, 93, 0.4) !important;
                border-radius: 2px !important;
                box-shadow: none !important;
                min-width: 19px !important;
                min-height: 19px !important;
                outline: none !important;
                background-color: transparent !important;
            }
            .wc-block-components-checkbox .wc-block-components-checkbox__mark {
                min-width: 15px !important;
                min-height: 15px !important;
                width: 15px !important;
                height: 15px !important;
                margin: 0 !important;
                left: 2px;
                top: 2px;
            }
            .wc-block-components-radio-control {
                background-color: #EDE7E5;
                border-radius: 2px;
                box-shadow: 2px 2px 1.3px rgba(19, 46, 93, 0.3);
            }
            .wc-block-components-radio-control__option {
                background-color: transparent !important;
                box-shadow: none !important;
                border: none !important;
                padding: 16px !important;
                padding-left: 54px !important;
            }
            .wc-block-components-radio-control__label,
            .wc-block-components-radio-control__secondary-label {
                font-family: "Satoshi-Variable";
                font-size: 16px !important;
                font-weight: 500 !important;
                line-height: 24px !important;
                color: #132e5d !important;
            }
            input.wc-block-components-radio-control__input {
                padding: 0 !important;
                width: 22px !important;
                height: 22px !important;
                margin: 0 !important;
                min-width: 22px !important;
                min-height: 22px !important;
                border: 1px solid #6184c2 !important;
                border-radius: 50% !important;
                box-shadow: none !important;
                background-color: transparent !important;
            }
            input.wc-block-components-radio-control__input::before {
                background-color: #6183c2 !important;
                width: 14px !important;
                height: 14px !important;
            }
            input.wc-block-components-radio-control__input:focus,
            input.wc-block-components-radio-control__input:focus-visible {
                outline: none !important;
            }
            .wc-block-components-form .wc-block-checkout__order-notes.wc-block-components-checkout-step {
                margin-bottom: 0 !important;
                margin-top: 20px !important;
            }
            .wc-block-checkout__payment-method .wc-block-components-checkout-step__content {
                padding-top: 0 !important;
            }
            .wc-block-components-radio-control-accordion-option {
                box-shadow: none !important;
            }
            .wc-block-components-radio-control-accordion-content {
                padding: 16px !important;
                padding-top: 0 !important;font-family: "Satoshi-Variable" !important;
                font-size: 14px !important;
                line-height: 22px !important;
                color: rgba(19, 46, 93, 0.8);
            }
            .wc-block-components-form .wc-block-components-checkout-step#payment-method {
                margin-bottom: 56px !important;
            }
            .wc-block-checkout__payment-method .wc-block-components-radio-control.disable-radio-control .wc-block-components-radio-control__input {
                display: block !important;
            }
            .wc-block-checkout__terms.wc-block-checkout__terms--with-separator {
                border: none !important;
                margin-bottom: 23px !important;
                padding-top: 0 !important;
            }
            .wc-block-checkout__actions {
                padding: 0 !important;
            }
            .wc-block-components-checkout-place-order-button {
                background: #6184c2 !important;
                color: #F7ECE3 !important;
                font-family: "DynaPuff";
                font-size: 24px !important;
                line-height: 20px !important;
                font-weight: 600 !important;
                padding: 20px 26px !important;
                min-height: unset !important;
                text-shadow: 1.09px 2.18px #272525;
                -webkit-text-stroke: 1.09px #272525 !important;
                box-shadow: 1px 1px 0 #132e5d;
                position: relative;
            }
            .wc-block-components-checkout-place-order-button::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            .wp-block-woocommerce-checkout-order-summary-block {
                background: #FFFCFA;
                border: 1.8px solid rgba(19, 46, 93, 0.8) !important;
                border-radius: 2px !important;
                padding: 24px !important;
            }
            @media (max-width: 430px) {
                .wp-block-woocommerce-checkout-order-summary-block {
                    padding: 20px !important;
                }
            }
            @media (max-width: 360px) {
                .wp-block-woocommerce-checkout-order-summary-block {
                    padding: 16px !important;
                }
            }
            .wp-block-woocommerce-checkout-order-summary-block .wc-block-components-totals-wrapper,
            .wp-block-woocommerce-checkout-order-summary-totals-block {
                border-top: none !important;
                padding: 0 !important;
                margin-bottom: 12px !important;
            }
            .wc-block-components-sidebar .wc-block-components-panel,
            .wc-block-components-sidebar .wc-block-components-totals-coupon,
            .wc-block-components-sidebar .wc-block-components-totals-item {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .wc-block-components-panel__button {
                margin-top: 10px !important;
                margin-bottom: 10px !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 19px !important;
                font-weight: 700 !important;
                line-height: 23.4px !important;
                color: #132e5d !important;
            }
            .wc-block-components-order-summary .wc-block-components-order-summary__button-text {
                font-weight: 700 !important;
            }
            .wc-block-components-totals-coupon .wc-block-components-panel__button {
                margin-bottom: 12px !important;
            }
            .wc-block-components-panel__button svg path {
                fill: #132e5d;
            }
            .wc-block-components-totals-item {
                position: relative !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 19px !important;
                font-weight: 700 !important;
                line-height: 23.4px !important;
                color: #132e5d !important;
                padding: 10px 0 !important;
            }
            .wc-block-components-totals-footer-item {
                margin-top: 46px !important;
            }
            .wc-block-components-totals-item__description {
                color: rgba(19, 46, 93, 0.4) !important;
                font-size: 16px !important;
                font-weight: 500 !important;
            }
            .wp-block-woocommerce-checkout-order-summary-cart-items-block .wc-block-components-order-summary__content {
                display: flex !important;
                flex-direction: column !important;
                gap: 12px !important;
            }
            .wp-block-woocommerce-checkout-order-summary-cart-items-block .wc-block-components-order-summary-item {
                gap: 36px;
                padding: 0 !important;
            }
            .woocommerce-mini-cart__buttons.buttons a:first-child {
                display: none !important;
            }
            @media (max-width: 360px) {
                .wp-block-woocommerce-checkout-order-summary-cart-items-block .wc-block-components-order-summary-item {
                    gap: 28px;
                }
            }
            @media (max-width: 320px) {
                .wp-block-woocommerce-checkout-order-summary-cart-items-block .wc-block-components-order-summary-item {
                    gap: 14px;
                }
            }
            .wc-block-components-order-summary-item__image {
                display: flex !important;
                flex-shrink: 0;
                width: 92px !important;
                height: 92px !important;
                padding: 0 !important;
                margin: 0 !important;
                border: 0.81px solid #000 !important;
                align-items: center !important;
                justify-content: center !important;
                background-color: #f7ece3 !important;
                box-shadow: 0.81px 0.81px 0 #132e5d !important;
            }
            .wc-block-components-order-summary-item__image > img {
                max-width: 56px !important;
                width: 56px !important;
                height: 56px !important;
            }
            .wc-block-components-product-name {
                order: 1;
            }
            .wc-block-components-order-summary-item__individual-prices {
                padding-top: 0 !important;
                order: 3;
            }
            .wc-block-components-order-summary-item__description {
                display: flex !important;
                padding: 4px 0 !important;
                flex-direction: column !important;
            }
            .wc-block-components-product-name, .wc-block-components-product-metadata__description {
                font-family: "Satoshi-Variable" !important;
                font-size: 16px !important;
                font-weight: 700 !important;
                line-height: 110% !important;
                color: #132e5d !important;
                padding-top: 0 !important;
            }
            .wc-block-components-product-metadata {
                margin-top: 0 !important;
                order: 2;
            }
            .wc-block-components-product-metadata__description {
                line-height: 24px;
            }
            .wc-block-components-product-metadata__description p {
                margin: 0 !important;
            }
            .wc-block-components-product-details {
                margin-top: 0 !important;
            }
            .wc-block-components-product-details li,
            .wc-block-components-order-summary-item__individual-prices {
                color: rgba(19, 46, 93, 0.4) !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 16px !important;
                font-weight: 500 !important;
                line-height: 24px !important;
            }
            .wc-block-components-product-price__value.is-discounted {
                margin-left: 12px !important;
                color: #132e5d !important;
            }
            .wc-block-components-order-summary-item__total-price {
                display: none !important;
            }
            .wc-block-components-totals-shipping .wc-block-components-shipping-address {
                margin-top: 0 !important;
            }
            .wc-block-components-totals-item__value {
                font-size: inherit !important;
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
            }
            .wc-block-components-totals-coupon__form {
                position: relative !important;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input {
                width: 100% !important;
                flex: none !important;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input input,
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input input:focus,
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input input:focus-visible {
                border: 1px solid rgba(19, 46, 93, 0.4) !important;
                padding: 8px !important;
                border-radius: 4px !important;
                outline: none !important;
                box-shadow: none !important;
                min-height: auto !important;
                height: 46px !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 18px !important;
                font-weight: 500 !important;
                line-height: 23.4px !important;
                color: rgba(19, 46, 93, 0.4) !important;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input input::placeholder {
                color: rgba(19, 46, 93, 0.4) !important;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__input label {
                display: none;
            }
            .wc-block-components-totals-coupon__form .wc-block-components-totals-coupon__button {
                padding: 10px 18px !important;
                min-height: unset !important;
                background-color: #6184c2 !important;
                color: #f7ece3 !important;
                font-family: "Satoshi-Variable" !important;
                font-size: 14px !important;
                border-radius: 2px !important;
                font-weight: 700 !important;
                line-height: 10px;
                display: inline-flex !important;
                width: auto !important;
                flex: none !important;
                border: none !important;
                position: absolute !important;
                top: 8px !important;
                right: 8px !important;
                box-shadow: none !important;
            }
        </style>
        <?php
    } elseif (is_front_page()) {
        ?>
        <style>
            .heading-people span {
                font-family: "DynaPuff";
                font-weight: 700;
                text-transform: uppercase;
                text-shadow: 1.5px 2.5px #132e5d;
                -webkit-text-stroke: 2px #132e5d;
                position: relative;
            }
            .heading-people span::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            .splide__track {
                overflow: visible !important;
            }
        </style>
        <?php
    }
}

add_action( 'wp_logout', 'thdp_redirect_after_logout' );
function thdp_redirect_after_logout() {

    $login_link = function_exists( 'get_locale' ) ? site_url().'/'.substr( get_locale(), 0, 2 ).'/login' : ''; 
    wp_redirect( $login_link );
    exit();
}

add_action('wp_head', 'tdc_custom_js');
function tdc_custom_js() {

    // Redirect to my account page if user logged in
    if( is_user_logged_in() && stripos( $_SERVER['REQUEST_URI'], '/login' ) !== false ) {

        $myaccount_link = get_permalink( get_option('woocommerce_myaccount_page_id') );
        wp_redirect( $myaccount_link );
        exit();
    }
    // Redirect to login page if user not logged in
    if( ! is_user_logged_in() && is_account_page() ) {

        wp_redirect( '/login' );
        exit();
    }
    // Redirect to homepage if user tries to access cart page directly
    if( is_cart() ) {

        wp_redirect( site_url() );
        exit();
    }

    $is_user_logged_in = is_user_logged_in() ? 'true' : 'false';
    $login_link = function_exists( 'get_locale' ) ? site_url().'/'.substr( get_locale(), 0, 2 ).'/login' : ''; 
    $is_checkout = is_checkout();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            /**
             * Conditionally change translatePress switcher font color on header
             */
            if( $( 'body:not(.menu-opened) .header-main-section' ).length > 0 ) {

                let bgColor = $( 'body:not(.menu-opened) .header-main-section' ).css( 'background-color' );

                if( bgColor.indexOf( 'rgb(247, 236, 227)' ) == -1 && $( '.brxe-block.profile-column .trp_language_switcher_shortcode' ).length > 0 ) {
                    
                    $( '.brxe-block.profile-column .trp_language_switcher_shortcode' ).addClass( 'thdp-dark-header' );
                }
            }

            /**
             * Add login link on header my account menu if user is a guest user
             */
            if( '<?php echo $is_user_logged_in; ?>' == 'false' && $( '[rel="thdp-acc-link"]' ).length > 0 ) {
                $( '[rel="thdp-acc-link"]' ).attr( 'href', '<?php echo $login_link; ?>' );
            }
            if( '<?php echo $is_user_logged_in; ?>' == 'false' && $( '#brxe-13a60f' ).length > 0 ) {
                $( '#brxe-13a60f' ).attr( 'href', '<?php echo $login_link; ?>'	 );
            }

            /**
             * Custom Menu
             */
            $('.custom-menu-template-container').insertBefore('.bricks-mobile-menu-wrapper .bricks-mobile-menu');
            $('.bricks-mobile-menu-wrapper .custom-menu-template-container').show();
            $('.bricks-mobile-menu-wrapper .bricks-mobile-menu').hide();

            const navMenuElement = document.querySelector('.brxe-nav-menu');

            if (navMenuElement) {
                const observer = new MutationObserver(function (mutationsList) {
                    mutationsList.forEach(function (mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            const element = mutation.target;

                            if ($(element).hasClass('show-mobile-menu')) {
                                $('body').addClass('menu-opened');
                            } else {
                                $('body').removeClass('menu-opened');
                            }
                        }
                    });
                });

                const config = {
                    attributes: true,
                    attributeFilter: ['class'],
                };

                observer.observe(navMenuElement, config);
            }

            function addDataText(selector) {
                $(selector).each(function () {
                    if (!$(this).attr('data-text') || $(this).attr('data-text') !== $(this).text()) {
                        $(this).attr('data-text', $(this).text());
                    }
                });
            }

            addDataText('.thdp-cont-btn');
            addDataText('.thdp-faq-sec-heading');
            addDataText('.thdp-sg-heading');
            addDataText('.thdp-faq-main-heading');
            addDataText('#brxe-thnqfn .submit-button-wrapper button');
            addDataText('.thdp-signup-form .submit-button-wrapper button');
            addDataText('.custom-menu-template-container nav ul a');
            addDataText('.brxe-woocommerce-mini-cart .cart-detail .woocommerce-mini-cart__buttons a.button');
            addDataText('.button-order-now');
            addDataText('.heading-faqs');
            addDataText('.heading-our-shop');
            addDataText('.wc-fast-cart > h2');
            addDataText('.wfc-cart-collaterals .wfc-proceed-to-checkout a.wfc-button');
            addDataText('.wfc-cart__actions a.button.continue-shopping');
            addDataText('.thdp-cs-hd-text');

            // latest addDataText
            addDataText('.thdp-sg-droppoints-heading');
            addDataText('.thdp-sg-droppoints-title');
            addDataText('.thdp-sg-droppoints-3rd-section-title');
            addDataText('.thdp-sg-wholesale-1st-section-title-heading');
            addDataText('.thdp-sg-wholesale-3rd-section-heading');
            addDataText('.thdp-sg-wholesale-need-more-3rd-section-title');
            addDataText('.thdp-sg-droppoints-contactus-btn > p');
            addDataText('.thdp-sg-wholesale-need-more-contactus-btn > p');
            addDataText('.thdp-sg-wholesale-3rd-section-btn > p');
            addDataText('.thdp-sg-wholesale-1st-section-title-btn > p');
            addDataText('.thdp-sg-droppoints-signup-btn > p');
            addDataText('.thdp-sg-droppoints-readmore-btn > p');
            addDataText('.thdp-sg-wholesaler-heading');
            addDataText('.thdp-signup-wholesaler-form .submit-button-wrapper button .text');
            addDataText('.thdp-sg-droppoints-btn');
            addDataText('.thdp-ppg-heading1');
            // latest addDataText ends

            const observer = new MutationObserver(() => {
                addDataText('.custom-menu-template-container nav ul a');
                addDataText('.brxe-woocommerce-mini-cart .cart-detail .woocommerce-mini-cart__buttons a.button');
                addDataText('.wc-fast-cart > h2');
                addDataText('.wfc-cart-collaterals .wfc-proceed-to-checkout a.wfc-button');
                addDataText('.wfc-cart__actions a.button.continue-shopping');
            });

            observer.observe(document.body, { childList: true, subtree: true });

            /**
             * Mini cart
             */
            let cartQuantities = {};
            // let miniCartUpdated = false;

            $(document).on('wc_fragments_refreshed wc_fragments_loaded', function() {
                const miniCartSelector = '.brxe-woocommerce-mini-cart';
                const miniCartContent = `${miniCartSelector} .widget_shopping_cart_content`;
                const miniCartDetail = `${miniCartSelector} .cart-detail`;
                const miniCartItem = `${miniCartDetail} .woocommerce-mini-cart-item`;
                const miniCartTotal = `${miniCartDetail} .total`;

                // Add mini-cart heading if not present
                if (!$(miniCartContent + ' .mini-cart-heading').length) {
                    $(miniCartContent).prepend('<h3 class="mini-cart-heading">Shopping cart</h3>');
                }

                setTimeout(() => {
                    // let prdsCount = $(miniCartItem).length;
                    // let prdCount = 1;

                    $(miniCartItem).each(function ( i, e ) {
                        const $miniCartItem = $(this);
                        const $miniCartItemRemove = $miniCartItem.find('.remove_from_cart_button');
                        const $miniCartItemQuantity = $miniCartItem.find('.quantity');
                        const cartItemKey = $miniCartItemRemove.data('cart_item_key');
                        let currentQty = cartQuantities.hasOwnProperty(cartItemKey) ? cartQuantities[cartItemKey] : $miniCartItemQuantity.text().match(/\d+/)[0];
                        // let updatedQty = false;

                        if( $('[name="cart['+cartItemKey+'][qty]').length > 0 ) {

                            currentQty = $('[name="cart['+cartItemKey+'][qty]').val();  
                            // updatedQty = currentQty;
                        }

                        let y = setInterval( function() {

                            if( $('.wc-block-components-order-summary-item').length > 0 ) {

                                let ckQty = $('.wc-block-components-order-summary-item').get(i);

                                if( $( ckQty ).find( '.wc-block-components-order-summary-item__quantity span[aria-hidden="true"]' ).length > 0 ) {

                                    let chkQtySpan = $( ckQty ).find( '.wc-block-components-order-summary-item__quantity span[aria-hidden="true"]' );
                                    currentQty = chkQtySpan.text().trim();  
                                    clearInterval(y);

                                    if( $( '.thdp-updated-qty[cart-item-key="'+cartItemKey+'"]' ).length > 0 ) {

                                        currentQty = $( '.thdp-updated-qty[cart-item-key="'+cartItemKey+'"]' ).val();  
                                    }
                                    else {
                                        chkQtySpan.after( '<input type="hidden" class="thdp-updated-qty" cart-item-key="'+cartItemKey+'" value="'+currentQty+'" />' );
                                    }

                                    if( $( 'input[data-cart_item_key="'+cartItemKey+'"]' ).length > 0 ) {

                                        $( 'input[data-cart_item_key="'+cartItemKey+'"]' ).val( currentQty );
                                    }
                                }   
                            }

                        }, 100 );

                        if (currentQty && cartItemKey) {
                            // Add remove item text
                            if ($miniCartItemRemove.length && $miniCartItemRemove.text() !== 'Remove item') {
                                $miniCartItemRemove.text('Remove item');
                            }

                            // Add controls if missing
                            if (!$miniCartItem.find('.cart-item-controls').length) {
                                $miniCartItem.prepend(`<div class="cart-item-controls"></div>`);

                                const $miniCartItemControls = $miniCartItem.find('.cart-item-controls');
                                if (!$miniCartItemControls.find('.quantity-controls').length) {
                                    $miniCartItemControls.prepend(`
                                        <div class="quantity-controls">
                                            <button class="qty-minus">-</button>
                                            <input type="number" class="qty" value="${currentQty}" min="1" step="1" data-cart_item_key="${cartItemKey}">
                                            <button class="qty-plus">+</button>
                                        </div>
                                    `);

                                    $miniCartItemRemove.insertAfter($miniCartItemControls.find('.quantity-controls'));
                                }
                            }

                            // Remove quantity from price string
                            if ($miniCartItemQuantity.length && $miniCartItemQuantity.find('.amount').length) {
                                $miniCartItemQuantity.find('.amount').insertBefore($miniCartItemQuantity);
                                $miniCartItemQuantity.remove();
                            }

                            const $productLink = $miniCartItem.find('> a').first();
                            const $productThumbnail = $productLink.find('img');
                            let productTitle = $productLink.text().trim();
                            let titleMatch = productTitle.match(/^(.*?) - (\d+)ml$/);
                            

                            if( ! titleMatch ) {

                                titleMatch = productTitle.match(/^(.*?) - (\d+\w+\s\d+)ml$/);

                                if( ! titleMatch && $( '.wfc-cart-form__cart-item.cart_item' ).length > 0 ) {

                                    let anc = $( '.wfc-cart-form__cart-item.cart_item' ).get(i);
                                    
                                    if( $( anc ).find( '.cart-item-info span' ).length > 0 ) {

                                        titleMatch = [];
                                        let title1 = $( anc ).find( '.cart-item-info span .product-name' ).text();
                                        let title2 = $( anc ).find( '.cart-item-info span .bundle-text' ).text().replace( /ml/g, '' );
                                        let title3 = $( anc ).find( '.cart-item-info span .bottle-size' ).text().trim().replace( 'Flesje:', '' );
                                            title3 = title3.trim().replace( 'Bottle size:', '' );
                                        titleMatch.push( title2, title1, title3 );
                                        console.log(titleMatch);
                                    }
                                }

                                let x = setInterval( function() {

                                    if( ( ! titleMatch || titleMatch !== undefined && titleMatch.length <= 0 ) && $( '.wc-block-components-order-summary-item' ).length > 0 ) {

                                        anc = $( '.wc-block-components-order-summary-item' ).get(i);

                                        if( $( anc ).find( '.wc-block-components-order-summary-item__description' ).length > 0 ) {

                                            titleMatch = [];
                                            let title1 = $( anc ).find( '.wc-block-components-order-summary-item__description .wc-block-components-product-name' ).text();
                                            let title2 = $( anc ).find( '.wc-block-components-order-summary-item__description .wc-block-components-product-details__value' ).text().replace( /ml/g, '' );
                                            let title3 = $( anc ).find( '.wc-block-components-order-summary-item__description .wc-block-components-product-metadata__description' ).text().trim().replace( 'Flesje:', '' );
                                                title3 = title3.trim().replace( 'Bottle size:', '' );

                                            if( title2 && title1 && title3 ) {

                                                titleMatch.push( title3, title1, title2 );

                                                console.log( titleMatch );

                                                clearInterval(x);

                                                if (titleMatch) {
                                                    const baseTitle = titleMatch[1];
                                                    let bottleSize = `${titleMatch[2]}`;

                                                    if( bottleSize.indexOf( 'ml' ) === -1 ) {

                                                        bottleSize += 'ml';
                                                    }

                                                    const bundleText = ( bottleSize === '15ml' || bottleSize.indexOf('3x') > -1 ) ? '3 Bottles Bundle' : '1 Bottle';

                                                    $productLink.html(`
                                                        <span class="product-name">${baseTitle}</span><span class="bundle-text">${bundleText}</span><span class="bottle-size">Bottle size: ${bottleSize}</span>
                                                    `);
                                                    $productLink.prepend($productThumbnail[0]);
                                                }
                                            }
                                        }
                                    }
                                }, 100 );
                            }

                            if (titleMatch) {
                                const baseTitle = titleMatch[1];
                                let bottleSize = `${titleMatch[2]}`;

                                if( bottleSize.indexOf( 'ml' ) === -1 ) {

                                    bottleSize += 'ml';
                                }

                                const bundleText = ( bottleSize === '15ml' || bottleSize.indexOf('3x') > -1 ) ? '3 Bottles Bundle' : '1 Bottle';

                                $productLink.html(`
                                    <span class="product-name">${baseTitle}</span><span class="bundle-text">${bundleText}</span><span class="bottle-size">Bottle size: ${bottleSize}</span>
                                `);
                                $productLink.prepend($productThumbnail[0]);
                            }
                        }
                    });
                }, 100);

                $(miniCartSelector).off('click', '.qty-minus');
                $(miniCartSelector).off('click', '.qty-plus');
                $(miniCartSelector).off('change', '.qty');

                $(miniCartSelector).on('click', '.qty-minus', function () {
                    const $input = $(this).siblings('.qty');
                    let cartItemKey = $input.data( 'cart_item_key' );
                    const $updQty = $('input[name="cart['+cartItemKey+'][qty]"]');
                    let $chkUpdQty = $('input[cart-item-key="'+cartItemKey+'"]');

                    const currentValue = parseInt($input.val()) || 1;
                    const min = parseInt($input.attr('min')) || 1;
                    if (currentValue > min) {

                        if( $chkUpdQty ) {
                            $chkUpdQty.val(currentValue - 1);
                        }
                        if( $updQty ) {
                            $updQty.val(currentValue - 1);
                        }
                        $input.val(currentValue - 1).trigger('change');
                    }
                });

                $(miniCartSelector).on('click', '.qty-plus', function () {
                    const $input = $(this).siblings('.qty');
                    let cartItemKey = $input.data( 'cart_item_key' );
                    const $updQty = $('input[name="cart['+cartItemKey+'][qty]"]');
                    let $chkUpdQty = $('input[cart-item-key="'+cartItemKey+'"]');

                    const currentValue = parseInt($input.val()) || 1;
                    const max = parseInt($input.attr('max')) || Number.POSITIVE_INFINITY;
                    if (currentValue < max) {

                        if( $chkUpdQty ) {
                            $chkUpdQty.val(currentValue + 1);
                        }
                        if( $updQty ) {
                            $updQty.val(currentValue + 1);
                        }
                        $input.val(currentValue + 1).trigger('change');
                    }
                });

                let isCartUpdating = false;

                $(miniCartSelector).on('change', '.qty', function() {
                    if (isCartUpdating) return;

                    isCartUpdating = true;

                    const $input = $(this);
                    const $button = $input.siblings('button');
                    const cartItemKey = $input.data('cart_item_key');
                    const newQty = $input.val();

                    $input.prop('disabled', true);
                    $button.prop('disabled', true);

                    $.ajax({
                        url: wc_add_to_cart_params.ajax_url,
                        method: 'POST',
                        data: {
                            action: 'tdc_update_cart_item_quantity',
                            cart_item_key: cartItemKey,
                            quantity: newQty
                        },
                        success: function(res) {
                            if (res.success) {
                                if (res.data) {
                                    cartQuantities[cartItemKey] = res.data;
                                }

                                $(document.body).trigger('wc_fragment_refresh');
                            }
                        },
                        complete: function() {
                            isCartUpdating = false;
                            $input.prop('disabled', false);
                            $button.prop('disabled', false);
                        }
                    });
                });

                // Remove colon from the total label text
                const $miniCartTotalLabel = $(`${miniCartTotal} strong`);
                if ($miniCartTotalLabel.length && $miniCartTotalLabel.text().includes(':')) {
                    $miniCartTotalLabel.text($miniCartTotalLabel.text().replace(':', ''));
                }

                // Remove non-breaking spaces from the total amount
                const $miniCartTotalAmount = $(`${miniCartTotal} bdi`);
                if ($miniCartTotalAmount.length) {
                    $miniCartTotalAmount.html(function(_, html) {
                        return html.replace(/&nbsp;/g, '');
                    });
                }

                // Change cart button text
                const $miniCartButton = $(`${miniCartDetail} .woocommerce-mini-cart__buttons a.button:first-child`);
                if ($miniCartButton.length && $miniCartButton.text() !== 'Your Cart') {
                    $miniCartButton.text('Your Cart');
                }

                $('.mini-cart .mini-cart-heading').after(`
                    <button class="mini-cart-close-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M16.192 6.344L11.949 10.586 7.707 6.344 6.293 7.758 10.535 12 6.293 16.242 7.707 17.656 11.949 13.414 16.192 17.656 17.606 16.242 13.364 12 17.606 7.758z"></path>
                        </svg>
                    </button>
                `);

                $(document).on('click', '.mini-cart .mini-cart-close-btn', function(e) {
                    e.preventDefault();

                    $('body').trigger('click');
                });
            });

            $('.brxe-woocommerce-mini-cart .mini-cart-link').on('click', function () {
                setTimeout(() => {
                    if ($(this).parent().hasClass('show-cart-details')) {
                        $('body').addClass('mini-cart-opened');
                        $('body').append('<div class="mini-cart-overlay" style="height:'+$('body').outerHeight()+'px;top:'+$('body').offset().top+'px"></div>');
                        <?php
                        if (tdc_url_has('/product')) {
                            ?>
                            $('header .header-container').append('<div class="mini-cart-overlay" style="height:'+(parseInt($('header .header-inner-section').outerHeight()) - 1)+'px"></div>');
                            <?php
                        } else {
                            ?>
                            $('header .header-container').append('<div class="mini-cart-overlay" style="height:'+$('header .header-inner-section').outerHeight()+'px"></div>');
                            <?php
                        }
                        ?>
                    } else {
                        $('body').removeClass('mini-cart-opened');
                        $('body').find('.mini-cart-overlay').remove();
                    }
                }, 0);
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('.brxe-woocommerce-mini-cart').length) {
                   if (!$('.brxe-woocommerce-mini-cart mini-cart').hasClass('show-cart-details')) {
                        $('body').removeClass('mini-cart-opened');
                        $('body').find('.mini-cart-overlay').remove();
                   }
                }
            });

            /**
             * Fast Cart Popup
             */
            $(document).on('wc-fast-cart|open, wc-fast-cart|after_refresh', function () {
                $('.wc-fast-cart__inner-contents .woocommerce-notices-wrapper').insertBefore('.wfc-cart-items');
                $('.wfc-cart-form__cart-item.cart_item .cart-item-controls .quantity .action.minus').text('-');
                $('.wfc-cart-form__cart-item.cart_item .cart-item-controls .quantity .action.plus').text('+');
                $('.wfc-coupon h2 label').text('Add a coupon');
                $('.wc-fast-cart .wfc-coupon button.wfc-button').text('Apply');

                const $includesTax = $('.wc-fast-cart .order-total td .includes_tax');

                if ($includesTax.length) {
                    $includesTax.appendTo('.wc-fast-cart .order-total th');

                    let htmlContent = $includesTax.html().trim();
                    let updatedHtml = htmlContent.replace(/\(includes\s(.+?)\sTax\)/, 'Including $1 Tax');
                    $includesTax.html(updatedHtml);
                }

                const $shippingRow = $('.wc-fast-cart .woocommerce-shipping-totals.shipping');

                if ($shippingRow.length) {
                    const $shippingFee = $shippingRow.find('.woocommerce-Price-amount').first();

                    $shippingRow.find('th').html('Delivery <span>Standard shipping</span>');
                    $shippingFee.appendTo('.wc-fast-cart .woocommerce-shipping-totals.shipping td, .wc-fast-cart .shipping-message td .fees');
                    $shippingRow.find('.woocommerce-shipping-methods').remove();
                    $shippingRow.find('.woocommerce-shipping-destination').remove();
                    $('.wc-fast-cart .shipping-message td .fees .woocommerce-Price-amount:not(:first-child)').remove();
                }
            });

            /**
             * Tricker
             */
            setTimeout(() => {
                $('.ticker-wrapper:visible').each(function () {
                    initializeTicker($(this));
                });
            }, 100);

            function initializeTicker($tickerWrapper, recalculate = true) {
                const $tickerItems = $tickerWrapper.find('.ticker-content-items');

                if ($tickerWrapper.length && $tickerItems.length) {
                    let totalWidth = 0;
                    let animationDuration = 0;

                    if (recalculate) {
                        $tickerItems.each(function () {
                            totalWidth += $(this).outerWidth(true);
                        });
    
                        $tickerWrapper.append($tickerWrapper.html());

                        animationDuration = totalWidth / 60;
                    } else {
                        totalWidth = parseInt($tickerWrapper.css('--total-width'));

                        if (totalWidth < 0) {
                            totalWidth = totalWidth * -1;
                        }

                        animationDuration = totalWidth / 60;
                    }

                    let initialized = false;

                    function startTickerAnimation(isLoopContinued = false) {
                        if (!initialized || isLoopContinued) {
                            $tickerWrapper.css({
                                transform: 'translateX(0)',
                                transition: 'none',
                            });
    
                            setTimeout(() => {
                                $tickerWrapper.css({
                                    '--total-width': `-${totalWidth}px`,
                                    '--animation-duration': `${animationDuration}s`,
                                    transform: `translateX(var(--total-width))`,
                                    transition: `transform var(--animation-duration) linear`,
                                });
                            }, 100);

                            initialized = true;
                        }
                    }

                    $tickerWrapper.on('transitionend', () => startTickerAnimation(true));

                    startTickerAnimation(false);
                }
            }
        });
    </script>
    <?php
    if (tdc_url_has('/product')) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                function addDataText(selector) {
                    $(selector).each(function () {
                        if (!$(this).attr('data-text') || $(this).attr('data-text') !== $(this).text()) {
                            $(this).attr('data-text', $(this).text());
                        }
                    });
                }

                addDataText('.brxe-product-add-to-cart .cart .single_add_to_cart_button');
                addDataText('.heading-the-drops');
                addDataText('.heading-people span');

                var $gallery = $(".brxe-product-gallery");
                var $originalParent = $gallery.parent();
                var $originalSibling = $gallery.next();

                function makeGalleryResponsive() {
                    if ($(window).width() <= 768) { 

                        let ratingBox = $(".brxe-product-rating").length > 0 ? $(".brxe-product-rating") : $(".woocommerce-product-rating").length > 0 ? $(".woocommerce-product-rating") : "";

                        if( $(".brxe-product-gallery").length > 0 && ratingBox ) {

                            $(".brxe-product-gallery").insertAfter( ratingBox );   
                        }

                        $originalParent.parent().hide();
                    } else {
                        if ($originalSibling.length) {
                            $gallery.insertBefore($originalSibling);
                        } else {
                            $originalParent.append($gallery);
                        }
                        $originalParent.parent().show();
                    }
                }

                makeGalleryResponsive();

                $(window).resize(makeGalleryResponsive);

                const $variationInfoElements = $('.gmrbw-variation-info');

                if ($variationInfoElements.length > 0) {
                    const $lastVariationInfo = $variationInfoElements.last();

                    const $saleIcon = $('<img>', {
                        src: '<?php echo home_url('/wp-content/uploads/2024/10/sale.svg'); ?>',
                        alt: 'Sale Icon'
                    });

                    $lastVariationInfo.after($saleIcon);
                }
            });
        </script>
        <?php
    } elseif (tdc_url_has('/checkout')) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                function addDataText(selector) {
                    $(selector).each(function () {
                        if (!$(this).attr('data-text') || $(this).attr('data-text') !== $(this).text()) {
                            $(this).attr('data-text', $(this).text());
                        }
                    });
                }

                addDataText('h1');
                addDataText('.wc-block-components-checkout-place-order-button');

                const observer = new MutationObserver(() => {

                });

                observer.observe(document.body, { childList: true, subtree: true });

                function applyCheckoutChanges(changePlaceholder = true, moveOrderNotes = true, changeOrderButtonText = true) {
                    

                    function recheckPlaceholder() {

                        let intPlc = setInterval(function() {

                            if( $( '#email' ).length > 0 ) {

                                let placeholderEm = $( '#email' ).attr( 'placeholder' );
                                
                                if( ! placeholderEm ) {

                                    applyCheckoutChanges(true, false, false);
                                }
                                else {
                                    clearInterval( intPlc );
                                }
                            }
                        }, 10);
                    }

                    if (changePlaceholder) {
                        const $textLabels = $('.wc-block-components-form .wc-block-components-text-input label');
    
                        if ($textLabels.length >= 7) {
                            $textLabels.each(function () {
                                const $input = $(this).parent().find('input');
                                if (!$input.attr('placeholder')) {
                                    $input.attr('placeholder', $(this).text());
                                }
                            });
                            recheckPlaceholder();
                        } else {
                            // setTimeout(function() {
                            //     applyCheckoutChanges(true, false, false);
                            // }, 20);
                            recheckPlaceholder();
                        }
                    }

                    if (moveOrderNotes) {
                        const $orderNotes = $('.wc-block-checkout__order-notes');
                        const $shippingOptionContainer = $('.wc-block-checkout__shipping-option .wc-block-components-checkout-step__container');

                        if ($orderNotes.length && $shippingOptionContainer.length) {
                            if (!$orderNotes.parent().hasClass('wc-block-checkout__shipping-option')) {
                                if (!$orderNotes.is($shippingOptionContainer) && !$orderNotes.has($shippingOptionContainer).length) {
                                    $orderNotes.insertAfter($shippingOptionContainer);
                                }
                            }
                        } else {
                            setTimeout(function () {
                                applyCheckoutChanges(false, true, false);
                            }, 100);
                        }
                    }

                    if (changeOrderButtonText) {
                        const $orderButtonText = $('.wc-block-components-checkout-place-order-button .wc-block-components-button__text');

                        if ($orderButtonText.length) {
                            $orderButtonText.text('Checkout');
                        } else {
                            setTimeout(function () {
                                applyCheckoutChanges(false, false, true);
                            }, 100);
                        }
                    }
                }

                $(document).on('click', '.wc-block-components-address-form__address_2-toggle', function () {
                    const $address2Labels = $('.wc-block-components-form .wc-block-components-text-input.wc-block-components-address-form__address_2 label');

                    if ($address2Labels.length) {
                        $address2Labels.each(function () {
                            const $input = $(this).parent().find('input');
                            if (!$input.attr('placeholder')) {
                                $input.attr('placeholder', $(this).text());
                            }
                        });
                    }
                });

                $(document).on('click', '.wc-block-checkout__use-address-for-billing input', function () {
                    const $textLabels = $('#billing .wc-block-components-text-input label');

                    if ($textLabels.length) {
                        $textLabels.each(function () {
                            const $input = $(this).parent().find('input');
                            if (!$input.attr('placeholder')) {
                                $input.attr('placeholder', $(this).text());
                            }
                        });
                    }
                });

                $(document.body).on('init_checkout update_checkout updated_checkout', function () {
                    applyCheckoutChanges();
                });
            });
        </script>
        <?php
    } elseif (is_front_page()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                function addDataText(selector) {
                    $(selector).each(function () {
                        if (!$(this).attr('data-text') || $(this).attr('data-text') !== $(this).text()) {
                            $(this).attr('data-text', $(this).text());
                        }
                    });
                }
    
                addDataText('.heading-people span');
            });
        </script>
        <?php
    }
}

add_action('wp_ajax_tdc_update_cart_item_quantity', 'tdc_update_cart_item_quantity');
add_action('wp_ajax_nopriv_tdc_update_cart_item_quantity', 'tdc_update_cart_item_quantity');
function tdc_update_cart_item_quantity() {
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = intval($_POST['quantity']);

    if ($cart_item_key && $quantity >= 0) {
        WC()->cart->set_quantity($cart_item_key, $quantity, true);
        WC()->cart->calculate_totals();
    }

    wp_send_json_success(array($quantity));
}

add_filter('wfc_script_params', 'tdc_wfc_script_params', 9999);
function tdc_wfc_script_params($data) {
    $data['strings']['cartTitle'] = __( 'Your Cart', 'wc-fast-cart' );

    return $data;
}

add_action('wfc_before_cart', function() {
    remove_all_actions('wfc_cart_actions');
    add_action('wfc_cart_actions', function() {
        ?>
        <a href="#close-modal" class="button continue-shopping wfc-exit">Continue Shopping</a>
        <style>
            .wfc-cart__actions a.button.continue-shopping {
                background-color: #6184c2;
                color: #F7ECE3;
                font-family: "Dynapuff";
                font-size: 24px;
                letter-spacing: 2%;
                padding: 16px 26px;
                line-height: 18px;
                border: 1px solid #000;
                box-shadow: 2px 2px 1.3px 0 rgba(19, 35, 93, 0.3);
                -webkit-text-stroke: 0.7px #272525;
                text-shadow: 0.5px 1.7px #272525;
                position: relative;
                text-transform: uppercase;
                white-space: nowrap;
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .wfc-cart__actions a.button.continue-shopping::before {
                color: inherit;
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                padding: inherit;
                text-shadow: none;
                -webkit-text-stroke: 0;
            }
            @media (max-width: 430px) {
                .wfc-cart__actions a.button.continue-shopping {
                    width: 100%;   
                }
            }
        </style>
        <?php
    });

    $template_path = WC()->template_path() . 'wfc/fast-cart/cart-totals.php';

    if (file_exists(get_stylesheet_directory() . '/' . $template_path)) {
        remove_all_actions('wfc_cart_collaterals');
        add_action('wfc_cart_collaterals', function() use ($template_path) {
            wc_get_template($template_path);
        });
    }

    add_action('wfc_before_cart_totals', function() {
        $templates = new Barn2\Plugin\WC_Fast_Cart\Frontend_Templates(Barn2\Plugin\WC_Fast_Cart\PLUGIN_VERSION);
        $templates->wfc_coupons();
    });

    $template_path = WC()->template_path() . 'wfc/fast-cart/proceed-to-checkout-buttons.php';

    if (file_exists(get_stylesheet_directory() . '/' . $template_path)) {
        remove_all_actions('woocommerce_proceed_to_checkout');
		add_action('woocommerce_proceed_to_checkout', 'wc_get_pay_buttons', 10);
        add_action('woocommerce_proceed_to_checkout', function() use ($template_path) {
            wc_get_template($template_path);
        });
    }
}, 9999);

add_filter('manage_tdc_testimonials_posts_columns', 'tdc_testimonials_set_custom_edit_columns');
function tdc_testimonials_set_custom_edit_columns($columns) {
    $date_label = $columns['date'];
    unset($columns['date']);
    $columns['name'] = __('Author', 'thedrops-customizations');
    $columns['is_left_to_right'] = __('Move to Right?', 'thedrops-customizations');
    $columns['date'] = $date_label;

    return $columns;
}

add_action('manage_tdc_testimonials_posts_custom_column' , 'tdc_testimonials_custom_column', 10, 2);
function tdc_testimonials_custom_column($column, $post_id) {
    echo get_post_meta($post_id, $column, true);
}

add_action('wp_head', 'tdc_testimonials_section');
function tdc_testimonials_section() {
	?>
	<style>
		.testimonials-row {
			transform: rotate(-8deg);
			margin-top: 100px;
		}
		.testimonials-row:last-child {
			margin-top: 40px;
		}
		.testimonials-row .bricks-swiper-container {
		  	overflow: visible !important;
		}
		.testimonials-row .swiper-slide {
            min-width: 340px;
			padding: 20px 40px;
			cursor: grab;
			transform: skew(-5deg);
            align-self: end;
		}
        @media (max-width: 430px) {
            .testimonials-row {
			    transform: rotate(0deg);
                margin-top: 50px;
            }
        }
		.testimonials-row:last-child .swiper-wrapper .swiper-slide:first-child {
			margin-left: -120px;
		}
		.testimonials-row .swiper-slide .testimonial-meta-wrapper {
			text-align: center;
			justify-content: center;
		}
		.testimonials-row .swiper-slide .image {
			width: 25px;
			height: 25px;
		}
        .testimonials-row.move-left .swiper-wrapper {
            animation: testimonial-left 30s linear infinite;
            align-content: end;
        }
        .testimonials-row.move-right .swiper-wrapper {
            animation: testimonial-right 30s linear infinite;
        }
        /* Pause slider on hover - 20-dec-24 */
        .testimonials-row:hover .swiper-wrapper,
        .testimonials-row .swiper-wrapper:hover {
            animation-play-state: paused;
        }
        @keyframes testimonial-left {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }
        @keyframes testimonial-right {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(0);
            }
        }
	</style>
	<script>
		jQuery(document).ready(function($) {
			if ($(".testimonials-row .swiper-slide").length > 0) {
				$('.testimonials-row .swiper-slide').each(function() {
					if ($(this).find('.testimonial-title').length > 0 && $(this).find('.testimonial-content-wrapper').length > 0) {
						$(this).find('.testimonial-content-wrapper').before($(this).find('.testimonial-title'));
					}
				});
			}
		});
	</script>
	<?php
}

add_shortcode('testimonials_background', 'tdc_testimonials_background');
function tdc_testimonials_background() {
    ob_start();
    ?>
	<style>
		.testimonials-background-container {
			position: absolute;
			margin: 0;
		}
		.testimonials-background {
			width: 1047.61px;
			height: 974.26px;
			position: relative;
            left: 50%;
            transform: translateX(-50%);
		}
        @media (max-width: 430px) {
            .testimonials-background {
                width: 1024px;
                height: 860px;
            }
        }
		#tb-ellipse {
			background-color: #db6c36;
			border-radius: 50%;
			box-shadow: inset 0 0 0 1.8px black;
			width: 100%;
			height: 100%;
			position: absolute;
		}
		#tb-mushroom-1, #tb-mushroom-2 {
		 	position: absolute;
		}
		#tb-mushroom-1 {
			left: -80px;
			top: 50px;
		}
		#tb-mushroom-2 {
			right: -80px;
			bottom: -50px;
		}
		#tb-mushroom-1 svg {
			width: 234px;
			height: 234px;
		}
		#tb-mushroom-2 svg {
			width: 304px;
			height: 307px;
		}
	</style>
	<div class="testimonials-background">
		<div id="tb-mushroom-1">
			<svg viewBox="0 0 234 234" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M189.434 165.354L139.441 217.123C129.897 210.243 121.917 200.93 116.353 190.547C109.06 176.961 105.737 162.032 104.131 146.816C103.265 138.633 99.3446 122.155 104.197 114.69C111.144 104.032 120.843 119.773 124.468 125.872C137.922 148.641 160.519 164.556 187.607 165.31C188.206 165.328 188.825 165.346 189.443 165.345L189.434 165.354Z" fill="#EDBC3C"/>
				<path d="M190.996 164.71L139.518 218.017L139.046 217.673C129.55 210.821 121.495 201.566 115.764 190.872C109.247 178.747 105.342 164.769 103.461 146.895C103.291 145.31 103 143.347 102.688 141.269C101.375 132.524 99.5966 120.544 103.634 114.331C105.367 111.685 107.407 110.366 109.719 110.411C115.493 110.529 121.235 119.102 125.046 125.53C139.314 149.683 162.12 163.93 187.628 164.636C188.19 164.654 188.741 164.664 189.312 164.673L191.005 164.7L190.996 164.71ZM139.363 216.241L187.88 166.001C187.785 166.002 187.68 165.995 187.585 165.996C161.611 165.271 138.392 150.774 123.889 126.226C121.782 122.677 115.384 111.881 109.685 111.762C107.84 111.718 106.232 112.802 104.76 115.062C101 120.853 102.744 132.538 104.025 141.065C104.328 143.152 104.628 145.125 104.799 146.748C106.659 164.451 110.523 178.278 116.942 190.234C122.476 200.569 130.231 209.554 139.363 216.241Z" fill="#272525"/>
				<path d="M178.999 76.2931C181.708 90.4059 174.944 104.922 163.699 113.43C152.961 121.529 139.385 124.981 126.021 126.507C122.111 126.956 118.132 127.263 114.388 128.508C105.416 131.546 102.863 139.997 100.009 148.101C98.9027 151.202 97.3926 154.043 95.4867 156.54C91.2945 162.081 85.2474 165.962 77.9577 167.297C72.014 168.38 65.8406 167.746 60.1286 165.763C58.5695 165.248 57.0465 164.619 55.5691 163.884C43.5219 157.989 35.627 145.355 41.2939 132.227C44.2006 125.501 49.3649 120.076 53.3286 113.977C54.4029 112.323 55.3437 110.652 56.1701 108.964C59.4921 102.059 60.706 94.7813 60.0923 86.8609C59.6565 80.9629 58.8634 74.7574 59.1479 68.7137C59.303 65.0687 59.8393 61.4742 61.0535 58.0294C64.2777 48.7867 71.8618 37.6229 82.4346 36.4113C87.104 35.8734 91.7186 37.0957 96.252 38.5762C99.7048 39.7046 103.122 40.9858 106.464 41.7263C115.447 43.7282 126.989 44.6299 138.235 46.6113C139.294 46.7926 140.334 46.9741 141.374 47.1937C159.076 50.6982 175.399 57.4885 179.027 76.2831L178.999 76.2931Z" fill="#DB6C36"/>
				<path d="M168.996 109.626C167.492 111.183 165.863 112.638 164.098 113.962C154.768 120.991 142.338 125.307 126.09 127.169L124.562 127.339C121.221 127.701 117.775 128.075 114.589 129.148C106.373 131.926 103.783 139.313 101.047 147.139L100.631 148.326C99.4974 151.512 97.9314 154.412 96.0071 156.946C91.5827 162.806 85.3728 166.623 78.0641 167.958C72.177 169.021 65.8912 168.484 59.8827 166.402C58.3426 165.887 56.7719 165.249 55.2469 164.505C43.8614 158.931 34.437 146.391 40.6521 131.979C42.5903 127.514 45.5264 123.564 48.3702 119.767C49.8571 117.782 51.3904 115.73 52.7422 113.633C53.8165 111.979 54.7573 110.308 55.528 108.697C58.7396 102.003 60.0038 94.8862 59.3894 86.9278C59.2895 85.5601 59.1701 84.1738 59.0412 82.7781C58.6373 78.1634 58.2211 73.3872 58.434 68.6951C58.5998 64.5745 59.251 61.016 60.3744 57.8201C63.6529 48.4243 71.3658 37.011 82.3182 35.7548C87.3389 35.1821 92.2323 36.5802 96.4216 37.9526C97.2442 38.2235 98.0667 38.4945 98.8799 38.7751C101.539 39.6796 104.055 40.5201 106.576 41.0847C111.607 42.2046 117.421 42.9781 123.588 43.7929C128.392 44.4318 133.378 45.096 138.319 45.9608C139.378 46.142 140.427 46.3329 141.477 46.5428C157.184 49.6541 175.731 55.8255 179.653 76.1746C181.922 87.9747 177.795 100.514 168.996 109.626ZM107.206 132.244C109.019 130.368 111.274 128.845 114.167 127.862C117.486 126.749 121.017 126.364 124.415 126L125.943 125.831C141.945 124.011 154.167 119.775 163.29 112.902C174.985 104.063 180.898 89.7519 178.336 76.4354C174.561 56.8826 156.521 50.902 141.215 47.8788C140.175 47.6782 139.135 47.4776 138.095 47.2961C133.192 46.4307 128.216 45.7757 123.431 45.1365C117.235 44.3127 111.383 43.54 106.294 42.402C103.707 41.829 101.048 40.9245 98.4649 40.0566C97.6516 39.776 96.8291 39.5051 96.0162 39.2435C90.9853 37.6005 86.7554 36.6187 82.4846 37.1117C72.1586 38.3 64.8087 49.26 61.6663 58.2826C60.5692 61.3544 59.9634 64.7885 59.7953 68.776C59.5904 73.3823 60.0056 78.1014 60.3995 82.6878C60.5191 84.0932 60.6384 85.4795 60.7481 86.8565C61.3858 95.0618 60.0778 102.398 56.7558 109.303C55.9481 110.972 54.989 112.681 53.8777 114.393C52.4978 116.519 50.9458 118.591 49.4592 120.595C46.6622 124.343 43.7818 128.216 41.9077 132.538C36.0396 146.125 45.0247 158.017 55.8537 163.315C57.3404 164.04 58.8348 164.661 60.3268 165.148C66.1249 167.158 72.1822 167.68 77.8321 166.65C84.8089 165.368 90.7271 161.736 94.947 156.147C96.7972 153.728 98.2892 150.944 99.377 147.882L99.7937 146.695C101.633 141.461 103.498 136.103 107.225 132.244L107.206 132.244Z" fill="#272525"/>
				<path d="M138.194 46.6132C137.303 50.0523 135.534 53.3165 132.891 56.0539C124.671 64.566 111.095 64.822 102.573 56.5926C97.5435 51.7353 95.4006 45.0113 96.1917 38.5783C99.6445 39.7068 103.062 40.988 106.403 41.7285C115.387 43.7305 126.928 44.6321 138.175 46.6135L138.194 46.6132Z" fill="var(--bricks-color-hkwrlr)"/>
				<path d="M133.385 56.5242C124.913 65.2974 110.89 65.5517 102.117 57.0795C97.1548 52.2875 94.6941 45.3313 95.5353 38.498L95.6351 37.6785L96.4192 37.9311C97.2418 38.202 98.0644 38.473 98.8777 38.7536C101.537 39.6581 104.053 40.4986 106.573 41.0633C111.605 42.1832 117.419 42.9566 123.586 43.7714C128.39 44.4103 133.375 45.0745 138.317 45.9392L139.032 46.0694L138.854 46.7763C137.91 50.4255 136.019 53.7964 133.385 56.5242ZM96.7887 39.4842C96.2508 45.713 98.5145 51.7313 103.051 56.1122C111.283 64.0613 124.459 63.8313 132.418 55.5901C134.725 53.2009 136.424 50.2992 137.358 47.1447C132.694 46.3417 127.985 45.7202 123.419 45.1056C117.223 44.2818 111.371 43.5091 106.282 42.3712C103.695 41.7982 101.036 40.8936 98.4529 40.0258C97.8982 39.8452 97.3528 39.6551 96.7884 39.4652L96.7887 39.4842Z" fill="#272525"/>
				<path d="M79.7275 73.9675C88.2396 82.1875 88.4859 95.7537 80.2472 104.285C73.8206 110.94 64.1294 112.536 56.1363 108.985C59.4583 102.08 60.6722 94.8032 60.0584 86.8828C59.6226 80.9848 58.8296 74.7793 59.114 68.7356C66.1836 66.7768 74.0595 68.5127 79.7278 73.9865L79.7275 73.9675Z" fill="var(--bricks-color-hkwrlr)"/>
				<path d="M80.7191 104.732C74.3018 111.378 64.3031 113.331 55.8596 109.588L55.2272 109.314L55.5301 108.691C58.7417 101.997 60.0059 94.8805 59.3915 86.922C59.2916 85.5543 59.1723 84.168 59.0433 82.7723C58.6394 78.1576 58.2232 73.3814 58.4361 68.6893L58.4562 68.204L58.9198 68.0723C66.4248 65.9918 74.5728 68.0653 80.1831 73.4831C88.9467 81.946 89.1916 95.9782 80.7194 104.751L80.7191 104.732ZM57.0409 108.617C64.8369 111.771 73.8857 109.873 79.7518 103.798C87.7103 95.5571 87.49 82.3899 79.2487 74.4314C74.1027 69.462 66.6696 67.4805 59.7484 69.2275C59.5882 73.6714 59.991 78.2291 60.3723 82.635C60.4919 84.0403 60.6112 85.4267 60.7208 86.8037C61.3349 94.7431 60.1284 101.897 57.0312 108.607L57.0409 108.617Z" fill="#272525"/>
				<path d="M151.132 105.648C157.663 98.8845 157.475 88.1073 150.712 81.5762C143.948 75.0451 133.171 75.2332 126.64 81.9963C120.109 88.7595 120.297 99.5367 127.06 106.068C133.823 112.599 144.601 112.411 151.132 105.648Z" fill="var(--bricks-color-hkwrlr)"/>
				<path d="M151.626 106.127C144.844 113.149 133.607 113.345 126.585 106.564C119.562 99.7822 119.366 88.5451 126.148 81.5226C132.929 74.5001 144.166 74.304 151.189 81.0855C158.211 87.867 158.408 99.1042 151.626 106.127ZM127.125 82.466C120.857 88.9565 121.038 99.338 127.529 105.606C134.019 111.874 144.401 111.692 150.668 105.202C156.936 98.7115 156.755 88.3299 150.264 82.0621C143.774 75.7944 133.392 75.9755 127.125 82.466Z" fill="#272525"/>
				<path d="M89.8227 144.413C93.2759 147.748 95.1683 152.099 95.4741 156.544C91.2819 162.085 85.2348 165.966 77.9451 167.301C72.0013 168.384 65.828 167.75 60.116 165.768C56.8783 158.977 57.9968 150.598 63.536 144.862C70.6632 137.482 82.4517 137.276 89.832 144.403L89.8227 144.413Z" fill="var(--bricks-color-hkwrlr)"/>
				<path d="M93.6811 159.673C89.5057 163.996 84.1726 166.828 78.0584 167.952C72.1714 169.016 65.8855 168.479 59.877 166.396L59.6092 166.306L59.4811 166.052C56.0459 158.846 57.4728 150.148 63.0306 144.393C70.41 136.751 82.6358 136.538 90.2774 143.917C93.7209 147.243 95.8054 151.704 96.1265 156.482L96.1499 156.729L95.9917 156.931C95.2668 157.895 94.4744 158.812 93.6524 159.664L93.6811 159.673ZM60.5887 165.214C66.2996 167.14 72.2519 167.635 77.8167 166.635C84.7081 165.364 90.5707 161.809 94.7736 156.335C94.4124 151.985 92.4966 147.93 89.3433 144.884C82.2337 138.019 70.8635 138.217 63.9979 145.327C58.8978 150.608 57.5437 158.573 60.5887 165.214Z" fill="#272525"/>
			</svg>
		</div>
		<div id="tb-mushroom-2">
			<svg viewBox="0 0 304 307" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M64.6588 80.6938L137.106 20.0987C148.603 30.4061 157.72 43.6709 163.534 57.9947C171.16 76.7393 173.417 96.6844 173.395 116.765C173.385 127.563 176.206 149.613 168.834 158.678C158.285 171.619 147.82 149.727 143.939 141.263C129.552 109.677 102.28 85.7617 67.0364 81.0058C66.2567 80.8988 65.4521 80.7895 64.6453 80.7051L64.6588 80.6938Z" fill="#EDBC3C"/>
				<path d="M62.5418 81.3277L137.142 18.9324L137.71 19.4465C149.148 29.7111 158.37 42.9103 164.36 57.6631C171.175 74.3937 174.325 93.178 174.291 116.765C174.292 118.857 174.399 121.46 174.517 124.214C175.012 135.809 175.664 151.69 169.53 159.236C166.901 162.447 164.055 163.885 161.044 163.504C153.525 162.546 147.227 150.56 143.148 141.64C127.892 108.134 100.115 86.3663 66.9263 81.8937C66.1963 81.7911 65.4776 81.702 64.7341 81.6107L62.5283 81.3391L62.5418 81.3277ZM137.096 21.2711L66.7874 80.0774C66.9117 80.0884 67.0474 80.113 67.1717 80.1241C100.966 84.6882 129.248 106.839 144.755 140.893C147.01 145.817 153.856 160.797 161.276 161.746C163.678 162.06 165.927 160.87 168.163 158.125C173.876 151.092 173.227 135.6 172.743 124.294C172.639 121.529 172.521 118.912 172.524 116.77C172.563 93.4094 169.445 74.8283 162.734 58.3325C156.951 44.0742 148.082 31.2698 137.096 21.2711Z" fill="#272525"/>
				<path d="M65.8965 198.355C64.3261 179.561 75.1753 161.56 91.0333 152.024C106.174 142.949 124.372 140.336 142.023 140.205C147.188 140.164 152.423 140.317 157.483 139.214C169.613 136.499 174.122 125.826 178.975 115.647C180.85 111.755 183.217 108.258 186.051 105.265C192.294 98.6181 200.725 94.3956 210.424 93.6686C218.331 93.0827 226.299 94.7698 233.477 98.1529C235.44 99.0418 237.339 100.075 239.165 101.24C254.065 110.611 262.609 128.197 253.385 144.538C248.656 152.912 241.161 159.272 235.139 166.678C233.507 168.688 232.047 170.737 230.733 172.825C225.436 181.374 222.839 190.701 222.537 201.122C222.284 208.88 222.455 217.088 221.242 224.935C220.532 229.67 219.332 234.286 217.268 238.612C211.773 250.225 200.322 263.737 186.356 263.846C180.188 263.898 174.336 261.66 168.626 259.097C164.278 257.143 159.996 254.996 155.739 253.564C144.295 249.701 129.359 246.917 114.958 242.765C113.602 242.381 112.27 241.999 110.943 241.568C88.331 234.53 67.9753 223.396 65.8581 198.364L65.8965 198.355Z" fill="#DB6C36"/>
				<path d="M83.6038 156.256C85.7832 154.433 88.112 152.762 90.5993 151.279C103.753 143.406 120.575 139.505 142.038 139.338L144.056 139.329C148.466 139.321 153.015 139.313 157.322 138.357C168.429 135.876 172.839 126.597 177.498 116.764L178.208 115.274C180.13 111.274 182.577 107.708 185.441 104.668C192.031 97.6385 200.666 93.5219 210.39 92.7971C218.22 92.2293 226.348 93.8053 233.899 97.3595C235.837 98.2462 237.798 99.2978 239.685 100.481C253.766 109.34 264.318 127.017 254.201 144.958C251.05 150.515 246.668 155.261 242.429 159.819C240.212 162.203 237.925 164.667 235.869 167.215C234.237 169.225 232.776 171.274 231.546 173.269C226.423 181.558 223.782 190.668 223.476 201.139C223.416 202.938 223.378 204.764 223.352 206.603C223.237 212.681 223.115 218.972 222.184 225.065C221.393 230.419 220.048 234.972 218.137 238.987C212.55 250.791 200.896 264.611 186.429 264.725C179.797 264.773 173.606 262.268 168.33 259.893C167.295 259.425 166.259 258.957 165.237 258.478C161.892 256.927 158.726 255.48 155.516 254.392C149.106 252.23 141.626 250.411 133.692 248.489C127.512 246.986 121.099 245.425 114.771 243.609C113.415 243.225 112.072 242.83 110.731 242.41C90.6666 236.162 67.3226 225.526 65.039 198.425C63.7205 182.71 70.8523 166.922 83.6038 156.256ZM167.387 135.344C164.761 137.541 161.605 139.214 157.693 140.093C153.208 141.084 148.546 141.095 144.061 141.096L142.043 141.105C120.908 141.251 104.369 145.077 91.5058 152.776C75.0135 162.682 65.305 180.534 66.7944 198.268C68.9969 224.309 91.7056 234.626 111.259 240.703C112.588 241.109 113.918 241.516 115.249 241.897C121.527 243.71 127.929 245.257 134.084 246.758C142.055 248.695 149.584 250.519 156.066 252.712C159.363 253.82 162.707 255.371 165.957 256.863C166.979 257.343 168.015 257.811 169.039 258.265C175.375 261.11 180.759 262.98 186.4 262.931C200.041 262.819 211.159 249.54 216.516 238.203C218.375 234.347 219.644 229.95 220.419 224.77C221.327 218.788 221.443 212.571 221.567 206.532C221.607 204.681 221.644 202.855 221.693 201.043C222.003 190.247 224.732 180.856 230.028 172.307C231.315 170.242 232.804 168.145 234.493 166.065C236.59 163.484 238.904 160.996 241.123 158.588C245.295 154.086 249.593 149.433 252.64 144.054C262.19 127.141 252.121 110.371 238.727 101.949C236.888 100.795 235.024 99.7774 233.145 98.9335C225.859 95.5031 218.027 93.9786 210.51 94.5367C201.227 95.2381 192.999 99.1529 186.713 105.859C183.962 108.759 181.627 112.184 179.782 116.028L179.072 117.518C175.944 124.092 172.763 130.825 167.362 135.342L167.387 135.344Z" fill="#272525"/>
				<path d="M115.038 242.781C116.679 238.417 119.442 234.404 123.273 231.2C135.185 221.236 152.936 222.793 162.911 234.718C168.798 241.757 170.658 250.83 168.73 259.115C164.382 257.162 160.101 255.014 155.843 253.582C144.399 249.719 129.463 246.935 115.062 242.783L115.038 242.781Z" fill="var(--bricks-color-hkwrlr)"/>
				<path d="M122.676 230.527C134.953 220.258 153.288 221.879 163.557 234.156C169.365 241.101 171.607 250.521 169.558 259.321L169.314 260.377L168.326 259.938C167.29 259.47 166.254 259.002 165.232 258.522C161.887 256.971 158.722 255.524 155.511 254.436C149.102 252.274 141.622 250.455 133.687 248.533C127.507 247.031 121.094 245.47 114.766 243.653L113.851 243.384L114.181 242.486C115.922 237.855 118.858 233.72 122.676 230.527ZM168.06 257.86C169.629 249.806 167.513 241.637 162.203 235.289C152.568 223.769 135.341 222.234 123.808 231.881C120.464 234.677 117.843 238.227 116.185 242.214C122.16 243.911 128.218 245.378 134.091 246.815C142.061 248.753 149.591 250.577 156.073 252.77C159.369 253.878 162.714 255.429 165.964 256.921C166.662 257.234 167.347 257.558 168.058 257.884L168.06 257.86Z" fill="#272525"/>
				<path d="M195.119 215.241C185.156 203.329 186.724 185.592 198.663 175.606C207.976 167.816 220.845 167.083 230.781 172.829C225.485 181.378 222.887 190.705 222.585 201.126C222.333 208.884 222.503 217.092 221.291 224.939C211.792 226.511 201.756 223.149 195.122 215.217L195.119 215.241Z" fill="var(--bricks-color-hkwrlr)"/>
				<path d="M198.136 174.927C207.435 167.149 220.755 165.993 231.253 172.052L232.04 172.498L231.558 173.269C226.434 181.557 223.794 190.668 223.487 201.139C223.427 202.938 223.39 204.763 223.364 206.603C223.248 212.681 223.126 218.972 222.195 225.065L222.101 225.695L221.478 225.803C211.395 227.473 201.05 223.632 194.484 215.781C184.226 203.517 185.86 185.171 198.138 174.902L198.136 174.927ZM229.576 173.155C219.842 167.953 207.769 169.17 199.268 176.281C187.735 185.927 186.189 203.14 195.835 214.674C201.858 221.875 211.282 225.496 220.558 224.18C221.385 218.403 221.494 212.399 221.61 206.597C221.65 204.746 221.687 202.92 221.736 201.108C222.04 190.662 224.611 181.495 229.587 173.169L229.576 173.155Z" fill="#272525"/>
				<path d="M106.354 163.941C96.889 171.857 95.6337 185.947 103.55 195.412C111.466 204.877 125.556 206.132 135.021 198.216C144.486 190.299 145.741 176.209 137.825 166.745C129.908 157.28 115.818 156.025 106.354 163.941Z" fill="var(--bricks-color-hkwrlr)"/>
				<path d="M105.786 163.243C115.613 155.024 130.305 156.332 138.525 166.16C146.744 175.987 145.435 190.679 135.608 198.899C125.78 207.118 111.089 205.81 102.869 195.982C94.6495 186.154 95.9583 171.463 105.786 163.243ZM134.464 197.531C143.547 189.934 144.757 176.362 137.16 167.279C129.562 158.196 115.99 156.986 106.907 164.583C97.8236 172.18 96.6145 185.753 104.212 194.836C111.809 203.919 125.381 205.128 134.464 197.531Z" fill="#272525"/>
				<path d="M191.755 121.903C187.713 117.071 185.849 111.13 186.069 105.286C192.311 98.6386 200.743 94.4161 210.442 93.6891C218.349 93.1032 226.317 94.7902 233.495 98.1733C236.774 107.486 234.148 118.264 226.121 124.978C215.792 133.616 200.38 132.243 191.741 121.915L191.755 121.903Z" fill="var(--bricks-color-hkwrlr)"/>
				<path d="M188.844 101.452C194.895 96.3906 202.248 93.4377 210.384 92.822C218.214 92.2543 226.342 93.8303 233.893 97.3844L234.23 97.5397L234.362 97.8896C237.841 107.771 234.768 118.923 226.714 125.659C216.02 134.604 200.036 133.18 191.092 122.486C187.061 117.667 184.962 111.554 185.208 105.274L185.212 104.949L185.447 104.707C186.527 103.55 187.689 102.464 188.88 101.467L188.844 101.452ZM232.8 98.8279C225.615 95.5194 217.917 94.0443 210.516 94.5751C201.346 95.2741 193.2 99.0961 186.953 105.655C186.819 111.381 188.754 116.941 192.445 121.353C200.767 131.303 215.632 132.627 225.582 124.306C232.973 118.124 235.849 107.919 232.8 98.8279Z" fill="#272525"/>
			</svg>
		</div>
		<div id="tb-ellipse"></div>
	</div>
    <?php
    return ob_get_clean();
}

add_shortcode('custom_product_display', 'tdc_custom_product_display');
function tdc_custom_product_display( $sc_atts ) {

    // 19-dec-24
    $product_id = isset( $sc_atts['product_id'] ) && $sc_atts['product_id'] ? $sc_atts['product_id'] : 0;

    $atts = shortcode_atts(array(
        'product_id' => $product_id,
    ), $atts, 'custom_product_display');

    $product_id = intval($atts['product_id']);
    $product = wc_get_product($product_id);

    if (!$product || $product->get_type() !== 'variable') {
        return '<p>Product not found or not a variable product.</p>';
    }

    $variations = $product->get_available_variations();

    if (empty($variations)) {
        return '<p>No variations available for this product.</p>';
    }

    ob_start();
    ?>
    <style>
        .woo-prd-rating {
            padding: 15px 0px;
        }
        .thdr-grey-star {
            filter: grayscale(1);
        }
        .regular-price {
            top: -8px;	
        }
        .regular-price .woocommerce-Price-amount.amount {
            font-family: "Urbanist", sans-serif;
            font-size: 21.42px;
        }
        .custom-product-display {
            display: flex;
            justify-content: space-between;
            gap: 148px;
            margin-top: 40px;
        }
        .product-item {
            background-color: transparent;
            border-radius: 25px;
            text-align: center;
            position: relative;
            width: calc((100% - 148px) / 2);
        }
        .product-image-container {
            width: 100%;
            height: 540px;
            padding: 10px;
            border-radius: 225px;
            background-color: #F7ECE3;
            margin: 0 auto 15px;
            border: 13px solid #006C37;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }
        @media (max-width: 768px) {
            .custom-product-display {
                gap: 110px;
                margin-top: 60px;
            }
            .product-item {
                width: calc((100% - 110px) / 2);
            }
            .product-image-container {
                height: 425px;
                border-radius: 160px;
            }
        }
        @media (max-width: 430px) {
            .custom-product-display {
                gap: 60px;
                margin-top: 40px;
            }
            .product-item {
                width: 100%;
                flex: none;
                transition: left 0.6s ease, opacity 0.3s ease;
            }
            .product-item:first-child.active {
                left: 0px;
                opacity: 1;
                visibility: visible;
            }
            .product-item:first-child:not(.active) {
                left: calc(-100% - 60px);
                opacity: 0;
            }
            .product-item:nth-child(2).active {
                left: calc(-100% - 60px);
                opacity: 1;
                visibility: visible;
            }
            .product-item:nth-child(2):not(.active) {
                left: 0px;
                opacity: 0;
            }
            .product-image-container {
                width: calc(100% - 60px);
                height: 360px;
                border-radius: 155px;
            }
        }
        .product-item:first-child .product-image-container {
            border-color: #6184C2;
        }
        .product-item:nth-child(2) .product-image-container {
            border-color: #00885A;
        }
        .product-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .discount-label {
            display: inline-block;
            background-color: #DE6327;
            color: #fff;
            padding: 5px 15px;
            border-radius: 10px;
            font-weight: bold;
            position: absolute;
            top: 65px;
            right: 0px;
            box-shadow: 2px 3px 1.3px rgba(0, 0, 0, 0.38);
            border-radius: 45.7793px;
            font-family: 'DynaPuff';
            font-style: normal;
            font-weight: 500;
            font-size: 22.8897px;
            line-height: 27px;
            color: #FFFFFF;
        }
        .discount-label {
            right: -45px;
            top: 100px;
            box-sizing: border-box;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 7px 2px;
            gap: 10px;
            position: absolute;
            width: 113px;
            height: 42px;
            background: #DB6C36;
            border: 0.8px solid #272525;
            box-shadow: 2px 2px 1.3px rgba(19, 46, 93, 0.3);
            border-radius: 45.7793px;
            flex: none;
            order: 2;
            flex-grow: 0;
            z-index: 2;
        }
        @media (max-width: 430px) {
            .discount-label {
                right: -20px;
                top: 70px;
            }
        }
        .product-title {
            margin: 5px 0px;
            margin-top: 30px;
            font-family: 'DynaPuff';
            font-style: normal;
            font-weight: 500;
            font-size: 38px;
            line-height: 1.1;
            text-transform: capitalize;
            color: #132E5D;
            height: auto;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .product-pricing {
            margin-top: -6px;
            font-family: cursive;
            height: auto;
        }
        .product-pricing .price {
            font-family: 'DynaPuff';
            font-style: normal;
            font-weight: 700;
            font-size: 48px;
            line-height: 71px;
            color: #132E5D;
        }
        .product-pricing .regular-price {
            text-decoration: line-through;
            font-family: 'Urbanist';
            font-style: normal;
            font-weight: 500;
            font-size: 21.4227px;
            line-height: 21px;
            color: #132E5D;
            margin-left: 10px;
        }
        .button.order-now {
            background-color: #00885A;
            text-transform: uppercase;
            box-sizing: border-box;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            padding: 16px 2px;
            gap: 12.27px;
            width: 176.55px;
            height: 50px;
            border: 0.981333px solid #272525;
            box-shadow: 1.96267px 2.944px 1.27573px rgba(0, 0, 0, 0.38);
            font-family: 'DynaPuff';
            font-style: normal;
            font-weight: 700;
            font-size: 26px;
            line-height: 25px;
            color: #F7ECE3;
            border: 0.357045px solid #000000;
            -webkit-text-stroke: 1px #272525;
            margin: 0 auto;
            margin-top: 10px;
            text-shadow: 1px 1px 0 #000000;
            position: relative;
        }
        .button.order-now::before {
            color: inherit;
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            width: 100%;
            height: 100%;
            padding: inherit;
            text-shadow: none;
            -webkit-text-stroke: 0;
            padding: unset;
            height: unset;
            top: 50%;
            transform: translateY(-50%);
        }
        .star-rating .star-full {
            color: #FFA500;
        }
        .short-description {
            font-family: 'Satoshi-Variable';
            font-style: normal;
            font-weight: 400;
            font-size: 18.968px;
            line-height: 27px;
            text-align: center;
            color: #132E5D;
        }
        .product-item:first-child a.button.order-now {
            background-color: #6184C2 !important;
        }
        .custom-bullets-block {
            display: none;
        }
        @media (max-width: 430px) {
            .custom-bullets-block {
                display: block;
            }
        }
        .custom-bullets {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            list-style: none;
            padding: 0;
        }
        .custom-bullet {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(19, 46, 93, 0.4);
            cursor: pointer;
        }
        .custom-bullet.active {
            background: rgba(19, 46, 93, 0.8);
            cursor: default;
        }
    </style>
    <div class="custom-product-display">
        <?php
        foreach ($variations as $i => $variation) {
            $variation_id = $variation['variation_id'];
            $variation_product = wc_get_product($variation_id);
            $parent_product_id = $variation_product->get_parent_id();
            $parent_product = wc_get_product($parent_product_id);
            $price = $variation_product->get_price();
            $regular_price = $variation_product->get_regular_price();
            $discount = ($regular_price > $price) ? round((($regular_price - $price) / $regular_price) * 100) : 0;
            ?>
            <div class="product-item <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i + 1; ?>">
                <div class="product-image-container">
                    <a href="<?php echo get_permalink($product_id); ?>">
                        <?php echo $variation['image']['src'] ? '<img src="' . esc_url($variation['image']['src']) . '" alt="' . esc_attr($variation['image']['alt']) . '">' : woocommerce_placeholder_img(); ?>
                    </a>
                </div>
                <div class="product-info">
                    <h2 class="product-title"><?php echo str_replace('The Drops - ', '', $variation_product->get_name()); ?></h2>
                    <?php
                    if ($discount > 0) {
                        ?>
                        <div class="discount-label"><?php echo $discount; ?>% off</div>
                        <?php
                    }
                    ?>
                    <p class="short-description"><?php echo wp_trim_words($variation_product->get_description(), 4); ?></p>
                    <div class="product-pricing">
                        <?php $dis_price = str_replace(',00', ',-', wc_price($price)); ?>
                        <span class="price"><?php echo $dis_price; ?></span>
                        <?php
                        if ($regular_price > $price) {
                            $regular_price = str_replace(',00', '', wc_price($regular_price));
                            ?>
                            <span class="regular-price"><?php echo $regular_price; ?></span>
                            <?php
                        }
                        ?>
                    </div>
                    <a href="<?php echo esc_url($variation_product->add_to_cart_url()); ?>" data-no-translation-href class="button order-now button-order-now">Order Now</a>
                    <div class="woo-prd-rating">
                        <?php
                        $star_rating = floor($parent_product->get_average_rating());
                        $grey_stars = 5 - (int) $star_rating;

                        for ($x = 1; $x <= $star_rating; $x++) {
                            ?>
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.51371 1.88207C9.94123 0.566247 11.8027 0.566248 12.2302 1.88207L13.7577 6.58343C13.9489 7.17188 14.4973 7.5703 15.116 7.5703H20.0591C21.4426 7.5703 22.0179 9.34072 20.8986 10.1539L16.8995 13.0595C16.399 13.4232 16.1895 14.0679 16.3807 14.6563L17.9082 19.3577C18.3357 20.6735 16.8298 21.7677 15.7105 20.9545L11.7114 18.0489C11.2109 17.6852 10.5331 17.6852 10.0325 18.0489L6.03344 20.9545C4.91417 21.7677 3.40821 20.6735 3.83573 19.3577L5.36324 14.6563C5.55444 14.0679 5.34498 13.4232 4.84443 13.0595L0.845351 10.1539C-0.273915 9.34072 0.301312 7.5703 1.6848 7.5703H6.62794C7.24665 7.5703 7.795 7.17188 7.9862 6.58343L9.51371 1.88207Z" fill="#DB6C36" />
                            </svg>
                            <?php
                        }

                        for ($x = 1; $x <= $grey_stars; $x++) {
                            ?>
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.51371 1.88207C9.94123 0.566247 11.8027 0.566248 12.2302 1.88207L13.7577 6.58343C13.9489 7.17188 14.4973 7.5703 15.116 7.5703H20.0591C21.4426 7.5703 22.0179 9.34072 20.8986 10.1539L16.8995 13.0595C16.399 13.4232 16.1895 14.0679 16.3807 14.6563L17.9082 19.3577C18.3357 20.6735 16.8298 21.7677 15.7105 20.9545L11.7114 18.0489C11.2109 17.6852 10.5331 17.6852 10.0325 18.0489L6.03344 20.9545C4.91417 21.7677 3.40821 20.6735 3.83573 19.3577L5.36324 14.6563C5.55444 14.0679 5.34498 13.4232 4.84443 13.0595L0.845351 10.1539C-0.273915 9.34072 0.301312 7.5703 1.6848 7.5703H6.62794C7.24665 7.5703 7.795 7.17188 7.9862 6.58343L9.51371 1.88207Z" fill="#DB6C36" filter="grayscale(1)" />
                            </svg>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="custom-bullets-block">
        <ul class="custom-bullets">
            <li class="custom-bullet active" data-index="1"></li>
            <li class="custom-bullet" data-index="2"></li>
        </ul>
    </div>
    <script>
        jQuery(document).ready(function($) {
            function addDataText(selector) {
                $(selector).each(function () {
                    if (!$(this).attr('data-text') || $(this).attr('data-text') !== $(this).text()) {
                        $(this).attr('data-text', $(this).text());
                    }
                });
            }

            addDataText('.button.order-now');

            const $productItems = $('.custom-product-display .product-item');
            const $bullets = $('.custom-bullet');
            const $slider = $('.custom-product-display');
            let activeIndex = 0;
            let startX = 0;
            let isDragging = false;

            function setActiveSlide(index) {
                $productItems.removeClass('active').eq(index).addClass('active');
                $bullets.removeClass('active').eq(index).addClass('active');
                activeIndex = index;
            }

            $bullets.on('click', function() {
                const index = $(this).data('index') - 1;
                setActiveSlide(index);
            });

            $slider.on('mousedown touchstart', function(e) {
                if (!$bullets.is(':visible')) return;
                startX = e.type === 'mousedown' ? e.clientX : e.originalEvent.touches[0].clientX;
                isDragging = true;
                $slider.css('cursor', 'grabbing');
            });

            $slider.on('mousemove touchmove', function(e) {
                if (!$bullets.is(':visible')) return;
                if (!isDragging) return;

                const currentX = e.type === 'mousemove' ? e.clientX : e.originalEvent.touches[0].clientX;
                const deltaX = currentX - startX;

                if (Math.abs(deltaX) > 50) {
                    if (deltaX < 0 && activeIndex < $productItems.length - 1) {
                        setActiveSlide(activeIndex + 1);
                    } else if (deltaX > 0 && activeIndex > 0) {
                        setActiveSlide(activeIndex - 1);
                    }
                    isDragging = false;
                }
            });

            $slider.on('mouseup touchend', function() {
                if (!$bullets.is(':visible')) return;
                isDragging = false;
                $slider.css('cursor', 'grab');
            });

            if ($(window).width() <= 430) {
                $slider.css('cursor', 'grab');
            }

            $(window).on('resize', function () {
                if ($(window).width() > 430) {
                    $productItems.removeClass('active').css('visibility', 'visible');
                    $productItems.parent().find('[data-index="1"]').addClass('active');
                    $bullets.removeClass('active');
                    $bullets.parent().find('[data-index="1"]').addClass('active');
                    $slider.css('cursor', 'default');
                } else {
                    $slider.css('cursor', 'grab');
                }
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('product_long_description', 'tdc_product_long_description');
function tdc_product_long_description() {
    if (!is_product()) {
        return '';
    }

    global $product;

    $full_description = $product->get_description();

    ob_start();
    ?>
    <div class="product-description">
        <div class="long-description">
            <?php echo wpautop(wp_kses_post($full_description)); ?>
            <div class="fade-effect"></div>
        </div>
        <a href="javascript:void(0);" class="read-more-toggle">Read More</a>
    </div>
    <script>
        jQuery(document).ready(function($) {
            const $readMoreToggle = $('.read-more-toggle');
            const $shortDescription = $('.long-description');
            const $fadeEffect = $('.fade-effect');

            $readMoreToggle.on('click', function() {
                if ($shortDescription.css('maxHeight') === 'none' || $shortDescription.css('maxHeight') === 'none') {
                    $shortDescription.css('maxHeight', '330px');
                    $fadeEffect.show();
                    $readMoreToggle.text('Read More');
                } else {
                    $shortDescription.css('maxHeight', 'none');
                    $fadeEffect.hide();
                    $readMoreToggle.text('Read Less');
                }
            });
        });
    </script>
    <style>
        .long-description {
            text-align: start;
            max-height: 330px;
            overflow: hidden;
            position: relative;
        }
        .long-description ul, .long-description ol {
            padding-inline-start: 15px;
        }
        .long-description .fade-effect {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(transparent 0%, var(--bricks-color-hkwrlr) 100%);
        }
        .product-description .read-more-toggle {
		    font-size: 18px;
		    font-weight: 500;
		    line-height: 30px;
		    text-decoration-line: underline;
		    text-transform: capitalize;
		    color: #132e5d;
            margin-top: 10px;
        }
        @media (max-width: 768px) {
            .product-description .read-more-toggle {
                display: block;
                text-align: center;
            }
        }
    </style>
    <?php

    return ob_get_clean();
}

add_shortcode('custom_product_reviews', 'tdc_product_reviews');
function tdc_product_reviews() {
    global $product;

    if (!is_product() || !$product) {
        return '<div class="custom-reviews">No reviews found.</div>';
    }

    ob_start();

    // Fetch reviews and organize by star rating
    $comments = get_comments(array(
        'post_id' => $product->get_id(),
        'status' => 'approve',
        'type' => 'review',
    ));

    $star_counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    foreach ($comments as $comment) {
        $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
        if ($rating >= 1 && $rating <= 5) {
            $star_counts[$rating]++;
        }
    }
    ?>
    <style>
        .star_single {
            color: #db6c36;
        }
        .custom-reviews {
            max-height: 838px;
            overflow-y: auto;
            border-radius: 8px;
        }
        .custom-reviews::-webkit-scrollbar {
            width: 10px !important;
        }
        .custom-reviews::-webkit-scrollbar-track {
            background: #fff !important;
            border-radius: 10px !important;
            margin: 2px 0;
        }
        .custom-reviews::-webkit-scrollbar-thumb {
            background-color: #e2ceb7 !important;
            border-radius: 10px !important;
            border: 2px solid var(--bricks-color-hkwrlr) !important;
        }
        .custom-reviews::-webkit-scrollbar-thumb:vertical {
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
            border-bottom-left-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
        }
        .filter-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        @media (max-width: 430px) {
            .filter-bar {
                gap: 10px;
            }
        }
        .star-filter {
            display: flex;
            gap: 10px;
            width: calc(100% - 148px - 20px);
        }
        .star-filter .single_filter {
            display: inline-flex;
            align-items: center;
    		justify-content: space-between;
            gap: 4px;
            background-color: var(--bricks-color-hkwrlr);
            color: #132e5d;
            padding: 6.5px 10px;
            font-size: 14px;
            cursor: pointer;
            border: 1.6px solid #132e5d;
            box-shadow: 1.6px 1.6px 1.03px rgba(19, 46, 93, 0.3);
            border-radius: 2px;
			width: 103px;
        }
        @media (max-width: 430px) {
            .star-filter {
                width: calc(100% - 43px - 10px);
            }
            .star-filter .single_filter {
                flex-direction: row;
                flex-wrap: nowrap;
                white-space: nowrap;
                max-width: calc((100% - 40px) / 5);
                padding-left: 0;
                padding-right: 0;
                justify-content: center;
            }
            .star-filter .single_filter .number {
                display: none;
            }
        }
        .search-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6.5px 10px;
            font-size: 14px;
            background-color: var(--bricks-color-hkwrlr);
            border: 1.6px solid #132e5d;
            box-shadow: 1.6px 1.6px 1.03px rgba(19, 46, 93, 0.3);
            border-radius: 2px;
            width: 148px;
            position: relative;
        }
        .search-bar input {
            border: none;
            outline: none;
            width: 97px;
            background-color: transparent;
            color: #132e5d;
            text-align: right;
            line-height: inherit;
        }
        .search-bar input::placeholder {
            color: #132e5d;
        }
        .search-bar input:focus {
            outline: none;
        }
        @media (max-width: 430px) {
            .search-bar {
                width: fit-content;
                padding-top: 8.4px;
                padding-bottom: 8.4px;
                cursor: pointer;
            }
            .search-bar input {
                display: none;
                position: absolute;
                right: 0;
                height: 100%;
                width: 200px;
                background-color: var(--bricks-color-hkwrlr);
                border: 1.6px solid #132e5d;
                box-shadow: 1.6px 1.6px 1.03px rgba(19, 46, 93, 0.3);
                top: 100%;
                margin-top: 10px;
                border-radius: 2px;
            }
        }
        .custom-review-item {
            padding: 20px 0;
            margin-right: 11px;
            border-bottom: 1px solid #e2ceb7;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .review-stars {
            color: #db6c36;
            font-size: 20px;
        }
        .review-date {
            font-family: 'Satoshi-Variable';
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 110%;
            letter-spacing: -0.02em;
            text-transform: capitalize;
            color: #132e5d;
        }
        .custom-review-item h4 {
            font-family: 'Satoshi-Variable';
            font-style: normal;
            font-weight: 800;
            font-size: 22px;
            line-height: 30px;
            color: #132e5d;
        }
        .review-content {
            font-family: 'Satoshi-Variable';
            font-style: normal;
            font-weight: 400;
            font-size: 18px;
            line-height: 30px;
            color: #132e5d;
        }
        .reviewer-info {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .reviewer-info img {
            border-radius: 50%;
            margin-right: 10px;
        }
        .reviewer-name {
            font-family: 'Satoshi-Variable';
            font-style: normal;
            font-weight: 700;
            font-size: 22px;
            line-height: 110%;
            letter-spacing: -0.02em;
            text-transform: capitalize;
            color: #132e5d;
        }
        .search-button {
            background-color: transparent;
            height: 20px;
        }
    </style>

    <div id="reviews" class="custom-reviews-section">
        <!-- Star Filter and Search Bar -->
        <div class="filter-bar">
            <!-- Star Filter -->
            <div class="star-filter">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <span class="single_filter" data-rating="<?php echo $i; ?>">
						<p><span class="star_single">★</span> <?php echo $i; ?></p><span class="number">(<?php echo $star_counts[$i]; ?>)</span>
                    </span>
                <?php endfor; ?>
            </div>
            <!-- Search Bar -->
            <div class="search-bar">
                <button class="search-button" type="button" aria-label="Search">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.0097 8.74341C13.0097 9.56701 12.7655 10.3721 12.3079 11.0569C11.8504 11.7417 11.2 12.2755 10.4391 12.5906C9.67819 12.9058 8.84091 12.9883 8.03313 12.8276C7.22536 12.6669 6.48337 12.2703 5.90099 11.6879C5.31862 11.1056 4.92202 10.3636 4.76134 9.5558C4.60066 8.74803 4.68313 7.91074 4.99831 7.14983C5.31349 6.38893 5.84722 5.73857 6.53202 5.281C7.21682 4.82343 8.02193 4.5792 8.84553 4.5792C9.94995 4.5792 11.0091 5.01793 11.7901 5.79887C12.571 6.57981 13.0097 7.63899 13.0097 8.74341ZM17.5948 17.4927C17.5396 17.548 17.474 17.5919 17.4017 17.6218C17.3295 17.6518 17.2521 17.6672 17.1739 17.6672C17.0958 17.6672 17.0184 17.6518 16.9461 17.6218C16.8739 17.5919 16.8083 17.548 16.7531 17.4927L13.0306 13.7695C11.736 14.8464 10.0761 15.3829 8.39614 15.2672C6.71616 15.1516 5.14542 14.3928 4.01066 13.1486C2.87589 11.9044 2.26445 10.2706 2.30352 8.58712C2.34259 6.90362 3.02916 5.29998 4.22042 4.10977C5.41169 2.91956 7.01593 2.2344 8.69947 2.19682C10.383 2.15924 12.0162 2.77212 13.2594 3.90799C14.5026 5.04385 15.2601 6.61526 15.3742 8.29534C15.4884 9.97542 14.9504 11.6348 13.8723 12.9284L17.5948 16.6509C17.6501 16.7062 17.694 16.7718 17.7239 16.844C17.7539 16.9162 17.7693 16.9936 17.7693 17.0718C17.7693 17.15 17.7539 17.2274 17.7239 17.2996C17.694 17.3718 17.6501 17.4374 17.5948 17.4927ZM8.84553 14.0974C9.90445 14.0974 10.9396 13.7834 11.82 13.1951C12.7005 12.6068 13.3867 11.7706 13.792 10.7923C14.1972 9.81397 14.3032 8.73747 14.0966 7.6989C13.89 6.66033 13.3801 5.70634 12.6314 4.95757C11.8826 4.20881 10.9286 3.69889 9.89004 3.49231C8.85147 3.28572 7.77496 3.39175 6.79665 3.79698C5.81834 4.20221 4.98216 4.88844 4.39386 5.7689C3.80556 6.64935 3.49155 7.68449 3.49155 8.74341C3.49313 10.1629 4.05771 11.5238 5.06144 12.5275C6.06516 13.5312 7.42605 14.0958 8.84553 14.0974Z" fill="#132E5D"/>
                    </svg>
                </button>
                <input type="text" id="review-search-input" placeholder="Search">
            </div>
        </div>

        <div class="custom-reviews" id="reviews-list">
            <?php
            if ($comments) {
                foreach ($comments as $comment) {
                    $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
                    $reviewer_name = $comment->comment_author;
                    $review_date = date('m/d/y', strtotime($comment->comment_date));
                    $review_content = $comment->comment_content;
                    $reviewer_avatar = get_avatar($comment->comment_author_email, 48);
                    ?>
                    <div class="custom-review-item" data-rating="<?php echo $rating; ?>">
                        <div class="review-header">
                            <div class="review-stars">
                                <?php for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $rating ? '★' : '☆';
                                } ?>
                            </div>
                            <div class="review-date"><?php echo esc_html($review_date); ?></div>
                        </div>

						<h4><?php echo get_comment_meta( $comment->comment_ID, "review_title", true ) ?: 'This is a review!' ?></h4>
                        <p class="review-content"><?php echo esc_html($review_content); ?></p>

                        <div class="reviewer-info">
                            <?php echo $reviewer_avatar; ?>
                            <span class="reviewer-name"><?php echo esc_html($reviewer_name); ?></span>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No reviews yet.</p>';
            }
            ?>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            const $starFilters = $(".star-filter span");
            const $searchBar = $('.search-bar');
            const $searchInput = $("#review-search-input");
            const $reviewsList = $("#reviews-list").children();
            let reviewsVisible = false;

            $searchBar.on("click", function (e) {
                if ($(window).width() <= 430) {
                    if (e.target.id !== 'review-search-input') {
                        if ($(this).find('#review-search-input').is(':visible')) {
                            $(this).find('#review-search-input').hide(200);
                        } else {
                            $(this).find('#review-search-input').show(200);
                        }
                    }
                }
            });

            $(document).on('click', function(e) {
                if ($(window).width() <= 430) {
                    if (!$(e.target).closest('.search-bar, #review-search-input').length) {
                        if ($('#review-search-input').is(':visible')) {
                            $('#review-search-input').hide(200);
                        }
                    }
                }
            });

            $starFilters.on("click", function () {
                const rating = $(this).data("rating");
                reviewsVisible = false;

                $reviewsList.each(function () {
                    const $review = $(this);
                    if ($review.data("rating") == rating) {
                        $review.show();
                        reviewsVisible = true;
                    } else {
                        $review.hide();
                    }
                });
                renderNoReviewsMessage();
            });

            $searchInput.on("input", function () {
                const searchTerm = $(this).val().toLowerCase();
                reviewsVisible = false;

                $reviewsList.each(function () {
                    const $review = $(this);
                    const content = $review.find(".review-content").text().toLowerCase();
                    const reviewerName = $review.find(".reviewer-name").text().toLowerCase();

                    if (content.includes(searchTerm) || reviewerName.includes(searchTerm)) {
                        $review.show();
                        reviewsVisible = true;
                    } else {
                        $review.hide();
                    }
                });

                renderNoReviewsMessage();
            });

            function renderNoReviewsMessage() {
                const $noReviewsMessage = $(".no-reviews-message");
                if (!reviewsVisible) {
                    if ($noReviewsMessage.length === 0) {
                        const noReviewsMessage = $("<div>")
                            .addClass("no-reviews-message")
                            .text("No reviews found.");
                        $("#reviews-list").append(noReviewsMessage);
                    }
                } else {
                    $noReviewsMessage.remove();
                }
            }
        });
    </script>
    <?php
    return ob_get_clean();
}

// Add custom column to the WooCommerce product reviews table
add_filter('woocommerce_product_reviews_table_columns', 'tdc_reviews_custom_column', 9999);
function tdc_reviews_custom_column($columns) {
    $new_column = ['review_title' => 'Title'];
    // Reorder columns, inserting the custom column in the correct place
    $columns = array_slice($columns, 0, 3, true) + $new_column + array_slice($columns, 3, NULL, true);
    return $columns;
}

// Display Title
add_action('woocommerce_product_reviews_table_column_review_title', 'tdc_custom_column_content');
function tdc_custom_column_content($item) {
    echo get_comment_meta($item->comment_ID, 'review_title', true) ?: '&mdash;';
}

add_filter('acf/load_value/name=review_type', 'tdc_load_acf_field_from_comment_type', 10, 3);
function tdc_load_acf_field_from_comment_type($value, $post_id, $field) {
    if (strpos($post_id, 'comment_') === 0) {
        $comment_id = str_replace('comment_', '', $post_id);
        $comment = get_comment($comment_id);

        if ($comment && isset($comment->comment_type)) {
            $value = $comment->comment_type;
        }
    }

    return $value;
}

add_action('acf/save_post', 'tdc_update_comment_type_based_on_acf', 20);
function tdc_update_comment_type_based_on_acf($post_id) {
    if (strpos($post_id, 'comment_') === 0) {
        $comment_id = str_replace('comment_', '', $post_id);

        if (isset($_POST['acf'])) {
            foreach ($_POST['acf'] as $field_key => $field_value) {
                $field = acf_get_field($field_key);

                if ($field && $field['name'] === 'review_type') {
                    $review_type = sanitize_text_field($field_value);

                    $new_comment_type = ($review_type === 'review') ? 'review' : 'comment';

                    wp_update_comment([
                        'comment_ID'   => $comment_id,
                        'comment_type' => $new_comment_type,
                    ]);

                    break;
                }
            }
        }
    }
}

add_action( 'add_meta_boxes', 'tdc_force_wc_rating_meta_box', 40 );
function tdc_force_wc_rating_meta_box() {
    remove_meta_box('woocommerce-rating', 'comment', 'normal');
    $screen = get_current_screen();
    $screen_id = $screen ? $screen->id : '';

    if ( 'comment' === $screen_id && isset( $_GET['c'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $comment_id = intval( wc_clean( wp_unslash( $_GET['c'] ) ) );
        $comment = get_comment( $comment_id );

        if ( $comment && 'review' === $comment->comment_type ) {
            add_meta_box(
                'woocommerce-rating',
                __( 'Rating', 'woocommerce' ),
                'WC_Meta_Box_Product_Reviews::output',
                'comment',
                'normal',
                'high'
            );
        }
    }
}

// Sign Up Form - Password Validation - #23-01-25
add_action('bricks/form/custom_action', 'register_form_password_validation', 10, 1);

function register_form_password_validation($form)
{
    $form_fields = $form->get_fields();

    $form_id = "formId"; // your form ID
    $password_id = "form-field-niyjlt"; // your password form field ID
    $con_password_id = "form-field-qlytfl"; // your confirm password form field ID

    $form_id = $form_fields[$form_id];
    $password_value = $form_fields[$password_id];
    $confirm_password_value = $form_fields[$con_password_id];

    // Check if the form id is the one you want
    if ($form_id !== 'abaeb8') {
        return;
    }

    // Check passwords
    if ($password_value !== $confirm_password_value) {
        // Passwords do not match
        wp_send_json_error(
            [
                'code'    => 400,
                'action'  => '',
                'type'    => 'error',
                'message' => esc_html__('Passwords do not match.', 'bricks'),
            ]
        );
    }
} 