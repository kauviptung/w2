<?php
if (!defined('BASEPATH')) {
    die('Direct access to the script is not allowed');
}
define("ADDFUNDS", TRUE);
$title .= " Add Funds";

if ($_SESSION["msmbilisim_userlogin"] != 1 || $user["client_type"] == 1) {
    header("Location:" . site_url('logout'));
}
if ($settings["email_confirmation"] == 1 && $user["email_type"] == 1) {
    header("Location:" . site_url('confirm_email'));
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $paymentMethods = $conn->prepare("SELECT * FROM paymentmethods WHERE methodStatus=:status ORDER BY methodPosition ASC");
    $paymentMethods->execute(["status" => 1]);

    $methodsList = array();

    if ($paymentMethods->rowCount()) {
        $paymentMethods = $paymentMethods->fetchAll(PDO::FETCH_ASSOC);
        for ($i = 0; $i < count($paymentMethods); $i++) {
            $methodsList[] = [
                "id" => $paymentMethods[$i]["methodId"],
                "name" => $paymentMethods[$i]["methodVisibleName"],
                "instructions" => trim(htmlspecialchars_decode($paymentMethods[$i]["methodInstructions"])),
                "fee" => $paymentMethods[$i]["methodFee"]
            ];
            $paymentMethodsJSON = json_encode(array_group_by($methodsList, "id"), 1);
        }
    } else {
        $methodsList[] = [
            "id" => 0,
            "name" => "No payment gateway activated"
        ];
    }



    	$methodNames = $conn->prepare("SELECT methodId,methodVisibleName FROM paymentmethods");
	$methodNames->execute();
	$methodNames = $methodNames->fetchAll(PDO::FETCH_ASSOC);
	$methodNames = array_group_by($methodNames, "methodId");


	$query = "
    SELECT payment_id AS id, payment_create_date AS date, payment_method, payment_amount AS amount, 'payment' AS source
    FROM payments
    WHERE payment_status = :payment_status
      AND payment_delivery = :payment_delivery
      AND client_id = :client_id
    
    UNION
    
    SELECT i.id, i.create_time AS date, i.payment_method, 
           (CASE 
               WHEN c.currency_code = 'VND' THEN i.amount / c.currency_rate 
               ELSE i.amount 
           END) AS amount, 
           'invoice' AS source
    FROM invoices i
    LEFT JOIN currencies c ON c.currency_code = 'VND'
    WHERE i.status = :invoice_status
      AND i.user_id = :client_id
    
    ORDER BY date DESC
";


	$stmt = $conn->prepare($query);
	$stmt->execute([
		"payment_status" => 3,
		"payment_delivery" => 2,
		"invoice_status" => 0,
		"client_id" => $user["client_id"]
	]);

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$paymentHistory = [];
	foreach ($results as $row) {
		$paymentMethodCode = $row["payment_method"];
		$paymentMethodName = isset($methodNames[$paymentMethodCode]) ? $methodNames[$paymentMethodCode][0]["methodVisibleName"] : $paymentMethodCode;

		$paymentHistory[] = [
			"id" => $row["id"],
			"date" => $row["date"],
			"payment_method_code" => $paymentMethodCode, // Mã phương thức thanh toán
			"payment_method_name" => $paymentMethodName, // Tên hiển thị của phương thức thanh toán
			"amount" => format_amount_string($user["currency_type"], from_to($currencies_array, $settings["site_base_currency"], $user["currency_type"], $row["amount"])),
			"source" => $row["source"]
		];
	}
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["action"] == "getForm") {
    $formData .= "";
    $selectedMethod = $_POST["selectedMethod"];
    include("addfunds/getForm.php");
    $response = [];
    $response["success"] = true;
    $response["content"] = $formData;
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($response, true);
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $methodId = intval($_POST["payment_type"] ?: 0);

    $method = $conn->prepare("SELECT * FROM paymentmethods WHERE methodId=:id AND methodStatus=:status");
    $method->execute([
        "id" => $methodId,
        "status" => 1
    ]);
    if ($method->rowCount()) {
        $method = $method->fetch(PDO::FETCH_ASSOC);
        $methodId = $method["methodId"];
        $methodMin = number_format($method["methodMin"], 2, '.', '');
        $methodMax = number_format($method["methodMax"], 2, '.', '');
        $methodCurrency = $method["methodCurrency"];
        $methodCurrencySymbol = $currencies_array[$methodCurrency][0]["currency_symbol"] ?: $methodCurrency;
        $methodCallback = $method["methodCallback"];
        $methodExtras = json_decode($method["methodExtras"], 1);
        $paymentFee = $method["methodFee"];
        $paymentBonus = $method["methodBonusPercentage"];
        $paymentBonusStartAmount = $method["methodBonusStartAmount"];

        $paymentAmount = floatval($_POST["payment_amount"] ?: 0);
        if ($paymentFee > 0) {
            $fee = ($paymentAmount * ($paymentFee / 100));
            $paymentAmount += $fee;
        }
        $response = [];

        if ($paymentAmount < $methodMin) {
            errorExit("Minimum amount : $methodCurrencySymbol $methodMin");
        }
        if ($paymentAmount > $methodMax) {
            errorExit("Maximum amount : $methodCurrencySymbol $methodMax");
        }
        if ($method["methodId"] == 1) {
            require("addfunds/Initiators/payTMCheckout.php");
        }
        if ($method["methodId"] == 2) {
            require("addfunds/Initiators/payTMMerchant.php");
        }
        if ($method["methodId"] == 3) {
            require("addfunds/Initiators/perfectMoney.php");
        }
        if ($method["methodId"] == 4) {
            require("addfunds/Initiators/coinbaseCommerce.php");
        }
        if ($method["methodId"] == 5) {
            require("addfunds/Initiators/kashier.php");
        }
        if ($method["methodId"] == 6) {
            require("addfunds/Initiators/razorPay.php");
        }
        if ($method["methodId"] == 7) {
            require("addfunds/Initiators/phonepe.php");
        }
        if ($method["methodId"] == 8) {
            require("addfunds/Initiators/easypaisa.php");
        }
        if ($method["methodId"] == 9) {
            require("addfunds/Initiators/jazzcash.php");
        }
        if ($method["methodId"] == 10) {
            require("addfunds/Initiators/instamojo.php");
        }
        if ($method["methodId"] == 11) {
            require("addfunds/Initiators/cashmaal.php");
        }
        if ($method["methodId"] == 12) {
            require("addfunds/Initiators/alipay.php");
        }
        if ($method["methodId"] == 13) {
            require("addfunds/Initiators/payU.php");
        }
        if ($method["methodId"] == 14) {
            require("addfunds/Initiators/upiapi.php");
        }
        if ($method["methodId"] == 15) {
            require("addfunds/Initiators/opay.php");
        }
        if ($method["methodId"] == 16) {
            require("addfunds/Initiators/flutterwave.php");
        }
        if ($method["methodId"] == 17) {
            require("addfunds/Initiators/stripe.php");
        }
        if ($method["methodId"] == 18) {
            require("addfunds/Initiators/payeer.php");
        }
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($response, true);
        exit;
    } else {
        errorExit("Select a valid payment method.");
    }
}
?>