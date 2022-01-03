<?php
class tweet_bot
{
    function oauth()
    {
        require_once("twitteroauth/twitteroauth.php");
        $con = new TwitterOAuth($this->api_key, $this->api_secret, $this->access_token, $this->access_token_secret);
        return $con;
    }
    function read($user_id)
    {
        $con = $this->oauth();
        $status = $con->get('statuses/user_timeline', array('screen_name' => $user_id,'count' => '5'));
        return $status;
    }
    function tweet($text)
    {
        $con = $this->oauth();
        $status = $con->post('statuses/update', array('status' => $text));
        return $status;
    }
    function reply($text,$in_reply_to_status_id)
    {
        $con = $this->oauth();
        $status = $con->post('statuses/update', array('status' => $text,'in_reply_to_status_id' => $in_reply_to_status_id));
        return $status;
    }
    function setKey($api_key,$api_secret,$access_token,$access_token_secret)
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->access_token = $access_token;
        $this->access_token_secret = $access_token_secret;
    }
}
function extractString($string, $start, $end) {
    $string = " ".$string;
    $ini = strpos($string, $start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
function generatetext($phunk_id){
    $arr_json = json_decode(file_get_contents('https://nll-v2-1-39luy.ondigitalocean.app/static/phunks-market-data'));
$price_arr= array();
foreach($arr_json->phunksOfferedForSale as $phunk){
    $metadata = $phunk->data;
    $attributes = $metadata->properties;

    $price_base = $phunk->minValue;
    $price_display = $price_base/1000000000000000000;
    $count_traits = count($attributes)-1;
    $value = "$count_traits traits";
    if($count_traits == '0'){
        $value = "$count_traits trait";  
    }
    if(empty($price_arr[$value])){
        $price_arr[$value]['price'] = '999999999999999999999999999';        
    }
    if($price_arr[$value]['price'] > $price_display){
        $price_arr[$value]['price'] = $price_display;
        $price_arr[$value]['id'] = $phunk->phunkIndex;
    }
    foreach($attributes as $v){
       $value = $v->value;
       if(empty($price_arr[$value])){
        $price_arr[$value]['price'] = '999999999999999999999999999';        
    }
    if($price_arr[$value]['price'] > $price_display){
        $price_arr[$value]['price'] = $price_display;
        $price_arr[$value]['id'] = $phunk->phunkIndex;
    }
    }
}
    $owner = json_decode(file_get_contents("https://nll-v2-1-39luy.ondigitalocean.app/static/owner-of?tokenId=$phunk_id"));
if(!empty($owner->owner)){
    $owner = $owner->owner;
    $data_phunk = json_decode(file_get_contents("https://nll-v2-1-39luy.ondigitalocean.app/static/phunk-listing?tokenId=$phunk_id&tokenData=true"),true);
    //print_r($data_phunk);
    $placeholder = $phunk_id;
    $name = $data_phunk['data']['name'];
    $traits = $data_phunk['data']['properties'];
    $count_traits_phunk = count($traits)-1;
    if(strlen($phunk_id) == '1'){
        $id_img = "000$phunk_id";
    }
    if(strlen($phunk_id) == '2'){
        $id_img = "00$phunk_id";
    }
    if(strlen($phunk_id) == '3'){
        $id_img = "0$phunk_id";
    }
    if(strlen($phunk_id) == '4'){
        $id_img = "$phunk_id";
    }
    $img = "https://nll-v2-1-39luy.ondigitalocean.app/static/phunk$id_img.png";
    $result_text = '';
    foreach($traits as $v_trait){
        $v_name = $v_trait['value'];
        $v_price = $price_arr[$v_name]['price'];
        $result_text .= "$v_name floor $v_price Ξ \n";
    }
    $c = "$count_traits_phunk traits";
    if($count_traits_phunk == '0'){
        $c = "$count_traits_phunk trait";  
    }
    $v_price = $price_arr[$c]['price'];
    $result_text .= "$c floor $v_price Ξ \n";
}
return $result_text;
}
$api_key='' ;
$api_secret='' ;
$access_token ='' ;
$access_token_key='' ;
$tweet= new tweet_bot;
$tweet->setKey($api_key, $api_secret,$access_token , $access_token_key);
$result = $tweet->read('PhunkBot');

foreach ($result as $key => $value) {
    $latest = $value->text;
$phid = extractString($latest,'#',' ');
$phid = (int)$phid;
$publish = FALSE;
if (strpos($latest, 'accepted bid') !== false) {
  $publish = TRUE;
}
if (strpos($latest, 'was flipped') !== false) {
    $publish = TRUE;
}
$latest_id = $value->id;
if($publish){
    $generate = generatetext($phid);
    $text = "$generate More @ https://www.phunksfloor.com/?id=$phid @PhunkBot";
    if(!file_exists($latest_id)){
        file_put_contents($latest_id, $text);
       $tweet->reply($text,$latest_id);
    }
}
else {
    if(!file_exists($latest_id)){
        file_put_contents($latest_id, "");
    }
}
}
