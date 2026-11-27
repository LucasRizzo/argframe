<div class="brand">
<span>Logged as <?php
    echo htmlspecialchars($_SESSION["username"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
?>

</span>
</div>
<form class="navbar-form pull-right" action="index.php?action=logout" method="post">
<button type="submit" value="Submit" class="btn">Logout</button>
</form>
