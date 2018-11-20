<div class="continer">
<div class="message">
<h1>Error</h1>

<p>&nbsp;&nbsp;</p>

<p class="error" align="center" style="font-weight:bold">

<?php if($this->session->flashdata('message')!='') {?>
	<?php echo $this->session->flashdata('message');?>
<?php } else{?>	

	Please contact to Administrator for more details!!

<?php } ?>
</p>

<p>&nbsp;&nbsp;</p>
<p>&nbsp;&nbsp;</p>
<p>&nbsp;&nbsp;</p>

</div>
</div>