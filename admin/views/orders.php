<?php include 'header.php'; ?>
<div class="container-fluid">
            <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
<div class="container-fluid">
            <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
   <ul class="nav nav-tabs p-b">
     <li class="<?php if( $status == "all"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders")?>">Tất cả Đơn hàng <span class="badge" style="background-color: #808080"><?php echo countRow(["table"=>"orders"]) ?></span></a></li>
     <li class="<?php if( $status == "cronpending"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/cronpending")?>">Đang chờ đợi <span class="badge" style="background-color: #808080"><?php if($cronpendingcount): echo $cronpendingcount; endif; ?></span></a></li>
     <li class="<?php if( $status == "pending"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/pending")?>">Đang đợi <span class="badge" style="background-color: #808080"><?php if($pendingcount): echo $pendingcount; endif; ?></span></a></li>
               <li class="<?php if( $status == "processing"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/processing")?>">Đang xử lý <span class="badge" style="background-color: #808080"><?php if($processingcount): echo $processingcount; endif; ?></span></a></li>
     <li class="<?php if( $status == "inprogress"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/inprogress")?>">Đang chạy <span class="badge" style="background-color: #808080"><?php if($inprogresscount): echo $inprogresscount; endif; ?></span></a></li>
     <li class="<?php if( $status == "completed"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/completed")?>">Hoàn thành <span class="badge" style="background-color: #808080"><?php if($completedcount): echo $completedcount; endif; ?></span></a></li>
     <li class="<?php if( $status == "partial"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/partial")?>">Hoàn một phần <span class="badge" style="background-color: #808080"><?php if($partialcount): echo $partialcount; endif; ?></span></a></li>
     <li class="<?php if( $status == "canceled"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/canceled")?>">Đã hủy <span class="badge" style="background-color: #808080"><?php if($canceledcount): echo $canceledcount; endif; ?></span></a></li>

     <li class="<?php if( $status == "fail"): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/fail")?>">Bị lỗi <span class="badge" style="background-color: #8b3a3a"><?php if($failCount): echo $failCount; endif; ?></span></a></li>
      <li class="pull-right custom-search">
         <form class="form-inline" action="<?=site_url("admin/orders")?>" method="get">
            <div class="input-group">
               <input type="text" name="search" class="form-control" value="<?=$search_word?>" placeholder="Search">
               <span class="input-group-btn search-select-wrap">
                  <select class="form-control search-select" name="search_type">
                     <option value="order_id" <?php if( $search_where == "order_id" ): echo 'selected'; endif; ?> >Mã đơn hàng</option>
                     <option value="order_url" <?php if( $search_where == "order_url" ): echo 'selected'; endif; ?> >Link đơn hàng</option>
                     <option value="username" <?php if( $search_where == "username" ): echo 'selected'; endif; ?> >Thành viên</option>
                  </select>
                  <button type="submit" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
               </span>
            </div>
         </form>
      </li>
   </ul>
               
                    
