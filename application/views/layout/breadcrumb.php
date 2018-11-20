<div class="breadcrumb"> <a href="<?php echo base_url() ?>">Home</a> > <a href="javascript:void(0)"><?php echo ($pageDetails[0]['ParentPage']!='')?$pageDetails[0]['ParentPage']:'Helper';?></a> > <?php echo $pageDetails[0]['PageTitle'];?> 
<?php if($this->session->userdata("logged") == TRUE && $this->session->userdata("UserId") !='' ){?>
  <div style="float:right"><b><a href="<?php echo site_url('logout')?>">Logout</a></b></div>
<?php } ?>  
</div>
