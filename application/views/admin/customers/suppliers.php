<script language="javascript">
    $(document).ready(function () {
        $('#save').click(function () {
            window.location = "<?php echo site_url('admin_bak/customers/suppliers') ?>";
        });
    });

</script>
<?php
if (is_array($data) && sizeof($data) > 0) {
    // extract($data[0]);
    // echo '<pre>';print_r($data); die; 
}
?>


<div id="content">
    <div class="box-element">
        <div class="box-head">
            <h3><?php echo $title; ?></h3>			 
        </div>
        <div class="productbox">
            <?php echo form_open('admin_bak/customers/suppliers/' . $datas, array('name' => 'search', 'id' => 'search')); ?>
            <table class="table" width="100%" border="0" cellspacing="2" cellpadding="0">
                <tr>
                    <td align="left" class="currency-intro">
                        Search key :
                        <input type="text" class="custom-input"name="key" size="32" value="<?php echo $this->input->post("key"); ?>" />
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input type="submit" name="submit" value="Search" id="search" class="save btn custom-btn" />
                        <input type="button" name="showall" value="Show All" class="btn custom-btn" id="search" onclick="window.location = '<?php site_url('admin_bak/customers/suppliers' . $datas) ?>'" />
                    </td>
                </tr>
            </table>
            <?php echo form_close(); ?>

            <?php if (is_array($data) && sizeof($data) > 0) { ?>
                <div class="pagination" style="float:right;">
                    <?php echo $paginglinks; ?></div>

                <div class="table-light">
                    <div class="table-header">
                        <div class="table-caption">
                           <?php echo $title; ?>
                        </div>
                    </div>
                    <table class="table table-bordered panel">
                        <thead>
                            <tr>
                                
                                <th>Supplier ID</th>
                                <th>Supplier Name</th>
                                <th>Vendor Code</th>
                                <th>Contact</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $n = 0;
                            $counter = 0;
                            $counter = $counter + $per_page;

                            foreach ($data as $value) {
                                ?>
                                <tr>
                                    
                                    <td><?php echo $value->SupplierId; ?></td>
                                    <td><?php echo $value->Supplier; ?></td>
                                    <td><?php echo $value->Vendorcode; ?></td>
                                    <td><?php echo $value->ContactPerson; ?></td>
                                    <td><?php echo $value->Phone; ?></td>
                                    <td><?php echo $value->Email; ?></td>
                                </tr>

                                <?php
                                $counter++;
                            }
                            ?>

                        </tbody>
                    </table>
<!--                    <div class="table-footer">
                        Footer
                    </div>-->
                </div>
                
                <div class="pagination" style="float:right;">
                    <?php echo $paginglinks; ?></div>

            <?php } else { ?>
                <p align="center">No Record Found!</p>
            <?php } ?>


            <p>&nbsp;</p>

        </div>
        <div class="clear"></div>			
    </div>
    <div class="clear"></div>
</div>
