

<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>


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
	.company{
		padding: 15px;
		margin-top: 20px;
	}
	.company-title{
		display: flex;
		align-items: baseline;
		justify-content: flex-start;
	}
	.company-title button{
		margin-left: 15px;
	}
	.company-body{
		margin-top: 20px;
	}
	.market-info{
		padding: 15px;
		margin-top: 20px;
	}

	.other-set{
		padding: 15px;
		margin-top: 20px;
		display: flex;
		align-items: baseline;
		justify-content: flex-start;
	}
	.other-set button{
		margin-left: 15px;
	}
	td{
		text-align: center;
	}
	.form-footer{
		padding: 15px;
		margin-top: 20px;
	}

	.main-wtd .row{
		margin-bottom: 15px;
	}

	.contact-box th{
		text-align: center;
	}

	.contact-box .h4{
		font-weight: bold;
		font-size: 16px;
		display: inline-block;
		margin-right: 20px;
	}

	.glyphicon-plus,.glyphicon-minus,.glyphicon-pencil{
		cursor: pointer;
	}

	.add-market{
		margin-left: 15px;
	}

	.warp{
		background: rgba(105,105,105,0.3);
		top: 0;
		width: 100%;
		position: fixed;
		left: 0;
		height: 100%;
		text-align: center;
		display:none;
		z-index: 5000;
	}
</style>

<!-- Modal content-->
<div class="modal-content">
	<div class="modal-body">
		<section class="popup-input" id="section_pop2">
			<div class="popup-input-sec">

				<div class="table-wrap-sec full">

					<div class="form-inline">
						<div class="form-group" style="width: 45%">
							<label class="col-sm-4">买家编号</label>
							<input  class="form-control" type="text" name="division" readonly value="B00011">

						</div>
						<div class="form-group" style="width: 45%">
							<label class="col-sm-4">加入时间</label>
							<input  class="form-control" type="date" name="joindate">
						</div>
					</div>
					<div class="company">
						<div class="company-title">
							<label>1.公司信息</label>
							<button class="btn btn-default  company-add">修改</button>
						</div>
						<div class="company-body">
							<?php echo $company['CompanyName'] ?>
						</div>
					</div>
					<div class="market-info">
						<label>2.市场信息</label><span data-flag="add-market" class="glyphicon glyphicon-plus add-market"></span>
						<table id="market_table" class="display" cellspacing="0">
							<thead>
							<tr>
								<th style="text-align: center;"><span>序号</span></th>
								<th style="text-align: center;width:140px;"><span>状态</span></th>
								<th style="text-align: center;width:140px;"><span>子公司</span></th>
								<th style="text-align: center;"><span>币别</span></th>
								<th style="text-align: center;"><span>发票更新</span></th>
								<th style="text-align: center;"><span>供应商更新</span></th>
								<th style="text-align: center;"><span>清算负责人</span></th>
								<th style="text-align: center;"><span>&nbsp;</span></th>
							</tr>
							</thead>
							<tbody>
								<?php foreach($market as $index=>$item): ?>
									<tr id="<?php echo $item['Id'] ?>" data-sub-name="<?php echo $item['CompanyDivision'] ?>"
											data-sub-currency="<?php echo $item['CurrencySign'] ?>" data-sub-currency-name="<?php echo $item['CurrencyName'] ?>"
											data-sub-update-type="<?php echo $item['SyncInvoiceType'] ?>"
											data-sub-update-date="<?php echo $item['SyncInvoiceDate'] ?>"
											data-sub-cust-update-type="<?php echo $item['SyncVendorType'] ?>"
											data-sub-cust-update-date="<?php echo $item['SyncVendorDate'] ?>"
											data-sub-reconciliation-date="<?php echo $item['ReconciliationDate'] ?>"
											data-sub-comment="<?php echo $item['Memo'] ?>" data-sub-manager-id="<?php echo $item['UserId'] ?>">
											<td><?php echo $index+1 ?></td>
											<td><?php echo ($item['MarketStatus']=='0')?'活跃':'非活跃' ?></td>
											<td><?php echo $item['CompanyDivision'] ?></td>
											<td><?php echo $item['CurrencyName'] ?></td>
											<td><?php echo ($item['SyncInvoiceType']==0)?'手动更新':'自动更新' ?></td>
											<td><?php echo ($item['SyncVendorType']==0)?'手动更新':'自动更新' ?></td>
											<td><?php echo $item['UserName'] ?></td>
											<td><span class="glyphicon glyphicon-minus remove-market" style="display: none;"></span> <span data-flag="edit-market" class="glyphicon glyphicon-pencil edit-market"></span></td>
										</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
					<div class="other-set">
						<label>3.其它设置</label>
						<button class="btn btn-default other-add">增加</button>
					</div>
					<div class="form-footer">
						<button class="btn btn-primary buyer-add">确定</button>
						<button class="btn btn-default">取消</button>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>


