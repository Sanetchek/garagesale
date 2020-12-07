jQuery(document).ready(function($) {
    /*
    ===================================================================
              Place Autocomplete Address Form
    ===================================================================
    */

    var placeSearch, autocomplete, place;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };
    var adminArea =  $( '#administrative_area_level_1' ),
        postal =  $( '#postal_code' ),
        route =  $( '#route' ),
        streetNum =  $( '#street_number' ),
        locality =  $( '#locality' ),
        country =  $( '#country' );

    placeSearch = $( '#autocomplete' );

    placeSearch.on('focus', function() {
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */( document.getElementById( 'autocomplete' )),
            {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
        autocomplete.addListener('place_changed', changeLocationFields);
    });

    
    function fillInAddress() {
        // Get the place details from the autocomplete object.
        place = autocomplete.getPlace();
        
        for ( var component in componentForm ) {
            document.getElementById( component ).value = '';
            document.getElementById( component ).disabled = false;
        }

        var placeAddrComp = place.address_components;
        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for ( var i = 0; i < placeAddrComp.length; i++ ) {
            var addressType = placeAddrComp[i].types[0];
            if ( componentForm[addressType] ) {
                var val = placeAddrComp[i][componentForm[addressType]];
                document.getElementById( addressType ).value = val;
            }
        }
    }

    function changeLocationFields() {
        if ( country.val() || adminArea.val() || postal.val() || route.val() || streetNum.val() || locality.val() ) {
            $( '#admin_area' ).val( adminArea.val() );
            $( '#post_code' ).val( postal.val() );
            $( '#city_route' ).val( route.val() );
            $( '#street_num' ).val( streetNum.val() );
            $( '#local' ).val( locality.val() );
            $( '#country_name' ).val( country.val() );
        }
    }
});