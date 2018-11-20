<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Author: Daniel Davis
*         @ourmaninjapan
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.09.2013
*
* Description:  English language file for Ion Auth example views
*
*/

// Errors
$lang['error_csrf'] = 'This form post did not pass our security checks.';

// Login
$lang['login_heading']         = 'Login';
$lang['login_subheading']      = 'Please login with your email/username and password below.';
$lang['login_identity_label']  = 'Email/Username:';
$lang['login_password_label']  = 'Password:';
$lang['login_remember_label']  = 'Remember Me:';
$lang['login_submit_btn']      = 'Login';
$lang['login_forgot_password'] = 'Forgot your password?';

$lang['home'] = '首页';
$lang['homepage_welcome'] = '欢迎来到我们的网站。我们肯定希望你会喜欢你会发现在这里。';
$lang['hello'] = '您好';
$lang['Register'] = '注册';
$lang['Login'] = '登录';

$lang['plan_tip'] = 'EP平台早付计划日结于每个周一至周五，10：30am 开始， 5：00pm 结束竞价， 6：00pm 获得早付结果';


// Index
$lang['index_heading']           = 'Users';
$lang['index_subheading']        = 'Below is a list of the users.';
$lang['index_fname_th']          = 'First Name';
$lang['index_lname_th']          = 'Last Name';
$lang['index_email_th']          = 'Email';
$lang['index_groups_th']         = 'Groups';
$lang['index_status_th']         = 'Status';
$lang['index_action_th']         = 'Action';
$lang['index_active_link']       = 'Active';
$lang['index_inactive_link']     = 'Inactive';
$lang['index_create_user_link']  = 'Create a new user';
$lang['index_create_group_link'] = 'Create a new group';



// Deactivate User
$lang['deactivate_heading']                  = 'Deactivate User';
$lang['deactivate_subheading']               = 'Are you sure you want to deactivate the user \'%s\'';
$lang['deactivate_confirm_y_label']          = 'Yes:';
$lang['deactivate_confirm_n_label']          = 'No:';
$lang['deactivate_submit_btn']               = 'Submit';
$lang['deactivate_validation_confirm_label'] = 'confirmation';
$lang['deactivate_validation_user_id_label'] = 'user ID';

// Create User
$lang['create_user_heading']                           = 'Create User';
$lang['create_user_subheading']                        = 'Please enter the user\'s information below.';
$lang['create_user_fname_label']                       = 'First Name:';
$lang['create_user_lname_label']                       = 'Last Name:';
$lang['create_user_company_label']                     = 'Company Name:';
$lang['create_user_email_label']                       = 'Email:';
$lang['create_user_phone_label']                       = 'Phone:';
$lang['create_user_password_label']                    = 'Password:';
$lang['create_user_password_confirm_label']            = 'Confirm Password:';
$lang['create_user_submit_btn']                        = 'Create User';
$lang['create_user_validation_fname_label']            = 'First Name';
$lang['create_user_validation_lname_label']            = 'Last Name';
$lang['create_user_validation_email_label']            = 'Email Address';
$lang['create_user_validation_phone1_label']           = 'First Part of Phone';
$lang['create_user_validation_phone2_label']           = 'Second Part of Phone';
$lang['create_user_validation_phone3_label']           = 'Third Part of Phone';
$lang['create_user_validation_company_label']          = 'Company Name';
$lang['create_user_validation_password_label']         = 'Password';
$lang['create_user_validation_password_confirm_label'] = 'Password Confirmation';

