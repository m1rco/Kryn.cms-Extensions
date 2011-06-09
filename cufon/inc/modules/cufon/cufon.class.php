<?php

class cufon extends baseModule {

    function __construct(){
        global $kryn, $cfg;

        kryn::addJs('cufon/cufon-yui.js');
        $kryn->htmlBodyEnd .= '<script type="text/javascript" src="'.$cfg['path'].'inc/template/cufon/cufon-init.js"></script>';
    }

}

?>
