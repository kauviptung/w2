<?php include 'header.php'; ?>
<style>
    body {
        background-color: #fff;
    }
</style>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
		 <div class="panel panel-default">
          <div class="panel-body">
      <form action="admin/account" method="post" enctype="multipart/form-data">
     
          <div class="form-group">
            <label for="charge" class="control-label">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" value="" name="current_password">
          </div>

          <div class="form-group">
            <label for="charge" class="control-label">Mật khẩu mới</label>
            <input type="password" class="form-control" value="" name="password">
          </div>

          <div class="form-group">
            <label for="charge" class="control-label">Nhập lại mật khẩu</label>
            <input type="password" class="form-control" value="" name="confirm_password">
          </div>
          <button type="submit" class="btn btn-primary">Thay đổi mật khẩu</button>
        </form>
      </div><br>

      </div>
</div>

    </div>
  </div>
</div>
</body>
<?php include 'footer.php'; ?>
