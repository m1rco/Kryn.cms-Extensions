<?php

class downloadCenterItemEdit extends windowEdit {

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
                
            )            
        ),
        'File' => array(
            'item_name' => array(
                'label' => 'Upload file',
                'type' => 'fileUpload',
                'empty' => false
            ),
            'item_type' => array(
                'label' => 'File type',
                'type' => 'text',
                'empty' => false
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
    
    
    
    function saveItem(){
        //at first handle file
    
        //check if a new file was uploaded
        if(getArgv('newPictureUploaded') == 'true') {
            //delete old one
            $oldFileName = getArgv('oldFileName');
            $oldFileName =  str_replace( "..", "", $oldFileName );
            if(getArgv('oldCatRsn', 1) && getArgv('oldCatRsn', 1)+0 > 0)
                 $oldCatHash = dbExFetch("SELECT hash FROM %pfx%dlc_categories WHERE rsn = ".getArgv('oldCatRsn', 1));
            if(isset($oldCatHash['hash'])) { 
                $oldCatHash = $oldCatHash['hash'];   
                $filePath = dirname(__FILE__).'/../../upload/downloadCenter/'.$oldCatHash.'/'.$oldFileName;         
                if(file_exists($filePath)) 
                    unlink($filePath);            
            }
            $fileName1 = getArgv('item_name');
            $fileName1 =  str_replace( "..", "", $fileName1 );
            $oldFilePath = dirname(__FILE__).'/../../template/downloadCenter/tempUpload/'.$fileName1;  
    
        //if category was changed
        }else if(getArgv('newPictureUploaded') == 'false' && getArgv('oldCatRsn') != getArgv('cat_rsn')) {
            $oldFileName = getArgv('oldFileName');
            $oldFileName =  str_replace( "..", "", $oldFileName );   
            if(getArgv('oldCatRsn', 1) && getArgv('oldCatRsn', 1)+0 > 0)         
                $oldCatHash = dbExFetch("SELECT hash FROM %pfx%dlc_categories WHERE rsn = ".getArgv('oldCatRsn', 1));
            if(isset($oldCatHash['hash'])) { 
                $oldCatHash = $oldCatHash['hash'];   
                $oldFilePath = dirname(__FILE__).'/../../upload/downloadCenter/'.$oldCatHash.'/'.$oldFileName;                          
            }                  
        }
        
        
        
        if(isset($oldFilePath) && (getArgv('newPictureUploaded') == 'true' || (getArgv('oldCatRsn') != getArgv('cat_rsn')))) {
         //now move file from old to new cat
            $fileName = getArgv('item_name');
            $fileName =  str_replace( "..", "", $fileName );
    
            if(strlen($fileName) > 3)  {  
                if(getArgv('cat_rsn', 1) && getArgv('cat_rsn', 1)+0 > 0)      
                    $catHash = dbExFetch("SELECT hash FROM %pfx%dlc_categories WHERE rsn = ".getArgv('cat_rsn', 1));
                if(isset($catHash['hash'])) {                   
                    $catHash = $catHash['hash'];            
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
                  
                     if(!is_dir(dirname(__FILE__).'/../../upload/downloadCenter/'))
                         mkdir(dirname(__FILE__).'/../../upload/downloadCenter/');
        
        
                    if(!is_dir(dirname(__FILE__).'/../../upload/downloadCenter/'.$catHash.'/'))
                        mkdir(dirname(__FILE__).'/../../upload/downloadCenter/'.$catHash.'/');
                    
                    
                    rename($oldFilePath, $newPath);
                    $_REQUEST['item_filesize'] = round(filesize($newPath)/1024);               
                    if($renamed)
                        $_REQUEST['item_name'] = $_POST['item_name'] = $newName;
                }
            }
        }
        
        //now go on with regular updating process
        parent::saveItem();        

    }
    
}

?>

