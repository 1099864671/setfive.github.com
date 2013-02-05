<?php
$output=shell_exec('/usr/local/php5/bin/php /home/45960/users/.home/domains/election.setfive.com/html/index.php');
$time=time();
$fp=fopen('/home/45960/users/.home/domains/election.setfive.com/html/snapshot/'.$time.'.html','w+');
fwrite($fp,$output);
fclose($fp);