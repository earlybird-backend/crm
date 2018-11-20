<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>
<?php  include  $GLOBALS['view_folder'].'customer/__lefter.php'; ?>



<style>
    .tab-pane{
        border-bottom: 1px solid #ddd;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
        margin-bottom: 10px;
    }




    .tab-pane div.panel-body{
        background: white;
        padding-left:50px;
        padding-right:50px;
    }


</style>

        <ul class="nav nav-tabs nav-justified" role="tablist">

            <li class="active" role="presentation" >
                <a href="#basicinfo" class="nav-link" aria-controls="active" role="tab"  data-toggle="tab">基础信息</a>
            </li>
            <li role="presentation">
                <a href="#contact" class="nav-link" aria-controls="inactive" role="tab"  data-toggle="tab">联系信息</a>
            </li>
            <li role="presentation">
                <a href="#extrainfo" class="nav-link" aria-controls="inactive" role="tab"  data-toggle="tab">额外信息</a>
            </li>
        </ul>


    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="basicinfo">
            <div class="panel-body">
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
            <div class="file-sec-wrap-info">
                <a href="JavaScript:void(0)" class="btn-rectangular-wrap custom-btn-br" data-dismiss="modal"><?php echo $this->lang->line('plans')['CANCEL'] ?></a> &nbsp;
                <a href="JavaScript:void(0)" onclick="plansave();"
                   class="btn-rectangular" ><?php echo $this->lang->line('plans')['SUBMIT'] ?></a>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="contact">
            <!-- Modal content-->
            <div class="panel-body">

                    <section class="popup-input" id="section_pop2">
                        <div class="popup-input-sec">

                            <div class="table-wrap-sec full">
                                <h4>企业负责人</h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label><span>*</span> 姓</label>
                                        <input  class="form-control" type="text" name="division"  value="">
                                    </div>
                                    <div class="form-group">
                                        <label><span>*</span> 名</label>
                                        <input  class="form-control" type="text" name="division"  value="">
                                    </div>
                                    <div class="form-group">
                                        <label><span>*</span>职务 </label>
                                        <input type="text" name="info" class="form-control"  required >
                                    </div>
                                    <div class="form-group">
                                        <label><span>*</span>主要电话</label>
                                        <input type="text" name="info" class="form-control" >
                                    </div>
                                    <div class="form-group">
                                        <label><span>*</span>邮箱</label>
                                        <input type="text" name="info" class="form-control" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
            </div>
            <div class="file-sec-wrap-info">
                <a href="JavaScript:void(0)" class="btn-rectangular-wrap custom-btn-br" data-dismiss="modal"><?php echo $this->lang->line('plans')['CANCEL'] ?></a> &nbsp;
                <a href="JavaScript:void(0)" onclick="plansave();"
                   class="btn-rectangular" ><?php echo $this->lang->line('plans')['SUBMIT'] ?></a>
            </div>
        </div>

        <div role="tabpanel" class="tab-pane" id="extrainfo">
                <div class="panel-body">
                    <section class="popup-input" id="section_pop2">
                        <div class="popup-input-sec">
                            <div class="form-group">
                                <label><span>*</span>年销售额</label>
                                <select name="status" class="form-control">
                                    <option value="level1" selected>1000万美元以内</option>
                                    <option value="level2">1000万~5000万美元</option>
                                    <option value="level3">5000万~1亿美元</option>
                                    <option value="level4">1亿~10亿美元</option>
                                    <option value="level5">10亿美元以上</option>
                                </select>
                                <label>补充</label>
                                <input type="text" name="info" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label><span>*</span>年采购额</label>
                                <select name="status" class="form-control">
                                    <option value="level1" selected>500万美元以内</option>
                                    <option value="level2">500万~2000万美元</option>
                                    <option value="level3">2000万~5000万美元</option>
                                    <option value="level4">5000万~1亿美元</option>
                                    <option value="level5">1亿美元以上</option>
                                </select>
                                <label>补充</label>
                                <input type="text" name="info" class="form-control" >
                            </div>

                            <div class="form-group">
                                <label><span>*</span>现金流情况</label>
                                <select name="status" class="form-control">
                                    <option value="level1" selected>500万美元以内</option>
                                    <option value="level2">500万~2000万美元</option>
                                    <option value="level3">2000万~5000万美元</option>
                                    <option value="level4">5000万~1亿美元</option>
                                    <option value="level5">1亿美元以上</option>
                                </select>
                                <label>补充</label>
                                <input type="text" name="info" class="form-control" >
                            </div>
                        </section>
                    </div>
                    <div class="file-sec-wrap-info">
                        <a href="JavaScript:void(0)" class="btn-rectangular-wrap custom-btn-br" data-dismiss="modal"><?php echo $this->lang->line('plans')['CANCEL'] ?></a> &nbsp;
                        <a href="JavaScript:void(0)" onclick="plansave();"
                           class="btn-rectangular" ><?php echo $this->lang->line('plans')['SUBMIT'] ?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>