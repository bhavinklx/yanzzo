<!-- scrollToTop start -->
<div class="progress-wrap active-progress">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919px, 307.919px; stroke-dashoffset: 228.265px;"></path>
    </svg>
</div>
<!-- scrollToTop end -->

<!-- Bootstrap Core JS -->
<script src="{{ url('/public/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select JS -->
<script src="{{ url('/public/plugins/select2/js/select2.min.js') }}"></script>
<!-- Owl Carousel JS -->
<script src="{{ url('/public/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
<!-- Aos -->
<script src="{{ url('/public/plugins/aos/aos.js') }}"></script>
<!-- Bootstrap DateTime Picker -->
<script src="{{ url('/public/js/moment.min.js') }}"></script>
<script src="{{ url('/public/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- Fancybox JS -->
<script src="{{ url('/public/plugins/fancybox/jquery.fancybox.min.js') }}"></script>

<script src="{{ url('/public/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('/public/plugins/datatables/datatables.min.js') }}"></script>
<!-- Sticky Sidebar JS -->
<script src="{{ url('/public/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
<script src="{{ url('/public/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>
<!-- Counterup JS -->
<script src="{{ url('/public/js/jquery.waypoints.js') }}"></script>
<script src="{{ url('/public/js/jquery.counterup.min.js') }}"></script>
<!-- Top JS -->
<script src="{{ url('/public/js/backToTop.js') }}"></script>
<!-- Custom JS -->
<script src="{{ url('/public/js/script.js?v=') . time() }}"></script>
<script src="{{ url('/public/js/common.js?v=') . time() }}"></script>
<script src="{{ url('/public/js/page.js?v=') . time() }}"></script>
<script src="{{ url('/public/build/js/intlTelInput.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        $('.allowstring').on('keypress keyup blur', function(event) {
            // Get the current value of the input field
            var value = $(this).val();
            // Remove any non-letter characters (including numbers, symbols, etc.)
            var cleanedValue = value.replace(/[^a-zA-Z]/g, '');
            // Update the input field with the cleaned value (only letters)
            $(this).val(cleanedValue);
        });
    });
    
    $(document).ready(function () {
        $('.iti').css("width","100%");
    });

    var input = document.querySelector(".mobile");
    var iti = window.intlTelInput(input,{
        // allowDropdown: false,
        // autoHideDialCode: false,
        // autoPlaceholder: "off",
        // dropdownContainer: document.body,
        // excludeCountries: ["us"],
        // formatOnDisplay: false,
        geoIpLookup: function(callback) {
            $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        // hiddenInput: "full_number",
        // initialCountry: "auto",
        // localizedCountries: { 'de': 'Deutschland' },
        // nationalMode: false,
        // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
        // placeholderNumberType: "MOBILE",
        // preferredCountries: ['cn', 'jp'],
        // separateDialCode: true,
        // utilsScript: "build/js/utils.js",
    });

    var input1 = document.querySelector(".mobile1");
    window.intlTelInput(input1,{
        // allowDropdown: false,
        // autoHideDialCode: false,
        // autoPlaceholder: "off",
        // dropdownContainer: document.body,
        // excludeCountries: ["us"],
        // formatOnDisplay: false,
        geoIpLookup: function(callback) {
            $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        // hiddenInput: "full_number",
        // initialCountry: "auto",
        // localizedCountries: { 'de': 'Deutschland' },
        // nationalMode: false,
        // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
        // placeholderNumberType: "MOBILE",
        // preferredCountries: ['cn', 'jp'],
        // separateDialCode: true,
        // utilsScript: "build/js/utils.js",
    });

    $('.mobile').blur(function() {
        // Does some stuff and logs the event to the console
        var number = iti.getSelectedCountryData();
        $('.country').val(number.name);
        $('.prefix').val(number.dialCode);
    });

    $('#otp_verify_form').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault(); // Stop the form submission
        }
    });
