<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <title>No Google Top</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

<script type="text/javascript" src="./js/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="./js/1.9.1/jquery-ui.min.js"></script> 
    <script type="text/javascript" src="./js/checkpro.js"></script>
   
   
   <script type="text/javascript">
    function selectText(containerid) {
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(containerid));
            range.select();
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(document.getElementById(containerid));
            window.getSelection().addRange(range);
        }
    }
</script>

 
	<link rel="stylesheet" href="./js/css/css.css"/>
    <link rel="stylesheet" href="./js/chosen/chosen.css">
  <style type="text/css" media="all">
    /* fix rtl for demo */
    .chosen-rtl .chosen-drop { left: -9000px; }
  </style>
    
  <!-- Stylesheets -->
  <link href="style/bootstrap.css" rel="stylesheet">
  <!-- Font awesome icon -->
  <link rel="stylesheet" href="style/font-awesome.css"> 
  <!-- jQuery UI -->
  <link rel="stylesheet" href="style/jquery-ui-1.9.2.custom.min.css"> 
  <!-- Calendar -->
  <link rel="stylesheet" href="style/fullcalendar.css">
  <!-- prettyPhoto -->
  <link rel="stylesheet" href="style/prettyPhoto.css">  
  <!-- Star rating -->
  <link rel="stylesheet" href="style/rateit.css">
  <!-- Date picker -->
  <link rel="stylesheet" href="style/bootstrap-datetimepicker.min.css">
  <!-- CLEditor -->
  <link rel="stylesheet" href="style/jquery.cleditor.css"> 
  <!-- Uniform -->
  <link rel="stylesheet" href="style/uniform.default.html"> 
  <!-- Uniform -->
  <link rel="stylesheet" href="style/daterangepicker-bs3.css" />
  <!-- Bootstrap toggle -->
  <link rel="stylesheet" href="style/bootstrap-switch.css">
  <!-- Main stylesheet -->
  <link href="style/style.css" rel="stylesheet">
  <!-- Widgets stylesheet -->
  <link href="style/widgets.css" rel="stylesheet">   
    <!-- Gritter Notifications stylesheet -->
  <link href="style/jquery.gritter.css" rel="stylesheet">   
  
  <!-- HTML5 Support for IE -->
  <!--[if lt IE 9]>
  <script src="js/html5shim.js"></script>
  <![endif]-->

  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon/favicon.png">
</head>

<body>
<header>
<div class="navbar navbar-fixed-top bs-docs-nav" role="banner">
  
    <div class="container">
      <!-- Menu button for smallar screens -->
      <div class="navbar-header">
		  <button class="navbar-toggle btn-navbar" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse"><span>Menu</span></button>
      <a href="#" class="pull-left menubutton hidden-xs"><i class="fa fa-bars"></i></a>
		  <!-- Site name for smallar screens -->
		  <a href="#" class="navbar-brand">CHECKER<span class="bold"> PRO</span></a>
		</div>

      <!-- Navigation starts -->
      <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">         
        
        <!-- Links -->
        <ul class="nav navbar-nav pull-right">
      <li><a style="display: none;" id="kqTong" style="color:#FFF" class="btn btn-s-md btn-default disabled"></a></li>
	  <li><a style="display: none;" id="kqlive" style="color:#FFF" class="btn btn-s-md btn-primary disabled"></a></li>
	  <li><a style="display: none;" id="kqdie" style="color:#FFF" class="btn btn-s-md btn-danger disabled"></a></li>
	  <li><a style="display: none;" id="kqunk" style="color:#FFF" class="btn btn-s-md btn-warning disabled"></a></li> 
        </ul>
      </nav>

    </div>
  </div>
</header>
<!-- Main content starts -->

<div class="content">

  	<!-- Sidebar -->
    
    
          
          
          
          
          <div class="col-md-12">

              <div class="widget" style="min-height:550px;">
                <div class="widget-head">
                  <div class="pull-left">CHECK CCV & CCN</div>
                  <div class="widget-icons pull-right">
                    <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
                 <!--   <a href="#" class="wsettings"><i class="fa fa-wrench"></i></a>  
                    <a href="#" class="wclose"><i class="fa fa-times"></i></a> -->
                  </div>
                  <div class="clearfix"></div>
                </div>

                <div class="widget-content">
                  <div class="padd">

                        <div style="text-align:center"> <h2>Checker CCV & CCN - Số 1 tại Việt Nam</h2>  <br>
