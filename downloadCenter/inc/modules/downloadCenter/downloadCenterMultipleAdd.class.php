<?php

class downloadCenterMultipleAdd extends windowAdd {

    public $table = 'dlc_items';

    public $primary = array('item_rsn');
    public $multiLanguage = true;
    
    public $tabFields = array(
        'General' => array(            
            'cat_rsn' => array(
                'label' => 'Category',
                'type' => 'select',
                'table' => 'dlc_categories',
                'table_label' => 'cat_name',
                'table_key' => 'rsn'
            ),
            'preview_image' => array(
                'label' =>  'Preview image for all files',
                'type' => 'fileChooser'
             ),            
            'download_from' => array(
                'label' => 'Downloads available from',
                'desc' => 'If you do not want to limit the download, leave it empty',
                'type' => 'datetime'                
            ),
            'download_to' => array(
                'label' => 'Downloads available to',
                'desc' => 'If you do not want to limit the download, leave it empty',
                'type' => 'datetime'
                
            ),           
        ),
        'Files' => array(
            'multi_upload' => array(
                'label' => 'Upload new file( s )',
                'type' => 'multiUpload',
                'savepath' => '/downloadCenter/tempUpload/',
                'uploadpath' => 'admin/backend/window/sessionbasedFileUpload/',
                'empty' => false,
                'fileNameConverter' => 'dlcFileNameConvert',
                'onUpload' => 'blablubFunc',
                'childs' => array(
                    'item_headline' => array(
                        'label' => 'Title',
                        'type' => 'text',
                        'empty' => false
                    ),
                    'item_type' => array(
                        'label' => 'File type',
                        'type' => 'text',
                        'empty' => false
                    )
                )
            )
        ),
        'File description' => array(
            'item_desc' => array(
                'label' => 'Description for all files',
                'type' => 'wysiwyg',
                'width' => 600,
                'height' => 260
            )
        )
    );

  function saveItem(){
        global $user;
        $tableInfo = $this->db[$this->table];
        
        //collect global data
        $cat_rsn = getArgv('cat_rsn', 1)+0;
        $download_from = getArgv('download_from', 1)+0; 
        $download_to = getArgv('download_to', 1)+0; 
        $item_desc = getArgv('item_desc', 1);
        $preview_image  = getArgv('preview_image', 1);
        
        
       $catHash = dbExFetch("SELECT hash FROM %pfx%dlc_categories WHERE rsn = ".$cat_rsn, 1);
       if(!isset($catHash['hash'])) 
            return false;         
           
       $catHash = $catHash['hash'];

       //now loop through files
       foreach($_POST['multi_upload'] as $key => $value) {
           $fileNum = esc($key);
           
           $fileName = esc($value['name']);
           $fileName =  str_replace( "..", "", $fileName );
           
           $fileHeadline = esc($value['item_headline']);
           $fileType = esc($value['item_type']);
           
           if(strlen($fileName) < 3) 
                continue;

                
           $catDir = dirname(__FILE__).'/../../upload/downloadCenter/';
           $newPath = $catDir.$catHash.'/'.$fileName;

           //check if dir already exitst
           if(!is_dir($catDir))
               mkdir($catDir);
        
        
           if(!is_dir($catDir.$catHash))
               mkdir($catDir.$catHash);
           
           
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
           
           rename(dirname(__FILE__).'/../../template/downloadCenter/tempUpload/'.$user->sessionid.'/'.$fileName, $newPath);                      
           $item_fileSize = round(filesize($newPath)/1024);       
           if($renamed)
                $fileName = $newName;
                
           dbExec("INSERT INTO %pfx%dlc_items 
                    (cat_rsn, item_name, item_headline, item_desc, item_type, item_filesize, download_from, download_to, preview_image, created)
                    VALUES (".$cat_rsn.", '".$fileName."', '".$fileHeadline."', '".$item_desc."', '".$fileType."', ".$item_fileSize." , ".$download_from.", ".$download_to.", '".$preview_image."', ".time().")
                  ");  
       }       
        
       return true;
    }    
   
}

?>