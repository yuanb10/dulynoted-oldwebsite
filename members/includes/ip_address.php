<?php /* Get IP Address of Logged-In Member */ $ip = isset ($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']; ?>
