<!DOCTYPE html>
<html class="gt-ie8 gt-ie9 not-ie"> 
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">

        <!-- Pixel Admin's stylesheets -->
        <link href="<?php echo base_url() ?>assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>assets/admin/css/pixel-admin.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>assets/admin/css/pages.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>assets/admin/css/rtl.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url() ?>assets/admin/css/themes.min.css" rel="stylesheet" type="text/css">
        <style>
            #signin-demo {
                position: fixed;
                right: 0;
                bottom: 0;
                z-index: 10000;
                background: rgba(0,0,0,.6);
                padding: 6px;
                border-radius: 3px;
            }
            #signin-demo img { cursor: pointer; height: 40px; }
            #signin-demo img:hover { opacity: .5; }
            #signin-demo div {
                color: #fff;
                font-size: 10px;
                font-weight: 600;
                padding-bottom: 6px;
            }
        </style>
    </head>
    <body class="theme-default page-signin">
        
       
        <div id="page-signin-bg">
            <div class="overlay"></div>
            <img src="<?php echo base_url() ?>images/signin-bg-1.jpg" alt="">
        </div>
        <div class="signin-container">
            <div class="signin-info">
                <a href="<?php echo site_url('admin_bak') ?>" class="logo">
                    <img src="<?php echo base_url() ?>images/logo-big.png" alt="" style="margin-top: -5px;">&nbsp;
                    earlypay
                </a> 
                <div class="slogan">
                    Simple. Flexible. Powerful.
                </div>
                <ul>
                    <li><i class="fa fa-sitemap signin-icon"></i> Flexible modular structure</li>
                    <li><i class="fa fa-file-text-o signin-icon"></i> LESS &amp; SCSS source files</li>
                    <li><i class="fa fa-outdent signin-icon"></i> RTL direction support</li>
                    <li><i class="fa fa-heart signin-icon"></i> Crafted with love</li>
                </ul> <!-- / Info list -->
            </div>
            <div class="signin-form">
                <!-- Form -->
                <div style="margin-left:150px; margin-top:5px;">
                    <font style="color:#FF0000;font-size:14px">
                    <?php
                    echo validation_errors('<p class="error">');
                    echo '<p class="error">' . $this->session->flashdata('message') . '</p>';
                    ?>

                    </font>
                </div>
                <?php echo form_open("admin_bak/forgotpass"); ?>
                <div class="sign_box" style="margin-top:20px;">	
                        <p style="font-weight:bold;"> Please enter your username/email then click on the submit button. We'll email you a new password. </p>
                        <div class="form-group w-icon">
                            <label>Email :</label>
                            <input type="text" name="email" id="email" class="input form-control input-lg" placeholder="Enter your email" />
                        </div>
                        <div class="form-actions">
                            <input type="submit" name="submit" value="Submit" class="signin-btn bg-primary" />
                            <input type="button" name="cancel" value="Back To Login" class="signin-btn bg-primary" onclick="window.location = '<?php echo site_url('admin_bak') ?>'" />
                        </div>
                    </div>
                <?php echo form_close(); ?>

<!--                <div class="signin-with">
                    <a href="<?php //echo site_url('admin_bak') ?>" class="signin-with-btn" style="background:#4f6faa;background:rgba(79, 111, 170, .8);">Sign In with <span>Facebook</span></a>
                </div>-->
                <div class="password-reset-form" id="password-reset-form">
                    <div class="header">
                        <div class="signin-text">
                            <span>Password reset</span>
                            <div class="close">&times;</div>
                        </div> 
                    </div> 
                  
                </div>
            </div>
        </div>       
        <script type="text/javascript"> window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js">' + "<" + "/script>");</script>
        <script src="<?php echo base_url() ?>js/bootstrap.min.js"></script>
        <script src="<?php echo base_url() ?>js/pixel-admin.min.js"></script>

        <script type="text/javascript">
             // Resize BG
             init.push(function () {
                 var $ph = $('#page-signin-bg'),
                         $img = $ph.find('> img');

                 $(window).on('resize', function () {
                     $img.attr('style', '');
                     if ($img.height() < $ph.height()) {
                         $img.css({
                             height: '100%',
                             width: 'auto'
                         });
                     }
                 });
             });

             // Show/Hide password reset form on click
             init.push(function () {
                 $('#forgot-password-link').click(function () {
                     $('#password-reset-form').fadeIn(400);
                     return false;
                 });
                 $('#password-reset-form .close').click(function () {
                     $('#password-reset-form').fadeOut(400);
                     return false;
                 });
             });

             // Setup Sign In form validation
             init.push(function () {
                 $("#signin-form_id").validate({focusInvalid: true, errorPlacement: function () {}});

                 // Validate username
                 $("#username_id").rules("add", {
                     required: true,
                     minlength: 3
                 });

                 // Validate password
                 $("#password_id").rules("add", {
                     required: true,
                     minlength: 6
                 });
             });

             // Setup Password Reset form validation
             init.push(function () {
                 $("#password-reset-form_id").validate({focusInvalid: true, errorPlacement: function () {}});

                 // Validate email
                 $("#p_email_id").rules("add", {
                     required: true,
                     email: true
                 });
             });

             window.PixelAdmin.start(init);
        </script>

    </body>
</html>



<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $title;?></title>

</head>
<body>

<div class="wrapper">
		<div class="top">
			<div class="floatL">&nbsp;</div>
		</div>
        
        <div style="width:550px; margin: auto; padding:25px 0;">
        
		<div class="middle">
			<div class="middle_top"><div class="sgnin_tab">
<div class="sgnin_tab_mid">Site Administration</div>
                
            </div></div>
<div class="middle_mid">
<div style="margin-left:150px; margin-top:5px;" class="error">
<?php
echo validation_errors('<p class="error">');
echo $this->session->flashdata('message');
?>
</div>
<div class="middle_right">
<?php echo form_open("admin_bak/forgotpass");?>
<div class="sign_box" style="margin-top:20px;">	
<p style="font-weight:bold;"> Please enter your username/email then click on the submit button.
    We'll email you a new password.
</p>
				<p>
				<label>Email :</label>
				  <input type="text" name="email" id="email" class="input" />
				</p>
				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <input type="submit" name="submit" value="Submit" />
				  <input type="button" name="cancel" value="Back To Login" onclick="window.location='<?php echo site_url('admin_bak')?>'" />
				</p>
	</div>			
   						 <?php echo form_close();?>
				</div>
			</div>
			<div class="middle_bottom">&nbsp;</div>

			
		</div>
        </div>
        
		<div class="bottom"></div>
	</div>    
	</body>
</html>-->