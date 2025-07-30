<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}
$symbol = $currency['symbol'];
$currency_value = $currency['value'];
$action = htmlspecialchars($_POST["action"]);
$discount_percent = $user["discount_percentage"]/100;
if ($action == "services_list"):

$category = $_POST["category"];

$_SESSION["selected_category_id"] = $category;

$services = $conn->prepare("SELECT * FROM services WHERE category_id=:c_id && service_type=:type && service_deleted=:deleted ORDER BY service_line ");
$services->execute(array('c_id' => $category, 'type' => 2,"deleted" => 0));
$services = $services->fetchAll(PDO::FETCH_ASSOC);
if ($services):
$serviceList = "";
else:
$serviceList = "<option value='0'>Không tìm thấy dịch vụ trong danh mục này</option>";
endif;



$i = 0;
foreach ($services as $service) {
    
$serviceNameLang = $service["name_lang"];

$serviceNameLang = json_decode($serviceNameLang, 1);

if(!$serviceNameLang[$user["lang"]]){
    $service_name = $service["service_name"];
} else {
   $service_name = $serviceNameLang[$user["lang"]];
}
    
$price = service_price($service["service_id"]);
$price = ($price - ($price * $discount_percent));
$final_service_price = format_amount_string($user["currency_type"],from_to(get_currencies_array("enabled"),$settings["site_base_currency"],$user["currency_type"],$price));
$search = $conn->prepare("SELECT * FROM clients_service WHERE service_id=:service && client_id=:c_id && services_icon=:sv_icon ");
$search->execute(array("service" => $service["service_id"], "c_id" => $user["client_id"],"sv_icon" => $service["services_icon"]));
if ($service["service_secret"] == 2 || $search->rowCount()):
$serviceList.= "<option data-content=\"".htmlentities("<span class=\"badge badge-secondary style-text-primary badge-rounded\">".$service["service_id"]."</span><span class=\"p-2\">".$service["services_icon"]."</span>")." ".$service_name . " - <b>" .$final_service_price."</b>\" value='" . $service['service_id'] . "' ";
if($_SESSION["selected_service_id"] == $service["service_id"] && $i != 0):
  $serviceList.= "selected";
elseif ($i == 0):
$serviceList.= "selected";
endif;

$serviceList.= "></option>";
$i++;
endif;
}
    echo json_encode(['services' => $serviceList]);
elseif ($action == "service_detail"):
    $s_id = $_POST["service"];
    $_SESSION["selected_service_id"] = $s_id;
    $service = $conn->prepare("SELECT * FROM services WHERE service_id=:s_id AND service_deleted=:deleted");
    $service->execute(array('s_id' => $s_id,"deleted" => 0));
    $service = $service->fetch(PDO::FETCH_ASSOC);
    $service["service_price"] = service_price($service["service_id"]);
    $service["service_price"] = ($service["service_price"]  - ($service["service_price"]  * $discount_percent));
    $serviceDetails = "";
    
    $multiDescription = $service["description_lang"];
    $multiDescription = json_decode($multiDescription,1);
    
    if($multiDescription[$user["lang"]]){
      $description = $multiDescription[$user["lang"]];
    } else {
       $description = $service["service_description"];
    }
    
    if (!empty($description)):
        $description = str_replace("\n", "<br />", $description);
        $serviceDetails.= '<div class="form-group fields" id="description">
              <label for="service_description" class="control-label">Mô tả</label>
              <div class="panel-body border-solid border-rounded" id="service_description">
              ' . $description . '
              </div>
            </div>';
    endif;

$s_id = $_POST["service"];
    $service = $conn->prepare("SELECT * FROM services WHERE service_id=:s_id AND service_deleted=:deleted");
    $service->execute(array('s_id' => $s_id,"deleted" => 0));
    $service = $service->fetch(PDO::FETCH_ASSOC);
    $service["service_price"] = service_price($service["service_id"]);
   $service["service_price"] = ($service["service_price"] - ($service["service_price"] * $discount_percent));
    
        $time    = str_replace("\n","<br />",$service["time"]);


