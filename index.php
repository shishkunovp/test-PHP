<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Задание 1</title>
</head>
<body>

<?php
Class MenuItem {
    public $name = "Название блюда";
    public $price = "Цена блюда";
    public $description = "Описание блюда";
    function __construct($name,$price,$description)
    {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    public function __toString(){
        return "{$this->name} - {$this->price}$: {$this->description}";
    }
}
Class Order {
    private $items = [];

    public function addMenuItem(MenuItem $menuItem){
        $this->items[] = $menuItem;
    }
    public function calculateTotal(){
        $sum = 0;
        echo "<br>Заказано: ";
           foreach ($this->items as $val) {
               $sum += $val->price;
               echo "{$val->name} ";
           }
           echo "<br>Сумма заказа: {$sum}$ <br>";
    }

}

$dish1 = new MenuItem("Паста", "400", "Паста с креветками на оливковом масле");
$dish2 = new MenuItem("Рис", "250", "Рис с мясом на сливочном масле");
$dish3 = new MenuItem("Каша", "350", "Каша с рыбой");
$dish4 = new MenuItem("Овощной салат", "100", "Капуста, морковь, масло");

echo "Меню ресторана:";
echo "<br>Блюдо 1<br>{$dish1}";
echo "<br>Блюдо 2<br>{$dish2}";
echo "<br>Блюдо 3<br>{$dish3}";
echo "<br>Блюдо 4<br>{$dish4}";
echo "<br>";
echo "<br>Заказ №1";
$order1 = new Order();
$order1->addMenuItem($dish1);
$order1->addMenuItem($dish2);
$order1->calculateTotal();
echo "<br>Заказ №2";
$order2 = new Order();
$order2->addMenuItem($dish1);
$order2->addMenuItem($dish3);
$order2->calculateTotal();
echo "<br>Заказ №3";
$order3 = new Order();
$order3->addMenuItem($dish2);
$order3->addMenuItem($dish4);
$order3->calculateTotal();

?>
</body>
</html>

