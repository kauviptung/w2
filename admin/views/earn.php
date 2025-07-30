<?php include 'header.php'; ?>

<div class="content-body">
    <div class="pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10 dbg-none">
                        <li class="breadcrumb-item"><a href="#"><?= constant("HOME") ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Khuyến mãi</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row row-xs">

            <div class="col">
                <div class="card dwd-100">
                    <div class="card-body pd-20 table-responsive dof-inherit">

                        <div class="container-fluid pd-t-20 pd-b-20">
                            <table class="table" id="dt">
                                <thead>
                                    <tr>
                                        <th class="p-l">ID</th>
                                        <th>Thành viên</th>
                                        <th>link</th>
                                        <th>Ghi chú</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <form id="changebulkForm" method="post">
                                    <tbody>
                                        <?php foreach($earn as $earn ): ?>
                                        <tr>

                                            <td class="p-l"><?php echo $earn["earn_id"] ?></td>
                                            <td><?php echo $user["username"] ?></td>
                                            <td><a   target="_blank" href="<?php echo $earn["link"] ?>"><?php echo $earn["link"] ?></td>
                                            <td><?php echo $earn["earn_note"] ?></td>
                                            <td><?php echo $earn["status"] ?></td>

<td class="service-block__action">
                                    <div class="dropdown pull-right">
                     <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Tùy chọn <span class="caret"></span></button>
                     <ul class="dropdown-menu">
                      
      <?php?>
                              <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/earn/earn_review/".$earn["earn_id"])?>">Đang xem xét</a></li>
                            <?php?>
                              <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="earn_note" data-id="<?php echo $earn["earn_id"] ?>">Sửa ghi chú</a></li>
                            <?php ?>
                              <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/earn/earn_grant/".$earn["earn_id"])?>">Quỹ được cấp</a></li>
                            
                            <?php ?>
                              <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/earn/earn_reject/".$earn["earn_id"])?>">Từ chối</a></li>
                            
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">
                                </form>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
   <div class="modal-dialog modal-dialog-center" role="document">
      <div class="modal-content">
         <div class="modal-body text-center">
            <h4>Bạn có chắc chắn muốn cập nhật trạng thái không??</h4>
            <div align="center">
               <a class="btn btn-primary" href="" id="confirmYes">Có</a>
               <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
<ul>
</td>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'footer.php'; ?>
