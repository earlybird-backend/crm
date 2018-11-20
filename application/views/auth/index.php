<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->data['title']; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url() ;?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url() ;?>assets/css/jquery.bxslider.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url() ;?>assets/css/style_front.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url() ;?>assets/css/style1_front.css">

        <link rel="stylesheet" href="<?php echo lang_url() ;?>assets/css/animate.css">

        <link rel="stylesheet" type="text/css" href="<?php echo lang_url() ;?>assets/css/sdstyle.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url() ;?>assets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url() ;?>assets/css/responsive.css">

    </head>
    <body class="bg-white">
        
        <header>
            <div class="container">
                <a href="<?php echo base_url(); ?>" class="logo">
                    <img src="<?php echo lang_url(); ?>assets/images/logo_front.png">
                </a>
                <?php if($display){ ?>
                <div class="pull-right hm-wrap">
                    <div class="drop-flag">
                        <ul>
                            <li><img src="<?php echo lang_url(); ?>assets/icon/5.svg">
                                <ul class="drop-chlid">                            
                                    <li><a href="<?php echo lang_url('english','auth'); ?>"><img src="<?php echo lang_url(); ?>assets/icon/1.svg"></a></li>
                                </ul>
                            </li>
        
                        </ul>
                    </div>             
                </div>
                <?php } ?>
                <div class="pull-right">
                    <div class="menu-section main-menu">
                        <ul class="list-inline list-unstyled main-menu_list">
                            <li><a href="<?php echo base_url(); ?>#EPFO">关于我们</a></li>
                            <li><a href="<?php echo base_url(); ?>#earlypay">为何选我们</a></li>
                            <li><a href="<?php echo base_url(); ?>#benefit">平台益处</a></li>
                            <li><a href="<?php echo base_url(); ?>#faq">常见问题</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
            
        <section class="bg-white">
            <div class="bag-sec-wrap">
                <div class="bag-sec">

                </div>

                <div class="bag-wrap">
                    <div class="wrap-sec">
                        <div class="img-sec">
                            <a href="<?php echo base_url() ?>" class="logo">
                                <img href="<?php lang_url() ;?>assets/images/logo_front.png">
                            </a>
                            <p class="error" style="text-align:center;"><?php
                                if ($this->session->flashdata('message')) {
                                    echo $this->session->flashdata('message');
                                }
                                ?>
                            </p>
                        </div>
                        <div class="row step no-min-h login-sec">
                            <?php
                                echo form_open_multipart('auth',
                                    array(
                                        'name' => 'login',
                                        'id' => 'signin-form_id',
                                        'class' => 'panel register'
                                    )
                                );
                            ?>
                            <div class="login-user-type text-center margin_40">
                                <div class="login-wrap">
                                    <input type="radio" class="type" name="type" id="customer" value="customer"  checked="true" />
                                    <label for="customer">
                                        <span class="img-wrap-uset">
                                            <img src="<?php echo base_url() ?>assets/images/icon1.png">
                                        </span>
                                        <span><?php echo $this->lang->line('auth_index_login_type_b') ?></span>
                                    </label>
                                </div>

                                <div class="login-wrap">
                                    <input type="radio" class="type" name="type" id="admin" value="admin" />
                                    <label for="admin">
                                                    <span class="img-wrap-uset">
                                                            <img src="<?php echo base_url() ?>assets/images/icon2.png">
                                                    </span>
                                        <span><?php echo $this->lang->line('auth_index_login_type_a') ?></span>
                                    </label>
                                </div>

                            </div>


                            <div class="form-group">
                                <input type="text" name="EmailAddress" id="EmailAddress" tabindex="1" class="form-control" placeholder="注册邮箱" value="<?php echo set_value('EmailAddress'); ?>" />
                                <?php echo form_error('EmailAddress', '<p class="error">'); ?>

                            </div>


                            <div class="form-group relative pass-login">       
                                <input type="Password" name="Password" id="Password" tabindex="1" class="form-control" placeholder="登录密码" value="<?php echo set_value('Password'); ?>" />
                                <?php echo form_error('Password', '<p class="error">'); ?>
                            </div>

                            <div class="text-center btn-step-wrap">
                                <input class="btn btn-rounded btn-inline btn-theme black-hover" type="submit" value="登录" />                                                                     
                            </div>

                            </form>		
                        </div>
                    </div>
                    
                </div>

            </div>

        </div>
    </section>
    
    <?php 
    
        include (VIEWPATH.'layout/footer.php') ;
    ?>
    
    
    <script src="<?php echo lang_url() ?>assets/js/jquery-3.2.1.min.js"></script>	
	<script>
		$(document).ready(function(){  


			 $('footer').addClass('bg_white');
			
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

