<?php
require_once 'includes/core.php';

if(!$user){
    header('location:'._LOGIN);
    exit();
}
$admin_title = 'Hướng Dẫn Xem Định Vị';
require_once 'header.php';
?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Hướng dẫn sử dụng định vị</h4> </div>
            <div class="card-body">
                Website có 2 chức chức năng chính là xem định vị các xe hiện đang chạy và xem lại hành trình theo xe đã chạy theo khoảng thời gian lựa chọn.<br />
                <ul>
                    <li>
                        Xem định vị mặc định ở trang chủ, hoặc click vào <a href="<?php echo _HOME?>"><strong>Xem định vị</strong></a> ở thanh công cụ<br>
                        Sau khi vào trang, trên đầu trang sẽ hiển thị ra 1 mục select box, mục này sẽ hiển thị các xe đang hoạt động, bạn hãy chọn xe muốn xem định vị theo biển số xe và bấm vào xem định bị để xem bị trí của xe vừa chọn
                    </li>
                    <li>
                        Xem lại hành trình là là chức năng xem lại quãng đường đã đi của 1 xe trong 1 khoảng thời gian tùy chọn. Trên thanh công cụ click vào <a href="history.php"><strong>Xem Hành Trình</strong></a> để xem lại hành trình.<br />
                        Sau khi vào trang, trên đầu trang sẽ hiển thị ra 3 mục tùy chọn sau:<br />
                        <ol>
                            <li>Chọn xe để xem : Chọn hoặc tìm kiếm xe để xem theo biển số</li>
                            <li>Thời gian bắt đầu: Thời gian bắt đầu hành trình</li>
                            <li>Thời gian kết thúc: Thời gian kết thúc hành trình</li>
                        </ol>
                        Cuối cùng bấm <strong>Tìm kiếm</strong> để xem lại hành trình trên xe vừa chọn.<br />
                        Sau khi bấm, bản đồ sẽ hiển thị ra quãng đường mà định vị ghi lại được trong khoảng thời gian bạn chọn
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'footer.php';