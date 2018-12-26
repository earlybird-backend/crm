<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>
<style>

    .infocls
    {
        width: 1130px;background-color: #ffffff;overflow: hidden;display: none;
    }
    .pageshow
    {
        display: block; !important;
    }
    #myTableCss .split1 {

        border-bottom:1px solid #b1b1b1;
    }
    .myTableCss .split2:before
    {
        content:" / ";
    }
    .myTableCss th {
        font-size: 12px;
        text-align: center;
    }
    .split1 {

        border-bottom:1px solid #b1b1b1;
    }
    .split2:before
    {
        content:" / ";
    }
    .searchtxtcls
    {
        float:left;margin-left: 10px;margin-top: 5px;width: 214px;
    }
</style>
<ul class="nav nav-tabs nav-justified" role="tablist">
    <li class="<?php echo $information;?>" role="presentation">
        <a href="/customer/user/userListDetail/<?php echo $id;?>" class="nav-link" aria-controls="active" role="tab" data-toggle="tab">基本信息</a>
    </li>
    <li class="<?php echo $company;?>" role="presentation">
        <a href="/customer/user/userCompanyList/<?php echo $id;?>" class="nav-link" aria-controls="inactive" role="tab" data-toggle="tab">公司列表</a>
    </li>
    <li class="<?php echo $trace;?>" role="presentation">
        <a href="/customer/user/userListDetailTrace/<?php echo $id;?>" class="nav-link" aria-controls="inactive" role="tab" data-toggle="tab">跟踪日志</a>
    </li>