<!-- 联系信息添加弹窗 -->
<div class="modal fade" style="z-index: 2050" id="contact-info" role="dialog">
	<div class="modal-dialog modal-md" style="width: 600px;">
		<div class="modal-content">
			<form action="#" id="contact-info-form" class="form-horizontal" >
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="list-form">联系信息</div>

					<section class="popup-input" id="section_pop2">
						<div class="slimScrollDiv" style="position: relative; overflow: auto; width: auto; height: 250px;">
							<div class="table-wrap-sec main-wtd">
								<div class="row">
									<div class="col-sm-2">
										<label>名</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" required name="contact_lastname" />
									</div>
									<div class="col-sm-2">
										<label>姓</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" required name="contact_firstname" />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label>职位</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" required name="contact_title" />
									</div>
									<div class="col-sm-2">
										<label>电话</label>
									</div>
									<div class="col-sm-4">
										<input type="tel" class="form-control" required name="contact_phone" />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label>邮箱</label>
									</div>
									<div class="col-sm-4">
										<input type="email" class="form-control" required name="contact_email" />
									</div>
								</div>
							</div>

						</div>
					</section>
				</div>
				<div class="file-sec-wrap-info">
                                        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
					<button type="submit" class="btn-rectangular contact-info-add">确定</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- 公司信息添加弹框 -->
