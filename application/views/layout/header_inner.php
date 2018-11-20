<?php if($this->uri->segment(3)){$slash='../../../';} else{$slash='../../';} ?>


<header class="text-center"> 
    <a href="#" class="bars"><img src="<?php echo base_url(); ?>assets/images/menu.png"></a>
    <img src="<?php echo base_url(); ?>assets/images/logo.png" />
</header>

<div class="layer"></div>

<div class="sidebar_left">

    <div class="epfo_menu_head">
        <img src="<?php echo base_url(); ?>assets/images/logo.png" />                          
    </div>
<?php    	
    if ($this->session->userdata("logged") == TRUE && $this->session->userdata('UserId')) {
?>   
    <ul class="main-menu">
        <li>
            <div class="row">
                <div class="col-md-6" style="text-align:center;">
                    <a href="<?php echo lang_url('english',$uri); ?>"><img src="<?php echo lang_url(); ?>assets/icon/1.svg" width="20px" style="margin-right: 10px;"> En </a>
                </div>
                 
                <div class="col-md-6" style="text-align:center;color:#2699f2">
                    <?php if ($this->session->userdata('Role') == 'customer') { ?>
                        <a href="<?php echo lang_url('chinese','customer/markets'); ?>"><i class="fa fa-retweet"></i> 新 版</a>                           
                    <?php }?>
                </div>
            </div>
        </li>
                
        <?php if ($this->session->userdata('Role') == 'customer') { ?>
                                
                <li class="active">
                    <a href="<?php echo base_url('customer/plans'); ?>"><i class="fa fa-tachometer"></i>进行中计划</a>
                </li>
                <li>
                    <a href="<?php echo base_url('customer/historyplan'); ?>"> <i class="fa fa-history"></i> 成交历史 </a>
                </li>
                <li>
                    <a href="<?php echo base_url('customer/invitesuppliers'); ?>"> <i class="fa fa-paper-plane"></i> 邀请供应商 </a>
                </li>               
                <li>
                    <a href="<?php echo base_url('customer/supplierslist'); ?>"> <i class="fa fa-list"></i> 供应商列表 </a>
                </li>
                
                <li>
                    <a href="<?php echo base_url('customer/settings'); ?>"> <i class="fa fa-cog"></i>设置</a>
                </li>
                
        <?php 
            } elseif ($this->session->userdata('Role') == 'supplier') { 
        ?>

                <li>
                    <a href="<?php echo base_url('supplier/plans'); ?>"><i class="fa fa-cubes"></i> 买家市场 </a>
                </li>
                <li>
                    <a href="<?php echo base_url('supplier/historyplan'); ?>"><i class="fa fa-history"></i> 成交历史 </a>
                </li>
                <li>
                    <a href="<?php echo base_url('supplier/customers'); ?>"><i class="fa fa-users"></i> 买家列表 </a>
                </li>
                    <?php if( 1 === 0 ){?>
                <li>
                    <a href="<?php echo base_url('supplier/invitecustomer'); ?>"><i class="fa fa-paper-plane"></i>邀请买家</a>
                </li>
                    <?php }?>
                 <li>
                    <a href="<?php echo base_url('supplier/settings'); ?>"> <i class="fa fa-cog"></i>设置</a>
                </li>

        <?php
            }        
        ?>
    </ul>

    <a href="<?php echo base_url('auth/logout'); ?>" class="btn-logout">
        <div style="margin-bottom:5px;">                
            <?php echo $this->session->userdata('ContactName'); ?>
        </div>
        <div>
         <i class="fa fa-sign-out"></i> <?php echo $this->lang->line('button')['LOG OUT']; ?>
        </div>
    </a>   
<?php
    }        
?>                        
    
</div> 
    
    


