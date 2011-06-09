<?php

class downloadCenterItemAdd extends windowAdd {

    public $table = 'dlc_items';

    public $primary = array('item_rsn');
    public $multiLanguage = true;
    
    public $tabFields = array(
        'General' => array(
            'item_headline' => array(
                'label' => 'Title',
                'type' => 'text',
                'empty' => false
            ),
            'cat_rsn' => array(
                'label' => 'Category',
                'type' => 'select',
                'table' => 'dlc_categories',
                'table_label' => 'cat_name',
                'table_key' => 'rsn'
            ),
 	        'preview_image' => array(
                'label' =>  'Preview image for the download',
                'type' => 'fileChooser'
             ),            
            'download_from' => array(
                'label' => 'Download available from',
                'desc' => 'If you do not want to limit the download, leave it empty',
                'type' => 'datetime'                
            ),
            'download_to' => array(
                'label' => 'Download available to',
                'desc' => 'If you do not want to limit the download, leave it empty',
                'type' => 'datetime'
                
            ),            
        ),
        'File' => array(
            'item_name' => array(
                'label' => 'Upload a new file',
                'type' => 'fileUpload'
            ),
            'item_type' => array(
                'label' => 'File type',
                'type' => 'text'
            ),
            'item_copy' => array(
                'label' => 'or Copy an existing one',
                'type' => 'select',
                "multi" => false,
                "sql" => "SELECT I.*, C.cat_name FROM %pfx%dlc_items I JOIN %pfx%dlc_categories C WHERE C.rsn = I.cat_rsn",
                "table_label" => "combined_name",
                "table_id" => "item_rsn",
                "modifier" => "prepeareItemCopySelect"
            )
        ),
        'File description' => array(
            'item_desc' => array(
                'label' => 'File description',
                'type' => 'wysiwyg',
                'width' => 600,
                'height' => 260
            )
        )
    );

    
    public $modifier = 'addDomainLanguage';
    
    public function prepeareItemCopySelect( $pTableItems ){
         foreach($pTableItems as $key => $value) {
             $pTableItems[$key]['combined_name'] = strtoupper($value['lang']).' >> '.$value['cat_name'].' > '.$value['item_headline'].' | '.$value['item_name'];
         }
         array_unshift($pTableItems, array('item_rsn' => 'none', 'combined_name' => 'none'));       
         
         return $pTableItems;
    }
   