if($settings["services_average_time"]){
        $serviceDetails.= '<div class="form-group fields" id="description">
              
			  <label class="control-label"  for="service_description" class="control-label"><span>Thời gian trung bình</span>
                                                    <span class="ml-1 mr-1 fa fa-exclamation-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Thời gian trung bình dựa trên 10 đơn hàng hoàn thành gần nhất trên 1000 số lượng."></span>
                                               </label>
			  <div class="panel-body border-solid border-rounded" id="service_description">
              '.$time.'
              </div>
</div>
            </div>';
}
      


    if ($service["service_package"] == 1 || $service["service_package"] == 2 || $service["service_package"] == 3 || $service["service_package"] == 4):
        if ($service["want_username"] == 2):
            $link_type = 'Username';
        else:
            $link_type = 'Link';
        endif;
        $serviceDetails.= '<div class="form-group fields" id="order_link">
                <label class="control-label" for="field-orderform-fields-link">' . $link_type . '</label>
                <input class="form-control" name="link" value="' . $_SESSION["data"]["link"] . '" type="text" id="field-orderform-fields-link">
              </div>';
    endif;
    if ($service["service_package"] == 1):
        $serviceDetails.= '<div class="form-group fields" id="order_quantity">
                  <label class="control-label" for="field-orderform-fields-quantity">Số lượng</label>
                  <input class="form-control" name="quantity" value="' . $_SESSION["data"]["quantity"] . '" type="text" id="neworder_quantity">
              </div>
              <small class="help-block min-max">Tối thiểu: ' . $service["service_min"] . ' - Tối đa: ' . $service["service_max"] . '</small>
              ';
    endif;
    if ($service["service_package"] == 11 || $service["service_package"] == 12 || $service["service_package"] == 13 || $service["service_package"] == 14 || $service["service_package"] == 15):
        $serviceDetails.= '<div class="form-group fields" id="order_link">
                <label class="control-label" for="field-orderform-fields-link">Tên người dùng</label>
                <input class="form-control" name="username" value="' . $_SESSION["data"]["username"] . '" type="text" id="field-orderform-fields-link">
              </div>';
    endif;
    if ($service["service_package"] == 3):
        $serviceDetails.= '<div class="form-group fields" id="order_quantity">
              <label class="control-label" for="field-orderform-fields-quantity">Số lượng</label>
              <input class="form-control" name="quantity" value="" type="text" id="neworder_quantity" disabled="">
          </div>
          <small class="help-block min-max">Tối thiểu: ' . $service["service_min"] . ' - Tối đa: ' . $service["service_max"] . '</small>
          ';
    endif;
    if ($service["service_package"] == 11 || $service["service_package"] == 12 || $service["service_package"] == 13):
        $serviceDetails.= '<div class="form-group fields" id="order_link">
                <label class="control-label" for="field-orderform-fields-link">Bạn muốn giới hạn bao nhiêu bài viết?</label>
                <input class="form-control" name="posts" value="' . $_SESSION["data"]["posts"] . '" type="text" id="field-orderform-fields-link">
              </div>';
        $serviceDetails.= '<div class="form-group fields" id="order_min">
              <label class="control-label" for="order_count">Số lượng</label>
              <div class="row">
                  <div class="col-xs-6">
                      <input type="text" class="form-control" id="order_count" name="min" value="' . $_SESSION["data"]["min"] . '" placeholder="Minimum">
                  </div>
                  <div class="col-xs-6">
                      <input type="text" class="form-control" id="order_count" name="max" value="' . $_SESSION["data"]["max"] . '" placeholder="Maximum">
                  </div>
              </div>
              <small class="help-block min-max">Tối thiểu: ' . $service["service_min"] . ' - Tối đa: ' . $service["service_max"] . '</small>
          </div>
          <div class="form-group fields" id="order_delay">
              <div class="row">
                  <div class="col-xs-6">
                      <label class="control-label" for="field-orderform-fields-delay">Bạn muốn trì hoãn đơn hàng trong bao lâu??</label>
                      <select class="form-control" name="delay" id="field-orderform-fields-delay">
                          <option value="0" ';
        if ($_SESSION["data"]["delay"] == 0):
            $serviceDetails.= ' selected';
        endif;
        $serviceDetails.= '>Không chậm trễ</option>
                          <option value="300" ';
        if ($_SESSION["data"]["delay"] == 300):
            $serviceDetails.= ' selected';
        endif;
        $serviceDetails.= '>5 Phút</option>
                          <option value="600" ';
        if ($_SESSION["data"]["delay"] == 600):
            $serviceDetails.= ' selected';
        endif;
        $serviceDetails.= '>10 Phút</option>
                          <option value="900" ';
        if ($_SESSION["data"]["delay"] == 900):
            $serviceDetails.= ' selected';
        endif;
        $serviceDetails.= '>15 Phút</option>
                          <option value="1800" ';
        if ($_SESSION["data"]["delay"] == 1800):
            $serviceDetails.= ' selected';
        endif;
        $serviceDetails.= '>30 Phút</option>
                          <option value="3600" ';
        if ($_SESSION["data"]["delay"] == 3600):
            $serviceDetails.= ' selected';
        endif;
        $serviceDetails.= '>60 Phút</option>
                          <option value="5400" ';
        if ($_SESSION["data"]["delay"] == 5400):
            $serviceDetails.= ' selected';
        endif;
        $serviceDetails.= '>90 Phút</option>
                      </select>
                  </div>
                  <div class="col-xs-6">
                      <label for="field-orderform-fields-expiry">Ngày kết thúc</label>
                      <div class="input-group" id="datetimepicker">
                          <input class="form-control datetime" name="expiry" id="expiryDate" value="' . $_SESSION["data"]["expiry"] . '" type="text" autocomplete="off">
                          <span class="input-group-btn">
                              <button class="btn btn-default clear-datetime" id="clearExpiry" type="button"> <span class="fa fa-trash-o"></span></button>
                          </span>
                      </div>
                  </div>
              </div>
          </div>';
    endif;
    if ($service["service_package"] == 3 || $service["service_package"] == 4):
        $serviceDetails.= '<div class="form-group fields" id="order_comment">
              <label class="control-label">Comments</label>
              <textarea class="form-control counter" name="comments" id="neworder_comment" cols="30" rows="10" data-related="quantity">' . $_SESSION["data"]["comments"] . '</textarea>
          </div>';
    endif;
    if ($service["service_dripfeed"] == 2):
        if ($_SESSION["data"]["check"]):
            $check = "checked";
        endif;
        $serviceDetails.= '<div id="dripfeed">
                <div class="form-group fields" id="order_check">
                    <label class="control-label has-depends " for="dripfeedcheckbox">
                        <input name="check" value="1" type="checkbox" ' . $check . ' id="dripfeedcheckbox">
                        Drip-feed Order
                    </label>
                    <div class="hidden" id="dripfeed-options">
                        <div class="form-group">
                            <label class="control-label" for="dripfeed-runs">Quá trình này nên được lặp lại bao nhiêu lần?</label>
                            <input class="form-control" name="runs" value="' . $_SESSION["data"]["runs"] . '" type="text" id="dripfeed-runs">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="dripfeed-interval">Khoảng cách bao nhiêu phút? (60*24 = 1440 for daily shipping)</label>
                            <input class="form-control" name="interval" value="' . $_SESSION["data"]["interval"] . '" type="text" id="dripfeed-interval">
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="dripfeed-totalquantity">Tổng số lượng cần gửi</label>
                            <input class="form-control" name="total_quantity" value="' . $_SESSION["data"]["total_quantity"] . '" type="text" id="dripfeed-totalquantity" readonly="">
                        </div>
                    </div>
                </div>
            </div>
            ';
    endif;
    $runs = $_POST["runs"];
    if (!$runs):
        $runs = 1;
    endif;
    $dripfeed = $_POST["dripfeed"];
    $quantity = $_POST["quantity"];
    if ($s_id != 0 && $dripfeed == "bos"):
 $price = $quantity * $service["service_price"] / 1000;
 $price = ($price - ($price * $discount_percent));
