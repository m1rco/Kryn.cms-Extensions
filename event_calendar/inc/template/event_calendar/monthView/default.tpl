<div class="eventCalendarMonthView">
	<h2>{$thisTime|date_format:"%B %Y"}</h2>
	
	<div class="eventCalendarMonthViewControls">
	 	<a {strip}href="
		{if $toView.month == 1}
          {$page.rsn|realUrl}/12/{$toView.year-1}/
        {else}
          {$page.rsn|realUrl}/{$toView.month-1}/{$toView.year}/
        {/if}
		
		"{/strip}>[[previous month]]</a>
	 	
		
		<a {strip}href="
		{if $toView.month == 12}
		  {$page.rsn|realUrl}/1/{$toView.year+1}/
		{else}
		  {$page.rsn|realUrl}/{$toView.month+1}/{$toView.year}/
		{/if}
		"{/strip}>[[next month]]</a>
		
		
		<br />
		<a href="{$page.rsn|realUrl}/{$toView.month}/{$toView.year-1}/">[[previous year]]</a>
        <a href="{$page.rsn|realUrl}/{$toView.month}/{$toView.year+1}/">[[next year]]</a>
	</div>
	
	<table class="eventCalendarMonthViewTable">
	{foreach from=$days item=day name=monthViewLoop}
	    <tr>
		  <td class="eventCalendarMonthViewDayText">{$day|date_format:"%e %a "} </td>
		  
		  <td class="eventCalendarMonthViewDayEvents">
		  	
		  	{if $dayEvents[$day] && $dayEvents[$day]|count > 0}
			     {foreach from=$dayEvents[$day] item=dayEvent name=monthViewEventLoop}
				 <div class="eventCalendarMonthViewOneEvent">
				 	<div class="eventCalendarMonthViewOneEventTimeTitle">{$dayEvent.event_date|date_format:"%H:%M"} - {$dayEvent.title}</div>
				 	<div class="eventCalendarMonthViewOneEventIntro">{$dayEvent.intro}</div>				
					  
					 {if $pConf.detailPage}           
	                    <div class="eventCalendarMonthViewOneEventMoreLink">
	                    	<a href="{$pConf.detailPage|realUrl}/{$dayEvent.title|escape:"rewrite"}/{$dayEvent.rsn}/">[[more]]</a> 
						</div>
					{/if}            
                    <div class="eventCalendarMonthViewOneEventExportLink">
                    	<a href="{$page.rsn|realUrl}/{$dayEvent.title|escape:"rewrite"}/{$dayEvent.rsn}/export/">[[Calendar export]]</a> 
					</div>            
              
					{if !$smarty.foreach.monthViewEventLoop.last}
					   <div class="eventCalendarMonthViewOneEventSeperator">&nbsp;</div>
					{/if}
					
				 </div>
				 {/foreach}
			{/if}			
		  </td>
	    </tr>
		
		<tr class="eventCalendarMonthViewDaySeperator">
			<td colspan="2"> </td>			
	    </tr>
	{/foreach}
	</table>
	
	

</div>