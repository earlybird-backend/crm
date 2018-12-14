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
</div>
<div class="infocls <?php echo $tracePageshow;?>">
    <div class="row" style="margin-top: 10px;">
        <div class="col-sm-10">&nbsp;</div>
        <div class="col-sm-2">
        <button data-flag="advanced_search"  class="btn btn-primary" type="button"   onclick="advancedSearch();" >
            高级搜索
        </button>
            <script>
                function advancedSearch() {
                    $('#advanced_search_info').modal({
                        keyboard:false
                    });
                }
                function advancedSearchTraceDo() {
                    alert("搜索");
                    $('#advanced_search_info').modal('hide');
                }
            </script>
         </div>
    </div>
    <div class="modal fade" id="advanced_search_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">输入搜索条件</h4>
                </div>
                <div class="modal-body">
                        <div style="float:left;margin-left: 10px;margin-top: 5px;">
                        <input type="text" id="EmailAddress" class="form-control" placeholder="账户名称"/>
                        </div>
                        <div style="float:left;margin-left: 10px;margin-top: 5px;">
                            <input type="text" id="HyperLink" class="form-control" placeholder="执行对象"/>
                        </div>
                       <div style="float:left;margin-left: 10px;margin-top: 5px;">
                        <input type="text" id="IP" class="form-control" placeholder="客户IP"/>
                       </div>
                        <div style="float:left;margin-left: 10px;margin-top: 5px;">
                            <input type="text" id="CashpoolCode" class="form-control" placeholder="市场编号"/>
                        </div>
                        <div style="float:left;margin-left: 10px;margin-top: 5px;">
                            <input type="text" id="CashpoolCode" class="form-control" placeholder="市场编号"/>
                        </div>
                        <div style="float:left;margin-left: 10px;margin-top: 5px;">
                            <input type="text" id="CreateTime" class="form-control" placeholder="执行时间"/>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="advancedSearchTraceDo();">搜索</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
    <div class="row" style="margin: 0 auto;padding: 5px 0px 5px 0px;">
        <div class="graph-body">
        <div class="table-body" style="padding:25px;">
            <table id="myTable" class="display" cellspacing="0">
                <thead>
                <tr>
                    <th><span>账户名称</span></th>
                    <th>
                        <span>执行对象</span>
                    </th>
                    <th><span>客户IP</span></th>
                    <th><span>市场编号</span></th>
                    <th>执行GET数据</th>
                    <th>执行POST数据</th>
                    <th>执行时间</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($rs as $item): ?>
                <tr>
                    <td><?php echo $item["EmailAddress"];?></td>
                    <td><?php echo $item["HyperLink"];?></td>
                    <td><?php echo $item["IP"];?></td>
                    <td><?php echo $item["CashpoolCode"];?></td>
                    <td><?php echo $item["GetData"];?></td>
                    <td><?php echo $item["PostData"];?></td>
                    <td><?php echo $item["CreateTime"];?></td>
                </tr>
                <?php endforeach ?>
                </tbody>
           </table>
            <script>
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
