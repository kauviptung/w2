<?php include 'header.php';?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-BhE9vaZbc5R4EdfPAMhrTRmnDCDpQ2ENMewMx+na8zU8kcZGsip3GpPQXFlA7qpp+aGT6pW4W3DBRMb1OAsdPQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container-fluid">
    <ul class="nav nav-tabs nav-tabs__service">
        <br>
        <li class="p-b"><button class="btn btn-default" data-toggle="modal" data-target="#modalDiv" data-action="new_service">Dịch vụ mới</button></li>
        <li class="p-b"><button class="btn btn-default m-l" data-toggle="modal" data-target="#modalDiv" data-action="new_category">Danh mục mới</button></li>
        <li class="p-b">
        <a class="btn btn-danger m-l" href="<?= site_url('admin/api-services/delete_all_categories') ?>">Xóa tất cả Danh mục</a></li>

<li class="pull-right">
<a class="btn btn-primary" href="<?= site_url('admin/api-services') ?>"><i class="fas fa-plus-circle"></i> Nhập dịch vụ API</a>
        </li>




<li class="pull-right">
<div class="form-inline">
<label for="service-search-input" class="service-search__icon"></label>
<input class="form-control" placeholder="Search" id="priceService" type="text" value="">
</div>
</li>
    </ul>
    <ul></ul>
    <div class="services-table">
        <div class="sticker-head">
            <table class="service-block__header" id="sticker">
                <thead>
<th class="checkAll-th service-block__checker null">
    <div class="checkAll-holder">
        <input type="checkbox" id="checkAll">
        <input type="hidden" id="checkAllText" value="order">
    </div>
    <div class="action-block">
        <ul class="action-list">
            <li><span class="countOrders"></span> Chọn dịch vụ</li>
            <li>
                <div class="dropdown">
<button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Hoạt động hàng loạt<span class="caret"></span></button>
<ul class="dropdown-menu">
    <li>
        <a class="bulkorder" data-type="active">Bật các Dịch vụ đã chọn</a>
        <a class="bulkorder" data-type="deactive">Tắt các Dịch vụ đã chọn</a>
        <a class="bulkorder" data-type="secret">Làm cho các dịch vụ được chọn trở nên bí mật</a>
        <a class="bulkorder" data-type="desecret">Xóa Đã chọn khỏi Dịch vụ Bí mật</a>
        <a class="bulkorder" data-type="del_price">Xóa các dịch vụ đã chọn Giá tùy chỉnh</a>
        <a class="bulkorder" data-type="del_service">Xóa các dịch vụ đã chọn</a>
        <a class="bulkorder" data-type="refill-active">Nạp lại Kích hoạt các dịch vụ đã chọn</a>
        <a class="bulkorder" data-type="refill-inactive">Nạp lại Vô hiệu hóa các dịch vụ đã chọn</a>
        <a class="bulkorder" data-type="cancel-active">Hủy bỏ Kích hoạt các dịch vụ đã chọn</a>
        <a class="bulkorder" data-type="cancel-inactive">Hủy Bỏ Vô Hiệu Hóa Các Dịch Vụ Đã Chọn</a>
    </li>
</ul>
                </div>
            </li>
        </ul>
    </div>
</th>
<th class="service-block__id">ID</th>
<th class="service-block__service">Dịch vụ</th>
<th>Loại dịch vụ</th>
<th class="service-block__minorder">Bảo hành</th>
<th class="service-block__minorder">Đã hủy</th>
<th class="service-block__provider">Nhà cung cấp</th>
<th class="service-block__rate">Giá tiền</th>
<th class="service-block__minorder">Tối thiểu</th>
<th class="service-block__minorder">Tối đa</th>
<th class="service-block__visibility">Trạng thái</th>
<th class="service-block__action text-right"><span id="allServices" class="service-block__hide-all fa fa-compress"></span></th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="service-block__body">
        <div class="service-block__body-scroll">
            <div style="width: 100%; height: 0px;"></div>


<form action="<?php echo site_url("admin/services/multi-action") ?>" method="post" id="changebulkForm">
<div style="" class="category-sortable">
<?php $c = 0; foreach ($serviceList as $category => $services): $c++; ?>
<div class="categories" data-id="<?=$services[0]["category_id"] ?>">
    <div class="<?php if ($services[0]["category_type"] == 1): echo 'grey'; endif; ?>  service-block__category ">
        <div class="service-block__category-title" class="categorySortable" data-category="<?=$category ?>" id="category-<?=$c ?>">
            <div class="service-block__drag handle">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
