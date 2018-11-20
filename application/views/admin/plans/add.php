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
			<h4>Unit Price Setting For Space</h4>
			<hr/>
			<?php echo form_open('admin_bak/currency/add/',array('name' => 'editcurrency', 'id' => 'editcurrency'));?>
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
		<td width="221">Currency Name</td>
		<td width="725">
		<input type="text"  name="CurrencyName" id="CurrencyName" value="<?php echo set_value('CurrencyName');?>" /> 		
		<?php echo form_error('CurrencyName','<p class="error">'); ?>
		 </td>
	 </tr>	
		
		<tr>
		<td>&nbsp;</td>
    	<td align="left">
		<input type="submit" name="submit" value="Add Currency" id="save" class="save" />		
		</tr>
		
		</table>
<?php echo form_close(); ?>
	
	
	
			
			</div>
            <div class="clear"></div>			
          </div>
          <div class="clear"></div>
        </div>