$data = [
'details' => $serviceDetails,
'price' => format_amount_string($user["currency_type"],from_to(get_currencies_array("enabled"),$settings["site_base_currency"],$user["currency_type"],$price))

];
    elseif ($s_id != 0 && $dripfeed == "var"):
$price = $runs * $quantity * $service["service_price"] / 1000;
$price = ($price - ($price * $discount_percent));
$data = [
'details' => $serviceDetails,
'price' => format_amount_string($user["currency_type"],from_to(get_currencies_array("enabled"),$settings["site_base_currency"],$user["currency_type"],$price))
];
    elseif ($s_id != 0 && !isset($dripfeed)):
$price = $service["service_price"];
$price = ($price - ($price * $discount_percent));
$data = [
'details' => $serviceDetails, 
'price' => format_amount_string($user["currency_type"],from_to(get_currencies_array("enabled"),$settings["site_base_currency"],$user["currency_type"],$price))

];
    else:
        $data = ['empty' => 1];
    endif;
    if ($service["service_package"] == 11 || $service["service_package"] == 12 || $service["service_package"] == 13):
        $data["sub"] = 1;
    endif;
    echo json_encode($data);
    unset($_SESSION["data"]);
elseif ($action == "service_price"):
    $service = $_POST["service"];
    $quantity = $_POST["quantity"];
    $comments = $_POST["comments"];
    $dripfeed = $_POST["dripfeed"];
    $runs = $_POST["runs"];
    if (!$runs):
        $runs = 1;
    endif;
$price = service_price($service) / 1000 ;
$price = ($price - ($price * $discount_percent));

if ($comments):
$quantity = count(explode("\n", $comments));
endif;

if($quantity == 0) {
$totalPrice = get_currency_symbol_by_code($user["currency_type"])." ".service_price($service)*0;

} elseif ($dripfeed == "var") {
$totalPrice = $price * $quantity * $runs;
$totalPrice = format_amount_string($user["currency_type"],from_to(get_currencies_array("enabled"),$settings["site_base_currency"],$user["currency_type"],$totalPrice));
$totalPrice.= '';
    } else {
$totalPrice = $price * $quantity;;
$totalPrice = format_amount_string($user["currency_type"],from_to(get_currencies_array("enabled"),$settings["site_base_currency"],$user["currency_type"],$totalPrice));
$totalPrice.= '';
    }
    echo json_encode(['price' => $totalPrice, 'commentsCount' => $quantity, 'totalQuantity' => $runs * $quantity]);


endif;