<title>Drag-Handle</title>
<path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path>
                </svg>
            </div>
<?php if ($services[0]["category_secret"] == 1): echo '<small data-toggle="tooltip" data-placement="top" title="" data-original-title="gizli kategori"><i class="fa fa-lock"></i></small> '; endif; 

if($services[0]["category_type"] == 2){
echo '<span data-post="category_id='.$services[0]["category_id"].'" class="category-visibility category-visible"></span>';
} 
if($services[0]["category_type"] == 1){
echo '<span data-post="category_id='.$services[0]["category_id"].'" class="category-visibility category-invisible"></span>';
} 

$category_icon_array = json_decode($services[0]["category_icon"],true);

$category_icon_type = $category_icon_array["icon_type"];

if($category_icon_type == "image"){

$icon = "<img style=\"margin-right:10px;\" src=\"".$images[$category_icon_array["image_id"]][0]["link"]."\" class=\"img-responsive btn-group-vertical\">";
} elseif($category_icon_type == "icon"){

$icon = "<i style=\"margin-right:10px;font-size:18px;\" class=\"".$category_icon_array["icon_class"]."\" aria-hidden=\"true\"></i>";
} else {
    $icon = "";
    
}


echo '<span class="category-name">'.$icon.$category.'</span>'; ?>
<span style="margin-left:10px;margin-right:10px;font-weight:bold;">|</span>

<a style="margin-right:15px;" class="dcs-pointer" data-toggle="modal" data-target="#modalDiv" data-action="edit_category" data-id="<?=$services[0]["category_id"]?>"><i class="fas fa-pen"></i></a>
<a class="text-danger" href="<?php echo site_url("admin/services/del_category/".$services[0]["category_id"]) ?>" data-action="del_category"><i class="fas fa-trash"></i></a>

<?php if (!empty($services[0]["service_id"])): ?>

            <div class="service-block__collapse-block">
                <div id="collapedAdd-<?=$c ?>" class="service-block__collapse-button" data-category="category-<?=$c ?>"></div>
            </div>
            <?php endif; ?>
        </div>
        <div class="collapse in">
            <div class="service-block__packages">
                <table id="servicesTableList" class="Servicecategory-<?=$c ?>">
<tbody class="service-sortable">
    <div class="serviceSortable" id="Servicecategory-<?=$c ?>" data-id="category-<?=$c ?>">
        <?php for ($i = 0; $i < count($services); $i++): 
      if($services[$i]["service_deleted"] == 0):
        $api_detail = json_decode($services[$i]["api_detail"], true); ?>
        <tr id="serviceshowcategory-<?=$c ?>" class="ui-state-default <?php if ($services[$i]["service_type"] == 1): echo "grey"; endif; ?>" data-category="category-<?=$c ?>" data-id="service-<?php echo $services[$i]["service_id"] ?>" data-service="<?php echo $services[$i]["service_name"] ?>">
<?php if (!empty($services[0]["service_id"])): ?>
<td class="service-block__checker">
<?php if ($services[$i]["api_servicetype"] == 1): echo '<div class="service-block__danger"></div>'; endif; ?>
<span></span>
<div class="service-block__checkbox">
    <div class="service-block__drag handle">
        <svg>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Drag-Handle</title>
                <path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path>
            </svg>
        </svg>
    </div>
    <input type="checkbox" class="selectOrder" name="service[<?php echo $services[$i]["service_id"] ?>]" value="1" style="border:1px solid #fff">
</div>
</td>

<td class="service-block__id"><?php echo $services[$i]["service_id"] ?></td>
<td class="service-block__service"><span><?php echo $services[$i]["services_icon"] ?> </span><?php if ($services[$i]["service_secret"] == 1): echo '<small data-toggle="tooltip" data-placement="top" title="" data-original-title="Secret Service"><i class="fa fa-lock"></i></small> '; endif; echo $services[$i]["service_name"]; ?></td>
<td width="10%"><?php echo servicePackageType($services[$i]["service_package"]); ?><?php if ($services[$i]["time"] != "Not enough data"): ?><div class="tooltip5">
&nbsp;<i class="fas fa-clock"></i><span class="tooltiptext5"><?php echo $services[$i]["time"]; ?> </span>
</div>
<?php endif; ?><?php if ($services[$i]["show_refill"] == "true"): echo '<div  class="tooltip5">&nbsp;<i class="fas fa-sync"></i></span><span class="tooltiptext5" >Nút bảo hành đã bật</span></div>'; endif; ?>
<?php if ($services[$i]["cancelbutton"] == 1): echo '<div  class="tooltip5">&nbsp;<i  class="fas fa-ban"></i></span><span class="tooltiptext5" >Hủy nút Bảo hành</span></div>'; endif; ?>

