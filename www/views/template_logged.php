<div class="brand">
<span>Logged as <?php
    echo $_SESSION ["username"];
?>

</span>
</div>
<form class="navbar-form pull-right" action="index.php?action=logout" method="post">
<button type="submit" value="Submit" class="btn">Logout</button>
</form>
