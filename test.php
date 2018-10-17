<?php
require_once 'includes/core.php';

echo '<pre>'. print_r(getApi('authencation', array('user' => 'pana', 'pass' => 123456789))) .'</pre>';