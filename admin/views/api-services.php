<?php include('header.php') ?>

<style>
    .services-import__header li.active {
        background-color: #EEEEEE;
        color: white;
        border-radius: 3px;
    }
</style>



<div class="container-fluid" role="main">
    <?php if (($_SESSION["information"])) : $info = $_SESSION["information"];  ?>
        <div class="alert alert-<?= $info["type"] ?> " role="alert">
<?= $info["message"]  ?>
        </div>

        <script>
setTimeout(() => {
    <?php $_SESSION["information"] = array(); ?>
}, 2000);
        </script>


    <?php endif; ?>
    <div class="container container-fluid" role="main">
        <div class="col-sm-offset-3 col-sm-5 m-t">

<div class="services-import__header">
    <ul style="display:flex; cursor:not-allowed;" class="nav nav-wizard">
        <li class="<?php if (!route(2)) : ?> active <?php endif; ?>"><a>Chọn nhà cung cấp</a></li>
        <li class="<?php if (route(2) == "ajax_services_update") : ?> active  <?php endif; ?>"><a>Chọn danh mục</a></li>
        <li class="<?php if (route(2) == "ajax_services_last") : ?> active  <?php endif; ?>"><a>Tùy chỉnh dịch vụ</a></li>

    </ul>


</div>
        </div>





    </div>

    <?php if (!route(2)) : ?>

        <div class="col-sm-offset-3 col-sm-5 m-t">
<form method="post" action="<?= site_url('admin/api-services/ajax_services_update') ?>" data-parsley-validate="" id="demo-form2" novalidate="">
    <div class="form-group">
        <label class="control-label" for="api_fetch_id"> Chọn API </label>
        <select id="api_fetch_id" data-live-search="true" name="api_fetch_id" class="form-control">
<?php foreach ($providers as $provider) : ?>
    <option value="<?php echo $provider['id'] ?>"><?php echo $provider['api_name'] ?></option>
<?php endforeach; ?>
        </select>
        <p class="help-block help-block-error"></p>
    </div>
    <div class="form-group">
        <label class="control-label" for="profit"> Phần trăm lợi nhuận </label>
        <div class="input-group">
<input class="form-control" name="profit" id="profit" value="10" required type="number">
<div class="input-group-addon">
    <label>%</label>
</div>
        </div>
        <p class="help-block help-block-error"></p>
    </div>


    <button type="submit" id="api_feetch" name="api_feetch" class="btn btn-block btn-primary"> Bước tiếp theo </button>

</form>
        </div>

    <?php elseif (route(2) == "ajax_services_update") : ?>



        <center>
<h3>Chọn danh mục để nhập</h3>
<h5>Tổng số dịch vụ tìm thấy: <?= $servicesCount ?></h5>

        </center>
        <ul class="nav nav-tabs nav-tabs__service">
<li class="pull-right">
    <div class="inline-block"><label for="service-search-input" class="service-search__icon"></label>
        <input class="form-control" placeholder="Tìm kiếm danh mục" id="priceService" type="text" value="">
    </div> <br>
</li>
        </ul>

        <?php if (isset($disabled)) : ?>
<div class="alert alert-danger">
    <h4>Thông tin quan trọng</h4>
    Chọn tối đa <strong>10-15</strong> danh mục vì nhà cung cấp này có nhiều dịch vụ nên trang có thể mất nhiều thời gian hơn để tải.<br />
    Tốt hơn là thêm một số danh mục trước và phần còn lại ở hàng thứ hai,
    cũng như giữ càng ít dịch vụ càng tốt để không bị chậm trễ khi ở trên trang dịch vụ.
</div>
        <?php endif; ?>
<div style="overflow:auto">
        <table class="table" style="font-size: 13px;">
<form action="<?= site_url('admin/api-services/ajax_services_add') ?>" method="post" id="changebulkForm">
    <input id="hidden_data" hidden type="text" value="" name="form_data">

    <thead>
        <tr>
