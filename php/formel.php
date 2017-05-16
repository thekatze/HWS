<?php

    $buyerRes = 0;
    $sellerRes = 0;
    $costDollaz = 0;

    $var = $buyerRes+1/(abs($sellerRes+1)); //=1

    $var2 = abs(log($var, 10));             //=0

    $var3 = pow(($buyerRes+1), 1/1.7);          //=0

    $var4 = pow($dollaz_cost+1/10, -1/2);   //=3,16...

    $out = ceil($var2 * $var3 + $var4);

    for ($buyerRes=0; $buyerRes < 100; $buyerRes += 10) {
        echo "<br/><br/>";
        for ($sellerRes=0; $sellerRes < 100; $sellerRes += 10) {
            echo "<br/>";
            for ($dollaz_cost=0; $dollaz_cost < 100; $dollaz_cost += 10) {
                $var = ($buyerRes+1)/abs($sellerRes+1); //=1

                $var2 = abs(log($var, 10));             //=0

                $var3 = pow(($buyerRes+1), 1/1.7);          //=0

                $var4 = pow($dollaz_cost+1/10, -1/2);   //=3,16...

                $out = ceil($var2 * $var3 + $var4);
                echo "<span>$out   $buyerRes/$sellerRes=$var    $var2    $var3    $var4<span><br/>";
            }
        }
    }

    $out = ceil(abs(log(($buyerRes+1)/abs($sellerRes+1), 10)) * pow(($buyerRes+1), 1/1.7) + 1);
?>
