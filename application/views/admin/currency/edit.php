<?php
if (is_array($data) && sizeof($data) > 0) {
    extract($data[0]);
}
//echo '<pre>';print_r($data);die;
?>
<div id="content">
    <div class="box-element">
        <div class="box-head">
            <h3> Add <?php echo $title; ?></h3>
        </div>
        <div class="productbox">
            <h4>Currency Edit</h4>
            <hr/>
            <?php echo form_open('admin_bak/currency/edit/' . $CurrencyId, array('name' => 'editpage', 'id' => 'editpage')); ?>
            <table width="100%" border="0" cellspacing="4" cellpadding="0" class="pdt-custom">
                <tr>
                    <td colspan="4" style="color:#FF0000;">
                        <?php
                        echo $this->session->flashdata('message');
                        ?>		
                    </td>
                </tr>
                 <tr>
                    <td width="100">Currency Name</td>
                    <td>
                        <input type="text"  name="CurrencyName" class="custom-input" id="CurrencyName" value="<?php echo $CurencyName; ?>" readonly />
                      </td>
					   <td><?php echo form_error('CurrencyName', '<p class="error">'); ?></td>
					  </tr><tr>
					  <td width="100">Currency Sign</td>
					  <td>
					  <input type="text"  name="CurrencySign" id="CurrencySign" value="<?php echo $CurrencySign;?>" class="custom-input"/>
					  </td>                       
						 <td><?php echo form_error('CurrencySign', '<p class="error">'); ?></td>
						  </tr><tr>
						 <td>
                             <input type="submit" name="submit" value="Save" id="save" class="save m-a-3 btn btn-small btn custom-btn" />
                    </td>
                </tr>


            </table>
            <?php echo form_close(); ?>


        </div>
        <div class="clear"></div>			
    </div>
    <div class="clear"></div>
</div>