<th class="checkAll-th">
    <div class="checkAll-holder">
        <input <?= $disabled ?> class="checkAll" type="checkbox">
    </div>
    <div class="action-block">
        <ul class="action-list">
<li><span class="countOrders"></span> selected</li>
<li>
    <div class="dropdown pull-right">
        <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Hành động <span class="caret"></span></button>
        <ul class="dropdown-menu">
<li><a onclick="map_categories()" id="map_categories"> Nhập các danh mục này </a></li>
        </ul>
    </div>
</li>
        </ul>
    </div>
</th>
<th class="column-id">Id</th>
<th>Chọn danh mục</th>
<th>Tên danh mục API</th>
        </tr>
    </thead>
    <tbody>

        <?php if (!$services->error) {
$grouped = array_group_by($services, 'category');
$category_id = 0;
foreach ($grouped as $category) {
    $category_id++;

        ?>
    <tr data-name="<?= $category[0]->category ?>">
        <td><input class="selectOrder" name="checkbox[<?= $category_id ?>]" value="<?= $category_id ?>" type="checkbox"></td>
        <td> <?= $category_id ?> </td>
        <td class="col-md-5">
<select class="form-control" name="category_ids[<?= $category_id ?>]">
    <option value="0">Tạo mới</option>
    <?php foreach ($categoriesData as $cat) : ?>
        <option class="<?= $cat["category_type"] == 1 ? "grey" : "" ?>" value="<?= $cat["category_id"] ?>"><?= $cat['category_name'] ?></option>
    <?php endforeach; ?>
</select>

        </td>
        <td class="col-md-6">
<input style="width:400px;" class="form-control categoryName" value="<?= $category[0]->category ?>" name="category_name[<?= $category_id ?>]" type="text">
<input value="<?= $category[0]->category ?>" name="old_category_name[<?= $category_id ?>]" type="hidden">
        </td>
    </tr>
<?php
}
        } else { ?>
<tr>
    <td colspan="4" align="center">
        <h3><strong>Không tìm thấy dịch vụ nào<br />
</strong><code> <?= $services->error ?></code></h3>
    </td>

</tr>
        <?php } ?>
    </tbody>
    <input name="status" id="bulkStatus" value="1" type="hidden">
    <input type="hidden" value="categories" name="import" id="importValue">
</form>
        </table>
</div>

    <?php elseif (route(2) == "ajax_services_last") : ?>

        <center>
<h3>Chọn dịch vụ để nhập</h3>
<h5>Tổng số dịch vụ được tìm thấy: <?= $servicesCount ?></h5>
<span class="badge badge-secondary"><?=$provider["api_name"]." : ".$provider["currency"];?></span>
        </center>
        <br>
        <ul class="nav nav-tabs nav-tabs__service">
<li class="pull-right">
    <div class="inline-block"><label for="service-search-input" class="service-search__icon"></label>
        <input class="form-control" placeholder=" Tìm kiếm Dịch vụ" id="priceService" type="text" value="">
    </div> <br>
</li>
        </ul>


        <form action="<?= site_url('admin/api-services/ajax_services_addNow') ?>" method="post" id="servicesAddForm">

<input id="services_data" hidden type="text" value="" name="form_data">


        </form>
        <?php if (isset($pageMessage) && !empty($pageMessage)) : ?>
<div class="alert alert-info">
    <?= $pageMessage ?>
</div>
        <?php endif; ?>
        <div style="overflow:auto;">

<table class="table" style="font-size: 13px; ">

    <form action="<?= site_url('admin/api-services/ajax_services_addNow') ?>" method="post" id="changebulkForm">
        <input type="hidden" value="<?= $profit ?>" name="service_profit_percentage">
        <input type="hidden" value="services" name="import" id="importValue">
        <thead>
<tr>
    <th class="checkAll-th">
        <div class="checkAll-holder">
<input class="checkAll" type="checkbox">
        </div>
        <div class="action-block">
<ul class="action-list">
    <li><span class="countOrders"></span> selected</li>
    <li>
        <div class="dropdown pull-right">
<button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">
    Hành động <span class="caret"></span>