<div class="orders-table">
<table class="table" id="dt">
                                <thead>
         <tr>
            <th class="checkAll-th">
               <div class="checkAll-holder">
                  <input type="checkbox" id="checkAll">
                  <input type="hidden" id="checkAllText" value="order">
               </div>
               <div class="action-block">
                  <ul class="action-list">
                     <li><span class="countOrders"></span> Đơn hàng đã chọn</li>
                     <li>
                        <div class="dropdown">
                           <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown"> Hành động hàng loạt <span class="caret"></span></button>
                           <ul class="dropdown-menu">
                              <li>
								  <?php if( $status  ==  "fail" ): ?>
                                 <a class="bulkorder" data-type="resend">Gửi lại</a>
                                <?php endif; ?>
                                 <a class="bulkorder" data-type="pending">Đang đợi</a>
                                 <a class="bulkorder" data-type="inprogress">Đang chạy</a>
                                 <a class="bulkorder" data-type="completed">Hoàn thành</a>
                                 <a class="bulkorder" data-type="canceled">Hủy và Hoàn tiền</a>
                                 
                              </li>
                           </ul>
                        </div>
                     </li>
                  </ul>
               </div>
            </th>
            <th class="p-l">ID</th>
            <th>Thành viên</th>
            <th>Giá tiền</th>
            <th>Lợi nhuận</th>
            <th class="service-block__service">Link</th>
            <th>Nhà cung cấp</th>
            <th>Bắt đầu</th>
            <th>Số lượng</th>
            <th class="dropdown-th">
              <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" data-active="<?=$_GET["service_id"]?>" type="button" id="serviceList" data-href="admin/orders/counter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Dịch vụ
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" id="serviceListContent" style="max-height: 275px; overflow:hidden; overflow-y: scroll">
                </ul>
              </div>
            </th>
            <th>Trạng thái</th>
            <th>Còn lại</th>
            <th width="10%">Date</th>
            <th width="5%" class="dropdown-th">
              <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Chế độ
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li class="<?php if( !$_GET["mode"] ): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/".$status)?>">Tất cả</a></li>
                    <li class="<?php if( $_GET["mode"] == "manuel" ): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/".$status)?>?mode=manuel">Thủ công</a></li>
                    <li class="<?php if( $_GET["mode"] == "auto" ): echo "active"; endif; ?>"><a href="<?=site_url("admin/orders/1/".$status)?>?mode=auto">Tự động API</a></li>
                </ul>
              </div>
            </th>
            <th></th>
         </tr>
      </thead>
      <form id="changebulkForm" action="<?php echo site_url("admin/orders/multi-action") ?>" method="post">
        <tbody>
          <?php foreach( $orders as $order ): ?>
              <tr>
                 <td><input type="checkbox" class="selectOrder"  name="order[<?php echo $order["order_id"] ?>]" value="1" style="border:1px solid #fff"></td>
                 <td class="p-l">
                  <?php echo $order["order_id"] ?>
                  <?php if( $order["api_orderid"] != 0 ): echo '<div class="label label-api">'.$order["api_orderid"].'</div>'; endif; ?>
                </td>
                 <td><?php echo $order["username"]; if( $order["order_where"] == "api" ): echo ' <span class="label label-api">API</span>'; endif; ?> </td>
                 <td class="service-block__minorder">
                   <div style="width:85px;">
 
<?php echo format_amount_string($settings["site_base_currency"],$order["order_charge"]); ?>
                   
<?php if( $order["service_api"] != 0 ): echo '<div class="service-block__provider-value">'.format_amount_string($settings["site_base_currency"],$order["api_charge"]).'</div>'; endif; ?>
</div>
                 </td>
<td>
 <div style="width:85px;"><?php echo format_amount_string($settings["site_base_currency"],$order["order_profit"]); ?>
 
 </div>
 </td>
<td class="service-block__service"><?php echo $order["order_url"]; 
            if(empty($order["order_extras"]) || $order["order_extras"] == "[]"){ }else{
                        echo' <a href="#" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalDiv" data-action="order_comment" data-id="'.$order["order_id"].'">Yorumlar</a>'; 
                        
                    }
                    ?>
		<a href="https://href.li/<?php echo $order["order_url"]; ?>" class="order-link" target="_blank">
<span class="fa fa-external-link"></span>
</a>		  
				  
				  </td>
<td><?php echo GET_API_NAME_BY_ID($order["order_api"]);?></td>
                 <td><?php echo $order["order_start"]; ?></td>
                 <td><?php echo $order["order_quantity"]; ?></td>
                 <td width="30%"><span class="label-id"><?php echo $order["service_id"]; ?></span><?php echo $order["service_name"]; ?></td>
                 <td><?php echo  orderStatu($order["order_status"],$order["order_error"],$order["order_detail"]); ?></td>
                 <td><?php if( $order["order_status"] == "completed" && substr($order["order_remains"], 0,1) == "-" ): echo "+".substr($order["order_remains"], 1);  else: echo $order["order_remains"]; endif; ?></td>
                 <td width="10%"><?php echo $order["order_create"]; ?></td>
                 <td width="5%"><?php if( $order["api_service"] == 0 ): echo "Thủ công"; else: echo "Tự động API"; endif; ?></td>
                 <td class="service-block__action">
                   <div class="dropdown pull-right">
                     <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Tùy chọn <span class="caret"></span></button>
                     <ul class="dropdown-menu">
                       <?php if( $order["order_error"] != "-" && $order["service_api"] != 0 ): ?>
                         <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_errors" data-id="<?php echo $order["order_id"] ?>">Đơn hàng lỗi</a></li>
                         <li><a href="<?=site_url("admin/orders/order_resend/".$order["order_id"])?>">Resend Order</a></li>
                       <?php endif; ?>
                       <?php if( $order["order_error"] == "-" && $order["service_api"] != 0 ): ?>
                         <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_details" data-id="<?php echo $order["order_id"] ?>">Chi tiết Đơn hàng</a></li>
                       <?php endif; ?>
                       <?php if( $order["service_api"] == 0 || $order["order_error"] != "-"  ): ?>
                         <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_orderurl" data-id="<?php echo $order["order_id"] ?>">Sửa link Đơn hàng</a></li>
                       <?php endif; ?>
                         <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_startcount" data-id="<?php echo $order["order_id"] ?>">Sửa số bắt đầu</a></li>
                      
                       <?php if( $order["order_status"] != "partial"): ?>
                         <li><a href="#"  data-toggle="modal" data-target="#modalDiv" data-action="order_partial" data-id="<?php echo $order["order_id"] ?>">Số lượng hoàn lại</a></li>
                       <?php endif; ?>