// Edit User
$lang['edit_user_heading']                           = 'Edit User';
$lang['edit_user_subheading']                        = 'Please enter the user\'s information below.';
$lang['edit_user_fname_label']                       = 'First Name:';
$lang['edit_user_lname_label']                       = 'Last Name:';
$lang['edit_user_company_label']                     = 'Company Name:';
$lang['edit_user_email_label']                       = 'Email:';
$lang['edit_user_phone_label']                       = 'Phone:';
$lang['edit_user_password_label']                    = 'Password: (if changing password)';
$lang['edit_user_password_confirm_label']            = 'Confirm Password: (if changing password)';
$lang['edit_user_groups_heading']                    = 'Member of groups';
$lang['edit_user_submit_btn']                        = 'Save User';
$lang['edit_user_validation_fname_label']            = 'First Name';
$lang['edit_user_validation_lname_label']            = 'Last Name';
$lang['edit_user_validation_email_label']            = 'Email Address';
$lang['edit_user_validation_phone1_label']           = 'First Part of Phone';
$lang['edit_user_validation_phone2_label']           = 'Second Part of Phone';
$lang['edit_user_validation_phone3_label']           = 'Third Part of Phone';
$lang['edit_user_validation_company_label']          = 'Company Name';
$lang['edit_user_validation_groups_label']           = 'Groups';
$lang['edit_user_validation_password_label']         = 'Password';
$lang['edit_user_validation_password_confirm_label'] = 'Password Confirmation';

// Create Group
$lang['create_group_title']                  = 'Create Group';
$lang['create_group_heading']                = 'Create Group';
$lang['create_group_subheading']             = 'Please enter the group information below.';
$lang['create_group_name_label']             = 'Group Name:';
$lang['create_group_desc_label']             = 'Description:';
$lang['create_group_submit_btn']             = 'Create Group';
$lang['create_group_validation_name_label']  = 'Group Name';
$lang['create_group_validation_desc_label']  = 'Description';

// Edit Group
$lang['edit_group_title']                  = 'Edit Group';
$lang['edit_group_saved']                  = 'Group Saved';
$lang['edit_group_heading']                = 'Edit Group';
$lang['edit_group_subheading']             = 'Please enter the group information below.';
$lang['edit_group_name_label']             = 'Group Name:';
$lang['edit_group_desc_label']             = 'Description:';
$lang['edit_group_submit_btn']             = 'Save Group';
$lang['edit_group_validation_name_label']  = 'Group Name';
$lang['edit_group_validation_desc_label']  = 'Description';

// Change Password
$lang['change_password_heading']                               = 'Change Password';
$lang['change_password_old_password_label']                    = 'Old Password:';
$lang['change_password_new_password_label']                    = 'New Password (at least %s characters long):';
$lang['change_password_new_password_confirm_label']            = 'Confirm New Password:';
$lang['change_password_submit_btn']                            = 'Change';
$lang['change_password_validation_old_password_label']         = 'Old Password';
$lang['change_password_validation_new_password_label']         = 'New Password';
$lang['change_password_validation_new_password_confirm_label'] = 'Confirm New Password';

// Forgot Password
$lang['forgot_password_heading']                 = 'Forgot Password';
$lang['forgot_password_subheading']              = 'Please enter your %s so we can send you an email to reset your password.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'Submit';
$lang['forgot_password_validation_email_label']  = 'Email Address';
$lang['forgot_password_username_identity_label'] = 'Username';
$lang['forgot_password_email_identity_label']    = 'Email';
$lang['forgot_password_email_not_found']         = 'No record of that email address.';

// Reset Password
$lang['reset_password_heading']                               = 'Change Password';
$lang['reset_password_new_password_label']                    = 'New Password (at least %s characters long):';
$lang['reset_password_new_password_confirm_label']            = 'Confirm New Password:';
$lang['reset_password_submit_btn']                            = 'Change';
$lang['reset_password_validation_new_password_label']         = 'New Password';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirm New Password';


$lang['auth_login_h'] = "Login";
$lang['auth_login_error'] = "Please login here first";
$lang['auth_login_m'] = "You are logged in successfully";
$lang['auth_logout_m'] = "You are logged out successfully";
$lang['auth_login_error_m'] = "用户登录信息有错!";
$lang['auth_account_inactive_m'] = "Your account is not activated yet";
$lang['auth_index_login_type_b'] = '买家';
$lang['auth_index_login_type_s'] = '供应商';

