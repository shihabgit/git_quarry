<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <title><?php echo $title?></title>
    <base href="<?php echo base_url(); ?>" >
    <link rel="stylesheet" type="text/css" href="css/login/style.css" />
    <link rel="stylesheet" type="text/css" href="css/error_tooltip.css" />
</head>
<body>
<div id="container">
<div class="content">
<img src="images/login/crush2.png" style="width:300px;">
</div>
<div class="content">
<?php echo form_open() ?>
  <h1><?php echo $heading?></h1>
  <div class="inset">
  <p>    <label for="username">USERNAME</label>
    <input type="text" name="email" id="email">
    <div class="dialog-box-border"> "Hello World" </div>
  </p>
  <p>
    <label for="password">PASSWORD</label>
    <input type="password" name="password" id="password">
  </p>
  <p>
    <input type="checkbox" name="remember" id="remember">
    <label for="remember">Remember me for 14 days</label>
  </p>
  </div>
  <p class="p-container">
    <span>Forgot password ?</span>
    <input type="submit" name="go" id="go" value="Log in">    
  </p>
  
  
    
  
  
  
    
<?php echo form_close(); ?>
</div>
    
</div>
    
    
    
    
</body>
</html>