</ul>
<div class="infocls <?php echo $CompanyPageshow;?>">
    <div class="row" style="margin: 0 auto;padding: 5px 0px 5px 0px;">
        <div class="graph-body">
            <div class="table-body" style="padding:25px;">
                <table id="myTableCompany" class="display myTableCss" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="width: 200px;text-align: left">公司名称<br>公司网站</th>
                        <th style="width: 200px;text-align: left"><span>公司地址</span></th>
                        <th style="text-align: left">所属国家<br>企业类型</th>
                        <th> 所属行业<br>对外联系人</th>
                        <th>公司邮箱<br>公司电话</th>
                        <th>备注</th>
                        <th>创建时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($rsCompany as $item): ?>
                        <tr>
                            <td>
                                <span class="split1"><?php echo $item["CompanyName"];?></span>
                                <br>
                                <span class="split1"><?php echo $item["CompanyWebsite"];?></span>
                            </td>
                            <td>
                                <?php echo $item["CompanyAddress"];?>
                            </td>
                            <td>
                                <span class="split1"><?php echo $item["countryName"];?>
                                 <br>
                                 <span class="split1"><?php echo $item["typeName"];?></span>
                            </td>
                            <td>
                                <span class="split1"><?php echo $item["industryName"];?>
                                    <br>
                                 <span class="split1"><?php echo $item["ContactPerson"];?></span>
                            </td>
                            <td>
                                <span class="split1"><?php echo $item["ContactEmail"];?></span>
                                <br>
                                <span class="split1"><?php echo $item["ContactPhone"];?></span>
                            </td>
                            <td>
                                <?php echo $item["CompanyInfo"];?>
                            </td>
                            <td>
                                <?php echo $item["CreateTime"];?>
                            </td>
                        </tr>
                    </tbody>
                    <?php endforeach ?>
                </table>
                <script>
                    $(document).ready(function() {
                        var table = $('#myTableCompany').DataTable({
                            bStateSave:true,
                            bFiltered:false,
                            info:true,
                            ordering:false,
                            searching:false,
                            bLengthChange: true,
                            paging:true,
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
<div class="infocls <?php echo $pageshow;?>">
    <?php if(!$item==null){?>
        <div class="row" style="margin: 0 auto;padding: 10px 0px 5px 0px;">
            <div class="col-sm-2">
                姓名:<span class="split1"><?php echo $info["FirstName"].' '.$info['LastName']; ?></span>
            </div>
            <div class="col-sm-5">
                移动电话 / 座机电话:<span class="split1"><?php echo $info['Telephone'] ?></span>
                <span class="split2"></span>
                <span class="split1"><?php echo $info['Cellphone'] ?></span>
            </div>
            <div class="col-sm-2">
                职位:<span class="split1"><?php echo $info['Position'] ?></span>
            </div>

            <div class="col-sm-3">
                电子邮件:<span class="split1"><?php echo $info['EmailAddress'] ?></span>
            </div>
        </div>
        <div class="row" style="margin: 0 auto;padding: 5px 0px 5px 0px;">
            <div class="col-sm-2">
                国家:<span class="split1"><?php echo $info["CountryName"]; ?></span>
            </div>
            <div class="col-sm-5">
                公司名称:<span class="split1"><?php echo $info["CompanyName"]; ?></span>
            </div>
            <div class="col-sm-3">
                注册时间:<span class="split1"><?php echo $info["RegisterDate"]; ?></span>
            </div>
        </div>
        <div style="height: 5px;width:inherit;clear: both">
            <div style="height: 1px;background-color: #CCCCCC; width:90%; margin-left: 5px;"></div>
        </div>
    <div class="row" style="margin: 0 auto;padding: 5px 0px 5px 0px;">
        <div class="col-sm-3">
            称呼:<span class="split1"><?php echo $item["UserName"]; ?></span>
        </div>
        <div class="col-sm-3">
            名字/姓氏:<span class="split1"><?php echo $item["FirstName"]; ?></span>
            <span class="split1"><?php echo $item["LastName"]; ?></span>
        </div>
        <div class="col-sm-2">
            角色:<span class="split1"><?php echo $item["UserRole"]; ?></span>
        </div>
        <div class="col-sm-2">
            职位:<span class="split1"><?php echo $item["UserPosition"]; ?></span>
        </div>
        <div class="col-sm2">
            状态:<input id="UserStatuschk_<?php echo $item["Uid"]; ?>" type="checkbox" <?php echo intval($item['UserStatus'])==1?"checked=\"checked\"":"" ?> onchange="UserStatusChange('<?php echo $item["Uid"]; ?>',this);" /></span>
        </div>
    </div>
    <div class="row" style="margin: 0 auto;padding: 5px 0px 5px 0px;">
        <div class="col-sm-3">
            电子邮件:<span class="split1"><?php echo $item["UserEmail"]; ?></span>
        </div>
        <div class="col-sm-3">
            联系电话:<span class="split1"><?php echo $item["UserPosition"]; ?></span>
        </div>
    </div>
    <?php }?>
</div>
<div class="infocls <?php echo $tracePageshow;?>">
    <div class="row" style="margin-top: 10px;">
        <div class="col-sm-10">&nbsp;</div>
        <div class="col-sm-2">

         </div>
    </div>
    <div class="input-group searchtxtcls">
          <span class="input-group-addon">
              <input id="user_email_c" type="checkbox">
          </span>
        <input type="text" id="user_email_t" class="form-control" autocomplete="off" placeholder="账户名称">
    </div>
    <div class="input-group searchtxtcls">
          <span class="input-group-addon">
              <input id="apply_api_c" type="checkbox">
          </span>
        <input type="text" id="apply_api_t" class="form-control" autocomplete="off" placeholder="执行对象">
    </div>
    <div class="input-group searchtxtcls">
          <span class="input-group-addon">
              <input id="user_ip_c" type="checkbox">
          </span>
        <input type="text" id="user_ip_t" class="form-control" autocomplete="off" placeholder="客户IP">
    </div>
    <div class="input-group searchtxtcls">
          <span class="input-group-addon">
              <input id="market_id_c" type="checkbox">
          </span>
        <input type="text" id="market_id_t" class="form-control" autocomplete="off" placeholder="市场编号">
    </div>
    <div class="input-group searchtxtcls">
          <span class="input-group-addon">
              <input id="app_key_c" type="checkbox">
          </span>
        <input type="text" id="app_key_t" class="form-control" autocomplete="off" placeholder="平台key">
    </div>
    <div style="clear: both"></div>
    <div class="input-group searchtxtcls">
          <span class="input-group-addon">
              <input id="createTime_c" type="checkbox">
          </span>
        <input type="text" id="createTime_t" class="form-control" autocomplete="off" placeholder="开始执行时间">
    </div>
    <div class="input-group searchtxtcls">
          <span class="input-group-addon">
              <input id="createTime1_c" type="checkbox">
          </span>
        <input type="text"  id="createTime1_t" class="form-control" autocomplete="off" placeholder="结束执行时间">
    </div>
    <button type="button" class="btn btn-primary" style="margin-top: 5px;margin-left: 5px;" onclick="advancedSearchTraceDo();">搜索</button>
    <script>
        function getSearchStr(key,datap) {
            if($("#"+key+"_c").prop("checked")) {
                var t = $("#"+key+"_t").val();
                datap[key] = t;
            }
        }
        function advancedSearchTraceDo() {
            //alert("搜索");
            //$('#advanced_search_info').modal('hide');
            var datap = {};
            getSearchStr('user_email',datap);
            getSearchStr('apply_api',datap);
            getSearchStr('user_ip',datap);
            getSearchStr('market_id',datap);
            getSearchStr('app_key',datap);
            getSearchStr('createTime',datap);
            getSearchStr('createTime1',datap);
            searchParam = datap;
            myDataTable.ajax.reload();
        }
        $(".searchtxtcls input[type='checkbox']").bind(
            "change",function() {
                if(!$(this).prop("checked")) {
                    $(this).closest('div').find("input[type='text']:first").val('');
                }
            }
        );
        $(".searchtxtcls input[type='text']").bind(
            "change blur focus keyup",function() {
                var t = $(this).val();
                if($.trim(t)!='')
                {
                    $(this).closest('div').find("input[type='checkbox']:first").prop("checked", true);
                }
                else
                {
                    $(this).closest('div').find("input[type='checkbox']:first").prop("checked", false);
                }
            }
        );
        $("#createTime_t").datetimepicker();
        $("#createTime1_t").datetimepicker();
    </script>
    <div class="row" style="margin: 0 auto;padding: 5px 0px 5px 0px;">
        <div class="graph-body">
        <div class="table-body" style="padding:25px;">
            <table id="myTable" class="display myTableCss" cellspacing="0">
                <thead>
                <tr>
                    <th><span>账户名称</span></th>
                    <th>
                        <span>执行链接</span>
                    </th>
                    <th><span>请求方式</span></th>
                    <th><span>客户IP</span></th>
                    <th><span>市场编号</span></th>
                    <th><span>平台key</span></th>
                    <th>数据查看</th>
                    <th>执行时间</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
           </table>
            <div class="modal fade" id="log_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #0a0a0a">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">数据查看</h4>
                        </div>
                        <div class="modal-body">
                            <style>
                                .dataTabTxt
                                {
                                    word-break:break-all;width: 90%;margin:10px 10px 10px;display: none
                                }
                            </style>
                            <div class="label label-primary"  style="margin: 5px;;cursor: pointer" onclick="downTreeDataTab('requestDataTab');">请求数据	&#8744;</div>

                            <div id="requestDataTab" class="dataTabTxt" style="display:block"></div>
                            <div style="height: 5px;width:inherit;clear: both">
                                <div style="height: 1px;background-color: #CCCCCC; width:90%; margin-left: 5px;"></div>
                            </div>
                            <div class="label label-primary"  style="margin: 5px;;cursor: pointer" onclick="downTreeDataTab('executeDataTab');">执行数据	&#8744;</div>

                            <div id="executeDataTab" class="dataTabTxt"></div>
                            <div style="height: 5px;width:inherit;clear: both">
                                <div style="height: 1px;background-color: #CCCCCC; width:90%; margin-left: 5px;"></div>
                            </div>
                            <div class="label label-primary" style="margin: 5px;cursor: pointer" onclick="downTreeDataTab('receivingDataTab');">返回数据	&#8744;</div>
                            <div id="receivingDataTab" class="dataTabTxt"></div>
                            <div style="height: 5px;width:inherit;clear: both">
                                <div style="height: 1px;background-color: #CCCCCC; width:90%; margin-left: 5px;"></div>
                            </div>
                            <script>
                                function  downTreeDataTab(id) {
                                    $(".dataTabTxt:not(#"+id+")").hide(100);
                                    $("#"+id).toggle(100);
                                }
                            </script>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div>
            <script>
                function openlogInfo(id) {
                    var t = $('#requestData_'+id).html();
                    t = $.trim(t) == ''?'无':t;
                    $('#requestDataTab').html(t);
                    t = $('#executeData_'+id).html();
                    t = $.trim(t) == ''?'无':t;
                    $('#executeDataTab').html(t);
                    t = $('#receivingData_'+id).html();
                    t = $.trim(t) == ''?'无':t;
                    $('#receivingDataTab').html(t);
                    $('#log_info').modal('show');
                }
                function UserStatusChange(uid,obj) {
                    var uidid = $(obj).attr("id");
                    //alert(uidid);
                    var ischeck = $('#'+uidid).is(':checked');
                    var valuet = 0;
                    if(ischeck)
                    {
                        valuet = 1;
                    }
                    $.post('/customer/user/userListForChangeStatusDo/'+uid,{value:valuet}
                    ,function (data) {
                            alert(data.message);
                     },'json');
                }
                $(document).ready(function() {
                    searchParam = [];
                    myDataTable = $('#myTable').DataTable({
                        bStateSave:true,
                        bFiltered:false,
                        info:true,
                        ordering:false,
                        searching:false,
                        bLengthChange: true,
                        paging:true,
                        columns: [
                            { data: 'user_email' },
                            { data: 'apply_api' },
                            { data: 'request_method' },
                            { data: 'user_ip' },
                            { data: 'market_id' },
                            { data: 'app_key' },
                            {
                                data: null,
                                render: function (data, type, row, meta) {
                                    return '<button data-flag="log_infoget"  class="btn btn-primary" type="button" \
                                        onclick="openlogInfo(' + row.id + ');" >查看 \
                                        </button>\
                                        <span id="requestData_' + row.id + '" style="display:none">\
                                        ' + row.request_data + '\
                                        </span>\
                                        <span id="executeData_' + row.id + '" style="display:none">\
                                        ' + row.execute_data + '\
                                        </span>\
                                        <span id="receivingData_' + row.id + '" style="display:none">\
                                        ' + row.receiving_data + '\
                                        </span>';
                                }
                            },
                            { data: 'create_time' }
                         ],
                        //服务器端，数据回调处理
                        "bProcessing" : true, //DataTables载入数据时，是否显示‘进度’提示
                        "bServerSide" : true, //是否启动服务器端数据导入
                        "sAjaxSource" : "/customer/user/userListDetailTraceSearch/<?php echo $id;?>?now=" + new Date().getTime(),
                        "fnServerData" : function(sSource, aDataSet, fnCallback) {
                            var datap = {};
                            datap["iDisplayStart"] = aDataSet[3].value;
                            datap["iDisplayLength"] = aDataSet[4].value;
                            if(Object.keys(searchParam).length>0)
                            {
                                $.extend(datap,searchParam);
                            }
                            $.ajax({
                                "dataType" : 'json',
                                "type" : "POST",
                                "url" : sSource,
                                "data" : datap,
                                "success" : function (data) {
                                    if(data.data == null || data.error == 1)
                                    {
                                        alert(data.message);
                                        return;
                                    }
                                    fnCallback(data.data);
                                }
                            });
                        }
                    });
                });
            </script>
          </div>
        </div>
    </div>
</div>
<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>
