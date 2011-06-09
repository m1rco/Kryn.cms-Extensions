<?php

class downloadCenterCategoryList extends windowList {

    public $table = 'dlc_categories';
    public $itemsPerPage = 20;
    public $orderBy = 'cat_name';
    
    public $iconAdd = 'add.png';   
    public $iconDelete = 'cross.png';
    public $multiLanguage = true;
    
    
    public $filter = array('cat_name', 'hash');
    
    public $add = true;
    public $edit = true;
    public $remove = true;
    
    public $primary = array('rsn');
        
    public $columns = array(
        'cat_name' => array(
            'label' => 'Name',
            'type' => 'text'
        ),
        'hash' => array(
            'label' => 'Hash',
            'type' => 'text'
        )
    );
    
  function deleteItem(){         
        $item = getArgv('item');
        $catRsn = $item['rsn'];
        
        $catHash = $item['hash'];
        $catHash =  str_replace( "..", "", $catHash );
        
        if($catRsn) {
              //first delete all items belonging to this category
            dbExec("DELETE FROM %pfx%dlc_items WHERE cat_rsn =".$catRsn);
        }              
        
        //remove cat dir
        if($catHash) {
            $catDir = dirname(__FILE__).'/../../upload/downloadCenter/'.$catHash;      
            if(is_dir($catDir)) {
              $scan = glob(rtrim($catDir.$catHash,'/').'/*');
                foreach($scan as $index=>$path){
                    unlink($path);
                }           
                rmdir($catDir.$catHash); 
            }
        }
        
        //go on with db del
        parent::deleteItem(); 
        return true;
    }

	
    function removeSelected(){    
        $catDir = dirname(__FILE__).'/../../upload/downloadCenter/';
        $selected = json_decode( getArgv('selected'), 1 );
        
        foreach( $selected as $select ){
            $catRsn = $select['rsn'];
            if($catRsn) {
                  //first delete all items belonging to this category
                dbExec("DELETE FROM %pfx%dlc_items WHERE cat_rsn =".$catRsn);
            }         
            $catHash = dbExFetch( "SELECT hash FROM %pfx%".$this->table." WHERE rsn =".$catRsn, 1 );
            if($catHash) {
                $catHash = $catHash['hash'];                    
                if(is_dir($catDir.$catHash)) {
                    $scan = glob(rtrim($catDir.$catHash,'/').'/*');
                    foreach($scan as $index=>$path){
                        unlink($path);
                    }           
                    rmdir($catDir.$catHash); 
                }   
            }          
        }      
           
        parent::removeSelected();        
        return true;
    }

}

?>
