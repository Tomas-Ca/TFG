<?php

session_start();

$_SESSION["mensaje"] = "Sesión cerrada";


session_unset ();

session_destroy ();

header("Location: ../index.php");
exit();
?>