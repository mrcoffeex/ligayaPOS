
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="../../bower_components/chart.js/chart.min.js"></script>
    <script src="../../dist/js/sb-admin-2.js"></script>

    <script>

    function btnLoader(formObj){

        formObj.disabled = true;
        formObj.innerHTML = "processing ...";
        return true;  

    }

    function check_password(input1, input2, submitBtn, alertPane){

        var pass1 = document.getElementById(input1).value;
        var pass2 = document.getElementById(input2).value;

        if (pass1 == "" || pass2 == "") {
            document.getElementById(submitBtn).disabled = true;
        }else if (pass1 == "" && pass2 == "") {
            document.getElementById(submitBtn).disabled = true;
        }else if (pass1 != pass2) {
            document.getElementById(submitBtn).disabled = true;
            document.getElementById(alertPane).innerHTML = "Password Mismatch";
        }else if (pass1 == pass2) {
            document.getElementById(submitBtn).disabled = false;
            document.getElementById(alertPane).innerHTML = "";
        }else{
            document.getElementById(submitBtn).disabled = false;
            document.getElementById(alertPane).innerHTML = "";
        }
    }

    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });

    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
    .popover()

    //modal autofocus
    $(document).on('shown.bs.modal', function() {
        $(this).find('[autofocus]').focus();
        $(this).find('[autofocus]').select();
    });

    //disable f12
    $(document).keydown(function (event) {
        if (event.keyCode == 123) { // Prevent F12
            return false;
        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
            return false;
        }
    });

    // disable inspect element
    $(document).on("contextmenu", function (e) {        
        e.preventDefault();
    });

    //disable back function
    function preventBack() { window.history.forward(); }
    setTimeout("preventBack()", 0);
    window.onunload = function () { null };

    var alphanumericField = document.getElementById('alphanumericField');

    if (alphanumericField !== null) {
        alphanumericField.addEventListener('input', function() {
            var fieldValue = alphanumericField.value;
            var alphanumericValue = fieldValue.replace(/[^a-zA-Z0-9-]/g, '');
            alphanumericField.value = alphanumericValue;
        });
    } else {
        //nothing
    }
    
    </script>