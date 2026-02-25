<!-- Owl Carousel JS -->
<script src="{{ url('plugins/owl-carousel/owl.carousel.min.js') }}"></script>
<!-- Aos -->
<script src="{{ url('plugins/aos/aos.js') }}"></script>
<!-- Top JS -->
<script src="{{ url('js/backToTop.js') }}"></script>
<script src="{{ url('plugins/select2/js/select2.full.js') }}"></script>
<!-- Custom JS -->
<script src="{{ url('js/script.js?v=') . time() }}"></script>
<script src="{{ url('js/common.js?v=') . time() }}"></script>
<script src="{{ url('js/page.js?v=') . time() }}"></script>
<script src="{{ url('build/js/intlTelInput.js') }}"></script>
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
        alert(number.name)
        $('.country').val(number.name);
        $('.prefix').val(number.dialCode);
    });
</script>