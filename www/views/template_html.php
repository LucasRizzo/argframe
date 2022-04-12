<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" href="graph.png">
<meta charset="utf-8">

<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.0.min.js"></script>

<script>
//paste this code under the head tag or in a separate js file.
    // Wait for window load
    $(window).load(function() {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");;
    });
</script>

<!-- <title><?php //echo TITLE_WEB_APP; ?></title> -->
<title>Argumentation Framework</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<!-- JS -->
<script src= <?php echo ADDRESS_PREFIX . "js/jquery.js"; ?>></script>
<script src='<?php echo JQUERY; ?>'></script>
<!-- Using 3.3.5 for implementing confimation plugin
<script src='<?php //echo BOOTSTRAP_MIN_JS; ?>'></script>
<script src='<?php //echo BOOTSTRAP_JS; ?>'></script> -->
<script src='<?php echo MODALS_JS; ?>'></script>
<script src='<?php echo D3; ?>'></script> 
<script src='<?php echo FILE_SAVER; ?>'></script>

<!-- <script src='<?php //echo VALIDATOR; ?>'></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src='<?php echo ADDRESS_PREFIX . "js/papaparse.js"; ?>'></script>
<script src='<?php echo REQUIRE_JS; ?>'></script>
<script src='<?php echo BOOLEAN_PARSER; ?>'></script>
<script src='<?php echo JQUERY_CSV; ?>'></script>
<!-- <script type="text/javascript" src='<?php //echo BOOTSTRAP_TOOLTIP; ?>'></script> -->
<!-- bootstrap 3.3.5 for confirmation plugin -->
<script src=<?php echo ADDRESS_PREFIX . "style/bootstrap/js/bootstrap.js"; ?>></script>
<script src=<?php echo ADDRESS_PREFIX . "js/bootstrap-confirmation.min.js"; ?>></script>
<script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/autoresize.jquery.js"></script>

  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->

<!-- CSS -->
<link href="<?php echo BOOTSTRAP_PATH; ?>" rel="stylesheet">
<link href="<?php echo BOOTSTRAP_MIN_PATH; ?>" rel="stylesheet">
<link href="<?php echo GRAPH_STYLE; ?>" rel="stylesheet">
<link href="<?php echo MY_ALTERATIONS; ?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
<!-- <link rel="stylesheet" id="font-awesome-css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" type="text/css" media="screen"> -->

<script src='<?php echo BOOTSTRAP_EDITABLE_JS; ?>'></script>
<link href="<?php echo BOOTSTRAP_EDITABLE; ?>" rel="stylesheet">
<script src=<?php echo ADDRESS_PREFIX . "js/bootstrap3-alert-box.js"; ?>></script>

<style>

    body { 
        padding-bottom: 0px; 
    }

    .affix {
      top:0;
      width: 100%;
      z-index: 9999 !important;
    }

  </style>
</head>

<body>

<!--  Header and menu -->
<nav class="navbar navbar-default" > <!-- Inverse colors -->
<div class="container-fluid"> <!-- Spam vertically -->
  <div class="navbar-header">
    <!-- Button for resize bar in small screens -->
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span> 
    </button>
    <a class="navbar-brand" href=<?php echo ADDRESS_PREFIX . "index.php"; ?>><img style="max-width:50px; margin-top: -12px;" src="graph.png"></a>
  </div>
  <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
<!--         <li class="active"><a href=<?php //echo ADDRESS_PREFIX . "index.php"; ?>>Tutorial</a></li> -->
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Feature set
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
<!--             <li><a href=<?php //echo ADDRESS_PREFIX . "index.php?action=create"; ?>>Add/Edit feature set</a></li>  -->
<!--             <li><a href=<?php //echo ADDRESS_PREFIX . "index.php?action=createjson"; ?>>Create feature set with a JSON file</a></li> -->
            <li><a href=<?php echo ADDRESS_PREFIX . "index.php?action=editFeatureset"; ?>>Add/Edit feature set</a></li>
            <li><a href=<?php echo ADDRESS_PREFIX . "index.php?action=print"; ?>>Print feature set and rules</a></li>
            <!-- <li><a href=<?php //echo ADDRESS_PREFIX . "index.php?action=database"; ?>>Example feature set</a></li> -->
          </ul>
        </li>

        <li class="dropdown">
        <li><a href=<?php echo ADDRESS_PREFIX . "index.php?action=graph"; ?>>Graphs</a></li>  
        <li><a href=<?php echo ADDRESS_PREFIX . "index.php?action=compute"; ?>>Compute graph</a></li>
        <li><a href="#" data-toggle="modal" data-target="#modalHelp">Help</a></li>

        <li class="dropdown">
        <!-- <a class="dropdown-toggle" data-toggle="dropdown" href="#">Export
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href=<?php //echo ADDRESS_PREFIX . "index.php?action=export"; ?>>Export results</a></li>
            <li><a href=<?php //echo ADDRESS_PREFIX . "index.php?action=exportjson"; ?>>Export feature as a JSON file</a></li>
          </ul>
        </li> -->

        <!--
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Visualizations
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href=<?php //echo ADDRESS_PREFIX . "index.php/graph"; ?>>Graphs</a></li> 
            <li><a href=<?php //echo ADDRESS_PREFIX . "index.php/featureset"; ?>>Feature sets</a></li>
          </ul>
        </li> -->
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="navbar-text">Logged as <span class="bg-info "><?php echo $_SESSION["username"]; ?></span></li>
        <li><a href=<?php echo ADDRESS_PREFIX . "index.php/login"; ?>><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
  </div>
