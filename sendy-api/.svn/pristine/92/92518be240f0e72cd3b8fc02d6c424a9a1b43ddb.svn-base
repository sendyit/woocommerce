let script = document.createElement('script');
script.src = 'https://maps.googleapis.com/maps/api/js?&libraries=places&key=AIzaSyD5y2Y1zfyWCWDEPRLDBDYuRoJ8ReHYXwY&callback=initMap';
document.head.appendChild(script);

function initMap() {}
(function( $ ) {
    'use strict';
    console.log('ready');
    $('.woocommerce-shipping-destination').hide();
    $('.woocommerce-shipping-fields').hide();

    $(() => {
        initMap = function() {
            console.log('initializing public maps');
            $('#api_to').val('');
        }
    });

    $('#api_to').keyup(function(){
        console.log("$('#api_to').val()", $('#api_to').val());
        
        if (typeof google === 'object' && typeof google.maps === 'object') {
            console.log("autocomplete", $("#api_to")[0]);
            let country = 'ke';
            let options = {
                componentRestrictions: {country: country},
                // types: ['address']
            };
            let autocomplete = new google.maps.places.Autocomplete($("#api_to")[0], options);
            google.maps.event.addListener(autocomplete, 'place_changed',
            function () {
                let place = autocomplete.getPlace();
                let to_name = place.name;
                let to_lat = place.geometry.location.lat();
                let to_long = place.geometry.location.lng();
                sendRequest(to_name, to_lat, to_long);
                // $.cookie('name', to_name);
            });
        } else {
            $.getScript("https://maps.googleapis.com/maps/api/js?&libraries=places&key=AIzaSyD5y2Y1zfyWCWDEPRLDBDYuRoJ8ReHYXwY&callback=initMap");
        }

        if($('#api_to').val().length !=0) {
            $('#place_order').attr('disabled', false);            
        } else {
            $('#place_order').attr('disabled',true);
        }
    });

    $("form.woocommerce-checkout").on('submit', function(e){
        console.log("submitting checkout");

        if($('#api_to').val().length ==0) {
            $('#place_order').attr('disabled',true);
            console.log("checkout is disabled");
            e.preventDefault();
        }    
     });
   

    function sendRequest(to_name, to_lat, to_long) {
        $('#place_order').attr('disabled',true);
        $.ajax({
            url: ajax_object.ajaxurl,
            type : 'post',
            data: {
                'action':'getPriceQuote',
                'to_name' : to_name,
                'to_lat' : to_lat,
                'to_long' : to_long,
            },
            beforeSend: function () {
                $('#info-block').hide();
                $('.divHidden').hide();
                $('.loader').show();
                $("#submitBtn").show();
                $("#submitBtn").css("background-color", "grey");
                $("#submitBtn").val('PRICING...');
            },
            success: function (res) {
                console.log('response', res);
                $('#place_order').attr('disabled',false);

                let data = JSON.parse(res);

                console.log('responseData', data);

                if (data.status) {
                    let price = data.data.amount;
                    if (price) {
                        $('.loader').hide();
                        $('#submitBtn').hide();
                        $.ajax({
                            url: ajax_object.ajaxurl,
                            type : 'post',
                            data: {
                                'action':'displayDelivery',
                            },
                            success: function (res) {
                                 console.log(res);
                                 location.reload();
                            },
                            error: function(errorThrown){
                                console.log(errorThrown);
                                location.reload();

                            }
                        });
                    }
                    else {
                        $('.loader').hide();
                        $('#submitBtn').hide();
                        $('#api_to').attr("placeholder", "Change delivery destination");
                        $('#info-block').show();
                    }
                } else  {
                    $('.loader').hide();
                    $('#submitBtn').hide();
                    $('#error-block').show();
                    $("#error-block").text(data.description||'an error occured while calculating Sendy shipping cost');
                }

                },
            error: function(errorThrown){
                console.log('ero', errorThrown);
                $('#place_order').attr('disabled',false);
                $('.loader').hide();
            }
        });
    }

})( jQuery );