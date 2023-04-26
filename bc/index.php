<?php
//ini_set('display errors' 1);
error_reporting(E_ALL);

    require '../vendor/autoload.php';

//echo 'drud223.oo'; exit;
//	require_once 'Bigcommerce/Api/Connection.php';


    use Bigcommerce\Api\Client as Bigcommerce;




// $object = new \stdClass();
// $object->client_id = 'xxxxxx';
// $object->client_secret = 'xxxxx';
// $object->redirect_uri = 'https://app.com/redirect';
// $object->code = $request->get('code');
// $object->context = $request->get('context');
// $object->scope = $request->get('scope');

// $authTokenResponse = Bigcommerce::getAuthToken($object);

// Bigcommerce::configure(array(
    // 'client_id' => 'xxxxxxxx',
    // 'auth_token' => $authTokenResponse->access_token,
    // 'store_hash' => 'xxxxxxx'
// ));




//    Bigcommerce::configure(array(

        // 'store_url' => 'https://waverly.mybigcommerce.com/',
        // 'username' => 'WConnector',
        // 'api_key' => 'hey9oj8c0d020uoh8uh0uu70kfpl6cb'
    // ));

    Bigcommerce::verifyPeer(false);

    $ping = Bigcommerce::getTime();

    if ($ping) {
        //echo $ping->format('H:i:s');
    }
    Bigcommerce::failOnError();

    try {
        $orders = Bigcommerce::getOrders();

    } catch(Bigcommerce\Api\Error $error) {
        echo $error->getCode();
        echo $error->getMessage();
    }

    $products = Bigcommerce::getProducts();

    //echo '<pre>'; print_r($products); exit;

    echo '<pre>';

    foreach($products as $product) {
        //print_r($product);
        echo $product->name . '---------';
        //echo $product->price . '<br>';
    }