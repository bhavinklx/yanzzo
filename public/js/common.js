function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function validate_search() {
    const city = document.getElementById('city').value;
    if (city === "") {
        $('#city').css('border', '1px solid red');
        return false; // Prevent form submission
    } else {
        $('#city').css('border', '');
    }
    return true; // Allow form submission
}

//for contact
function validate_contact() {
    var current_path = window.location.pathname.split('/').pop();
    //alert('Hello')
    $('#common_msg').html('');
    var emailFilter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,4}))$/;
    var email = $('#contact_email').val();
    var mobile = $('#contact_mobile').val();
    //var isCareer = $('#is_career').val();
    var error = false;

    if ($('#contact_fname').val() == '') {
        error = true;
        $('#contact_fname').addClass('is-invalid');
    } else {
        $('#contact_fname').removeClass('is-invalid');
    }

    if ($('#contact_lname').val() == '') {
        error = true;
        $('#contact_lname').addClass('is-invalid');
    } else {
        $('#contact_lname').removeClass('is-invalid');
    }

    if ($('#contact_email').val() == '') {
        error = true;
        $('#contact_email').addClass('is-invalid');
    } else if (email != '' && !(emailFilter.test(email))) {
        error = true;
        $('#contact_email').addClass('is-invalid');
    } else {
        $('#contact_email').removeClass('is-invalid');
    }

    if ($('#contact_mobile').val() == '') {
        error = true;
        $('#contact_mobile').addClass('is-invalid');
    } else if (mobile.length < 10) {
        error = true;
        $('#contact_mobile').addClass('is-invalid');
    } else {
        $('#contact_mobile').removeClass('is-invalid');
    }

    if ($('#contact_subject').val() == '') {
        error = true;
        $('#contact_subject').addClass('is-invalid');
    } else {
        $('#contact_subject').removeClass('is-invalid');
    }

    if ($('#contact_message').val() == '') {
        error = true;
        $('#contact_message').addClass('is-invalid');
    } else {
        $('#contact_message').removeClass('is-invalid');
    }

    if (error == false) {
        var fname = $('#contact_fname').val();
        var lname = $('#contact_lname').val();
        var email = $('#contact_email').val();
        var country = $('#country').val();
        var prefix = $('#prefix').val();
        var mobile = $('#contact_mobile').val();
        var subject = $('#contact_subject').val();
        var message = $('#contact_message').val();
        //$('#submit').html('<img src="https://artenspace.com/public/img/loader.gif" width="40px">');
        $('#submit').prop('disabled', true);

        setTimeout(function () {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "/contact-insert",
                type: "POST",
                data: { act: "contact", fname: fname, lname: lname, email: email, country: country, prefix: prefix, mobile: mobile, subject: subject, message: message },
                success: function (result) {
                    if (result == 'success') {
                        $("#contactForm").fadeOut(500);
                        $('#submit').prop('disabled', false);
                        $("#contactForm").trigger("reset");
                        setTimeout(function () {
                            $('#thankMsg').html('<h2 style="margin-bottom: 0 !important; color: black !important">Thank You</h2><p style="color: green;">Your message has been submitted. We will contact you shortly</p>');
                            //window.location = "https://www.artenspace.com/thank-you";
                        }, 100);
                    } else if (result == 'wrong') {
                        $('#thankMsg').html('<h2 style="margin-bottom: 0 !important; margin-top: 10px !important;">Robot verification failed, please try again</h2>');
                        $('#submit').html('Submit');
                        $('#submit').prop('disabled', false);
                    }
                }
            });
        }, 100);
    } else {
        $('#common_msg').html('Your entry is not valid. Please correct it and submit again.');
        return false;
    }
}

window.onload = () => {
    //set local storage
    localStorage.setItem('isLoader', 'true');
    if (localStorage.getItem('isLoader') === 'true') {
        $('#overlay-loader').hide();
    }
};

// Clear localStorage
window.onbeforeunload = () => {
    localStorage.removeItem('isLoader');
}

