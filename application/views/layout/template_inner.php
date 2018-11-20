<?php
//echo $this->uri->segment(2);
//$timesettings = $this->ConfigurationModel->getConfiguration();
//pr($timesettings);

if (is_array($timesettings) && sizeof($timesettings) > 0)
{
	extract($timesettings[0]);
}

$start = $PlanStartTime; 
$end = $PlanEndTime; 	

date('M j, Y').' '.$end ;


$ptime = $ProposalTime;

$proptime = date('M j, Y').' '.$ptime;

$stop_date = date('M j, Y H:i:s', strtotime($proptime . ' +1 day'));

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->data['title']; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
        	<?php if($this->uri->segment(3)){$slash='../../../';}else{$slash='../../';} ?>
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/bootstrap-switch.min.css" />
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style_backup.css">-->
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/responsive.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/customer.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/developer.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/jquery.datetimepicker.css" />
        
         <!--   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.15/datatables.min.css" />-->    
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/style_inner.css" />
        <link rel="stylesheet" href="<?php echo lang_url(); ?>assets/css/bootstrap-material-datetimepicker.css" />
   
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.2.1/dt-1.10.16/b-1.4.2/datatables.min.css"/>
         
        <!-- jQuery -->
        <!-- <script src="<?php echo lang_url(); ?>assets/js/jquery.js"></script> -->
         <script src="<?php echo lang_url(); ?>assets/js/jquery-3.2.1.min.js"></script>  


        <!-- Bootstrap Core JavaScript -->
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.2.1/dt-1.10.16/b-1.4.2/datatables.min.js"></script>
        <script src="<?php echo lang_url(); ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo lang_url(); ?>assets/js/bootstrap-switch.min.js"></script>
        <script src="<?php echo lang_url(); ?>assets/js/jquery.datetimepicker.full.js"></script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/rome/2.1.22/rome.standalone.js"></script>
        <script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>                      
       
        
        <script type="text/javascript"  src="<?php echo lang_url(); ?>assets/js/developer.js"></script>
		<script type="text/javascript"  src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script type="text/javascript"  src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
        
        <script type="text/javascript" src="<?php echo lang_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
   
    </head>
    <!--class="page-loading"-->
    <body class="inner-page preloading ajax">

        <div class="pre-lodder">
            <img src="<?php echo lang_url(); ?>assets/images/logo_pre.gif">
        </div>

        <div class="layer"></div>

        <?php if ($header_inner) echo $header_inner; ?>

        <?php if ($middle) echo $middle;  ?>

        <?php if ($footer_inner) echo $footer_inner; ?>


        <div class="menu-overlay"></div>

       

        <script>
            $(function(){
                $('.popup-input-sec').slimScroll({
                    height: '370px',
                     color: '#656565',
                    size: '4px',
                });
            });
        </script>
        
        
        <script language="javascript">
            jQuery(document).ready(function () {


                <?php 
                
                if($page == 'supplier_plandetail')
                {
                ?>
                
            	//针对 supplier 开价  
            	$("#supplier_switch_bid").bootstrapSwitch(
                    	{                      	
            	        onText:'参与',  
            	        offText:'关闭' ,
            	        onColor:"success",  
            	        offColor:"warning",

            	        onSwitchChange:function(event,state){  
            	        	                	        
            	            if(state==true){
            	               $('#BidRate').val('');  
          	            	   $('#BidRate').removeAttr('disabled');
         	            	   $('#BidType').removeAttr('disabled');
          	            	   $('#supplier_btn_setting').removeAttr('disabled');          	            	              	               
            	            }else{  
            	            	$('#BidRate').val('0');                	           


            	            	submitsuppliersvalue('<?php echo $encode_planid; ?>',0);
                	            
            	                
            	            }  
            	        }  
                	        
         	        }
         	        	
                );      
                                      	 
            	<?php } ?>

            	
                jQuery('#uploadbtn').click(function () {
                    var ext = jQuery('#userfile').val().split('.').pop().toLowerCase();
                    if (jQuery('#userfile').val() == '')
                    {
                        jQuery('#errorblock').css('display', 'block');
                        jQuery('#error_File').html('&nbsp;File name is required');
                        return false;
                    }
                    if (jQuery.inArray(ext, ['xls']) == -1) {
                        jQuery('#errorblock').css('display', 'block');
                        jQuery('#error_File').html('&nbsp;invalid extension!');
                        return false;
                    }
                });
                
                jQuery('#deltmult').click(function () {
                    //alert('hi');
                    var test = '';
                    $("input[type='checkbox']:checked").each(function () {

                        test += $(this).val() + ',';
                    });
                    window.location = "<?php echo site_url('customer/multipledelete/?id=') ?>" + test;
                });
                
                jQuery('#deltmultpaid').click(function () {
                    //alert('hi');
                    var test = '';
                    $("input[type='checkbox']:checked").each(function () {

                        test += $(this).val() + ',';
                    });
                    window.location = "<?php echo site_url('customer/multipledeletepaidsuppliers/?id=') ?>" + test;
                });
                
                jQuery('#deltmultsuply').click(function () {
                    //alert('hi');
                    var test = '';
                    $("input[type='checkbox']:checked").each(function () {

                        test += $(this).val() + ',';
                    });
                    window.location = "<?php echo site_url('customer/multipledeletesuppliers/?id=') ?>" + test;
                });
                
                jQuery('#deltplans').click(function () {
                    //alert('hi');
                    var test = '';
                    $("input[type='checkbox']:checked").each(function () {

                        test += $(this).val() + ',';
                    });
                    window.location = "<?php echo site_url('customer/multipledeleteplans/?id=') ?>" + test;
                });
                jQuery('#cancel').click(function () {
                    window.location = "<?php echo site_url('customer/dashboard') ?>";
                });
            });
        </script>
        <script type="text/javascript">
        
            function toggleChecked(status) {
                $("input:checkbox").each(function () {
                    $(this).attr("checked", status);
                })
            }


            /*$('.EstimatePayDate').datetimepicker({
             timepicker: false,
             format: 'd-m-Y',
             formatDate: 'Y/m/d',
             mask: '39/39/9999',
             
             });*/


            /*function deleteRecordConfirm(id)
             {
             if(confirm('Are you sure? You want to delete')){
             window.location = '<?php echo site_url('customer/multipledelete/') ?>/'+id;
             }else{
             return false;
             }
             }*/

            /*Start Step For Early Pay Plan*/

            function validate() {
                var output = true;
                $(".signup-error").html('');
                if ($("#personal-field").css('display') != 'none') {
                    if ($((".CurrencyType")).val().length === 0) {
                        output = false;
                        $("#CurrencyType-Error").html("Currency required!");
                        $("#CurrencyType-Error").addClass("error");
						$("#CurrencyType-Error").show();
                    } else {
                        $("#CurrencyType-Error").hide();
                    }
					
                    if (!($(".MinAPRPercent").val())) {						
                        output = false;
                        $("#MinAPRPercent-Error").html("MinAPRPercent required!");
                        $("#MinAPRPercent-Error").addClass("error");
						$("#MinAPRPercent-Error").show();
                    } else {
                        $("#MinAPRPercent-Error").hide();
                    }
					
					  
					if ($(".MinAPRPercent").val()==0 ) {	                           						 
                        output = false;
                        $("#MinAPRPercent-Error").html("Please Enter MinAPR Value or Greater Than zero");
                        $("#MinAPRPercent-Error").addClass("error");
						$("#MinAPRPercent-Error").show();
                    } else {
                        $("#MinAPRPercent-Error").hide();
                    }			
					
                    if (!($(".ExpectAPRRate").val())) {
                        output = false;
                        $("#ExpectAPRRate-Error").html("ExpectAPRRate required!");
                        $("#ExpectAPRRate-Error").addClass("error");
						 $("#ExpectAPRRate-Error").show();
                    } else {
                        $("#ExpectAPRRate-Error").hide();
                    }
					
										
					
                    if (!($(".ExpectAPRPercent").val())) {
                        output = false;
                        $("#ExpectAPRPercent-Error").html("ExpectAPRPercent required!");
                        $("#ExpectAPRPercent-Error").addClass("error");
						$("#ExpectAPRPercent-Error").show();
                    } else {
                        $("#ExpectAPRPercent-Error").hide();
                    }
					
					 if ($(".ExpectAPRPercent").val()==0 ) {                            						 
                        output = false;
                        $("#ExpectAPRPercent-Error").html("Please Enter Percent Value or Greater Than zero!");
                        $("#ExpectAPRPercent-Error").addClass("error");
						$("#ExpectAPRPercent-Error").show();
                    } else {
                        $("#ExpectAPRPercent-Error").hide();
                    }
                    if (!($(".EarlyPayAmount").val())) {
                        output = false;
                        $("#EarlyPayAmount-Error").html("EarlyPayAmount required!");
                        $("#EarlyPayAmount-Error").addClass("error");
						$("#EarlyPayAmount-Error").show();
                    } else {
                        $("#EarlyPayAmount-Error").hide();
                    }
					
					 if ($(".EarlyPayAmount").val()==0 ) {                            						 
                        output = false;
                        $("#EarlyPayAmount-Error").html("Please Enter Amount Value or Greater Than zero!");
                        $("#EarlyPayAmount-Error").addClass("error");
						$("#EarlyPayAmount-Error").show();
                    } else {
                        $("#EarlyPayAmount-Error").hide();
                    }
                    if (!($(".EstimatePayDate").val())) {
                        //alert('hi');
                        output = false;
                        $("#EstimatePayDate-Error").html("EstimatePayDate required!");
                        $("#EstimatePayDate-Error").addClass("error");
						$("#EstimatePayDate-Error").show();
                    } else {
                        $("#EstimatePayDate-Error").hide();
                    }
					

                }

                if ($("#password-field").css('display') != 'none') {

                    if (!($(".download-check").is(":checked"))) {
                        output = false;
                        $("#download-check-error").html("Please accept all terms!");
                    }

                    if (!($(".download-check1").is(":checked"))) {
                        output = false;
                        $("#download-check-error").html("Please accept all terms!");
                    }
                    if (!($(".download-check2").is(":checked"))) {
                        output = false;
                        $("#download-check-error").html("Please accept all terms!");
                    }
                    if (!($(".download-check3").is(":checked"))) {
                        output = false;
                        $("#download-check-error").html("Please accept all terms!");
                    }
                    /*if(!($("#confirm-password").val())) {
                     output = false;
                     $("#confirm-password-error").html("Confirm password required!");
                     }	
                     if($("#user-password").val() != $("#confirm-password").val()) {
                     output = false;
                     $("#confirm-password-error").html("Password not matched!");
                     }*/
                }
                return output;
            }
            $(document).ready(function () {
                
                $('#signup-step li.active').prev('li').addClass('done');
                /*$("#next").click(function () {
                 var output = validate();
                 if (output) {
                 
                 var current = $("#signup-step .active");
                 
                 var next = $("#signup-step .active").next("#signup-step li");
                 if (next.length > 0) {
                 
                 $("#" + current.attr("id") + "-field").hide();
                 //alert(current.attr("id"));
                 
                 $("#" + next.attr("id") + "-field").show();
                 //$("#back").show();						
                 //$("#finish").hide();
                 $("#supplier-record").hide();
                 $(".sidebar_right .active").addClass("done");
                 $(".sidebar_right .active").removeClass("active");
                 next.addClass("active");
                 if ($(".sidebar_right .active").attr("id") == $('#signup-step li:last-child').attr("id")) {
                 // $("#next").hide();
                 //$("#finish").show();
                 }
                 }
                 }
                 });
                 $("#back").click(function () {
                 $(".active").prev("li").removeClass("done");
                 var current = $(".active");
                 var prev = $(".active").prev("li");
                 if (prev.length > 0) {
                 $("#" + current.attr("id") + "-field").hide();
                 $("#" + prev.attr("id") + "-field").show();
                 //$("#next").show();
                 // $("#password-field").hide();
                 //$("#general-field").hide();					   
                 //$("#back").hide();					   
                 //$("#finish").hide();
                 $(".active").removeClass("active");
                 prev.addClass("active");
                 if ($(".active").attr("id") == $("li").first().attr("id")) {
                 //$("#back").hide();
                 }
                 }
                 });*/



                var mynext = $("#next").attr('data-id');
                if ($("#personal-field").hasClass("visible")) {
                    $("#back").hide();
                } else {
                    $("#back").show();
                }

                if ($("#general-field").hasClass("visible")) {
                    $("#next").hide();
                    $("#finish").hide();
					$("#finish1").show();
                } else {
                    $("#next").show();
                    $("#finish").hide();
					$("#finish1").hide();
                }

                $("#next").click(function () {
                    var output = validate();
                    if (output) {
                        var current = $("#signup-step .active");
                        var next = $("#signup-step .active").next("#signup-step li");
                        if (next.length > 0) {
                            var mynext = $("#next").attr('data-id');
                            $(".step_section_inner").removeClass('visible');
                            $("#" + mynext).addClass('visible');
                            if ($("#personal-field").hasClass("visible")) {
								
								 $("#EarlyPayAmount-Error").show();
                                $("#back").hide();
                            } else {
                                $("#back").show();
                                $(".personal-lists").hide();
                                $(".upload-lists").show();
                            }

                            if ($("#general-field").hasClass("visible")) {
                                $("#next").hide();
                                $("#finish").hide();
								 $("#finish1").show();
                                $(".upload-lists").hide();
                                $(".finish-lists").show();								
                            } else {


                                $("#next").show();
                                $("#finish").hide();
								 $("#finish1").hide();
                            }

                             var tabactive= $(".tab-head").attr("data-id");
                            if ($("#password-field").hasClass("visible")) {
                                $("#next").attr("data-id", "general-field")
                                $("#back").attr("data-id", "personal-field")
                            } else if ($("#general-field").hasClass("visible")) {
								//alert(tabactive);
								$(tabactive).addClass('active');
								$(".tab-head:first-child").addClass('active');
								$("#" + tabactive).addClass('active');
                                $("#next").attr("data-id", "general-field")
                                $("#back").attr("data-id", "password-field")
								
                            } else if ($("#personal-field").hasClass("visible")) {
                                $("#next").attr("data-id", "password-field")
                                $("#back").attr("data-id", "personal-field")
								

                            }

                            $(".sidebar_right .active").addClass("done");
                            $(".sidebar_right .active").removeClass("active");
                            next.addClass("active");
                        }
                    }
                });
                $("#back").click(function () {
                    $(".active").prev("li").removeClass("done");
                    var current = $(".active");
                    var prev = $(".active").prev("li");
                    if (prev.length > 0) {
                        var myback = $("#back").attr('data-id');
                        $(".step_section_inner").removeClass('visible');
                        $("#" + myback).addClass('visible');
                        if ($("#personal-field").hasClass("visible")) {
                            $("#back").hide();
                            $(".upload-lists").hide();
                            $(".personal-lists").show();
                        } else {
                            $("#back").show();
                        }

                        if ($("#password-field").hasClass("visible")) {
                            $(".finish-lists").hide();
                            $(".upload-lists").show();							
							$("#download-check-error").hide();
							
                        } else {

                        }

                        if ($("#general-field").hasClass("visible")) {

                            $("#next").hide();
                            $("#finish").hide();
                             $("#finish1").show();							
							
							
                        } else {
                            $("#next").show();
                            $("#finish").hide();
							$("#finish1").hide();
                        }



                        if ($("#password-field").hasClass("visible")) {
                            $("#back").attr("data-id", "personal-field")
                            $("#next").attr("data-id", "general-field")
                        } else if ($("#general-field").hasClass("visible")) {
                            $("#back").attr("data-id", "password-field")
                            $("#next").attr("data-id", "general-field")
                        } else if ($("#personal-field").hasClass("visible")) {
                            $("#next").attr("data-id", "password-field")
                            $("#back").attr("data-id", "personal-field")
                        }
                        $(".active").removeClass("active");
                        prev.addClass("active");
                    }
                });
                $("#uploadsuppliereditlist").on('click', function () {


                    var file_data = $('#choosefile1').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);

                    $('.pre-lodder').show();
                    $.ajax({
                        dataType: 'text', // what to expect back from the server

                        allowed_types: ["xls"],
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        url: '<?php echo base_url('customer/addcompletesupplierslist'); ?>',
                        data: form_data,
                        success: function (response) {
                            $('.pre-lodder').hide();
                            var b = response;
                            if (b == '') {
                                $("#supplierlists-check-error").html("Please Upload Supplier List");
                            } else if (b == 'Unsupported File!') {
                                $('.form-supplierlist').find('input:file').val('');
                                $("#supplierlists-check-error").html("Please Upload Only Supplier List");
                            } else if (b == 'Supplier List upload unsucessfully') {

                                $('.form-supplierlist').find('input:file').val('');
                                $("#supplierlists-check-error").html("Please Upload Only Supplier List");
                            } else {

                                $('#myModal3').modal('show');
                                $('#myModal').modal('hide');
								$('#myModal1').modal('hide');
                                $('#suppliers-list').html(response);
                            }
                        },
                        error: function (response) {

                        }
                    });
                });
                $("#confirmeditinglist").on('click', function () {


                    var file_data = $('#choosefile1').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $('.pre-lodder').show();
                    $.ajax({
                        dataType: 'text', // what to expect back from the server

                        allowed_types: ["xls"],
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        url: '<?php echo base_url('customer/completesuppliersconfirmcsv'); ?>',
                        data: form_data,
                        success: function (response) {
                            $('.pre-lodder').hide();
                            var a = response;							
                            window.location.href = "<?php echo base_url('customer/suppliersinformation'); ?>" + '/' + a;
                        },
                        error: function (response) {

                        }
                    });
                });
                $("#uploadinvitesuppliers").on('click', function () {


                    var file_data = $('#invitefile').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $('.pre-lodder').show();
                    $.ajax({
                        dataType: 'text', // what to expect back from the server

                        allowed_types: ["xls"],
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        url: '<?php echo base_url('customer/addinvitesupplierslist'); ?>',
                        data: form_data,
                        success: function (response) {
                            $('.pre-lodder').hide();
                            var b = response;
                            if (b == '') {
                                $("#invitesupplierlist-check-error").html("Please Choose First");
                            } else if (b == 'Unsupported File!') {
                                $('.form-supplierinvite').find('input:file').val('');
                                $("#invitesupplierlist-check-error").html("Please Upload Supplier List .xls Format");
                            } else if (b == 'Supplier List upload unsucessfully') {

                                $('.form-supplierinvite').find('input:file').val('');
                                $("#invitesupplierlist-check-error").html("Please Upload Supplier List .xls Format");
                            } else if (b == 'jpg') {
                                $('.form-supplier').find('input:file').val('');
                               $("#invitesupplierlist-check-error").html("Please Upload Supplier List .xls Format");
                            } else if (b == 'png') {
                                $('.form-supplier').find('input:file').val('');
                               $("#invitesupplierlist-check-error").html("Please Upload Supplier List .xls Format");
                            } else if (b == 'docx') {
                                $('.form-supplier').find('input:file').val('');
                               $("#invitesupplierlist-check-error").html("Please Upload Supplier List .xls Format");
                            } else {
								$("#invitesupplierlist-check-error").hide();
								//$(".invitesucessf").html("sucessufully Invite");
                                $('.ftype').html("No file Choosen");
                                $('#myModalinvite300').modal('show');
                                $('#invite-lists').html(response);
                            }

                        },
                        error: function (response) {

                        }
                    });
                });
                $("#confirminvitelist").on('click', function () {


                    var file_data = $('#invitefile').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $('.pre-lodder').show();
                    $.ajax({
                        dataType: 'text', // what to expect back from the server

                        allowed_types: ["xls"],
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        url: '<?php echo base_url('customer/addplanconfirminvitecsv'); ?>',
                        data: form_data,
                        success: function (response) {

                            var a = response;
                            $.ajax({

                                type: 'post',
                                url: '<?php echo base_url('customer/addplanconfirminvite'); ?>',
                                data: 'a=' + a,
                                success: function (html) {
															

                                    if (html == '') {
										//alert('hi');
										 $('.pre-lodder').hide();

                                         $(".invitesucessf").addClass("custom-postion-chlid");
                                         $(".invitesucessf").html("Your record added sucessfully.");
                                        $('#myModalinvite300').modal('hide');
                                        $('.form-supplierinvite').find('input:file').val('');
                                    } else {
                                          // alert('hi1');
                                          // $(".invitesucessf").html("sucessufully Invite");
										$('.pre-lodder').hide();
                                        $('.form-supplierinvite').find('input:file').val('');
                                        $("#invitesupplierlist-check-error").hide();
                                        $('.myinvitelist').html(html);
                                         $(".invitesucessf").addClass("custom-postion-chlid");
                                        $(".invitesucessf").html("Supplier Invite Sucessfully");
                                        //$('.myinvitelists').show();
                                        $('.first-upload').hide();
                                        $('#myModalinvite300').modal('hide');
                                    }
                                }
                            })



                        },
                        error: function (response) {

                        }
                    });
                });
                $("#uploadsupplierlist").on('click', function () {


                    var file_data = $('#supplierfile').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $.ajax({
                        dataType: 'text', // what to expect back from the server
                        allowed_types: ["xls"],
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        url: '<?php echo base_url('customer/addplansuppliercsv'); ?>',
                        data: form_data,
                        success: function (response) {
                            var b = response;
                            if (b == '') {
                                $("#supplierlists-check-error").html("Please Choose First");
                            } else if (b == 'Unsupported File!') {
                                $('.form-supplierlist').find('input:file').val('');
                                $("#supplierlists-check-error").html("Please Upload Supplier List .xls Format");
                            } else if (b == 'Supplier List upload unsucessfully') {

                                $('.form-supplierlist').find('input:file').val('');
                                $("#supplierlists-check-error").html("Please Upload Supplier List .xls Format");
                            } else {

                                $('#myModal3').modal('show');
                                $('#myModal').modal('hide');
                                $('#suppliers-list').html(response);
                            }

                        },
                        error: function (response) {

                        }
                    });
                });
                
                $("#confirmlist").on('click', function () {
					
					$('.pre-lodder').show();
                    var file_data = $('#supplierfile').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $.ajax({
                        dataType: 'text', // what to expect back from the server
                        allowed_types: ["xls"],
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        url: '<?php echo base_url('customer/addplanconfirmcsv'); ?>',
                        data: form_data,
                        success: function (response) {

                        	var r = JSON.parse(response);
                        	
                            if(r.ret === 1)
                            {
                            	$('.form-supplierlist').find('input:file').val('');
                            	$('#myModal3').modal('hide');
                            	
                            	$("#uploadsupplier").click();
                            	                            	
                            }
                            	
                        	$('.pre-lodder').hide();

                        },
                        error: function (response) {
                        	$(".my-sucsuplist").html("上传供应商清单失败.");
                        	$('.pre-lodder').hide();
                            console.log(response);
                        }
                    });
                });
            });
            $(document).ready(function () {

                $('.CurrencyList').on('change', function () {

                    var currencyid = $('select[name=CurrencyList]').val();
                    var currencyname = $(".CurrencyList option:selected").text();
                    if (currencyid == '') {

                        $('.CurrencyName').val('');
                        $('.hidden-currency').val('');
                    } else {
                        $('.hidden-currency').val(currencyid);
                        $('.CurrencyName').val(currencyname);
                    }

                });
                $('.CurrencyListp').on('change', function () {
					
                    var currencyid = $('select[name=CurrencyListp]').val();
                    var currencyname = $(".CurrencyListp option:selected").text();
                    if (currencyid == '') {

                        $('.CurrencyNamep').val('');
                        $('.hidden-currency').val('');
                    } else {
                        $('.hidden-currency').val(currencyid);
                        $('.CurrencyNamep').val(currencyname);
                    }

                });
            });
            $(document).ready(function () {

                $('.CurrencyLists').on('change', function () {

                    var currencyid = $('select[name=CurrencyLists]').val();
                    var currencyname = $(".CurrencyLists option:selected").text();
                    if (currencyid == '') {

                        $('.CurrencyNames').val('');
                    } else {

                        $('.CurrencyNames').val(currencyname);
                    }

                });
            });
            $(document).ready(function () {

                $('.EditCurrencyList').on('change', function () {

                    var currencyid = $('select[name=EditCurrencyList]').val();
                    var currencyname = $(".EditCurrencyList option:selected").text();
                    if (currencyid == '') {

                        $('.EditCurrencyName').val('');
                        $('.hidden-editcurrency').val('');
                    } else {
                        $('.hidden-editcurrency').val(currencyid);
                        $('.EditCurrencyName').val(currencyname);
                    }

                });
            });
            
            $(document).ready(function () {

                $('.EditCurrencyLists').on('change', function () {

                    var currencyid = $('select[name=EditCurrencyLists]').val();
                    var currencyname = $(".EditCurrencyLists option:selected").text();
                    if (currencyid == '') {

                        $('.EditCurrencyName').val('');
                        $('.hidden-editcurrency').val('');
                    } else {
                        $('.hidden-editcurrency').val(currencyid);
                        $('.EditCurrencyName').val(currencyname);
                    }

                });
            });
            /*End Step For Early Pay Plan*/


            $(document).ready(function () {
                /* ==============================================
                 Menu
                 =============================================== */
                $('a.open_close').on("click", function () {
                    $('.main-menu').toggleClass('show');
                    $('.layer').toggleClass('layer-is-visible');
                    $(this).toggleClass('active');
                });
                $('a.show-submenu').on("click", function () {
                    $(this).next().toggleClass("show_normal");
                });
                $('a.show-submenu-mega').on("click", function () {
                    $(this).next().toggleClass("show_mega");
                });
            });
             function imageupopup(winnerid,vendercode)
            {
               // alert(vendercode);
                $('.uploadimg' + vendercode).modal('show');
            }

            function deletepaymentcopys(winnerid,vendercode)
            {
                //alert(winnerid);
                $('.deletepopup' + vendercode).modal('show');
            }
            function deleteconfirms(vendercode, planid) {
                var vendercode = vendercode;
                //$('.pre-lodder').show();				
                if (vendercode) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('customer/confirmproposaldelete'); ?>',
                        data: {vendercode: vendercode},
                        success: function (html) {
                           //$('.deletepopup' + vendercode).modal('hide');
                            //$('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('customer/confirmproposal'); ?>/" + planid;
                        }
                    });
                }
            }

            function invitesupplier(modelid, supplierid, suppliername)
            {
                //所有邀请主都使用同一个Modal            	
                $('#myModal3').modal('show');
                $('.supplierid' + modelid).val(supplierid);
                $('.suppliername' + modelid).val(suppliername);
            }

            function inviteditsuppliers()
            {
                $('#myModal556').modal('show');
            }


            function finalfinish()
            {



                //window.location.href = "<?php echo base_url('customer/plans'); ?>";

            }

            function activatesuppliers(supplierid)
            {
				
                window.location.href = "<?php echo base_url('customer/activatesupplier'); ?>" + '/' + supplierid;
            }


            function manualaddsupplier() {


                var supplierid = $('input[name=supplierid]').val();
                var suppliername = $('input[name=suppliername]').val();
                var contactemail = $('input[name=contactemail]').val();
                var contactperson = $('input[name=contactperson]').val();
                var contactphone = $('input[name=contactphone]').val();
                $('.pre-lodder').show();
                if (!validateEmail(contactemail)) {
                    $("#contact-manual-error").html("Please fillup valid email address");
                } else if (supplierid == '' || suppliername == '' || contactemail == '' || contactperson == '' || contactphone == '') {
                    $("#contact-manual-error").hide();
                    $("#contact-manuals-error").html("Please fill required fields");
                } else {
                    $("#contact-manual-error").hide();
                    $("#contact-manuals-error").hide();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('customer/manualsuppliers'); ?>',
                        data: {supplierid: supplierid, suppliername: suppliername, contactemail: contactemail, contactperson: contactperson, contactphone: contactphone},
                        success: function (html) {
                            if (html == 'email exist in database') {
                                $("#emails-exist").html(html);
                            } else {
                                $('.pre-lodder').hide();
                                $('#invitesmymodel').modal('hide');
                                $(".manuales-supliers").html("Supplier Added Sucessfully");
                            }
                        }
                    });
                }
            }


            function invitescustomers() {
                $('.pre-lodder').show();
                var companycname = $('input[name=companycname]').val();
                var emailaddress = $('input[name=emailaddress]').val();
                var phones = $('input[name=phones]').val();
                var contact = $('input[name=contactphone]').val();
                var position = $('input[name=position]').val();
                if (companycname == '') {
					 $('.pre-lodder').hide();
                    $(".companycname").html("Please fill Company Name");
                } else {
                    $(".companycname").hide();
                }
                if (!validateEmail(emailaddress)) {
					 $('.pre-lodder').hide();
                    $(".emailaddress").html("Please fillup valid email address");
                } else {
                    $(".emailaddress").hide();
                }
                if (phones == '') {
					 $('.pre-lodder').hide();
                    $(".phones").html("Please fill Phone Number");
                } else {
                    $(".phones").hide();
                }
                if (contact == '') {
					 $('.pre-lodder').hide();
                    $(".contactphone").html("Please fill contact Number");
                } else {
                    $(".contactphone").hide();
                }
                if (position == '') {
					 $('.pre-lodder').hide();
                    $(".position").html("Please fill position");
                } else {
                    $(".position").hide();
                }
                if (position != '' && contact != '' && phones != '' && companycname != '' && emailaddress != '') {
					 $('.pre-lodder').hide();
                    $(".position").html("Please fill position");

                    $("#contact-invite-error").hide();
                    $("#contact-invites-error").hide();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('supplier/invitescustomeres'); ?>',
                        data: {companycname: companycname, emailaddress: emailaddress, phones: phones, contact: contact, position: position},
                        success: function (html) {                           
                            if (html == 'Email exixt') {								
								$(".alreadyemail").html("Email Already exixts!");
                               
                            } else {
                                $('.pre-lodder').hide();
                                window.location.href = "<?php //echo base_url('supplier/invitecustomer');           ?>";
                            }

                        }
                    });
                }
            }


            $(document).ready(function () {

                /*Start Plan Creating Process Code*/
                /*Start Step1 - Getting Currency Settings*/

                $('.ChangeCurrency').on('change', function () {
                    $('.pre-lodder').show();
                    var currencyid = $('select[name=CurrencyType]').val();
                    if (currencyid) {

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo base_url('customer/getcustomeraprsettings'); ?>',
                            data: {currencyid: currencyid},
                            dataType: 'JSON',
                            success: function (data) {
                                $('.pre-lodder').hide();
                                if ($.isEmptyObject(data))
                                {
                                    $('#myModal23').modal('show');
                                    
                                  $('.CurrencyListp').val(currencyid);
                                } else
                                {
                                    if (!($.isEmptyObject(data))) {

                                        $('.MinAPRPercent').val(data["CapitalCost"]);
                                    }
                                    if (!($.isEmptyObject(data))) {

                                        $('.ExpectAPRRate').val(data["ExpectedAPRType"]);
                                    }
                                    if (!($.isEmptyObject(data))) {

                                        $('.ExpectAPRPercent').val(data["ExpectedAPR"]);
                                    }
                                }

                            }
                        });
                    }
                    $('.MinAPRPercent').val('');
                    $('.ExpectAPRRate').val('');
                    $('.ExpectAPRPercent').val('');
                });
                /*End Step1 - Getting Currency Settings*/
                /*End Plan Creating Process Code*/


                /*Start Third Step Uploading Payable Suppliers*/
                $("#uploadsupplier").on('click', function () {

                    $('.pre-lodder').show();
                    var filename = $('#file').val();
                    //alert(filename);
                    var file_data = $('#file').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    $.ajax({
                        dataType: 'html', // what to expect back from the server
                        //tmp_dir: "tmp",
                        allowed_types: ["xls"],
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'post',
                        url: '<?php echo base_url('customer/uploadpayablecsv'); ?>',
                        data: form_data,
                        success: function (response) {
                            //alert(response);
                            $('.pre-lodder').hide();
                            var hiddenValue = $('#myhidden', $(response)).val();
							//alert (hiddenValue);
                            if (hiddenValue = '0') {
                              						
                            }else{
								$('.remove-seterror').hide();
							}
                             

                            var a = response;
							//alert(a);
							
                            if (a == '') {
								 $(".upload-check-error").html("<?php echo $this->lang->line('customer_js')['Please Choose First'] ?>");
                            } else if (a == 'PaybleList uploaded unsuccessfully') {
                                $('.form-supplier').find('input:file').val('');
                                $(".upload-check-error").html("<?php echo $this->lang->line('customer_js')['Upload Payble Format'] ?> ");
                            } else if (a == 'Unsupported File!') {
                                $('.form-supplier').find('input:file').val('');
                                $(".upload-check-error").html("<?php echo $this->lang->line('customer_js')['Upload Payble Format'] ?>");
                            } else if (a == 'jpg') {
                                $('.form-supplier').find('input:file').val('');
                                $(".upload-check-error").html("<?php echo $this->lang->line('customer_js')['Upload Payble Format'] ?>");
                            } else if (a == 'png') {
                                $('.form-supplier').find('input:file').val('');
                                $(".upload-check-error").html("<?php echo $this->lang->line('customer_js')['Upload Payble Format'] ?>");
                            } else if (a == 'docx') {
                                $('.form-supplier').find('input:file').val('');
                                $(".upload-check-error").html("<?php echo $this->lang->line('customer_js')['Upload Payble Format'] ?>");
                            } else {
								$('.pre-lodder').hide();
                                $('.ftype').html("<?php echo $this->lang->line('customer_js')['No file Choosen'] ?>");
                                $('span[class^="upload-check-error"]').remove();
                                $('.first-upload').hide();
                                $('.supplier-list').show();
                                $('.recordes').html(response);								 
                            }

                            var filename = $('#file').val();
                            //alert(filename);
                            var file_data = $('#file').prop('files')[0];
                            var form_data = new FormData();
                            form_data.append('file', file_data);
                            $.ajax({
                                allowed_types: ["xls"],
                                cache: false,
                                contentType: false,
                                processData: false,
                                type: 'post',
                                url: '<?php echo base_url('customer/uploadregpayablecsv'); ?>',
                                data: form_data,
                                success: function (html) {	
                                    $('.pre-lodder').show();								
    									
    								var reghiddenValue = $('#myreghidden', $(html)).val();									
                                    if (reghiddenValue =='1') {  
                                                $('.pre-lodder').hide();
        										$('.remove-seterror').hide();										
                                     			//$('span[class^="upload-check-error"]').remove();										
                                             $('.first-upload').hide();
                                            $('.supplier-list').show();	
                                        // $("input.finish").removeClass("disabled");
        								$("input.finish-stepsstart").hide();
        								$("input.finish-stepsfinal").show();
        								
                                    }
                             		$('.pre-lodder').hide();			
                                    $('.second-upload').hide();
                                    $('.mysupplierlist').html(html);
                                    $('.register-list').show();
								
									}
                                
                            });
                        }
                    });
                });
            });
            function updateinvitesupplierpopup(modelid) {

                $('.pre-lodder').show();
                var supplierid = $('.supplierid' + modelid).val();
                var suppliername = $('.suppliername' + modelid).val();
                var contactemail = $('.contactemail' + modelid).val();
                var contactperson = $('.contactperson' + modelid).val();
                var contactphone = $('.contactphone' + modelid).val();
                if (!validateEmail(contactemail)) {
                    $("#contact-email-error").html("Please fillup valid email address");
                } else if (supplierid == '' || suppliername == '' || contactemail == '' || contactperson == '' || contactphone == '') {
                    $("#contact-email-error").hide();
                    $("#contact-emails-error").html("Please fill required fields");
                } else {
                    $("#contact-email-error").hide();
                    $("#contact-emails-error").hide();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('customer/popupemailinvitesupplier'); ?>',
                        data: {supplierid: supplierid, suppliername: suppliername, contactemail: contactemail, contactperson: contactperson, contactphone: contactphone},
                        success: function (html) {
                            $('.pre-lodder').hide();
                            $('.sendnow').hide();
                            $('.sendresponse').append('<i class="fa fa-check" aria-hidden="true"></i>  Invition sent');
                            $('.invitepoup' + modelid).modal('hide');
                        }
                    });
                }
            }


            function planfinishprocess()
            {
                $('.pre-lodder').show();
                var CurrencyType = $(".CurrencyType :selected").attr('value');
                var minaprpercent = $('input[name=MinAPRPercent]').val();
                var ExpectAPRRate = $(".ExpectAPRRate :selected").attr('value');
                var ExpectAPRPercent = $('input[name=ExpectAPRPercent]').val();
                var earlypayamount = $('input[name=EarlyPayAmount]').val();
                var paydate = $('.EstimatePayDate').val();
                //alert(paydate);
                var filename = $('#file').val();
                //alert(filename);
                var file_data = $('#file').prop('files')[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('CurrencyType', CurrencyType);
                form_data.append('minaprpercent', minaprpercent);
                form_data.append('ExpectAPRRate', ExpectAPRRate);
                form_data.append('ExpectAPRPercent', ExpectAPRPercent);
                form_data.append('earlypayamount', earlypayamount);
                form_data.append('paydate', paydate);
                $.ajax({
                    dataType: 'text', // what to expect back from the server
                    //tmp_dir: "tmp",
                    allowed_types: ["xls"],
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    url: '<?php echo base_url('customer/finishplanprocess'); ?>',
                    data: form_data,
                    success: function (response) {
                        $('.pre-lodder').hide();
                        window.location.href = "<?php echo base_url('customer/plans'); ?>";
                    },
                    error: function (response) {

                    }
                });
            }


            /*End Third Step Uploading Payable Suppliers*/






            $(document).ready(function () {

                $('.ChangeBidType').on('change', function () {
                    $('.pre-lodder').show();
                    var currencyid = $('select[name=BidType]').val();
                    if (currencyid) {

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo base_url('supplier/addplan'); ?>',
                            data: {currencyid: currencyid},
                            dataType: 'JSON',
                            success: function (data) {
                                $('.pre-lodder').hide();
                                if (data["CapitalCost"]) {

                                    $('.MinAPRPercent').val(data["CapitalCost"]);
                                }


                            }
                        });
                    }


                });
            });
            function updateinvitesupplier() {

                $('.pre-lodder').show();
                var supplierid = $('input[name=supplierid]').val();
                var suppliername = $('input[name=suppliername]').val();
                var contactemail = $('input[name=contactemail]').val();
                var contactperson = $('input[name=contactperson]').val();
                var contactphone = $('input[name=contactphone]').val();
                var planid = $('.modelplanid').val();
                if (!validateEmail(contactemail)) {
                    $("#contact-email-error").html("Please fillup valid email address");
                } else if (supplierid == '' || suppliername == '' || contactemail == '' || contactperson == '' || contactphone == '') {
                    $("#contact-email-error").hide();
                    $("#contact-emails-error").html("Please fill required fields");
                } else {
                    $("#contact-email-error").hide();
                    $("#contact-emails-error").hide();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('customer/emailinvitesupplier'); ?>',
                        data: {supplierid: supplierid, suppliername: suppliername, contactemail: contactemail, contactperson: contactperson, contactphone: contactphone, planid: planid},
                        success: function (html) {
                            $('.pre-lodder').hide();
                            $('#myModal1').modal('hide');
                        }
                    });
                }
            }

            function validateEmail(contactemail) {
                var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                if (filter.test(contactemail)) {
                    return true;
                } else {
                    return false;
                }
            }


            $(function () {
                $('.table-new-upload').slimScroll({
                    height: '300px',
                    size: '2px',
                });
            });
            function uploadpaymentcopy(planid, vendorcode)
            {
                var filename = $('.paymentcopy'+vendorcode).val();
				//alert(filename);
                
				$('.pre-lodder').show();
                var file_data = $('.paymentcopy'+vendorcode).prop('files')[0];
				
				//alert(file_data);
				
                var form_data = new FormData();
                form_data.append('paymentcopy'+vendorcode, file_data);
                form_data.append('planid', planid);
                form_data.append('vendorcode', vendorcode);
               // form_data.append('WinnerId', winnerid);
                $.ajax({
                    dataType: 'text', // what to expect back from the server
                    //tmp_dir: "tmp",
                    allowed_types: ["jpg"],
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post',
                    url: '<?php echo base_url('customer/uploadpaymentcopy'); ?>',
                    data: form_data,
                    success: function (response) {                       						
                                               
                        if (response == 'Last Payment copy uploaded successfully' || response == 'Payment copy uploaded successfully') {
							$('.pre-lodder').hide(); 													
							
							 window.setTimeout(function () {
                           $('.uploadimg' + vendorcode).modal('hide');
						    $('.uploadsfiles' + vendorcode).html(response);
							window.location.href = "<?php echo base_url('customer/confirmproposal'); ?>" + '/' + planid;
                             },0);
							//$('.uploadimg' + winnerid).modal('hide');
							//$('.payment_copy'+ winnerid).modal('show');	
						 
                        }else {
							$('.pre-lodder').hide(); 
							$('.uploadmessage' + vendorcode).html(response);
						}


                    },
                    error: function (response) {

                    }
                });
            }
			function paymentcopydone()
            {
                 
               window.location.href = "<?php echo base_url('customer/confirmproposal'); ?>" + '/' + planid;
            }

            function submitsuppliersvalue(planid,isenable) {

                var BidType = $(".BidType :selected").attr('value');
                var BidRate = $('input[name=BidRate]').val();
                var planid = planid;
                //alert(planid);				 
                $('.pre-lodder').show();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('supplier/changeplandetail'); ?>',
                    data: {planid: planid, BidType: BidType, BidRate: BidRate ,EnableBid:isenable},
                    success: function (html) {
                        //alert(html);		
                        $('.pre-lodder').hide();
                       window.location.href = "<?php echo base_url('supplier/plandetail'); ?>" + '/' + planid;
                    }
                });
            }



            $(document).ready(function () {
                $('input[type="file"]').change(function (e) {
                    var fileName = e.target.files[0].name;
                    $(this).siblings('.ftype').text(fileName);
                });
            });
			
            $(document).ready(function () {
                var i = 1;
                $("#addmores").click(function () {
                    //alert('hi');


                    $("#settingc" + i).html("<hr /> <div class='header-wrap'><a href='#' class='edit-top pull-right'>Edit</a></div><div class='steps-wrap-line'><div class='warp-label' id='settingc" + i + "'><form class='wrap_apr_label'><div class='form-group'><label>Currency <span>*</span></label><select name='' disabled='disabled' class='CurrencyList form-group'><option value=''>Select Currency</option><?php foreach ($results as $key => $value) { ?><option value='<?php echo $value['CurrencyId']; ?>'><?php echo $value['CurencyName']; ?></option> <?php } ?></select></div><div class='form-group relative'><label>Currency</label><input type='text' readonly name='' class='CurrencyName steps-wraped-input form-group' value=''></div><div class='form-group relative'><label>Capital Cost APR</label><input type='text' readonly name='' class='CapitalCost  steps-wraped-input form-group' value=' '></div><div class='form-group'><label>Expect APR</label><select name='' disabled='' class='ExpectAPRRate form-group'><option value=''>Select ExpectAPRRate</option><option value='apr'>APR</option><option value='discount'>Discount</option><option value='minapr'>Min APR Increase</option></select></div><div class='form-group relative'><label>Expected APR</label><input type='text' readonly name='' class='steps-wraped-input form-group' value=' ' placeholder='Enter ExpectAPRPercent'></div></div></div>");
                    $('#settinglabel').append('<div id="settingc' + (i + 1) + '"></div>');
                    //$('#addaprsetting').modal('hide');			
                    i++;
                });
            });
            $(document).ready(function () {
                $("#customersetting").click(function () {
                    $('.pre-lodder').show();
                    var CurrencyId = $(".CurrencyList :selected").attr('value');
                    var CurrencyName = $('input[name=CurrencyName]').val();
                    var CapitalCost = $('input[name=CapitalCost]').val();
                    var ExpectAPRRate = $(".ExpectAPRRate :selected").attr('value');
                    var ExpectAPRPercent = $('input[name=ExpectAPRPercent]').val();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('customer/aprpreference'); ?>',
                        data: {CurrencyId: CurrencyId, CurrencyName: CurrencyName, CapitalCost: CapitalCost, ExpectAPRRate: ExpectAPRRate, ExpectAPRPercent: ExpectAPRPercent},
                        success: function (html) {
                            $('.pre-lodder').hide();
                            //window.location.href = "<?php echo base_url('customer/aprpreference'); ?>";						                            
                        }
                    });
                });
            });
            $(".open_modal_new").click(function () {
                var cusid = $(this).attr("data-id");
                $("#" + cusid).addClass('show');
                return false;
            });
            $(".cus_pop_close").click(function () {
                $(".custome_popup").removeClass('show');
            });
            function editsuppliercompanyname(userid)
            {
                $('.pre-lodder').show();
                var companyname = $('input[name=companyname]').val();
				if ($.isEmptyObject(companyname) && $('.companyname').val()=='' && $('.companyname').val().length === 0 &&  !$('.companyname').val() )
				 {
					// alert('hivinod');
					 $('.pre-lodder').hide();
					 //$('.btn-rectangular').click();
					 $('#settings_company1').modal('show'); 
                  $('#settings_company').modal('show');
				   $(".error-mails").html("Please fill Company Name");
			  
					
				}
                else if (userid && $('.companyname').val()!='') {
                  $('#settings_company1').modal('hide'); 
                  $('#settings_company').modal('hide');
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('supplier/settingcompany'); ?>',
                        data: 'companyname=' + companyname,
                          success: function (html) {
							  if(html=='Company Name Same')	{
								window.location.href = "<?php echo base_url('supplier/settings'); ?>";
							}	else{
                            $('.pre-lodder').hide();
							 $('.settings_company_supplier' + userid).modal('show');                            
                        }}
                    });
                }

            }
			
			function settingsupplieremail(userid)
            {
                 
               $('.settings_company_supplier' + userid).modal('hide'); 
            }

            function editcompanyname(userid)
            {
				
                $('.pre-lodder').show();
                var companyname = $('input[name=companyname]').val();			
				 if ($.isEmptyObject(companyname) && $('.companyname').val()=='' && $('.companyname').val().length === 0 &&  !$('.companyname').val() )
				 {
					
					 $('.pre-lodder').hide();
					 //$('.btn-rectangular').click();
					 $('.settings_company1' + userid).modal('show'); 
                  $('.settings_company' + userid).modal('show');
				   $(".error-mails").html("Please fill Company Name");
			  
					
				}
					
                else if (userid && $('.companyname').val()!='') {
                                         $('.settings_company1' + userid).modal('hide'); 
					$('.settings_company' + userid).modal('hide');
					//alert('by');
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('customer/settingcompany'); ?>',
                        data: 'companyname=' + companyname,
                        success: function (html) {
                            if(html=='Company Name Same')	{
								window.location.href = "<?php echo base_url('customer/settings'); ?>";
							}	else{
								$('.pre-lodder').hide();						
							$('.settings_company_send' + userid).modal('show');                           
							console.log(html);
							}					
                            
                            
                        }
                    });
                }
					
			
			
			}
			
			
			function settingcmpny(userid)
            {
                 
               $('.settings_company' + userid).modal('show'); 
            }
			function settingemail(userid)
            {
                 
               $('.settings_company_send' + userid).modal('hide'); 
            }

			
			
			


            function editsuppliercontact(userid)
            {
                $('.pre-lodder').show();
                var contactemail = $('input[name=contactemail]').val();
                var contactname = $('input[name=contactname]').val();
                var contactposition = $('input[name=contactposition]').val();
                var contactphone = $('input[name=contactphone]').val();
                if (userid) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('supplier/settinguser'); ?>',
                        data: {contactemail: contactemail, contactname: contactname, contactposition: contactposition, contactphone: contactphone},
                        success: function (html) {
                            $('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('supplier/settings'); ?>";
                        }
                    });
                }

            }


            function editcontact(userid)
            {
                $('.pre-lodder').show();
                var contactemail = $('input[name=contactemail]').val();
                var contactname = $('input[name=contactname]').val();
                var contactposition = $('input[name=contactposition]').val();
                var contactphone = $('input[name=contactphone]').val();
                if (userid) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('customer/settinguser'); ?>',
                        data: {contactemail: contactemail, contactname: contactname, contactposition: contactposition, contactphone: contactphone},
                        success: function (html) {
                            $('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('customer/settings'); ?>";
                        }
                    });
                }

            }

            function Addsupplierssetting() {
                $('.pre-lodder').show();
                var CurrencyId = $(".CurrencyLists :selected").attr('value');
                var CurrencyName = $('input[name=CurrencyNames]').val();
                var CapitalCost = $('input[name=CapitalCosts]').val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('supplier/addsettings'); ?>',
                    data: {CurrencyId: CurrencyId, CurrencyName: CurrencyName, CapitalCost: CapitalCost},
                    dataType: 'JSON',
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    success: function (html) {
                        if (html[0]) {
                            $("#mysupplierfields").html(html[0]);
                        } else {
                            $('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('supplier/settings'); ?>";
                        }
                    }
                });
            }
            function Addcustomersetting() {
                $('.pre-lodder').show();
                var CurrencyId = $(".CurrencyList :selected").attr('value');
                var CurrencyName = $('input[name=CurrencyName]').val();
                var CapitalCost = $('input[name=CapitalCost]').val();
                var ExpectAPRRate = $(".ExpectAPRRate :selected").attr('value');
                var ExpectAPRPercent = $('input[name=ExpectAPRPercent]').val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('customer/addaprpreference'); ?>',
                    data: {CurrencyId: CurrencyId, CurrencyName: CurrencyName, CapitalCost: CapitalCost, ExpectAPRRate: ExpectAPRRate, ExpectAPRPercent: ExpectAPRPercent},
                    dataType: 'JSON',
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    success: function (html) {
                        //alert(html[0]);							
                        if (html[0]) {
                            $(".myfields").html(html[0]);
                        } else {
                            $('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('customer/settings'); ?>";
                        }
                    }
                });
            }
            function Addcustomerplansetting() {
               // $('.pre-lodder').show();
                var CurrencyId = $(".CurrencyListp :selected").attr('value');
                var CurrencyName = $('input[name=CurrencyNamep]').val();
                var CapitalCost = $('input[name=CapitalCost]').val();
                var ExpectAPRRate = $(".ExpectAPRRatep :selected").attr('value');
                var ExpectAPRPercent = $('input[name=ExpectAPRPercentp]').val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('customer/addaprplanpreference'); ?>',
                    data: {CurrencyId: CurrencyId, CurrencyName: CurrencyName, CapitalCost: CapitalCost, ExpectAPRRate: ExpectAPRRate, ExpectAPRPercent: ExpectAPRPercent},
                    dataType: 'JSON',
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    success: function (html) {
                        //alert(html[0]);							
                        if (html[0]) {
                            $(".myfieldsc").html(html[0]);
                        } else {
                          //  $('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('customer/createplan'); ?>";
                        }
                    }
                });
            }
            function Editcustomersetting(SettingId)
            {
                $('.pre-lodder').show();
                var SettingId = SettingId;
                //var EditCurrencyid = $(".EditCurrencyList :selected").attr('value');
                var EditCurrencyid = $('input[name=EditCurrencyid]').val();
                var UserId = $('.UserId' + SettingId).val();
                var EditCurrencyName = $('.EditCurrencyName' + SettingId).val();
                var EditCapitalCost = $('.EditCapitalCost' + SettingId).val();
                var ExpectedAPRType = $(".EditExpectAPRRate" + SettingId + " :selected").attr('value');
                var EditExpectAPRPercent = $('.EditExpectAPRPercent' + SettingId).val();
                if (SettingId) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('customer/aprpreferenceedit'); ?>',
                        data: {SettingId: SettingId, EditCurrencyid: EditCurrencyid, UserId: UserId,
                            EditCurrencyName: EditCurrencyName, EditCapitalCost: EditCapitalCost,
                            ExpectedAPRType: ExpectedAPRType, EditExpectAPRPercent: EditExpectAPRPercent},
                        success: function (html) {
                            // alert(html);
                            $('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('customer/settings'); ?>";
                        }
                    });
                }

            }

            function Editsuppliersetting(SettingId)
            {
                $('.pre-lodder').show();
                var SettingId = SettingId;
                //var EditCurrencyid = $(".EditCurrencyLists :selected").attr('value');
                var EditCurrencyid = $(".EditCurrencyid" + SettingId).val();
                var EditCurrencyName = $('.EditCurrencyName' + SettingId).val();
                var EditCapitalCost = $('.EditCapitalCost' + SettingId).val();
                if (SettingId) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('supplier/settingsedit'); ?>',
                        data: {SettingId: SettingId, EditCurrencyid: EditCurrencyid,
                            EditCurrencyName: EditCurrencyName, EditCapitalCost: EditCapitalCost},
                        success: function (html) {
                            $('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('supplier/settings'); ?>";
                        }
                    });
                }

            }


              function ConfirmSupplierPayment(bidId)
            {
                $('.pre-lodder').show();
                if (bidId) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('supplier/confirmpayment'); ?>/' + bidId,
                        data: {bidId: bidId},
                        success: function (html) {
                            $('.pre-lodder').hide();
                            $('.confirmpayment').html(html);							
							 $('.confirm-file').hide();
							//window.location.href = "<?php echo base_url('supplier/plandetail'); ?>";
                            //alert(html);
                        }
                    });
                }

            }
			
			function finishedpopupplan()
            {
			    $('.finishedpopups').modal('show');
			}



			function popfinishedclose()
            {
                 $('.finishedpopups').modal('hide');
               
            }


        </script>

        


        <script>
            $(document).ready(function () {
                function setHeight() {
                    windowHeight = $(window).innerHeight();
                    $('.main-section').css('min-height', windowHeight);
                }
                ;
                setHeight();
                $(window).resize(function () {
                    setHeight();
                });
            });
        </script>

        <script>
            $(document).ready(function () {
                $(".bars").click(function () {
                    $(".sidebar_left").addClass('show');
                    $(".layer").addClass('visible');
                });
                $(".layer").click(function () {
                    $(".sidebar_left").removeClass('show');
                    $(".layer").removeClass('visible');
                });
            });
        </script>

        <script>
            $(".tab-head").click(function () {
                $(".tabs_content").addClass('show');
                $(".tab_inner").removeClass('active');
                var cusid = $(this).attr("data-id");
                $("#" + cusid).addClass('active');
                $(".tab-head").removeClass('active');
                $(this).addClass('active');
                return false;
            });
            function deleteapr(id) {
                $('.pre-lodder').show();
                var id = id;
                if (id) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('customer/aprpreferencedelete'); ?>/' + id,
                        data: {id: id},
                        success: function (html) {
                            $('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('customer/settings'); ?>";
                        }
                    });
                }
            }
            function deletesupplierapr(id) {
                var id = id;
                $('.pre-lodder').show();
                if (id) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('supplier/aprpreferencesdelete'); ?>/' + id,
                        data: {id: id},
                        success: function (html) {
                            $('.pre-lodder').hide();
                            window.location.href = "<?php echo base_url('supplier/settings'); ?>";
                        }
                    });
                }
            }
        </script>

        <script>
            $(document).ready(function () {
                // $('[data-toggle="tooltip"]').tooltip();
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip({container: 'body'})
                }
                );
            });
        </script>
        <script type="text/javascript">


            function save()
            {
                $('.pre-lodder').show();
                $('.form-group').removeClass('has-error');
                $('.help-block').empty();
                var url;
                url = "<?php echo site_url('customer/aprpreference') ?>";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: $('#form').serialize(),
                    dataType: "JSON",
                    success: function (data)
                    {

                        if (data.status)
                        {
                            $('.pre-lodder').hide();
							 $('#addaprsetting').modal('hide');
						    $('.settings_add_currencypopup').modal('show');
                           // window.location.href = "<?php echo base_url('customer/settings'); ?>";
                        } else
                        {
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
								$('.pre-lodder').hide();
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                            }
                        }

                    },
                });
            }
			
			function settingcurrencyredirect()
            {
                 
               window.location.href = "<?php echo base_url('customer/settings'); ?>";
            }

			
			
            function editsave(SettingId)
            {
                $('.pre-lodder').show();

                $('.form-group').removeClass('has-error');
                $('.help-block').empty();
				var SettingId = SettingId;
                //var EditCurrencyid = $(".EditCurrencyList :selected").attr('value');
                var EditCurrencyid = $('.EditCurrencyid' + SettingId).val();
				//alert(EditCurrencyid);
                var UserId = $('.UserId' + SettingId).val();
                var EditCurrencyName = $('.EditCurrencyName' + SettingId).val();
                var EditCapitalCost = $('.EditCapitalCost' + SettingId).val();
                var ExpectedAPRType = $(".EditExpectAPRRate" + SettingId + " :selected").attr('value');
                var EditExpectAPRPercent = $('.EditExpectAPRPercent' + SettingId).val();
                var url;

                url = "<?php echo site_url('customer/aprpreferenceedit') ?>";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {SettingId: SettingId, EditCurrencyid: EditCurrencyid, UserId: UserId,
                            EditCurrencyName: EditCurrencyName, EditCapitalCost: EditCapitalCost,
                            ExpectedAPRType: ExpectedAPRType, EditExpectAPRPercent: EditExpectAPRPercent},
                    dataType: "JSON",
                    success: function (data)
                    {

                        if (data.status)
                        {
                            $('.pre-lodder').hide();
							$('#settings_apr' + SettingId).modal('hide');
						    $('.settings_edit_currencypopup').modal('show');
                            //window.location.href = "<?php echo base_url('customer/settings'); ?>";
                        } else
                        {
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
								$('.pre-lodder').hide();
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                            }
                        }

                    },

                });
            }
			function settingcurrencyedit()
            {
                 
              window.location.href = "<?php echo base_url('customer/settings'); ?>";
            }

			
			function suppliersave()
            {
				
				var CurrencyId = $(".CurrencyLists :selected").attr('value');
                var CurrencyName = $('input[name=CurrencyName]').val();
                var CapitalCost = $('input[name=CapitalCost]').val();
                $('.pre-lodder').show();
                $('.form-group').removeClass('has-error');
                $('.help-block').empty();
                var url;
                url = "<?php echo site_url('supplier/addsettings') ?>";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {CurrencyId: CurrencyId, CurrencyName: CurrencyName, CapitalCost: CapitalCost},
                    dataType: "JSON",
                    success: function (data)
                    {

                        if (data.status)
                        {
                            $('.pre-lodder').hide();
							 $('#addaprsetting').modal('hide');
						    $('.settings_addsup_currencypopup').modal('show');
                           // window.location.href = "<?php echo base_url('supplier/settings'); ?>";
                        } else
                        {
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
								$('.pre-lodder').hide();
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); 
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); 
                            }
                        }

                    },
                });
            }
			
				function settingcurrencysupredirect()
            {
                 
              window.location.href = "<?php echo base_url('supplier/settings'); ?>";
            }
			
			
			function editsuppliersave(SettingId)
            {
                $('.pre-lodder').show();
                $('.form-group').removeClass('has-error');
                $('.help-block').empty();
				var SettingId = SettingId;               
                var EditCurrencyid = $(".EditCurrencyid" + SettingId).val();
                var EditCurrencyName = $('.EditCurrencyName' + SettingId).val();
                var EditCapitalCost = $('.EditCapitalCost' + SettingId).val();
                var url;

                url = "<?php echo site_url('supplier/settingsedit') ?>";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {SettingId: SettingId, EditCurrencyid: EditCurrencyid,
                            EditCurrencyName: EditCurrencyName, EditCapitalCost: EditCapitalCost},
                    dataType: "JSON",
                    success: function (data)
                    {

                        if (data.status)
                        {
                            $('.pre-lodder').hide();
							 $('#settings_apr' +SettingId ).modal('hide');
						    $('.settings_editsup_currencypopup').modal('show');
                           // window.location.href = "<?php echo base_url('supplier/settings'); ?>";
                        } else
                        {
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
								$('.pre-lodder').hide();
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); 
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); 
                            }
                        }

                    },

                });
            }
			
			function settingcurrencysupedit(userid)
            {
                 
               window.location.href = "<?php echo base_url('supplier/settings'); ?>";
            }




            function manualaddsupplierp()
            {

			
			   $('.pre-lodder').show();
                $('.form-group').removeClass('has-error');
                $('.help-block').empty();  
				//$('.remove-errors span:empty').removeClass('help-block');
                var url;
                url = "<?php echo site_url('customer/manualsuppliers') ?>";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: $('#addform').serialize(),
                    dataType: "JSON",
                    success: function (data)
                    {

                        if (data.status)
                        {
                           $('.pre-lodder').hide();
                              $(".upload-manual").addClass("custom-postion-chlid");							
							 $(".upload-manual").html("您已经手动添加了供应商");
                           // window.location.href = "<?php echo base_url('customer/invitesuppliers'); ?>";
                        } else
                        {
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
								$('.pre-lodder').hide();
								  
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
								
								//select span help-block error class set text error string
                            }
                        }

                    },
                });
            }
			
			
			
			 function editcontactsettings(userid)
            {

			
			  // $('.pre-lodder').show();
                $('.form-group').removeClass('has-error');
                $('.help-block').empty();  
				var contactemail = $('input[name=contactemail]').val();
                var contactname = $('input[name=contactname]').val();
                var contactposition = $('input[name=contactposition]').val();
                var contactphone = $('input[name=contactphone]').val();
                var url;
                url = "<?php echo site_url('customer/settinguser') ?>";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {contactemail: contactemail, contactname: contactname, contactposition: contactposition, contactphone: contactphone},
                    dataType: "JSON",
                    success: function (data)
                    {

                        if (data.status)
                        {
                           // $('.pre-lodder').hide();
						   
						   $('#settings_contact').modal('hide');
						    $('.settings_contact_send' + userid).modal('show');
                           // window.location.href = "<?php echo base_url('customer/settings'); ?>";
                        } else
                        {
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
								//$('.pre-lodder').hide();
								  
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
								
								//select span help-block error class set text error string
                            }
                        }

                    },
                });
            }
			function settingpopupsemail(userid)
            {
                 
               window.location.href = "<?php echo base_url('customer/settings'); ?>";
            }

            function updateinvitesupplierpopupp(modelid)
            {
                $('.pre-lodder').show();

                $('.form-group').removeClass('has-error');
                $('.help-block').empty();
                var supplierid = $('.supplierid' + modelid).val();
                var suppliername = $('.suppliername' + modelid).val();
                var contactemails = $('.contactemails' + modelid).val();
                var contactpersons = $('.contactpersons' + modelid).val();
                var contactphones = $('.contactphones' + modelid).val();
                var url;

                url = "<?php echo site_url('customer/popupemailinvitesupplier') ?>";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {modelid: modelid, supplierid: supplierid, suppliername: suppliername, contactemails: contactemails, contactpersons: contactpersons, contactphones: contactphones},
                    dataType: "JSON",
                    success: function (data)
                    {
					if (data.already)                        {
							 
							 $('.pre-lodder').hide();							
							  $('.invitepoup' + modelid).modal('hide');							  
							   $('.show-invite'+ modelid).hide();
							    $('.hide-invite'+ modelid).show();
						}else if (data.status) 	   
                        
                        {
							$('.pre-lodder').hide();
                            $('.invitepoup' + modelid).modal('hide');

                        } else
                        {							
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
                                   $('.pre-lodder').hide();
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                            }
							
							 
                        }

                    },

                });
            }
			
			function plansave()
            {
                $('.pre-lodder').show();
                $('.form-group').removeClass('has-error');
                $('.help-block').empty();
				var CurrencyId = $(".CurrencyListp :selected").attr('value');
                var CurrencyName =$(".CurrencyListp option:selected").text();				
                var CapitalCost = $('input[name=CapitalCost]').val();
                var ExpectAPRRate = $(".ExpectAPRRatep :selected").attr('value');
                var ExpectAPRPercent = $('input[name=ExpectAPRPercentp]').val();
                var url;
                url = "<?php echo site_url('customer/addaprplanpreference') ?>";
                $.ajax({
                    url: url,
                    type: "POST",
                    data:  {CurrencyId: CurrencyId, CurrencyName: CurrencyName, CapitalCost: CapitalCost, ExpectAPRRate: ExpectAPRRate, ExpectAPRPercent: ExpectAPRPercent},
                    dataType: "JSON",
                    success: function (data)
                    {

                        if (data.status)
                        {
                            $('.pre-lodder').hide();							
                            window.location.href = "<?php echo base_url('customer/createplan'); ?>";
                        } else
                        {
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
								$('.pre-lodder').hide();
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                            }
                        }

                    },
                });
            }
			
			 function editsuppliercontactes(userid)
            {

			
			  // $('.pre-lodder').show();
                $('.form-group').removeClass('has-error');
                $('.help-block').empty();  
				 var contactemail = $('input[name=contactemail]').val();
                var contactname = $('input[name=contactname]').val();
                var contactposition = $('input[name=contactposition]').val();
                var contactphone = $('input[name=contactphone]').val();
                var url;
                url = "<?php echo site_url('supplier/settinguser') ?>";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {contactemail: contactemail, contactname: contactname, contactposition: contactposition, contactphone: contactphone},
                    dataType: "JSON",
                    success: function (data)
                    {

                        if (data.status)
                        {
                           // $('.pre-lodder').hide();
						   
						   $('#settings_contact').modal('hide');
						    $('.settings_contact_supplier' + userid).modal('show');
                           // window.location.href = "<?php echo base_url('supplier/settings'); ?>";
                        } else
                        {
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
								//$('.pre-lodder').hide();
								  
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
								
								//select span help-block error class set text error string
                            }
                        }

                    },
                });
            }
			function settingcontactpopups(userid)
            {
                 
               window.location.href = "<?php echo base_url('supplier/settings'); ?>";
            }
			
			
			


        </script>


        <script>
            $(window).on('load', function () {
                $("body").removeClass('preloading');
            });
        </script>

       
       <!-- datatable setting -->
       <script>       
       
       var CONSTANT = {            
       // datatables常量  
       DATA_TABLES : {  
           DEFAULT_OPTION : { // DataTables初始化选项  
               LANGUAGE : {  
                   sProcessing : "处理中...",  
                   sLengthMenu : "显示 _MENU_ 项结果",  
                   sZeroRecords : "没有匹配结果",  
                   sInfo : "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",  
                   sInfoEmpty : "显示第 0 至 0 项结果，共 0 项",  
                   sInfoFiltered : "(由 _MAX_ 项结果过滤)",  
                   sInfoPostFix : "",  
                   sSearch : "搜索:",  
                   searchPlaceholder : "关键字搜索",  
                   sUrl : "",  
                   sEmptyTable : "表中数据为空",  
                   sLoadingRecords : "载入中...",  
                   sInfoThousands : ",",  
                   oPaginate : {  
                       sFirst : "首页",  
                       sPrevious : "上页",  
                       sNext : "下页",  
                       sLast : "末页"  
                   },  
                   oAria : {  
                       sSortAscending : ": 以升序排列此列",  
                       sSortDescending : ": 以降序排列此列"  
                   }  
               },  
               // 禁用自动调整列宽  
               autoWidth : false,  
               // 为奇偶行加上样式，兼容不支持CSS伪类的场合  
               stripeClasses : [ "odd", "even" ],  
               // 取消默认排序查询,否则复选框一列会出现小箭头  
               order : [],  
               // 隐藏加载提示,自行处理  
               processing : false,  
               // 启用服务器端分页  
               serverSide : false,  
               // 禁用原生搜索  
               searching : true  ,
                
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
			aoColumnDefs: [ { "bSortable": false, "aTargets": [ 0 ] }] ,
			language : CONSTANT.DATA_TABLES.DEFAULT_OPTION.LANGUAGE,
			preDrawCallback : CONSTANT.DATA_TABLES.CALLBACKS.PREDRAWCALLBACK, 	
			initComplete : CONSTANT.DATA_TABLES.CALLBACKS.INITCOMPLETE		,	
			drawCallback : CONSTANT.DATA_TABLES.CALLBACKS.DRAWCALLBACK
			
		});
   }
       
   </script>     


