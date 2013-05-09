(function() {
    $(function() {

        if (typeof Dropzone !== 'undefined') {
          Dropzone.autoDiscover = false;
          $("div#photos").dropzone({url: '/upload'});

          var myDropzone = Dropzone.forElement("div#photos");

          myDropzone.on('sending', function(file, xhr, formData) {
            // Clear any previous errors
            $('div#photo-errors').html('');
          });

          myDropzone.on('success', function(file, response) {
            $('div#photo-ids').append('<input type="hidden" name="photo_ids[]" value="' + response.id + '" />');
          });

          myDropzone.on('error', function(file, message, response) {
            $('div#photo-errors').html('<div class="alert alert-error">' + message + '</div>');
            console.log(file, message, response);
          });
        }

        if ($('#map-canvas').length)
        {
            // Define the address we want to map.
            var address = $('#map-canvas').attr('data-address');

            // Create a new Geocoder
            var geocoder = new google.maps.Geocoder();

            // Locate the address using the Geocoder.
            geocoder.geocode( { "address": address }, function(results, status) {

              // If the Geocoding was successful
              if (status == google.maps.GeocoderStatus.OK) {

                // Create a Google Map at the latitude/longitude returned by the Geocoder.
                var myOptions = {
                  zoom: 8,
                  center: results[0].geometry.location,
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                var map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);

                // Add a marker at the address.
                var marker = new google.maps.Marker({
                  map: map,
                  position: results[0].geometry.location
                });

              } else {
                try {
                  console.error("Geocode was not successful for the following reason: " + status);
                } catch(e) {}
              }
            });
        }

    });
})();