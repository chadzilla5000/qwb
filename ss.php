<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// Set session variables
$_SESSION["favcolor"] = "Wallgreen";
$_SESSION["favanimal"] = "catMandu";
echo "Session variables are set.";
?>
</body>
</html>
