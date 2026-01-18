<?php
session_start();
session_destroy();
// Ne brišemo košaricu iz baze jer je povezana s korisnikom
header("Location: ../index.php");
exit();