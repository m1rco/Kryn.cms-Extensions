{capture name=eventCalendarNavi}
    {if $pages > 1 }
    <div class="publicationNewsListDefaultNavi">
        {section name=ecpage start=1 loop=$pages+1 max=$pConf.maxPages}
            {if $currentEventPage == $smarty.section.ecpage.index }
                <span>{$smarty.section.ecpage.index}</span>
            {else}
                <a href="{$page|@realUrl}/{$smarty.section.ecpage.index}/">{$smarty.section.ecpage.index}</a>
            {/if}
        {/section}
    </div>
    {/if}
{/capture}



{$smarty.capture.eventCalendarNavi}


<div class="eventElementsList">

  
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
                  <span class="eventCalendarListLineLong"> </span> <a href="{$pConf.detailPage|realUrl}/{$item.title|escape:"rewrite"}/{$item.rsn}/">[[more]]</a>
              </div>
              <br style="clear:both;">
          {/if}
      </div>    
  {/foreach}
  
  
  {if $eventItems|count < 1}
     <h3>[[There are no events for this date]].</h3>
  {/if}
  </div>

</div>

{$smarty.capture.eventCalendarNavi}

