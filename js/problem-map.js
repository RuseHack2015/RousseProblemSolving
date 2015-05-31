/**
 * Created by Z50-70 on 29.5.2015 г..
 */
var latitude;
var longitude;
$(function() {
    $(".close").click(function() {
        $(".modal").fadeOut("slow");
    })
    $(".captcha-replay").trigger("click");
    $(".captcha-replay").click(function() {
        var img = new Image();
        img.src = "/vendor/captcha.php?dummy=" + Math.ceil(Math.random() * 999999)
        img.alt = "CAPTCHA";
        $(".captcha").html(img);
    })

   $("#desired-address").on('blur',function() {
       var address = $("#desired-address").val();
       $.get("https://maps.googleapis.com/maps/api/geocode/json?address=" + address, function(data) {

           latitude = data.results[0].geometry.location.lat;
           longitude = data.results[0].geometry.location.lng;
           initialize();
       })
   });
    var geocoder = new google.maps.Geocoder();

    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                updateMarkerAddress(responses[0].formatted_address);
            } else {
                updateMarkerAddress('Не може да се определи адреса на това място.');
            }
        });
    }

    function updateMarkerStatus(str) {

    }

    function updateMarkerPosition(latLng) {
        $("#lat").val(latLng.lat());
        $("#long").val(latLng.lng());
    }

    function updateMarkerAddress(str) {
        document.getElementById('desired-address').value = str;
    }

    function initialize() {
        latitude = latitude || 43.849578600000000000;
        longitude = longitude || 25.955229199999962000;
        var latLng = new google.maps.LatLng(latitude, longitude);
        var map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 13,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var marker = new google.maps.Marker({
            position: latLng,
            title: 'Point A',
            map: map,
            draggable: true
        });

        // Update current position info.
        updateMarkerPosition(latLng);
        geocodePosition(latLng);

        // Add dragging event listeners.
        google.maps.event.addListener(marker, 'dragstart', function() {
            updateMarkerAddress('Избиране...');
        });

        google.maps.event.addListener(marker, 'drag', function() {
            updateMarkerStatus('Избиране...');
            updateMarkerPosition(marker.getPosition());
        });

        google.maps.event.addListener(marker, 'dragend', function() {
            updateMarkerStatus('Избирането свърши');
            geocodePosition(marker.getPosition());
        });
    }

    // Onload handler to fire off the app.
    google.maps.event.addDomListener(window, 'load', initialize);
})