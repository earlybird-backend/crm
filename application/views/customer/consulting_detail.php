<?php  include  $GLOBALS['view_folder'].'customer/__header.php'; ?>
<style>
    .split1 {

        border-bottom:1px solid #b1b1b1;
    }
    .split2:before
    {
        content:" / ";
    }
    .infocls
    {
        width: 1130px;background-color: #ffffff;overflow: hidden;display: none;
    }
    .pageshow
    {
        display: block; !important;
    }
</style>
<ul class="nav nav-tabs nav-justified" role="tablist">
    <li class="<?php echo $information;?>" role="presentation">
        <a href="/customer/consulting/consultingDetail/<?php echo $id;?>" class="nav-link" aria-controls="active" role="tab" data-toggle="tab">基本信息</a>
    </li>
    <li class="<?php echo $notes;?>" role="presentation">
        <a href="/customer/consulting/consultingDetailNotes/<?php echo $id;?>" class="nav-link" aria-controls="inactive" role="tab" data-toggle="tab">记录日志</a>
    </li>
    <li class="<?php echo $dactive;?>" role="presentation">
        <a href="/customer/consulting/consultingDetailActiveLog/<?php echo $id;?>" class="nav-link" aria-controls="inactive" role="tab" data-toggle="tab">操作日志</a>
    </li>
    <li class="<?php echo $sendEmail;?>" role="presentation">
        <a href="/customer/consulting/consultingSendEmail/<?php echo $id;?>" class="nav-link" aria-controls="inactive" role="tab" data-toggle="tab">发送邮件</a>
    </li>
</ul>
<div class="infocls <?php echo $pageshow;?>">
    <div class="row" style="margin: 0 auto;padding: 5px 0px 5px 0px;">
        <div class="col-sm-3">
            公司名称:&nbsp;&nbsp;<span class="split1"><?php echo $rs["CompanyName"]; ?></span>
        </div>
       <div class="col-sm-2">
           状态:&nbsp;&nbsp;<span class="split1">
            <?php
            $status = "-";
            switch ($rs['ActivateStatus']){
                case 0:
                    $status="新申请";
                    break;
                case 1:
                    $status="已线下沟通";
                    break;
                case 2:
                    $status="已经邀请注册";
                    break;
                case 3:
                    $status="已经注册";
                    break;
            }
            echo $status;
            ?>
            </span>
        </div>
        <div class="col-sm-3">
            电子邮箱:&nbsp;&nbsp;<span class="split1"><?php echo $rs["ContactEmail"]; ?></span>
        </div>
        <div class="col-sm-4">
            名 / 姓/ 所属角色:&nbsp;&nbsp;
            <span class="split1"><?php echo $rs['CompanyName'] ?></span>
            <span class="split2"></span>
            <span class="split1"><?php echo $rs['FirstName'] ?></span>
            <span class="split2"></span>
            <span class="split1"><?php echo $rs['LastName'] ?> </span>
            <span class="split2"></span>
            <span class="split1"><?php echo $rs['RoleName'] ?></span>
        </div>
    </div>
    <div class="row" style="margin: 0 auto;padding: 5px 0px 5px 0px;">
         <div class="col-sm-3">
             电子邮箱:&nbsp;&nbsp;<span class="split1"><?php echo $rs['ContactEmail'] ?></span>
         </div>
        <div class="col-sm-2">
            联系电话:&nbsp;&nbsp;<span class="split1"><?php echo $rs['ContactPhone'] ?></span>
        </div>
        <div class="col-sm-3">
            所属区域:&nbsp;&nbsp;<span class="split1"><?php echo $rs['RegionName'] ?></span>
        </div>
        <div class="col-sm-4">
            提交时间:&nbsp;&nbsp;<span class="split1"><?php echo $rs['CreateTime'] ?></span>
        </div>
     </div>
    <div style="padding: 5px 5px 5px 13px;">
        意向:&nbsp;&nbsp;<span class="split1"><?php echo $rs['InterestName'] ?></span>
    </div>
    <div style="padding: 5px 5px 5px 13px;">
        备注:
    </div>
    <div style="padding: 5px 5px 5px 13px;">
        <?php echo $rs['RequestComment'] ?>
    </div>
