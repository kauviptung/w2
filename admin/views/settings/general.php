  <div class="col-md-8">
  <div class="panel panel-default">
    <div class="panel-body">
    
      <form action="" method="post" enctype="multipart/form-data">
 
<div class="alert" style="background-color:rgb(251, 195, 38);">
        <div class="form-group">
        
          <div class="row">
            <div class="col-md-10">
              <label for="preferenceLogo" class="control-label">Logo trang web</label>
              <input type="file" name="logo" id="preferenceLogo">
            </div>
            <div class="col-md-2">
              <?php if( $settings["site_logo"] ):  ?>
                <div class="setting-block__image">
                      <img class="img-thumbnail" src="<?=$settings["site_logo"]?>">
                    <div class="setting-block__image-remove">
                      <a href="" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/settings/general/delete-logo")?>"><span class="fa fa-remove"></span></a>
                    </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-11">
              <label for="preferenceFavicon" class="control-label">Biểu tượng trang web</label>
              <input type="file" name="favicon" id="preferenceFavicon">
            </div>
            <div class="col-md-1">
              <?php if( $settings["favicon"] ):  ?>
                <div class="setting-block__image">
                    <img class="img-thumbnail" src="<?=$settings["favicon"]?>">
                    <div class="setting-block__image-remove">
                      <a href="" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/settings/general/delete-favicon")?>"><span class="fa fa-remove"></span></a>
                    </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
          <hr>
      
        <div class="form-group">
          <label class="control-label">Chế độ bảo trì  
            </label>
          <select class="form-control" name="site_maintenance">
            <option value="2" <?= $settings["site_maintenance"] == 2 ? "selected" : null; ?> >Tắt</option>
            <option value="1" <?= $settings["site_maintenance"] == 1 ? "selected" : null; ?>>Mở</option>
          </select></div>
          <hr>
          
        <div class="form-group">
          <label class="control-label">Tên bảng điều khiển</label>
          <input type="text" class="form-control" name="name" value="<?=$settings["site_name"]?>">
</div></div>

<?php/*
<div class="form-group">
<div style="background-color:rgba(227,18,76);color:#fff;" class="alert">
<div class="form-group">
<div class="input-group">
<label for="" class="control-label">TỶ GIÁ VND CHO 1 USD</label>
<input type="text" name="dolar" id="inr_rate" class="form-control" value="">
<span class="input-group-btn">
<button style="margin-bottom:-25px;" class="btn btn-default" id="update_inr_rate" type="button"><i class="fa fa-refresh" aria-hidden="true"></i></button>
   </span>
   </div>
<div style="background-color:rgba(255,255,255,0.2);margin-top:4px;" class="alert help block">
<small><i>Tỷ giá hối đoái VND được cập nhật tự động. Bạn cũng có thể cập nhật / chỉnh sửa thủ công.</i></small>
</div></div>


<div class="form-group">
<label class="control-label">Tỷ lệ làm tròn   <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">Khi Đồng bộ hóa và nhập</span></div> 
            </label>
          <select class="form-control" name="currency_format">
            <option value="0" <?= $general["currency_format"] == 0 ? "selected" : null; ?> >Một (1)</option>
            <option value="2" <?= $general["currency_format"] == 2 ? "selected" : null; ?>>Hàng trăm (1.12)</option>
<option value="3" <?= $general["currency_format"] == 3 ? "selected" : null; ?> >Hàng ngàn (1.111)</option>
            <option value="4" <?= $general["currency_format"] == 4 ? "selected" : null; ?>>Mười ngàn (1.1111)</option>

          </select> 
          </div></div>*/?>
</hr>

<div class="alert" style="background-color:rgb(113, 34, 250);color:#fff;">
<?php /*
<label class="control-label">Hiệu ứng tuyết rơi</label>
<select class="form-control" name="snow_effect">
            <option value="2" <?= $settings["snow_effect"] == 2 ? "selected" : null; ?> >Tắt</option>
            <option value="1" <?= $settings["snow_effect"] == 1 ? "selected" : null; ?>>Mở</option>
</select>
        
<div class="form-group">
          <label for="" class="control-label">Màu tuyết</label>
          <input type="text" class="form-control" name="snow_colour" value="<?=$settings["snow_colour"]?>">
        </div>
*/?>
  
		<div class="form-group">
          <label for="" class="control-label">Thành viên đồng</label>
          <input type="text" class="form-control" name="bronz_statu" value="<?=$settings["bronz_statu"]?>">
        </div>
		
		<div class="form-group">
          <label for="" class="control-label">Thành viên bạc</label>
          <input type="text" class="form-control" name="silver_statu" value="<?=$settings["silver_statu"]?>">
        </div>
		
		<div class="form-group">
          <label for="" class="control-label">Thành viên vàng</label>
          <input type="text" class="form-control" name="gold_statu" value="<?=$settings["gold_statu"]?>">
        </div>
		
