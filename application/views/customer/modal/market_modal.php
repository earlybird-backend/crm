

<style>
    .form-group label{
        display:inline-block;
        width:25%;
    }
    .form-group .form-control{
         display:inline-block;
         width:70%;
     }
    .form-group span{
        padding:10px;
        font-size:15px;
    }
    .form-group span i{
        width:15px;
    }
</style>

<div class="modal fade" id="marketModal" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="main_sent">

                    <section class="popup-input" id="section_pop2">
                       <div class="popup-input-sec">

                    <h4>新增买家市场</h4>


                    <div id="myfields" class="myfieldsc error" style="text-align:center"></div>

                     
                            <div class="table-wrap-sec full">
                                
                                <div class="form-row two-childs">
                                    <div class="form-group">
                                        <label><span>*</span>市场编号 </label>
                                        <input  class="form-control" type="text" name="division" readonly value="B00011">
                                    </div>
                                    <div class="form-group">
                                       <label><span>*</span>选择公司 </label>
                                        <select name="status" class="form-control" style="width:50%">
                                            <option value="" >Cisco</option>
                                        </select>
                                        <span><a href="#" data-toggle="modal" data-target="#companyModal"> <i class="fa fa-plus-square" style="color:#6aa443"></i>新建 </a></span>
									</div>
                                </div>

                                <p>市场信息</p>
                                <div class="form-row">

                                    <div class="form-group">
                                        <label><span>*</span>公司名称 </label>
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




<div class="modal fade" id="companyModal" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="main_sent">

                    <section class="popup-input" id="section_pop2">
                        <div class="popup-input-sec">

                            <h4>新增集团公司</h4>

                            <div class="table-wrap-sec full">

                                <div class="form-row">
                                    <div class="form-group">
                                        <label><span>*</span>公司编号 </label>
                                        <input  class="form-control" type="text" name="division" readonly value="C00011">
                                    </div>
                                    <div class="form-group">
                                        <label><span>*</span>公司名称 </label>
                                        <input type="text" name="info" class="form-control"  required >
                                    </div>

                                    <div class="form-group">
                                        <label><span></span>网站 </label>
                                        <input type="text" name="info" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label>行业</label>
                                        <select name="status" class="form-control">
                                            <option value="eco" selected>电子商务平台</option>
                                            <option value="clasical">传统消费</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>国家</label>
                                        <select name="current" class="form-control">
                                            <?php foreach ($country as $key => $value) { ?>
                                                <option value="<?php echo $value['CurrencyId']; ?>"><?php echo $value['CurencyName']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><span>*</span>总机</label>
                                        <input type="text" name="info" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label><span>*</span>地址</label>
                                        <input type="text" name="info" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label>公司类型</label>
                                        <select name="status" class="form-control">
                                            <option value="jituan" selected>集团公司</option>
                                            <option value="zhushi">独立经营</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>财年截止时间</label>
                                        <input class="form-control" type="number" name="NextPaydate">
                                    </div>
                                    <div class="form-group">
                                        <label style="display:block;">备 注</label>
                                        <textarea rows="5"  name="ExpectAPRPercentp" class="form-control"  style="display:block;width:100%;" ></textarea>
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

