<div class="col-md-8">
  <div class="panel panel-default">
    <div class="panel-body">
<h1>Đơn hàng giả</h1><a href="<?php echo site_url("admin/settings/site_count/service_enable_disable");?>" style="float:right;margin-top:-45px;" class="btn btn-primary btn-sm"><?php if($settings["fake_order_service_enabled"] == 0){ echo "Enable Service";} else { echo  "Disable Service";}?></a>
<center>
<p><i>Sử dụng cài đặt này để tăng tốc độ sắp xếp bảng điều khiển.</i></p></center>
<form class="form  <?php if($settings["fake_order_service_enabled"] == 0){ echo "disabledDiv"; }?>" action="" method="post">
<div class="form-group">
<label class="">Số lượng đơn hàng giả tối thiểu</label>
<input class="form-control" type="number" name="min_count" value="<?php if(is_numeric($settings["fake_order_min"])){
echo $settings["fake_order_min"];}?>">
</div>
<div class="form-group">
<label class="">Số lượng đơn hàng giả tối đa</label>
<input class="form-control" type="number" name="max_count" value="<?php if(is_numeric($settings["fake_order_max"])){
echo $settings["fake_order_max"];}?>">
</div>
<div class="alert alert-info">Để trống để chọn ngẫu nhiên.</div>
<div class="form-group">
<button class="btn btn-primary" type="submit">Cập nhật cài đặt</button>
</div>
</form>
<div class="alert alert-info">
Lưu ý: Khi được bật, đơn hàng sẽ tăng lên sau mỗi 5 phút.</div>
<hr><hr>
<div class="form-group">
<label class="">ID ĐƠN HÀNG TIẾP THEO</label>
<input class="form-control" type="number" id="next_order_id_value" value="<?=$settings["panel_orders"] + 1?>">
<small class="text-muted">
Phải lớn hơn <?=$settings["panel_orders"]?>.
</small>
</div>
<div class="form-group">
<button type="button" id="next_order_id_value_btn" class="btn btn-primary">Đặt hàng</button>
</div>
<div class="alert alert-info">

Lưu ý: Thiết lập ở trên sẽ tạo một lệnh giả với ID lệnh đã nhập. ID lệnh tiếp theo sẽ bắt đầu từ ID lệnh đã nhập đó.<br>Ví dụ, ORDER ID: 2000<br>ID ĐƠN HÀNG TIẾP THEO: 2001</div>


<hr><hr>

<label class="">Tổng số đơn hàng Mẫu</label>

<p style="font-weight:bold;"><span>Tổng số đơn hàng Tiền tố</span>
<span style="float:right;" class="">Tổng số đơn hàng Hậu tố</span></p>
<div class="form-group">
<div class="input-group">
<?php 
$sff = json_decode($settings["panel_orders_pattern"],true);
$prefix = $sff["panel_orders_prefix"];
$suffix = $sff["panel_orders_suffix"];

?>
<input type="number" class="form-control" id="total_orders_prefix" value="<?=$prefix?>" placeholder="10">
<span class="input-group-addon"><?=$settings["panel_orders"]?></span>

<input type="number" class="form-control" id="total_orders_suffix" value="<?=$suffix?>" placeholder="10">
</div></div>
<div class="form-group">
<button type="button" id="set_total_orders_pattern" class="btn btn-primary">Đặt hàng</button>
</div>


<div class="alert alert-info">
Lưu ý: Mã đơn hàng sẽ không bị ảnh hưởng.</div>



</div>


</div>

</div></div>