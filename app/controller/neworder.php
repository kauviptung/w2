<?php
if (!defined('BASEPATH')) {
  die('Direct access to the script is not allowed');
}


$title .= $languageArray["neworder.title"];

$smmapi = new SMMApi();
$fapi = new socialsmedia_api();


unset($_SESSION["massorder_seen"]);


if ($_SESSION["msmbilisim_userlogin"] != 1 || $user["client_type"] == 1) {
  header("Location:" . site_url('logout'));
}
if ($settings["email_confirmation"] == 1 && $user["email_type"] == 1) {
  header("Location:" . site_url('confirm_email'));
}


$categoriesRows = $conn->prepare("SELECT * FROM categories WHERE category_type=:type  ORDER BY categories.category_line ASC ");


/*$categoriesRows->execute(array("type"=>2));
$categoriesRows = $categoriesRows->fetchAll(PDO::FETCH_ASSOC);

$categories = [];
  foreach ( $categoriesRows as $categoryRow ) {
    $search = $conn->prepare("SELECT * FROM clients_category WHERE category_id=:category && client_id=:c_id ");
    $search->execute(array("category"=>$categoryRow["category_id"],"c_id"=>$user["client_id"]));
    if( $categoryRow["category_secret"] == 2 || $search->rowCount() ):
      $rows     = $conn->prepare("SELECT * FROM services WHERE category_id=:id ORDER BY service_line ASC");
      $rows     ->execute(array("id"=>$categoryRow["category_id"] ));
      $rows     = $rows->fetchAll(PDO::FETCH_ASSOC);
      $services = [];
foreach ( $rows as $row ) {
$s["service_price"] = service_price($row["service_id"]);
$s["service_price"] = ($s["service_price"] - ($s["service_price"] * $discount_percent));
$s["service_id"]    = $row["service_id"];
$multiName   =  json_decode($row["name_lang"],true);
if( $multiName[$user["lang"]] ):
$s["service_name"] = $multiName[$user["lang"]];
else:
$s["service_name"] = $row["service_name"];
endif;
$s["service_min"]   = $row["service_min"];
          $s["service_max"]   = $row["service_max"];
          $search = $conn->prepare("SELECT * FROM clients_service WHERE service_id=:service && client_id=:c_id ");
          $search->execute(array("service"=>$row["service_id"],"c_id"=>$user["client_id"]));
          if( $row["service_secret"] == 2 || $search->rowCount() ):
            array_push($services,$s);
          endif;
        }
      $c["category_name"]          = $categoryRow["category_name"];
      $c["category_id"]            = $categoryRow["category_id"];
      $c["category_icon"]          = $categoryRow["category_icon"];
      $c["services"]               = $services;
      array_push($categories,$c);
    endif;

  }*/
$images = $conn->prepare("SELECT * FROM files");

$images->execute();

$images = $images->fetchAll(PDO::FETCH_ASSOC);
$images = array_group_by($images, "id");
$category_html = "";
$category_html_simplify = "";
$categories1 = $conn->prepare("SELECT * FROM categories WHERE category_type=:type AND category_deleted=:deleted  ORDER BY categories.category_line ASC ");
$categories1->execute(array("type" => 2, "deleted" => 0));
$categories1 = $categories1->fetchAll(PDO::FETCH_ASSOC);
for ($i = 0; $i < count($categories1); $i++) {
  $id = $categories1[$i]["category_id"];
  $name = $categories1[$i]["category_name"];
  $multiCatName = $categories1[$i]["category_name_lang"];
  $multiCatName = json_decode($multiCatName,1);
  if($multiCatName[$user["lang"]]){
      $name = $multiCatName[$user["lang"]];
  }
  if($_SESSION["selected_category_id"] == $id && $i != 0){
   $category_html .= "<option data-content=\"" . icon($images, $id, $name) . "  $name\" value=\"$id\" selected></option>";

    $category_html_simplify .= "<option data-icon=\"" . icon($images, $id, $name) . "\" value=\"$id\" selected>$name</option>";
  } elseif ($i == 0) {
    $category_html .= "<option data-content=\"" . icon($images, $id, $name) . "  $name\" value=\"$id\" selected></option>";
    $category_html_simplify .= "<option data-icon=\"" . icon($images, $id, $name) . "\" value=\"$id\" selected>$name</option>";

  } else {
    $category_html .= "<option data-content=\"" . icon($images, $id, $name) . "  $name\" value=\"$id\"></option>";
    $category_html_simplify .= "<option data-icon=\"" . icon($images, $id, $name) . "\" value=\"$id\" >$name</option>";
  }
}


