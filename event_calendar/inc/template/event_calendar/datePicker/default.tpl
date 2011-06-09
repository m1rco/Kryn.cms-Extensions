{if !$onlyGetElements}
{addJs file="kryn/mootools-core.js"}
{addJs file="kryn/mootools-more.js"}
<script>var pageUrl = '{$page|@realUrl}';</script>
<div id="calendar"></div>

<div id="eventElements">
{/if}  
  
  <div class="eventCalendarListDefault">
  {foreach from=$eventItems item=item}
      <div class="eventCalendarListDefaultItem">
          <div class="eventCalendarListCat">{$item.categoryTitle}</div>
          <h3 class="eventCalendarListDefaultItemH3">
            <a class="eventCalendarListDefaultItemLink" href="{$pConf.detailPage|realUrl}/{$item.title|escape:"rewrite"}/{$item.rsn}/" >
                {$item.event_date|date_format:"%d.%m.%Y"} - {$item.title}
            </a>
          </h3>
          <div class="eventCalendarListDefaultItemContent">
              {$item.intro}
          </div>
          {if $item.content != ""}
              <div class="eventCalendarListSeperator">               
                  <span class="eventCalendarListLineLong"> </span> <a href="{$pConf.detailPage|realUrl}/{$item.title|escape:"rewrite"}/{$item.rsn}/">more</a>
              </div>
              <br style="clear:both;">
          {/if}
      </div>    
  {/foreach}
  
  
  {if $eventItems|count < 1}
     <h3>There are no events for this date.</h3>
  {/if}
  </div>
{if !$onlyGetElements}  
</div>
{/if}
