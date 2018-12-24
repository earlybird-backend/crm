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
    #myTable .split1 {

        border-bottom:1px solid #b1b1b1;
    }
    #myTable .split2:before
    {
        content:" / ";
    }
    #myTable th {
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
        float:left;margin-left: 10px;margin-top: 5px;width: 142px;
    }
</style>
<ul class="nav nav-tabs nav-justified" role="tablist">
    <li class="<?php echo $information;?>" role="presentation">
        <a href="/customer/user/userListDetail/<?php echo $id;?>" class="nav-link" aria-controls="active" role="tab" data-toggle="tab">基本信息</a>
    </li>
    <li class="<?php echo $trace;?>" role="presentation">
        <a href="/customer/user/userListDetailTrace/<?php echo $id;?>" class="nav-link" aria-controls="inactive" role="tab" data-toggle="tab">跟踪日志</a>
    </li>
</ul>
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
    <div class="searchtxtcls">
        <input type="text" id="user_email_t" class="form-control" autocomplete="off" placeholder="账户名称"/>
    </div>
    <div class="searchtxtcls">
        <input type="text" id="apply_api_t" class="form-control" autocomplete="off" placeholder="执行对象"/>
    </div>
    <div class="searchtxtcls">
        <input type="text" id="user_ip_t" class="form-control" autocomplete="off" placeholder="客户IP"/>
    </div>
    <div class="searchtxtcls">
        <input type="text" id="market_id_t" class="form-control" autocomplete="off" placeholder="市场编号"/>
    </div>
    <div class="searchtxtcls">
        <input type="text" id="app_key_t" class="form-control" autocomplete="off" placeholder="平台key"/>
    </div>
    <div class="searchtxtcls">
        <input type="text" id="CreateTime_t" class="form-control" autocomplete="off" placeholder="开始执行时间"/>
    </div>
    <div class="searchtxtcls">
        <input type="text" id="CreateTime_t1" class="form-control" autocomplete="off" placeholder="结束执行时间"/>
    </div>
    <button type="button" class="btn btn-primary" style="margin-top: 5px;margin-left: 5px;" onclick="advancedSearchTraceDo();">搜索</button>
    <script>
        function advancedSearchTraceDo() {
            alert("搜索");
            $('#advanced_search_info').modal('hide');
        }
        $("#CreateTime_t").datetimepicker();
        $("#CreateTime_t1").datetimepicker();
    </script>
    <div class="row" style="margin: 0 auto;padding: 5px 0px 5px 0px;">
        <div class="graph-body">
        <div class="table-body" style="padding:25px;">
            <table id="myTable" class="display" cellspacing="0">
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
                    <th>请求数据</th>
                    <th>执行数据</th>
                    <th>执行时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($rs as $item): ?>
                <tr>
                    <td><?php echo $item["user_email"];?></td>
                    <td><?php echo $item["apply_api"];?></td>
                    <td><?php echo $item["request_method"];?></td>
                    <td><?php echo $item["user_ip"];?></td>
                    <td><?php echo $item["market_id"];?></td>
                    <td><?php echo $item["app_key"];?></td>
                    <td>
                        <?php $executeData = $item["request_data"];?>
                        <button data-flag="log_infoget"  class="btn btn-primary" type="button" onclick="openlogInfo(<?php echo $item["id"];?>,'requestData');" >
                            查看
                        </button>
                        <span id="requestData_<?php echo $item["id"];?>" style="display:none">
                            <?php echo $executeData;?>
                        </span>
                    </td>
                    <td>
                        <?php $executeData = $item["execute_data"];?>
                        <button data-flag="log_infopost"  class="btn btn-primary" type="button"  onclick="openlogInfo(<?php echo $item["id"];?>,'executeData');" >
                            查看
                        </button>
                        <span id="executeData_<?php echo $item["id"];?>" style="display:none">
                            <?php echo $executeData;?>
                        </span>
                    </td>
                    <td><?php echo $item["create_time"];?></td>
                </tr>
                <?php endforeach ?>
                </tbody>
           </table>
            <div class="modal fade" id="log_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">查看日志</h4>
                        </div>
                        <div class="modal-body">
                            <div id="log_info_txt" style="word-break:break-all;width: 90%;margin: 0 auto;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div>
            <script>
                function openlogInfo(id,key) {
                    var t = $('#'+key+'_'+id).html();
                    $('#log_info_txt').html(t);
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
                    var table = $('#myTable').DataTable({
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
<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>
