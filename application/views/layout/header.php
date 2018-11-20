
<header class="sticky">
    <div class="container">
        <a href="<?php echo base_url(); ?>" class="logo">
            <img src="<?php echo lang_url(); ?>assets/images/logo_front.png">
        </a>


        <div class="pull-right hm-wrap">
            <a href="<?php echo site_url('auth'); ?>" class="btn btn-login">登录</a>

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

<div class="modal fade" id="inqmodal" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-head text-center">Inquiry for Register</div>		  
                <div id="CompanyName" class="color-red"></div>		

                <?php //echo form_open_multipart('enquiry\index',array('name' => 'enquiry', 'class'=>'text-center  mt-20 register', 'id' => 'register-form','role'=>'form')); ?>
                <form class="text-center  mt-20">
                    <div class="popup-input-sec">
                        <div class="form-group add-new">
                            <textarea rows="3" name="RequestComment" placeholder="Request Comment" class="form-control"><?php echo set_value('RequestComment'); ?></textarea>									          
                            <div class="margin_10"></div>
                        </div>

                        <div class="form-group add-new">

                            <input type="text" name="CompanyName" placeholder="Company Name" value="<?php echo set_value('CompanyName'); ?>" class="form-control" required />
                            <div id="CompanyName"></div>
                            <div class="margin_10"></div>
                        </div>


                        <div class="form-group error-group  add-new">

                            <input type="text" name="ContactPerson" value="<?php echo set_value('ContactPerson'); ?>" placeholder="ContactPerson" class="form-control" required />
                            <div id="ContactPerson"></div>
                            <div class="margin_10"></div>
                        </div>


                        <div class="form-group add-new">

                            <input type="text" name="ContactPhone" value="<?php echo set_value('ContactPhone'); ?>" placeholder="ContactPhone" class="form-control" required />
                            <div id="ContactPhone"></div>
                            <div class="margin_10"></div>
                        </div>

                        <div class="form-group add-new">

                            <input type="text" name="ContactEmail" value="<?php echo set_value('ContactEmail'); ?>"  placeholder="Contact Email" class="form-control" required />
                            <div id="ContactEmail"></div>
                            <div class="margin_10"></div>
                        </div>
                        <input class="btn btn-rounded btn-inline btn-theme show-errors" id="enquiryform" type="button" value="Submit" />
                    </div>

                </form>
                <?php // echo form_close(); ?>
            </div>
        </div>

    </div>
</div>