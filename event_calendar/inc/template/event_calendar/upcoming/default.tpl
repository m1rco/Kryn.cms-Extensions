<div class="eventCalendarUpcomingElements">
  {foreach from=$upcomingEventItems item=item}
      <div class="eventCalendarUpcomingDefaultItem">
          <div class="eventCalendarUpcomingCat">{$item.categoryTitle}</div>
          <h3 class="eventCalendarUpcomingDefaultItemH3">
            <a class="eventCalendarUpcomingDefaultItemLink" href="{$pConf.detailPage|realUrl}/{$item.title|escape:"rewrite"}/{$item.rsn}/" >
                {$item.event_date|date_format:"%d.%m.%Y"} - {$item.title}
            </a>
          </h3>
          <div class="eventCalendarUpcomingDefaultItemContent">
              {$item.intro}
          </div>
          {if $item.content != ""}
              <div class="eventCalendarUpcomingSeperator">               
                  <span class="eventCalendarUpcomingLineLong"> </span> <a href="{$pConf.detailPage|realUrl}/{$item.title|escape:"rewrite"}/{$item.rsn}/">[[more]]</a>
              </div>
              <br style="clear:both;">
          {/if}
      </div>    
  {/foreach}
  
  
  {if $upcomingEventItems|count < 1}
     <h3>[[There are no events for this date]].</h3>
  {/if}
  
</div>

