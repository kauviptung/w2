<?php if( !route(4) ): ?>

<div class="col-md-9">

                      <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row settings-menu__row">
                        <div class="col-md-3">
                            <div class="settings-menu__title">Tiêu đề</div>
                            <div class="settings-menu__description">Tùy chọn yêu cầu hỗ trợ.</div>
                        </div>
                        <div class="col-md-9">
                            <div class="dd">
                                <ol class="dd-list ui-sortable">
                                       <?php foreach($subjectList as $subject): ?>
                                        <li class="dd-item ui-sortable-handle">
                                            <div class="dd-handle"><?php echo $subject["subject"]?></div>
                                            <div class="settings-menu__action">
                                                <?php if($subject["auto_reply"] == 1):
                                                echo'<i class="fas fa-magic"></i> ';
                                                endif; ?>
                                                <a href="<?php echo site_url('admin/settings/subject/edit/'.$subject["subject_id"].'') ?>" class="btn btn-default btn-xs edit-modal-menu">Chỉnh sửa</a>
                                            </div>
                                        </li>
                                        <?php endforeach; ?>
                                                                           
                                                                    </ol>
                            </div>
                            <a href="javascript:;" onclick="showMe('gizlebeni');" class="btn btn-default m-b add-modal-menu">Tạo tiêu đề mới</a>
                        </div>
                    </div>

                </div>
            </div>
      
        
         <div class="panel panel-default" id="gizlebeni" style="display: none;">
    <div class="panel-body">

         <form action="<?php echo site_url('admin/settings/subject') ?>" method="post" enctype="multipart/form-data">
             
                     <div class="form-group relative">
         
          <label for="" class="control-label">Tiêu đề hỗ trợ</label>
          <input type="text" class="form-control" name="subject">
        </div>
        
<div class="form-group">
               <label class="control-label">Trả lời tự động</label>
<select class="form-control" name="auto_reply">
    <option value="0" selected>Đóng</option>
    <option value="1">Mở</option>
</select>            </div>	          

            <div class="form-group">
               <label class="control-label">Tin nhắn để trả lời tự động</label>
               <textarea class="form-control" rows="5" name="content"></textarea>
            </div>	  
           <p>Trả lời tự động khi có yêu cầu hỗ trợ mới được tạo trong chủ đề này.</p> 
            <hr>

            <button type="submit" class="btn btn-primary">Create</button>
         </form>

</div> </div>



</div>

<script type="text/javascript">
function showMe(blockId) {
     if ( document.getElementById(blockId).style.display == 'none' ) {
          document.getElementById(blockId).style.display = ''; }
else if ( document.getElementById(blockId).style.display == '' ) {
          document.getElementById(blockId).style.display = 'none'; }
}
</script>


<?php elseif( route(3) == "edit" ): ?>
<div class="col-md-9">
    <a href="/admin/settings/subject" class="details_backButton btn btn-link"><span>‹</span> Trở lại</a>

            <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>

                 
         <div class="panel panel-default">
    <div class="panel-body">

         <form action="<?php echo site_url('admin/settings/subject/edit/'.route(4)) ?>" method="post" enctype="multipart/form-data">
             
                     <div class="form-group relative">
         
          <label for="" class="control-label">Tiêu đề hỗ trợ</label>
          <input type="text" class="form-control" name="subject" value="<?=$post["subject"]?>">
        </div>
        
<div class="form-group">
               <label class="control-label">Trả lời tự động</label>
<select class="form-control" name="auto_reply">
    <option value="0" <?php if($post["auto_reply"] == 0){echo'selected';}
    elseif($post["auto_reply"] == 1){echo'selected'; }?>>Closed</option>
    <option value="1" <?php if($post["auto_reply"] == 1){echo'selected';}
    elseif($post["auto_reply"] == 1){echo'selected'; }?>>Active</option>
</select>            </div>	          

            <div class="form-group">
               <label class="control-label">Tin nhắn để trả lời tự động</label>
               <textarea class="form-control" rows="5" name="content"><?=$post["content"]?></textarea>
            </div>	  
           <p>Trả lời tự động khi có yêu cầu hỗ trợ mới được tạo trong chủ đề này.</p> 
            <hr>

            <button type="submit" class="btn btn-primary">Cập nhật</button>

            <a href="<?php echo site_url('admin/settings/subject/delete/'.$post["subject_id"]) ?>" class="btn btn-link pull-right deactivate-integration-btn">
                                Xóa
                            </a>

         </form>

</div> </div>


</div> </div> </div> 


<?php endif; ?>
 