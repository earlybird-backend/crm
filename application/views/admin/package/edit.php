<script language="javascript">
jQuery(document).ready(function() {
		jQuery('#cancel').click(function(){		
			window.location= "<?php echo site_url('admin_bak/plans/')?>";
		});
  });
</script>
<?php
if(is_array($data) && sizeof($data) >0)
{
	extract($data[0]);
}
//echo '<pre>';print_r($data);
?>
<div id="content">
          <div class="box-element">
            <div class="box-head">
              <h3> Add <?php echo $title;?></h3>
            </div>
            <div class="productbox">
			<h4>Space Price Setting</h4>
			<hr/>
			<?php echo form_open('admin_bak/plans/edit/'.$PlanId,array('name' => 'editpage', 'id' => 'editpage')); ?>
<table width="100%" border="0" cellspacing="4" cellpadding="0">
<tr>
<td colspan="4" style="color:#FF0000;">
<?php
//echo validation_errors();
echo $this->session->flashdata('message');
?>		
</td>
		</tr>
	
	<tr>
		<td width="221">Plan Category</td>
		<td width="725">
		<select name="PlanCategory">
			<option value="">Select Plan Category</option>
			<option value="ch" <?php if($PlanCategory=='ch')echo 'selected';?>>Chinese</option>
			<option value="en" <?php if($PlanCategory=='en')echo 'selected';?>>English</option>
		</select>
		
 		
		<?php echo form_error('PlanCategory','<p class="error">'); ?>
		 </td>
	 </tr>
	
	
	<tr>
		<td width="221">Plan Name</td>
		<td width="725">
		<input type="text"  name="PlanName" id="PlanName" value="<?php echo $PlanName; ?>" />
 		For eg. 5G,10G
		<?php echo form_error('PlanName','<p class="error">'); ?>
		 </td>
	 </tr>
	 
	 <tr>
		<td width="221">PlanSpace</td>
		<td width="725">
		<input type="text"  name="PlanSpace" id="PlanSpace" value="<?php echo $PlanSpace; ?>" />
 		For eg. 5,10.
		<?php echo form_error('PlanSpace','<p class="error">'); ?>
		 </td>
	  </tr>
		
		
		
		
		
		
	
	
	<tr>
		<td width="221">Plan UnitCost(In ￥)</td>
		<td width="725">
		<input type="text"  name="PlanCost" id="PlanCost" value="<?php echo $PlanCost; ?>" />
 		For eg. 10.00
		<?php echo form_error('PlanCost','<p class="error">'); ?>
		 </td>
	    </tr>
		
		<tr>
		<td width="221">Plan Unit Cost(In USD)</td>
		<td width="725">
		<input type="text"  name="PlanUSDCost" id="PlanUSDCost" value="<?php echo $PlanUSDCost; ?>" />
 		For eg. 10.00
		<?php echo form_error('PlanUSDCost','<p class="error">'); ?>
		 </td>
	    </tr>
		
		<tr>
		<td>&nbsp;</td>
    	<td align="left">
		<input type="submit" name="submit" value="Save" id="save" class="save" />
		&nbsp;
		<input type="button" name="cancel" id="cancel" value="Cancel" class="save" /></td>
		</tr>
		
		</table>
<?php echo form_close(); ?>
	
			
			</div>
            <div class="clear"></div>			
          </div>
          <div class="clear"></div>
        </div>