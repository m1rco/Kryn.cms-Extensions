<div class="rotatorDefault">
    <div class="rotatorDefaultPages">
    {foreach from=$pages item=item}
        <div class="rotatorDefaultPage" id="rotatorDefaultPage.{$item.rsn}" style="display: none;">
            {$item.content}
        </div>
    {/foreach}
    </div>
    <div class="rotatorDefaultNavi">
        <a href="#" id="rotatorDefaultNaviLeft">&lt;</a>
        <a href="">&gt;</a>
    </div>
</div>
