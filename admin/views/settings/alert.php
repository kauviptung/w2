<div class="col-md-8">

  <div class="panel panel-default">

    <div class="panel-body">
        
      <form action="" method="post" enctype="multipart/form-data">

<label class="control-label">  Thông báo người dùng</label>
<hr>
<div class="row">
          <div class="col-md-6 form-group">
            <label class="control-label">Welcome <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">Được gửi đến người dùng mới khi tài khoản của họ được tạo.</span></div>  </label>
            <select class="form-control" name="welcomemail">
              <option value="2" <?= $settings["alert_welcomemail"] == 2 ? "selected" : null; ?> >Mở</option>
              <option value="1" <?= $settings["alert_welcomemail"] == 1 ? "selected" : null; ?>>Tắt</option>
            </select>
          </div>

          <div class="col-md-6 form-group">
            <label class="control-label">Khóa API đã thay đổi <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">Được gửi đến người dùng khi khóa API của họ bị thay đổi</span></div> </label>
            <select class="form-control" name="apimail">
              <option value="2" <?= $settings["alert_apimail"] == 2 ? "selected" : null; ?> >Mở</option>
              <option value="1" <?= $settings["alert_apimail"] == 1 ? "selected" : null; ?>>Tắt</option>
            </select>
          </div>

          <div class="col-md-6 form-group">
            <label class="control-label">Tin nhắn mới <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">Được gửi đến người dùng khi họ nhận được tin nhắn mới.</span></div> </label>
            <select class="form-control" name="newmessage">
              <option value="2" <?= $settings["alert_newmessage"] == 2 ? "selected" : null; ?> >Mở</option>
              <option value="1" <?= $settings["alert_newmessage"] == 1 ? "selected" : null; ?>>Tắt</option>
            </select>
          </div>
</div>
<hr>
<label class="control-label">  Thông báo của quản trị viên</label> 
   <hr>
     <div class="row">
          <div class="col-md-6 form-group">
            <label class="control-label">Thông báo số dư API</label>
            <select class="form-control" name="alert_apibalance">
              <option value="2" <?= $settings["alert_apibalance"] == 2 ? "selected" : null; ?> >Mở</option>
              <option value="1" <?= $settings["alert_apibalance"] == 1 ? "selected" : null; ?>>Tắt</option>
            </select>
          </div>

          <div class="col-md-6 form-group">
            <label class="control-label">Thông báo về phiếu hỗ trợ mới</label>
            <select class="form-control" name="alert_newticket">
              <option value="2" <?= $settings["alert_newticket"] == 2 ? "selected" : null; ?> >Mở</option>
              <option value="1" <?= $settings["alert_newticket"] == 1 ? "selected" : null; ?>>Tắt</option>
            </select>
          </div>

          <div class="col-md-6 form-group">
            <label class="control-label">Đơn hàng thủ công mới <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">Gửi định kỳ cho nhân viên nếu nhận được lệnh thủ công mới.</span></div>  </label>
            <select class="form-control" name="alert_newmanuelservice">
              <option value="2" <?= $settings["alert_newmanuelservice"] == 2 ? "selected" : null; ?> >Mở</option>
              <option value="1" <?= $settings["alert_newmanuelservice"] == 1 ? "selected" : null; ?>>Tắt</option>
            </select>
          </div>
          <div class="col-md-6 form-group">
            <label class="control-label">Đơn hàng thất bại <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">Gửi định kỳ cho nhân viên nếu một số đơn hàng có trạng thái Không đạt.</span></div> </label>
            <select class="form-control" name="orderfail">
              <option value="2" <?= $settings["alert_orderfail"] == 2 ? "selected" : null; ?> >Mở</option>
              <option value="1" <?= $settings["alert_orderfail"] == 1 ? "selected" : null; ?>>Tắt</option>
            </select>
          </div>
          <div class="col-md-12 form-group">
            <label class="control-label">Nhà cung cấp dịch vụ đã thay đổi thông tin</label>
            <select class="form-control" name="serviceapialert">
              <option value="2" <?= $settings["alert_serviceapialert"] == 2 ? "selected" : null; ?> >Mở</option>
              <option value="1" <?= $settings["alert_serviceapialert"] == 1 ? "selected" : null; ?>>Tắt</option>
            </select>
          </div>
		 <div class="col-md-12 form-group">
            <label class="control-label">SMTP Email</label>
            <input type="text" class="form-control" name="admin_mail" value="<?=$settings["admin_mail"]?>">
			 
          </div>

      
        </div>
      <div class="row">
          <div class="form-group col-md-6">
            <label class="control-label">Email</label>
            <input type="text" class="form-control" name="smtp_user" value="<?=$settings["smtp_user"]?>">
          </div>
          <div class="form-group col-md-6">
            <label class="control-label">Mật khẩu Email</label>
            <input type="text" class="form-control" name="smtp_pass" value="<?=$settings["smtp_pass"]?>">
          </div>
          <div class="form-group col-md-6">
            <label class="control-label">SMTP Server</label>
            <input type="text" class="form-control" name="smtp_server" value="<?=$settings["smtp_server"]?>">
          </div>
          <div class="form-group col-md-3">
            <label class="control-label">SMTP Port</label>
            <input type="text" class="form-control" name="smtp_port" value="<?=$settings["smtp_port"]?>">
          </div>
          <div class="col-md-3 form-group">
            <label class="control-label">SMTP Protocol</label>
            <select class="form-control" name="smtp_protocol">
              <option value="0" <?= $settings["smtp_protocol"] == 0 ? "selected" : null; ?> >None</option>
              <option value="tls" <?= $settings["smtp_protocol"] == "tls" ? "selected" : null; ?>>TLS</option>
              <option value="ssl" <?= $settings["smtp_protocol"] == "ssl" ? "selected" : null; ?>>SSL</option>
            </select>
          </div>
        </div>
 <button type="submit" class="btn btn-primary">Cập nhật Cài đặt</button>
      
    
            </table>
        </div>
    </div>

</form>
</div>
  </div>
</div>