$lang['auth_forget_password_t'] = "Forget Password";
$lang['auth_forget_password_h'] = "Forget Password";
$lang['auth_forget_password_error'] = "Sorry ! No email found in database";
$lang['auth_forget_password_success'] = "New password sent to your email address.";

$lang['logout'] = '登出';

// 开始翻译 plan 页面

$lang['plans'] = array(

    // 列表清单
    'View' => '查看',
    'View Report' => '查看报告',
    'View Detail' => '查看明细',
    'No record found' => '没有记录',
    'Status' => '状态',
    // 第1步
    'Create Plan' => '发布计划',

    'Currency' => '币别',
    'Select Currency' => '选择币种',
    'Minimum APR' => '资金成本 ',
    'Enter Minimum' => '输入最低期望回报',
    'Expect APR Type' => '期望年化率类型',
    'Expect APR' => '期望年化率',
    'Expected APR' => '期望年化率',
    'Select Expect APR Rate' => '选择期望回报类型',
    'Expect Earn based on capital cost APR' => '利率成本上的收益比',
    'Expect APR Percent' => '期望回报百分比',
    'Enter Expect APR' => '输入期望回报',
    'Early Pay Amount' => '早付金额',
    'Enter Early Pay Amount' => '输入早付金额',
    'Estimate Pay Date' => '预计早付时间',
    'APR Settings' => '年化率设置',
    'Change APR Settings' => '修改年化率设置',
    'Capital Cost' => '资金成本',
    'Back' => '上一步',
    'Next step' => '下一步',
    'Upload' => '上传',
    'Download' => '下载',
    'Finish' => '完成',
    'Update' => '更新',
    'Delete' => '删除',
    
    'Yes'   => '是',
    'No'   => '否',

    // 第2步

    'Upload list' => '上传应付',
    'step_2_1' => '请确定您已经下载应付表格格式并依照格式填入数据',
    'step_2_2' => '请确定一个表格里只包含一种币种的应付数据',
    'step_2_2_tip' => '多个币别请用多个表格设置多个不同的计划',
    'step_2_3' => '请确认您所上传的发票是与供应商一致的发票信息，表格中应当要给出您的原定支付日期',
    'step_2_4' => '请确认表中不包含您不打算在现阶段支付的发票',

    // 第3步
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

    // 上传供应商清单弹出Modal
    'LIST UPLOAD' => '表格上传',
    'Upload Supplier List' => '上传供应商表格',
    'Upload_Supplier_modal_tip' => '如您还没下载供应商表格格式，请先在右边页面点击下载',
    'Add A supplier' => '添加供应商',

    // 弹出提示对话框Modal
    'Dialog_Title' => '需要应付款和供应商清单',
    'Dialog_Desc' => '计划需要供应商来参与投标，请邀请更多的供应商加入',

    // 新建APR弹出Modal

    'Add APR Settings' => '新增 年化率设置',
    'APR_modal_tip' => '您还没有为该货币添加任何设置,请点击添加货币按钮以添加货币设置 ',
    'Capital Cost APR' => '资金成本年利率',
    'APR_Settings_tip' => '如果您想要根据币别添加更多币别的年化率设置，您可以点击这里进行添加。',
    'Add New' => '新增',
    'APR_Settings_delete_tip' => '您确定要删除这个币别年化率设置？',

    
    'Dialog_Delete_Payment_tip' => '你确定要删除这个付款副本吗?',
    'Dialog_Uploaded_Payment_title' => '付款副本上传成功',
    'Dialog_Uploaded_Payment_desc' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.',
    
    // 按钮
    'CANCEL' => '取消',
    'CONFIRM' => '确定',
    'SUBMIT' => '提交',
    'OK' => '确定',
    'UPDATE' => '更新',
    'DELETE' => '删除',
    'EDIT' => '修改',

    // 右侧辅助栏
    'Check List' => '检查清单',
    'Upload Files' => '文件上传',
    'sidebar_tip_1' => '这是发布计划的第一步，请填写完成计划内容并进入下一步',
    'sidebar_tip_2' => '这是创建新计划的第二步，请接受所有的条款，并进入第三步',
    'sidebar_tip_3' => '这是创建新计划的最后一步，请上传正确的文件格式。如果你没有，请通过下面的链接下载',

    'Supplier Format' => '供应商表格式',
    'Payable Format' => '应付表格式',

    'download_format_tip' => '可以在此下载供应商表格格式和应付表格格式',
    
    'Bid Value' => '当前竞价值',
    'Early Pay Amount' => '早付金额'
    
);

