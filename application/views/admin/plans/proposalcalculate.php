<?php
if (is_array($data) && sizeof($data) > 0) {
    extract($data[0]);
    //echo '<pre>';print_r($data[0]);
	
}
?>
<div id="content-wrapper">
   
	
    <div class="panel-heading custom-btn btn-add back-block"><span><a href="<?php echo site_url('admin_bak/plans/') ?>">Back</a></span>
<?php 
if($this->session->flashdata('invoicemessage'))
{
?>
	<span class="label label-success"><?php echo $this->session->flashdata('invoicemessage'); ?></span>

<?php	} ?>

<?php
if($this->session->flashdata('reportmessage'))
{
?>	
	<span class="label label-success"><?php echo $this->session->flashdata('reportmessage'); ?></span>

<?php
}
?>
	
	</div>

    <div class="row mr-top-20">
        <div class="col-sm-12">
            
		
		<?php echo form_open('admin_bak/plans/proposalcalculate/'.$planid, array('name' => 'plandetail', 'class'=>'panel')); ?>
		
            <div class="panel-body">     
				<div class="row form-group">
                    <label class="col-sm-4 control-label">PlanAmount</label>
                    <div class="col-sm-8">	
<?php			
                                    $plansql = "SELECT * FROM `customer_early_pay_plans` "
                                                    . "WHERE `PlanId` ='" . $planid . "'";

                                            $planquery = $this->db->query($plansql);
                                            $plandata = $planquery->result_array();						
									 $Currencynames = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" .$plandata[0]['CurrencyType']. "'";

                                            $CurrencyTypequery = $this->db->query($Currencynames);
                                            $CurrencyName = $CurrencyTypequery->result_array();                                           
									 ?><?php echo $CurrencyName[0]['CurrencySign'] . '' . $EarlyPayAmount; ?>			
                      
                       </div>
                </div>
				
				<div class="row form-group">
                    <label class="col-sm-4 control-label">ExpectAPRRate</label>
                    <div class="col-sm-8">
                        <?php echo $ExpectAPRRate; ?>
                       </div>
                </div>
				
				<div class="row form-group">
                    <label class="col-sm-4 control-label">EstimateAPR</label>
                    <div class="col-sm-8">
                        <?php echo $ExpectAPRPercent.'%'; ?>
                       </div>
                </div>
				
				<div class="row form-group">
                    <label class="col-sm-4 control-label">PayAmount</label>
                    <div class="col-sm-8">
					<?php			
                                    $plansql = "SELECT * FROM `customer_early_pay_plans` "
                                                    . "WHERE `PlanId` ='" . $planid . "'";

                                            $planquery = $this->db->query($plansql);
                                            $plandata = $planquery->result_array();						
									 $Currencynames = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" .$plandata[0]['CurrencyType']. "'";

                                            $CurrencyTypequery = $this->db->query($Currencynames);
                                            $CurrencyName = $CurrencyTypequery->result_array();                                           
									 ?>
                       <?php if($InvAmount) { echo $CurrencyName[0]['CurrencySign'] . '' .  $InvAmount; } else { echo 'No payable Amount'; } ?>
                     </div>
                </div>
				
				<div class="row form-group">
                    <label class="col-sm-4 control-label">CalculateAPR*</label>
                    <div class="col-sm-8">
                       <input type="text" name="CalculateAPR" value="<?php if($CalculateAPR){echo $CalculateAPR;}else{echo set_value('CalculateAPR');} ?>" />
					   <?php echo form_error('CalculateAPR','<p class="error">'); ?>
                     </div>
                </div>
				
				<div class="row form-group">
                    <label class="col-sm-4 control-label">Earns*</label>
                    <div class="col-sm-8">
                       <input type="text" name="Earns" value="<?php if($Earns){echo $Earns;}else{echo set_value('Earns');} ?>" />
					   <?php echo form_error('Earns','<p class="error">'); ?>
                     </div>
                </div>
				
				<div class="panel-footer">
						 <?php $uploadedwinnersql = "SELECT * FROM `customer_early_pay_plans` WHERE `PlanId` ='" . $planid . "' ";

			                       $uploadedwinnerquery = $this->db->query($uploadedwinnersql);
			                      $uploadedwinnerdata = $uploadedwinnerquery->result_array(); 
								  $uploadedstatus= $uploadedwinnerdata[0]['PlanStatus'];
								  if($uploadedstatus == "pending_payment"){
									  ?>
								  <a href="javascript:void(0);" >Upload Winners</a>&nbsp;|&nbsp;
								  <?php } else { ?>
						<a href="<?php echo site_url('admin_bak/plans/addproposal/'.$PlanId); ?>" >Upload Winners</a>&nbsp;|&nbsp;
								  <?php } ?>
						<input type="submit" class="btn btn-primary" value="Submit to customer" />&nbsp;|&nbsp;
						<a href="javascript:void();" class="btn btn-primary planfail" onclick="planfailbyadmin(<?php echo $PlanId; ?>);" >Plan Fail</a>
				</div>
				
            </div>
<?php echo form_close(); ?>
			
        </div>
    </div>
<?php if($winnersdata)	{?>
	<table class="table table-bordered panel">
							<thead>
								<tr>
									<th>#</th>
									<th>VendorCode</th>
									<th>Invoice</th>
									<th>Amount</th>
									<th>AddedDate</th>
								</tr>
							</thead>
							<tbody>
							<?php $w=1; foreach($winnersdata as $m=>$d) {?>
								<tr>
									<td><?php echo $w; ?></td>
									<td><?php echo $d['Vendorcode']; ?></td>
									<td><?php echo $d['InvoiceId']; ?></td>
									<td><?php			
                                    $plansql = "SELECT * FROM `customer_early_pay_plans` "
                                                    . "WHERE `PlanId` ='" . $planid . "'";

                                            $planquery = $this->db->query($plansql);
                                            $plandata = $planquery->result_array();						
									 $Currencynames = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" .$plandata[0]['CurrencyType']. "'";

                                            $CurrencyTypequery = $this->db->query($Currencynames);
                                            $CurrencyName = $CurrencyTypequery->result_array();                                           
									 ?><?php echo $CurrencyName[0]['CurrencySign'] . '' .  $d['InvAmount']; ?></td>
									<td><?php echo $d['AddedDate']; ?></td>
								</tr>
							<?php $w++; } ?>	
							</tbody>
						</table>
<?php }else{ ?>
No record found
<?php } ?>
	
	
</div>	