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
            <div class="productbox">&nbsp;
			<?php echo form_open('admin_bak/plans/edit/'.$PlanId,array('name' => 'editpage', 'id' => 'editpage'));?>
<table width="100%" border="0" cellspacing="4" cellpadding="0">
<tr>
<td colspan="4" style="color:#FF0000;">
<?php
//echo validation_errors();
echo $this->session->flashdata('message');
?>		</td>
		</tr>
	
	<tr>
		<td width="221">Plan Category</td>
		<td width="725">
		<select name="PlanCategory">
			<option value="">Select Plan Category</option>
			<option value="chinese" <?php if($PlanCategory=='chinese')echo 'selected';?>>Chinese</option>
			<option value="english" <?php if($PlanCategory=='english')echo 'selected';?>>English</option>
		</select>
		
 		
		<?php echo form_error('PlanCategory','<p class="error">'); ?>
		 </td>
	 </tr>
	
	<tr>
		<td width="221">Plan Name</td>
		<td width="725">
		<input type="text" size="40" name="PlanName" id="PlanName" value="<?php echo $PlanName; ?>" />
 		For eg. 5G,10G
		<?php echo form_error('PlanName','<p class="error">'); ?>
		 </td>
	 </tr>
	 
	 <tr>
		<td width="221">PlanSpace</td>
		<td width="725">
		<input type="text" size="40" name="PlanSpace" id="PlanSpace" value="<?php echo $PlanSpace; ?>" />
 		For eg. 5,10.
		<?php echo form_error('PlanSpace','<p class="error">'); ?>
		 </td>
	  </tr>
		
		
		<tr>
		<td width="200">PlanDuration </td>
		<td width="746">
		<input type="text" size="6" name="PlanDuration" id="PlanDuration" value="<?php echo $PlanDuration; ?>" /> For eg.1,2.
		<input type="radio" name="DurationType" value="month" <?php if($DurationType=='month') echo 'checked';?> />Month
		 <input type="radio" name="DurationType" value="year" <?php if($DurationType=='year') echo 'checked';?> />Year
		 <?php echo form_error('PlanDuration','<p class="error">'); ?>
		<?php echo form_error('DurationType','<p class="error">'); ?>
        </td>
	    </tr>
		
		<tr>
		<td width="221">PlanCost(In ￥)</td>
		<td width="725">
		<input type="text" size="40" name="PlanCost" id="PlanCost" value="<?php echo $PlanCost; ?>" />
 		For eg. 10.00
		<?php echo form_error('PlanCost','<p class="error">'); ?>
		 </td>
	    </tr>
		
		
		<tr>
		<td width="221">PlanCost(In USD)</td>
		<td width="725">
		<input type="text"  name="PlanUSDCost" id="PlanUSDCost" value="<?php echo $PlanUSDCost; ?>" />
 		For eg. 10.00
		<?php echo form_error('PlanUSDCost','<p class="error">'); ?>
		 </td>
	    </tr>
		
		
		<tr>
		<td width="221">PlanDiscount</td>
		<td width="725">
		<input type="text" size="40" name="PlanDiscount" id="PlanDiscount" value="<?php echo $PlanDiscount; ?>" />(in %age)
		<?php echo form_error('PlanDiscount','<p class="error">'); ?>
		 </td>
	    </tr>
		
		<tr>
		<td width="221">PromoDiscount</td>
		<td width="725">
		<input type="text" size="40" name="PromoDiscount" id="PromoDiscount" value="<?php echo $PromoDiscount; ?>" />(in %age)
		<?php echo form_error('PromoDiscount','<p class="error">'); ?>
		 </td>
	    </tr>
		 <tr>
		 <td colspan="2">&nbsp;</td>
		 </tr>
	    <tr>
		<td>&nbsp;</td>
    	<td align="left">
		<input type="submit" name="submit" value="Save" id="save" class="save" />
		&nbsp;
		<input type="button" name="cancel" id="cancel" value="Cancel" class="save" /></td>
		</tr>
    	</table>
		<?php echo form_close();?>
			
			</div>
            <div class="clear"></div>			
          </div>
          <div class="clear"></div>
        </div>