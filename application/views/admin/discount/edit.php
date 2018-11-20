<script language="javascript">
jQuery(document).ready(function() {
		jQuery('#cancel').click(function(){		
			window.location= "<?php echo site_url('admin_bak/plans/discount')?>";
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
			
			
	
	
	<hr/>
<h4>Discount Setting</h4>
			<hr/>
<?php echo form_open('admin_bak/plans/editdiscount/'.$DiscountId,array('name' => 'editpage', 'id' => 'editpage'));?>	
	<table>
		<tr>
		<td width="200">Time Duration </td>
		<td width="746">
		<input type="text" size="6" name="PlanDuration" id="PlanDuration" value="<?php echo $PlanDuration; ?>" /> For eg.1,2.
		
		 
		 <input type="radio" name="DurationType" value="month" <?php if($DurationType=='month') echo 'checked';?> />Month
		 <input type="radio" name="DurationType" value="year" <?php if($DurationType=='year') echo 'checked';?> />Year
		<?php echo form_error('PlanDuration','<p class="error">'); ?>
		<?php echo form_error('DurationType','<p class="error">'); ?>
        </td>
	    </tr>
		
		<tr>
		<td width="221">Discount</td>
		<td width="725">
		<input type="text"  name="PlanDiscount" id="PlanDiscount" value="<?php echo $PlanDiscount; ?>" />(in %age)
		<?php echo form_error('PlanDiscount','<p class="error">'); ?>
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