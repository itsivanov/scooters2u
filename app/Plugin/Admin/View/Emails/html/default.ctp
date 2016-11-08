<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_SERVER['SERVER_NAME'] ?></title>
</head>
<body>
<section style='width:600px; margin: 30px auto; background-color: #DFDFDF;'>
	<br>
	<br>
	<div style="width:100%;"><img src="<?php echo $_SERVER['SERVER_NAME'] ?>/img/logo.png" width="190px"></div>
	<br>
	<br>
	<div style="padding:10px; font-style: italic; min-height: 250px; border-top: 1px solid #d90004; border-bottom: 1px solid #d90004;">
		 <?php echo $text; ?>
	</div>
	<br>
	<br>
	<div style="width:100%;">
		<span style='margin-left: 50px;'><?php echo $_SERVER['SERVER_NAME'] ?> | <?php echo date("l"); ?></span>
	</div>
</section>
</body>
</html>