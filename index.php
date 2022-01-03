<?php
$eth_price = json_decode(file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=ETH&tsyms=USD'))->USD;
$arr_json = json_decode(file_get_contents('https://nll-v2-1-39luy.ondigitalocean.app/static/phunks-market-data'));
$price_arr = array();
foreach ($arr_json->phunksOfferedForSale as $phunk) {
    $metadata = $phunk->data;
    $attributes = $metadata->properties;
    $id = $phunk->phunkIndex;
    $price_base = $phunk->minValue;
    $price_display = $price_base/1000000000000000000;
    $count_traits = count($attributes) - 1;
    $value = "$count_traits traits";
    if ($count_traits == '0') {
        $value = "$count_traits trait";
    }
    if (empty($price_arr[$value])) {
        $price_arr[$value]['price'] = '999999999999999999999999999';
    }
    if ($price_arr[$value]['price'] > $price_display) {
        $price_arr[$value]['price'] = $price_display;
        $price_arr[$value]['id'] = $phunk->phunkIndex;
    }
    foreach ($attributes as $v) {
        $value = $v->value;
        if (empty($price_arr[$value])) {
            $price_arr[$value]['price'] = '999999999999999999999999999';
        }
        if ($price_arr[$value]['price'] > $price_display) {
            $price_arr[$value]['price'] = $price_display;
            $price_arr[$value]['id'] = $phunk->phunkIndex;
        }
    }
}
function by_price($a, $b)
{
    return $a["price"] * 100000000 - $b["price"] * 100000000;
}
uasort($price, "by_price");
$placeholder = 'Token id';
$phunk_id = $_GET['id'];
$adress = $_GET['adress'];
$placeholder_owner = '0x adress';
$placeholder_value = '';
if (!empty($adress)) {
    $phunks_data = json_decode(file_get_contents("https://app.flooredape.io/api/v1/get-phunks?address=$adress"), true);
    if (!empty($phunks_data['0'])) {
        $placeholder_owner = $placeholder_value = $adress;
        $phunks_result = $phunks_data['0']['data']['customData'];
        //print_r($phunks_result);
        $phunks_text = "<h3 class='mb-2 text-dark text-center'>Phunks owned by $adress</h3><div class='row mb-4'>";
        foreach ($phunks_result as $key => $value) {
            $name = $value['metadata']['name'];
            $traits = $value['metadata']['attributes'];
            $count_traits_phunk = count($traits) - 1;
            $phunk_id = $value['token_id'];
            if (strlen($phunk_id) == '1') {
                $id_img = "000$phunk_id";
            }
            if (strlen($phunk_id) == '2') {
                $id_img = "00$phunk_id";
            }
            if (strlen($phunk_id) == '3') {
                $id_img = "0$phunk_id";
            }
            if (strlen($phunk_id) == '4') {
                $id_img = "$phunk_id";
            }
            $img = "https://nll-v2-1-39luy.ondigitalocean.app/static/phunk$id_img.png";
            $phunks_text .=  "<div class='col-6 col-md-3 text-center'><a href='https://notlarvalabs.com/cryptophunks/details/$phunk_id' target='_blank' class='text-decoration-none'><h3 class='mb-2 text-dark'>$name</h3> <br> <img src='$img' width='150px' style='image-rendering:pixelated;background:#638596;width:150px'><br><span class='text-dark'>This phunk have $count_traits_phunk traits :</span> <br>";
            foreach ($traits as $v_trait) {
                $v_name = $v_trait['value'];
                $v_price = $price_arr[$v_name]['price'];
                $phunks_text .= "$v_name floor price $v_price Ξ<br>";
            }
            $c = "$count_traits_phunk traits";
            if ($count_traits_phunk == '0') {
                $c = "$count_traits_phunk trait";
            }
            $v_price = $price_arr[$c]['price'];
            $phunks_text .= "$c floor price $v_price Ξ</a></div>";
        }
        $phunks_text .= "</div>";
    }
} else {
    $owner = json_decode(file_get_contents("https://nll-v2-1-39luy.ondigitalocean.app/static/owner-of?tokenId=$phunk_id"));
    if (!empty($owner->owner)) {
        $owner = $owner->owner;
        $data_phunk = json_decode(file_get_contents("https://nll-v2-1-39luy.ondigitalocean.app/static/phunk-listing?tokenId=$phunk_id&tokenData=true"), true);
        //print_r($data_phunk);
        $placeholder = $phunk_id;
        $name = $data_phunk['data']['name'];
        $traits = $data_phunk['data']['properties'];
        $count_traits_phunk = count($traits) - 1;
        if (strlen($phunk_id) == '1') {
            $id_img = "000$phunk_id";
        }
        if (strlen($phunk_id) == '2') {
            $id_img = "00$phunk_id";
        }
        if (strlen($phunk_id) == '3') {
            $id_img = "0$phunk_id";
        }
        if (strlen($phunk_id) == '4') {
            $id_img = "$phunk_id";
        }
        $img = "https://nll-v2-1-39luy.ondigitalocean.app/static/phunk$id_img.png";
        if ($data_phunk['isForSale']) {
            $phunk_price = $data_phunk['minValue'];
            $result_text = "<div class='row mb-4'><div class='col-12 text-center'><a href='https://notlarvalabs.com/cryptophunks/details/$phunk_id' target='_blank' class='text-decoration-none'><h3 class='mb-2 text-dark'>$name</h3> <span class='text-dark'>Owned by $owner <br> Currently on sale ($phunk_price Ξ)</span> <br> <img src='$img' width='150px' style='image-rendering:pixelated;background:#638596;width:150px'><br><span class='text-dark'>This phunk have $count_traits_phunk traits :</span><br>";
        } else {
            $result_text =  "<div class='row mb-4'><div class='col-12 text-center'><a href='https://notlarvalabs.com/cryptophunks/details/$phunk_id' target='_blank' class='text-decoration-none'><h3 class='mb-2 text-dark'>$name</h3> <span class='text-dark'>Owned by $owner <br> Currently not on sale</span> <br> <img src='$img' width='150px' style='image-rendering:pixelated;background:#638596;width:150px'><br><span class='text-dark'>This phunk have $count_traits_phunk traits :</span> <br>";
        }
        foreach ($traits as $v_trait) {
            $v_name = $v_trait['value'];
            $v_price = $price_arr[$v_name]['price'];
            $result_text .= "$v_name floor price $v_price Ξ<br>";
        }
        $c = "$count_traits_phunk traits";
        if ($count_traits_phunk == '0') {
            $c = "$count_traits_phunk trait";
        }
        $v_price = $price_arr[$c]['price'];
        $result_text .= "$c floor price $v_price Ξ</a></div></div>";
    }
}
?>
<html>
<head>
    <title>Cryptophunks Floors by attributes</title>
    <meta name="description" content="Find current phunks floors by attributes, We <3 Phunks, by @maximedotair" />
    <link rel="icon" type="image/vnd.microsoft.icon" href="phunk6308.ico" />
    <link rel="shortcut icon" type="image/x-icon" href="phunk6308.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/1.7.0-rc.0/web3.min.js"></script>
    <?php if ($placeholder_owner == '0x adress') {
    ?> <button class="enableEthereumButton btn btn-primary btm-sm m-4">Connect to Metamask / Frame</button> <?php } ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-RBZF9R9BDM"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-RBZF9R9BDM');
    </script>
</head>
<body>
    <div class="container mt-5">
        <?= $phunks_text ?>
        <?= $result_text ?>
        <div class="row justify-content-center">
            <div class="col-md-4 col-12 text-center">
                <form action="" method="get">
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" placeholder="<?= $placeholder ?>" aria-label="<?= $placeholder ?>" aria-describedby="button-addon2" name="id" value="<?= $placeholder ?>" autocomplete="off">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-md-8 col-12 text-center">
                <form action="" method="get">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="<?= $placeholder_owner ?>" aria-label="<?= $placeholder_owner ?>" aria-describedby="button-addon2" name="adress" value="<?= $placeholder_value ?>" autocomplete="off">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <h3 class="text-center mb-4">CryptoPhunks Floor price by attributes</h3>
        <p class="text-center text-muted">Made with <3 By <a href="https://www.twitter.com/maximedotair" target="_blank">@maximedotair</a> (1 eth = <?= $eth_price ?> $)</b>
                <div class="row">
                    <?php
                    $today = date("Ymd");
                    if (!file_exists("price_$today.json")) {
                        file_put_contents("price_$today.json", json_encode($price));
                    }
                    foreach ($price as $key => $value) {
                        $price_attribute = $value['price'];
                        $tokenId = $value['id'];
                        if (strlen($tokenId) == '1') {
                            $id_img = "000$tokenId";
                        }
                        if (strlen($tokenId) == '2') {
                            $id_img = "00$tokenId";
                        }
                        if (strlen($tokenId) == '3') {
                            $id_img = "0$tokenId";
                        }
                        if (strlen($tokenId) == '4') {
                            $id_img = "$tokenId";
                        }
                        $img = "https://nll-v2-1-39luy.ondigitalocean.app/static/phunk$id_img.png";
                        echo "<div class='col-6 col-md-2 text-center mb-4'><a href='https://notlarvalabs.com/cryptophunks/details/$tokenId' target='_blank' class='text-decoration-none'><img src='$img' width='100px' style='image-rendering:pixelated;background:#638596'> <br> <span class='text-dark'>$key</span> <br> floor price : $price_attribute Ξ</a></div>";
                    }
                    ?>
                </div>
    </div>
</body>
<?php if ($placeholder_owner == '0x adress') {
?>
    <script>
        const web3 = new Web3(Web3.givenProvider || "ws://localhost:8545");
        const ethereumButton = document.querySelector(".enableEthereumButton");
        let accounts = [];
        let ethereum;
        if (typeof window.ethereum !== "undefined") {
            console.log("MetaMask is installed!");
            ethereum = window.ethereum;
        }
        ethereumButton.addEventListener("click", () => {
            getAccount();
        });
        async function getAccount() {
            accounts = await ethereum.request({
                method: "eth_requestAccounts"
            });
            console.log("accounts", accounts);
            if (accounts.length >= 1) {
                window.location.replace("/?adress=" + accounts[0]);
            }
        }
    </script>
<?php
}
?>
</html>