<div class="modal fade" tabindex="-1" id="company_add" role="dialog">
	<div class="modal-dialog modal-md" style="width: 800px;">
		<div class="modal-content">
			<form action="#" id="addform" data-company-id="<?php echo $company['Id'] ?>" data-ext-id="<?php echo $ext_value['Id'] ?>" class="form-horizontal" >
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="list-form">公司信息</div>
					<section class="popup-input" id="section_pop2">
						<div class="slimScrollDiv" style="position: relative; overflow: auto; width: auto; height: 550px;">
							<div class="table-wrap-sec main-wtd">
								<h3>1.基础信息</h3>
								<div class="row">
									<div class="col-sm-2">
										<label>公司名称</label>
									</div>
									<div class="col-sm-4">
										<input type="text" name="c-name" required class="form-control" value="<?php echo $company['CompanyName'] ?>" />
									</div>
									<div class="col-sm-2">
										<label>网站</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" name="c-site" required value="<?php echo $company['CompanyWebsite'] ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label>行业</label>
									</div>
									<div class="col-sm-4">
										<select class="form-control" name="c-trade" required>
                        <?php foreach($industry as $item): ?>
													<option value="<?php echo $item['id'] ?>" <?php echo ($item['id']==$company['IndustryId'])?'selected':'' ?>><?php echo $item['name'] ?></option>
                        <?php endforeach; ?>
										</select>
									</div>
									<div class="col-sm-2">
										<label>国家</label>
									</div>
									<div class="col-sm-4">
										<select class="form-control" name="c-country" required>
                        <?php foreach($country as $item): ?>
													<option value="<?php echo $item['id'] ?>" <?php echo ($item['id']==$company['CountryId'])?'selected':'' ?>><?php echo $item['name'] ?></option>
                        <?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label>总机</label>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" name="c-phone" value="<?php echo $company['ContactPhone'] ?>" required  />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label>地址</label>
									</div>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="c-address" required value="<?php echo $company['CompanyAddress'] ?>"  />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label>公司类型</label>
									</div>
									<div class="col-sm-4">
										<select class="form-control" name="c-type" required >
                        <?php foreach($type as $item): ?>
													<option value="<?php echo $item['Id'] ?>" <?php echo ($item['id']==$company['TypeId'])?'selected':'' ?>><?php echo $item['name'] ?></option>
                        <?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label>财年截至时间</label>
									</div>
									<div class="col-sm-4">
										<input type="number" class="form-control" name="c-endtime" min="1" max="12" step="1" required value="<?php echo $company['FiscalMonth'] ?>"  />
									</div>
								</div>

							</div>
							<div class="table-wrap-sec main-wtd">
								<h3>2.联系信息</h3>
								<div class="contact-box box1">
									<div class="h4">2.1企业负责人</div><span class="glyphicon glyphicon-plus add-contact" data-flag="add-box1"></span>
									<table class="table">
										<thead>
										<tr>
											<th>名</th>
											<th>姓</th>
											<th>职位</th>
											<th>电话</th>
											<th>邮箱</th>
											<th>&nbsp;</th>
										</tr>
										</thead>
										<tbody>
											<?php foreach ($contact['box1'] as $item): ?>
													<tr id='<?php echo $item['Uid'] ?>' data-lastname='<?php echo $item['LastName'] ?>' data-firstname='<?php echo $item['FirstName'] ?>'
															data-phone='<?php echo $item['UserContact'] ?>' data-title='<?php echo $item['UserPosition'] ?>'
															data-email='<?php echo $item['UserEmail'] ?>'>
														<td><?php echo $item['LastName'] ?></td>
														<td><?php echo $item['FirstName'] ?></td>
														<td><?php echo $item['UserPosition'] ?></td>
														<td><?php echo $item['UserContact'] ?></td>
														<td><?php echo $item['UserEmail'] ?></td>
														<td><span class='glyphicon glyphicon-minus remove-contact'></span> <span data-flag='edit-box1' class='glyphicon glyphicon-pencil edit-contact'></span></td>
													</tr>
											<?php endforeach ?>
										</tbody>
									</table>
								</div>
								<div class="contact-box box2">
									<div class="h4">2.2CFO或财务负责人</div><span class="glyphicon glyphicon-plus add-contact" data-flag="add-box2"></span>
									<table class="table">
										<thead>
										<tr>
											<th>名</th>
											<th>姓</th>
											<th>职位</th>
											<th>电话</th>
											<th>邮箱</th>
											<th>&nbsp;</th>
										</tr>
										</thead>
										<tbody>
                    <?php foreach ($contact['box2'] as $item): ?>
											<tr id='<?php echo $item['Uid'] ?>' data-lastname='<?php echo $item['LastName'] ?>' data-firstname='<?php echo $item['FirstName'] ?>'
													data-phone='<?php echo $item['UserContact'] ?>' data-title='<?php echo $item['UserPosition'] ?>'
													data-email='<?php echo $item['UserEmail'] ?>'>
												<td><?php echo $item['LastName'] ?></td>
												<td><?php echo $item['FirstName'] ?></td>
												<td><?php echo $item['UserPosition'] ?></td>
												<td><?php echo $item['UserContact'] ?></td>
												<td><?php echo $item['UserEmail'] ?></td>
												<td><span class='glyphicon glyphicon-minus remove-contact'></span> <span data-flag='edit-box2' class='glyphicon glyphicon-pencil edit-contact'></span></td>
											</tr>
                    <?php endforeach ?>
										</tbody>
									</table>
								</div>
								<div class="contact-box box3">
									<div class="h4">2.3 早付清算负责人</div><span class="glyphicon glyphicon-plus add-contact" data-flag="add-box3"></span>
									<table class="table">
										<thead>
										<tr>
											<th>名</th>
											<th>姓</th>
											<th>职位</th>
											<th>电话</th>
											<th>邮箱</th>
											<th>&nbsp;</th>
										</tr>
										</thead>
										<tbody>
                    <?php foreach ($contact['box3'] as $item): ?>
											<tr id='<?php echo $item['Uid'] ?>' data-lastname='<?php echo $item['LastName'] ?>' data-firstname='<?php echo $item['FirstName'] ?>'
													data-phone='<?php echo $item['UserContact'] ?>' data-title='<?php echo $item['UserPosition'] ?>'
													data-email='<?php echo $item['UserEmail'] ?>'>
												<td><?php echo $item['LastName'] ?></td>
												<td><?php echo $item['FirstName'] ?></td>
												<td><?php echo $item['UserPosition'] ?></td>
												<td><?php echo $item['UserContact'] ?></td>
												<td><?php echo $item['UserEmail'] ?></td>
												<td><span class='glyphicon glyphicon-minus remove-contact'></span> <span data-flag='edit-box3' class='glyphicon glyphicon-pencil edit-contact'></span></td>
											</tr>
                    <?php endforeach ?>
										</tbody>
									</table>
								</div>

							</div>

							<div class="table-wrap-sec main-wtd">
								<h3>3.额外信息（业务员补充）</h3>
								<div class="row">
									<div class="col-sm-2">
										<label>年销售额</label>
									</div>
									<div class="col-sm-3">
										<select class="form-control" name="scope1">
                        <?php foreach($ext as $item): ?>
													<option value="<?php echo $item['Id'] ?>" <?php echo ($item['Id']==$ext_value['SaleVolumeId'])?'selected':'' ?>><?php echo $item['name'] ?></option>
                        <?php endforeach ?>
										</select>
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" name="other1" required value="<?php echo $ext_value['SaleVolume'] ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label>年采购额</label>
									</div>
									<div class="col-sm-3">
										<select class="form-control" name="scope2">
                        <?php foreach($ext as $item): ?>
													<option value="<?php echo $item['Id'] ?>" <?php echo ($item['Id']==$ext_value['PurhchaseVolumeId'])?'selected':'' ?>><?php echo $item['name'] ?></option>
                        <?php endforeach ?>
										</select>
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" name="other2" required value="<?php echo $ext_value['PurhchaseVolume'] ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label>现金流情况</label>
									</div>
									<div class="col-sm-3">
										<select class="form-control" name="scope3">
                        <?php foreach($ext as $item): ?>
													<option value="<?php echo $item['Id'] ?>" <?php echo ($item['Id']==$ext_value['CashflowVolumeId'])?'selected':'' ?>><?php echo $item['name'] ?></option>
                        <?php endforeach ?>
										</select>
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" name="other3" required value="<?php echo $ext_value['CashflowVolume'] ?>" />
									</div>
								</div>


							</div>

						</div>
					</section>
				</div>
				<div class="file-sec-wrap-info">
                                        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
					<button type="submit" class="btn-rectangular">确定</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- 市场信息添加弹框 -->
