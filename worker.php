<?php
/**
 * Created by PhpStorm.
 * User: vasy
 * Date: 25.03.16
 * Time: 15:30
 */

class Worker{
    static function fixCar($obj){
        if(property_exists($obj, "broken")){
            if($obj->broken == true){
                $obj->broken = false;
                $obj->countTimeWork=0;
                echo "Your car fixed.Good road )<br />";
                return;
            }
            else{
                echo 'This car don`t need fix!!!<br />';
                echo '<br />';
            }

        }else{
            echo "Sorry , can not fix you car<br />";
            return;
        }

    }
}