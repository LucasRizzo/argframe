<form class="navbar-form pull-right" action="index.php?action=login" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
<input class="span2" type="text" name="username" placeholder="Username" required>
<input class="span2" type="password" name="password" placeholder="Password" required>
<button type="submit" value="Submit" class="btn">Submit</button>
</form>