<?php
if (is_array($data) && sizeof($data) > 0) {
    extract($data[0]);
    //echo '<pre>';print_r($data[0]);
	
}
?>
<div id="content-wrapper">    
   
<a href="<?php echo site_url('admin_bak/plans/proposalcalculate/'.$planid); ?>">Back</a>
<p><p>
			
<?php echo form_open('admin_bak/plans/addproposal/'.$planid,array('name' => 'addproposal', 'id' => 'addproposal', 'enctype' => 'multipart/form-data')); ?>

<table width="100%" cellspacing="0" cellpadding="4" border="0" class="data">
<tbody>

	<tr>
		<td><b>Upload Winner Supplier XLS File</b>
			<br/>Note: Please check the excel upload list&nbsp;<a download href="<?php echo base_url('assets/admin_bak/uploads/winners.xls'); ?>" target="_blank">sample here</a>
		</td>
		
		<td>
			<br/><input type="file" name="userfile" id="userfile" class="btn btn-primary" required />
			<br/><input type="submit" name="uploadbtn" value="Upload File" id="uploadbtn" class="btn btn-primary" />
		</td>	
		
	</tr>
	  
        
</tbody>
</table>
        <table id="errorblock" style="display:none;"  width="100%" border="0" cellspacing="2" cellpadding="0">
        <tr>
          <td align="center" style="color:#FF0000;">
                <span id="error_File" style="color:#FF0000; text-align: center;">*</span>
          </td>
        </tr>
      </table>
<?php echo form_close();?>	
   
</div>	