<?php if( $order["refill"]  ==  "0" || $order["refill"]  ==  "2" || $order["status_order"]  ==  "Completed" ): ?>
<li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_refill_activate/".$order["order_id"])?>">Kích hoạt nút Bảo hành</a></li>
<?php endif; ?>

                       <li class="dropdown dropdown-submenu">
                          <a href="#" class="dropdown_menu">Cập nhật trạng thái đơn hàng</a>
                          <ul class="dropdown-menu submenu_drop">
                     
                            <?php if( $order["order_status"]  ==  "pending" || $order["order_status"]  ==  "completed"  || $order["order_status"]  ==  "processing" || $order["order_status"]  ==  "partial" || $order["order_status"]  ==  "fail" ): ?>
                              <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_cancel/".$order["order_id"])?>">Hủy và Hoàn tiền</a></li>
                            <?php endif; ?>
                 <?php if( $order["order_status"]  ==  "pending" || $order["order_status"]  ==  "inprogress"  || $order["order_status"]  ==  "processing" ): ?>           
                              <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_complete/".$order["order_id"])?>">Hoàn thành</a></li>
               <?php endif; ?>             
                            <?php if( $order["order_status"]  ==  "pending"  || $order["order_status"]  ==  "processing"  ): ?>
                              <li><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=site_url("admin/orders/order_inprogress/".$order["order_id"])?>">Đang chạy</a></li>
                            <?php endif; ?>
                          </ul>
                        </li>
                     </ul>
                   </div>
                 </td>
              </tr>
            <?php endforeach; ?>
        </tbody>
        <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">
      </form>
   </table></div>
   <?php if( $paginationArr["count"] > 1 ): ?>
     <div class="row">
        <div class="col-sm-8">
           <nav>
              <ul class="pagination">
                <?php if( $paginationArr["current"] != 1 ): ?>
                 <li class="prev"><a href="<?php echo site_url("admin/orders/1/".$status.$search_link) ?>">&laquo;</a></li>
                 <li class="prev"><a href="<?php echo site_url("admin/orders/".$paginationArr["previous"]."/".$status.$search_link) ?>">&lsaquo;</a></li>
                 <?php
                     endif;
                     for ($page=1; $page<=$pageCount; $page++):
                       if( $page >= ($paginationArr['current']-9) and $page <= ($paginationArr['current']+9) ):
                 ?>
                 <li class="<?php if( $page == $paginationArr["current"] ): echo "active"; endif; ?> "><a href="<?php echo site_url("admin/orders/".$page."/".$status.$search_link) ?>"><?=$page?></a></li>
                 <?php endif; endfor;
                       if( $paginationArr["current"] != $paginationArr["count"] ):
                 ?>
                 <li class="next"><a href="<?php echo site_url("admin/orders/".$paginationArr["next"]."/".$status.$search_link) ?>" data-page="1">&rsaquo;</a></li>
                 <li class="next"><a href="<?php echo site_url("admin/orders/".$paginationArr["count"]."/".$status.$search_link) ?>" data-page="1">&raquo;</a></li>
                 <?php endif; ?>
              </ul>
           </nav>
        </div>
        <div class="col-sm-4 pagination-counters">
          <?php echo $count; ?> từ bên trong đơn hàng <?php echo $where+1 ?>'den <?php if( $where+$to > $count ): echo $count; else: echo $where+$to; endif; ?>'lên đến
         </div>
     </div>
   <?php endif; ?>
</div>
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
   <div class="modal-dialog modal-dialog-center" role="document">
      <div class="modal-content">
         <div class="modal-body text-center">
            <h4>Bạn có chắc chắn muốn cập nhật trạng thái không?</h4>
            <div align="center">
               <a class="btn btn-primary" href="" id="confirmYes">Có</a>
               <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'footer.php'; ?>