</td>
<?php if ($services[$i]["show_refill"] == "true"): $type = "refill-deactive"; else : $type = "refill-active"; endif; ?>

<td class="service-block__minorder"> <a href="<?php echo site_url("admin/services/".$type."/".$services[$i]["service_id"]) ?>"> <?php if ($services[$i]["show_refill"] == "false"): echo "Tắt"; else : echo "Mở"; endif; ?></a></td>

<?php if ($services[$i]["cancelbutton"] == 2): $type = "cancelbutton-active"; else : $type = "cancelbutton-deactive"; endif; ?>
<td class="service-block__minorder"> <a href="<?php echo site_url("admin/services/".$type."/".$services[$i]["service_id"]) ?>"> <?php if ($services[$i]["cancelbutton"] == "2"): echo "Tắt"; else : echo "Mở"; endif; ?></a></td>


<td class="service-block__provider"><?php if ($services[$i]["service_api"] != 0): echo $services[$i]["api_name"]." <span class=\"badge badge-secondary\">".$services[$i]["currency"]."</span><br><span class=\"label label-api\">".$services[$i]["api_service"]."</span>"; else : echo "Thủ công"; endif; ?></td>

<td class="service-block__rate">
<?php
$api_price = $api_detail["rate"];
?>
<div style="width:100px;<?php if (!$api_detail["rate"]): echo "Empty"; elseif ($services[$i]["service_api"] != 0 && from_to(get_currencies_array("all"), $settings["site_base_currency"], "INR", $services[$i]["service_price"]) > from_to(get_currencies_array("enabled"), $services[$i]["currency"], "INR", $api_price)):
    echo "color: #38E54D;";
    elseif ($services[$i]["service_api"] != 0 && from_to(get_currencies_array("all"), $settings["site_base_currency"], "INR", $services[$i]["service_price"]) < from_to(get_currencies_array("all"), $services[$i]["currency"], "INR", $api_price)):
echo "color: #D2001A;";elseif($services[$i]["service_api"] != 0 && from_to(get_currencies_array("all"), $settings["site_base_currency"], "INR", $services[$i]["service_price"]) == from_to(get_currencies_array("all"), $services[$i]["currency"], "INR", $api_price)):
echo "color: #FFB200;";
endif;?>">
    <?php if ($settings["site_base_currency"] !== $services[$i]["currency"]) {
        echo "≈ ".format_amount_string($settings["site_base_currency"], $services[$i]["service_price"]);
    } elseif ($settings["site_base_currency"] == $services[$i]["currency"]) {
        echo format_amount_string($settings["site_base_currency"], $services[$i]["service_price"]);
    }
    ?>
</div>
<div class="service-block__provider-value">
    <?php if ($services[$i]["service_api"] != 0 && $api_detail["rate"]):
    if ($settings["site_base_currency"] !== $services[$i]["currency"]) {
        echo "≈ ".format_amount_string($settings["site_base_currency"], from_to(get_currencies_array("all"), $services[$i]["currency"], $settings["site_base_currency"], $api_detail["rate"]));
    } elseif ($settings["site_base_currency"] == $services[$i]["currency"]) {

        echo format_amount_string($settings["site_base_currency"], from_to(get_currencies_array("all"), $services[$i]["currency"], $settings["site_base_currency"], $api_detail["rate"]));

    }
    endif; ?>
</div>
</td>
<td class="service-block__minorder">
<div>
    <?php echo $services[$i]["service_min"] ?>
</div>
<?php if ($services[$i]["service_api"] != 0): echo '<div class="service-block__provider-value">'.$api_detail["min"].'</div>'; endif; ?>
</td>
<td class="service-block__minorder">
<div>
    <?php echo $services[$i]["service_max"] ?>