<div class="form-group">
          <label for="" class="control-label">Người bán lại</label>
          <input type="text" class="form-control" name="bayi_statu" value="<?=$settings["bayi_statu"]?>">
        </div>
		 <p style="background-color:rgb(255,255,255,0.2)" class="alert help block">
<small><i>Chỉ cần nhập số để xác định số tiền mà thành viên phải chi cho thứ hạng. Ví dụ: 350</i></small>
</p>
</div>
	<hr>
<div style="background-color:rgba(6,122,221);color:#fff;" class="alert">
        <div class="row">	
          <div class="form-group col-md-4">
            <?php 
            if($settings["resetpass_page"] == "2"){
                $respass_active = "selected";
            }else{
                $respass_passive = "selected";
            } ?>  
            <label class="control-label">Đặt lại mật khẩu</label>
            <select class="form-control" name="resetpass">
              <option value="2" <?= $respass_active ?> >Mở</option>
              <option value="1" <?= $respass_passive ?>>Tắt</option>
            </select>
          </div>

          <div class="form-group col-md-4">
            <?php 
            if($settings["resetpass_sms"] == "2"){
                $ressms_active = "selected";
            }else{
                $ressms_passive = "selected";
            } ?>  
            <label class="control-label">Đặt lại bằng SMS</label>
            <select class="form-control" name="resetsms">
              <option value="2" <?= $ressms_active ?> >Mở</option>
              <option value="1" <?= $ressms_passive ?>>Tắt</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <?php 
            if($settings["resetpass_email"] == "2"){
                $resemail_active = "selected";
            }else{
                $resemail_passive = "selected";
            } ?>
            <label class="control-label">Đặt lại bằng Email</label>
            <select class="form-control" name="resetmail">
              <option value="2" <?= $resemail_active ?> >Mở</option>
              <option value="1" <?= $resemail_passive ?>>Tắt</option>
            </select>
          </div>
        </div></div>
        <hr>
<div class="alert" style="background-color:rgba(252, 98, 56);">
        <div class="form-group">
            <?php 
            if($settings["ticket_system"] == "1"){
                $ticket_active = "selected";
            }else{
                $ticket_passive = "selected";
            } ?>
          <label class="control-label">Hệ thống vé</label>
          <select class="form-control" name="ticket_system">
            <option value="1" <?= $ticket_active ?> >Mở</option>
            <option value="2" <?= $ticket_passive ?>>Tắt</option>
          </select>
        </div>
<div class="form-group">
          <label class="control-label">Số lượng vé đang chờ xử lý tối đa cho mỗi người dùng</label>
          <select class="form-control" name="tickets_per_user">
            <option value="1" <?= $settings["tickets_per_user"] == 1 ? "selected" : null; ?> >1</option>
            <option value="2" <?= $settings["tickets_per_user"] == 2 ? "selected" : null; ?>>2</option>
<option value="3" <?= $settings["tickets_per_user"] == 3 ? "selected" : null; ?>>3</option>
<option value="4" <?= $settings["tickets_per_user"] == 4 ? "selected" : null; ?> >4</option>
            <option value="5" <?= $settings["tickets_per_user"] == 5 ? "selected" : null; ?>>5</option>
<option value="6" <?= $settings["tickets_per_user"] == 6 ? "selected" : null; ?>>6</option>
<option value="7" <?= $settings["tickets_per_user"] == 7 ? "selected" : null; ?> >7</option>
            <option value="8" <?= $settings["tickets_per_user"] == 8 ? "selected" : null; ?>>8</option>
<option value="9" <?= $settings["tickets_per_user"] == 9 ? "selected" : null; ?>>9</option>
<option value="10" <?= $settings["tickets_per_user"] == 10 ? "selected" : null; ?> >10</option>
            <option value="9999999999" <?= $settings["tickets_per_user"] == 9999999999 ? "selected" : null; ?>>Không giới hạn</option>

          </select>
        </div></div>

<hr>
<div class="alert" style="background-color:rgb(255, 150, 197);">
        <div class="form-group">
            <?php 
            if($settings["register_page"] == "2"){
                $reg_active = "selected";
            }else{
                $reg_passive = "selected";
            } ?>
