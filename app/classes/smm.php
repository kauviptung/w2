<?php
if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}
class SMMApi{

  public $api_url = '';
  public $apiKEY = '';

  public function action($data,$api){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT ,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_URL , $api);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_POST , true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $result = curl_exec($ch);
    if (curl_errno($ch) != 0 && empty($result)) {
      $result = false;
    }
    curl_close($ch);
    return json_decode($result);

  }

}

class socialsmedia_api
{
    private $data = array();

    function query($data=array())
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
                CURLOPT_URL             => $data["apiurl"],
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_CONNECTTIMEOUT  => 15,
                CURLOPT_TIMEOUT         => 30,
                CURLOPT_POST            => true,
                CURLOPT_POSTFIELDS      => http_build_query(
                    array(
                        'jsonapi' => json_encode(
                            array_merge($this->data, $data), JSON_UNESCAPED_UNICODE)
                    )
                )
            )
        );
        $cr = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log("SMMApi CURL error ({$api}): " . curl_error($ch));
            curl_close($ch);
            return false;
        }
        curl_close($ch);

        $decoded = json_decode($cr, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("SMMApi invalid JSON response ({$api}): " . $cr);
            return false;
        }
        return $decoded;
    }
}
