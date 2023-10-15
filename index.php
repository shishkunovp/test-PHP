<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Задание 2</title>
</head>
<body>
<?php
interface FurnitureFactory{
    public function createChair();
    public function createTable();
}
abstract class Chair{
    abstract public function sit();
}
abstract class Table{
    abstract public function work();
}
class VictorianChair extends Chair{
    public function sit()
    {
        echo "Викторианский стул";
    }
}
class ModernChair extends Chair{
    public function sit()
    {
        echo "Модерн стул";
    }

}
class VictorianTable extends Table{
    public function work()
    {
        echo "Викторианский стол";
    }
}
class ModernTable extends Table{
    public function work()
    {
        echo "Модерн стол";
    }
}
class VictorianFurnitureFactory implements FurnitureFactory{
    public function createChair()
    {
        return new VictorianChair();// TODO: Implement createChair() method.
    }
    public function createTable()
    {
        return new VictorianTable();  // TODO: Implement createTable() method.
    }
}
class ModernFurnitureFactory implements FurnitureFactory{
    public function createChair()
    {
       return new ModernChair(); // TODO: Implement createChair() method.
    }
    public function createTable()
    {
        return new ModernTable();   // TODO: Implement createTable() method.
    }
}
function clientCode(FurnitureFactory $factory)
{
    $product1 = $factory->createChair();
    $product2 = $factory->createTable();
    echo $product1->sit()."<br>";
    echo $product2->work()."<br>";
}
clientCode(new VictorianFurnitureFactory());
clientCode(new ModernFurnitureFactory());
?>
</body>
</html>

