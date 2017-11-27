<?php
    // create short variable name
    $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Davi's Auto Parts - Customer orders</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Davi’s Auto Parts</h1>
    <h2>Customer Orders</h2>
    <div class="customer-orders">

        <?php
        @ $fp = fopen('$DOCUMENT_ROOT/../orders.txt','rb');
        flock($fp, LOCK_SH); // Lock file for reading

        // Read from the file
        if(!$fp) {
            echo "<p><strong>No orders pending. Please try again later.</strong></p>";
            exit;
        }
        while (!feof($fp)) {
            $order = fgets($fp,999);
            echo $order."<br/>";
        }

        flock($fp,LOCK_UN); // release read lock
        fclose($fp);
        ?>

    </div>

</main>
</body>
</html>