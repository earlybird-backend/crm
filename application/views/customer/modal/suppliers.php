
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="list-form"><?php echo $this->lang->line('plans')['LIST UPLOAD'] ?></div>
                
                <div class="file-choose-form head-file-wrap step">
                    <div class="head-choose_file">
                        <?php echo $this->lang->line('plans')['Upload supplier List'] ?>
                    </div>
                    <div class="form-group relative cgroup dgroup form-group-sec">
                        <input type="file" name="" id="supplierfile">
                        <label for="supplierfile" class="btn btn-rounded btn-outline pull-left"><?php echo $this->lang->line('plans')['Choose file'] ?></label>
                        <span class="ftype"><?php echo $this->lang->line('plans')['No file Choosen'] ?></span>
                    </div> <input type="button" name="uploadsupplierlist" class="btn-upload uploadsupplierlist" id="uploadsupplierlist" value="<?php echo $this->lang->line('plans')['Upload'] ?>" >
               
                <div style="float: left; width: 100%;">
                <span id="supplierlists-check-error" class="error"></span>
                </div>
                 </div>
                <p class="upload-file-info"><?php echo $this->lang->line('plans')['Upload_Supplier_modal_tip'] ?></p>  
            </div>
            <!--<div class="file-sec-wrap-info">
            <a href="#" class="btn-rectangular-wrap" data-dismiss="modal">Cancel</a> &nbsp;  <a href="#" data-dismiss="modal" class="btn-rectangular"  data-toggle="modal" data-target="#myModal3">Submit</a>
            </div>-->

        </div>

    </div>
</div>




<div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="list-form"><?php echo $this->lang->line('plans')['Add A supplier'] ?></div>

                <section class="popup-input-sec" id="section_pop2">
                    <form>

                        <div class="table-new-create bg_white" id="suppliers-list">         

                        </div>
                    </form>
                </section>

            </div>
            <div class="file-sec-wrap-info">			
                <a href="#" class="btn-rectangular-wrap pdt-btn"><?php echo $this->lang->line('plans')['CANCEL'] ?></a> &nbsp;  <input type="button" name="confirmlist" class="btn-rectangular confirmlist"  id="confirmlist" value="<?php echo $this->lang->line('plans')['CONFIRM'] ?>" >
            </div>

        </div>

    </div>
</div>
