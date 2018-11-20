<div id="content">
          <div class="box-element">
            <div class="box-head">
              <h3><?php echo $title;?></h3>
			  
            </div>
            <div class="productbox">
<?php echo form_open('admin_bak/users/index',array('name' => 'search', 'id' => 'search'));?>
<table class="table" width="100%" border="0" cellspacing="2" cellpadding="0">
<tr>
<td align="left" class="currency-intro">
Search key :
<input type="text" name="key" class="custom-input" size="32" value="<?php echo $this->input->post("key");?>" />
<i class="fa fa-search" aria-hidden="true"></i>
<input type="submit" name="submit" value="Search" id="search" class="save btn custom-btn" />
<input type="button" name="showall" class="btn custom-btn" value="Show All" id="search" onclick="window.location='<?php site_url('admin_bak/users')?>'" />
</td>
</tr>
</table>
<?php echo form_close();?>
<div class="pagination" style="float:left; color:#FF0000;">	  
</div>
<?php if(is_array($result) && sizeof($result)>0){ ?>
<div class="pagination" style="float:right;">
<?php echo $paginglinks; ?></div>
 <?php
                if ($this->session->flashdata('activatemessage')) {?>
                 <div class="alert alert-success alert-dark "><?php   echo $this->session->flashdata('activatemessage');?>
				  
                        <button type="button" class="close" data-dismiss="alert">×</button>
                    </div>
                <?php }
                ?><table class="table table-bordered " style="background-color: #ebebeb;">
<tbody>

	<tr>
		<td valign="middle" >Sr.No</td>
		<!--<td valign="middle" >RequestType</td>-->
		<td valign="middle" >RequestComment</td>
		<td valign="middle" >CompanyName</td>
		<td valign="middle" >ContactPerson</td>
		<td valign="middle" >ContactPhone</td>
		<td valign="middle" >ContactEmail</td>
        <td valign="middle" >EnquiryDate</td>
        <td valign="middle" >Action</td>
	</tr>
	
	<?php
	
	
	
	$n = 0;
	$counter = 0;
	$counter =  $counter + $per_page;
	
	foreach($result as $key=>$value) {
		
	  $counter++;
                            
                            
	   //if($n%2 == 0) $class = "even"; else $class = "";
	?>
		
		<tr class="<?php //echo $class;?>" style="background-color:#fff">
			<td><?php echo $counter;?></td>
			<!--<td><?php //echo $value['RequestType'];?></td>-->
            <td><?php echo $value['RequestComment'];?></td>
			<td><?php echo $value['CompanyName'];?></td>
			<td><?php echo $value['ContactPerson'];?></td>
			<td><?php echo $value['ContactPhone'];?></td>
			<td><?php echo $value['ContactEmail'];?></td>
			<td><?php echo $value['EnquiryDate'];?></td>
            <td><?php if($value['ActivateStatus']==1){ ?>activated
			<?php }else{?>
			<a href="<?php echo site_url('admin_bak/users/activate/'.$value['EnquiryId'])?>">activate for register</a></td>
			<?php } ?>
		</tr>
			
		
		<?php 
			
	

}
	?>
	
	
</tbody>
</table>
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