//for register popup
function signup_popup() {
    $('#login_form').find('input:text, input:password, select, textarea').val('');
    //$('#login_form').find('input:radio, input:checkbox').prop('checked', false);

    $('#signup_form').find('input:text, input:password, select, textarea').val('');
    $('#signup_form').find('input:radio, input:checkbox').prop('checked', false);

    $('#forgot_form').find('input:text, input:password, select, textarea').val('');
    $('#forgot_form').find('input:radio, input:checkbox').prop('checked', false);

    $('#loginThankMsg').html('');
    $('#fotpThankMsg').html('');
    $('#otpThankMsg').html('');

    $('.help-block').html('');
    $('#signin-modal').modal("hide");
    $('#signup-modal').modal("show");
}

//for login popup
function signin_popup() {
    $('#login_form').find('input:text, input:password, select, textarea').val('');
    //$('#login_form').find('input:radio, input:checkbox').prop('checked', false);

    $('#signup_form').find('input:text, input:password, select, textarea').val('');
    $('#signup_form').find('input:radio, input:checkbox').prop('checked', false);

    $('#forgot_form').find('input:text, input:password, select, textarea').val('');
    //$('#forgot_form').find('input:radio, input:checkbox').prop('checked', false);

    $('#loginThankMsg').html('');
    $('#fotpThankMsg').html('');
    $('#otpThankMsg').html('');

    $('.help-block').html('');
    $('#signup-modal').modal("hide");
    $('#signin-modal').modal("show");
}

//for forgot password popup
function forgot_popup() {
    //alert('Hello')
    $('#login_form').find('input:text, input:password, select, textarea').val('');
    //$('#login_form').find('input:radio, input:checkbox').prop('checked', false);

    $('#signup_form').find('input:text, input:password, select, textarea').val('');
    $('#signup_form').find('input:radio, input:checkbox').prop('checked', false);

    $('#forgot_form').find('input:text, input:password, select, textarea').val('');
    $('#forgot_form').find('input:radio, input:checkbox').prop('checked', false);

    $('#loginThankMsg').html('');
    $('#fotpThankMsg').html('');
    $('#otpThankMsg').html('');

    $('.help-block').html('');

    $('#signin-modal').modal("hide");
    $('#forgot-modal').modal("show");
}

function validate_signup() {
    var emailFilter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,4}))$/;
    var email = $('#user_email').val();
    var mobile = $('#user_mobile').val();
    var gst = $('#user_gst').val();
    var error = false;

    if ($('#user_name').val() == '') {
        error = true;
        $('#uname_msg').html('Please enter your name');
        $('#uname_msg').css({ 'color': 'red' });
    } else {
        $('#uname_msg').html('');
    }

    if (validate_email(email)) {
        error = true;
    } else {
    }

    if (validate_mobile(mobile)) {
        error = true;
    } else {
    }

    /*if ($('#user_mobile').val() == '') {
     error = true;
     $('#umobile_msg').html('Please enter your mobile number');
     $('#umobile_msg').css({'color':'red'});
     } else {
     $('#umobile_msg').html('');
     }*/

    if ($('#user_password').val() == '') {
        error = true;
        $('#upass_msg').html('Please enter your password');
        $('#upass_msg').css({ 'color': 'red' });
    } else {
        $('#upass_msg').html('');
    }

    if ($("#user_tc").prop("checked") == false) {
        error = true;
        $('#user_tc_msg').html('Please accept Terms and Condition');
        $('#user_tc_msg').css('color', 'red');
        //$('#c_check').css({'border' : '1px solid red','margin-bottom':'0px','margin-left':'15px','padding-left': '1px','border-radius':'3px'});
    } else {
        //$('#c_check').css({'border' : 'none','margin-bottom':'0px !important','margin-left':'0px !important','padding-left': '0px'});
        $('#user_tc_msg').html("");
    }

    if (error == false) {
        $('#signupBtn').attr('disabled', true);
        var frmData = $('#signup_form').serialize();
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "/validate-signup",
            type: "POST",
            data: frmData + '&act=validate_signup',
            dataType: 'json',
            success: function (response) {
                $('#signup_form').find('input:text, input:password, select, textarea').val('');
                $('#signup_form').find('input:radio, input:checkbox').prop('checked', false);

                $('#signupBtn').attr('disabled', false);
                $('#form-area-signup').html(response.otp_form);
                $('#resend_otp').show();
            }
        });
    }
}