</div>
<div class="infocls <?php echo $pageshowActive;?>">
<div class="row" style="padding: 25px;">
    <div class="col-lg-12">
        选择时间范围从 <input id="datetimeStart">   至   <input id="datetimeEnd">
        &nbsp;&nbsp;
        <button class="btn btn-primary" type="button"   onclick="" >
            下载日志
        </button>
        <div style="margin-top: 10px;width: 100%;height: 200px;background-color:#a6e1ec">
            <div style="margin: 0 auto;padding-top: 90px;text-align: center">90天内日志统计线图</div>
        </div>
        <script>
            $("#datetimeStart").datetimepicker({//选择年月日
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                weekStart: 1,
                todayBtn: 1,//显示‘今日’按钮
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,  //Number, String. 默认值：0, 'hour'，日期时间选择器所能够提供的最精确的时间选择视图。

                clearBtn:true,//清除按钮

                forceParse: 0
            });
            $("#datetimeEnd").datetimepicker({//选择年月日
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                weekStart: 1,
                todayBtn: 1,//显示‘今日’按钮
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,  //Number, String. 默认值：0, 'hour'，日期时间选择器所能够提供的最精确的时间选择视图。

                clearBtn:true,//清除按钮

                forceParse: 0
            });
        </script>
    </div>
</div>
</div>
<div class="infocls <?php echo $pageshowEmail;?>">
    <textarea style="width:inherit;height: 200px;padding: 5px;"></textarea>
    <div style="width:inherit;overflow: hidden">
        <div style="margin: 0 auto;width: 100px">
            <button class="btn btn-primary" type="button"    onclick="alert('发送成功');" >
                提交
            </button>
        </div>
    </div>
</div>
<div class="infocls <?php echo $pageshowNotes;?>">
    <div class="row" style="width: 100%;margin: 0 auto;margin-top: 20px;">
        <div class="col-lg-6">&nbsp;</div>
        <div class="col-lg-6">
            <div class="input-group">
                <input id="notesContent" type="text" class="form-control">
                <span class="input-group-btn">
						<button class="btn btn-primary" type="button"  onclick="consultingNotesSubmitDo();" >
							发送邮件
						</button>
					</span>
            </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
    </div>
    <script>
        function consultingNotesDeleteDo(idt) {
            if(confirm('确实要删除吗?')) {
                $.post("/customer/consulting/consultingNotesDeleteDo", {id:idt}, function (data) {
                    alert(data.message);
                    if (data.error == 0) {
                        setTimeout(function () {
                            location.href = location.href;
                        }, 500);
                    }
                }, 'json');
            }
        }
         function consultingNotesSubmitDo() {
            var notesContentt = $('#notesContent').val();
             notesContentt = $.trim(notesContentt);
             if(notesContentt=='')
             {

             }
                $.post("/customer/consulting/consultingNotesSubmitDo", {id:<?php echo $id;?>,notesContent:notesContentt},function(data) {

                    alert(data.message);
                    if(data.error == 0)
                    {
                        $('#notesContent').val('');
                        setTimeout(function(){ location.href = location.href; }, 500);
                    }
                },'json');
        }
    </script>
    <div class="graph-body">
        <div class="table-body" style="padding:25px;">
        <table id="myTable" class="display" cellspacing="0">
        <thead>
        <tr>
            <th style="width: 50px">序号</th>
            <th>日志内容</th>
            <th style="width:160px;">创建时间</th>
            <th style="width:60px;">操作</th>
        </tr>
        </thead>
            <tbody>
            <?php
            $n = 0;
            foreach($rs as $v): ?>
        <tr>
                <td><?php echo ++$n ?></td>
                <td><?php echo $v["NotesContent"] ?></td>
                <td><?php echo $v["CreateTime"] ?></td>
                <td>
                    <button type="button"  class="btn btn-warning"   onclick="consultingNotesDeleteDo(<?php echo $v["id"];?>);">删除日志</button>
                </td>

            </tr>
             <?php endforeach ?>
        </tbody>
    </table>
            </div>
        </div>
</div>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable( {
            bStateSave:true,
            bFiltered:false,
            info:true,
            ordering:false,
            searching:false,
            bLengthChange: true,
            paging:true,
        } );

    });
    $(document).ready(function () {
        $('#myTable1').DataTable( {
            bStateSave:true,
            bFiltered:false,
            info:true,
            ordering:false,
            searching:false,
            bLengthChange: true,
            paging:true,
        } );

    });
</script>
<?php  include  $GLOBALS['view_folder'].'customer/__footer.php'; ?>