</script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    (function() {
        const button = document.createElement('div');
        button.id = 'whatsapp-button';
        button.style.position = 'fixed';
        button.style.bottom = '20px';
        button.style['right'] = '20px';
        button.innerHTML = `
               <a href="javascript:void(0)" id="explore-btn" style="text-decoration:none;">
                    <button style="display: flex; align-items: center; gap: 8px; font-size:16px; padding:10px 20px; background-color:rgb(34, 206, 186); color:white; border:none; border-radius:30px;">
                        <svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                          <path d="m.76 21.24 1.412-5.12A10.324 10.324 0 0 1 .76 10.93C.76 5.35 5.35.76 10.964.76 16.58.76 21.24 5.35 21.24 10.93c0 5.578-4.661 10.31-10.276 10.31-1.765 0-3.46-.565-4.978-1.413L.76 21.24Z" fill="#EDEDED"></path>
                          <path d="m6.268 17.991.318.177c1.307.812 2.825 1.306 4.414 1.306 4.626 0 8.474-3.848 8.474-8.545 0-4.696-3.848-8.404-8.51-8.404-4.66 0-8.439 3.743-8.439 8.404 0 1.624.46 3.213 1.307 4.555l.212.318-.812 2.966 3.036-.777Z" fill="#25D366"></path>
                          <path d="m8.245 6.198-.671-.036a.802.802 0 0 0-.565.212c-.318.283-.848.812-.989 1.519-.247 1.059.141 2.33 1.06 3.601.918 1.271 2.683 3.32 5.79 4.202.989.283 1.766.106 2.402-.282.494-.318.812-.812.918-1.342l.105-.494a.355.355 0 0 0-.176-.389l-2.225-1.024a.337.337 0 0 0-.423.106l-.883 1.13a.275.275 0 0 1-.283.07c-.6-.211-2.613-1.059-3.707-3.177-.036-.106-.036-.212.035-.283l.848-.953c.07-.106.105-.247.07-.353L8.527 6.41a.308.308 0 0 0-.282-.212Z" fill="#FEFEFE"></path>
                        </svg> Message Us
                    </button>
               </a>
               <div id="whatsapp-previews" style="display:none; margin-top:10px;">
                    <div style="background-image: url('https://ik.imagekit.io/cloy701fl/images/anantya-whatsapp.png'); width: 360px; height: 380px; border-radius: 20px; box-shadow: 0 24px 50px 10px #0066ff12; border-radius: 20px;
                    margin: 0 auto;
                    justify-content: center;
                    background-size: cover;
                    overflow: hidden;
                    position: relative;">
                        <button aria-label="Close chat" id="close-chat" style="position: absolute; top: 10px; right: 15px; color:#fff; background-color: transparent; border: none; font-size: 28px; cursor: pointer;">—</button>
                           <div style="background-color: #0a5f54; color: #fff; display: flex; padding: 5px 20px;">
                           <div style="border-radius: 50%; height: 40px; width: 40px;"><img src="https://www.yaarioke.com/public/img/logo.png" alt="User's profile" width="40" height="40" style="border-radius: 50%;" /></div>
                           <div style="margin-left: 10px;">
                               <span>Anantya.ai</span>
                               <p style="font-size: .69375rem; line-height: .8125rem;">Typically replies within a day</p>
                             </div>
                           </div>
                           <div id="chat-preview">
                                <p style="word-wrap: break-word;
                                align-items: flex-start;
                                border-radius: 15px;
                                background: #fff;
                                margin: 20px 30px 15px 20px;
                                max-width: 80% !important;
                                font-size: 14px;
                                height: calc(100% - 100px) !important;
                                letter-spacing: .0071rem;
                                line-height: 1.3125rem;
                                padding: .8rem;
                                position: relative;
                                text-align: left;
                                width: 100%;">Welcome to Yaarioke! How can we help?</p><p style="background-color: #dcf8c6;
                                margin-left: 5rem;
                                max-width: 80% !important; word-wrap: break-word;
                                align-items: flex-start;
                                border-radius: 15px;
                                font-size: 14px;
                                height: calc(100% - 100px) !important;
                                letter-spacing: .0071rem;
                                line-height: 1.3125rem;
                                padding: .8rem;
                                position: relative;
                                text-align: left;
                                width: 100%;}">Hi, I want to know more!</p></div>
                                <div class="cta-section" style="background-color: rgb(255, 255, 255);
                                bottom: 0px;
                                position: absolute;
                                width: 100%;
                                display: flex;  /* Flexbox Enable */
                                flex-direction: column; /* Column Layout */
                                justify-content: center;  /* Horizontal Center */
                                align-items: center;  /* Vertical Center */
                                text-align: center; /* Text Center */
                                padding: 10px;}">
                               <button id="start-chat-btn" style="background-color: #22ceba;
                                color: white;
                                width: 90%;
                                padding: 10px 20px;
                                border: none;
                                border-radius: 30px;
                                font-size: 14px;
                                font-weight: 600;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 8px;">
                                <svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                                    <path d="m.76 21.24 1.412-5.12A10.324 10.324 0 0 1 .76 10.93C.76 5.35 5.35.76 10.964.76 16.58.76 21.24 5.35 21.24 10.93c0 5.578-4.661 10.31-10.276 10.31-1.765 0-3.46-.565-4.978-1.413L.76 21.24Z" fill="#EDEDED"></path>
                                    <path d="m6.268 17.991.318.177c1.307.812 2.825 1.306 4.414 1.306 4.626 0 8.474-3.848 8.474-8.545 0-4.696-3.848-8.404-8.51-8.404-4.66 0-8.439 3.743-8.439 8.404 0 1.624.46 3.213 1.307 4.555l.212.318-.812 2.966 3.036-.777Z" fill="#25D366"></path>
                                    <path d="m8.245 6.198-.671-.036a.802.802 0 0 0-.565.212c-.318.283-.848.812-.989 1.519-.247 1.059.141 2.33 1.06 3.601.918 1.271 2.683 3.32 5.79 4.202.989.283 1.766.106 2.402-.282.494-.318.812-.812.918-1.342l.105-.494a.355.355 0 0 0-.176-.389l-2.225-1.024a.337.337 0 0 0-.423.106l-.883 1.13a.275.275 0 0 1-.283.07c-.6-.211-2.613-1.059-3.707-3.177-.036-.106-.036-.212.035-.283l.848-.953c.07-.106.105-.247.07-.353L8.527 6.41a.308.308 0 0 0-.282-.212Z" fill="#FEFEFE"></path>
                                </svg>
                                Start Chat
                        </button>
                        <p class="powered-by mt-2"><svg width="10" height="14" viewBox="0 0 10 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.84653 0.0360377C6.13974 0.138097 6.33912 0.438354 6.33912 0.777828V4.66669L9.15651 4.66669C9.41915 4.66669 9.65997 4.82804 9.78125 5.08527C9.90254 5.34251 9.88415 5.65289 9.73354 5.89048L4.80311 13.6682C4.62681 13.9463 4.30753 14.066 4.01433 13.964C3.72113 13.8619 3.52174 13.5616 3.52174 13.2222L3.52174 9.33331H0.704349C0.441715 9.33331 0.200895 9.17196 0.0796083 8.91473C-0.0416787 8.65749 -0.0232851 8.34711 0.127325 8.10952L5.05775 0.331805C5.23405 0.0536972 5.55333 -0.0660216 5.84653 0.0360377Z" fill="#FFA800"></path></svg> &nbsp; Powered by <a href="https://anantya.ai/" target="_blank">Anantya.ai</a></p>
                    </div>
               </div>
             `;

        document.body.appendChild(button);

        document.getElementById("explore-btn").addEventListener("click", function() {
            document.getElementById("whatsapp-previews").style.display = "block";
        });

        document.getElementById("close-chat").addEventListener("click", function () {
            document.getElementById("whatsapp-previews").style.display = "none";
        });

        document.getElementById("start-chat-btn").addEventListener("click", function() {
            window.open('https://wa.me/+918401055757?text=Hi%2C%20I%20want%20to%20know%20more!', '_blank');
        });
    })();
</script>