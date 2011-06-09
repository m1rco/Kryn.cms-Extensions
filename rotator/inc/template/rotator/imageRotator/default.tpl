<div class="rotator-image-default">
    <img class="rotator-image-default-toLeft" src="{$path}inc/template/rotator/images/rotator-image-default-arrowLeft.jpg">
    <div class="rotator-image-default-images" id="rotator-image-default-images">
        {foreach from=$images item=image}
            <a rel="lightbox-{$pConf.id}" href="{$path}{$image.file}"><img src="{$path}{$image.thump}" border="0" /></a>
        {/foreach}
    </div>
    <img class="rotator-image-default-toRight" src="{$path}inc/template/rotator/images/rotator-image-default-arrowRight.jpg">
    <div style="clear: both;"></div>
</div>
