<div class="rotator-image-without-scrollbar">
    <div class="rotator-image-default-images" id="rotator-image-default-images">
        {foreach from=$images item=image}
            <a rel="lightbox-{$pConf.id}" href="{$path}{$image.file}"><img src="{$path}{$image.thump}" border="0" /></a>
        {/foreach}
    </div>
    <div style="clear: both;"></div>
</div>