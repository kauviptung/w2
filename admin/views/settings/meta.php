<div class="col-md-8">
  <div class="panel panel-default">
    <div class="panel-body">
      <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
          <label for="" class="control-label">Tiêu đề bạn muốn trang web xuất hiện trên Google</label>
          <input type="text" class="form-control" name="seo" value="<?=$settings["site_seo"]?>">
        </div>
        <div class="form-group">
          <label for="" class="control-label">Tiêu đề trang web</label>
          <input type="text" class="form-control" name="title" value="<?=$settings["site_title"]?>">
        </div>
        <div class="form-group">
          <label for="" class="control-label">Từ khóa trang web</label>
          <input type="text" class="form-control" name="keywords" value="<?=$settings["site_keywords"]?>">
        </div>
        <div class="form-group">
          <label class="control-label">Mô tả trang web</label>
          <textarea class="form-control" rows="3" name="description" placeholder=''><?=$settings["site_description"]?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật cài đặt</button>
      </form>
    </div>
  </div>
</div>
