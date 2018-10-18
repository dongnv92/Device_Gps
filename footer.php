</div>
    </div>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<footer class="footer footer-static footer-light navbar-border navbar-shadow">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2018 <a class="text-bold-800 grey darken-2" href="http://citypost.com.vn" target="_blank">CITYPOST.COM.VN </a> Công Ty Cổ Phần Bưu Chính Thành Phố - Tầng 6, Tháp B, Tòa Nhà Sông Đà, đường Phạm Hùng, Nam Từ Niêm, Hà Nội</span>
    </p>
</footer>
<!-- BEGIN PAGE VENDOR JS-->
<script src="app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<?php
foreach ($js_plus AS $js){
    echo '<script src="'. $js .'" type="text/javascript"></script>'."\n";
}
?>
<!-- BEGIN MODERN JS-->
<script src="app-assets/js/core/app-menu.js" type="text/javascript"></script>
<script src="app-assets/js/core/app.js" type="text/javascript"></script>
<!-- PLUS -->
<script>tinymce.init({ selector:'textarea' });</script>
<!-- PLUS -->
<!--<script src="http://code.jquery.com/jquery-latest.min.js"></script>-->
<script>
    $(document).ready(function () {
        $('.pickadate').pickadate({
            format: 'yyyy/mm/dd',
            hiddenPrefix: 'prefix__',
            hiddenSuffix: '__suffix'
        });
    })
</script>
</body>
</html>