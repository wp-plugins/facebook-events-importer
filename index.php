<?php
// prevent directory listing
header('HTTP/1.0 403 Forbidden');
header("X-Robots-Tag: noindex");
exit;
?>