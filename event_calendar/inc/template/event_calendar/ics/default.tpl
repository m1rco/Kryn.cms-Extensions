BEGIN:VCALENDAR
VERSION:2.0
PRODID:{$event.title}
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VEVENT
SUMMARY:{$event.title}
{if $event.event_location|count_characters > 1}LOCATION;ENCODING=QUOTED-PRINTABLE:{$event.event_location}{/if}
X-MICROSOFT-CDO-BUSYSTATUS:BUSY
DESCRIPTION:{$event.intro} \n\n{$event.content}
CLASS:PUBLIC
DTSTAMP:{$smarty.now|date_format:"%Y%m%dT%H%M%S"}
UID:{$smarty.now}
DTSTART:{$event.event_date|date_format:"%Y%m%dT%H%M%S"}
DTEND:{$event.event_date_end|date_format:"%Y%m%dT%H%M%S"}
END:VEVENT
END:VCALENDAR