</div>
</nav> <!-- end menu -->

<div class="se-pre-con"></div>

<div class="container-fluid">

    <div class="row-fluid">
    
        <div id="messages" class="hide" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div id="messages_content"></div>
        </div>

        <?php

        if ($view_apiResponse != "NULL") {

            if ($view_slimStatus == HTTPSTATUS_OK) {

                if ($view_apiVisualization == VIEW_GRAPH) {
                    include ("template_graph_visualization.php"); 
                }

                if ($view_apiVisualization == VIEW_FEATURESET) {
                    include ("template_featureset_visualization.php"); 
                }

            } else if ($view_slimStatus == HTTPSTATUS_NOTFOUND) {

                ?> <script>
                // Generic error modal included in the index page
                $(function(){
                    $('#messages').removeClass('hide').addClass('alert alert-danger alert-dismissible fade in').slideDown().show();
                    $('#messages_content').html('<h4> <strong>Error!</strong> <?php echo $view_apiResponse["message"]; ?> </h4>');
                    $('#modal').modal('show');
                });
                </script> <?php
            }
        } else {

            if ($view_error != "NULL") {
                if ($view_errorType = "create") {
                    // Create errors are included in the template_create jquery
                    include ("template_create.php"); 
                } else {
                    ?> <script>
                    // Generic error modal included in the index page
                    $(function(){
                        $('#messages').removeClass('hide').addClass('alert alert-danger alert-dismissible fade in').slideDown().show();
                        $('#messages_content').html('<h4> <strong>Error!</strong> <?php echo $view_error; ?> </h4>');
                        $('#modal').modal('show');
                    });
                    </script> <?php
                }
            } else {
                if ($view_success != "NULL") {
                    if ($view_successType != "createGraph" && $view_successType != "deleteGraph") {
                        ?> <script>
                            $(function(){
                                $('#messages').removeClass('hide').addClass('alert alert-success alert-dismissible fade in').slideDown().show();
                                $('#messages_content').html('<h4><?php echo $view_success; ?> </h4>');
                                $('#modal').modal('show');
                            });
                        </script> <?php
                    } else {
                        // New graph sucess is included in the template graph 
                        include ("template_graph.php");
                    }
                } else {
                    switch ($view_action) {
                        case "database" :
                            include ("template_featureset.php"); 
                            break;
                        case "graph" :
                            include ("template_graph.php");
                            break;
                        case "create" :
                            include ("template_create.php"); 
                            break;
                        case "createjson" :
                            include ("template_createjson.php"); 
                            break;
                        case "compute" : 
                            include ("template_compute.php"); 
                            break;
                        case "exportjson" :
                            include ("template_export_featureset.php"); 
                            break;
                        case "print" :
                            include ("template_print.php"); 
                            break;
                        case "editFeatureset" :
                            include ("template_edit_featureset.php"); 
                            break;
                        default :
                            //include ("template_tutorial.php");
                            include ("template_edit_featureset.php"); 
                    }
                }
            }
        }
        ?>
    </div>
    <!-- Footer fixed at the bottom -->
    <!-- <div class="navbar navbar-fixed-bottom"> <?php //echo $view_footer;?> </div> -->
</div> <!-- end container -->


<a href="#" class="back-to-top" style="display: inline;">
 
<i class="glyphicon glyphicon-circle-arrow-up"></i>
 
</a>

</body>

<div class="modal fade " id="modalHelp" tabindex="-1" role="dialog" aria-labelledby="modalHelp" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Help</h4>
            </div>
            <div class="modal-body">
                <p>This argumentation framework has been proposed as a tool to perform automated reasoning with numerical data. It is able to use boolean logic for the creation of if-then rules and attacking rules. In turn, these
                rules can be activated by data, have their attacks solved, and finally aggregated in different fashions in order to produce a prediction (a number). This process works in the following order:
                <ul>
                <li> <b>(1)</b> feature set creation; </li>
                <li> <b>(2)</b> creation of rules and attacks employing the features created in (1), which results in an <i>argumentation graph</i>; </li>
                <li> <b>(3)</b> instantiation of graph(s) from (2) with numerical data and computation of predictions. </li> 
                </ul>
                Each step can be executed in the pages linked in the top bar.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

jQuery(document).ready(function() {
    var offset = 250;
    var duration = 300;
    jQuery('.back-to-top').fadeOut(0);

    jQuery(window).scroll(function() {

        if (jQuery(this).scrollTop() > offset) {
            jQuery('.back-to-top').fadeIn(duration);
        } else {
            jQuery('.back-to-top').fadeOut(duration);
        }
    });

    jQuery('.back-to-top').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});

</script>

</html>
