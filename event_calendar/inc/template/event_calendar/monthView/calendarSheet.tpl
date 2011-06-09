<div class="eventCalendarMonthViewCS">
    <h2>{$thisTime|date_format:"%B %Y"}</h2>
    
    <div class="eventCalendarMonthViewCSControls">
        <a {strip}href="
        {if $toView.month == 1}
          {$page.rsn|realUrl}/12/{$toView.year-1}/
        {else}
          {$page.rsn|realUrl}/{$toView.month-1}/{$toView.year}/
        {/if}
        
        "{/strip}>previous month</a>
        
        
        <a {strip}href="
        {if $toView.month == 12}
          {$page.rsn|realUrl}/1/{$toView.year+1}/
        {else}
          {$page.rsn|realUrl}/{$toView.month+1}/{$toView.year}/
        {/if}
        "{/strip}>next month</a>
        
        
        <br />
        <a href="{$page.rsn|realUrl}/{$toView.month}/{$toView.year-1}/">[[previous year]]</a>
        <a href="{$page.rsn|realUrl}/{$toView.month}/{$toView.year+1}/">[[next year]]</a>
    </div>       
    <div class="eventCalendarMonthViewCSBorder">  
              
        <ol class="eventCalendarMonthViewCSCalWeeks">
            {foreach from=$days item=week}
                {assign var="thisWeek" value=$week|date_format:"%W"}
                {if $thisWeek ne $lastWeek}
                    
                    <li>[[CW]]<br />{$thisWeek}</li>
                {/if}
                {assign var="lastWeek" value=$thisWeek}
            {/foreach}
        </ol>
      
        <div class="eventCalendarMonthViewCSCCalDays">
            <div class="eventCalendarMonthViewCSCCalDayTitle">[[Monday]]</div>
            <div class="eventCalendarMonthViewCSCCalDayTitle">[[Tuesday]]</div>
            <div class="eventCalendarMonthViewCSCCalDayTitle">[[Wednesday]]</div>
            <div class="eventCalendarMonthViewCSCCalDayTitle">[[Thursday]]</div>
            <div class="eventCalendarMonthViewCSCCalDayTitle">[[Friday]]</div>
            <div class="eventCalendarMonthViewCSCCalDayTitle">[[Saturday]]</div>
            <div class="eventCalendarMonthViewCSCCalDayTitle">[[Sunday]]</div>
            
          
            <ol class="eventCalendarMonthViewCSCCalDays">
            {foreach from=$days item="day"}
                <li id="{$day}" class="eventCalendarMonthViewCSCCalDayItem{if $day|date_format:"%m" ne $toView.month} eventCalendarMonthViewCSCCalDayItem-othermonth{/if}{if $day == $toView.today} eventCalendarMonthViewCSCCalDayItem-current{/if}">
                    <h3>{$day|date_format:"%d"}</h3>                   
                    {if $dayEvents[$day] && $dayEvents[$day]|count > 0}
		                 {foreach from=$dayEvents[$day] item=dayEvent name=monthViewEventLoop}
			                 <div class="eventCalendarMonthViewCSOneEvent">
			                    {if $pConf.detailPage}
								     <div class="eventCalendarMonthViewCSOneEventTitle">
								          <a href="{$pConf.detailPage|realUrl}/{$item.title|escape:"rewrite"}/{$item.rsn}/" class="eventCalendarMonthViewCSOneEventMoreLink" title="View Event">
								                 {$dayEvent.event_date|date_format:"%H:%M"} - {$dayEvent.title}
										  </a> 
									</div>
								{else}
									<div class="eventCalendarMonthViewCSOneEventTitle">{$dayEvent.title}</div>									
								{/if}					
			              
			                    {if !$smarty.foreach.monthViewEventLoop.last}
			                       <div class="eventCalendarMonthViewCSOneEventSeperator">&nbsp;</div>
			                    {/if}
			                    
			                 </div>
		                 {/foreach}
		            {/if} 
                </li>
            {/foreach}
            </ol>
        </div>
      </div>
    </div>
</div>