</button>
<ul class="dropdown-menu">
    <li><a onclick="map_services" id="disabledservices"> Nhập Dịch vụ </a></li>
</ul>
        </div>
    </li>
</ul>
        </div>
    </th>
    <th class="column-id">ID</th>
    <th>Danh mục</th>
    <th>Dịch vụ</th>
    <th>Dịch vụ API</th>
    <th>Kiểu</th>
    <th>API</th>
    <th>Giá [+<?= $profit ?>%]</th>
    <th nowrap="nowrap">Tối thiểu</th>
    <th nowrap="nowrap">Tối đa</th>
    <th nowrap="nowrap">Mô tả</th>
</tr>
        </thead>

        <tbody>
<input name="status" id="bulkStatus" value="2" type="hidden">
<?php $i = 0;
foreach ($getServicesByCategory as $categoryId => $services) : ?>
    <?php foreach ($services as $service) :

  if ($provider["api_type"] == 1) {
$servicePrice = $service->rate;
$SELLER_PRICE = $servicePrice;
        } elseif ($provider["api_type"] == 4) {
$servicePrice = $service->cost;
$SELLER_PRICE = $servicePrice;
        }
//$servicePrice = round($servicePrice/82.34, 3) ;
$servicePrice = from_to(get_currencies_array("all"),$provider["currency"],$settings["site_base_currency"],$servicePrice);
$providerCurrency = $provider["currency"];

$numberToAdd = ($profit / 100) * $servicePrice;


$price = $servicePrice + $numberToAdd;

$finalPrice = $price;

 
    ?>

<tr data-name="<?= $service->name ?>">
<td><input class="selectOrder" name='checkbox[<?= $i ?>]' value="1" type="checkbox"></td>
<td> <?= $i + 1 ?> </td>
<td>
    <select class="form-control" name="category_ids_array[<?= $i ?>]">
        <?php foreach ($allCategories as $category) : if ($category["category_id"] == $categoryId) : ?>
<option value="<?= $category["category_id"] ?>" <?php ?>selected="selected"><?= $category["category_name"] ?>
</option>
        <?php endif; endforeach; ?>
    </select>
</td>
<td>
    <select class="form-control" name="our_services_ids_array[<?= $i ?>]">
        <option value="0">Create New</option>
        <?php foreach ($allServices as $serv) : ?>
<option value="<?= $serv["service_id"] ?>"><?= $serv["service_name"] ?> </option>
        <?php endforeach; ?>
    </select>
</td>
<td class="link" style="max-width: 450px;">
    <input style="width:100%;" class="form-control" value="<?= $service->name ?>" name="service_name_array[<?= $i ?>]" type="text">
</td>
<td><?= $service->type ?>
    <input type="hidden" value="<?= $service->type ?>" name="service_type_array[<?= $i ?>]">
</td>
<td>
    <?= $provider["api_name"] ?> <div class="grey"> <?= $service->service ?></div>
    <input name="api_service_id_array[<?= $i ?>]" value="<?= $service->service ?>" type="hidden">
</td>
<td class="col-md-1">
    <div class="input-group">
        <input style="width:140px;" class="form-control" value="<?= $finalPrice ?>" name="prices_array[<?= $i ?>]" type="text">
        <div class="input-group-addon">
<label>
<?php echo $settings["site_base_currency"]." "."(".get_currency_symbol_by_code($settings["site_base_currency"]).")";?>
</label>
        </div>
    </div>
<?php 
if($provider["currency"] !== $settings["site_base_currency"]){?>
    <div>Selling Price [+<?= $profit;?>%] : <font color="#FF577F"><b><i><?= "â‰ˆ ".format_amount_string($settings["site_base_currency"],$finalPrice);?></i></b></font></div>
<?php } ?>
<?php 
if($provider["currency"] == $settings["site_base_currency"]){?>
    <div>Selling Price [+<?= $profit;?>%] : <font color="#FF577F"><b><i><?=format_amount_string($settings["site_base_currency"],$finalPrice);?></i></b></font></div>
<?php } ?>

