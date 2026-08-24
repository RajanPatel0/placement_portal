</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>
        <span style="color:#6c757d;">&copy; Copyright</span>
        <a href="https://ptu.ac.in/" style="color:#0d6efd; text-decoration:none;">IKGPTU T&amp;P Cell</a>
        <span style="color:#495057;">Developed By</span>
        <a href="https://birendrapandit.online" style="color:#198754; text-decoration:none;">Birendra Pandit</a>
    </strong>


    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show success message in modal
        @if (session('success'))
            document.getElementById('alertMessageContent').innerHTML = '<div class="alert alert-success">' +
                '{{ session('success') }}' + '</div>';
            var myModal = new bootstrap.Modal(document.getElementById('alertModal'));
            myModal.show();
        @endif

        // Show error message in modal
        @if (session('error'))
            document.getElementById('alertMessageContent').innerHTML = '<div class="alert alert-danger">' +
                '{{ session('error') }}' + '</div>';
            var myModal = new bootstrap.Modal(document.getElementById('alertModal'));
            myModal.show();
        @endif

        // Show validation errors in modal
        @if ($errors->any())
            var errorList = '<ul>';
            @foreach ($errors->all() as $error)
                errorList += '<li>{{ $error }}</li>';
            @endforeach
            errorList += '</ul>';
            document.getElementById('alertMessageContent').innerHTML = '<div class="alert alert-danger">' +
                errorList + '</div>';
            var myModal = new bootstrap.Modal(document.getElementById('alertModal'));
            myModal.show();
        @endif
    });
</script>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="/public/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/public/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="/public/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="/public/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="/public/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="/public/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="/public/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/public/plugins/moment/moment.min.js"></script>
<script src="/public/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="/public/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="/public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/public/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/public/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/public/dist/js/pages/dashboard.js"></script>
</body>

</html>
