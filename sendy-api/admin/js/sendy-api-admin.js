let adminGoogleScript = document.createElement('script');
adminGoogleScript.src = 'https://maps.googleapis.com/maps/api/js?&libraries=places&key=AIzaSyD5y2Y1zfyWCWDEPRLDBDYuRoJ8ReHYXwY&callback=initMap';
document.head.appendChild(adminGoogleScript);

function initMap() {}
(function( $ ) {
	'use strict';
    console.log('ready');
    $(() => {
        initMap = function() {
            console.log('initializing maps');
            let country = 'ke';
            let options = {
                componentRestrictions: {country: country},
                // types: ['address']
            };
            console.log("autocomplete", $("#woocommerce_sendy-woocommerce-shipping_shop_location"));

            let autocomplete = new google.maps.places.Autocomplete($("#woocommerce_sendy-woocommerce-shipping_shop_location")[0], options);
            google.maps.event.addListener(autocomplete, 'place_changed',
                function () {
                    let place = autocomplete.getPlace();
                    let from_lat = place.geometry.location.lat();
                    let from_long = place.geometry.location.lng();
                    sendData(from_lat, from_long);
                });
        }
    });
    function sendData(from_lat, from_long){
        $("#woocommerce_sendy-woocommerce-shipping_from_lat").val(from_lat);
        $("#woocommerce_sendy-woocommerce-shipping_from_long").val(from_long);
    }

})( jQuery );
