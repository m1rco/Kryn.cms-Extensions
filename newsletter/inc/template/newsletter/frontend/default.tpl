{addJs file="kryn/mootools-core.js"}

<script type="text/javascript">
{literal}
function checkNewsletterDefault(){
    if( $('newsletter.default.name').value == "" || $('newsletter.default.name').value == 'Name / Vorname' ){
       $('newsletter.default.name').highlight();
       return false;
    }
    if( $('newsletter.default.email').value == "" || $('newsletter.default.email').value == 'E-Mail' ){
       $('newsletter.default.email').highlight();
       return false;
    }
    $( 'newsletter.default' ).submit();
}
{/literal}
</script>
<form id="newsletter.default" method="post" >
    {if !$newsletterAnnounced}
    <div style="padding-left: 5px;">
        <input id="newsletter.default.name" type="text" onfocus="if(this.value == 'Name / [[First name]]') this.value=''" class="text"
        name="name" value="Name / Vorname" style="width: 200px; border: 1px solid silver; margin-bottom: 2px;" /><br />
        <input id="newsletter.default.email" type="text" onfocus="if(this.value == 'E-Mail') this.value=''" 
        name="email" class="text" value="E-Mail" style="width: 200px; border: 1px solid silver" /><br />
        <input type="hidden" name="announce" value="1" />
    </div>
    <div style="padding: 5px;">
        [[Please check your e-mails and confirm your subscription after clicking on send]].
    </div>
	 <div style="padding-left: 5px;">
        <input type="hidden" name="newsletterFormId{$pConf.formId}" value="1" />
        <a class="button" onclick="checkNewsletterDefault();" href="javascript:;">[[Send]]</a>
    </div>
	
	
    {/if}
    {if $newsletterConfirmAlready}<div class="error">[[Your e-mail address is already confirmed]].</div>{/if}
    {if $newsletterConfirmError}<div class="error">[[Your activation key is not valid]].</div>{/if}
    {if $newsletterConfirmDone}<div class="notice">[[You have successfully confirmed your newsletter subscription]].</div>{/if}
    {if $newsletterAnnounced}
        <div class="notice">
        	[[Successfully subscribed]].<br />
			[[Now, please confirm your subscription by clicking the link which has been sent to you.]].
        </div>
    {/if}
   
</form>
