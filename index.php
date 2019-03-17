<?php 
$Obj = new greetings("Mr. George Weah");

class greetings 
{
    public function __construct($Name) {
         print "Hello, I am". $Name;
    }
}

?>
