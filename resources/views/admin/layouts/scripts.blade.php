<!-- All Jquery -->
<!-- Bootstrap popper Core JavaScript -->
<script src="{{ asset('assets/node_modules/popper/popper.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ asset('assets/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('assets/dist/js/waves.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ asset('assets/dist/js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('assets/dist/js/custom.min.js') }}"></script>

<!--morris JavaScript -->
<script src="{{ asset('assets/node_modules/raphael/raphael-min.js') }}"></script>
<script src="{{ asset('assets/node_modules/morrisjs/morris.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<!--stickey kit -->
<script src="{{ asset('assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>

<!-- Popup message jquery -->
<script src="{{ asset('assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
<!-- Chart JS -->
<script src="{{ asset('assets/dist/js/dashboard1.js') }}"></script>
<!-- Data Table -->
{{--<script src="{{ asset('assets/node_modules/datatables/jquery.dataTables.min.js') }}"></script>--}}
<!-- Datatables-->
<script src="{{ asset('assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<!-- Sweet-Alert  -->
<script src="{{ asset('assets/node_modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
<script src="{{ asset('assets/dist/js/common.js') }}"></script>

<!-- wysuhtml5 Plugin JavaScript -->
<script src="{{ asset('assets/node_modules/tinymce/tinymce.min.js') }}"></script>
<!-- jQuery file upload -->
<script src="{{ asset('assets/node_modules/dropify/dist/js/dropify.min.js') }}"></script>
<!-- Date Picker Plugin JavaScript -->
<script src="{{ asset('assets/node_modules/moment/moment.js') }}"></script>

<script src="{{ asset('assets/node_modules/jqueryui/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ asset('assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/clockpicker/dist/jquery-clockpicker.js') }}"></script>

<!-- Color Picker Plugin JavaScript -->
<script src="{{ asset('assets/node_modules/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ asset('assets/node_modules/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>

<!--Nestable js -->
<script src="{{ asset('assets/node_modules/nestable/jquery.nestable.js') }}"></script>
<!--Multiple select-->

<script src="{{ asset('assets/node_modules/wizard/jquery.steps.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/wizard/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/wizard/steps.js') }}"></script>

<script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}"></script>

<script src="{{ asset('assets/dist/js/pages/jquery.PrintArea.js') }}"></script>

<!-- Footable -->
<script src="{{ asset('assets/node_modules/footable/js/footable.all.min.js') }}"></script>
<!--FooTable init-->
<script src="{{ asset('assets/dist/js/pages/footable-init.js') }}"></script>

<script src="{{ asset('assets/plupload/plupload.full.js') }}"></script>
<script src="{{ asset('assets/plupload/jquery.plupload.queue/jquery.plupload.queue.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#print").click(function() {
            var mode  = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });
    });
</script>

<script>
    jQuery(document).ready(function() {
        // For select 2
        $(".select2").select2();
    });

    @if(Session::get('failedMsg') != "")
    $.toast({
        heading: "{{ Session::get('failedMsg') }}",
        //text: 'Use the predefined ones, or specify a custom position object.',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'error',
        hideAfter: 3500,
        stack: 6
    });
    @elseif(Session::get('successMsg') != "")
    $.toast({
        heading: "{{ Session::get('successMsg') }}",
        //text: 'Use the predefined ones, or specify a custom position object.',
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: 'success',
        hideAfter: 3500,
        stack: 6
    });
    @endif
</script>