

<div class="modal fade" id="myModal23" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="main_sent text-center">

                    <!--div class="icon-wrap">
                        <img src="<?php // echo base_url(); ?>assets/images/alert.png" width="60px;">
                    </div-->
                    <section class="popup-input" id="section_pop2">
                       <div class="popup-input-sec">

                    <h4><?php echo $this->lang->line('plans')['Add APR Settings'] ?></h4>

                    <p class="form-note-bottom"><?php echo $this->lang->line('plans')['APR_modal_tip'] ?>
                    </p>
<!--                    <a href="<?php //echo site_url('customer/settings');   ?>" class="btn-rectangular">Add Currency Setting</a>-->

                    <div id="myfields" class="myfieldsc error" style="text-align:center"></div>
                    <?php
                    echo form_open('', array('name' => 'addcurrency',
                        'class' => 'wrap_apr_label addcurrency', 'id' => 'signup-form'));
                    ?>
              
                        <?php if ($this->session->flashdata('settingsuccess')) { ?>
                            <?php //echo $this->session->flashdata('settingsuccess');     ?>
                        <?php }
                        ?>
                     
                            <div class="table-wrap-sec full">
                                
                                <div class="form-row two-childs">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('plans')['Currency'] ?> <span>*</span></label>   
                                        <span class="newaddcurrency"></span>
                                        <select name="CurrencyListp" class="CurrencyListp form-control">
                                            <option value=""><?php echo $this->lang->line('plans')['Select Currency'] ?></option>
                                            <?php foreach ($admincurrency as $key => $value) { ?>
                                                <option value="<?php echo $value['CurrencyId']; ?>"><?php echo $value['CurencyName']; ?></option>
                                            <?php } ?>
                                        </select> 
                                        <span class="help-block"></span>      
                                        <input type="hidden" name="CurrencyNamep" class="CurrencyNamep form-control" value="" readonly >
											   <span class="help-block"></span>   
                                    </div>
                                    <div class="form-group per_icon">
                                       <label><?php echo $this->lang->line('plans')['Capital Cost APR'] ?> <span>*</span></label>
                                    <input type="text" name="CapitalCost" class="CapitalCost form-control"  maxlength = "2"  onkeyup="this.value = this.value.replace(/[^\d]/, '')" required >                 
                                    <span class="help-block"></span>   
									</div>
                                </div>
                           
                                <div class="form-row two-childs">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('plans')['Expect APR Type'] ?></label>
                                        <select name="ExpectAPRRatep" class="ExpectAPRRatep form-control">
                                            <option value=""><?php echo $this->lang->line('plans')['Select Expect APR Rate'] ?></option>
                                            <option value="Expected APR"><?php echo $this->lang->line('plans')['Expect APR'] ?></option>
                                            <option value="Expected Earn based on capital cost APR"><?php echo $this->lang->line('plans')['Expect Earn based on capital cost APR'] ?></option>
                                        </select>
                                        <span class="help-block"></span>                
                                    </div>
                                    <div class="form-group per_icon">
                                        <label><?php echo $this->lang->line('plans')['Expected APR'] ?></label>
                                        <input type="text" name="ExpectAPRPercentp" class="ExpectAPRPercentp form-control"
                                               placeholder="<?php echo $this->lang->line('plans')['Enter Expect APR'] ?>" maxlength = "2"  
                                               onkeyup="this.value = this.value.replace(/[^\d]/, '')"  />
                                       <span class="help-block"></span>                 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div> 
            </div>
            <div class="file-sec-wrap-info">
                <a href="JavaScript:void(0)" class="btn-rectangular-wrap custom-btn-br" data-dismiss="modal"><?php echo $this->lang->line('plans')['CANCEL'] ?></a> &nbsp; 
                <a href="JavaScript:void(0)" onclick="plansave();"
                   class="btn-rectangular" ><?php echo $this->lang->line('plans')['SUBMIT'] ?></a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade finishedpopups" role="dialog">
            <div class="modal-dialog modal-md">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="main_sent text-center">

                            <div class="icon-wrap">
                                <img src="<?php echo base_url();?>assets/images/alert.png" width="60px;">
                            </div>

                            <h4><?php echo $this->lang->line('plans')['Dialog_Title'] ?></h4>

                            <p class="form-note-bottom"><?php echo $this->lang->line('plans')['Dialog_Desc'] ?></p>


                            <a href="JavaScript:void(0)" class="btn-rectangular" onclick="popfinishedclose();" data-dismiss="modal"><?php echo $this->lang->line('plans')['OK'] ?></a>
                        </div>

                    </div></div>  


            </div>

</div>
