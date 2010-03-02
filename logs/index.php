<?php

$info = pathinfo($_SERVER['REQUEST_URI']);
header('location: ../index.php?c='.$info['basename']);

?>