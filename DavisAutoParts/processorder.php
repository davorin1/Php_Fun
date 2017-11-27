<?php

    // Declare variables and constants
    $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

    $date = date('H:i, jS F Y');

    // create short variable names
    $tireqty = $_POST['tireqty'];
    $oilqty = $_POST['oilqty'];
    $sparkqty = $_POST['sparkqty'];
    $find = $_POST['find'];
    $address = $_POST['address'];

    $totalqty = 0;
    $totalqty = $tireqty + $oilqty + $sparkqty;
    $totalamount = 0.00;

    define('TIREPRICE', 100);
    define('OILPRICE', 10);
    define('SPARKPRICE', 4);

    $discount = 0;

    // Tires discount
    if ($tireqty < 10) {
        $discount = 0;
    } elseif (($tireqty >= 10) && ($tireqty <= 49)) {
        $discount = 5;
    } elseif (($tireqty >= 50) && ($tireqty <= 99)) {
        $discount = 10;
    } elseif ($tireqty >= 100) {
        $discount = 15;
    }

    // Summarize total
    $tirestotal = $tireqty*TIREPRICE;
    $tirestotal = $tirestotal - ($tirestotal*($discount/100));

    $totalamount = $tirestotal
                 + $oilqty*OILPRICE
                 + $sparkqty*SPARKPRICE;
                $taxrate = 0.10;






?>

<html>
<head>
    <title>Davi’s Auto Parts - Order Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <a href="orderform.html" class="back"><</a>
    <h1>Davi’s Auto Parts</h1>
    <h2>Order Results</h2>
    <?php
    echo '<p>Order processed at ';
    echo $date;
    echo '</p>';
    echo '<p>Your order is as follows: </p>';
    if ($totalqty == 0) {
        echo '<p style="color:#f00">You did not order anything on previous page</p>';
        exit;
    } else {
        if ($tireqty > 0) echo $tireqty.' tires<br/>';
        if ($oilqty > 0) echo $oilqty.' bottles of oil<br/>';
        if ($sparkqty > 0) echo $sparkqty.' spark plugs<br/>';
    }
    echo '<h4>Total items ordered: '.$totalqty. '</h4>';
    if ($tireqty >= 10) {
        echo '<h4>Tires discount: '.$discount.'%</h4>';
    }
    echo '<h4>Subtotal: '.number_format($totalamount,2).' $</h4>';
    $totalamount = $totalamount * (1 + $taxrate);
    echo '<h4>Total including tax: '.number_format($totalamount,2).' $</h4>';

    echo '<span class="show-shipping">Show shipping costs</span>';
    echo '<div class="shipping"><table><tr><th>Distance</th><th>Cost</th></tr>';
    $distance = 50;
    while($distance <= 250) {
        echo '<tr>
         <td>under '.$distance.' km</td>
         <td>'.($distance/10).' $</td>
         </tr>';
        $distance += 50;
    }
    echo '</table></div>';

    switch($find) {
        case "a" :
            echo '<span>Regular customer</span>';
            break;
        case "b" :
            echo '<span>Customer referred by TV advert.</span>';
            break;
        case "c" :
            echo '<span>Customer referred by phone directory</span>';
            break;
        case "d" :
            echo '<span>Customer referred by word of mouth</span>';
            break;
        default :
            echo "<span>We don't know how this customer found us.</span>";
            break;
    }

    // open file and write
    @ $fp = fopen('$DOCUMENT_ROOT/../orders.txt','ab');
    flock($fp, LOCK_EX);
    if(!$fp) {
        echo "<p><strong>Order could not be processed at this time.</strong></p>";
        exit;
    }
    $outputstring = $date."\t".$tireqty." tires \t".$oilqty." oil\t".$sparkqty." spark plugs\t".$totalamount."$ \t".$address."\n";
    fwrite($fp, $outputstring);
    flock($fp, LOCK_UN);
    fclose($fp);
    echo "<p>Order written</p>";

    ?>
</main>
<script src="scripts/jquery-3.2.1.min.js"></script>
<script>
    $('.show-shipping').click(function() {
        $('.shipping').slideToggle();
    })
</script>
</body>
</html>