//Check Email valid or not
function validate_email(email) {
    var error = false;
    var user = $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "/validate-email",
        type: "POST",
        data: { act: 'validate_email', email: email },
        async: false,
        global: false,
        success: function (message) {
            if (message != '') {
                $('#uemail_msg').html(message);
                $('#uemail_msg').css({ 'color': 'red', 'font-size': '15px' });
            } else {
                $('#uemail_msg').html('');
            }
            return message;
        }
    }).responseText;
    if (user != "") {
        return user;
    }
}

//Check Mobile Number valid or not
function validate_mobile(mobile) {
    var error = false;
    var user = $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "/validate-mobile",
        type: "POST",
        data: { act: "validate_mobile", mobile: mobile },
        async: false,
        global: false,
        success: function (message) {
            if (message != '') {
                $('#umobile_msg').html(message);
                $('#umobile_msg').css({ 'color': 'red', 'font-size': '15px' });
            } else {
                $('#umobile_msg').html('');
            }
            return message;
        }
    }).responseText;
    if (user != "") {
        return user;
    }
}

function resend_otp() {
    $('#resendOtpBtn').attr('disabled', true);
    $('#otpThankMsg').html('');
    $('#fotpThankMsg').html('');
    $('#otp_verify_form').find('input:text, input:password, select, textarea').val('');

    var mobile = $('#verify_mobile').val();
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "/resend-otp",
        type: "POST",
        data: { act: "resend_otp", mobile: mobile },
        dataType: 'json',
        success: function (response) {
            if (response.message == 'success') {
                $('#resendOtpBtn').attr('disabled', false);
                $("#otpThankMsg").html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: green;">OTP send successfully</h2></div>');
                $("#fotpThankMsg").html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: green;">OTP send successfully</h2></div>');
            }
            if (response.message == 'wrong') {
                $("#otpThankMsg").html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: red;">Error during send OTP</h2></div>');
                $("#fotpThankMsg").html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: red;">Error during send OTP</h2></div>');
            }
        }
    });
}

function verify_otp() {
    var error = false;
    $('#otpThankMsg').html('');
    $('#already_ac').hide();
    if ($('#user_otp').val() == '') {
        error = true;
        $('#uotp_msg').html('Please enter your OTP');
        $('#uotp_msg').css({ 'color': 'red' });
        $('#user_otp').css('border', '1px solid red');
    } else {
        $('#uotp_msg').html('');
        $('#user_otp').css('border', '');
    }

    if (error == false) {
        //$('#verifyOtpBtn').attr('disabled',true);
        var frmData = $('#otp_verify_form').serialize();
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "/verify-otp",
            type: "POST",
            data: frmData + "&act=verify_otp" + "&mode=register",
            dataType: 'json',
            success: function (response) {
                if (response.message == 'success') {
                    $('#otp_verify_form').find('input:text, input:password, select, textarea').val('');
                    $('#otp_verify_form').find('input:radio, input:checkbox').prop('checked', false);

                    $('#optFrm').slideUp();
                    $('#verifyOtpBtn').attr('disabled', true);
                    $("#otpThankMsg").html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: green;">OTP verify successfully<br>Login Successfully</h2></div>');
                    $('#resend_otp').hide();
                    setTimeout(function () {
                        //$('#login').modal({backdrop: 'static', keyboard: false})
                        //$('#signup-modal').removeClass('active');
                        $('#signup-modal').modal("hide");
                    }, 1000);
                    if (response.redirect_url != "") {
                        setTimeout(function () {
                            window.location.href = response.redirect_url;
                        }, 1000);
                    } else {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    /*setTimeout(function () {
                        //$('#login').modal({backdrop: 'static', keyboard: false})
                        //$('#signin-modal').css({'overflow':'auto'});
                        signin_popup();
                    },1000);*/
                }
                if (response.message == 'unauthorised') {
                    $('#verifyOtpBtn').attr('disabled', false);
                    $("#otpThankMsg").html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: red;">' + response.msg_text + '</h2></div>');
                }
                if (response.message == 'wrong') {
                    $('#verifyOtpBtn').attr('disabled', false);
                    $("#otpThankMsg").html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: red;">Invalid OTP</h2></div>');
                }
            }
        });
    }
}