<div class="modal fade" tabindex="-1" id="market_info" role="dialog">
	<div class="modal-dialog modal-md" style="width: 800px;">
		<div class="modal-content">
			<form action="#" id="market_form" class="form-horizontal" >
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="list-form market-title">新增市场</div>
					<section class="popup-input" id="section_pop2">
						<div class="slimScrollDiv" style="position: relative; overflow: auto; width: auto; height: 550px;">
							<div class="table-wrap-sec main-wtd">
								<div class="row close-market-row">
									<div class="col-sm-3"></div>
									<div class="col-sm-6">
										<button class="btn btn-success close-market">停用市场</button>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>子公司</label>
									</div>
									<div class="col-sm-6">
										<input type="text" name="sub-name" required class="form-control" />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>币别</label>
									</div>
									<div class="col-sm-6">
										<select class="form-control" name="sub-currency">
                        <?php foreach($cur as $item): ?>
													<option value="<?php echo $item['sign'] ?>"><?php echo $item['name'] ?></option>
                        <?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>发票更新方式</label>
									</div>
									<div class="col-sm-6">
										<select class="form-control" name="sub-update-type">
											<option value="0">手动更新</option>
											<option value="1">自动更新</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>发票更新时间</label>
									</div>
									<div class="col-sm-4">
										<div style="display: inline-block">每月</div><input type="number" min="1" max="31" step="1" class="form-control" style="display: inline-block;width: 50%;margin: auto 5px;" name="sub-update-date" required  /><div style="display: inline-block"></div>号
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>发票对账日</label>
									</div>
									<div class="col-sm-4">
										<div style="display: inline-block">每月</div><input type="number" min="1" max="31" step="1" class="form-control" style="display: inline-block;width: 50%;margin: auto 5px;" name="sub-reconciliation-date" required  /><div style="display: inline-block"></div>号
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>供应商更新方式</label>
									</div>
									<div class="col-sm-6">
										<select class="form-control" name="sub-cust-update-type">
											<option value="0">手动更新</option>
											<option value="1">自动更新</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>供应商更新时间</label>
									</div>
									<div class="col-sm-4">
										<div style="display: inline-block">每月</div><input min="1" max="31" step="1" type="number" style="display: inline-block;width: 50%;margin: auto 5px;" class="form-control" name="sub-cust-update-date" required  /><div style="display: inline-block">号</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>备注</label>
									</div>
									<div class="col-sm-6">
										<textarea name="sub-comment" class="form-control"></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>市场清算负责人</label>
									</div>
									<div class="col-sm-6">
										<select class="form-control" id="sub-manager" name="sub-manager">
											<?php foreach($charge as $item): ?>
													<option value="<?php echo $item['Uid'] ?>"><?php echo $item['UserName'] ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>


							</div>
						</div>
					</section>
				</div>
				<div class="file-sec-wrap-info">
                                        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
					<button type="submit" class="btn-rectangular">确定</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- 其它设置弹框 -->
<div class="modal fade" tabindex="-1" id="other_info" role="dialog">
	<div class="modal-dialog modal-md" style="width: 600px;">
		<div class="modal-content">
			<form action="#" id="other_form" class="form-horizontal" >
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="list-form"></div>
					<section class="popup-input" id="section_pop2">
						<div class="slimScrollDiv" style="position: relative; overflow: auto; width: auto; height: 550px;">
							<div class="table-wrap-sec main-wtd">
								<div class="row">
									<div class="col-sm-2">
										<label>LOGO</label>
									</div>
									<div class="col-sm-3">
										<input type="file" id="other-logo" name="logo" required  />
									</div>
								</div>
								<div class="row img-box">
									<div class="col-sm-7">
										<img id="logo-pre" class="img-responsive" style="max-width: 400px;max-height: 300px;" src="" />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<h3>邮箱</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>账号</label>
									</div>
									<div class="col-sm-6">
										<input type="email" class="form-control" name="email-username" required />
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<label>密码</label>
									</div>
									<div class="col-sm-6">
										<input type="password" class="form-control" name="email-password" required />
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="file-sec-wrap-info">
                                        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
					<button type="submit" class="btn-rectangular">确定</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="warp">
	<img src="/assets/images/logo_pre.gif">