$lang['plan_column'] = array(
    'Non-Register Suppliers' => '未注册用户',
    'Registered Suppliers' => '已注册用户',
    'No data yet' => '暂无数据',

    'Vendor Code' => '供应商编号',
    'Suppliers' => '供应商',
    'Supplier ID' => '供应商编号',
    'Amount' => '金额',
    'Send Invitation' => '邀请',
    'ALready Sent' => '邀请',
    'Registered' => '已注册',
    'Total' => '总计',

    'Name' => '供应商名',
    'Email' => '邮箱',
    'Contact Person' => '联系人',
    'Contact Phone' => '联系电话'
)
;

$lang['plan_status'] = array(
    'pending_release' => '待发布',
    'ongoing' => '进行中',
    'pending_proposal' => '待报告',
    'pending_confirm_proposal' => '待确认报告',
    'pending_payment' => '待付款',
    'confirm_payment_copy' => '水单确认',
    'finished' => '完成',
    'closed' => '关闭',
    'cancel' => '取消',
    'failed' => '失败'
);

$lang['email_status'] = array(
    'Invite Suppliers' => '邀请供应商',
    'Send Invitation' => '邀请',
    'Already Sent' => '已发',
    'Registered' => '已注册'
);

$lang['customer_js'] = array(
    'No file Choosen' => '未有文件',
    'Please Choose First' => '请先选择文件',
    'Upload Payble Format' => '请上传支付清单的xls格式电子表格'
);


$lang['Select Country'] = '选择国家';

$lang['Language'] = array(
    'chinese' => '中文简体',
    'english' => '英文'
);

$lang['register'] = array(

    'EmailAddress' =>  '邮箱地址',    
    'Password' => '密码',
    'ConfirmPassword' => '确认密码' ,
    'CompanyName' => '公司名称',   
    'ContactName' => '联系人',
    'Position' => '职位',
    'Telephone' => '联系电话',
    'Cellphone' => '分机号',
    'Security Code' => '验证码'        
);


$lang['button'] = array(
    'SIGN UP' => '注册' ,    
    'CANCEL' => '取消',
    'ADD' => '新增',
    'EDIT' => '修改',
    'DELETE' => '删除',
    'UPDATE' => '更新',
    'CONFIRM' => '确定',
    'SUBMIT' => '提交',
    'OK' => '确定',
    'SETTING' => '设置',
    'LOG OUT' => '登 出',
    'SIGN IN' => '登 录',
    'BACK' => '上一步',
    'NEXT' => '下一步',
    'UPLOAD' => '上传',
    'DOWNLOAD' => '下载',
    'FINISH' => '完成'
    
);


$lang['register_holder'] = array(

    'EmailAddress' => '请输入邮箱地址',
    'Password' => '请输入密码',
    'ConfirmPassword' => '请重新输入密码',
    'CompanyName' => '请输入公司名称',  
    'ContactName' => '请输入联系人',
    'Position' => '请输入职位',
    'Telephone' => '请输入联系电话',
    'Cellphone' => '请输入分机号',
    'Security Code' => '请输入验证码'
);


$lang['plandetail'] = array(
    'col_ID' => '序号',
    'col_InvoiceId' => '发票号',
    'col_InvAmount' => '发票金额',
    'col_EstPaydate' => '原定付款日期',
    'col_DPE' => '提前天数',
    'col_DiscountRate' => '折扣率(%)',
    'col_Discount' => '折扣金额',
    'col_IsWinner' => '成交'
) ;

