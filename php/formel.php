<?php

    $buyerRes = 100;
    $sellerRes = 1000;
    $costDollaz = 20;

    $var = $buyerRes/(abs($sellerRes)+1);

    $var2 = abs(log($var, 10));

    $var3 = pow($buyerRes, 1/1.7);

    $var4 = pow($dollaz_cost/10, -1/2);

    $out = ceil($var2 * $var3 + $var4);



    $out = ceil(abs(log($buyerRes/(abs($sellerRes)+1), 10)) * pow($buyerRes, 1/1.7) + pow($costDollaz/10, -1/2));
    echo $out;
?>
