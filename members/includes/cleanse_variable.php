<?php /* Clean Bad Characters in Variables */ function cleanse ($variable) { return mysql_real_escape_string(stripslashes(trim($variable))); } ?>
