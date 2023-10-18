<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Задание 3</title>
</head>
<body>
<?php
class Singleton
{
    private static $instances = [];
    protected function __construct() { }
    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }
}
class VisitLog extends Singleton
{
    private static $visits = [];

    public function logVisit($visitor,$ip)
    {
         self::$visits[$visitor] = $ip;
    }

    public function showVisit()
    {
        echo "Журнал посещений:<br>";
        foreach (self::$visits as $key=>$value)
        {
            echo "Пользователь: $key IP адрес: $value<br>";
        }
    }
}
function clientCode()
{
    $s1 = VisitLog::getInstance();
    $s1->logVisit("andrey", "10.162.154.211");
    $s1->logVisit("ivan", "123.162.231.124");
    $s1->logVisit("sergey", "198.162.124.166");
    $s1->logVisit("pavel", "145.125.255.254");
    $s1->showVisit();
    }
clientCode();
?>
</body>
</html>