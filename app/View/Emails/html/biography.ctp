<img src="<?php echo $_SERVER['SERVER_NAME'] ?>/img/logo.png" width="70px;">
<br>
<br>
<?php if(isset($header)){ echo '<h2>'.$header.'</h2>'; } ?>
<br>
<br>
<div>
    <h2 style="font-size: 16px;"><strong>Artist:</strong>  <?php if(isset($name)){ echo $name; } ?>
<br>
<br>
    <p style="font-size: 14px;"><strong>Biography:</strong> <?php if(isset($biography)){ echo $biography; } ?></p>
</div>