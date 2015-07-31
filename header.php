<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>NationBuilder Various API example</title>
<script src="jquey/lib/jquery.js"></script>
<script src="jquey/dist/jquery.validate.js"></script>
<script>
	$(document).ready(function() {
		$( "#user_addnew" ).validate({
  rules: {
    fname: {
      required: true
    },
	lname: {
      required: true
    },
	email: {
      required: true,
	  email: true
    }

  }
});

$( "#user_addnew_event" ).validate({
  rules: {
    name: {
      required: true
    },
	slugname: {
      required: true
    },
	vname: {
      required: true
    }

  }
});

	});
	</script>
<style>
body { font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; background-color:#fafafa;}
a{ text-decoration:none; border:none; color:#000;}
a:hover{ border-bottom:1px solid #000;}
.container{ width:1000px; margin:auto;}
h1.title{ text-align:center; padding:40px 0px 20px;}
ul li{ list-style-type:lower-roman; font-size:20px;}
.navigator{ padding:10px 0px;}
</style>
</head>
<body>
<div class="container">
<h1 class="title">NationBuilder Various API example</h1>
