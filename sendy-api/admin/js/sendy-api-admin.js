jQuery.getScript("https://maps.googleapis.com/maps/api/js?&libraries=places&key=AIzaSyD5y2Y1zfyWCWDEPRLDBDYuRoJ8ReHYXwY&callback=initMap");
function initMap() {}
(function( $ ) {
	'use strict';
    console.log('ready');
    $('label[for=woocommerce_sendy-ecommerce_from_lat], input#woocommerce_sendy-ecommerce_from_lat').hide();
    $('label[for=woocommerce_sendy-ecommerce_from_long], input#woocommerce_sendy-ecommerce_from_long').hide();
    $(() => {
        initMap = function() {
            console.log('initializing maps');
            let country = 'ke';
            let options = {
                componentRestrictions: {country: country},
                // types: ['address']
            };
            let autocomplete = new google.maps.places.Autocomplete($("#woocommerce_sendy-ecommerce_shop_location")[0], options);
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
        $("#woocommerce_sendy-ecommerce_from_lat").val(from_lat);
        $("#woocommerce_sendy-ecommerce_from_long").val(from_long);
    }

})( jQuery );
