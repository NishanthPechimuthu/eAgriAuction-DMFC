<?php
$files = glob('../includes/*.php');
foreach ($files as $file) {
    include($file);
}
?>
