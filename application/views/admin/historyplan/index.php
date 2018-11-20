<div id="content">
    <div class="box-element">
        <div class="box-head">
            <h3><?php echo $title; ?></h3>
                        <!--b><?php //echo anchor('admin_bak/plans/add', 'Add Plan');            ?></b-->
        </div>
        <div class="productbox">
            <?php echo form_open('admin_bak/plans/index', array('name' => 'search', 'id' => 'search')); ?>
            <table class="table" width="100%" border="0" cellspacing="2" cellpadding="0">
                <tr>
                    <td align="left" class="currency-intro">
                        Search key :
                        <input type="text" name="key" size="32" value="<?php echo $this->input->post("key"); ?>" class="custom-input" />
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input type="submit" name="submit" value="Search" id="search" class="save btn custom-btn" />
                        <input type="button" class="btn custom-btn" name="showall" value="Show All" id="search" onclick="window.location = '<?php site_url('admin_bak/plans') ?>'" />
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
            <?php if (is_array($result) && sizeof($result) > 0) { ?>
                <div class="pagination" style="float:right;">
                    <?php echo $paginglinks; ?></div>
                <table class="table" width="100%" cellspacing="0" cellpadding="4" border="0" class="data">
                    <tbody>

                        <tr>
                            <td valign="middle" style="background: #fff;">PlanID</td>
                            <td valign="middle" style="background: #fff;" >CreateTime</td> 		
                            <td valign="middle" style="background: #fff;" >PlanAmount</td>
                            <td valign="middle" style="background: #fff;" >ClearAmount</td>
                            <td valign="middle" style="background: #fff;" >ClearInvoice</td>
                            <td valign="middle" style="background: #fff;" >ExpectAPR</td>
                            <td valign="middle" style="background: #fff;" >ClearAPR</td>
                            <td valign="middle" style="background: #fff;" >PlanDetail</td>
                            <td valign="middle" style="background: #fff;" >ClearDetail</td>
                            <td valign="middle" style="background: #fff;" >Payrecord</td>		 
                        </tr>

                        <?php
                        $n = 0;
                        $counter = 0;
                        $counter = $counter + $per_page;

                        foreach ($result as $key => $value) {

                            $counter++;


                            //if($n%2 == 0) $class = "even"; else $class = "";
                            ?>

                            <tr class="<?php //echo $class;  ?>" style="background-color:#fff">
                                <td><?php echo 'EPID' .$value['PlanId']; ?></td>
                                <td><?php echo $value['AddedDate']; ?></td>
                                <td><?php
                                            //echo $v['CurrencyType'];
                                            $CurrencyType = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" . $value['CurrencyType'] . "'";

                                            $CurrencyTypequery = $this->db->query($CurrencyType);
                                            $CurrencyName = $CurrencyTypequery->result_array();
                                            echo $CurrencyName[0]['CurrencySign'] . '' . $value['EarlyPayAmount'];
                                            ?></td>			
                                <td><?php
                                    $plancustmidsql = "SELECT sum(InvAmount) as amount, count(*) as count "
                                            . " FROM `winners` where PlanId=" . $value['PlanId'];
                                    $plancustmidstatus = $this->db->query($plancustmidsql);
                                    $plancustmiddata = $plancustmidstatus->result_array();
									
                                            //echo $v['CurrencyType'];
                                            $CurrencyType = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" . $value['CurrencyType'] . "'";

                                            $CurrencyTypequery = $this->db->query($CurrencyType);
                                            $CurrencyName = $CurrencyTypequery->result_array();
											if($plancustmiddata[0]['amount']){
												echo $CurrencyName[0]['CurrencySign'] . '' .  round($plancustmiddata[0]['amount'],2);
											}else{
												echo '-';
											}
                                            
                                            
                                    //print_r($plancustmiddata);                                   
                                    ?>
                                </td>
                                <td><?php echo $plancustmiddata[0]['count']; ?></td>
                                <td><?php echo $value['ExpectAPRPercent'].'%'; ?></td>
                                <td>
                                    <?php
                                    $proposalsql = "SELECT * FROM `proposal_report_by_admin` where PlanId=" . $value['PlanId'];
                                    $proposalstatus = $this->db->query($proposalsql);
                                    $proposal = $proposalstatus->result_array();
                                    //print_r($plancustmiddata);
									if($proposal[0]['CalculateAPR']){
										echo $proposal[0]['CalculateAPR'].'%';
									}else{
												echo '-';
											}
                                    
                                    ?>
                                </td>
                                <td><a href="<?php echo site_url('admin_bak/historyplan/plandetail/' . $value['PlanId']); ?>">Check</a></td>
                                <td><a href="<?php echo site_url('admin_bak/historyplan/cleardetail/' . $value['PlanId']); ?>">Check</a></td>
                                <td><a href="<?php echo site_url('admin_bak/historyplan/payrecorddetail/' . $value['PlanId']); ?>">Check</a></td>

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