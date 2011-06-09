<?php
class downloadCenter extends BaseModule {

    
    public function downloadCenterList( $pConf) {
        
    
    	//check if file should be delivered
    	if(getArgv('e1') == 'downloadFile' && (getArgv('e2')+0) > 0) {
    		$sql = "SELECT i.item_name, i.download_count, c.hash FROM %pfx%dlc_items i, %pfx%dlc_categories c WHERE
			i.item_rsn = ".getArgv('e2', 1)."  		
    		AND i.cat_rsn = c.rsn
            AND ( (download_from = 0 OR download_from < ".time().") AND (download_to = 0 OR download_to > ".time().") )";   
    		$item = DbExFetch($sql, 1);
			if($item && isset($item['hash']) && isset($item['item_name']) && is_file(dirname(__FILE__).'/../../upload/downloadCenter/'.$item['hash'].'/'.$item['item_name'])) {
				ob_end_clean();
				dbUpdate('dlc_items', array('item_rsn'=> (getArgv('e2', 1)+0)), array('download_count' => ($item['download_count']+1)));				
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.$item['item_name'].'"');
 
				$filePath = dirname(__FILE__).'/../../upload/downloadCenter/'.$item['hash'].'/'.$item['item_name'];		
			
				readfile($filePath);
				exit();
			}else {
			    if( !$item )
			       kLog('downloadCenter', 'File not found in database.');
			    else if(! is_file(dirname(__FILE__).'/../../upload/downloadCenter/'.$item['hash'].'/'.$item['item_name']) );
			       kLog('downloadCenter', 'File does not exists.');
		    }	    	
    	}
    
        $categories = false;

        if(is_array($pConf['cat_rsn']) && count($pConf['cat_rsn']) > 1) { 
            $categories = implode($pConf['cat_rsn'], ",");
        }else if(is_array($pConf['cat_rsn']) && count($pConf['cat_rsn']) == 1){       
            $categories = $pConf['cat_rsn'][0]+0;   
        }else if(strlen( $pConf['cat_rsn']) > 0){
            $categories = $pConf['cat_rsn'];   
        
        }
        
        
        if(!$pConf['itemsPerPage'] || $pConf['itemsPerPage'] < 1)
            $pConf['itemsPerPage'] = 10;
        

        $page = getArgv('e1')+0;
        $page = ($page==0)?1:$page;

        if( $page == 1 )
            $start = 0;
        else
        	$start = ($pConf['itemsPerPage'] * $page) - $pConf['itemsPerPage'];
        	
       
        $orderBy = 'created';
        if(strlen($pConf['orderBy']) > 2)
        	$orderBy = $pConf['orderBy'];

        	
        $sortSeq = 'DESC';        
        if(strlen($pConf['sortSeq']) > 1)
        	$sortSeq = $pConf['sortSeq'];
        	

       $sql = "SELECT i.*, c.cat_name as categoryTitle FROM %pfx%dlc_items i, %pfx%dlc_categories c WHERE";       
       if($categories)
            $sql .= " i.cat_rsn IN (".$categories.") AND ";             
        $sql .= " i.cat_rsn = c.rsn
                AND ( (download_from = 0 OR download_from < ".time().") AND (download_to = 0 OR download_to > ".time().") )            
                ORDER BY ".$orderBy." ".$sortSeq.", item_name LIMIT $start, ".$pConf['itemsPerPage'];    
        
        
        
        $sqlCount = "SELECT count(*) as dlcItemCount FROM %pfx%dlc_items WHERE";
        if($categories)
            $sqlCount .= " cat_rsn IN (".$categories.") AND";            
        $sqlCount .= " ( (download_from = 0 OR download_from < ".time().") AND (download_to = 0 OR download_to > ".time().") )";

        
        $countRow = dbExfetch( $sqlCount, 1 );         
        
        
        $dlcItemCount = $countRow['dlcItemCount'];
        tAssign( 'dlcItemCount', $dlcItemCount );
        $pages = 1;
        if( $dlcItemCount > 0 && $pConf['itemsPerPage'] > 0 )
            $pages = ceil($dlcItemCount/ $pConf['itemsPerPage'] );

        if( $pConf['maxPages']+0 == 0 )
            $pConf['maxPages'] = $pages;

        tAssign( 'pages', $pages );
        tAssign( 'currentDlclPage', $page );

        $dlcItems = dbExFetch($sql, -1);
        if(is_array($dlcItems) && !empty($dlcItems)) {
            $arSizePostfix = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
            
            foreach($dlcItems as $key => $value) {
                $fSize = $value['item_filesize']*1024;
                
                if ($fSize != 0) 
                    $fSize = round($fSize/pow(1024, ($i = floor(log($fSize, 1024)))), $i > 1 ? 2 : 0) .' '.$arSizePostfix[$i]; 
                
                $dlcItems[$key]['item_fFilesize'] = $fSize;
                $dlcItems[$key]['download_count'] = (int) $dlcItems[$key]['download_count'];               
                
            }
        
        }
        tAssign('dlcItems', $dlcItems);  
        
        
        tAssign('pConf', $pConf);
        kryn::addJs('downloadCenter/js/list.'.$pConf['template'].'.js');
        kryn::addCss('downloadCenter/css/list.'.$pConf['template'].'.css');
        return tFetch('downloadCenter/list/'.$pConf['template'].'.tpl'); 
    
    }



}