<?php 
if($provider["currency"] == $settings["site_base_currency"]){?>
    <div class="">Seller Cost :
<font color="#10A19D"><b><?php echo format_amount_string($settings["site_base_currency"],from_to(get_currencies_array("all"),$provider["currency"],$settings["site_base_currency"],$SELLER_PRICE));?></b></font>
<?php } ?>
<?php 
if($provider["currency"] !== $settings["site_base_currency"]){?>
    <div class="">Seller Cost :
<font color="#10A19D"><b><?php echo "â‰ˆ ".format_amount_string($settings["site_base_currency"],from_to(get_currencies_array("all"),$provider["currency"],$settings["site_base_currency"],$SELLER_PRICE));?></b></font>
<?php } ?>


</div>
</td>
<td class="col-md-1">

    <input style="width:100%;" class="form-control" value="<?= $service->min ?>" name="min_array[<?= $i ?>]" type="text">

</td>
<td class="col-md-1">
    <input style="width:100%;" class="form-control" value="<?= $service->max ?>" name="max_array[<?= $i ?>]" type="text">
</td>
<td class="col-md-1">
    <textarea style="width:100%;" class="form-control" name="description_array[<?= $i ?>]" type="text"><?= empty($service->desc) ?  $service->description : $service->desc ?></textarea>
</td>
<input type="hidden" value="<?= $service->refill ?>" name="service_refill_array[<?= $i ?>]">
<input type="hidden" value="<?= $provider["id"] ?>" name="service_provider_array[<?= $i ?>]">
<input type="hidden" value="<?= $SELLER_PRICE ?>" name="service_api_prices[<?= $i ?>]">

        </tr>
    <?php $i++;
    endforeach; ?>
<?php endforeach; ?>
        </tbody>
    </form>
</table>



        </div>

</div>


<?php endif; ?>


<div class="modal fade" id="modifyServices" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>
    <h4 class="modal-title" id="myModalLabelAddService">Thêm đăng ký</h4>
</div>
<form action="" method="post" id="modify-service">
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="modify-service-button">Lưu thay đổi</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Hủyl</button>
    </div>

</form>
        </div>
    </div>
</div>
<div class="modal fade" id="modifySubscription" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>
    <h4 class="modal-title" id="myModalLabelAddSubscription">Thêm đăng ký</h4>
</div>
<form action="" method="post" id="modify-subscription">
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="modify-service-button">Lưu thay đổi</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
    </div>

</form>
        </div>
    </div>
</div>
<div class="modal fade" id="modifyCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>
    <h4 class="modal-title" id="modifyCategoryLabel">Sửa danh mục</h4>
</div>
<form action="" method="post" id="modify-category">
    <div class="modal-body">
        <div class="alert alert-danger hide" id="modify-category-error"></div>
        <div class="form-group">
<label class="form-group__service-name">Tên danh mục <span class="badge">Tiếng việt</span></label>
<input name="name-_en" class="form-control" value="Twitter" type="text">
        </div>
        <div class="form-group">
<label>Khả năng hiển thị</label>
<select name="visibility" class="form-control">
    <option value="1">Mở</option>
    <option value="0" selected="selected">Tắt</option>
</select>
        </div>
    </div>
    <input name="id" value="1" type="hidden">
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="modify-category-button">Sửa danh mục</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
    </div>
</form>
        </div>
    </div>
</div>
<!-- confirmChange -->
<div class="modal modal-center fade" id="confirmChange_reset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-center" role="document">
        <div class="modal-content">
<form method="post" action="">
    <div class="modal-body text-center">
        <h4>Bạn có chắc chắn muốn thiết lập lại mức giá tùy chỉnh cho tất cả người dùng cho dịch vụ hiện tại không??</h4>
        <input type="hidden" name="service_id" value="0" id="reset_service_id" />
        <input type="hidden" name="reset_all_price_service" value="reset_all_price_service" />
        <div align="center">
<button class="btn btn-primary">Có</button>
<button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
        </div>
    </div>