<?php
        /*if ($this->session->userdata("logged") == TRUE && $this->session->userdata('UserId')) {
			
			if ($this->session->userdata('Role') == 'customer') {	
		?>


// Set the date we're counting down to
var countDownDate = new Date("<?php echo $stop_date; ?>").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();
    
    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="demo"
    document.getElementById("contdown").innerHTML = "<span>"+days+"<em>Days</em></span>"+"<i>:</i>"+"<span>"+hours+"<em>Hours</em></span>"+"<i>:</i>"+"<span>"+minutes+"<em>Mins</em></span>";
    
    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("contdown").innerHTML = "EXPIRED";
    }
}, 1000);


<?php } 
			
			if ($this->session->userdata('Role') == 'supplier') {
			?>

// Set the date we're counting down to

var supcountDownDate = new Date("<?php echo date('F d, Y').' '.$end ; ?>").getTime();
            // Update the count down every 1 second
            var y = setInterval(function () {

                // Get todays date and time
                var supnow = new Date().getTime();
                // Find the distance between now an the count down date
                var supdistance = supcountDownDate - supnow;
                // Time calculations for days, hours, minutes and seconds
                //var days = Math.floor(supdistance / (1000 * 60 * 60 * 24));
                var suphours = Math.floor((supdistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var supminutes = Math.floor((supdistance % (1000 * 60 * 60)) / (1000 * 60));
                var supseconds = Math.floor((supdistance % (1000 * 60)) / 1000);
                // Output the result in an element with id="demo"
				
                document.getElementById("supcontdown").innerHTML = "<span>"+suphours+"<em>Hours</em></span>"+"<i>:</i>"+"<span>"+supminutes+"<em>Mins</em></span>"+"<i>:</i>"+"<span>"+supseconds+"<em>Sec</em></span>";
				
                // If the count down is over, write some text 
                if (supdistance < 0) {
                    clearInterval(y);
                    document.getElementById("supcontdown").innerHTML = "Bid Time Is Expired";
                }
            }, 1000);

<?php 
			}
			
			}*/ ?>



</body>
</html>