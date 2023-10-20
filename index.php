<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Задание 5</title>
</head>
<body>
<?php
function setCacheCurrencyExchange($currency)
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
function viewCacheCurrencyExchange()
{
    if (file_exists("cache.txt"))
    {
        $cache = fopen("cache.txt", "r");
        $result = fgets($cache);
        echo $result;
    }
    else echo "Файл cache.txt не найден\n";
}

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
        setCacheCurrencyExchange("Обменный курс $value по ЦБ РФ на сегодня: {$this->data->Valute->$value->Value}\n");
    }
    public function showExchangeRate($value)
    {
        echo "Обменный курс $value по ЦБ РФ на сегодня: {$this->data->Valute->$value->Value}\n";
    }
}
function clientCode($valute)
{
    $date = getdate();
    $usd1 = CurrencyExchange::getInstance();
        if ($date["seconds"] > 30)
        {
            try {
                if (false == ($error = $usd1->getExchangeRate())) throw new Exception("сервер не доступен");
                    $usd1->updateExchangeRate($valute);
                    $usd1->showExchangeRate($valute);
                } catch (Exception $e){
                    echo $e->getMessage();
                    echo $e->getLine();
                }
        }
        else {viewCacheCurrencyExchange();}
}
clientCode("EUR");
?>
</body>
</html>