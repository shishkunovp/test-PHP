<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Задание 5</title>
</head>
<body>
<?php
class Singleton
{
    private static $instances;
    protected function __construct() { }
    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances)) {
            self::$instances = new static();
        }
        return self::$instances;
    }
}
class CurrencyExchange extends Singleton
{
    private static $rates;
    public $data;
    public function getExchangeRate()
    {
        if (self::$rates === null) {
            self::$rates = json_decode(file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js'));
        }
        return self::$rates;
    }
    public function updateExchangeRate($value)
    {
        $this->data = $this->getExchangeRate();
        $this->setCacheCurrencyExchange("Обменный курс $value по ЦБ РФ на сегодня: {$this->data->Valute->$value->Value}\n");
    }
    public function showExchangeRate($value)
    {
        echo "Обменный курс $value по ЦБ РФ на сегодня: {$this->data->Valute->$value->Value}\n";
    }

    public function errorUrl()
    {
        $url = 'https://www.cbr-xml-daily.ru';
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
    public function setCacheCurrencyExchange($currency)
    {
        if (file_exists("cache.txt"))
        {
            $cache = fopen("cache.txt", "w");
            fwrite($cache, "$currency\n");
            fclose($cache);
            echo "В файл cache.txt добавлена запись\n";
        }
        else echo "Файл cache.txt не найден\n";
    }
    public function viewCacheCurrencyExchange()
    {
        if (file_exists("cache.txt"))
        {
            $cache = fopen("cache.txt", "r");
            $result = fgets($cache);
            echo $result;
        }
        else echo "Файл cache.txt не найден\n";
    }
}
function clientCode($valute)
{
    $date = getdate();
    $usd1 = CurrencyExchange::getInstance();
    if ($date["seconds"] > 30)
    {
            try {
            if ($usd1->errorUrl() === false) throw new Exception("сервер не доступен");
            $usd1->updateExchangeRate($valute);
            $usd1->showExchangeRate($valute);
            } catch (Exception $e){echo $e->getMessage();}
    }
    else {$usd1->viewCacheCurrencyExchange();}
}
clientCode("EUR");
?>
</body>
</html>