</div>
<?php if ($services[$i]["service_api"] != 0): echo '<div class="service-block__provider-value">'.$api_detail["max"].'</div>'; endif; ?>
</td>
<td class="service-block__visibility"><?php if ($services[$i]["service_type"] == 1): echo "Không hoạt động"; else : echo "Hoạt động"; endif; ?> <?php if ($services[$i]["api_servicetype"] == 1): echo '<span class="text-danger" title="Nhà cung cấp dịch vụ đã xóa dịch vụ"><span class="fa fa-exclamation-circle"></span></span>'; endif; ?> </td>
<td class="service-block__action">
<div class="dropdown pull-right">
    <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Tùy chọn <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <li><a style="cursor:pointer;" data-toggle="modal" data-target="#modalDiv" data-action="edit_service" data-id="<?=$services[$i]["service_id"] ?>">Sửa dịch vụ</a></li>
        <li><a style="cursor:pointer;" data-toggle="modal" data-target="#modalDiv" data-action="edit_service_name" data-id="<?=$services[$i]["service_id"] ?>">Sửa tên Dịch vụ</a></li>
        <li><a style="cursor:pointer;" data-toggle="modal" data-target="#modalDiv" data-action="edit_description" data-id="<?=$services[$i]["service_id"] ?>">Sửa mô tả</a></li>
        <li><a style="cursor:pointer;" data-toggle="modal" data-target="#modalDiv" data-action="edit_time" data-id="<?=$services[$i]["service_id"] ?>">Sửa thời gian trung bình</a></li>
        <?php if ($services[$i]["service_type"] == 1): $type = "service-active"; else : $type = "service-deactive"; endif; ?>
        <li><a href="<?php echo site_url("admin/services/".$type."/".$services[$i]["service_id"]) ?>">Service <?php if ($services[$i]["service_type"] == 1): echo "Kích hoạt"; else : echo "Hủy kích hoạt"; endif; ?></a></li>

        <?php if ($services[$i]["show_refill"] == "true"): $type = "refill-deactive"; else : $type = "refill-active"; endif; ?>
        <li><a href="<?php echo site_url("admin/services/".$type."/".$services[$i]["service_id"]) ?>">Bảo hành <?php if ($services[$i]["show_refill"] == "true"): echo "Hủy kích hoạt"; else : echo "Kích hoạt"; endif; ?></a></li>

        <?php if ($services[$i]["cancelbutton"] == 2): $type = "cancelbutton-active"; else : $type = "cancelbutton-deactive"; endif; ?>
        <li><a href="<?php echo site_url("admin/services/".$type."/".$services[$i]["service_id"]) ?>">Nút hủy <?php if ($services[$i]["cancelbutton"] == 1): echo "Hủy kích hoạt"; else : echo "Kích hoạt"; endif; ?></a></li>


        <li><a href="<?php echo site_url("admin/services/delete/".$services[$i]["service_id"]) ?>">Xóa dịch vụ</a></li>
    </ul>
</div>
</td>
<?php endif; ?>
        </tr>
        <?php 
        endif;
        endfor; ?>
    </div>
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php
$services = $conn->prepare("SELECT * FROM services LEFT JOIN service_api ON service_api.id = services.service_api WHERE services.category_id=:c_id ORDER BY services.service_line ASC ");
$services -> execute(array("c_id" => 0));
$services = $services->fetchAll(PDO::FETCH_ASSOC);
if ($services):
?>
<div class="service-block__category ">
    <div class="service-block__category-title" class="categorySortable" data-category="notcategory" id="category-0">
        Uncategorized
        <div class="service-block__collapse-block">
            <div id="collapedAdd-0" class="service-block__collapse-button" data-category="category-0"></div>
        </div>
    </div>
    <div class="collapse in">
        <div class="service-block__packages">
            <table id="servicesTableList" class="Servicecategory-0">
                <tbody class="service-sortable">
<div class="serviceSortable" id="Servicecategory-0" data-id="category-0">
    <?php foreach ($services as $service): $api_detail = json_decode($service["api_detail"], true); ?>
    <tr id="serviceshowcategory-0" class="ui-state-default <?php if ($service["service_type"] == 1): echo "grey"; endif; ?>" data-category="category-0" data-id="service-<?php echo $service["service_id"] ?>" data-service="<?php echo mb_convert_encoding($service["service_name"], "UTF-8", "UTF-8") ?>">
        <td class="service-block__checker">
<!-- <div class="service-block__danger"></div> //Servis diğer sitede pasifse burayı aktif et-->
<span></span>
<div class="service-block__checkbox">
<div class="service-block__drag handle">
    <svg>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <title>Drag-Handle</title>
            <path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path>
        </svg>
    </svg>
</div>
<input type="checkbox" class="selectOrder" name="service[<?php echo $service["service_id"] ?>]" value="1" style="border:1px solid #fff">
</div>
        </td>
        <td class="service-block__id"><?php echo $service["service_id"] ?></td>
       
        <td class="service-block__service"><?php echo $service["services_icon"] ?><?php if (mb_convert_encoding($service["service_secret"], "UTF-8", "UTF-8") == 1): echo '<small data-toggle="tooltip" data-placement="top" title="" data-original-title="Secret service"><i class="fa fa-lock"></i></small> '; endif; echo mb_convert_encoding($service["service_name"], "UTF-8", "UTF-8"); ?></td>
        <td class="service-block__type" nowrap=""><?php echo servicePackageType($service["service_package"]); ?></td>
        <td class="service-block__provider"><?php if ($service["service_api"] != 0): echo $service["api_name"]; else : echo "Thủ công"; endif; ?></td>
        <td class="service-block__rate">
