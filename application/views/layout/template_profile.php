<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $MetaTitle;?></title>
<meta name="robots" content="index, follow" />
<meta name="description" content="<?php echo $MetaDescription;?>" />
<meta name="keywords" content="<?php echo $MetaKeywords;?>" />
<meta name="google-site-verification" content="SLtGTtasVgoQ6SkbkIXO3hstIIKLxFcvNlI8okCcj6c" />
<link rel="shortcut icon" href="<?php echo base_url() ?>images/favicon.ico" type="image/x-icon" /> 
<link href="<?php echo base_url() ?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>css/paging.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>css/demo.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ui.core.js"></script>

</head>
<body>
<div id="wrapper">
  <!-- header section -->
  <?php if($header) echo $header ;?>
  <!-- header section -->
  <!-- breadcrumb section -->
  <?php if($breadcrumb) echo $breadcrumb ;?>
  <!-- breadcrumb section -->
  <!-- header slider section -->
  <?php if($header_search) echo $header_search ;?>
  <!-- header slider section -->
  <!-- mid-section section -->
  <?php //if($left) echo $left;?>
  <?php if($middle) echo $middle;?>
  <!-- mid-section section -->
  <!-- FOOTER SECTION -->
  <?php if($footer) echo $footer;?>
  <!-- footer section-->
</div>
</body>
</html>
