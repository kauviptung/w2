<?php include 'new-header.php'; ?>
<div class="container-fluid margin-top-container">

   <div class="row">
      <div class="col">
         <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-form="add_remove_balance"
            data-bs-target="#staticBackdropModal">Thêm / Trừ số dư</button>
      </div>
   </div>
   <div class="row">
      <div class="col">
         <div id="fund-add-history" class="page-content col col-lg-12">
            <div data-pattern="priority-columns" class="mt-2">
               <table cellspacing="0"
                  class="table table-small-font table-tighten table-bordered  table-striped table-hover loading">
                  <thead class="table-secondary">
                     <tr>
                        <th>ID</th>
                        <th>Thành viên</th>
                        <th>Phương thức</th>
                        <th>Số dư người dùng</th>
                        <th>Số tiền</th>
                        <th>Trạng thái</th>
                        <th>Khi</th>
                        <th>Hành động</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td colspan="7">
                           <center><svg class="spinner medium" viewBox="0 0 48 48">
                                 <circle class="path" cx="24" cy="24" r="20" fill="none" stroke-width="5">
                                 </circle>
                              </svg> Đang tải...</center>
                        </td>
                     </tr>
                  </tbody>
               </table>


            </div>
         </div>
      </div>
   </div>


   <?php include 'new-footer.php'; ?>