//validate login
function validate_login() {
    var error = false;
    if ($('#usertype').val() == '0') {
        error = true;
        $('#usertype_msg').html('Please select type');
        $('#usertype_msg').css({ 'color': 'red' });
        $('#usertype').css('border', '1px solid red');
    } else {
        $('#usertype_msg').html('');
        $('#usertype').css('border', '');
    }

    if ($('#username').val() == '') {
        error = true;
        $('#username_msg').html('Please enter your mobile number');
        $('#username_msg').css({ 'color': 'red' });
        $('#username').css('border', '1px solid red');
    } else {
        $('#username_msg').html('');
        $('#username').css('border', '');
    }

    if ($('#userpassword').val() == '') {
        error = true;
        $('#userpassword_msg').html('Please enter your password');
        $('#userpassword_msg').css({ 'color': 'red' });
        $('#userpassword').css('border', '1px solid red');
    } else {
        $('#userpassword_msg').html('');
        $('#userpassword').css('border', '');
    }

    if (error == false) {
        $('#loginBtn').attr('disabled', true);
        var frmData = $('#login_form').serialize();
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "/validate-login",
            type: "POST",
            dataType: 'json',
            data: frmData + "&act=validate_login",
            xhrFields: { withCredentials: true },
            success: function (response) {
                //alert(response.redirect_url)
                if (response.message == 'success') {
                    $('#login_form').find('input:text, input:password, select, textarea').val('');
                    $('#login_form').find('input:radio, input:checkbox').prop('checked', false);

                    $('#login_form').slideUp();
                    $('#loginThankMsg').html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: green; text-align: center;">' + response.msg_text + '</h2></div>');
                    $('#create_account').hide();
                    if (response.redirect_url != "") {
                        setTimeout(function () {
                            window.location.href = response.redirect_url;
                        }, 2000);
                    } else {
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                    //$('#loginBtn').attr('disabled',false);
                    //$('#form-area-login').html(response.otp_form);
                    //$('#resend_otp').show();
                } else {
                    $('#loginBtn').attr('disabled', false);
                    $('#loginThankMsg').html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: red; text-align: center;">' + response.msg_text + '</h2></div>');
                }
            }
        });
    }
}

//for validate forgot
function validate_forgot() {
    var error = false;
    if ($('#forgot_mobile').val() == '') {
        error = true;
        $('#forgotmobile_msg').html('Please enter your mobile number');
        $('#forgotmobile_msg').css({ 'color': 'red' });
        $('#forgot_mobile').css('border', '1px solid red');
    } else {
        $('#forgotmobile_msg').html('');
        $('#forgot_mobile').css('border', '');
    }

    if (error == false) {
        $('#fotpThankMsg').html('');
        $('#forgotBtn').attr('disabled', true);
        var frmData = $('#forgot_form').serialize();
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "/validate-forgot",
            type: "POST",
            dataType: 'json',
            data: frmData + "&act=validate_forgot",
            success: function (response) {
                if (response.message == 'success') {
                    $('#forgot_form').find('input:text, input:password, select, textarea').val('');
                    $('#forgot_form').find('input:radio, input:checkbox').prop('checked', false);

                    $('#fresend_otp').show();
                    $('#forgotBtn').attr('disabled', false);
                    $('#form-area-forgot').html(response.otp_form);
                } else {
                    $('#forgotBtn').attr('disabled', false);
                    $('#fotpThankMsg').html('<div><h2 style="margin-bottom: 0 !important; font-size: 18px !important; color: red;">' + response.msg_text + '</h2></div>');
                }
            }
        });
    }
}

