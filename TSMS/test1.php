<?php
$arr = array(1,2,3,4,5,6,7,8,9);

while (list($key, $value) = each($arr))
{
  //  unset($arr[$key + 1]);
    echo $value . PHP_EOL;
}
?>