</form>
        </div>
    </div>
</div>
<!-- confirmChange -->
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-center" role="document">
        <div class="modal-content">
<div class="modal-body text-center">
    <h4>Bạn có chắc chắn muốn thiết lập lại mức giá tùy chỉnh cho tất cả người dùng cho dịch vụ hiện tại không??</h4>
    <div align="center">
        <a class="btn btn-primary" href="" id="confirmYes">Có</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
    </div>
</div>
        </div>
    </div>
</div>
<div class="modal fade" id="editDescription" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Ã—</span></button>
    <h4 class="modal-title" id="myModalLabel">Sửa mô tả</h4>
</div>
<div id="editdescriptionBody"></div>
        </div>
    </div>
</div>
<!-- confirmChangeBulk -->
<div class="modal fade" id="confirmChangeBulk" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-yesno" role="document">
        <div class="modal-content">
<div class="modal-body">
    <div class="m-b" align="center">
        <h4 class="m-t-0">Bạn có chắc chắn không??</h4>
    </div>
    <div align="center">
        <a class="btn btn-primary" onclick="map_services()" id="confirmYesBulk">Có</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
    </div>
</div>
        </div>
    </div>
</div>


</div>

<?php include('footer.php') ?>

<script>
    function change_display_order(num) {
        //var new_href = $('#').attr('href')

        var value = num.value;

        var type = num.options[num.selectedIndex].getAttribute('data-option');
        var increased_type = parseInt(type) + 1;
        if (isNaN(increased_type)) {
$("#add_service_display_order").val(2);
        } else {
$("#add_service_display_order").val(increased_type);
        }

        //console.log(value);
        console.log(type);

    }



    //refill_content_div
    //ajax_refill_details.php
    function refill_options(val) {
        if (val == 1) {
var api_selector = document.getElementsByName("api_id")[0];
var api_id = api_selector.options[api_selector.selectedIndex].value;
//console.log(api_id);

$("#refill_content_div").html('<img src="/ajax-loader.gif" border="0" alt="loading">');
var wurl = window.location.href;
var post_data = {
    method: "details",
    type: "0",
    uri: wurl,
    provider_id: api_id
};
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        //alert();
        document.getElementById("refill_content_div").innerHTML = this.responseText;
        //$("#subsserviceListContent").html(data);
    }
};
xhttp.open("POST", "ajax_refill_details", true);
xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
xhttp.send(JSON.stringify(post_data));
        } else {
$("#refill_content_div").html('');
        }


    }

    function refill_send_amount_func(val) {
        //console.log(val);
        if (val == 1) {
document.getElementById("refill_default_amount_div").style.display = "none";
        } else {
document.getElementById("refill_default_amount_div").style.display = "block";
        }
    }
</script>
<script>
    if ((self.parent && !(self.parent === self)) &&
        (self.parent.frames.length != 0)) {
        self.parent.location = document.location
    }





    $("#priceService").on('keyup', function() {
        var search = $(this).val();
        var filter = search.toUpperCase();

        $("table tbody tr").filter(function() {
var name = $(this).attr("data-name");
if (name.toUpperCase().indexOf(filter) > -1) {
    $(this).show();
} else {
    $(this).hide();
}
        });
    });

function map_categories() {

  $("#bulkStatus").val("1");

  $("#confirmChangeBulk").modal("show");

  return false;
}

function map_services() {

  document.getElementById("confirmYesBulk").disabled = true;

  let import_action = $("#importValue").val();

  if (import_action == "categories") {
    var frm = $("#changebulkForm");
    var formData = JSON.stringify(frm.serialize());
    $("#hidden_data").attr("value", formData);
    frm.submit();
  } else if (import_action == "services") {
    var frm = $("#changebulkForm");

    // console.log(frm.serializeArray());

    var formData = JSON.stringify(frm.serializeArray());
    $("#services_data").attr("value", formData);
    var frm2 = $("#servicesAddForm");
    frm2.submit();
  } else {
  }


  return false;
}



</script>

 <script src="public/admin/main.js"></script>