<?php

class event_calendar extends baseModule {    

    public function eventList( $pConf ){
        $this->eventFileExport($pConf);
        
        $categories = "";
        if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) > 1)
            $categories = "category_rsn IN (".implode($pConf['category_rsn'], ",").") AND ";  
        else if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) == 1)      
            $categories = "category_rsn =".($pConf['category_rsn'][0]+0)." AND ";  
        

        $page = getArgv('e1')+0;
        $page = ($page==0)?1:$page;

        $event_from = $event_to = false;
        $date_limit = '';

        //config date limitation by period
        if($pConf['period'] && $pConf['period'] > 0){
             $event_from = mktime(0,0,0);
             $event_to = $event_from + (60*60*24)*$pConf['period'];            
        }
                
        //config manual date limitation
        if($pConf['eventFrom'])
            $event_from = $pConf['eventFrom'];
            
        if($pConf['eventTo'])
            $event_to = $pConf['eventTo'];                
            
        
        if($event_from)
            $date_limit = " AND event_date > ".$event_from;
        if($event_to)
             $date_limit .= " AND event_date < ".$event_to;
        
             
             
         if(!$pConf['itemsPerPage'])
            $pConf['itemsPerPage'] = 10;    
             
        if( $page == 1 )
            $start = 0;
        else
            $start = ($pConf['itemsPerPage'] * $page) - $pConf['itemsPerPage'];

        $sql = "SELECT n.*, c.title as categoryTitle FROM %pfx%event_calendar n, %pfx%event_calendar_category c 
                WHERE ".$categories."deactivate = 0 AND category_rsn = c.rsn".$date_limit."
                 ORDER BY event_date, title LIMIT $start, ".$pConf['itemsPerPage'];

        $sqlCount = "SELECT count(*) as eventcount
            FROM %pfx%event_calendar n, %pfx%event_calendar_category c
            WHERE ".$categories."deactivate = 0 AND category_rsn = c.rsn".$date_limit;
        $countRow = dbExfetch( $sqlCount, 1 );

        $count = $countRow['eventcount'];
        tAssign( 'count', $count );
        $pages = 1;
        if( $count > 0 && $pConf['itemsPerPage'] > 0 )
            $pages = ceil($count/ $pConf['itemsPerPage'] );

        if( $pConf['maxPages']+0 == 0 )
            $pConf['maxPages'] = $pages;

        tAssign( 'pages', $pages );
        tAssign( 'currentEventPage', $page );

        $list = dbExFetch($sql, DB_FETCH_ALL);        
        if($list)
            tAssign('eventItems', $list);

        tAssign('pConf', $pConf);        
        
        kryn::addCss('event_calendar/css/list.'.$pConf['template'].'.css' );
        kryn::addJs('event_calendar/js/list.'.$pConf['template'].'.js' );
        return tFetch('event_calendar/list/'.$pConf['template'].'.tpl');
      
    }
    
    
    public function eventDatePicker( $pConf ){
        $this->eventFileExport($pConf);
    
        $categories = "";
        if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) > 1)
            $categories = "category_rsn IN (".implode($pConf['category_rsn'], ",").") AND ";  
        else if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) == 1)      
            $categories = "category_rsn =".($pConf['category_rsn'][0]+0)." AND ";  
        
        
        if(getArgv('getEventsInMonth') && getArgv('getEventsFrom') && getArgv('getEventsTo')) {
            $sql = "SELECT FROM_UNIXTIME(event_date, '%Y%m%d') AS form_date 
                    FROM %pfx%event_calendar
                    WHERE ".$categories."deactivate = 0 
                        AND event_date >= ".getArgv('getEventsFrom', 1)." AND event_date <= ".getArgv('getEventsTo', 1)."
                    GROUP BY form_date ORDER BY event_date";
            $dates = dbExFetch($sql, -1);
            $returnDate = array();
            if($dates) {
                foreach($dates as $date) {
                    $returnDate[$date['form_date']] = true;
                }
            }           
            json($returnDate);        
        }    
        

        $page = getArgv('e1')+0;
        $page = ($page==0)?1:$page;

        $event_from = $event_to = false;
        $date_limit = '';        
        
        $pConf['period'] = 1;
        
        //config date limitation by period
        if($pConf['period'] && $pConf['period'] > 0){
             $event_from = mktime(0,0,0);
             $event_to = $event_from + (60*60*24)*$pConf['period'];            
        }               
        
        //request date limitation    
        if(getArgv('event_from'))
            $event_from = getArgv('event_from', 1);
            
        if(getArgv('event_to'))
            $event_to = getArgv('event_to', 1);            
            
        if($event_from)
            $date_limit = " AND event_date > ".$event_from;
        if($event_to)
             $date_limit .= " AND event_date < ".$event_to;
        
            
             
         if(!$pConf['itemsPerPage'] || $pConf['itemsPerPage'] < 1)
            $pConf['itemsPerPage'] = 10;    
             
        

        $sql = "SELECT n.*, c.title as categoryTitle FROM %pfx%event_calendar n, %pfx%event_calendar_category c 
                WHERE ".$categories."deactivate = 0 AND category_rsn = c.rsn".$date_limit."
                 ORDER BY event_date, title LIMIT 0, ".$pConf['itemsPerPage'];       

       
        $list = dbExFetch($sql, DB_FETCH_ALL);        
        if($list)
            tAssign('eventItems', $list);

        tAssign('pConf', $pConf);
        
        if(getArgv('onlyGetElements')){
        
            tAssign('onlyGetElements', true);            
            ob_start();
            print( tFetch('event_calendar/datePicker/'.$pConf['template'].'.tpl'));
            $out = ob_get_contents();
            ob_end_clean();
                       
            json($out);
        }
        kryn::addJs('admin/js/ka.datePicker.js');
        kryn::addCss('event_calendar/css/datePicker.'.$pConf['template'].'.css' );
        kryn::addJs('event_calendar/js/datePicker.'.$pConf['template'].'.js' );
        return tFetch('event_calendar/datePicker/'.$pConf['template'].'.tpl');
      
    }
    
    
    public function eventDetail( $pConf ){
        $this->eventFileExport($pConf);    
    
        $id = getArgv('e2')+0;
        if( $id > 0 ){
            //$cats = implode($pConf['category_rsn'], ",");
            
            $categories = "";
            if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) > 1)
                $categories = "category_rsn IN (".implode($pConf['category_rsn'], ",").") AND ";  
            else if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) == 1)      
                $categories = "category_rsn =".($pConf['category_rsn'][0]+0)." AND ";  
            
            
            
           // $event = dbTableFetch('event_calendar',1, $categories.'rsn = '.$id.' AND deactivate = 0');
            
            $event = dbExFetch("SELECT n.*, c.title as categoryTitle FROM %pfx%event_calendar n, %pfx%event_calendar_category c  
                                        WHERE ".$categories."category_rsn = c.rsn AND n.rsn = ".$id." AND deactivate = 0", 1);
       
            
            if( $event['rsn'] != $id )
                return "[Errror:ext:event_calendar:] Invalid Event id.";  

            tAssign('event', $event);
            tAssign('pConf', $pConf);
            kryn::addCss( 'event_calendar/css/detail/'.$pConf['template'].'.css' );
            return tFetch('event_calendar/detail/'.$pConf['template'].'.tpl');
        }
    }
    
    public function eventMonthView ($pConf) {
        $this->eventFileExport($pConf);
    
    
        function leapYear($year){
            if ($year % 400 == 0 || ($year % 4 == 0 && $year % 100 != 0)) return TRUE;
            return FALSE;
        }
        
                
        function daysInMonth($month = 0, $year = ''){
            $days_in_month    = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
            $d = array("Jan" => 31, "Feb" => 28, "Mar" => 31, "Apr" => 30, "May" => 31, "Jun" => 30, "Jul" => 31, "Aug" => 31, "Sept" => 30, "Oct" => 31, "Nov" => 30, "Dec" => 31);
            if(!is_numeric($year) || strlen($year) != 4) $year = date('Y');
            if($month == 2 || $month == 'Feb'){
                if(leapYear($year)) return 29;
            }
             if(is_numeric($month)){
                if($month < 1 || $month > 12) return 0;
                else return $days_in_month[$month - 1];
             }else{
                if(in_array($month, array_keys($d))) return $d[$month];
                else return 0;
            }
        }
                 
         
        
    
        $monthToView = (getArgv('e1')+0 > 0) ? getArgv('e1')+0 : date('n');
        $yearToView = (getArgv('e2')+0 > 0) ? getArgv('e2')+0 : date('Y');
        
        $toView = array('year' => $yearToView, 'month' => $monthToView, 'today' => mktime(0,0,0));
        tAssign('toView', $toView);
        
        $thisTime = mktime(0,0,0, $monthToView, 1, $yearToView);
        tAssign( 'thisTime', $thisTime );
        
        $firstDay = date('N', mktime(0,0,0, $monthToView, 1, $yearToView));
        
        $days = array();
        
        if( $firstDay > 1 && $pConf['showFullWeeks'] == 1){
            //get days of previous month
            for( $i = $firstDay-2; $i >= 0; $i-- ){
                $days[] = mktime(0,0,0, $monthToView, $i*-1, $yearToView );
            }
        }
        
        $daysInMonth = daysInMonth( $monthToView, $yearToView);
        
        for( $i = 1; $i <= $daysInMonth; $i++ ){
            $days[] = mktime(0,0,0, $monthToView, $i, $yearToView );
        }
        
        $lastDay = date('N', mktime(0,0,0, $monthToView, $daysInMonth, $yearToView));
        if( $lastDay < 7 && $pConf['showFullWeeks'] == 1){
            for( $i = 0; $i < 7-$lastDay; $i++ ){
                $days[] = mktime(0,0,0, $monthToView, $daysInMonth+$i+1, $yearToView );
            }
        }
        
        
        
        $dayEvents = array();        
        
        
        $from = $days[0];
        $to = $days[count($days)-1 ];
        
        $categories = "";
        if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) > 1)
            $categories = "category_rsn IN (".implode($pConf['category_rsn'], ",").") AND ";  
        else if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) == 1)      
            $categories = "category_rsn =".($pConf['category_rsn'][0]+0)." AND ";
        
        $date_limit = " AND event_date >= ".$from." AND event_date <= ".$to;
        
        $sql = "SELECT n.*, c.title as categoryTitle FROM %pfx%event_calendar n, %pfx%event_calendar_category c 
                WHERE ".$categories."deactivate = 0 AND category_rsn = c.rsn".$date_limit."
                 ORDER BY event_date, title";      

       
        $list = dbExFetch($sql, -1);
        if(!empty($list)) {
            foreach($list as $eventItem) {
                $time = mktime(0,0,0, date('m', $eventItem['event_date']), date('d', $eventItem['event_date']), date('Y', $eventItem['event_date']));
                if(!isset($dayEvents[$time]))
                    $dayEvents[$time] = array();
                    
                $dayEvents[$time][] = $eventItem;
            }    
        }
        
        
        tAssign('days', $days);
        tAssign('dayEvents', $dayEvents);
        tAssign('pConf', $pConf);        
       
        kryn::addCss('event_calendar/css/monthView.'.$pConf['template'].'.css' );
        kryn::addJs('event_calendar/js/monthView.'.$pConf['template'].'.js' );
        return tFetch('event_calendar/monthView/'.$pConf['template'].'.tpl');
        
        
        
        
    }
    
    public function upcomingEvents( $pConf ){
        $this->eventFileExport($pConf);    
    
        $categories = "";
        if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) > 1)
            $categories = "category_rsn IN (".implode($pConf['category_rsn'], ",").") AND ";  
        else if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) == 1)      
            $categories = "category_rsn =".($pConf['category_rsn'][0]+0)." AND ";  
        
        $page = getArgv('e1')+0;
        $page = ($page==0)?1:$page;

        
        $event_from = mktime();
        $event_to = false;
        $date_limit = " AND event_date > ".$event_from;

         
        //config manual date limitation           
        if($pConf['eventTo'])
            $event_to = $pConf['eventTo'];                   
           
        if($event_to)
             $date_limit .= " AND event_date < ".$event_to;
        
             
             
        if(!$pConf['eventCount'])
            $pConf['eventCount'] = 10;            
        

        $sql = "SELECT n.*, c.title as categoryTitle FROM %pfx%event_calendar n, %pfx%event_calendar_category c 
                WHERE ".$categories."deactivate = 0 AND category_rsn = c.rsn".$date_limit."
                 ORDER BY event_date, title LIMIT 0, ".$pConf['eventCount'];      

       
        $list = dbExFetch($sql, DB_FETCH_ALL);        
        if($list)
            tAssign('upcomingEventItems', $list);

        tAssign('pConf', $pConf);        
        
        kryn::addJs('admin/js/ka.datePicker.js');
        kryn::addCss('event_calendar/css/upcoming.'.$pConf['template'].'.css' );
        kryn::addJs('event_calendar/js/upcoming.'.$pConf['template'].'.js' );
        return tFetch('event_calendar/upcoming/'.$pConf['template'].'.tpl');
      
    }
    
    
    public function eventFileExport ($pConf) {
         $id = getArgv('e2')+0;      
    
        //check if ical download
        if(getArgv('e3') && getArgv('e3') == 'export' && $id > 0) {
          @ob_end_clean();
           
          $categories = "";
          if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) > 1)
              $categories = "category_rsn IN (".implode($pConf['category_rsn'], ",").") AND ";  
          else if(is_array($pConf['category_rsn']) && count ($pConf['category_rsn']) == 1)      
              $categories = "category_rsn =".($pConf['category_rsn'][0]+0)." AND ";  
          
              $event = dbTableFetch('event_calendar',1, $categories.'rsn = '.$id.' AND deactivate = 0');
          if( $event['rsn'] != $id )
              return "[Errror:ext:publication:] Invalid Event id.";

           header("Content-Type: text/Calendar");
           header("Content-Disposition: inline; filename=calendar.ics"); 

           $event['intro'] = str_replace(array("\n", "\r"), array('\n', ''),html_entity_decode(strip_tags($event['intro'])));
           $event['content'] = str_replace(array("\n", "\r"), array('\n', ''), html_entity_decode (strip_tags($event['content'])));
           $event['event_date'] = gmdate('U', $event['event_date']);
           //check if end date is valid else add 2h to startdate
           if($event['event_date'] > $event['event_date_end']) 
              $event['event_date_end'] = $event['event_date']+60*60*2;           
           else
              $event['event_date_end'] = gmdate('U', $event['event_date_end']);

           tAssign('event', $event);
           echo tFetch('event_calendar/ics/default.tpl');               

           die();
        }else{
            return;
        }
    }
}
?>