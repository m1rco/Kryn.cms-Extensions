<?php

class downloadCenterCategoryAdd extends windowAdd {

    public $table = 'dlc_categories';

    public $primary = array('rsn');
    public $multiLanguage = true;
    
    public $fields = array(
        'cat_name' => array(
            'label' => 'Name',
            'type' => 'text',
            'empty' => false
        )
    );
    
    function saveItem(){        
        
        parent::saveItem();
        $hash = md5(getArgv('cat_name').'-'.time());
        dbUpdate($this->table,  array('rsn' => $this->last), array('hash'=> $hash));         
            
    	//temp dir
    	 if(!is_dir(dirname(__FILE__).'/../../template/downloadCenter/tempUpload/'))
    		mkdir(dirname(__FILE__).'/../../template/downloadCenter/tempUpload/');
        
        $catDir = dirname(__FILE__).'/../../upload/downloadCenter/';
        //check if dir already exitst
        if(!is_dir($catDir))
             mkdir($catDir);
        
        
        if(!is_dir($catDir.$hash))
            mkdir($catDir.$hash);
        
    }
}

?>