</div>
<script>
    var table = $('#market_table').DataTable({
        bStateSave:true,
        bFiltered:false,
        info:true,
        ordering:false,
        searching:false,
        bLengthChange: true,
        paging:true,
    });

    var buyer_info = {};
    var company_info = {}; //公司信息
    var contact_info = {
        box1:[],
        box2:[],
        box3:[]
    }; //联系信息
    var market_info = [];
    var other_info = {};

    /* 初始化联系人信息 */
    init();

    $('.company-add').click(function(){
        $('#company_add').modal({
            keyboard:false
        });
    });

    //新增联系信息
    $('.add-contact').click(function(){
        $('#contact-info').modal({
            keyboard:false
        });
        $('#contact-info').data('flag',$(this).data('flag'));
        $('#contact-info-form')[0].reset();
    });

    $('body').on('click','.remove-contact',function(){
        var h5id = $(this).closest('tr').attr('id');
        if($(this).closest('div').hasClass('box1')){
            for(var i=0;i<contact_info.box1.length;i++){
                if(contact_info.box1[i].h5id == h5id){
                    if(contact_info.box1[i].dbid!=undefined){
                        contact_info.box1[i].status = -1;
                    }else{
                        contact_info.box1.splice(i,1);
                    }
                }
            }
        }

        if($(this).closest('div').hasClass('box2')){
            for(var i=0;i<contact_info.box2.length;i++){
                if(contact_info.box2[i].h5id == h5id){
                    if(contact_info.box2[i].dbid!=undefined){
                        contact_info.box2[i].status = -1;
                    }else{
                        contact_info.box2.splice(i,1);
                    }
                }
            }
        }

        if($(this).closest('div').hasClass('box3')){
            for(var i=0;i<contact_info.box3.length;i++){
                if(contact_info.box3[i].h5id == h5id){
                    if(contact_info.box3[i].dbid!=undefined){
                        contact_info.box3[i].status = -1;
                    }else{
                        contact_info.box3.splice(i,1);
                    }
                }
            }
        }
        $(this).closest('tr').remove();
    });

    $('body').on('click','.edit-contact',function(){
        var tr = $(this).closest('tr');
        $('#contact-info input[name=contact_lastname]').val(tr.data('lastname'));
        $('#contact-info input[name=contact_firstname]').val(tr.data('firstname'));
        $('#contact-info input[name=contact_title]').val(tr.data('title'));
        $('#contact-info input[name=contact_phone]').val(tr.data('phone'));
        $('#contact-info input[name=contact_email]').val(tr.data('email'));
        $('#contact-info').modal('show');
        $('#contact-info').data('flag',$(this).data('flag'));
        $('#contact-info').data('trid',$(this).closest('tr').attr('id'));
    });

    //添加联系人弹窗处理
    $('#contact-info-form').submit(function(){
        var flag = $('#contact-info').data('flag');
        var lastname = $('#contact-info input[name=contact_lastname]').val();
        var firstname = $('#contact-info input[name=contact_firstname]').val();
        var title = $('#contact-info input[name=contact_title]').val();
        var phone = $('#contact-info input[name=contact_phone]').val();
        var email = $('#contact-info input[name=contact_email]').val();
        var table_class = flag.split('-');
        table_class = table_class[1];
        if(flag.indexOf('add')!=-1){
            var h5id = Date.parse(new Date());
            var tr_html = "<tr id='"+h5id+"' data-lastname='"+lastname+"' data-firstname='"+firstname+"' data-phone='"+phone+"' data-title='"+title+"' data-email='"+email+"'><td>"+lastname
                +"</td><td>"+firstname
                +"</td><td>"+title
                +"</td><td>"+phone
                +"</td><td>"+email
                +"</td><td><span class='glyphicon glyphicon-minus remove-contact'></span> <span data-flag='edit-"+table_class+"' class='glyphicon glyphicon-pencil edit-contact'></span></td></tr>";

            $('.'+table_class+' table tbody').append($(tr_html));
            var tmp = {
                h5id:h5id,
                firstname:firstname,
                lastname:lastname,
                title:title,
                phone:phone,
                email:email,
                status:1
            }
            switch (table_class){
                case 'box1':
                    contact_info.box1.push(tmp);
                    break;
                case 'box2':
                    contact_info.box2.push(tmp);
                    break;
                case 'box3':
                    contact_info.box3.push(tmp);
                    break;
            }
        }else{
            var trid = $('#contact-info').data('trid');
            $('#'+trid).html("");

            var td_html = "<td>"+lastname
                +"</td><td>"+firstname
                +"</td><td>"+title
                +"</td><td>"+phone
                +"</td><td>"+email
                +"</td><td><span class='glyphicon glyphicon-minus remove-contact'></span> <span data-flag='edit-"+table_class+"' class='glyphicon glyphicon-pencil edit-contact'></span></td>";
            $('#'+trid).data('lastname',lastname);
            $('#'+trid).data('firstname',firstname);
            $('#'+trid).data('phone',phone);
            $('#'+trid).data('title',title);
            $('#'+trid).data('email',email);
            $('#'+trid).html(td_html);

            var tmp = {
                h5id:trid,
                firstname:firstname,
                lastname:lastname,
                title:title,
                phone:phone,
                email:email,
                status:1
            }
            switch (table_class){
                case 'box1':
                    for(var i=0;i<contact_info.box1.length;i++){
                        if(contact_info.box1[i].h5id == trid){
                            tmp.dbid=contact_info.box1[i].dbid;
                            contact_info.box1[i] = tmp;
                        }
                    }
                    break;
                case 'box2':
                    for(var i=0;i<contact_info.box2.length;i++){
                        if(contact_info.box2[i].h5id == trid){
                            tmp.dbid=contact_info.box2[i].dbid;
                            contact_info.box2[i] = tmp;
                        }
                    }
                    break;
                case 'box3':
                    for(var i=0;i<contact_info.box3.length;i++){
                        if(contact_info.box3[i].h5id == trid){
                            tmp.dbid=contact_info.box3[i].dbid;
                            contact_info.box3[i] = tmp;
                        }
                    }
                    break;
            }
        }
        $('#contact-info').modal('hide');
        return false;

    });

    //添加公司信息弹框处理
    $('#addform').submit(function(){
        company_info.contact = contact_info;
        company_info.name = $('input[name="c-name"]').val();
        company_info.site = $('input[name="c-site"]').val();
        company_info.trade = $('select[name="c-trade"]').val();
        company_info.country = $('select[name="c-country"]').val();
        company_info.phone = $('input[name="c-phone"]').val();
        company_info.address = $('input[name="c-address"]').val();
        company_info.type = $('select[name="c-type"]').val();
        company_info.endtime = $('input[name="c-endtime"]').val();
        company_info.other1 = $('input[name="other1"]').val();
        company_info.other2 = $('input[name="other2"]').val();
        company_info.other3 = $('input[name="other3"]').val();
        company_info.scope1 = $('select[name="scope1"]').val();
        company_info.scope2 = $('select[name="scope2"]').val();
        company_info.scope3 = $('select[name="scope3"]').val();

        buyer_info.company = company_info;
        if(contact_info.box1.length ==0 || contact_info.box2.length==0 || contact_info.box3.length==0){
            alert('请输入联系人信息');
            return false;
        }

        $('.warp').show();
        $.ajax({
            url: "/customer/customer/savecompany",
            data: {company:JSON.stringify(company_info)},
            type: "POST",
            dataType: "json",
            success: function (result) {
                $('.warp').hide();
                if(result.id){
                    company_info.id = result.id;
                    company_info.ext_id = result.ext_id;
                    contact_info = result.contact;

                    $('.company-body').html(company_info.name);
                    $('.company-add').html("修改");
                    $('#company_add').modal('hide');
                }else{
                    alert('新增失败');
                }
            },
            error:function(error){
                alert(error);
            }
        });
        return false;
    });


    //添加市场信息
    $('.add-market').click(function(){
        var that = $(this);
        if(company_info.id==undefined){
            alert('请先增加公司信息');
            return;
        }
        $('.warp').show();
        $.get('/customer/customer/getCharge/'+company_info.id,function(data){
            $('.warp').hide();
            $('#sub-manager').html('');
            for(var i=0;i<data.length;i++){
                var op = $('<option value="'+data[i].Uid+'">'+data[i].UserName+'</option>');
                $('#sub-manager').append(op);
            }
            $('#market_info').data('flag',that.data('flag'));
            $('#market_form')[0].reset();
            $('#market_info .market-title').html("新增市场");
            $('#market_info .close-market-row').hide();
            $('#market_info').modal({
                keyboard:false
            });
        });

    });

    $('#market_form').submit(function(){
        var sub_name = $('input[name="sub-name"]').val();
        var sub_currency = $('select[name="sub-currency"] option:selected').val();
        var sub_currency_name = $('select[name="sub-currency"] option:selected').html();
        var sub_update_type = $('select[name="sub-update-type"]').val();
        var sub_update_date = $('input[name="sub-update-date"]').val();
        var sub_reconciliation_date = $('input[name="sub-reconciliation-date"]').val();
        var sub_cust_update_type = $('select[name="sub-cust-update-type"]').val();
        var sub_cust_update_date= $('input[name="sub-cust-update-date"]').val();
        var sub_comment= $('textarea[name="sub-comment"]').val();
        var sub_manager_name= $('select[name="sub-manager"] option:selected').html();
        var sub_manager_id= $('select[name="sub-manager"] option:selected').val();
        var flag =  $('#market_info').data('flag');
        var market_data = {
            CompanyId:company_info.id+'',
            CompanyDivision:sub_name,
            CurrencySign:sub_currency,
            CurrencyName:sub_currency_name,
            UserId:sub_manager_id,
            ReconciliationDate:sub_reconciliation_date,
            SyncVendorDate:sub_cust_update_date,
            SyncVendorType:sub_cust_update_type,
            SyncInvoiceDate:sub_update_date,
            SyncInvoiceType:sub_update_type,
            Memo:sub_comment
        };
        var sub_update_type_name = (sub_update_type==0)?"手动更新":"自动更新";
        var sub_cust_update_type_name = (sub_cust_update_type==0)?"手动更新":"自动更新";
        if(flag.indexOf('add')!=-1){
            var h5id = Date.parse(new Date());
            var tr_html = $('<tr id="'+h5id+'" data-sub-name="'+sub_name
                +'" data-sub-currency="'+sub_currency
                +'" data-sub-update-type="'+sub_update_type
                +'" data-sub-update-date="'+sub_update_date
                +'" data-sub-cust-update-type="'+sub_cust_update_type
                +'" data-sub-cust-update-date="'+sub_cust_update_date
                +'" data-sub-reconciliation-date="'+sub_reconciliation_date
                +'" data-sub-comment="'+sub_comment
                +'" data-sub-manager-id="'+sub_manager_id+'"><td>'+($('#market_table tbody tr').length*1+1)+'</td><td>活跃</td><td>'+sub_name
                +'</td><td>'+sub_currency_name
                +'</td><td>'+sub_update_type_name
                +'</td><td>'+sub_cust_update_type_name
                +'</td><td>'+sub_manager_name
                +'</td><td><span class="glyphicon glyphicon-minus remove-market" style="display: none;"></span> <span data-trid="" data-flag="edit-market" class="glyphicon glyphicon-pencil edit-market"></span></td></tr>');
            $('#market_table tbody').append(tr_html);
            market_data.h5id = h5id;
            market_info.push(market_data);

        }else{
            var trid= $('#market_info').data('trid');
            var tds = $('#'+trid+' td');
            var index = $(tds[0]).html();
            var status = $(tds[1]).html();
            $('#'+trid).html("");
            var td_html = '<td>'+index+'</td><td>'+status+'</td><td>'+sub_name
                +'</td><td>'+sub_currency_name
                +'</td><td>'+sub_update_type_name
                +'</td><td>'+sub_cust_update_type_name
                +'</td><td>'+sub_manager_name
                +'</td><td><span class="glyphicon glyphicon-minus remove-market" style="display: none;"></span> <span data-flag="edit-market" class="glyphicon glyphicon-pencil edit-market"></span></td>';
            $('#'+trid).data('sub-name',sub_name);
            $('#'+trid).data('sub-currency',sub_currency);
            $('#'+trid).data('sub-update-type',sub_update_type);
            $('#'+trid).data('sub-update-date',sub_update_date);
            $('#'+trid).data('sub-reconciliation-date',sub_reconciliation_date);
            $('#'+trid).data('sub-cust-update-type',sub_cust_update_type);
            $('#'+trid).data('sub-cust-update-date',sub_cust_update_date);
            $('#'+trid).data('sub-comment',sub_comment);
            $('#'+trid).data('sub-manager-id',sub_manager_id);
            $('#'+trid).html(td_html);
            market_data.h5id = trid;
            for(var i=0;i<market_info.length;i++){
                if(market_info[i].h5id == trid){
                    var Id = market_info[i].Id;
                    market_info[i] = market_data;
                    market_info[i].Id = Id;
                }
            }
        }
        //提交市场信息
        $('.warp').show();
        $.post('/customer/customer/savemarket',{market:JSON.stringify(market_data)},function(data){
            $('.warp').hide();
            for(var i=0;i<market_info.length;i++){
                if(market_info[i].h5id == data.h5id){
                    market_info[i].Id = data.Id;
                }
            }

            $('#market_info').modal('hide');
        });
        return false;
    });

    $('body').on('click','.remove-market',function(){
//		    $(this).closest('tr').remove();
    });
    //编辑市场信息
    $('body').on('click','.edit-market',function(){
        $('#market_info').data('flag',$(this).data('flag'));
        $('#market_info').data('trid',$(this).closest('tr').attr('id'));
        $('#market_form')[0].reset();

        $('#market_info .market-title').html("修改市场");
        $('#market_info .close-market-row').show();
        $('input[name="sub-name"]').val($(this).closest('tr').data('sub-name'));
        $('select[name="sub-currency"]').val($(this).closest('tr').data('sub-currency'));
        $('select[name="sub-update-type"]').val($(this).closest('tr').data('sub-update-type'));
        $('input[name="sub-update-date"]').val($(this).closest('tr').data('sub-update-date'));
        $('input[name="sub-reconciliation-date"]').val($(this).closest('tr').data('sub-reconciliation-date'));
        $('select[name="sub-cust-update-type"]').val($(this).closest('tr').data('sub-cust-update-type'));
        $('input[name="sub-cust-update-date"]').val($(this).closest('tr').data('sub-cust-update-date'));
        $('textarea[name="sub-comment"]').val($(this).closest('tr').data('sub-comment'));
        $('select[name="sub-manager"]').val($(this).closest('tr').data('sub-manager-id'));
        $('#market_info').modal({
            keyboard:false
        });
    });

    //停用市场
    $('body').on('click','.close-market',function(){
        var trid= $('#market_info').data('trid');
        var dbid='';
        for(var i=0;i<market_info.length;i++){
            if(market_info[i].h5id == trid){
                dbid = market_info[i].Id;
            }
        }
        if(dbid!=''){
            $('.warp').show();
            $.get('/customer/customer/closemarket/'+dbid,function(data){
                $('.warp').hide();
                if(data.MarketStatus != undefined){
                    var tds = $('#'+trid+' td');
                    $(tds[1]).html('不活跃');
                    $('#market_info').modal('hide');
                }else{
                    alert('数据处理出错');
                }
            });
        }else{
            alert('市场信息出错!');
        }
    });


    //增加其它设置
    $('.other-add').click(function(){
        $('#other_info').modal({
            keyboard:false
        });
    });

    $('#other_form').submit(function(){
        var email_user = $('input[name="email-username"]').val();
        var email_pass = $('input[name="email-password"]').val();
        other_info.user = email_user;
        other_info.pass = email_pass;
        var form = new FormData();
        var logo = document.getElementById('other-logo').files[0];
        form.append('other_info',JSON.stringify(other_info));
        form.append('logo',logo);
        $('.warp').show();
        $.ajax({
            url: "/customer/customer/saveother",
            data: form,
            type: "POST",
            dataType: "json",
            cache: false,//上传文件无需缓存
            processData: false,//用于对data参数进行序列化处理 这里必须false
            contentType: false, //必须
            success: function (result) {
                $('.warp').hide();
                $('.other-add').html('修改');
                $('#other_info').modal('hide');
            },
        });
        return false;
    });

    $('#other-logo').change(function(){
        var imgUrl = getImageURL(this.files[0]);
        if(imgUrl){
            $('#logo-pre').attr('src',imgUrl);
        }
    });

    $('.buyer-add').click(function(){
        var is_market_null = 0;
        if($('#market_table tr .dataTables_empty').length>0){
            is_market_null = $('#market_table tr .dataTables_empty').length;
        }else{
            if($('#market_table tbody tr').length==0){
                is_market_null = 1;
            }
        }
        if(buyer_info.company==null || buyer_info.other == null || is_market_null!=0){
            alert('请输入相关信息');
        }else{
            window.location.href = '/customer/customer';
        }
    });

    $('.buyer-cancel').click(function(){
        window.location.href = '/customer/customer';
    });


    function init(){
        $('.box1 table tbody tr').each(function(){
            var tmp = {
                h5id:$(this).attr('id'),
                dbid:$(this).attr('id'),
                firstname:$(this).data('firstname'),
                lastname:$(this).data('lastname'),
                title:$(this).data('title'),
                phone:$(this).data('phone'),
                email:$(this).data('email'),
                status:1
            };
            contact_info.box1.push(tmp);
        });

        $('.box2 table tbody tr').each(function(){
            var tmp = {
                h5id:$(this).attr('id'),
                dbid:$(this).attr('id'),
                firstname:$(this).data('firstname'),
                lastname:$(this).data('lastname'),
                title:$(this).data('title'),
                phone:$(this).data('phone'),
                email:$(this).data('email'),
                status:1
            };
            contact_info.box2.push(tmp);
        });

        $('.box3 table tbody tr').each(function(){
            var tmp = {
                h5id:$(this).attr('id'),
                dbid:$(this).attr('id'),
                firstname:$(this).data('firstname'),
                lastname:$(this).data('lastname'),
                title:$(this).data('title'),
                phone:$(this).data('phone'),
                email:$(this).data('email'),
                status:1
            };
            contact_info.box3.push(tmp);
        });

        company_info.id = $('#addform').data('company-id')+"";
        company_info.ext_id = $('#addform').data('ext-id')+"";

        //市场信息
				$('#market_table tbody tr').each(function(){
            var market_data = {
                h5id:$(this).attr('id')+'',
                Id:$(this).attr('id')+'',
                CompanyId:company_info.id,
                CompanyDivision:$(this).data('sub-name'),
                CurrencySign:$(this).data('sub-currency'),
                CurrencyName:$(this).data('sub-currency-name'),
                UserId:$(this).data('sub-manager-id'),
                ReconciliationDate:$(this).data('sub-reconciliation-date'),
                SyncVendorDate:$(this).data('sub-cust-update-date'),
                SyncVendorType:$(this).data('sub-cust-update-type'),
                SyncInvoiceDate:$(this).data('sub-update-date'),
                SyncInvoiceType:$(this).data('sub-update-type'),
                Memo:$(this).data('sub-comment')
            };
            market_info.push(market_data);
				});

    }

    function getImageURL(file) {
        var url = null ;
        // 下面函数执行的效果是一样的，只是需要针对不同的浏览器执行不同的 js 函数而已
        if (window.createObjectURL!=undefined) { // basic
            url = window.createObjectURL(file) ;
        } else if (window.URL!=undefined) { // mozilla(firefox)
            url = window.URL.createObjectURL(file) ;
        } else if (window.webkitURL!=undefined) { // webkit or chrome
            url = window.webkitURL.createObjectURL(file) ;
        }
        return url ;
    }
</script>

<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>