    function saveItem(){         

        unset($this->_fields['item_copy']);
        $tableInfo = $this->db[$this->table];

        $sql = 'INSERT INTO %pfx%'.$this->table.' ';
        foreach( $this->_fields as $key => $field ){
            if( $field['fake'] == true ) continue;

            $val = getArgv($key);

            $mod = ($field['add']['modifier'])?$field['add']['modifier']:$field[$key]['modifier'];
            if( $mod ){
                $val = $this->$mod($val);
            }
            
            if( !empty($field['customSave']) ){
                continue;
            }

            if( $field['type'] == 'fileList' ){
                $val = json_encode( $val );
            }

            if( $tableInfo[$key][0] == 'int' || $field['update']['type'] == 'int' )
                $val = $val+0;
            else
                $val = "'".esc($val)."'";

            $values .= "$val,";
            $fields .= "$key,";
        }
        
        if( $this->multiLanguage ){
            $curLang = getArgv('lang', 2);
            $fields .= "lang,";
            $values .= "'$curLang',";
        }
        
        
        $values = substr($values, 0, -1);
        $fields = substr($fields, 0, -1);
        $sql .= " ($fields, created) VALUES ($values, '".time()."') ";

        dbExec( $sql );
        $this->last = database::last_id();
        $_REQUEST[$this->primary[0]] = $this->last;

        //custom saves        
        foreach( $this->_fields as $key => $field ){
            if( !empty($this->fields[$key]['customSave']) ){
                $func = $this->fields[$key]['customSave'];
                $this->$func();
            }
        }
        
        //relations
        foreach( $this->_fields as $key => $field ){
            if( $field['relation'] == 'n-n' ){
                $values = json_decode( getArgv($key) );
                foreach( $values as $value ){
                    $sqlInsert = "
                        INSERT INTO %pfx%".$field['n-n']['middle']."
                        ( ".$field['n-n']['middle_keyleft'].", ".$field['n-n']['middle_keyright']." )
                        VALUES ( '".getArgv($field['n-n']['left_key'])."', '$value' );";
                    dbExec( $sqlInsert );
                }
            }
        } 

        
        if(!getArgv('cat_rsn', 1) || getArgv('cat_rsn', 1)+0 < 1) {
                kLog('downloadCenter', 'No Category Rsn');
                return false;
        }
        
       $catHash = dbExFetch("SELECT hash FROM %pfx%dlc_categories WHERE rsn = ".getArgv('cat_rsn', 1));       
       if(!isset($catHash['hash'])) { 
           kLog('donwloadCenter', 'No Category hash found!');
           return false;
       }    
        

       $catHash = $catHash['hash'];     
        
        
       $catDir = dirname(__FILE__).'/../../upload/downloadCenter/';
       //check if dir already exitst
       if(!is_dir($catDir))
            mkdir($catDir);        
        
       if(!is_dir($catDir.$catHash))
           mkdir($catDir.$catHash);
       
           
           
           
       //check if new file or just a file to copy
       $itemCopy = getArgv('item_copy', 1);
       if($itemCopy && $itemCopy != 'none') {
           $toCopy = dbExFetch("SELECT I.item_name, I.item_type, I.cat_rsn, C.hash FROM %pfx%dlc_items I JOIN %pfx%dlc_categories C WHERE I.item_rsn = ".$itemCopy." AND I.cat_rsn = C.rsn", 1);
           $fileName = $toCopy['item_name'];
           $fileType = $toCopy['item_type'];
           //check if file to copy is present
           if(!is_file(dirname(__FILE__).'/../../upload/downloadCenter/'.$toCopy['hash'].'/'.$fileName)) {
                kLog('donwloadCenter', 'File to copy not present');
                return false;               
           }
           
           
           $finalFileName = $fileName;
           $newPath = dirname(__FILE__).'/../../upload/downloadCenter/'.$catHash.'/'.$fileName;              
           $exist = file_exists( $newPath );
           $_id = 0;
           while( $exist ){
               $extPos = strrpos($fileName,'.');
               $ext = substr($fileName, (strlen($fileName)-$extPos)*-1 );
               $tName = substr($fileName, 0, $extPos );
               $_id++;
               $newName = $tName.'-'.$_id.$ext;
               $newPath = dirname(__FILE__).'/../../upload/downloadCenter/'.$catHash.'/'. $newName;
               $exist = file_exists( $newPath );
               $finalFileName  = $newName;            
           }   
           copy(dirname(__FILE__).'/../../upload/downloadCenter/'.$toCopy['hash'].'/'.$fileName, $newPath);
           $fileCopied = true;
           
       } else {
       
           $fileName = getArgv('item_name');
           $fileName =  str_replace( "..", "", $fileName );
           if(strlen($fileName) < 3)           
               return false;
            
           if(!is_file(dirname(__FILE__).'/../../template/downloadCenter/tempUpload/'.$fileName)) {
               kLog('donwloadCenter', 'File not found in tempUpload');
               return false;
           }
               
            $newPath = dirname(__FILE__).'/../../upload/downloadCenter/'.$catHash.'/'.$fileName;              
            $exist = file_exists( $newPath );
            $_id = 0;
            $renamed = false;
            while( $exist ){
                $extPos = strrpos($fileName,'.');
                $ext = substr($fileName, (strlen($fileName)-$extPos)*-1 );
                $tName = substr($fileName, 0, $extPos );
                $_id++;
                $newName = $tName.'-'.$_id.$ext;
                $newPath = dirname(__FILE__).'/../../upload/downloadCenter/'.$catHash.'/'. $newName;
                $exist = file_exists( $newPath );
                $renamed = true;
            }            
           
            
            rename(dirname(__FILE__).'/../../template/downloadCenter/tempUpload/'.$fileName, $newPath);
       }
        
        $item_fileSize = round(filesize($newPath)/1024);
        
        if($renamed)
            dbExec("UPDATE %pfx%dlc_items SET item_name='".$newName."', item_filesize = ".$item_fileSize.", created = ".time()." WHERE item_rsn=".$this->last);
        else if($fileCopied)
            dbExec("UPDATE %pfx%dlc_items SET item_name='".$finalFileName."', item_type = '".$fileType."', item_filesize = ".$item_fileSize.", created = ".time()." WHERE item_rsn=".$this->last);
        else
            dbExec("UPDATE %pfx%dlc_items SET item_filesize = ".$item_fileSize.", created = ".time()." WHERE item_rsn=".$this->last);
        
        return true;
    }
    
}

?>