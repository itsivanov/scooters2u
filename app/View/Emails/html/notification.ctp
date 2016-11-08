<img src="<?php echo $_SERVER['SERVER_NAME'] ?>/img/logo.png" width="70px;">
<br>
<br>
<?php if(isset($header)){ echo '<h2>'.$header.'</h2>'; } ?>
<br>
<br>
<?php if(isset($text)){ echo '<p>'.$text.'</p>'; } ?>
<br>
<br>
<?php if(isset($user)){ echo '<a href="'.$_SERVER['SERVER_NAME'].'/admin/users/edit/'.$user.'">User '.$user.'</p>'; } ?>