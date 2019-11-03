<?php
$fp=fopen("sockssh.txt",r)or exit("Loi");
while(!feof($fp))
{
      echo fgets($fp);
}
fclose($fp);
?>