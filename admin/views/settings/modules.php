<style>
    .buy-button1 {
            background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    }
</style>
<div class="col-md-8">
  <div class="panel panel-default">
    <div class="panel-body">
      <form action="" method="post" enctype="multipart/form-data">
  
<div class="form-group">
          <label for="" class="control-label">Hệ thống liên kết</label>
          <select class="form-control" name="affiliates_status">
         
          <option value="1"  <?= $settings["referral_status"] == 1 ? "selected" : null; ?>>Tắt</option>
          <option value="2" <?= $settings["referral_status"] == 2 ? "selected" : null; ?>>Mở</option>
          
          </select>
        </div>
  <div class="form-group">
          <label for="" class="control-label">Tỷ lệ hoa hồng, %</label>
          <input type="number" class="form-control" name="commision" value="<?=$settings["referral_commision"]?>">
        </div>
        <div class="form-group">  
          <label for="" class="control-label">Thanh toán tối thiểu</label>
          <input type="number" class="form-control" name="minimum" value="<?=$settings["referral_payout"]?>">
        </div>
        
<hr>
<div class="childpanels-settings">
<div class="form-group">
          <label for="" class="control-label">SMM Panel Đại lý</label>
          <select class="form-control" name="selling">
         
<option value="1"  <?= $settings[""] == 1 ? "selected" : null; ?>>Tắt</option>
          <option value="2" <?= $settings["childpanel_selling"] == 2 ? "selected" : null; ?>>Mở</option>
          
          </select>
        </div>
        

<div class="form-group">
<label for="" class="control-label">Giá SMM Panel</label>
<input type="text" class="form-control" name="price" value="<?=$settings["childpanel_price"]?>">
</div> 
<div style="padding:4px; background-color:lightgrey;border:1px solid #000; border-radius:4px;width:max-content;">
<small>Giá SMM Panel : $ 20</small></div>

</div>

<hr>


<div class="form-group">
          <label for="" class="control-label">Số dư miễn phí</label>
          <select class="form-control" name="freebalance">
         
                    <option value="1"  <?= $settings["freebalance"] == 1 ? "selected" : null; ?>>Tắt</option>
          <option value="2" <?= $settings["freebalance"] == 2 ? "selected" : null; ?>>Mở</option>
          
          </select>
        </div>
<div class="form-group">
          <label for="" class="control-label">Số tiền miễn phí</label>
          <input type="text" class="form-control" name="freeamount" value="<?=$settings["freeamount"]?>">
        </div> 
<hr>
<div class="form-group">
          <label for="" class="control-label">Quảng cáo video</label>
          <select class="form-control" name="promotion">
         
                    <option value="1"  <?= $settings["promotion"] == 1 ? "selected" : null; ?>>Tắt</option>
          <option value="2" <?= $settings["promotion"] == 2 ? "selected" : null; ?>>Mở</option>
          
          </select>
        </div>

<div class="form-group">
          <label for="" class="control-label">Nhật ký cập nhật</label>
          <select class="form-control" name="updates_show">
         
                    <option value="1"  <?= $general["updates_show"] == 1 ? "selected" : null; ?>>Tắt</option>
          <option value="2" <?= $general["updates_show"] == 2 ? "selected" : null; ?>>Mở</option>
          
          </select>
        </div>


<div class="form-group">
          <label for="" class="control-label">Đơn hàng SLL</label>
          <select class="form-control" name="massorder">
         
                    <option value="1"  <?= $general["massorder"] == 1 ? "selected" : null; ?>>Tắt</option>
          <option value="2" <?= $general["massorder"] == 2 ? "selected" : null; ?>>Mở</option>
          
          </select>
        </div>


<hr>
        <center><button type="submit" class="btn btn-primary">Lưu thay đổi</button></center>
      </form>
      
    </div>
  </div>
</div>


<div class="col-md-8">
<?php  

$google_login = json_decode($settings["google_login"],true);

?>

<?php if($google_login["purchased"] != "HVgKSfwiWe"){ ?>
<div data-addon="google_login">
  <div class="product-icon"></div>
  <div class="product-details">
    <h6 class="product-name">Google</h6>
    <p class="product-price">₹ 350.00</p>
  </div>
  </a>
</div>

<?php } else { ?>
 <div class="settings-emails__block-body">
<table>
<thead>
<tr>
<th class="settings-emails__th-name"></th>
<th class="settings-emails__th-actions"></th>
</tr>
</thead>
<tbody>
<tr class="settings-emails__row">
                    <td>
                        <div class="settings-emails__row-name">Google Login</div>
<div class="settings-emails__row-description">Users will be able to login with their Google Account to the panel.</div>
</td>

<td class="settings-emails__td-actions">  
    <label class="switch">
      <input  data-addon="google_login"  type="checkbox" class="switch-input addon"  <?php echo $google_login["status"] ? "checked" : "";?>>
      <span class="switch-label" data-on="On" data-off="Off"></span>
      <span class="switch-handle"></span>
    </label>

 </td>
</tr>
</tbody>
</table>

<?php } ?>



</div>