<?php
if (is_array($data) && sizeof($data) > 0) {
    extract($data[0]);
    //echo '<pre>';print_r($data[0]);
	
}
?>
<div id="content-wrapper">    
   
	
    <div class="panel-heading custom-btn btn-add "><span><a href="<?php echo site_url('admin_bak/plans/') ?> ">Back</a></span></div>

    <div class="row mr-top-20">
        <div class="col-sm-12">   
            

            <div class="panel-body">               

                <div class="row form-group">
                    <label class="col-sm-4 control-label">PlanID</label>
                    <div class="col-sm-8">
                        <?php echo 'EPID' .$PlanId; ?>                
                       </div>
                </div>         

                 <div class="row form-group">
                    <label class="col-sm-4 control-label">CreateTime</label>
                    <div class="col-sm-8">
                        <?php echo $AddedDate; ?>            
                       </div>
                </div>

				<div class="row form-group">
                    <label class="col-sm-4 control-label">Customer</label>
                    <div class="col-sm-8">
                        <?php //echo $CustomerId; 
						
						$usertable = 'site_users';
						$userwhere = array('UserId' =>  $CustomerId);
						$userdata = $this->UniversalModel->getRecords($usertable, $userwhere);
						echo $userdata[0]['CompanyName'];
						
						?>            
                       </div>
                </div>
				
				<div class="row form-group">
                    <label class="col-sm-4 control-label">PlanStatus</label>
                    <div class="col-sm-8">
                        <?php echo $PlanStatus; ?>   
                       </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-sm-4 control-label">PlanAmount</label>
                    <div class="col-sm-8">
					<?php
                                            //echo $v['CurrencyType'];
                                            $CurrencyType = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" . $CurrencyType . "'";

                                            $CurrencyTypequery = $this->db->query($CurrencyType);
                                            $CurrencyName = $CurrencyTypequery->result_array();
                                            echo $CurrencyName[0]['CurrencySign'] . '' .  $EarlyPayAmount;
                                            ?>                       
                       </div>
                </div>
				
				<div class="row form-group">
                    <label class="col-sm-4 control-label">EstimateAPR</label>
                    <div class="col-sm-8">
                        <?php echo $ExpectAPRPercent.'%'; ?>
                       </div>
                </div>
               
			   <div class="row form-group">
                    <label class="col-sm-4 control-label">EstimatePayDate</label>
                    <div class="col-sm-8">
                        <?php echo $EstimatePayDate; ?>
                       </div>
                </div>
                
            </div>
        </div>
    </div>	