<?php
if ($api_detail["currency"] == "USD"):
$api_price = $api_detail["rate"];
endif;
?>
<div style="<?php if ($service["service_api"] != 0 && $service["service_price"] > $api_price): echo "color: green"; elseif ($service["service_api"] != 0 && $service["service_price"] < $api_price): echo "color: red"; endif ?>">
<?php echo $service["service_price"] ?>
</div>
<?php if ($service["service_api"] != 0): echo '<div class="service-block__provider-value"><i class="fa fa-'.strtolower($api_detail["currency"]).'"></i> '.priceFormat($api_detail["rate"]).'</div>'; endif; ?>
        </td>
        <td class="service-block__minorder">
<div>
<?php echo $service["service_min"] ?>
</div>
<?php if ($service["service_api"] != 0): echo '<div class="service-block__provider-value">'.$api_detail["min"].'</div>'; endif; ?>
        </td>
        <td class="service-block__minorder">
<div>
<?php echo $service["service_max"] ?>
</div>
<?php if ($service["service_api"] != 0): echo '<div class="service-block__provider-value">'.$api_detail["max"].'</div>'; endif; ?>
        </td>
        <td class="service-block__visibility"><?php if ($service["service_type"] == 1): echo "Deactive"; else : echo "Kích hoạt"; endif; ?> </td>
        <td class="service-block__action">
<div class="dropdown pull-right">
<button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">Tùy chọn <span class="caret"></span></button>
<ul class="dropdown-menu">
    <li><a style="cursor:pointer;" data-toggle="modal" data-target="#modalDiv" data-action="edit_service" data-id="<?=$service["service_id"] ?>">Sửa dịch vụ</a></li>
    <li><a style="cursor:pointer;" data-toggle="modal" data-target="#modalDiv" data-action="edit_description" data-id="<?=$service["service_id"] ?>">Sửa mô tả</a></li>
    <?php if ($service["service_type"] == 1): $type = "service-active"; else : $type = "service-deactive"; endif; ?>
    <li><a href="<?php echo site_url("admin/services/".$type."/".$service["service_id"]) ?>">Dịch vụ <?php if ($service["service_type"] == 1): echo "Mở lên"; else : echo "Tắt"; endif; ?></a></li>
    <li><a href="<?php echo site_url("admin/services/del_price/".$service["service_id"]) ?>">Xóa giá</a></li>
    <li><a href="<?php echo site_url("admin/services/delete/".$services[$i]["service_id"]) ?>">Xóa dịch vụ</a></li>
</ul>
</div>
        </td>
    </tr>
    <?php endforeach; ?>
</div>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php endif; ?>
</div>

<input type="hidden" name="bulkStatus" id="bulkStatus" value="-1">
            </form>
        </div>
    </div>
    
</div>



<?php if( $paginationArr["count"] > 1 ): ?>
     <div class="row">
        <div class="col-sm-8">
  <nav>
 <ul class="pagination">
 <?php if( $paginationArr["current"] != 1 ): ?>
  <li class="prev"><a href="<?php echo site_url("admin/services/1".$search_link) ?>">&laquo;</a></li>
  <li class="prev"><a href="<?php echo site_url("admin/services/".$paginationArr["previous"].$search_link) ?>">&lsaquo;</a></li>
  <?php
      endif;
      for ($page=1; $page<=$pageCount; $page++):
        if( $page >= ($paginationArr['current']-9) and $page <= ($paginationArr['current']+9) ):
  ?>
  <li class="<?php if( $page == $paginationArr["current"] ): echo "active"; endif; ?> "><a href="<?php echo site_url("admin/services/".$page.$search_link) ?>"><?=$page?></a></li>
  <?php endif; endfor;
        if( $paginationArr["current"] != $paginationArr["count"] ):
  ?>
  <li class="next"><a href="<?php echo site_url("admin/services/".$paginationArr["next"].$search_link) ?>" data-page="1">&rsaquo;</a></li>
  <li class="next"><a href="<?php echo site_url("admin/services/".$paginationArr["count"].$search_link) ?>" data-page="1">&raquo;</a></li> 
  <?php endif; ?>
 </ul>
  </nav>
        </div>
     </div>
   <?php endif; ?>









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
