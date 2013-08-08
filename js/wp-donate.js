/**
 * WP-donate
 *
 * @since 1.4
 *
 */

// var wp-donate-key declared in DOM from localized script

donate.setPublishableKey( wpdonatekey );

// donate Token Creation & Event Handling

jQuery(document).ready(function($) {

    var resetdonateForm = function() {
        $("#wp-donate-payment-form").get(0).reset();
        $('input').removeClass('donate-valid donate-invalid');
    }

    function donateResponseHandler(status, response) {
        if (response.error) {

            $('.donate-submit-button').prop("disabled", false).css("opacity","1.0");
            $(".payment-errors").show().html(response.error.message);

        } else {

            var form$ = $("#wp-donate-payment-form");
            var token = response['id'];
            form$.append("<input type='hidden' name='donateToken' value='" + token + "' />");

            var newdonateForm = form$.serialize();

            $.ajax({
                type : "post",
                dataType : "json",
                url : ajaxurl,
                data : newdonateForm,
                success: function(response) {

                    $('.wp-donate-details').prepend(response);
                    $('.donate-submit-button').prop("disabled", false).css("opacity","1.0");
                    resetdonateForm();

                }

            });

        }
    }

    $("#wp-donate-payment-form").submit(function(event) {

        event.preventDefault();
        $(".wp-donate-notification").hide();

        $('.donate-submit-button').prop("disabled", true).css("opacity","0.4");

        var amount = $('.wp-donate-card-amount').val() * 100; //amount you want to charge in cents

        donate.createToken({
            name: $('.wp-donate-name').val(),
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, donateResponseHandler);

        // prevent the form from submitting with the default action

        return false;

    });
});

// Form Validation & Enhancement

jQuery(document).ready(function($) {

    $('.card-number').focusout( function() {

        var cardValid = donate.validateCardNumber( $(this).val() );
        var cardType = donate.cardType( $(this).val() );

        // Card Number Validation

        if ( cardValid ) {
            $(this).removeClass('donate-invalid').addClass('donate-valid');
        } else {
            $(this).removeClass('donate-valid').addClass('donate-invalid');
        }

        // Card Type Information

        /*
        if ( cardType && cardValid  ) {
            // Display Card Logo
        }
        */

    });

    // CVC Validation

    $('.card-cvc').focusout( function() {

        if ( donate.validateCVC( $(this).val() ) ) {
            $(this).removeClass('donate-invalid').addClass('donate-valid');
        } else {
            $(this).removeClass('donate-valid').addClass('donate-invalid');
        }

    });

});

