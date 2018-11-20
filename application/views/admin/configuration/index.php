<div id="content">
          <div class="box-element">
            <div class="box-head">
              <h3><?php echo $title;?></h3>
			  <b class="custom-btn btn-add"><?php echo anchor('admin/configuration/edit', 'Edit');?><i class="fa fa-pencil" aria-hidden="true"></i></b>
            </div>
            <div class="productbox">
<table width="100%" border="0" cellspacing="4" cellpadding="0">
<tr>
<td width="157">&nbsp;</td>
<td width="806" style="color:#FF0000;">
<?php 
echo validation_errors();
if($this->session->flashdata('message'))
{?>
<div class="alert alert-success alert-dark"><?php   echo $this->session->flashdata('message');?>
				  
                        <button type="button" class="close" data-dismiss="alert">×</button>
                    </div>
<?php  
}
?>
</td>
</tr>
</table>
<div class="panel">
<div class="panel-body">
<?php extract($result[0]);?>
<table width="100%" class="table table-striped" cellspacing="0" cellpadding="4" border="0" class="data">
<tbody>
	<tr>
		<th valign="middle">Title</td>
		<th valign="middle">Value</td>		
	</tr>
	<tr class="even">
		<td>PlanStartTime</td>
		<td><?php echo $PlanStartTime;?></td>
	</tr>
		
	<tr>
		<td>PlanEndTime</td>
		<td><?php echo $PlanEndTime;?></td>
	</tr>	
	
	<tr class="even">
		<td>ProposalTime</td>
		<td><?php echo $ProposalTime;?></td>
	</tr>
		
	<tr>
		<td>ApproveProposalTime (Next Day)</td>
		<td><?php echo $ApproveProposalTime;?></td>
	</tr>
	
	
</tbody>
</table>
</div>
</div>
	</div>
	<div class="clear"></div>			
  </div>
  <div class="clear"></div>
</div>