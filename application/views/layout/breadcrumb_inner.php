<div class="breadcrumb"> <a href="<?php echo base_url() ?>">Home</a> > <?php echo ucfirst($this->uri->segment(1));?>
<?php if($this->session->userdata("logged") == TRUE && $this->session->userdata("UserId") !='' ){?>
  <div style="float:right"><b><a href="<?php echo site_url('logout')?>">Logout</a></b></div>
<?php } ?>  
</div>
