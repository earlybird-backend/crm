<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->data['title']; ?></title>
        <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.bxslider.min.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/style_front.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/style1_front.css">

        <link rel="stylesheet" href="../../assets/css/animate.css">

        <link rel="stylesheet" type="text/css" href="../../assets/css/sdstyle.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/responsive.css">

    </head>
    <body class="bg-white">
        <section class="bg-white">
            <div class="bag-sec-wrap">
                <div class="bag-sec">

                </div>
                <div class="bag-wrap">
                    <div class="wrap-sec">
                        <div class="img-sec">
                            <a href="<?php echo site_url() ?>" class="logo">
                                <img src="../../assets/images/logo_front.png">
                            </a>
                            <?php
                                if ($this->session->flashdata('message')) { ?>
								<div class="forget-green">
                                <p class="green-text" style="text-align:center;"><?php   echo $this->session->flashdata('message'); ?></p></div>
                              <?php  }
                                ?>
                            
                        </div>
                         <div class="row step no-min-h login-sec">

                            <?php echo form_open_multipart('auth/forgetpassword', array('name' => 'login',
                                'id' => 'login','class' => 'panel register')); ?>

                            <div class="login-user-type text-center margin_40">

                                <div class="login-wrap">
                                    <input type="radio" name="type" id="customer" value="customer"  <?php echo (set_value('type') == 'customer') ? 'checked' : ''; ?> checked="checked" /> 
                                    <label for="customer"><span class="img-wrap-uset"><img src="../../assets/img/icon1.png"></span> <span>Buyer</span></label>
                                </div>


                                <div class="login-wrap">
                                    <input type="radio" name="type" id="customer2" value="supplier" <?php echo (set_value('type') == 'supplier') ? 'checked' : ''; ?> />	
                                    <label for="customer2"><span class="img-wrap-uset"><img src="../../assets/img/icon2.png">	</span> <span>Supplier</span></label>
                                </div>

                            </div>


                            <div class="form-group">   
                                <input type="EmailAddress" name="EmailAddress" id="EmailAddress" tabindex="1" 
                                       class="form-control" placeholder="EmailAddress" 
                                       value="<?php echo set_value('EmailAddress'); ?>">
                                <?php echo form_error('EmailAddress', '<p class="error">'); ?>
								<?php
                                if ($this->session->flashdata('forgotmessage')) {?>
                                <p class="error"> <?php   echo $this->session->flashdata('forgotmessage'); ?></p>
                               <?php  }
                                ?>                               						

                            </div>

                             <div class="text-center"> <a href="<?php echo site_url('auth') ?>" class="">Click here to login</a> </div>
                            <div class="text-center btn-step-wrap">
                               
                                <input class="btn btn-rounded btn-inline btn-theme black-hover" type="submit" value="RESET" />
                            </div>

                            </form>		
                            <?php echo form_close(); ?>			
                        </div>
                        <div class="copyright text-center">&copy; <?php echo date('Y'); ?></div>
                    </div>
                </div>
            </div>
        </section>
		<script src="../../assets/js/jquery-3.2.1.min.js"></script>	
	<script>
$(document).ready(function(){  
    $('.type').change(function(){
		var value = $('input[name=type]:checked').val();		
		
        /*if($('#customer2').prop('checked')){
			 $(".error").fadeOut();
          
        }
		if($('#customer').prop('checked')){
			 $(".error").fadeIn();
          
        }*/
		
		if(value=='supplier' && $('#customer2').prop('checked')==true){			
			$(".error").fadeOut();          			
		
		}
		if(value=='customer' && $('#customer2').prop('checked')==false){	          		
		 $(".error").fadeIn();
		}
		
		
		
    });
	
	
});
   
</script>
    </body>
</html>