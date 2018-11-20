<script language="javascript">
    $(document).ready(function () {
        $('#save').click(function () {           
             window.location = "<?php echo site_url('admin_bak/suppliers/') ?>";
        });       
    });

</script>
<?php
if (is_array($data) && sizeof($data) > 0) {
   // extract($data[0]);
    //echo '<pre>';print_r($data[0]);
	
}
?>
<div id="content-wrapper">
   
    <div class="panel-heading btn btn-add custom-btn"><span><a href="<?php echo site_url('admin_bak/suppliers/') ?>">Back</a></span></div>

    <div class="row mr-top-20">
        <div class="col-sm-12">   
            

            <div class="panel-body">               

                <div class="row form-group">
                    <label class="col-sm-4 control-label">User ID</label>
                    <div class="col-sm-8">
                        <?php echo $data[0]->EmailAddress; ?>                
                       </div>
                </div>         

                 <div class="row form-group">
                    <label class="col-sm-4 control-label">Contact</label>
                    <div class="col-sm-8">
                        <?php echo $data[0]->ContactName; ?>              
                       </div>
                </div>

				<div class="row form-group">
                    <label class="col-sm-4 control-label">Phone</label>
                    <div class="col-sm-8">
                        <?php echo $data[0]->Cellphone; ?>               
                       </div>
                </div>               
                
               

				<div class="row form-group">
                    <label class="col-sm-4 control-label">Position</label>
                    <div class="col-sm-8">
                        <?php echo $data[0]->Position; ?> 
                       </div>
                </div>	
                	<div class="row form-group">
                    <label class="col-sm-4 control-label">Reset Password</label>
                    <div class="col-sm-8">
                      	<a href="<?php echo site_url('admin_bak/suppliers/resetpassword/'.$data[0]->UserId)?>">Action</a>
                       </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-sm-4 control-label">ActiveUser</label>
                    <div class="col-sm-8">
                        <input type="checkbox" name="activeuser" value="" <?php if($data[0]->RegisterStatus==0){
                            echo '';                            
                        }else {
                            echo 'checked';
                        }?>><br>
                       </div>
                </div>
                
            </div>
        </div>
    </div>	

<?php //} ?>	