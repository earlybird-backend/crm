<div id="content">
          <div class="box-element">
            <div class="box-head">
              <h3 ><?php echo $title;?></h3>
			  <b class="m-a-3 btn btn-small btn-rounded custom-btn"><?php echo anchor('admin_bak/sendinvitation/add', 'Add Email');?></b>
            </div>
			<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success alert-dark"><?php   echo $this->session->flashdata('message');?>
				  
                        <button type="button" class="close" data-dismiss="alert">×</button>
                    </div>
				
			<?php } ?>
		            <div class="productbox">
<?php echo form_open('admin_bak/sendinvitation/index',array('name' => 'search', 'id' => 'search'));?>
<table class="table" width="100%" border="0" cellspacing="2" cellpadding="0">
<tr>
<td align="left" class="currency-intro">
Search key :
<input type="text" name="key" size="32" value="<?php echo $this->input->post("key");?>" class="custom-input"/> <i class="fa fa-search" aria-hidden="true"></i>
<input type="submit" name="submit" value="Search" id="search" class="save btn custom-btn"  />
<input type="button" name="showall" value="Show All" id="search" onclick="window.location='<?php site_url('admin_bak/sendinvitation')?>'" class="btn custom-btn" />
</td>
</tr>
</table>
<?php echo form_close();?>
<div class="pagination" style="float:left; color:#FF0000;">
<?php 
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
<div class="panel-body">
<table class="table table-striped" width="100%" cellspacing="0" cellpadding="4" border="0" class="data" id="editable-table">
<tbody>

	<tr>
		<td valign="middle">Sr.No</td>
		<td valign="middle" class="text-center">Email</td>	
        
	</tr>
	
	<?php
	
	
	
	$n = 0;
	$counter = 0;
	$counter =  $counter + $per_page;
	
	foreach($result as $key=>$value) {
		
	  $counter++;                           
                            
	  
	?>
		
		<tr class="">
			<td><?php echo $counter;?></td>
			<td class="text-center"><?php echo $value['invite_email'];?></td>            
			
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