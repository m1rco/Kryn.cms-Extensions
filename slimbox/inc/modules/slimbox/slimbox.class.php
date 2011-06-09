<?php

class slimbox extends baseModule {
    function __construct(){
        kryn::addJs('kryn/mootools-core.js');
        kryn::addCss('slimbox/slimbox.css'); 
        kryn::addJs('slimbox/slimbox.js'); 
    }
}

?>
