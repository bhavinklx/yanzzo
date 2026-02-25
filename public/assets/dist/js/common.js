// All action
function allAction(act) {
    if(act == 'delete_all'){
        var msg ='Delete';
        var textmsg='You will not be able to recover this detail!';
    }
    else if(act =='activate_all'){
        var msg = 'Activate';
    }
    else if(act=="inactivate_all"){
        var msg = 'Inactivate';
    }
    swal({
        title: "Are you sure?",
        text: textmsg,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, "+msg+" it!",
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm){
        if (isConfirm) {
            $("#action").val(act);
            swal(msg+"!", "Your detail has been "+msg+".", "success");
            setTimeout(function(){
                $("#listFrm").submit();
            },2000);
        } else {
            swal("Cancelled", "Your detail is safe :)", "error");
            
        }
    });
}

function deleteSingal(idname) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this detail!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm){
        if (isConfirm) {
            deleteData(idname);
            swal("Deleted!", "Your detail has been deleted.", "success");
            setTimeout(function(){
                //$("#listFrm").submit();
                location.reload();
            },2000);
        } else {
            swal("Cancelled", "Your detail is safe :)","error");
        }
    });
}

function deleteImage(idname) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this detail!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm){
        if (isConfirm) {
            remove_image(idname);
            swal("Deleted!", "Your detail has been deleted.", "success");
        } else {
            swal("Cancelled", "Your detail is safe :)","error");
        }
    });
}

// DataTable
$(document).ready(function() {

    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'csv', 'pdf'
        ]
    });

    $( document ).ready(function() {
        $('#myTable').DataTable({
            "aoColumnDefs": [
                { "bSortable": false, "aTargets": [0] },
                { "bSortable": false, "aTargets": [-1] },
                { "bSortable": false, "aTargets": [-2] }

            ],
            "order": [
                [0, 'asc']
            ],
            "displayLength": 25,
        });
    });

    $(document).ready(function() {
        var table = $('#example').DataTable({
            "columnDefs": [
                { "visible": false, "targets": 0 }
            ],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });
        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function() {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
    });
});

// Upload File
$(document).ready(function() {
    // Basic
    $('.dropify').dropify();

    // Translated
    $('.dropify-fr').dropify({
        messages: {
            default: 'Glissez-déposez un fichier ici ou cliquez',
            replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
            remove: 'Supprimer',
            error: 'Désolé, le fichier trop volumineux'
        }
    });

    // Used events
    var drEvent = $('#input-file-events').dropify();

    drEvent.on('dropify.beforeClear', function(event, element) {
        return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
    });

    drEvent.on('dropify.afterClear', function(event, element) {
        alert('File deleted');
    });

    drEvent.on('dropify.errors', function(event, element) {
        console.log('Has Errors');
    });

    var drDestroy = $('#input-file-to-destroy').dropify();
    drDestroy = drDestroy.data('dropify')
    $('#toggleDropify').on('click', function(e) {
        e.preventDefault();
        if (drDestroy.isDropified()) {
            drDestroy.destroy();
        } else {
            drDestroy.init();
        }
    })
});

// DATE_PICKER
$(document).ready(function () {
    $('.sdate').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        format: 'DD-MM-YYYY',
        minDate: new Date()
    });
    
    $('.mdate').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        format: 'DD-MM-YYYY',
        minDate: new Date()
    });
});

// DATE_PICKER
$(document).ready(function () {
    $('.edate').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        format: 'DD-MM-YYYY',
        maxDate: new Date()
    });
});

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

$(document).ready(function () {
    //called when key is pressed in textbox
    $(".isNumber").keypress(function (evt) {
        //if the letter is not digit then display error and don't type anything
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    });
});
