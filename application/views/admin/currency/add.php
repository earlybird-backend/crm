<script language="javascript">
jQuery(document).ready(function() {
		jQuery('#cancel').click(function(){		
			window.location= "<?php echo site_url('admin_bak/currency/')?>";
		});
  });
</script>
<div id="content">
          <div class="box-element">
            <div class="box-head">
              <h3> Add <?php echo $title;?></h3>
            </div>
            <div class="productbox">
			<h4>Add Currency</h4>
			<hr/>
			<?php echo form_open('admin_bak/currency/add/',array('name' => 'editcurrency', 'id' => 'editcurrency'));?>
<table width="100%" border="0" cellspacing="4" cellpadding="0" class="pdt-custom">
<tr>
<td colspan="4" style="color:#FF0000;">
<?php
//echo validation_errors();
echo $this->session->flashdata('message');
?>		
</td>
		</tr>
	

	
	
	<tr>
		<td width="100">Currency Name</td>
		<td>
		<input type="text"  name="CurrencyName" id="CurrencyName" value="<?php echo set_value('CurrencyName');?>" class="custom-input"/> 
		 </td>
		<td><?php echo form_error('CurrencyName','<p class="error">'); ?></td>
		</tr><tr>
		<td width="100">Currency Sign</td>
		<td>
		<input type="text"  name="CurrencySign" id="CurrencySign" value="<?php echo set_value('CurrencySign');?>" class="custom-input"/>	
        </td>
		<td><?php echo form_error('CurrencySign','<p class="error">'); ?></td>	
		</tr><tr>
		<td>
		<input type="submit" name="submit" value="Add Currency" id="save" class="save m-a-3 btn btn-small btn custom-btn" />		
		
		
		 </td>
	 </tr>	

		
		</table>
<?php echo form_close(); ?>
	
	
	
			
			</div>
            <div class="clear"></div>			
          </div>
          <div class="clear"></div>
        </div>