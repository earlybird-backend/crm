<script language="javascript">
jQuery(document).ready(function() {
		jQuery('#cancel').click(function(){
			window.location= "<?php echo site_url('admin_bak/configuration/')?>";
		});
  });
</script>

<div id="content">
  <div class="box-element panel ">
    <div class="box-head">
      <h3><?php echo $title;?></h3>
    </div>
    <div class="productbox custom-from"> <?php echo form_open_multipart('admin_bak/configuration/edit',array('name' => 'search', 'id' => 'search'));?>
      <table width="100%" border="0" cellspacing="4" cellpadding="0">
        <tr>
          <td width="157">&nbsp;</td>
          <td width="806" style="color:#FF0000;"><?php 
echo validation_errors();
if($this->session->flashdata('message'))
{
 echo $this->session->flashdata('message');
}
?>
          </td>
        </tr>
      </table>
      <?php
extract($result[0]);
?>
      <table width="100%" cellspacing="0" cellpadding="4" border="0" class="data">
        <tbody>
          <tr>
            <th width="28%" valign="middle" class="ft-size-15">Title
              </td>
            <th width="72%" valign="middle" class="ft-size-15">Value
              </td>
          </tr>
          <tr class="even mr-top-20">
            <td class="ft-size-15">PlanStartTime</td>
            <td><input type="text" size="40" name="PlanStartTime" class="PlanStartTime mr-top-20 custom-ins"  value="<?php echo $PlanStartTime;?>" />
            </td>
          </tr>
          <tr class="mr-top-20">
            <td class="ft-size-15">PlanEndTime</td>
            <td><input type="text" size="40" name="PlanEndTime" class="PlanEndTime mr-top-20 custom-ins" value="<?php echo $PlanEndTime;?>" />
            </td>
          </tr>
          <tr class="even">
            <td class="ft-size-15">ProposalTime</td>
            <td><input type="text" size="40" name="ProposalTime" class="ProposalTime mr-top-20 custom-ins" value="<?php echo $ProposalTime;?>" />
            </td>
          </tr>
          <tr>
            <td class="ft-size-15">ApproveProposalTime (Next Day)</td>
            <td><input type="text" size="40" name="ApproveProposalTime" class="ApproveProposalTime mr-top-20 custom-ins" value="<?php echo $ApproveProposalTime;?>" />
            </td>
          </tr>
         
        
          <tr class="">
            <td>&nbsp;</td>
            <td align="left"><input type="submit" name="submit" value="Save" id="save" class="save btn custom-btn mr-top-20" />
              &nbsp;
              <input type="button" name="cancel" id="cancel" value="Cancel" class="save btn custom-btn mr-top-20" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
