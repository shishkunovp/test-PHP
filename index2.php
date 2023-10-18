<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Задание 4</title>
</head>
<body>
<?php
interface Observer
{
    public function update(Blog $blog);
}
class Blog
{
    public $note = [];
    public $subscribes = [];
    public function attach(Observer $observer)
    {
        $this->subscribes[] = $observer;
        echo "Подписчик добавлен\n";
    }
    public function detach(Observer $observer)
    {
        $index = array_search($observer, $this->subscribes, true);
        unset($this->subscribes[$index]);
        echo "Подписчик удален\n" ;
    }
    public function notify()
    {
        echo "Уведомление подписчиков...\n";
        foreach ($this->subscribes as $subscribe){
            $subscribe->update($this);
        }
    }
    public function addNote($message)
    {
        $this->note[] = $message;
    }
    public function viewNote()
    {
        var_dump($this->note);
    }

}
class SubscribeA implements Observer
{
    public $name;
    public function __construct($name)
    {
        $this->name = $name;
    }
    public function update(Blog $blog)
    {
        echo "$this->name вышла новая статья в категории Кулинария\n"; // TODO: Implement update() method.
    }
}
class SubscribeB implements Observer
{
    public $name;
    public function __construct($name)
    {
        $this->name = $name;
    }
    public function update(Blog $blog)
    {
        echo "$this->name вышла новая статья в категории Спорт\n"; // TODO: Implement update() method.
    }
}
$blog1 = new Blog();
$blog1->addNote("Статья1");
$blog1->addNote("Статья2");
$subscribe1 = new SubscribeA("Алексей");
$subscribe2 = new SubscribeB("Владимир");
$subscribe3 = new SubscribeB("Дмитрий");
$subscribe4 = new SubscribeA("Павел");
$blog1->attach($subscribe1);
$blog1->attach($subscribe2);
$blog1->attach($subscribe3);
$blog1->attach($subscribe4);
$blog1->notify();
$blog1->detach($subscribe2);
$blog1->detach($subscribe4);
$blog1->notify();


?>
</body>
</html>