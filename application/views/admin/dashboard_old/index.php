<div id="content">
  <div class="box-element">
    <div class="box-head">
      <h3><?php echo $title;?>&nbsp;:&nbsp;<b>Summary Section</b></h3>
    </div>
    <div class="productbox">
	<p>Select date for summary</p>
<?php echo form_open('admin_bak/dashboard',array('name' => 'dashboard', 'id' => 'dashboard', 'method'=>'get'));?>
	<p><?php echo $Month;?><?php echo $Year;?>
	   <input type="submit" name="" value="Submit">
     </p>
	<?php echo form_close();?>
     <table cellpadding="0" cellspacing="1" border="0" width="100%" height="100%">
       <!-- <tr height="20">
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="left">&nbsp;</td>
        </tr>-->
        <tr height="20">
          <td width="43%" align="left">&nbsp;&nbsp;<strong>Total Seeker</strong></td>
          <td width="1%" align="center"><strong>&nbsp;:</strong></td>
          <td width="56%" align="left"><a href="<?php echo site_url('admin_bak/seekers');?>"><?php echo $TotalSeeker?></a></td>
        </tr>
		<tr height="20">
          <td align="left">&nbsp;&nbsp;<strong>Total Provider&nbsp;</strong></td>
          <td align="center"><strong>:</strong></td>
          <td align="left"><a href="<?php echo site_url('admin_bak/providers');?>"><?php echo $TotalProvider?></a></td>
          <td width="0%"></td>
        </tr>
        <tr height="20">
          <td align="left">&nbsp;&nbsp;<strong>Seeker Subscriptions&nbsp;</strong>(1 months, 3 months, 6 months, 12 months) </td>
          <td align="center"><strong>:</strong></td>
          <td align="left"><a href="<?php echo site_url('admin_bak/seekers');?>"><?php echo $TotalSeekerSubscription?></a> (<?php echo $SeekerSubscription_1month?>,<?php echo $SeekerSubscription_3month?>,<?php echo $SeekerSubscription_6month?>,<?php echo $SeekerSubscription_12month?>)</td>
        </tr>
		
		 <tr height="20">
          <td align="left">&nbsp;&nbsp;<strong>Provider/Helper Subscriptions&nbsp;</strong>(1 months, 3 months, 6 months, 12 months) </td>
          <td align="center"><strong>:</strong></td>
          <td align="left"><a href="<?php echo site_url('admin_bak/providers');?>"><?php echo $TotalProviderSubscription?></a> (<?php echo $ProviderSubscription_1month?>,<?php echo $ProviderSubscription_3month?>,<?php echo $ProviderSubscription_6month?>,<?php echo $ProviderSubscription_12month?>)</td>
        </tr>
		
        <tr height="20">
          <td align="left">&nbsp;&nbsp;<strong>Total Jobs/Listing</strong>&nbsp;(Free, Featured)</td>
          <td align="center"><strong>&nbsp;:</strong></td>
          <td align="left"><a href="<?php echo site_url('admin_bak/job');?>"><?php echo $TotalJobs?></a>&nbsp;(<?php echo $TotalFreeJobs?>&nbsp;,&nbsp;&nbsp;<?php echo $TotalFeaturedJobs?>) </td>
        </tr>
      
        <tr height="20">
          <td align="left">&nbsp;&nbsp;<strong>Today messages sent by users</strong></td>
          <td align="center"><strong>:</strong>&nbsp;</td>
          <td align="left">&nbsp;<a href="<?php echo site_url('admin_bak/messages');?>"><?php echo $Totalmessages?></a></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
    <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
