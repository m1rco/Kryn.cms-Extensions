<?php

class downloadCenterItemList extends windowList {
    
    public $table = 'dlc_items';
    public $itemsPerPage = 20;
   // public $orderBy = 'created'; 
    public $orderBy = 'item_name'; 
    
    public $iconAdd = 'add.png';   
   // public $iconDelete = 'cross.png';
    public $iconCustom = 'inc/template/admin/images/icons/arrow_divide.png';
    
    public $multiLanguage = true;
    public $filter = array('item_name', 'item_type', 'item_headline');
    
    public $add = true;
    public $edit = true;
    public $remove = true;
    public $custom = array(
        'name' => 'Multiple file add',
        'module' => 'downloadCenter',
        'code' => 'downloadCenter/multiadd'    
    );    
    
    public $primary = array('item_rsn');
        
    public $columns = array(
        'item_name' => array(
            'label' => 'Name',
            'type' => 'text'
        ),
        
         'cat_rsn' => array(
            'label' => 'Category',
            'type' => 'select',
            'table' => 'dlc_categories',
            'table_label' => 'cat_name',            
            'table_key' => 'rsn'
        ),
       
        'item_headline' => array(
            'label' => 'Title',
            'type' => 'text'
        ),
         'item_type' => array(
            'label' => 'Type',
            'type' => 'text'
        ),
         'item_filesize' => array(
            'label' => 'Filesize ( kb )',
            'type' => 'int'
        ),
         'download_count' => array(
            'label' => 'Downloads total',
            'type' => 'int'
        )
    );
    
    
    
     //continue from here 
    function deleteItem(){
        
        $item = getArgv('item');
        $fileName = $item['item_name'];
        $fileName =  str_replace( "..", "", $fileName );
        
        $itemRsn = $item['item_rsn'];       
        
        //get cat hash
        $catHash = dbExFetch("SELECT CAT.hash 
            FROM %pfx%dlc_categories CAT 
            JOIN %pfx%dlc_items IT 
            WHERE IT.cat_rsn = CAT.rsn AND IT.item_rsn = ".$itemRsn, 1);      
        if($catHash) {
            $catHash = $catHash['hash'];
            //check if file
            $filePath = dirname(__FILE__).'/../../upload/downloadCenter/'.$catHash.'/'.$fileName;         
            if(file_exists($filePath)) 
                 unlink($filePath);
        }

        //go on with regular stuff
        parent::deleteItem();
    
    }
    
   
    function removeSelected(){
        
        $selected = json_decode( getArgv('selected'), 1 );
        
        foreach( $selected as $select ){        
       
            $itemRsn = $select['item_rsn'];
            $catHash = dbExFetch("SELECT CAT.hash, IT.item_name  
                    FROM %pfx%dlc_categories CAT 
                    JOIN %pfx%dlc_items IT 
                    WHERE cat_rsn = CAT.rsn AND IT.item_rsn = ".$itemRsn, 1);            
          
            if($catHash) {
                $fileName = $catHash['item_name'];
                $catHash = $catHash['hash'];                
                $filePath = dirname(__FILE__).'/../../upload/downloadCenter/'.$catHash.'/'.$fileName;         
                if(file_exists($filePath)) 
                    unlink($filePath);
                }
            }        
        
        //check if file
        parent::removeSelected();       
        return true;
    }
}

?>