<h2>Đông Phương Bất Bại đang bận "làm việc" để kiếm đứa trẻ ! - ICQ: 681285657</h2> </div>
                
                
                
                <form method="post">
	<div align="center">
		<textarea placeholder="5480090177000741|07|2014|371|Darrell Downing|1320 Keyridge Dr|Cincinnati|OH|45240|" name="listcc" id="listcc" cols="60" rows="10" style="width:60%; height:160px"></textarea>
		<textarea placeholder="127.0.0.1:1080" name="socks" id="socks" cols="30" rows="10" style="display:none;width:25%; height:160px" ></textarea><br />
		<b>DELIM:</b> <input type="text" name="delim" id="delim" value="|" size="1" style="width:50px; height:30px" />
		&nbsp;<!-- <input type="checkbox" name="email" id="email"  value="1" /><b>Check Email</b> -->
         | <b>Sleep:</b> <input type="number" name="sleep" id="sleep" value="0" size="1" style="width:50px; height:30px" /><b id='changesocksiffail' hidden=""> | Change socks if fail: <input type="number" name="fail" id="fail" value="10" size="1" style="width:50px; height:30px"/> time(s) </b>
         
         <input type="checkbox" name="checkcosock" id="checkcosock" value="1" /><b>CHECK SOCKS</b>
         
         
          <b id='changesockrandom' hidden=""> <input name="randomsock" type="checkbox" id="randomsock" value="1" checked="CHECKED" /><b>RANDOM SOCK</b></b>
         
         
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select data-placeholder="Chọn List Sock" tabindex="12" name="chonlistsock" id="chonlistsock" style="min-width:200px;display:none">
     <!--  <option value=""></option> -->
          <option value="ssh" selected="selected">Sock SSH</option>
          <option value="ksrv">Sock list VIP</option>
                   
          
        </select>
        
        
        
        <br />
		<input type="checkbox" name="address" id="address" checked="checked" value="1" hidden=""/><b hidden="">GET ADDRESS</b>
		<input type="checkbox" name="card" id="card" checked="checked" value="1" hidden=""/><b hidden="">GET CARD</b>
		<input type="checkbox" name="order" id="order" checked="checked" value="1" hidden=""/><b hidden="">GET ORDER</b>        
        

     &nbsp; 
     
     
     <input type="hidden" id="getsock" value="GET SOCK" class = "submit-button"/>
      <br />
        
       <select data-placeholder="Chọn loại CC" class="chosen-select-deselect" tabindex="12" name="webcheck" id="webcheck" style="min-width:250px;">
                <option value="CC01">CCV & CCN - Charge $5 - No Socks</option>
         
        </select> &nbsp;&nbsp;&nbsp;
        
		<input type="button" class = "submit-button" value=" START " id="submit" />&nbsp;<input type="button" class = "submit-button-stop" value=" PAUSE " id="stop" />

        <br />
        <img id="loading" src="images/clear.gif" />
        <span id="checkStatus"></span>
        <br /><br /><br /><br />
	</div>
</form>
         
                          
                
                    <hr />


<div id="result">

<br />

<fieldset class="fieldset">
        <legend class="tientrinh">Tiến trình:</legend>
        <div id="tientrinh"></div>

    
<div style="width:25%;float:left"><font color="#009933"><strong>Đang kiểm tra <span id="sothutu">0</span> / Tổng số <span id="checktongso"></span> CC</strong></font></div>

<!-- Progress bar holder -->
<div id="progress" style="width:59%;border:1px solid #ccc; float:right"></div>
<!-- Progress information -->
<div id="information" style="width:15%;float:right;font-weight:bold"></div>
    </fieldset>
    







<div class="widget">
                <div class="widget-head">
                  <div class="pull-left">LIVE: <span id="checkerpro_live_count">0</span> 
                                 </div>
                  <div class="widget-icons pull-right">
                    <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
                     <!--   <a href="#" class="wsettings"><i class="fa fa-wrench"></i></a>  
                <a href="#" class="wclose"><i class="fa fa-times"></i></a>-->
                  </div>  
                  <div class="clearfix"></div>
                </div>
                <div class="widget-content">
                  <div class="padd">
                    
                    <div class="error-log sscroll">
               <div id="checkerpro_live"  onclick="selectText('checkerpro_live')"></div>
       
   
                    </div>

                  </div>
                </div>
              </div>
              
              
    


