<?php //echo '<pre>'; print_r($userdata); die;?>

<div id="mid-continer">
  <!-- left side links -->
  <?php include_once("leftlinks.php");?>
  <div class="part-right">
    <h1><?php echo $title;?></h1>
    <div class="pink-part">
    <table width="100%" border="0" cellspacing="0" cellpadding="3" >
      <tr>
        <td width="180px;" align="left"><h2>General Information</h2></td>
        <td align="right">&nbsp;</td>
      </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="3" >
      <tr>
        <td colspan="2"><?php echo form_open_multipart('seeker/addphoto',array('name' => 'addphoto', 'id' => 'addphoto'));?>
          <div class="divFull" align="center">
            <p class="grntxt" style="color:#FF0000"><?php echo $this->session->flashdata('success');?></p>
            <?php echo $this->session->flashdata('error');?> 
          </div>
          <div class="divFull pad-b10">
            <div class="divFull"><b>Adding a picture makes your profile and posted jobs look more appealing to helpers seeking families or responding to jobs.</b></div>
          </div>
          <?php if($ProfilePicture!='' && file_exists('uploads/seeker/'.$ProfilePicture)){?>
          <div class="divFull pad-b10">
            <div class="w250">&nbsp;</div>
            <div class="w250">
              <div class="divFull pad-b10">
                <div class="divFull">
                  <?php if($this->userdata['ProfilePicture']) {?>
                  <img  class="side-image"src="<?php echo base_url() ?>image.php/site.jpg?width=190&amp;height=120&amp;cropratio=1:0.6&amp;image=<?php echo base_url() ?>uploads/seeker/<?php echo $this->userdata['ProfilePicture']?>"  alt="" />
                  <?php } else { ?>
                  <img  class="side-image"src="<?php echo base_url() ?>images/noimage1.jpg"  alt="" />
                  <?php } ?>
                </div>
              </div>
            </div>
            <?php } ?>
            <p>&nbsp;</p>
            <div class="divFull pad-b10">
              <div class="w250"><b>Upload your profile photo*</b></div>
              <div class="w250">
                <div class="divFull pad-b10">
                  <div class="divFull">
                    <input type="file" name="userfile" />
                  </div>
                </div>
              </div>
              <div class="divFull pad-b10">
                <div class="w150">&nbsp;</div>
                <div class="w200 mar-20">
                  <input type="submit" value="Upload" class="btnblue" />
                </div>
              </div>
            </div>
          </div>
      </div>
      
      <?php echo form_close();?>
      </td>
      
      </tr>
       <tr>
	  	<td align="left">
			<div class="divFull">
			<div class="error1">
			Note: 
				<ul>
					<li>Allowed image type : gif, jpg, png</li>
					<li>Max upload image size : 2 MB</li>
					<li>Max upload image width : 1024</li>
					<li>Max upload image height : 768</li>
				</ul>
		</div>
	</div>
		</td>
	  </tr>
    </table>
  </div>
</div>
</div>
