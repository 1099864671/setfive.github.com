<?php
$link = mysql_connect('internal-db.s45960.gridserver.com', 'db45960', 'Free12!@');
if (!$link) {
   die('Could not connect: ' . mysql_error());
}
$db =mysql_select_db('db45960_twitterimage', $link)
        or die('Cannot open Database');
$query="SELECT * FROM images ORDER BY DATE ASC;";
$result=mysql_query($query);
$counter=1;
while($image = mysql_fetch_assoc($result)){
  $file=fopen('images/'.$counter.'.png','w+');
  fwrite($file,file_get_contents($image['url']));
  fclose($file);
  $counter++;

}
mysql_close($link);