<div class="widget">
                <div class="widget-head">
                  <div class="pull-left">DIE: <span id="checkerpro_die_count">0</span></div>
                  <div class="widget-icons pull-right">
                    <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
                 <!--    <a href="#" class="wsettings"><i class="fa fa-wrench"></i></a>  
                   <a href="#" class="wclose"><i class="fa fa-times"></i></a>-->
                  </div>  
                  <div class="clearfix"></div>
                </div>
                <div class="widget-content">
                  <div class="padd">
                    
                    <div class="error-log sscroll">
                  
       
         <div id="checkerpro_die"></div>
        
   
                    </div>

                  </div>
                </div>
              </div>
  
   
   
   
   
   <div class="widget">
                <div class="widget-head">
                  <div class="pull-left">CAN'T CHECK & BAD SOCKS</div>
                  <div class="widget-icons pull-right">
                    <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
                   <!--  <a href="#" class="wsettings"><i class="fa fa-wrench"></i></a>  
                   <a href="#" class="wclose"><i class="fa fa-times"></i></a>-->
                  </div>  
                  <div class="clearfix"></div>
                </div>
                <div class="widget-content">
                  <div class="padd">
                    
                    <div class="error-log sscroll">
                  
       
         <fieldset class="fieldset">
        <legend class="checkerpro_die_check">CAN'T CHECK: <span id="checkerpro_die_check_count">0</span></legend>
        <div id="checkerpro_die_check"></div>
    </fieldset>
    
    

    <fieldset class="fieldset">
        <legend class="checkerpro_die">Bad Socks:</legend>
        <div id="badsock"></div>
    </fieldset>
        
   
                    </div>

                  </div>
                </div>
              </div>
   
    
    
</div>
                 
                    
                    
                  


                  </div>
                </div>
              </div>                

            </div>
          
          

       
   <!-- Mainbar ends -->	    	
   <div class="clearfix"></div>

</div>
<!-- Content ends -->

<!-- Footer starts -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
            <!-- Copyright info -->
            <p class="copy">Copyright &copy; 2017 | <a href="#">Fixed By Đông Phương Bất Bại</a> </p>
      </div>
    </div>
  </div>
</footer> 	

<!-- Footer ends -->
<!-- Scroll to top -->
<span class="totop"><a href="#"><i class="fa fa-chevron-up"></i></a></span> 

<!-- JS -->
<script src="js/jquery.js"></script> <!-- jQuery -->
<script src="js/bootstrap.js"></script> <!-- Bootstrap -->
<script src="js/jquery-ui-1.9.2.custom.min.js"></script> <!-- jQuery UI -->
<script src="js/fullcalendar.min.js"></script> <!-- Full Google Calendar - Calendar -->
<script src="js/jquery.rateit.min.js"></script> <!-- RateIt - Star rating -->
<script src="js/jquery.prettyPhoto.js"></script> <!-- prettyPhoto -->

<!-- Morris JS -->
<script src="js/raphael-min.js"></script>
<script src="js/morris.min.js"></script>

<!-- jQuery Flot -->
<script src="js/excanvas.min.js"></script>
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.resize.js"></script>
<script src="js/jquery.flot.pie.js"></script>
<script src="js/jquery.flot.stack.js"></script>

<!-- jQuery Notification - Noty -->
<script src="js/jquery.noty.js"></script> <!-- jQuery Notify -->
<script src="js/themes/default.js"></script> <!-- jQuery Notify -->
<script src="js/layouts/bottom.js"></script> <!-- jQuery Notify -->
<script src="js/layouts/topRight.js"></script> <!-- jQuery Notify -->
<script src="js/layouts/top.js"></script> <!-- jQuery Notify -->
<!-- jQuery Notification ends -->

<!-- Daterangepicker -->
<script src="js/moment.min.js"></script>
<script src="js/daterangepicker.js"></script>

<script src="js/sparklines.js"></script> <!-- Sparklines -->
<script src="js/jquery.gritter.min.js"></script> <!-- jQuery Gritter -->
<script src="js/jquery.cleditor.min.js"></script> <!-- CLEditor -->
<script src="js/bootstrap-datetimepicker.min.js"></script> <!-- Date picker -->
<script src="js/jquery.uniform.min.html"></script> <!-- jQuery Uniform -->
<script src="js/jquery.slimscroll.min.js"></script> <!-- jQuery SlimScroll -->
<script src="js/bootstrap-switch.min.js"></script> <!-- Bootstrap Toggle -->
<script src="js/filter.js"></script> <!-- Filter for support page -->
<script src="js/custom.js"></script> <!-- Custom codes -->
<script src="js/charts.js"></script> <!-- Charts & Graphs -->



<script src="./js/chosen/chosen.jquery.js" type="text/javascript"></script>

  <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>

</body>
</html>