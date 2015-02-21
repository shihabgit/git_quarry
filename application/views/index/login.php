<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <title>Login</title>
    <base href="<?php echo base_url(); ?>" >
    <link rel="stylesheet" type="text/css" href="css/login/style.css" />
    <link rel="stylesheet" type="text/css" href="css/error_tooltip.css" />
</head>
<body>
<div id="container">
    <?php 
if(!empty($message) && !empty($message_level))
{
    if($message_level==1)
        echo '<div class="log-success"><span class="log-span">Success: </span> '.$message. '</div> ';
    if($message_level==2)
        echo '<div class="log-fail"><span class="log-span">Error: </span> '.$message. '</div> ';
}

?>
<div class="content">
    <img src="images/login/logo.png" id="logo"  >
</div>
<div class="content">



<?php echo form_open("index/login");?>
  <h1><?php echo lang('login_heading');?></h1>
<!--    <p><?php //echo lang('login_subheading');?></p>-->

<div class="inset">
    
    
    
  <p>

<?php echo lang('login_identity_label', 'identity');?>
    <?php echo form_input($identity);?>
    <?php echo form_error('identity'); ?>
  </p>
  
  
  
  <p>

    <?php echo lang('login_password_label', 'password');?>
    <?php echo form_input($password);?>
    <?php echo form_error('password'); ?>
  </p>
<!--  <p>

      <?php //echo lang('login_remember_label', 'remember');?>
    <?php //echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
  </p>-->
  </div>

<p class="p-container">
<!--    <span><a href="forgot_password"><?php //echo lang('login_forgot_password');?></a></span>-->
    <?php echo form_submit('submit', lang('login_submit_btn'));?>
  </p>

<?php echo form_close();?>


    
  
</div>
    
</div>
</body>
</html>