if ($_POST):

  foreach ($_POST as $key => $value) {
    $_SESSION["data"][$key] = htmlspecialchars($value);
  }



  $discount_percent = $user["discount_percentage"] / 100;
  $ip = GetIP(); // Uye ıp
  $service = htmlspecialchars($_POST["services"]); // Ürün id
  $quantity = htmlspecialchars($_POST["quantity"]); // Sipariş miktarı
  if (!$quantity):
    $quantity = 0;
  endif;
  $link = htmlspecialchars($_POST["link"]); // Sipariş link
  if (substr($link, -1) == "/"):
    $link = substr($link, 0, -1);
  endif;
  $username = htmlspecialchars($_POST["username"]); // abonelik, hangi kullanıcıya olacak
  $posts = htmlspecialchars($_POST["posts"]); // abonelik, kaç gönderiye gitsin
  $delay = htmlspecialchars($_POST["delay"]); // Abonelik, gecikme süresi
  $otoMin = htmlspecialchars($_POST["min"]); // abonelik, minimum miktar
  $otoMax = htmlspecialchars($_POST["max"]); // abonelik, maksimum tutar
  $comments = htmlspecialchars($_POST["comments"]); //custom comments
  $runs = htmlspecialchars($_POST["runs"]); // dripfeed kaç kez gitsin
  if (!$runs):
    $runs = 1;
  endif;
  $interval = htmlspecialchars($_POST["interval"]); // dripfeed gecikme süresi
  $dripfeedon = htmlspecialchars($_POST["check"]); // dripfeed aktif
  $expiry = htmlspecialchars($_POST["expiry"]);
  $expiry = date("Y-m-d", strtotime(str_replace('/', '-', $expiry)));
  $subscriptions = 1;
  $service_detail = $conn->prepare("SELECT * FROM services WHERE service_deleted=:deleted AND service_id=:id");
  $service_detail->execute(array("deleted" => 0, "id" => $service));
  $service_detail = $service_detail->fetch(PDO::FETCH_ASSOC);
  $service_overflow_percentage = $service_detail["service_overflow"];

  $service_overflow = (int) ($service_overflow_percentage / 100 * $quantity);


  if ($service_detail["service_api"] != 0):
    $api_detail = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
    $api_detail->execute(array("id" => $service_detail["service_api"]));
    $api_detail = $api_detail->fetch(PDO::FETCH_ASSOC);
  endif;



  if ($service_detail["service_package"] == 2):
    $quantity = $service_detail["service_min"];
    $price = service_price($service_detail["service_id"]);
    $price = ($price - ($price * $discount_percent));
    $extras = "";
  elseif ($service_detail["service_package"] == 3 || $service_detail["service_package"] == 4):
    $quantity = count(explode("\n", $comments)); // count custom comments
    $extras = json_encode(["comments" => $comments]);
  elseif ($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13):
    $extras = "";
    $quantity = $otoMin . "-" . $otoMax; // Sipariş miktarı
    $link = $username; // Sipariş link
    $subscriptions = 2;
    $price = 0;
  elseif ($service_detail["service_package"] == 14 || $service_detail["service_package"] == 15):
    $extras = "";
    $link = $username; // Sipariş link
    $subscriptions = 2;
    $quantity = $service_detail["service_min"];
    $price = service_price($service["service_id"]);
    $price = ($price - ($price * $discount_percent));
    $posts = $service_detail["service_autopost"];
    $delay = 0;
    $time = '+' . $service_detail["service_autotime"] . ' days';
    $expiry = date('Y-m-d H:i:s', strtotime($time));
    $otoMin = $service_detail["service_min"];
    $otoMax = $service_detail["service_min"];
  else:
    $extras = "";
  endif;

  

  if ($service_detail["service_package"] == 14 || $service_detail["service_package"] == 15):
    $price = service_price($service_detail["service_id"]);
    $price = ($price - ($price * $discount_percent));

  elseif ($service_detail["service_package"] != 2 && $service_detail["service_package"] != 11 && $service_detail["service_package"] != 12 && $service_detail["service_package"] != 13):
    $price = (service_price($service_detail["service_id"]) / 1000) * $quantity;
    if(!$price){
      $price = (service_price($service_detail["service_id"]) / 1000) * $quantity;
    }
    $price = ($price - ($price * $discount_percent));
  endif;

  

  if ($service_detail["service_package"] == 14 || $service_detail["service_package"] == 15) {
    $subscriptions_status = "limit";
    $expiry = date("Y-m-d", strtotime('+' . $service_detail["service_autotime"] . ' days'));
  } else {
    $subscriptions_status = "active";
  }

  if ($dripfeedon == 1):
    $dripfeedon = 2;
    $dripfeed_totalquantity = $quantity * $runs; //dripfeed toplam gönderim miktarı
    $dripfeed_totalcharges = service_price($service_detail["service_id"]) * $dripfeed_totalquantity / 1000; //dripfeed toplam gönderim ücreti
    $price = service_price($service_detail["service_id"]) * $dripfeed_totalquantity / 1000;
    $price = ($price - ($price * $discount_percent));
  else:
    $dripfeedon = 1;
    $dripfeed_totalcharges = "";
    $dripfeed_totalquantity = "";
  endif;


  if ($service_detail["want_username"] == 2):
    $private_type = "username";
    $countRow = $conn->prepare("SELECT * FROM orders WHERE order_url=:url &&  order_status!=:statu && dripfeed=:dripfeed && subscriptions_type=:subscriptions_type ");
    $countRow->execute(array("url" => $link, "statu" => "completed", "dripfeed" => 1, "subscriptions_type" => 1));
    $countRow = $countRow->rowCount();
  else:
    $private_type = "username";
    $countRow = $conn->prepare("SELECT * FROM orders WHERE order_url=:url &&  order_status!=:statu && dripfeed=:dripfeed && subscriptions_type=:subscriptions_type ");
    $countRow->execute(array("url" => $link, "statu" => "completed", "dripfeed" => 1, "subscriptions_type" => 1));
    $countRow = $countRow->rowCount();
  endif;
  if ($service_detail["start_count"] == "none"):
    $start_count = "0";
  else:
    $start_count = instagramCount(["type" => $private_type, "url" => $link, "search" => $service_detail["start_count"]]);
  endif;


  if ($service_detail["service_type"] == 1):
    $error = 1;
    $errorText = $languageArray["error.neworder.service.deactive"];
  elseif ($service_detail["service_package"] == 1 && (empty($link) || empty($quantity))):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif ($service_detail["service_package"] == 2 && empty($link)):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif ($service_detail["service_package"] == 3 && (empty($link) || empty($comments))):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif (($service_detail["service_package"] == 14 || $service_detail["service_package"] == 15) && empty($username)):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif ($service_detail["service_package"] == 4 && (empty($link) || empty($comments))):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif (($service_detail["service_package"] == 1 || $service_detail["service_package"] == 2 || $service_detail["service_package"] == 3 || $service_detail["service_package"] == 4) && $quantity < $service_detail["service_min"]):
    $error = 1;
    $errorText = str_replace("{min}", $service_detail["service_min"], $languageArray["error.neworder.min"]);
  elseif (($service_detail["service_package"] == 1 || $service_detail["service_package"] == 2 || $service_detail["service_package"] == 3 || $service_detail["service_package"] == 4) && $quantity > $service_detail["service_max"]):
    $error = 1;
    $errorText = str_replace("{max}", $service_detail["service_max"], $languageArray["error.neworder.max"]);
  elseif ($dripfeedon == 2 && (empty($runs) || empty($interval))):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif ($dripfeedon == 2 && $dripfeed_totalquantity > $service_detail["service_max"]):
    $error = 1;
    $errorText = str_replace("{max}", $service_detail["service_max"], $languageArray["error.neworder.max"]);
  elseif (($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13) && empty($username)):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif (($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13) && empty($otoMin)):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif (($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13) && empty($otoMax)):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif (($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13) && empty($posts)):
    $error = 1;
    $errorText = $languageArray["error.neworder.empty"];
  elseif (($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13) && $otoMax < $otoMin):
    $error = 1;
    $errorText = $languageArray["error.neworder.min.largest.max"];
  elseif (($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13) && $otoMin < $service_detail["service_min"]):
    $error = 1;
    $errorText = str_replace("{min}", $service_detail["service_min"], $languageArray["error.neworder.min"]);
  elseif (($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13) && $otoMax > $service_detail["service_max"]):
    $error = 1;
    $errorText = str_replace("{max}", $service_detail["service_max"], $languageArray["error.neworder.max"]);
  elseif (instagramProfilecheck(["type" => $private_type, "url" => $link, "return" => "private"]) && $service_detail["instagram_private"] == 2):
    $error = 1;
    $errorText = $languageArray["error.neworder.profile.secret"];
  elseif ($service_detail["instagram_second"] == 1 && $countRow >= 1 && ($service_detail["service_package"] != 11 && $service_detail["service_package"] != 12 && $service_detail["service_package"] != 13 && $service_detail["service_package"] != 14 && $service_detail["service_package"] != 15)):
    $error = 1;
    $errorText = $languageArray["error.neworder.there.order"];
  elseif (($price > $user["balance"]) && $user["balance_type"] == 2):
    $error = 1;
    $errorText = $languageArray["error.neworder.balance.notenough"];
  elseif (($user["balance"] - $price < "-" . $user["debit_limit"]) && $user["balance_type"] == 1):
    $error = 1;
    $errorText = $languageArray["error.neworder.balance.notenough"];
  elseif (empty($user["balance"])):
    $error = 1;
    $errorText = "Something went wrong.";
  elseif (!is_numeric($user["balance"])):
    $error = 1;
    $errorText = "Something went wrong.";
  elseif ($user["balance"] == 0):
    $error = 1;
    $errorText = $languageArray["error.neworder.balance.notenough"];
  else:

   
    /* Sipariş ver - başla */
    if ($service_detail["service_api"] == 0):
      /* manuel sipariş - başla */
      $conn->beginTransaction();
      $insert = $conn->prepare("INSERT INTO orders SET order_start=:count, order_profit=:profit, order_error=:error,client_id=:c_id, service_id=:s_id, order_quantity=:quantity, order_charge=:price, order_url=:url, order_create=:create, order_extras=:extra, last_check=:last ");
      $insert = $insert->execute(array("count" => $start_count, "c_id" => $user["client_id"], "error" => "-", "s_id" => $service_detail["service_id"], "quantity" => $quantity, "price" => $price, "profit" => $price, "url" => $link, "create" => date("Y.m.d H:i:s"), "last" => date("Y.m.d H:i:s"), "extra" => $extras));
      if ($insert):
        $last_id = $conn->lastInsertId();
      endif;
      if ($insert):
        $update = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id");
        $update = $update->execute(array("balance" => $user["balance"] - $price, "spent" => $user["spent"] + $price, "id" => $user["client_id"]));
        $insert2 = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");
        $insert2 = $insert2->execute(array("c_id" => $user["client_id"], "action" => $price . " Đơn hàng mới #" . $last_id . ".", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
      endif;
      if ($insert && $update && $insert2):

        
$conn->commit();
unset($_SESSION["data"]);
$user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id");
$user->execute(array("id" => $_SESSION["msmbilisim_userid"]));
$user = $user->fetch(PDO::FETCH_ASSOC);
$user['auth'] = $_SESSION["msmbilisim_userlogin"];
$order_data = [
    'success' => 1,
    'id' => $last_id,
    "service" => $service_detail["service_name"],
    "link" => $link,
    "quantity" => $quantity,
    "price" => $price,
    "balance" => $user["balance"]
];

// Gửi dữ liệu đến Telegram
if ($order_data['success'] == 1) {
    $bot_token = "7802159971:AAGg8elyxec7mX5NsG7L2GXoFwSU9JX-1dY"; // Bot token của bạn
    $chat_id = "1118789739"; // Chat ID của bạn

    // Tạo nội dung thông báo
    $message = "Đơn hàng thủ công mới: \n";
    $message .= "ID Đơn hàng: " . $order_data['id'] . "\n";
    $message .= "Dịch vụ: " . $order_data['service'] . "\n";
    $message .= "Liên kết: " . $order_data['link'] . "\n";
    $message .= "Số lượng: " . $order_data['quantity'] . "\n";
    $message .= "Giá: " . $order_data['price'] . "\n";
    $message .= "Số dư: " . $order_data['balance'];

    // Gửi yêu cầu POST tới API của Telegram
    $telegram_url = "https://api.telegram.org/bot" . $bot_token . "/sendMessage";
    $post_fields = [
        'chat_id' => $chat_id,
        'text' => $message
    ];

    // Sử dụng CURL để gửi yêu cầu
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $telegram_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);
}


        $update = $conn->prepare("UPDATE settings SET panel_orders=:orders WHERE id=:id");

        $update = $update->execute(array("id" => 1, "orders" => $last_id));


        $_SESSION["data"]["services"] = $_POST["services"];
        $_SESSION["data"]["categories"] = $_POST["categories"];
        $_SESSION["data"]["order"] = $order_data;
        header("Location:" . site_url("order/" . $last_id));

        if ($settings["alert_newmanuelservice"] == 2) {

          $msg = "Đã nhận được đơn đặt hàng thủ công mới
Xem tất cả các đơn đặt hàng thủ công trong bảng quản trị: " . site_url() . "admin/orders/1/all?mode=manuel ";
          $send = mail($settings['admin_mail'], "Đơn hàng thủ công mới", $msg);

        }
        $msg = "Mật khẩu mới của bạn là: " . $pass;
        $send = mail($row['enmail'], "Đặt lại mật khẩu", $msg);


      else:
        $conn->rollBack();
        $error = 1;
        $errorText = $languageArray["error.neworder.fail"];
      endif;
      /* manuel sipariş - bitir */
    else:
         
      /* api ile sipariş - başla */
      $conn->beginTransaction();

      /* API SİPARİŞİ GEÇ BAŞLA */
      if ($api_detail["api_type"] == 1):
       
        ## Standart api başla ##
        if ($service_detail["service_package"] == 1 || $service_detail["service_package"] == 2):
          ## Standart başla ##
          $order = $smmapi->action(array('key' => $api_detail["api_key"], 'action' => 'add', 'service' => $service_detail["api_service"], 'link' => $link, 'quantity' => $quantity + $service_overflow), $api_detail["api_url"]);
          if (@!$order->order):
            $error = json_encode($order);
            $order_id = "";
          else:
            $error = "-";
            $order_id = @$order->order;
          endif;
          ## Standart bitti ##
        elseif ($service_detail["service_package"] == 3):
          ## Custom comments başla ##
          $order = $smmapi->action(array('key' => $api_detail["api_key"], 'action' => 'add', 'service' => $service_detail["api_service"], 'link' => $link, 'comments' => $comments), $api_detail["api_url"]);
          if (@!$order->order):
            $error = json_encode($order);
            $order_id = "";
          else:
            $error = "-";
            $order_id = @$order->order;
          endif;
          ## Custom comments bitti ##
        elseif ($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13 || $service_detail["service_package"] == 14 || $service_detail["service_package"] == 15):
          ## oto başla ##
          $error = "-";
          $order_id = "";
          ## oto bitti ##
        else:
        endif;
        $orderstatus = $smmapi->action(array('key' => $api_detail["api_key"], 'action' => 'status', 'order' => $order_id), $api_detail["api_url"]);
        $balance = $smmapi->action(array('key' => $api_detail["api_key"], 'action' => 'balance'), $api_detail["api_url"]);
        $api_charge = $orderstatus->charge;
        if (!$api_charge):
          $api_charge = 0;
        endif;
        $currency =  $api_detail["currency"];
        $profit = from_to($currencies_array, $currency, $settings["site_base_currency"], $price - $api_charge);
        $balance = $balance->balance;
        ## Standart api bitti ##
      elseif ($api_detail["api_type"] == 3):
        if ($service_detail["service_package"] == 1 || $service_detail["service_package"] == 2):
          ## Standart başla ##
          $order = $fapi->query(array('cmd' => 'orderadd', 'token' => $api_detail["api_key"], 'apiurl' => $api_detail["api_url"], 'orders' => [['service' => $service_detail["api_service"], 'amount' => $quantity, 'data' => $link]]));
          if (@$order[0][0]['status'] == "error"):
            $error = json_encode($order);
            $order_id = 0;
            $api_charge = "0";
            $currencycharge = 1;
          else:
            $error = "-";
            $order_id = @$order[0][0]["id"];
            $orderstatus = $fapi->query(array('cmd' => 'orderstatus', 'token' => $api_detail["api_key"], 'apiurl' => $api_detail["api_url"], 'orderid' => [$order_id]));
            $balance = $fapi->query(array('cmd' => 'profile', 'token' => $api_detail["api_key"], 'apiurl' => $api_detail["api_url"]));
            $api_charge = $orderstatus[$order_id]["order"]["price"];

            $profit = from_to(get_currencies_array("enabled"), $currency, $settings["site_base_currency"], $price - $api_charge);
          endif;

          ## Standart bitti ##
        elseif ($service_detail["service_package"] == 11 || $service_detail["service_package"] == 12 || $service_detail["service_package"] == 13):
          ## oto başla ##
          $error = "-";
          $order_id = "";
          ## oto bitti ##
        else:
        endif;

      else:
      endif;
      /* API SİPARİŞ GEÇ BİTTİ */
      if ($dripfeedon == 2):
        $insert = $conn->prepare("INSERT INTO orders SET order_start=:count, order_error=:error, client_id=:c_id, api_orderid=:order_id, service_id=:s_id, order_quantity=:quantity, order_charge=:price,
                order_url=:url,
                order_create=:create, order_extras=:extra, last_check=:last_check, order_api=:api, api_serviceid=:api_serviceid, api_orderid = :api_orderid, dripfeed=:drip, dripfeed_totalcharges=:totalcharges, dripfeed_runs=:runs,
                dripfeed_interval=:interval, dripfeed_totalquantity=:totalquantity, dripfeed_delivery=:delivery
                ");
        $insert = $insert->execute(
          array(
            "count" => $start_count,
            "c_id" => $user["client_id"],
            "error" => "-",
            "s_id" => $service_detail["service_id"],
            "quantity" => $quantity,
            "price" => $price,
            "url" => $link,
            "create" => date("Y.m.d H:i:s"),
            "extra" => $extras,
            "order_id" => 0,
            "last_check" => date("Y.m.d H:i:s"),
            "api" => $api_detail["id"],
            "api_serviceid" => $service_detail["api_service"],
            "api_orderid" => $order_id,
            "drip" => $dripfeedon,
            "totalcharges" => $dripfeed_totalcharges,
            "runs" => $runs,
            "interval" => $interval,
            "totalquantity" => $dripfeed_totalquantity,
            "delivery" => 1
          )
        );
        if ($insert):
          $dripfeed_id = $conn->lastInsertId();
        endif;


      else:
        $dripfeed_id = 0;
      endif;
      $profit = $price - $api_charge;
      $insert = $conn->prepare("INSERT INTO orders SET order_start=:count, order_error=:error, order_detail=:detail, client_id=:c_id, api_orderid=:order_id, service_id=:s_id, order_quantity=:quantity, order_charge=:price, order_url=:url,
              order_create=:create, order_extras=:extra, last_check=:last_check, order_api=:api, api_serviceid=:api_serviceid, api_orderid = :api_orderid, subscriptions_status=:s_status,
              subscriptions_type=:subscriptions, subscriptions_username=:username, subscriptions_posts=:posts, subscriptions_delay=:delay, subscriptions_min=:min,
              subscriptions_max=:max, subscriptions_expiry=:expiry, dripfeed_id=:dripfeed_id, api_charge=:api_charge, api_currencycharge=:api_currencycharge, order_profit=:profit
              ");



      $insert = $insert->execute(
        array(
          "count" => $start_count,
          "c_id" => $user["client_id"],
          "detail" => json_encode($order),
          "error" => $error,
          "s_id" => $service_detail["service_id"],
          "quantity" => $quantity,
          "price" => $price / $runs,
          "url" => $link,
          "create" => date("Y.m.d H:i:s"),
          "extra" => $extras,
          "order_id" => intval($order_id),
          "last_check" => date("Y.m.d H:i:s"),
          "api" => $api_detail["id"],
          "api_serviceid" => $service_detail["api_service"],
            "api_orderid" => $order_id,
          "s_status" => $subscriptions_status,
          "subscriptions" => $subscriptions,
          "username" => $username,
          'posts' => $posts,
          "delay" => $delay,
          "min" => $otoMin,
          "max" => $otoMax,
          "expiry" => $expiry,
          "dripfeed_id" => $dripfeed_id,
          "profit" => $profit,
          "api_charge" => $api_charge,
          "api_currencycharge" => $currencycharge
        )
      );
      if ($insert):
        $last_id = $conn->lastInsertId();
      endif;
      if ($settings["alert_orderfail"] == 2) {
        $errorMessage = json_decode($error, true);
        if ($error != "-") {
          $msg = "Order Got Failed Order id : " . $last_id . "
Order Error : " . $errorMessage["error"] . " 
View Fail orders in admin panel :
" . site_url() . "admin/orders/1/failed";
          $send = mail($settings["admin_mail"], "Failed Orders Information", $msg);
        }
      }
      if ($insert):
        $update = $conn->prepare("UPDATE clients SET balance=:balance, spent=:spent WHERE client_id=:id");
        $update = $update->execute(array("balance" => $user["balance"] - $price, "spent" => $user["spent"] + $price, "id" => $user["client_id"]));

      endif;
      $insert2 = $conn->prepare("INSERT INTO client_report SET client_id=:c_id, action=:action, report_ip=:ip, report_date=:date ");

      $insert2 = $insert2->execute(array("c_id" => $user["client_id"], "action" => $price . " Đơn hàng mới #" . $last_id . ".", "ip" => GetIP(), "date" => date("Y-m-d H:i:s")));
      if ($settings["alert_apibalance"] == 2 && $api_detail["api_limit"] > $balance && $api_detail["api_alert"] == 2):
        $msg = "Provider balance is lesser than limit! 
" . $api_detail["api_name"] . " api available balance :" . $balance;
        $send = mail($settings['admin_mail'], "Provider balance notification", $msg);

      endif;


      if ($insert && $update && ($order_id || $error) && $insert2):

        $error = 0;
        $conn->commit();
        unset($_SESSION["data"]);
        $user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id");
        $user->execute(array("id" => $_SESSION["msmbilisim_userid"]));
        $user = $user->fetch(PDO::FETCH_ASSOC);
        $user['auth'] = $_SESSION["msmbilisim_userlogin"];
        $order_data = ['success' => 1, 'id' => $last_id, "service" => $service_detail["service_name"], "link" => $link, "quantity" => $quantity, "price" => $price, "balance" => $user["balance"]];

        $update = $conn->prepare("UPDATE settings SET panel_orders=:orders WHERE id=:id");

        $update = $update->execute(array("id" => 1, "orders" => $last_id));

        $_SESSION["data"]["services"] = $_POST["services"];
        $_SESSION["data"]["categories"] = $_POST["categories"];
        $_SESSION["data"]["order"] = $order_data;
        header("Location:" . site_url("order/" . $last_id));

      else:
        $conn->rollBack();
        $error = 1;
        $errorText = $languageArray["error.neworder.fail"];
      endif;
      /* api ile sipariş - bitir */
    endif;
    /* Sipariş ver - bitir */
  endif;

endif;




?>