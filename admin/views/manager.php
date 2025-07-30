<?php require 'header.php'; ?>

<div class="container-fluid">
    <div class="col-md-12">


        <ul class="nav nav-tabs p-b">


<li class="pull-right p-b">
<?php if (route(2) == "admins") : ?>
<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalDiv" data-action="add_admin">Thêm Admin</button>
<?php endif; ?>


</li>
</ul>

<?php if (route(2) == "admins") : ?>

<div class="row">
<center>
    <h3><strong>Quản trị viên siêu cấp</strong></h3>
</center>
<div  style="overflow:scroll;height:140px;" class="">

<table  class="table">
    <thead>
        <tr>

<th>Name</th>
            <th>Email</th>
            <th>Tên người dùng</th>
            <th>Trạng thái</th>


<th nowrap="">Được tạo ra tại</th>
<th nowrap="">Đăng nhập lần cuối</th>

            <th></th>
        </tr>
    </thead>



    <tbody>
        <?php foreach ($admins as $mainadmin): ?>

           

                <tr>

<td><?php echo $mainadmin["admin_name"] ?></td>
<td><?php echo $mainadmin["admin_email"] ?></td>
<td><?php echo $mainadmin["username"] ?></td>
<td><?php echo $mainadmin["client_type"] == 2 ? "Kích hoạt"  : "Không hoạt động" ?></td>

<td><?php echo $mainadmin["register_date"] ?></td>
<td><?php echo $mainadmin["login_date"] ?></td>


<td class="td-caret">
    <div class="dropdown pull-right">
        <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown" aria-expanded="true">
          Hành động <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">

            <li><a href="#" data-toggle="modal" data-target="#modalDiv" data-action="edit_admin" data-id="<?php echo $mainadmin["admin_id"] ?>">Sửa tài khoản</a></li>


        </ul>
    </div>
</td>
                </tr>
        
   <?php    endforeach; ?>



    </tbody>
</table>
                </div>
<center>
    <h3><strong>Nhân viên</strong></h3>
</center>
<div style="overflow:scroll;"><br>



<table class="table">
    <thead>
        <tr>

            <th>Tên</th>
            <th>Email</th>
            <th>Tên người dùng</th>
            <th>Trạng thái</th>

            <th nowrap="">Được tạo tại</th>
            <th nowrap="">Đăng nhập lần cuối</th>

            <th></th>
        </tr>
    </thead>



    <tbody>

        <?php foreach ($staffsData as $staff ) : ?>



                <tr>

<td><?php echo $staff["admin_name"] ?></td>
<td><?php echo $staff["admin_email"] ?></td>
<td><?php echo $staff["username"] ?></td>
<td><?php echo $staff["client_type"] == 2 ? "Kích hoạt"  : "Không hoạt động" ?></td>

<td><?php echo $staff["register_date"] ?></td>
<td><?php echo $staff["login_date"] ?></td>




                <td class="td-caret">
<div class="dropdown pull-right">
    <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown"
    data-boundary="window"
    aria-haspopup="true"
    aria-expanded="false">
      Hành động <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">

        <li><a href="#" data-toggle="modal" data-target="#modalDiv" data-action="edit_admin" data-id="<?php echo $staff["admin_id"] ?>">Sửa tài khoản</a></li>
        <li><a href="#" data-toggle="modal" data-target="#modalDiv" data-action="edit_admin_password" data-id="<?php echo $staff["admin_id"] ?>">Thay đổi mật khẩu</a></li>
<li><a href="/admin/manager/admins/delete_staff/<?php echo $staff["admin_id"]?>">Xóa nhân viên</a></li>
    </ul>
</div>
                </td>
            </tr>
    <?php    endforeach; ?>



    </tbody>
</table>
                </div>
            </div>

        <?php endif; ?>
        
    </div>
</div>
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-center" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>Bạn có xác nhận quá trình này không?</h4>
                <div align="center">
<a class="btn btn-primary" href="" id="confirmYes">Xác nhận</a>
<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require 'footer.php'; ?>