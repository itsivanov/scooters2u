<img src="<?php echo $_SERVER['SERVER_NAME'] ?>/img/logo.png" width="70px;">
<br>
<br>
<?php echo "<h2>Your order is received ".$_SERVER['SERVER_NAME']."</h2>"; ?>
<br>
<?php echo "<h4>Total amount ".$Order['amount']." USD</h4>"; ?>
<br>
<?php echo count($OrderItem)." items"; ?>
<br>
<h4>Billing Info</h4>
<table>
<?php
    foreach($OrderBillingInfo as $key=>$value){
        echo "<tr>
                <td width='150' style='padding: 10px; border: 1px solid #7c7c7c;'>".str_replace('_', ' ', $key)."</td>
                <td style='padding: 10px; border: 1px solid #7c7c7c;'>".$value."</td>
             </tr>";
    }
?>
</table>