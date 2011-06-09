
<div class="eventCalendarDetailCat">{$event.categoryTitle}</div>
<div class="eventCalendarDetailCatDate">{$event.event_date|date_format:"%d.%m.%Y"} - {$event.event_date_end|date_format:"%d.%m.%Y"}</div>
<div class="eventCalendarDetailLocation">[[Location]]:  - {$event.event_location}</div>
<h3 class="eventCalendarDetailDefaultItemH3">{$event.title}</h3>


{$event.intro}
<br />
{$event.content}

<div class="eventCalendarExport"> 
   <a href="{$page.rsn|realUrl}/{$event.title|escape:"rewrite"}/{$event.rsn}/export/" class="eventCalendarExportLink">[[Calendar export]]</a>
</div>

<div class="eventCalendarDetailSeperator">               
    <span class="eventCalendarDetailLineLong"> </span>
    <a href="javascript: history.go(-1);">[[Back]]</a>
</div>
<br style="clear:both;">
