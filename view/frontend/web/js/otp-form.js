/**
 * @author manugentoo@gmail.com
 */
define([
    'jquery'
], function ($) {
    'use strict';

    return function (config) {

        var otpSubmitButton = $('#' + config.otpSubmitButton);
        var otpInputFields = $('.' + config.otpInputFields);
        var otpCode = $('#' + config.otpCode);

        otpSubmitButton.on('click', function(){
            var otp = '';
            otpInputFields.each(function(e) {
                otp += $(this).val();
            });
            otpCode.val(otp);
        })
    };
});
