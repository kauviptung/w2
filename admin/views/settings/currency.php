
        <div class="col-md-8">
	<div class="settings-header__table">
		<button type="button" class="btn btn-default m-b" data-toggle="modal" data-target="#modalDiv" data-action="add_currency">Thêm tiền tệ</button>
	</div>
	<div class="col-md-12">
   <table class="table report-table" style="border:1px solid #ddd">
      <thead>
         <tr>
            <th>Tên tiền tệ</th>
            <th>Biểu tượng</th>
   <th>Tỷ giá hối đoái</th>
   <th></th>
         </tr>
      </thead>
      <tbody>
         <?php foreach($currencies as $currencie): ?>
         <tr class="<?php if( $currencie["status"] == 2 ): echo "grey "; endif; ?>" data-toggle="<?php echo $currencie["id"]; ?>" data-id="<?php echo $currencie["id"]; ?>">
            <td> <?php echo $currencie["name"];  ?></td>
<td> <?php echo $currencie["symbol"]; ?></td>
<td> <?php echo $currencie["value"]; ?></td>
            <td class="text-right col-md-1">
              <div class="dropdown pull-right">
                <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Tùy chỉnh <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <li>
                <a  data-toggle="modal" data-target="#modalDiv" data-action="edit_currency" data-id="<?= $currencie["id"] ?>">Chỉnh sửa</a>
					
                  </li>

                    <li>
                      <a href="<?php echo site_url('admin/settings/currency/delete/'.$currencie["id"]) ?>">
                        Xóa
                      </a>
                    </li>
</td>
                  
                </ul>
              </div>
            </td>
         </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>