<?php

require 'vendor/autoload.php';

use Intervention\Image\ImageManagerStatic as Image;

Image::configure(['driver' => 'imagick']);

abstract class Shape
{
    public $color;
    public $borderThickness;
    public $position = [];

    public function __construct($color, $borderThickness, $position)
    {
        $this->color = $color;
        $this->borderThickness = $borderThickness;
        $this->position = $position;
    }

    public function draw($img)
    {
    }

    public function move($newPosition)
    {
        $this->position = $newPosition;
    }

    public function resize($newSize)
    {

    }

    public function newColor($newcolor)
    {
        $this->color = $newcolor;
    }

    public function newBorderThickness($newBorderThickness)
    {
        $this->borderThickness = $newBorderThickness;
    }


}

class Circle extends Shape
{
    public $radius;

    public function __construct($color, $borderThickness, $position, $radius)
    {
        parent::__construct($color, $borderThickness, $position);
        $this->radius = $radius;
    }

    public function draw($img)
    {
        $img->circle($this->radius, $this->position[0], $this->position[1], function ($draw) {
            $draw->border($this->borderThickness, '#00000');
            $draw->background($this->color);
        });

    }

    public function resize($newSize)
    {
        $this->radius = $this->radius * $newSize;// TODO: Implement resize() method.
    }
}

class Rectangle extends Shape
{
    public $sideA;
    public $sideB;

    public function __construct($color, $borderThickness, $position, $sideA, $sideB)
    {
        parent::__construct($color, $borderThickness, $position);
        $this->sideA = $sideA;
        $this->sideB = $sideB;
    }

    public function draw($img)
    {
        $img->rectangle($this->position[0], $this->position[1], $this->position[0]+$this->sideA, $this->position[1]+$this->sideB, function ($draw) {
            $draw->border($this->borderThickness, '#00000');
            $draw->background($this->color);
        });

    }

    public function resize($newSize)
    {
        $this->sideA = $newSize * $this->sideA;// TODO: Implement resize() method.
        $this->sideB = $newSize * $this->sideB;
    }

}

class Triangle extends Shape
{
    public $width;
    public function __construct($color, $borderThickness, $position,$width)
    {
        parent::__construct($color, $borderThickness, $position);
        $this->width = $width;
    }

    public function draw($img)
    {
        $p1_x = $this->position[0];
        $p1_y = $this->position[1]+($this->width/2);

        $p2_x = $this->position[0]+($this->width/2);
        $p2_y = $this->position[1];

        $p3_x = $this->position[0]+$this->width;
        $p3_y = $this->position[1]+($this->width/2);

        $points = array($p1_x, $p1_y, $p2_x, $p2_y, $p3_x, $p3_y);
        $img->polygon($points, function ($draw) {
            $draw->border($this->borderThickness, '#00000');
            $draw->background($this->color);
        });

    }

    public function resize($newSize)
    {
        $this->width = $newSize * $this->width;
    }
}

class Group
{
    public $nameGroup;
    public $shapes = [];

    public function __construct($nameGroup)
    {
        $this->nameGroup = $nameGroup;
    }

    public function draw($img)
    {
        foreach ($this->shapes as $shape) {
            $shape->draw($img);

        }
    }

    public function addShape(Shape $shape)
    {
        $this->shapes[] = $shape;
    }

    public function deleteShape(Shape $shape)
    {
        $index = array_search($shape, $this->shapes, true);
        unset($this->shapes[$index]);
    }

    public function resize($newResize)
    {
        foreach ($this->shapes as $shape) {
            $shape->resize($newResize);
        }
    }

    public function move($newPosition)
    {
        foreach ($this->shapes as $shape) {
            $shape->move([$shape->position[0] + $newPosition[0], $shape->position[1]+ $newPosition[1]]);
        }
    }
}

    $img = Image::canvas(800, 600);

    $circle1 = new Circle('#00abcd', 5, [200, 200], 100);
    $circle1->move([1,1]);
    $circle1->resize(2);

    $rectangle1 = new Rectangle('#000fda', 3,[100, 100], 10,10);
    $rectangle1->move([20,20]);
    $rectangle1->resize(2);

    $triangle1 = new Triangle('#00abdc', 2,[1,100], 200);
    $triangle1->newColor('#ff2122');
    $triangle1->newBorderThickness(4);

    $circle2 = new Circle('#00abcd', 5, [200, 200], 100);
    $circle2->move([300,55]);
    $circle2->newColor('#ff2122');

    $rectangle2 = new Rectangle('#ff2122', 3,[300, 300], 50,50);

    $group1 = new Group('Первая');
    $group1->addShape($circle2);
    $group1->addShape($rectangle2);
    $group1->move([200,100]);
    $group1->deleteShape($rectangle2);
    $group1->resize(2);
    $group1->draw($img);

    $group2 = new Group('Вторая');
    $group2->addShape($circle1);
    $group2->addShape($rectangle1);
    $group2->addShape($triangle1);
    $group2->move([100,200]);
    $group2->resize(2);
    $group2->draw($img);

    $img->save('bar.png');
    $im = file_get_contents("bar.png");
    header("Content-type: image/jpeg");
    echo $im;
?>
