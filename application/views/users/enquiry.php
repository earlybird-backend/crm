<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->data['title']; ?></title>

        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/jquery.bxslider.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/style_front.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/style1_front.css">

        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/animate.css">

        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/sdstyle.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/responsive.css">

    </head>
    
    <body class="bg-offwhite">
        <header>
            <div class="container">
                <a href="<?php echo base_url(); ?>" class="logo">
                    <img src="<?php echo lang_url(); ?>assets/images/logo_front.png">
                </a>
        
                <div class="pull-right hm-wrap">      
                     <a href="<?php echo base_url('auth'); ?>" class="btn btn-login">登录</a>     
                     
                     <div class="drop-flag">
                        <ul>
                            <li><img src="<?php echo lang_url(); ?>assets/icon/5.svg">
                                <ul class="drop-chlid">                            
                                    <li><a href="<?php echo lang_url('english','enquiry'); ?>"><img src="<?php echo lang_url(); ?>assets/icon/1.svg"></a></li>
                                </ul>
        
                            </li>
        
                        </ul>
                    </div>             
                </div>
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
    
    
        <section class="bg-offwhite bg-images-wrap">
            <div class="overlay-section"></div>
            <div class="container">
                <div class="row">

                    <div class="col-sm-6 text-center padd_top_65">
                        <a href="<?php echo site_url() ?>">
                            <img src="assets/images/chreg.png">
                        </a>
                     
                    </div>
                    <div class="col-md-5 col-md-offset-1">
                        <div class="wrap-sec">
                            <div class="row step">
                                <?php
                                if ($this->session->flashdata('inquirymessage')) {
                                    ?><p class="success"><i class="fa fa-check" aria-hidden="true"></i>
                                        <?php echo $this->session->flashdata('inquirymessage'); ?></p><?php
                                }
                                ?>
                                <h2>咨询成为注册用户</h2>
                                <p>请完成所有字段.</p>
                                <br />
                                <form class="register" id="register-form" action="" method="post" role="form">                        
                                    <div class="form-group">                                   
                                        <input type="text" name="RequestComment"
                                               value="<?php echo set_value('RequestComment'); ?>" 
                                               placeholder="咨询留言" class="form-control form-section" name="">

                                    </div>
                                    <div class="form-group <?php if(form_error('CompanyName', '')){echo 'error'; } ?>">    
                                        <input type="text" name="CompanyName" placeholder="公司名称" value="<?php echo set_value('CompanyName'); ?>" 
                                               class="form-control" />
                                               <?php echo form_error('CompanyName', '<p class="error">'); ?>  
                                    </div>
                                    <div class="form-group <?php if(form_error('ContactPerson', '')){echo 'error'; } ?>">  
                                        <input type="text" name="ContactPerson" 
                                               value="<?php echo set_value('ContactPerson'); ?>" placeholder="联系人" class="form-control" />
                                               <?php echo form_error('ContactPerson', '<p class="error">'); ?> 
                                    </div> 
                                    <div class="form-group <?php if(form_error('ContactPhone', '')){echo 'error'; } ?> "> 
                                        <!--<input type="text" name="ContactPhone"  minlength="10" maxlength="10" 
                                               min="0" value="<?php //echo set_value('ContactPhone'); ?>" placeholder="ContactPhone" class="form-control" />-->
											   <input type="text" name="ContactPhone"  value="<?php echo set_value('ContactPhone'); ?>" placeholder="联系电话" class="form-control" />
                                               <?php echo form_error('ContactPhone', '<p class="error">'); ?>

                                    </div>
                                    <div class="form-group <?php if(form_error('ContactEmail', '')){echo 'error'; } ?>">           
                                        <input type="text" name="ContactEmail" 
                                               value="<?php echo set_value('ContactEmail'); ?>"  placeholder="联系邮箱" class="form-control" />
                                               <?php echo form_error('ContactEmail', '<p class="error">'); ?>
                                    </div>
                                    <div class="text-left btn-step-wrap btn-wrap-wram">
                                        <input class="btn btn-rounded btn-inline btn-white"  type="submit" value="提交" />
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




        <script src="<?php echo lang_url(); ?>assets/js/jquery-3.2.1.min.js"></script>
        <script src="<?php echo lang_url(); ?>assets/js/bootstrap.min.js"></script>        
        <script src="<?php echo lang_url(); ?>assets/js/jquery.bxslider.min.js"></script>
       

        <script>
            $(document).ready(function () {
                $('.collapse').collapse();

                $('footer').addClass('bg_white');

                
            });
        </script>

        <script>
            $(document).ready(function () {
                $('.bxslider').bxSlider();
            });
        </script>


        <script>
            $(document).ready(function () {

                $("#toggle1").click(function () {
                    $(".animation-wrapper").toggleClass("toggled");
                    $(".bullets-top i").addClass('fadeInUp animated');
                    $(".animation2").addClass('fadeInUpBig animated');
                    $(".animation2").addClass('fadeInUpBig animated');
                    $(".chat-box-wrap").removeClass('wow bounceInUp');
                    $(".chat-box-wrap").removeAttr("data-wow-delay");
                    $(".chat-box-wrap").removeAttr("style");
                    $(".chat-box-wrap").addClass('fadeInUp animated');
                });

            });
        </script>

        <script>
            $(document).ready(function () {
                $(".show-errors").click(function () {
                    $(".error-group").addClass('error');
                });
            });
        </script>

    </body>
</html>