<div class="form-group field-editgeneralform-skype_field required" >
          <label class="control-label" for="editgeneralform-registration_page">Trang đăng ký <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">Cho phép người dùng đăng ký</span></div></label>

          <select class="form-control"  name="registration_page">
            <option value="2" <?= $reg_active ?> >Mở</option>
            <option value="1" <?= $reg_passive ?>>Tắt</option>
          </select>
        </div></div>
<div class="form-group field-editgeneralform-skype_field required">
<label class="control-label" for="editgeneralform-skype_field">Tên trường <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">Trường tên trên trang Đăng ký</span></div></label>
          <select class="form-control" name="name_fileds">
            <option value="1" <?= $settings["name_fileds"] == 1 ? "selected" : null; ?> >Mở</option>
            <option value="2" <?= $settings["name_fileds"] == 2 ? "selected" : null; ?>>Tắt</option>
          </select>
          </div>
<div class="form-group field-editgeneralform-skype_field required">
<label class="control-label" for="editgeneralform-skype_field">Các trường Skype <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">Trường Skype trên trang Đăng ký</span></div></label>
          <select class="form-control" name="skype_feilds">
            <option value="1" <?= $settings["skype_feilds"] == 1 ? "selected" : null; ?> >Mở</option>
            <option value="2" <?= $settings["skype_feilds"] == 2 ? "selected" : null; ?>>Tắt</option>
          </select>
          </div>
<div class="form-group field-editgeneralform-skype_field required">
<label class="control-label" for="editgeneralform-skype_field">Xác nhận Email <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">(Cho phép xác nhận email bắt buộc cho người dùng sau khi đăng ký)</span></div> </label>
          <select class="form-control" name="email_confirmation">
            <option value="1" <?= $settings["email_confirmation"] == 1 ? "selected" : null; ?> >Mở</option>
            <option value="2" <?= $settings["email_confirmation"] == 2 ? "selected" : null; ?>>Tắt</option>
          </select>
          </div>
            <div class="form-group ">
                <label class="control-label">Tỷ lệ chuyển tiền <span class="fa fa-percent" data-toggle="tooltip" data-placement="top"></span></label>
                <input type="number" value="<?= $settings["fundstransfer_fees"]; ?>" class="form-control" name="fundstransfer_fees">
            </div> 
<div class="form-group">
          <label for="" class="control-label">Gửi lại liên kết tối đa<h6>(Khuyến khích 2)</h6></label>
          <input type="text" class="form-control" name="resend_max" value="<?=$settings["resend_max"]?>">
        </div>
        <div class="form-group">
            <?php 
            if($settings["service_list"] == "2"){
                $servlist_active = "selected";
            }else {
                $servlist_passive = "selected";
            } ?>
          <label class="control-label">Danh sách dịch vụ</label>
          <select class="form-control" name="service_list">
            <option value="2" <?= $servlist_active ?> >Hoạt động cho mọi người</option>
            <option value="1" <?= $servlist_passive ?>>Chỉ hoạt động cho người dùng</option>
          </select>
        </div>
                <div class="form-group">

            <?php 

            if($settings["services_average_time"] == "1"){
                $avg_time_active = "selected";
            }else {
                $avg_time_passive = "selected";
            } ?>
          <label class="control-label">Thời gian trung bình</label>
          <select class="form-control" name="services_average_time">
            <option value="1" <?= $avg_time_active ?> >Mở</option>
            <option value="0" <?= $avg_time_passive ?>>Tắt</option>
          </select>
        </div>
</div>
                  <hr>
<div class="alert" style="background-color:rgb(22,221,53);color:#fff;">
        <div class="form-group">
          <label class="control-label">Mã tiêu đề</label>
          <textarea class="form-control" rows="7" name="custom_header" placeholder='<style type="text/css">...</style>'><?=$settings["custom_header"]?></textarea>
        </div>
        <div class="form-group">
          <label>Mã chân trang</label>
          <textarea class="form-control" rows="7" name="custom_footer" placeholder='<script>...</script>'><?=$settings["custom_footer"]?></textarea>
        </div></div>
		<hr>
                    
        <button type="submit" class="btn btn-primary">Cập nhật Cài đặt</button>
      </form>
    </div>
  </div>
</div>
<center><img width="250" src="https://i.imgur.com/Ox9FWDd.png"></center>
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
 <div class="modal-dialog modal-dialog-center" role="document">
   <div class="modal-content">
     <div class="modal-body text-center">
       <h4>Bạn có chắc chắn không?</h4>
       <div align="center">
         <a class="btn btn-primary" href="" id="confirmYes">Có</a>
         <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
       </div>
     </div>
   </div>
 </div>
