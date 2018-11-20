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
            <table width="100%" border="0" cellspacing="4" cellpadding="0">
                <tr>
                    <td colspan="4" style="color:#FF0000;">
                        <?php
                        echo $this->session->flashdata('message');
                        ?>		
                    </td>
                </tr>
                <tr>
                    <td width="221">Currency Name</td>
                    <td width="725">
                        <input type="text"  name="CurrencyName" id="CurrencyName" value="<?php echo $CurencyName; ?>" />

                        <?php echo form_error('CurrencyName', '<p class="error">'); ?>
                    </td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td align="left">
                        <input type="submit" name="submit" value="Save" id="save" class="save" />
                        
                </tr>

            </table>
            <?php echo form_close(); ?>


        </div>
        <div class="clear"></div>			
    </div>
    <div class="clear"></div>
</div>