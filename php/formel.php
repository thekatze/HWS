<?php

    $buyerRes = 0;
    $sellerRes = 0;
    $costDollaz = 0;

    $var = $buyerRes+1/(abs($sellerRes+1)); //=1

    $var2 = abs(log($var, 10));             //=0

    $var3 = pow($buyerRes, 1/1.7);          //=0

    $var4 = pow($dollaz_cost+1/10, -1/2);   //=3,16...

    $out = ceil($var2 * $var3 + $var4);

    for ($buyerRes=0; $buyerRes < 100; $buyerRes++) {
        for ($sellerRes=0; $sellerRes < 100; $sellerRes++) {
            for ($dollaz_cost=0; $dollaz_cost < 100; $dollaz_cost++) {
                $out = ceil(abs(log($buyerRes+1/(abs($sellerRes+1))), 10) * pow($buyerRes, 1/1.7) + pow($dollaz_cost+1/10, -1/2));
                echo $out+" ,";
            }
        }
    }
?>
