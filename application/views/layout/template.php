<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">


        <title><?php echo $this->data['title']; ?></title>

        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/jquery.bxslider.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/style_front.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/style1_front.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/animate.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/sdstyle.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo lang_url(); ?>assets/css/responsive.css">
        
        
        <script src="<?php echo lang_url(); ?>assets/js/jquery-3.2.1.min.js"></script>
        <script src="<?php echo lang_url(); ?>assets/js/bootstrap.min.js"></script>        
        <script src="<?php echo lang_url(); ?>assets/js/jquery.bxslider.min.js"></script>
        
        
    </head>
    <body class="preloading">
    <div class="pre-lodder">
     <img src="<?php echo lang_url(); ?>assets/images/logo_pre.gif">
    </div>

        <!-- header section -->
        <?php if ($header) echo $header; ?>
        <!-- header section -->   
        <!-- search section -->
        <?php if ($middle) echo $middle; ?>
        <!-- search section -->
        <!-- main-section -->

        <!-- main-section -->
        <!-- FOOTER SECTION -->
        <?php if ($footer) echo $footer; ?>
        <!-- footer section-->
     

        <script>
            $(document).ready(function () {
                $('.bxslider').bxSlider();
            });

        </script>


        <script>
            $(document).ready(function () {

                $("#toggle1").click(function () {
                    $(".animation-wrapper").toggleClass("toggled");
                    $(".bullets-top i").addClass('fadeInUp animated');
                    $(".lappy_new").addClass('fadeInUpBig animated');
                    $(".lappy_new").addClass('fadeInUpBig animated');
                    $(".chat-box-wrap").removeClass('wow bounceInUp');
                    $(".chat-box-wrap").removeAttr("data-wow-delay");
                    $(".chat-box-wrap").removeAttr("style");
                    $(".chat-box-wrap").addClass('fadeInUp animated');
                });

            });

            function enquirepopup()
            {
                $('#inqmodal').modal('show');
            }

        </script>	
        <script language="javascript">

            /*$(document).ready(function(){
             $("#enquiryform").click(function(){
             var RequestComment = $('input[name=RequestComment]').val();
             var CompanyName = $('input[name=CompanyName]').val();
             var ContactPerson = $('input[name=ContactPerson]').val();
             var ContactPhone = $('input[name=ContactPhone]').val();
             var ContactEmail = $('input[name=ContactEmail]').val();
             $data = $(this).serialize();
             $.ajax({
             type: 'POST',
             url: '<?php echo base_url('enquiry/index'); ?>',
             data: {RequestComment:RequestComment,CompanyName:CompanyName,ContactPerson:ContactPerson,ContactPhone:ContactPhone,ContactEmail:ContactEmail},
             dataType:'JSON', 
             contentType:"application/x-www-form-urlencoded; charset=UTF-8",						
             success: function (html) {
             //alert(html);							
             if(html[0])	{
             $("#CompanyName").html(html[0]);							
             
             }
             if(html["msg"])	{
             $("#CompanyName").html(html["msg"]);							
             
             }
             
             
             }
             
             });
             });
             });	*/
        </script>



<script>
    $(document).ready(function () {
    $(document).on("scroll", onScroll);
    
    //smoothscroll
    $('.main-menu a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        $(document).off("scroll");
        
        $('a').each(function () {
            $(this).parent('li').removeClass('active');
        })
        $(this).parent('li').addClass('active');
      
        var target = this.hash,
            menu = target;
        $target = $(target);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top - 100
        }, 500, 'swing', function () {
            window.location.hash = target;
            $(document).on("scroll", onScroll);
        });
    });
});

function onScroll(event){
    var scrollPos = $(document).scrollTop();
    $('.main-menu a').each(function () {
        var currLink = $(this);
        var refElement = $(currLink.attr("href"));
        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
            $('main-menu_list li').removeClass("active");
            currLink.parent('li').addClass("active");
        }
        else{
            currLink.parent('li').removeClass("active");
        }
    });
}
</script>

<script>

    $(window).scroll(function() {    
    var scroll = $(window).scrollTop();

     //>=, not <=
    if (scroll >= 200) {
        //clearHeader, not clearheader - caps H
        $("header").addClass("sticky");
    } else {

    $("header").removeClass("sticky");

    }
}); //missing );
</script>


<script>
    $(window).on('load', function(){
        $("body").removeClass('preloading');
    });
</script>


</body>
</html>