{capture name=dlcListNavi}
    {if $pages > 1 }
    <div class="dlc-list-navi">
        {section name=dlcListPage start=1 loop=$pages+1 max=$pConf.maxPages}
            {if $currentDlclPage == $smarty.section.dlcListPage.index }
                <span>{$smarty.section.dlcListPage.index}</span>
            {else}
                <a href="{$page|@realUrl}/{$smarty.section.dlcListPage.index}/">{$smarty.section.dlcListPage.index}</a>
            {/if}
        {/section}
    </div>
    {/if}
{/capture}



{if $pConf.pageNumbering == 1}
	{$smarty.capture.dlcListNavi}
{/if}

{foreach from=$dlcItems item=dlcItem}
	<div class="dlc-item">
		<h4 class="dlc-item-headline">{$dlcItem.item_headline}</h4>
		<div class="dlc-item-subhead">
			[[Category]]: <b>{$dlcItem.categoryTitle}</b> | {$dlcItem.item_fFilesize} | [[Created]]: {$dlcItem.created|@date_format:"%d.%m.%Y"}
			 | [[Downloaded]]: <b>{$dlcItem.download_count}</b> [[times]]
		</div>
		{if $dlcItem.preview_image|count_characters > 0}
		 <div class="dlc-item-preview-image">
			 <a href="{$page|@realUrl}/downloadFile/{$dlcItem.item_rsn}/{$dlcItem.categoryTitle|escape:"rewrite"}/{$dlcItem.item_name|escape:"rewrite"}" title="[[download]] {$dlcItem.item_name}" target="_blank">
				<img src="inc/template/{$dlcItem.preview_image}" alt="preview" />
			 </a>
		 </div>
		{/if}


		<div class="dlc-item-desc">					
			{$dlcItem.item_desc}
		</div>
		<div style="clear:both;"></div>
		<div class="dlc-item-download">
			 [[Download]]: 
			 <a href="{$page|@realUrl}/downloadFile/{$dlcItem.item_rsn}/{$dlcItem.categoryTitle|escape:"rewrite"}/{$dlcItem.item_name|escape:"rewrite"}" title="[[download]] {$dlcItem.item_name}" target="_blank">
				    {$dlcItem.item_name}
					<img src="downloadCenter/icons/{$pConf.iconDir}/{$dlcItem.item_type}.png" alt="File format {$dlcItem.item_type}" />			
			 </a>
		 </div>
	</div>

{/foreach}

{if $pConf.pageNumbering == 1}
	{$smarty.capture.dlcListNavi}
{/if}
