<?php
$link = mysql_connect('internal-db.s45960.gridserver.com', 'db45960', 'Free12!@');
if (!$link) {
   die('Could not connect: ' . mysql_error());
}
$db =mysql_select_db('db45960_twitterimage', $link)
        or die('Cannot open Database');


try
    {
   $it = new directoryIterator('snapshot');
        while( $it->valid())
        {
            /*** echo the file name, minus the suffix ***/
            $file=file_get_contents('snapshot/'.$it->getFilename());
            $image=ereg_replace(".*<img src='(.*)' alt='map should be here google blocked us.'  />.*","\\1",$file);
            $query="INSERT INTO images (`date`,`url`) VALUES ('".date('c', $it->getCTime() )."','".$image."');";
            mysql_query($query);
            /*** move to the next element ***/
            $it->next();
        }
    }
    catch(Exception $e)
    {
        /*** echo the error message ***/
        echo $e->getMessage();
    }

mysql_close($link);