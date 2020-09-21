<?php

/**
 * Plugin Name:       Sendy Ecommmerce
 * Plugin URI:        https://github.com/sendyit/woocommerce
 * Description:       This is the Sendy WooCommerce Plugin for Sendy Public API.
 * Version:           1.0.1
 * Author:            Sendy Engineering
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sendy-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('PLUGIN_NAME_VERSION', '1.0.0');

function activate_sendy_api()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-sendy-api-activator.php';
    Sendy_Api_Activator::activate();
}

function deactivate_sendy_api()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-sendy-api-deactivator.php';
    Sendy_Api_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_sendy_api');
register_deactivation_hook(__FILE__, 'deactivate_sendy_api');

require plugin_dir_path(__FILE__) . 'includes/class-sendy-api.php';

    /*
     * Check if WooCommerce is active
     */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    function sendy_shipping_method()
    {
        if (!class_exists('Sendy_Shipping_Method')) {
            class Sendy_Shipping_Method extends WC_Shipping_Method
            {
                public function __construct()
                {
                    $this->id = 'sendy-ecommerce';
                    $this->method_title = __('Sendy Ecommerce', 'sendy-ecommerce');
                    $this->method_description = __('The Sendy Woocommerce Plugin for Sendy Public API.', 'sendy-ecommerce');

                    // Availability & Countries
                    $this->availability = 'including';
                    $this->countries = array(
                        'KE', // Kenya
                        'UG', // Uganda
                        'TZ' // Tanzania
                    );

                    $this->init();

                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('Sendy Ecommerce', 'sendy-ecommerce');
                }

                function init()
                {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                function init_form_fields()
                {
                    $this->form_fields = array(
                        'environment' => array(
                            'title' => __('Environment', 'sendy-ecommerce'),
                            'type' => 'select',
                            'options' => array(
                                'blank' => __('Select Sendy Environment', 'sendy-ecommerce'),
                                'sandbox' => __('Sandbox', 'sendy-ecommerce'),
                                'live' => __('Live', 'sendy-ecommerce')
                                  )
                        ),
                        'sendy_api_key' => array(
                            'title' => __('Sendy Api Key', 'sendy-ecommerce'),
                            'type' => 'text',
                            'id' => 'key',
                            'default' => __('mysendykey', 'sendy-ecommerce')
                        ),

                        'sendy_api_username' => array(
                            'title' => __('Sendy Api Username', 'sendy-ecommerce'),
                            'type' => 'text',
                            'default' => __('mysendyusername', 'sendy-ecommerce')
                        ),

                        'shop_location' => array(
                            'title' => __('Shop Location', 'sendy-ecommerce'),
                            'type' => 'text',
                            'placeholder' => 'Enter a location',
                            'description' => __('Please Pick From Google Map Suggestions.', 'sendy-ecommerce')
                        ),
                        'from_lat' => array(
                            'title' => __('Latitude', 'sendy-ecommerce'),
                            'type' => 'text'
                        ),

                        'from_long' => array(
                            'title' => __('Longitude', 'sendy-ecommerce'),
                            'type' => 'text'
                        ),

                       

                        'open_hours' => array(
                            'title' => __('Shop Opening Hours', 'sendy-ecommerce'),
                            'type' => 'select',
                            'options' => array(
                                'blank' => __('Select opening hour', 'sendy-ecommerce'),
                                '6' => __('6:00 AM', 'sendy-ecommerce'),
                                '7' => __('7:00 AM', 'sendy-ecommerce'),
                                '8' => __('8:00 AM', 'sendy-ecommerce'),
                                '9' => __('9:00 AM', 'sendy-ecommerce'),
                                '10' => __('10:00 AM', 'sendy-ecommerce'),
                                '11' => __('11:00 AM', 'sendy-ecommerce'),
                                '12' => __('12:00 PM', 'sendy-ecommerce'),
                                '13' => __('1:00 PM', 'sendy-ecommerce'),
                                '14' => __('2:00 PM', 'sendy-ecommerce'),
                                '15' => __('3:00 PM', 'sendy-ecommerce'),
                                '16' => __('4:00 PM', 'sendy-ecommerce'),
                                '17' => __('5:00 PM', 'sendy-ecommerce'),
                                '18' => __('6:00 PM', 'sendy-ecommerce'),
                                '19' => __('7:00 PM', 'sendy-ecommerce'),
                                '20' => __('8:00 PM', 'sendy-ecommerce')
                            )
                        ),

                        'close_hours' => array(
                            'title' => __('Shop Closing Hours', 'sendy-ecommerce'),
                            'type' => 'select',
                            'options' => array(
                                'blank' => __('Select closing hour', 'sendy-ecommerce'),
                                '6' => __('6:00 AM', 'sendy-ecommerce'),
                                '7' => __('7:00 AM', 'sendy-ecommerce'),
                                '8' => __('8:00 AM', 'sendy-ecommerce'),
                                '9' => __('9:00 AM', 'sendy-ecommerce'),
                                '10' => __('10:00 AM', 'sendy-ecommerce'),
                                '11' => __('11:00 AM', 'sendy-ecommerce'),
                                '12' => __('12:00 PM', 'sendy-ecommerce'),
                                '13' => __('1:00 PM', 'sendy-ecommerce'),
                                '14' => __('2:00 PM', 'sendy-ecommerce'),
                                '15' => __('3:00 PM', 'sendy-ecommerce'),
                                '16' => __('4:00 PM', 'sendy-ecommerce'),
                                '17' => __('5:00 PM', 'sendy-ecommerce'),
                                '18' => __('6:00 PM', 'sendy-ecommerce'),
                                '19' => __('7:00 PM', 'sendy-ecommerce'),
                                '20' => __('8:00 PM', 'sendy-ecommerce')
                            )
                        ),

                        'vendor_type' => array(
                            'title' => __('Default Vendor Type', 'sendy-ecommerce'),
                            'type' => 'select',
                            'options' => array(
                                'blank' => __('Select Vendor Type', 'sendy-ecommerce'),
                                '21'    => __('Runner', 'sendy-ecommerce'),
                                '1'     => __('Bike', 'sendy-ecommerce'),
                                '2'     => __('Pick Up', 'sendy-ecommerce'),
                                '6'     => __('3T Truck', 'sendy-ecommerce'),
                                '10'    => __('5T Truck', 'sendy-ecommerce'),
                                '13'    => __('7T Truck', 'sendy-ecommerce'),
                                '14'    => __('10T Truck', 'sendy-ecommerce')
                            )
                        ),
                        'sender_name' => array(
                            'title' => __('Sender Name', 'sendy-ecommerce'),
                            'type' => 'text'
                        ),
                        'sender_phone' => array(
                            'title' => __('Sender Phone', 'sendy-ecommerce'),
                            'type' => 'text'
                        ),
                        'sender_email' => array(
                            'title' => __('Sender Email', 'sendy-ecommerce'),
                            'type' => 'text'
                        ),
                        'sender_notes' => array(
                            'title' => __('Sender Delivery Notes', 'sendy-ecommerce'),
                            'type' => 'textarea'
                        ),

                        'notify_sender' => array(
                            'title' => __('Notify Sender', 'sendy-ecommerce'),
                            'type' => 'select',
                            'options' => array(
                                'blank' => __('Notify Sender', 'sendy-ecommerce'),
                                true    => __('Yes', 'sendy-ecommerce'),
                                false     => __('No', 'sendy-ecommerce')
                            )
                        ),
                        'notify_recipient' => array(
                            'title' => __('Notify Recipient', 'sendy-ecommerce'),
                            'type' => 'select',
                            'options' => array(
                                'blank' => __('Notify Recipient', 'sendy-ecommerce'),
                                true    => __('Yes', 'sendy-ecommerce'),
                                false     => __('No', 'sendy-ecommerce')
                            )
                        ),

                        'building' => array(
                            'title' => __('Building', 'sendy-ecommerce'),
                            'type' => 'text'
                        ),

                        'floor' => array(
                            'title' => __('Floor', 'sendy-ecommerce'),
                            'type' => 'text'

                        ),

                        'other_details' => array(
                            'title' => __('Other Details', 'sendy-ecommerce'),
                            'type' => 'textarea'
                        )

                        

                    );

                }

                public function calculate_shipping($package)
                {
                    $weight = 0;
                    $cost = 0;
                    $country = $package["destination"]["country"];

                    foreach ($package['contents'] as $item_id => $values) {
                        $_product = $values['data'];
                        $weight = $weight + $_product->get_weight() * $values['quantity'];
                    }

                    $weight = wc_get_weight($weight, 'kg');

                    if ($weight <= 10) {

                        $cost = 0;

                    } elseif ($weight <= 30) {

                        $cost = 5;

                    } elseif ($weight <= 50) {

                        $cost = 10;

                    } else {

                        $cost = 20;

                    }

                    $countryZones = array(
                        'KE' => 0,
                        'UG' => 3,
                        'TZ' => 2
                    );

                    $zonePrices = array(
                        0 => 20,
                        1 => 30,
                        2 => 50,
                        3 => 70
                    );

                    $zoneFromCountry = $countryZones[$country];
                    $priceFromZone = $zonePrices[$zoneFromCountry];

                    $cost += $priceFromZone;

                    $rate = array(
                        'id' => $this->id,
                        'label' => "Sendy",
                        'cost' => $cost
                    );

                    $this->add_rate($rate);

                }
            }
        }
    }

    function register_session()
    {
        if (!session_id())
            session_start();
    }

    add_action('init', 'register_session');

    add_action('woocommerce_shipping_init', 'sendy_shipping_method');

    function add_sendy_shipping_method($methods)
    {
        $methods[] = 'Sendy_Shipping_Method';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_sendy_shipping_method');

    function get_delivery_address()
    {
        echo '<div class="sendy-api">
            <div class="input-block">
               <input class="input" id="api_to" type="text" placeholder="Enter A Delivery Address to Get A Sendy Quote">
            </div>
            </div>
            <div class="loader"></div>
            <div id="pricing" class="divHidden">
                <div class="show-currency" >KES</div>
                <div class="show-price">240</div>
            </div>
            <button id="submitBtn" type="button" class="btn">PRICING...</button>
            <div id="info-block" class="alert alert-info">
                Please choose a location within Nairobi to deliver with Sendy.
            </div>
            <div id="error-block" class="alert alert-danger">
            </div>';
    }

    add_action( 'woocommerce_cart_totals_before_shipping', 'get_delivery_address', 10, 0 ); 


    function add_js_scripts()
    {
        wp_register_script('moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js', null, null, true);
        wp_enqueue_script('moment');
        wp_enqueue_script('moment', plugin_dir_url(__FILE__) . '/public/js/cookie.js', array('jquery'), '1.0', true);
        wp_enqueue_script('cookie-script', plugin_dir_url(__FILE__) . '/public/js/cookie.js', array('jquery'), '1.0', true);
        wp_enqueue_script('ajax-script', plugin_dir_url(__FILE__) . '/public/js/sendy-api-public.js', array('jquery'), '1.0', true);
        wp_localize_script('ajax-script', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    function add_style()
    {
        wp_enqueue_style('styles', plugin_dir_url(__FILE__) . '/public/css/sendy-api-public.css', false);
    }

    add_action('wp_enqueue_scripts', 'add_js_scripts');
    add_action('wp_enqueue_scripts', 'add_style');

    function add_admin_scripts()
    {
        wp_enqueue_script('admin-script', plugin_dir_url(__FILE__) . '/admin/js/sendy-api-admin.js', array('jquery'), '1.0', true);
    }
    add_action('admin_enqueue_scripts', 'add_admin_scripts');

    
    function getPriceQuote($delivery = false, $type = "quote",  $pick_up_date = null,    $note = "Sample Note", $recepient_name = "Dervine N", $recepient_phone = "0716163362", $recepient_email = "ndervine@sendy.co.ke")
    {
        //if post is set
        if (isset($_POST['to_name'])) {
            $to_name = $_POST['to_name'];
            $to_lat = $_POST['to_lat'];
            $to_long = $_POST['to_long'];
            //then update session
            $_SESSION['to_name'] = $to_name;
            $_SESSION['to_lat'] = $to_lat;
            $_SESSION['to_long'] = $to_long;
        } else {
            //use session
            $to_name = $_SESSION['to_name'];
            $to_lat = $_SESSION['to_lat'];
            $to_long = $_SESSION['to_long'];
        }
        $to_name = $to_name;
        $to_lat = $to_lat;
        $to_long = $to_long;
        $sendy_settings = get_option('woocommerce_sendy-ecommerce_settings');
        $api_key = $sendy_settings['sendy_api_key'];
        $api_username = $sendy_settings['sendy_api_username'];
        $pickup = $sendy_settings['shop_location'];
        $pickup_lat = $sendy_settings['from_lat'];
        $pickup_long = $sendy_settings['from_long'];
        $vendor_type = $sendy_settings['vendor_type'];
        $notify_recipient = $sendy_settings['notify_recipient'];

        if($pick_up_date === null) {
            $pick_up_date =  date('m/d/Y h:i:s a', time());
        }


        $request = '{
                      "command": "request",
                      "data": {
                        "api_key": "' . $api_key . '",
                        "api_username": "' . $api_username . '",
                        "vendor_type": "'. $vendor_type .'",
                        "from": {
                          "from_name": "' . $pickup . '",
                          "from_lat": "' . $pickup_lat . '",
                          "from_long": "' . $pickup_long . '",
                          "from_description": ""
                        },
                        "to": {
                          "to_name": "' . $to_name . '",
                          "to_lat": "' . $to_lat . '",
                          "to_long": "' . $to_long . '",
                          "to_description": ""
                        },
                        "recepient": {
                          "recepient_name": "' . $recepient_name . '",
                          "recepient_phone": "' . $recepient_phone . '",
                          "recepient_email": "' . $recepient_email . '",
                          "recepient_notes":"",
                          "recepient_notify":"'. boolval($notify_recipient).'"
                        },
                        "delivery_details": {
                          "pick_up_date": "' . $pick_up_date . '",
                          "return": false,
                          "note": "' . $note . '",
                          "note_status": true,
                          "request_type": "' . $type . '",
                          "order_type": "ondemand_delivery",
                          "skew": 1,
                          "package_size": [
                            {
                              "weight": 20,
                              "height": 10,
                              "width": 200,
                              "length": 30,
                              "item_name": "laptop"
                            }
                          ]
                        }
                      },
                      "request_token_id": "request_token_id"
                    }';
        
        $payload = json_encode(json_decode($request, true));

        $request_url = "https://apitest.sendyit.com/v1/";

        if($sendy_settings['environment'] === 'live') {
            $request_url = "https://api.sendyit.com/v1/";
        }

        $ch = curl_init($request_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload))
        );
        $result = curl_exec($ch);
        $json = json_decode($result, true);
        $cost = $json['data']['amount'];
        $order_no = $json['data']['order_no'];
        $_SESSION['arrayImg'] = $cost;
        $_SESSION['sendyOrderNo'] = $order_no;
        if ($type == "quote") {
            echo $result;
            die();
        } else if ($type == "delivery") {
            return;
        }

    }

    add_action('wp_ajax_nopriv_getPriceQuote', 'getPriceQuote');
    add_action('wp_ajax_getPriceQuote', 'getPriceQuote');

    add_action('woocommerce_after_shipping_rate', 'displayDelivery');
    function displayDelivery()
    {
        if (isset($_POST)) {
            $hour = $order_hour = $var = date('H', strtotime('+3 hours'));
            if ($hour >= 14 && $hour <= 20) {
                echo '<div id="delivery-info" class="alert alert-info">
                Orders placed from <b>2 PM</b> will be delivered on the next day via Sendy.
            </div>';
            } else if ($hour >= 6 && $hour <= 14) {
                echo '<div id="delivery-info" class="alert alert-info">
                Orders placed before <b>2 PM</b> will be delivered on the same day via Sendy.
            </div>';
            } else {
                echo '<div id="delivery-info" class="alert alert-info">
                Place orders as from <b>6 AM</b> to <b>8 PM</b> to deliver with Sendy.
            </div>';
            }
        }
    }

    add_action('wp_ajax_nopriv_displayDelivery', 'displayDelivery');
    add_action('wp_ajax_displayDelivery', 'displayDelivery');

    function setSendyDeliveryCost($rates, $package)
    {
        if (!session_id())
            session_start();

        $cost = $_SESSION['arrayImg'];
        if (isset($rates['sendy-ecommerce'])) {
            $rates['sendy-ecommerce']->cost = $cost;
        }

        return $rates;
    }

    add_filter('woocommerce_package_rates', 'setSendyDeliveryCost', 10, 2);

    add_action('woocommerce_thankyou', 'completeOrder', 10, 1);

    function completeOrder($order_id)
    {

        if (!$order_id) {
            return;
        } else {
            if (!session_id()) {
                session_start();
            } else {
                $order = new WC_Order($order_id);
                $note = $order->customer_message;
                $fName = $order->billing_first_name;
                $lName = $order->billing_last_name;
                $name = $fName . ' ' . $lName;
                $phone = $order->billing_email;
                $email = $order->billing_phone;
                $sendy_settings = get_option('woocommerce_sendy-ecommerce_settings');
                $order_no = $_SESSION['sendyOrderNo'];
                $sendy_hour = 14;
                $type = "delivery";
                $open_hour = $sendy_settings['open_hours'];
                $close_hour = $sendy_settings['close_hours'];
                $order_hour = $var = date('H', strtotime('+3 hours'));
                if ($order_hour < $open_hour && $order_hour >= $close_hour && $close_hour < 20) {
                    $pick_up_date = date("Y-m-d H:i:s", strtotime('+3 hours'));
                } else if ($order_hour >= $open_hour && $order_hour < $sendy_hour) {
                    $pick_up_date = date("Y-m-d H:i:s", strtotime('+3 hours'));
                } else if ($order_hour >= $sendy_hour && $order_hour < $close_hour && $close_hour < 20) {
                    $pick_up_date = date("Y-m-d H:i:s", mktime(8, 00, 0, date('n'), date('j') + 1, date('Y')));
                } else {
                    $pick_up_date = date("Y-m-d H:i:s", strtotime('+3 hours'));
                }

                getPriceQuote(true, $type, $pick_up_date, $note, $name, $phone, $email);

                $tracking_url = 'https://app.sendyit.com/biz/sendyconnect/track/' . $order_no;

                echo "<p><a target=\"_tab\" href='" . $tracking_url . "'> Click Here To Track Your Sendy Order. </a></p>";
                return;
            }
        }
    }
}

function run_sendy_api()
{

    $plugin = new Sendy_Api();
    $plugin->run();

}

run_sendy_api();
