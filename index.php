<?php

require 'worker.php';

class Car
{
    private $_color;
    private $_dataCreat;
    private $_work = false;
    public $broken = false;
    private $_remainsDistance = 100;
    private $_distance;
    public $countTimeWork = 0;
    private $_IDCar;


    public static $cars = array();

    /*В конструкторе добавляем цвет и текущую дату создания объекта , и уникальный ID для каждой машины
    * по нему будем удалять из массива
     */
    function __construct($color)
    {
        $this->_addColor($color);
        $this->_dataCreat = date("l dS of F Y h:i:s A");
        self::$cars[] = $this;
        end(self::$cars);
        $this->_IDCar = key(self::$cars);
        echo "Your creat new car with ID = " . $this->_IDCar . " in " . $this->_dataCreat . "<br />";
        echo "Your car have " . $this->_color . " color<br/>";
        echo "<br />";
    }

    /*
     * Проверяем цвет если string то добавляем , или добавляем дефолтное
     */
    private function _addColor($color)
    {
        if (is_string($color)) {
            $this->_color = $color;
        } else {
            $this->_color = 'white';
        }
    }

    static function getCars($i)
    {
        return self::$cars[$i];
    }

    /*
     * получаем значение закрытых переменных
     */
    function get($attr)
    {
        if ($attr == 'color') {
            return $this->_color;
        }
        if ($attr == 'data') {
            return $this->_dataCreat;
        }
        if ($attr == 'remainsDistance') {
            return $this->_remainsDistance . " km";
        }
        if ($attr == 'work') {
            return $this->_work;
        }


    }

    /*
     * проверяем если машина не работает или не сломана , то заводим её
     * если наоборот то выводим соответствующие сообщения
     */

    function start()
    {
        if (!$this->_work && !$this->broken) {
            $this->_work = true;
            echo "Car " . $this->_IDCar . " start<br />";
        } elseif ($this->broken) {
            echo "Your car broken , he should be fixed!!!!!<br/>";
        } else {
            echo "Already car start<br />";
        }
    }

    /*
     * аналогично предыдущему методу
     */

    function stop()
    {
        if ($this->_work) {
            $this->_work = false;
            echo "Your car stop<br />";
        } else {
            echo "Already car stop<br />";
        }
    }

    /*
     * проверяем если машина заведена ,не сломана и корректно введено расстояние
    * только числовое значение больше 0 ,если один из пунктов несоответствен то выводим соответствующее сообщение
    * если расстояние больше 100 то удаляем по ID из массива CARS


     */

    function run($d)
    {

        if ($this->_work) {

            if (is_int($d) && $d > 0) {
                if ($d > $this->_remainsDistance) {
                    echo "Your don`t run more , then 100 km <br />";
                    echo "Your can run " . $this->_remainsDistance . " only<br/>";
                } else {
                    echo 'Your run on ' . $d . ' km<br />';
                    $this->_remainsDistance -= $d;
                    $this->_distance += $d;
                    echo "Your run total " . $this->_distance . " km <br />";

                    if ($this->_remainsDistance == 0 || $this->_remainsDistance < 0) {
                        unset(Car::$cars[$this->_IDCar]);
                        echo '<br />';
                        echo "Your Car was delete from array!!!<br /> ";

                    }
                }
            } else {
                echo "Should be a number greater than 0!!!!!";
            }


        } else {
            echo "Your need firstly car start<br />";
        }


    }

    /*
     * при вызове метода  к каждому работающему элементу  массива Cars добовляется +1 час
     * если превышает 10
     */
    static function afterHour()
    {
        echo '<br />';

        echo "After hour<br />";
        foreach (self::$cars as $key => $tempCar) {

            if ($tempCar->get('work') && !($tempCar->broken)) {

                $tempCar->countTimeWork++;
                echo "Your car#" . $key . "  work " . $tempCar->countTimeWork . " hours";
                echo '<br />';
                if ($tempCar->countTimeWork >= 10) {// если время работы превышает 10 то выводится сообщение о поломке
                    echo '<br />';
                    echo "Car# " . $key . " broken.Need fix<br />";
                    $tempCar->broken = true;
                    $tempCar->stop();
                }
            } else {
                echo "Car# " . $key . " not start now or broken<br />";
            }
        }

        echo '<br />';

    }
}

$car = new Car('gold');
$car1 = new Car('green');
$car2 = new Car('green');
$car3 = new Car();

Worker::fixCar($car3);

$car3->run();
$car3->start();
$car3->run();
$car3->run(10);
$car3->run(25);
$car3->run(65);
$car2->start();
echo(Car::getCars(2)->get('color'));


for ($i = 0; $i < 4; $i++) {
    Car::afterHour();
}
$car1->start();
for ($i = 0; $i < 4; $i++) {
    Car::afterHour();
}
$car->start();
for ($i = 0; $i < 4; $i++) {
    Car::afterHour();
}

Worker::fixCar($car2);