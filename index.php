<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Задание 7</title>
    <link href="styles/style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php
require 'vendor/autoload.php';

class Singleton
{
    private static $instances = [];
    protected function __construct() { }
    protected function __clone() { }
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }
}

class Coinmarketcap extends Singleton {
    private $data;
    public function getCryptoPrice()
    {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'start' => '1',
            'limit' => '50',
            'convert' => 'USD'
        ];
        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: bb5a900a-4c93-4f35-8beb-3ed9ef5f186f'
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL
        $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));
        $response = curl_exec($curl);// Send the request, save the response
        curl_close($curl); // Close request
        return json_decode($response); // print json decoded response;
    }
    public function viewCryptoPrice ()
    {
        try {
            $this->data = $this->getCryptoPrice();
            if ($this->data === NULL) throw new Exception('Ошибка подключения к coinmarketcap.com');
            $i = 0;
            foreach ($this->data->data as $item) {
                $i++;
                echo "
                <tr>
                    <td>{$i}</td>
                    <td>{$item->name}</td>
                    <td>{$item->quote->USD->price}</td>
                </tr>";

            }
        }
        catch (Exception $error)
        {
            echo $error->getMessage();
        }
    }
}

class Investing extends Singleton {
    private $data;
    public function errorUrl()
    {
        $url = 'https://investing-cryptocurrency-markets.p.rapidapi.com';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_result = curl_exec($ch);
        curl_close($ch);
        if ($curl_result !== false) {
            return true;
        } else {
            return false;
        }
    }
    public function getCryptoPrice()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://investing-cryptocurrency-markets.p.rapidapi.com/coins/list?edition_currency_id=12&time_utc_offset=28800&lang_ID=1&sort=TOTAL_VOLUME_DN%20&page=1', [
            'headers' => [
                'X-RapidAPI-Host' => 'investing-cryptocurrency-markets.p.rapidapi.com',
                'X-RapidAPI-Key' => '1dea2305camsh2bbd42fe6be3da0p17bd83jsn6e651ffc4e11',
            ],
        ]);
        return json_decode($response->getBody());
    }
    public function viewCryptoPrice ()
    {
        $this->data = $this->getCryptoPrice();
        $i = 0;
        foreach ($this->data->data as $item) {
            foreach ($item->screen_data->crypto_data as $crypto_datum){
                $i++;
                echo "
                <tr>
                    <td>{$i}</td>
                    <td>{$crypto_datum->name}</td>
                    <td>{$crypto_datum->inst_price_usd}</td>
                </td>";
            }
        }
    }
}

$coinmarketcup = Coinmarketcap::getInstance();
$investing = Investing::getInstance();
?>

<div class="coinmarketcap">
<table>
    <caption>coinmarketcap.com</caption>
    <tr>
        <th>№ п/п</th>
        <th>Наименование криптовалюты</th>
        <th>Цена, $ </th>
    </tr>
    <?php $coinmarketcup->viewCryptoPrice(); ?>
</table>
</div>
<div class="investing">
<table>
    <caption>investing.com</caption>
    <tr>
        <th>№ п/п</th>
        <th>Наименование криптовалюты</th>
        <th>Цена, $</th>
    </tr>
    <?php
    try {
        if ($investing->errorUrl() === false) throw new Exception("Ошибка подключения к investing.com");
            $investing->viewCryptoPrice();
    } catch (Exception $e){echo $e->getMessage();}
    ?>
</table>
</div>

</body>
</html>