// for forgot otp verify
function forgot_password() {
    var error = false;
    $('#otpThankMsg').html('');
    $('#fotpThankMsg').html('');
    $('#fresend_otp').hide();
    if ($('#user_otp').val() == '') {
        error = true;
        $('#uotp_msg').html('Please enter your OTP');
        $('#uotp_msg').css({ 'color': 'red' });
        $('#user_otp').css('border', '1px solid red');
    } else {
        $('#uotp_msg').html('');
        $('#user_otp').css('border', '');
    }

    if (error == false) {
        $('#verifyOtpBtn').attr('disabled', true);
        var frmData = $('#otp_verify_form').serialize();
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "/verify-otp",
            type: "POST",
            data: frmData + "&act=verify_otp" + "&mode=forgot",
            dataType: 'json',
            success: function (response) {
                if (response.message == 'success') {
                    $('#verifyOtpBtn').attr('disabled', false);
                    $('#fresend_otp').hide();
                    setTimeout(function () {
                        //$('#login').modal({backdrop: 'static', keyboard: false})
                        $('#form-area-forgot').html(response.reset_form);
                    }, 500);
                } else if (response.message == 'wrong') {
                    $('#verifyOtpBtn').attr('disabled', false);
                    $('#fresend_otp').show();
                    $("#fotpThankMsg").html('<div><h2 style="margin-bottom: 0 !important; font-size: 20px !important; color: red;">Invalid OTP</h2></div>');
                }
            }
        });
    }
}

// for reset password
function reset_password() {
    var error = false;
    if ($('#resetpassword').val() == '') {
        error = true;
        $('#rpass_msg').html('<ul class="list-unstyled"><li>Please enter your password</li></ul>');
        $('#rpass_msg').css({ 'color': 'red', 'font-size': '15px' });
        $('#resetpassword').css('border', '1px solid red');
    } else {
        $('#rpass_msg').html('');
        $('#resetpassword').css('border', '');

    }

    if ($('#resetcpassword').val() == '') {
        error = true;
        $('#rcpass_msg').html('<ul class="list-unstyled"><li>Please enter your confirm password</li></ul>');
        $('#rcpass_msg').css({ 'color': 'red', 'font-size': '15px' });
        $('#resetcpassword').css('border', '1px solid red');
    } else if ($('#resetpassword').val() != $('#resetcpassword').val()) {
        error = true;
        $('#rcpass_msg').html('<ul class="list-unstyled"><li>password and confirm password do not match</li></ul>');
        $('#rcpass_msg').css({ 'color': 'red', 'font-size': '15px' });
        $('#resetcpassword').css('border', '1px solid red');
    } else {
        $('#rcpass_msg').html('');
        $('#resetcpassword').css('border', '');
    }

    if (error == false) {
        $('#resetPassBtn').attr('disabled', true);
        var frmData = $('#resetPass_form').serialize();
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "/reset-password",
            type: "POST",
            data: frmData + "&act=reset_password",
            dataType: 'json',
            success: function (response) {
                if (response.message == 'success') {
                    $('#resetPass_form').find('input:text, input:password, select, textarea').val('');
                    $('#resetPass_form').find('input:radio, input:checkbox').prop('checked', false);

                    $('#resetPassBtn').attr('disabled', false);
                    $('#fresend_otp').hide();
                    $('#fotpThankMsg').addClass('ps-form__content');
                    $('#resetPassFrm').slideUp();
                    $('#fotpThankMsg').html('<div><h2 style="margin-bottom: 0 !important; font-size: 20px !important; color: green;">' + response.msg_text + '</h2></div>');
                    setTimeout(function () {
                        //$('#login').modal({backdrop: 'static', keyboard: false})
                        $('#forgot-modal').modal("hide");
                        signin_popup();
                    }, 1000);
                } else if (response.message == 'wrong') {
                    $('#resetPassBtn').attr('disabled', false);
                    $('#fresend_otp').hide();
                    $("#fotpThankMsg").html('<div><h2 style="margin-bottom: 0 !important; font-size: 20px !important; color: red;">' + response.msg_text + '</h2></div>');
                }
            }
        });
    }
};

