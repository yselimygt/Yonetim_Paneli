<?php
$dir=new RecursiveDirectoryIterator('backend/controllers'); 
$ite=new RecursiveIteratorIterator($dir); 
foreach($ite as $f){
    if($f->isFile() && pathinfo($f, PATHINFO_EXTENSION)=='php'){ 
        $c=file_get_contents($f); 
        if(strpos($c, "'events'")!==false && strpos($c, 'boot')!==false) {
            echo $f->getPathname()."\n"; 
        }
    }
}
