(function() {
    $(function() {
        console.log($('.tooltip-toggle').length);
        $('.tooltip-toggle').each(function(index, element) {
          $(element).tooltip();
        });

        $('#contact-form #send').click(function(e) {
          e.preventDefault();

          var contactForm = $('#contact-form');
          contactForm.find('#contact-form-spinner').html('<i class="icon-spinner icon-spin icon-large"></i>');

          var data = {
            name:    contactForm.find('#name').val(),
            phone:   contactForm.find('#phone').val(),
            email:   contactForm.find('#email').val(),
            subject: contactForm.find('#subject').val(),
            message: contactForm.find('#message').val(),
            post_id: contactForm.find('input[name=post_id]').val()
          };

          $.ajax({
            type: 'post',
            url: '/posts/contact',
            data: data
          }).done(function(response) {
            contactForm.find('#contact-form-spinner').html('');
            $('#contact-form-status').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a>' + response.message + '</div>');
            $('#contact-form').modal('hide');
          });
        });

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

        $('.thumbnail-edit button.close').click(function(e) {
          // Get the photo's id from the data-photo-id attribute
          var id = $(this).attr('data-photo-id');

          // Remove the DOM element
          $(this).parent().remove();

          // Append a hidden field element denoting which photo we would like to remove
          $('#photo-remove-ids').append('<input type="hidden" name="photo_remove_ids[]" value="' + id + '" />');
        });

        $('input[type=file]#main_photo').change(function(e) {
          $('#main_photo_thumbnail').remove();
        });

        $('select[name=log]').change(function(e) {
          window.location = '/admin/log/' + $(this).find('option:selected').text();
        });

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