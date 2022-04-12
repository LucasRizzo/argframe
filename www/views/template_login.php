<html lang="en">
<head>
<link rel="icon" href=<?php echo ADDRESS_PREFIX . "graph.png" ; ?>>
<meta charset="utf-8">

<title>Argumentation Framework</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<!-- JS -->
<script src="/js/jquery.js"></script>
<script src='<?php echo JQUERY; ?>'></script>
<!-- Using 3.3.5 for implementing confimation plugin
<script src='<?php //echo BOOTSTRAP_MIN_JS; ?>'></script>
<script src='<?php //echo BOOTSTRAP_JS; ?>'></script> -->
<script src='<?php echo MODALS_JS; ?>'></script>
<script src='<?php echo D3; ?>' charset="utf-8"></script>    
<script src='<?php echo FILE_SAVER; ?>'></script>
<!-- <script src='<?php //echo VALIDATOR; ?>'></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>
<script src='<?php echo JQUERY_CSV; ?>'></script>
<!-- <script type="text/javascript" src='<?php //echo BOOTSTRAP_TOOLTIP; ?>'></script> -->
<!-- bootstrap 3.3.5 for confirmation plugin -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
<script src="/js/bootstrap-confirmation.min.js"></script>

  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->

<!-- CSS -->
<link href="<?php echo BOOTSTRAP_PATH; ?>" rel="stylesheet">
<link href="<?php echo BOOTSTRAP_MIN_PATH; ?>" rel="stylesheet">
<link href="<?php echo GRAPH_STYLE; ?>" rel="stylesheet">
<link href="<?php echo MY_ALTERATIONS; ?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
<style> body { padding-bottom: 70px; } </style>
</head>

<body style="padding-top: 40px; padding-bottom: 40px; background-color: #eee;">
    <br><br><br>
    <div class="container">
        <form class="form-signin" method="post" action=<?php echo ADDRESS_PREFIX . "index.php"; ?>>
            <h2 class="form-signin-heading text-info" style="text-align:center;">Argumentation Framework *</h2>
            <img style="width:60%;margin-left: 20%;" src=<?php echo ADDRESS_PREFIX . "graph.png" ; ?>>
            <br>
            <br>
            <h3 class="form-signin-heading">Please sign in</h3>
                <label for="username" class="sr-only">Email address</label>
                <input type="email" id="username" class="form-control" name="username" placeholder="Email address" required autofocus 
                value="<?php if(isset($_COOKIE["member_login"])) { echo $_COOKIE["member_login"]; }?>">
                <label for="password" class="sr-only">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required value="<?php if(isset($_COOKIE["member_pass"])) { echo $_COOKIE["member_pass"]; }?>">
                <?php
                    if (! empty($view_loggedErr)) {
                        echo "<font color=\"red\">$view_loggedErr</font>";
                    }
                ?>
                
                <div class="checkbox">
                    <label>
                        <!-- TODO implement this functionallity -->
                        <input type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["member_login"])) { ?> checked <?php } ?> />  Remember me
                    </label>
                </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>
        <form class="form-signin" method="post" action=<?php echo ADDRESS_PREFIX . "index.php"; ?>>
            <input type="hidden" hidden id="usernameHidden" class="form-control" name="username" value="Guest">
            <input type="hidden" hidden name="password" id="passwordHidden" class="form-control" value="guest">
            <button class="btn btn-lg btn-success btn-block" type="submit">Enter as a guest</button>
            <br><i>* Works better in Chrome</i>
        </form>
    </div> 
</div>

<!-- TODO implement sign up page! -->

</div> <!-- end container -->

</body>
</html>
