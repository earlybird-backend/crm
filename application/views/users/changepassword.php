<div id="mid-continer">
  <!-- left side links -->
  <?php include_once("leftlinks.php");?>
  <div class="part-right">
    <h1><?php echo $title;?></h1>
    <div class="pink-part">
      <table width="100%" border="0" cellspacing="0" cellpadding="3" >
        <tr>
          <td align="center" style="color:#FF0000">
		  	<div class="divFull pad-b10">
              <div class="w150">&nbsp;</div>
              <div class="w250">
			<?php echo $this->session->flashdata("changepassword_error");?>
			</div></div>
		  </td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="3" >
        <tr>
          <td colspan="2">
		  <?php echo form_open_multipart('seeker/changepassword',array('name' => 'changepassword', 'id' => 'changepassword'));?>
            <div class="divFull pad-b10">
              <div class="w150"><strong>Old Password*</strong></div>
              <div class="w250">
                <input type="password" class="inp3" name="oldpassword" id="oldpassword" value="" />
                <?php echo form_error('oldpassword','<p class="error">');?></div>
            </div>
            <div class="divFull pad-b10">
              <div class="w150"><strong>New Password*</strong></div>
              <div class="w250">
                <input type="password"class="inp3" name="password" id="password" value="" />
                <?php echo form_error('password','<p class="error">');?> </div>
            </div>
            <div class="divFull pad-b10">
              <div class="w150"><strong>Confirm Password*</strong></div>
              <div class="w250">
                <input type="password" class="inp3" name="passconf" id="passconf" value="" />
                <?php echo form_error('passconf','<p class="error">');?> </div>
            </div>
            <div class="divFull pad-b10">
              <div class="w150">&nbsp;</div>
              <div class="w200 mar-20">
                <input type="submit" value="Reset Password" class="btnblue" />
              </div>
            </div>
            <?php echo form_close();?> </td>
        </tr>
      </table>
    </div>
  </div>
</div>
