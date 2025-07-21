<script>
    var defaultBounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(-35.299435, -18.296356),
    new google.maps.LatLng(38.289937, 53.773956));

    var input = document.getElementById('address');
    var options = {
    bounds: defaultBounds,
    types: ['geocode']
    };
    var selected = false

    autocomplete = new google.maps.places.Autocomplete(input, options);
    // autocomplete.setFields(['address_components', 'formatted_address', 'geometry', 'name']);
    autocomplete.addListener('place_changed', onPlaceChanged);

    function setAutocompleteCountry() {
        var country = document.getElementById('country-id').value;
        if (country == 'all') {
            autocomplete.setComponentRestrictions({'country': []});
        } else {
            autocomplete.setComponentRestrictions({'country': country});
        }
    }

    function onPlaceChanged () {
        selected = true
        var place = autocomplete.getPlace();
        if (place.geometry) {
            document.getElementById('lat').value = place.geometry.location.lat();
            document.getElementById('lng').value = place.geometry.location.lng();
        }
    }

    $(document).ready(function () {
        $('#address').focus(function () {
            selected = false
        });

        $("#address").keypress(function (event) {
            if (event.keyCode == 13 || event.keyCode == 9) {
                $(event.target).blur();
                if ($(".pac-container .pac-item:first span:eq(3)").text() == "")
                    firstValue = $(".pac-container .pac-item:first .pac-item-query").text();
                else
                    firstValue = $(".pac-container .pac-item:first .pac-item-query").text() + ", " + $(".pac-container .pac-item:first span:eq(3)").text();
                event.target.value = firstValue;

            } else
                return true;
        });

        $('#address').blur(function () {
            if (!selected) {
                $(this).val('');
            }
        });
    });

</script>