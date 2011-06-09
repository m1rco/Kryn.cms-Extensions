
<div style="border-top: 1px solid silver;">
{foreach from=$items item=item}

<div style="border-bottom: 1px solid silver;" class="optiWikiItem">
    <a href="javascript: ;" style="font-size: 13px; text-decoration: none;">{$item.title}</a>
    <a href="javascript: ;" class="arrow"></a>
    <div style="display: none; padding-left: 10px; line-height: 18px">{$item.content|nl2br}<br /><br /></div>
</div>

{/foreach}
</div>
