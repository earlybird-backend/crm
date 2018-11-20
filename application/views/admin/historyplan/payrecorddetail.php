<div id="content">
    <div class="box-element">
        <div class="box-head">
            <h3><?php echo $title; ?></h3>
                        <!--b><?php //echo anchor('admin_bak/plans/add', 'Add Plan');                          ?></b-->
        </div>
        <div class="productbox">
            <?php echo form_open('admin_bak/plans/index', array('name' => 'search', 'id' => 'search')); ?>
            <table class="table" width="100%" border="0" cellspacing="2" cellpadding="0" style="display: none">
                <tr>
                    <td align="left">
                        Search key :
                        <input type="text" name="key" size="32" value="<?php echo $this->input->post("key"); ?>" />
                        <input type="submit" name="submit" value="Search" id="search" class="save" />
                        <input type="button" name="showall" value="Show All" id="search" onclick="window.location = '<?php site_url('admin_bak/plans') ?>'" />
                    </td>
                </tr>
            </table>
            <?php echo form_close(); ?>
            <div class="pagination" style="float:left; color:#FF0000;">
                <?php
                if ($this->session->flashdata('activatemessage')) {
                    echo $this->session->flashdata('activatemessage');
                }
                ?>
            </div>
            <?php if (is_array($data) && sizeof($data) > 0) { ?>
                <div class="pagination" style="float:right;">
                    <?php echo $paginglinks; ?></div>
                <table class="table oa-lp" width="100%" cellspacing="0" cellpadding="4" border="0" class="data">
                    <tbody>

                        <tr>
                            <td valign="middle" style="background: #fff">Paydate</td>
                            <td valign="middle" style="background: #fff">SupplierID</td> 		
                            <td valign="middle" style="background: #fff">PayAmount</td>
                            <td valign="middle" style="background: #fff">Uploadtime</td>
                            <td valign="middle" style="background: #fff">PaymentCopy</td>				 
                        </tr>

                        <?php
                        $n = 0;
                        $counter = 0;
                        $counter = $counter + $per_page;

                        foreach ($data as $key => $value) {

                            $counter++;
                            ?>

                            <tr class="<?php //echo $class;                         ?>" style="background: #fff;">

                                <td><?php echo $date = date("d-m-Y", strtotime($value['AddedDate'])); ?></td>
                                <td><?php echo $value['PlanId']; ?></td>
                                <td><?php			
                                    $plansql = "SELECT * FROM `customer_early_pay_plans` "
                                                    . "WHERE `PlanId` ='" . $value['PlanId'] . "'";

                                            $planquery = $this->db->query($plansql);
                                            $plandata = $planquery->result_array();						
									 $Currencynames = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" .$plandata[0]['CurrencyType']. "'";

                                            $CurrencyTypequery = $this->db->query($Currencynames);
                                            $CurrencyName = $CurrencyTypequery->result_array();                                           
									  echo $CurrencyName[0]['CurrencySign'] . '' . $value['InvAmount']; ?></td>			
                                <td><?php echo $date = date("H:i", strtotime($value['AddedDate'])); ?></td>
                                <td>  <a href="<?php echo base_url(); ?>uploads/<?php echo $value['PaymentCopy']; ?>" download><i class="fa fa-download mr-right-custom"></i><span>Download Format</span></a></td>           	   

                            </tr>


                            <?php
                        }
                        ?>


                    </tbody>
                </table>
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