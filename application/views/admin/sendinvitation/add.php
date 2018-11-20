<script language="javascript">
jQuery(document).ready(function() {
		jQuery('#cancel').click(function(){		
			window.location= "<?php echo site_url('admin_bak/sendinvitation/')?>";
		});
  });
</script>
<div id="content">
          <div class="box-element">
            <div class="box-head">
              <h3> Add <?php echo $title;?></h3>
            </div>
            <div class="productbox">
			<h4>Send Invitation Email</h4>
			<hr/>
			<?php echo form_open('admin_bak/sendinvitation/add/',array('name' => 'editcurrency', 'id' => 'editcurrency'));?>
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
		<td width="100">Email</td>
		<td>
		<input type="text"  name="Email" id="Email" value="<?php echo set_value('invite_email');?>" class="custom-input"/> 	

		<input type="submit" name="submit" value="Add Email" id="save" class="save m-a-3 btn btn-small btn custom-btn" />		
		<?php echo form_error('Email','<p class="error">'); ?>
		 </td>
	 </tr>	

		
		</table>
<?php echo form_close(); ?>
	
	
	
			
			</div>
            <div class="clear"></div>			
          </div>
          <div class="clear"></div>
        </div>