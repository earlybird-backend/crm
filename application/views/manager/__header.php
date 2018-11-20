
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->data['title']; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
        
        <?php if($this->uri->segment(3)){$slash='../../../';}else{$slash='../../';} ?>
        
        
        <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
        <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
        <!--<link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/bootstrap.min.css" /> -->
        
                
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/style_customer.css">             
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/responsive.css" />
        
        
        
        <link rel="stylesheet" href="<?php echo lang_url(); ?>assets/css/bootstrap-datetimepicker.css" />


       <!-- jQuery -->
       <!-- <script src="<?php echo lang_url(); ?>assets/js/jquery.js"></script> -->
        <script src="<?php echo lang_url(); ?>assets/js/jquery-3.2.1.min.js"></script>

        <script src="<?php echo lang_url(); ?>assets/js/utils.js"></script>
 

        <!--  <script src="<?php echo lang_url(); ?>assets/js/bootstrap.min.js"></script> -->
        <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
        <script type="text/javascript" src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script type="text/javascript" src="<?php echo lang_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>

        <!--<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script> -->


        <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>

        <script type="text/javascript" src="http://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>



        <style>

    .nav .nav-bar a:hover{
        color:#1484da;    
    }
    
       
    .nav .nav-bar a.active{
        color:#1484da;    
    	background-color:#efefef;
    }
    
    
    .nav-tabs li {
    	min-width:120px;
    	text-align:center;
    	!important; 
    }
    
	.add-image-sec {
		display: block;
		color: #777;
		text-align: center;
		border: 2px dashed #a9b7b7;
		background: url(<?php echo base_url(); ?>assets/images/iconfont-add.png) center no-repeat;
		cursor: pointer;
		margin: 0 auto;
		height:100%;
		width:100%;
	}

	.minus-image-sec {
		display: block;
		color: #777;
		text-align: center;
		border: 2px dashed #a9b7b7;
		background: url(<?php echo base_url(); ?>assets/images/iconfont-minus.png) center no-repeat;
		cursor: pointer;
		margin: 0 auto;
		height:100%;
		width:100%;
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
	.market-box{
		border-radius:0;
	}
	.nav-bar ul{
		width: 80%;
	}
	.nav-bar ul li{
		width: 100%;
		display: inline-block;
		text-align: right;
	}

	.nav-bar ul li a{
		display: inline-block;
	}

	.content-strip{
		width: 75%;
		float:right;
	}
</style>          
        
        
        
        <!-- datatable setting -->
    <script>
        var CONSTANT = {            
        // datatables常量  
        DATA_TABLES : {  
            DEFAULT_OPTION : { // DataTables初始化选项
                // 禁用自动调整列宽  
                autoWidth : true,
                // 为奇偶行加上样式，兼容不支持CSS伪类的场合  
                stripeClasses : [ "odd", "even" ],  
                // 取消默认排序查询,否则复选框一列会出现小箭头  
                order : [],  
                // 隐藏加载提示,自行处理  
                processing : false,  
                // 启用服务器端分页  
                serverSide : false,  
                // 禁用原生搜索  
                searching : true ,
                 
            },  
            COLUMN : {  
                // 复选框单元格  
                CHECKBOX : {  
                    className: "td-checkbox",  
                    orderable : false,  
                    bSortable : false,  
                    data : "id",  
                    width : '60px',  
                    className : "text-center",
                    render : function(data, type, row, meta) {  
                        var content = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">';  
                        content += '    <input type="checkbox" class="group-checkable" value="' + data + '" />';  
                        content += '    <span></span>';  
                        content += '</label>';  
                        return content;  
                    }  
                }  
            },
            // 回调
    		CALLBACKS : {
    			// 表格绘制前的回调函数
    			PREDRAWCALLBACK : function(settings) {
    				if (settings.oInit.scrollX == '100%') {
    					// 给表格添加css类，处理scrollX : true出现边框问题
    					$(settings.nTableWrapper).addClass('dataTables_DTS');
    				}
    			},
    			INITCOMPLETE : function(settings, json) {
    				if (settings.oInit.scrollX == '100%' && $(settings.nTable).parent().innerWidth() - $(settings.nTable).outerWidth() > 5) {
    					$(settings.nScrollHead).children().width('100%');
    					$(settings.nTHead).parent().width('100%');
    					$(settings.nTable).width('100%');
    				}    				
    				
    			},
    			// 表格每次重绘回调函数
    			DRAWCALLBACK : function(settings) {
        			
    				if ($(settings.aoHeader[0][0].cell).find(':checkbox').length > 0) {
    					// 取消全选
    					$(settings.aoHeader[0][0].cell).find(':checkbox').prop('checked', false);
    				}
    				// 高亮显示当前行
    				$(settings.nTable).find("tbody tr").click(function(e) {
    					$(e.target).parents('table').find('tr').removeClass('warning');
    					$(e.target).parents('tr').addClass('warning');
    				});
    			}
    		},

            // 常用render可以抽取出来，如日期时间、头像等  
            RENDER : {  
                ELLIPSIS : function(data, type, row, meta) {  
                    data = data || "";  
                    return '<span title="' + data + '">' + data + '</span>';  
                }  
            }  
              
        }  
      
    };  
      
    if ($.fn.dataTable) {
		$.extend($.fn.dataTable.defaults, {
			processing : true,
			order: [],
			paging : true,
			searching: true,
            autoWidth : true,
            bAutoWidth: true,
			aoColumnDefs: [ { "bSortable": false, "aTargets": [ 0 ] }] ,
			//language : CONSTANT.DATA_TABLES.DEFAULT_OPTION.LANGUAGE,
			preDrawCallback : CONSTANT.DATA_TABLES.CALLBACKS.PREDRAWCALLBACK,
			initComplete : CONSTANT.DATA_TABLES.CALLBACKS.INITCOMPLETE		,	
			drawCallback : CONSTANT.DATA_TABLES.CALLBACKS.DRAWCALLBACK
			
		});
    }

        Date.prototype.Format = function (fmt) {
            var o = {
                "M+": this.getMonth() + 1, //月份
                "d+": this.getDate(), //日
                "h+": this.getHours(), //小时
                "m+": this.getMinutes(), //分
                "s+": this.getSeconds(), //秒
                "q+": Math.floor((this.getMonth() + 3) / 3), //季度
                "S": this.getMilliseconds() //毫秒
            };
            if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            for (var k in o)
                if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            return fmt;
        }

    </script>     
        
        
        
</head>
<!--class="page-loading"-->
<body class="inner-page preloading" style="background-color:#efefef">
 
 
    

<header>
	<div class="container">
		<div class="logo pull-left" style="margin-right:20px;">
		    <div style="display:inline-block;">
			     <a href="#">
				    <img src="<?php echo base_url(); ?>assets/images/logo.png">
			     </a>
			</div>
			<!--  
			<div style="display:inline-block;margin-left:35px;">
			     <a href="<?php echo lang_url('english',$uri); ?>" style="color:#fff;"> <img src="<?php echo lang_url(); ?>assets/icon/1.svg" style="margin-right:3px;width:24px;"> English </a>
			</div>
			-->
		</div>
		<div class="nav nav-bar" >

			<ul class="nav nav-bar">
				<li>
						<a href="<?php echo base_url('auth/logout'); ?>" title ="点击后将注销当前登录用户 '<?php echo $this->session->userdata('ContactName');?>'">
								<i class="fa fa-sign-out" style="padding-left:5px;padding-right:5px;"></i><?php echo 'Logout'; ?>
						</a>

				</li>
			</ul>			
		</div>	
			
	</div>
</header>
<section class="main-wrap">
<div class="container">
    <div class="main-strip">
    
        	      
    <?php if(isset($pre_nav) && !empty($pre_nav)){?>
    <!-- 面包屑导航 -->
    <div style="margin-top:20px;box-shadow:0px 0px 4px 2px rgba(0,0,0, 0.10);" >
     <ol class="breadcrumb" style="background-color:#fff;">
            <li><a href="<?php echo base_url($pre_nav['uri']);?>"><?php echo $pre_nav['title'];?></a></li>  
            <li class="active"><?php echo $title; ?></li>                
    </ol>
    </div>
    <?php }else{?>
    <div style="margin-top:20px;"></div>
    <?php }?>
      
    <div class="pre-lodder" style="text-align: center">
        <img src="<?php echo lang_url(); ?>assets/images/logo_pre.gif">
    </div>

