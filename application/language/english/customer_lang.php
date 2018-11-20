<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//开始翻译 plan 页面

$lang['create_plan'] = array(
    
    //第1步
    'Create Plan' => '发布计划',
    
    'Currency' => '币别',
    'Select Currency' => '选择币种',
    'Minimum APR' => '最低年利率回报 ',
    'Enter Minimum' => '输入最低期望回报',    
    'Expect APR' => '期望年利率回报',
    'Select Expect APR Rate' => '选择期望回报类型',           
    'Expect Earn based on capital cost APR' => '利率成本上的收益比',    
    'Expect APR Percent' => '期望回报百分比',
    'Enter Expect APR' => '输入期望回报',
    'Early Pay Amount' => '早付金额',
    'Enter Early Pay Amount' => '输入早付金额',    
    'Estimate Pay Date' => '预计早付时间',
    
    'Back' => '上一步',
    'Next step' => '下一步',
    'Upload' => '上传',
    'Download' => '下载',
    'Finish' => '完成',
    
    //第2步          
    
    'Upload list' => '上传应付',
    'step_2_1' => '请确定您已经下载应付表格格式并依照格式填入数据',
    'step_2_2' => '请确定一个表格里只包含一种币种的应付数据',
    'step_2_2_tip' => '多个币别请用多个表格设置多个不同的计划',
    'step_2_3' => '请确认您所上传的发票是与供应商一致的发票信息，表格中应当要给出您的原定支付日期',
    'step_2_4' =>'请确认表中不包含您不打算在现阶段支付的发票',
    
    //第3步
    'Finish Upload' => '上传应付', 
    'Upload Payable List' => '上传应付表格',
    'No file Choosen' => '未有文件',
    'Choose file' => '选择文件',
    
    'step_3_tip_1' => '如您还没下载应付表格格式，请先在右边页面点击下载',
    
    'Non-Register Suppliers' => '未注册用户',
    'Registered Suppliers' => '已注册用户',
    'No data yet' => '暂无数据',    
    
    
    'step_3_tip_2' => '如果您需要批量上传供应商联系信息，请点此按钮',
    'Invite Suppliers' => '邀请供应商',
    'Send Invitation' => '发送邀请',
    
    //上传供应商清单弹出Modal
    'LIST UPLOAD' => '表格上传',        
    'Upload Supplier List' => '上传供应商表格',
    'Upload_Supplier_modal_tip' => '如您还没下载供应商表格格式，请先在右边页面点击下载',    
    'Add A supplier' => '添加供应商',

    //弹出提示对话框Modal
    'Dialog_Title' => '需要应付款和供应商清单',
    'Dialog_Desc' => '计划需要供应商来参与投标，请邀请更多的供应商加入',
    
    //新建APR弹出Modal
    
    'Add APR Settings' => '新增 年化率设置',
    'APR_modal_tip' => '您还没有为该货币添加任何设置,请点击添加货币按钮以添加货币设置 ',
    'Capital Cost APR' => '资金年化利率',
    
    
    //按钮
    'CANCEL' => '取消',
    'CONFIRM' => '确定',
    'SUBMIT' => '提交',
    'OK' => '确定',
    
    
    //右侧辅助栏
    'Check List' => '检查清单',
    'Upload Files' => '文件上传', 
    'sidebar_tip_1' => '这是发布计划的第一步，请填写完成计划内容并进入下一步',
    'sidebar_tip_2' => '这是创建新计划的第二步，请接受所有的条款，并进入第三步',
    'sidebar_tip_3' => '这是创建新计划的最后一步，请上传正确的文件格式。如果你没有，请通过下面的链接下载',
    
    'Supplier Format' => '供应商表格式',
    'Payable Format' => '应付表格式',
    
    'download_format_tip' => '可以在此下载供应商表格格式和应付表格格式'
);



$lang['plan_column'] = array(
    'Non-Register Suppliers' => '未注册用户',
    'Registered Suppliers' => '已注册用户',
    'No data yet' => '暂无数据',
    
    
    
    'Vendor Code' => '供应商编号',
    'Suppliers' => '供应商',
    'Supplier ID' => '供应商编号',
    'Amount'    => '金额',
    'Send Invitation' => '邀请',     
    'Total' => '总计',
    
    'NAME' => '供应商名',
    'EMAIL' => '邮箱',
    'CONTACT PERSON' => '联系人',
    'CONTACT PHONE' => '联系电话'
    
);


$lang['plan_status']  = array(
    'pending_release'  => '待发布',
    'ongoing' => '进行中',
    'pending_proposal' => '待报告',
    'pending_confirm_proposal' => '待确认报告',
    'pending_payment' => '待付款',
    'confirm_payment_copy' => '水单确认',
    'finished' => '完成',
    'closed' => '关闭',
    'failed' => '失败'
);

$lang['email_status'] = array(
    'inviate_supplier' => '邀请供应商',
    'send_invitation' => '邀请',
    'already_sent' => '已发',
    'registered' => '已注册'
);



$lang['customer_js'] = array(
    'No file Choosen' => '未有文件',
    'Please Choose First' => '请先选择文件',
    'Upload Payble Format' => '请上传支付清单的xls格式电子表格'
);


