<div id="content">
          <div class="box-element">
            <div class="box-head">
              <h3><?php echo $title;?></h3>
			  <!--b><?php //echo anchor('admin_bak/plans/add', 'Add Plan');?></b-->
            </div>
            <div class="productbox">
<?php echo form_open('admin_bak/plans/index',array('name' => 'search', 'id' => 'search'));?>
<table class="table" width="100%" border="0" cellspacing="2" cellpadding="0">
<tr>
<td align="left" class="currency-intro">
Search key :
<input type="text" name="key" size="32" value="<?php echo $this->input->post("key");?>"  class="custom-input"/>
<i class="fa fa-search" aria-hidden="true"></i>
<input type="submit" name="submit" value="Search" id="search" class="save btn custom-btn" />
<input type="button" name="showall" value="Show All" id="search" class="btn custom-btn" onclick="window.location='<?php site_url('admin_bak/plans')?>'"/>
</td>
</tr>
</table>
<?php echo form_close();?>
<div class="pagination" style="float:left; color:#FF0000;">
<?php 
echo validation_errors();
if($this->session->flashdata('activatemessage'))
{?>
<div class="alert alert-success alert-dark"><?php   echo $this->session->flashdata('activatemessage');?>
				  
                        <button type="button" class="close" data-dismiss="alert">×</button>
                    </div>
<?php  
}
?>
</div>
<?php if(is_array($result) && sizeof($result)>0){ ?>
<div class="pagination" style="float:right;">
<?php echo $paginglinks; ?></div>
<p class="finishmsg alert-success"></p>
<p class="cancelmsg alert-success"></p>
<div class="panel">
<table class="table table-bordered" width="100%" cellspacing="0" cellpadding="4" border="0" class="data" >
<tbody >

	<tr>
		<td valign="middle">PlanID</td>
		<td valign="middle" >CreateTime</td>			
        <td valign="middle" >Customer</td>
		<td valign="middle" >PlanStatus</td>
		<td valign="middle" >PlanAmount</td>
		<td valign="middle" >PlanDetail</td>
		<td valign="middle" >Payable</td>
		<td valign="middle" >Quote</td>
		<td valign="middle" >Proposal</td>
		<td valign="middle" >Proposal Calculate</td>
		<td valign="middle" >Finish Plan</td>
		<td valign="middle" >Cancel Plan</td>
	</tr>
	
	<?php
	
	
	
	$n = 0;
	$counter = 0;
	$counter =  $counter + $per_page;
	
	foreach($result as $key=>$value) {
		
	  $counter++;
                            
                            
	   //if($n%2 == 0) $class = "even"; else $class = "";
	?>
		
		<tr class="<?php //echo $class;?> table-bordered">
			<td><?php echo 'EPID' . $value['PlanId'];?></td>
			<td><?php echo $value['AddedDate'];?></td>
			<td><?php 
			$usertable = 'site_users';
            $userwhere = array('UserId' =>  $value['CustomerId']);
            $userdata = $this->UniversalModel->getRecords($usertable, $userwhere);
			echo $userdata[0]['CompanyName'];
			
			
			?></td>
			<td><?php echo $value['PlanStatus'];?></td>
			<td>
			<?php
                                            //echo $v['CurrencyType'];
                                            $CurrencyType = "SELECT * FROM `apr_currency_by_admin` "
                                                    . "WHERE `CurrencyId` ='" . $value['CurrencyType'] . "'";

                                            $CurrencyTypequery = $this->db->query($CurrencyType);
                                            $CurrencyName = $CurrencyTypequery->result_array();
                                            echo $CurrencyName[0]['CurrencySign'] . '' . $value['EarlyPayAmount'];
                                            ?>										
			
			</td>
			<td><a href="<?php echo site_url('admin_bak/plans/checkdetail/'.$value['PlanId']); ?>">Checkdetail</a></td>
			<td><a href="<?php echo site_url('admin_bak/plans/checklist/'.$value['PlanId']); ?>">Checklist</a></td>
			<td><a href="<?php echo site_url('admin_bak/plans/checksupplierquote/'.$value['PlanId']); ?>">Checksupplierquote</a></td>
			<td><a href="<?php echo site_url('admin_bak/plans/checkproposal/'.$value['PlanId']); ?>">Checkproposal</a></td>
			<td><a href="<?php echo site_url('admin_bak/plans/proposalcalculate/'.$value['PlanId']); ?>">Proposal Calculate</a></td>
			<td><a href="javascript:void(0);" onclick="planfailbyadmin(<?php echo $value['PlanId']; ?>);">End</a></td>
			<td><a href="javascript:void(0);" onclick="plancancelbyadmin(<?php echo $value['PlanId']; ?>);">Cancel</a></td>
			
            <!--td><a href="<?php //echo site_url('admin_bak/currency/edit/'.$value['CurrencyId'])?>">Edit |</a>
            <a href="<?php //echo site_url('admin_bak/currency/delete/'.$value['CurrencyId'])?>" onclick="return deleteRecordConfirm();">Delete</a></td-->
			
		</tr>
			
		
		<?php 
			
	

}
	?>
	
	
</tbody>
</table>
</div>
<div class="pagination" style="float:right;">
<?php echo $paginglinks; ?></div>

<?php }else{?>
<p align="center">No Record Found!</p>
<?php }?>

			
			<p>&nbsp;</p>
			
			</div>
            <div class="clear"></div>			
          </div>
          <div class="clear"></div>
        </div>