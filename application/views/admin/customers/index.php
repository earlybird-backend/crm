<div id="content">
    <div class="box-element">
        <div class="box-head">
            <h3><?php echo $title; ?></h3>			 
        </div>
        <div class="productbox">
            <?php echo form_open('admin_bak/customers/index', array('name' => 'search', 'id' => 'search')); ?>
            <table class="table" width="100%" border="0" cellspacing="2" cellpadding="0">
                <tr>
                    <td align="left" class="currency-intro">
                        Search key :
                        <input type="text" name="key" size="32"  class="custom-input" value="<?php echo $this->input->post("key"); ?>" /><i class="fa fa-search" aria-hidden="true"></i>
                        <input type="submit" name="submit" value="Search" id="search" class="save btn custom-btn" />
                        <input type="button" name="showall" value="Show All" id="search" class="btn custom-btn" onclick="window.location = '<?php site_url('admin_bak/customers') ?>'" />
                    </td>
                </tr>
            </table>
            <?php echo form_close(); ?>
            <div class="pagination" style="float:left; color:#FF0000;">             
            </div>
            <?php if (is_array($result) && sizeof($result) > 0) { ?>
                <div class="pagination" style="float:right;">
                    <?php echo $paginglinks; ?></div>
                <div class="table-light custom-table ">
                    <div class="table-header">
                        <div class="table-caption">
                            <?php echo $title; ?>
                           
                        </div>
                    </div>
					   <?php
                if ($this->session->flashdata('activatemessage')) {?>
                 <div class="alert alert-success alert-dark "><?php   echo $this->session->flashdata('activatemessage');?>
				  
                        <button type="button" class="close" data-dismiss="alert">×</button>
                    </div>
                <?php }
                ?>
                    <?php if($this->session->flashdata('changecompanyname')){?>
                    <div class="alert alert-success alert-dark"> <?php echo $this->session->flashdata('changecompanyname');                     
                    ?>
                        <button type="button" class="close" data-dismiss="alert">×</button>
                    </div>
                    <?php } ?>
                    <table class="table table-bordered">
                        <thead class="table-td-custom">
                            <tr>
                                <th>CompanyID</th>
                                <th>CompanyName</th>
                                <th>New Company Name</th>
                                <th>Change Company Name Request</th>
                                <th>CompanyProfile</th>
                                <th>Contact</th>
                                <th>Reset Password</th>
                                <th>Suppliers</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $n = 0;
                            $counter = 0;
                            $counter = $counter + $per_page;

                            foreach ($result as $key => $value) {

                                $counter++;
                                ?>
                                <tr class="customer-wrap">
                                    <td><?php echo $value['UserId']; ?></td>
                                    <td><?php echo $value['CompanyName']; ?></td>
                                    <td><?php echo $value['NewCompanyName']; ?></td>
                                    <td><a href="<?php echo site_url('admin_bak/customers/changecompanyname/' . $value['UserId']) ?>">Change Name</a></td>
                                    <td><a href="<?php echo site_url('admin_bak/customers/companyprofile/' . $value['UserId']) ?>">View</a></td>
                                     
                                    <td><a href="<?php echo site_url('admin_bak/customers/contact/' . $value['UserId']) ?>">View</a></td>
                                    <td><a href="<?php echo site_url('admin_bak/customers/resetpassword/'.$value['UserId'])?>">Send Password</a></td>
                                    <td><a href="<?php echo site_url('admin_bak/customers/suppliers/' . $value['UserId']) ?>">View</a></td>
                                </tr>	

                                <?php
                            }
                            ?>							

                        </tbody>
                    </table>
<!--                    <div class="table-footer">
                        Footer
                    </div>-->
                </div>

                <div class="pagination" style="float:right;">
                    <?php echo $paginglinks; ?></div>

            <?php } else { ?>
                <p align="center">No Record Found!</p>
            <?php } ?>


            <p>&nbsp;</p>

        </div>
        <div class="clear"></div>			
    </div>
    <div class="clear"></div>
</div>