//for franchise
function validate_franchise() {
    var current_path = window.location.pathname.split('/').pop();
    //alert('Hello')
    $('#common_msg').html('');
    var emailFilter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,4}))$/;
    var email = $('#inquiry_email').val();
    var mobile = $('#inquiry_mobile').val();
    var error = false;

    if ($('#inquiry_company').val() == '') {
        error = true;
        $('#inquiry_company').css('border', '1px solid red');
    } else {
        $('#inquiry_company').css('border', '');
    }

    if ($('#inquiry_name').val() == '') {
        error = true;
        $('#inquiry_name').css('border', '1px solid red');
    } else {
        $('#inquiry_name').css('border', '');
    }

    if ($('#inquiry_email').val() == '') {
        error = true;
        $('#inquiry_email').css('border', '1px solid red');
    } else if (email != '' && !(emailFilter.test(email))) {
        error = true;
        $('#inquiry_email').css('border', '1px solid red');
    } else {
        $('#inquiry_email').css('border', '');
    }

    if ($('#inquiry_mobile').val() == '') {
        error = true;
        $('#inquiry_mobile').css('border', '1px solid red');
    } else if (mobile.length < 10) {
        error = true;
        $('#inquiry_mobile').css('border', '1px solid red');
    } else {
        $('#inquiry_mobile').css('border', '');
    }

    if ($('#inquiry_city').val() == '') {
        error = true;
        $('#inquiry_city').css('border', '1px solid red');
    } else {
        $('#inquiry_city').css('border', '');
    }

    if ($('#inquiry_state').val() == '') {
        error = true;
        $('#inquiry_state').css('border', '1px solid red');
    } else {
        $('#inquiry_state').css('border', '');
    }

    if ($('#inquiry_zipcode').val() == '') {
        error = true;
        $('#inquiry_zipcode').css('border', '1px solid red');
    } else {
        $('#inquiry_zipcode').css('border', '');
    }

    if (error == false) {
        var company = $('#inquiry_company').val();
        var name = $('#inquiry_name').val();
        var email = $('#inquiry_email').val();
        var country = $('#country').val();
        var prefix = $('#prefix').val();
        var mobile = $('#inquiry_mobile').val();
        var city = $('#inquiry_city').val();
        var state = $('#inquiry_state').val();
        var zipcode = $('#inquiry_zipcode').val();
        $('#submit').html('<img src="https://artenspace.com/public/img/loader.gif" width="40px">');
        $('#submit').prop('disabled', true);

        setTimeout(function () {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "/franchise-insert",
                type: "POST",
                data: { act: "franchise", company: company, name: name, email: email, country: country, prefix: prefix, mobile: mobile, city: city, state: state, zipcode: zipcode },
                success: function (result) {
                    if (result == 'success') {
                        $("#franchiseForm").fadeOut(500);
                        $('#submit').prop('disabled', false);
                        $("#franchiseForm").trigger("reset");
                        $(".save-changes").hide();
                        setTimeout(function () {
                            $('#thankMsg').html('<h2 style="margin-bottom: 0 !important; color: black !important">Thank You</h2><p style="color: black;">Your message has been submitted. We will contact you shortly</p>');
                            //window.location = "https://www.artenspace.com/thank-you";
                        }, 100);
                    } else if (result == 'wrong') {
                        $('#thankMsg').html('<h2 style="margin-bottom: 0 !important; margin-top: 10px !important;">Robot verification failed, please try again</h2>');
                        $('#submit').html('Submit');
                        $('#submit').prop('disabled', false);
                    }
                }
            });
        }, 100);
    } else {
        $('#common_msg').html('Your entry is not valid. Please correct it and submit again.');
        return false;
    }
}

// for forgot otp verify
function logout() {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "/validate-logout",
        type: "POST",
        dataType: 'json',
        success: function (response) {
            if (response.message == 'success') {
                location.reload();
            }
        }
    });
}
