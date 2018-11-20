
<div class="modal fade" id="companyModal" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="main_sent">

                    <section class="popup-input" id="section_pop2">
                       <div class="popup-input-sec">

                    <h4>新增买家</h4>


                    <div id="myfields" class="myfieldsc error" style="text-align:center"></div>

                     
                            <div class="table-wrap-sec full">
                                
                                <div class="form-row two-childs">
                                    <div class="form-group">
                                        <label><span>*</span>买家编号 </label>
                                        <input  class="form-control" type="text" name="division" readonly value="B00011">
                                    </div>
                                    <div class="form-group">
                                       <label><span>*</span>公司信息 </label>
                                        <select name="status" class="form-control" style="width:50%">
                                            <option value="" >Cisco</option>
                                        </select>
                                        <span><i class="fa fa-plus-square" style="color:#6aa443"></i>新建 </span>
									</div>
                                </div>

                                <p>市场信息</p>
                                <div class="form-row">

                                    <div class="form-group">
                                        <label><span>*</span>子公司 </label>
                                        <input type="text" name="info" class="form-control"  maxlength = "2"   required >
                                    </div>
                                    <div class="form-group">
                                        <label>状态</label>
                                        <select name="status" class="form-control">
                                            <option value="valid" selected>有效</option>
                                            <option value="invalid">无效</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>币别</label>
                                        <select name="current" class="form-control">
                                            <?php foreach ($currency as $key => $value) { ?>
                                                <option value="<?php echo $value['CurrencyId']; ?>"><?php echo $value['CurencyName']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>发票更新方式</label>
                                        <select name="status" class="form-control">
                                            <option value="manual" selected>手动更新</option>
                                            <option value="auto">自动更新</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>发票更新日期</label>
                                        每月 <input class="form-control" type="number" name="NextPaydate" value="1" min="1" max="31" style="width:60px;"> 号
                                    </div>
                                    <div class="form-group">
                                        <label>供应商更新方式</label>
                                        <select name="status" class="form-control">
                                            <option value="manual" selected>手动更新</option>
                                            <option value="auto">自动更新</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>供应商更新日期</label>
                                        每月 <input class="form-control" type="number" name="NextPaydate" value="1" min="1" max="31" style="width:60px;"> 号
                                    </div>
                                    <div class="form-group">
                                        <label style="display:block;">备 注</label>
                                        <textarea rows="5"  name="ExpectAPRPercentp" class="form-control"  style="display:block;width:100%;" ></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>市场清算负责人<span>*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="" >请选择负责人</option>
                                            <option value="auto">Tick</option>
                                        </select>
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
