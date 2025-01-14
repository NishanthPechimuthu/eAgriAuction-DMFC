<?php
$files = glob('./includes/*.php');
foreach ($files as $file) {
  include_once($file);
}
?>