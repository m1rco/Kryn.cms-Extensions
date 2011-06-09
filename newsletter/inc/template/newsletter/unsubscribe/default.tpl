{addJs file="kryn/mootools-core.js"}

<script type="text/javascript">
var newsletter_unsubscribe_emailtxt = '[[E-mail]]';
{literal}
function newsletter_unsubscribe_submit(){
    if( $('newsletter_unsubscribe_email').value == "" || $('newsletter.default.name').value == newsletter_unsubscribe_emailtxt ){
       $('newsletter_unsubscribe_email').highlight();
       return false;
    }
   
    $( 'newsletter_unsubscribe_form' ).submit();
}
{/literal}
</script>

<form method="post" id="newsletter_unsubscribe_form" action="{$page|@realUrl}">
    <input type="hidden" name="newsletter_unsubscribe_sent" value="1" />
    {if $newsletter_unsubscribe_sent}
        [[We send you a email with the unsubscribe link. Please follow the contained link to unsubscribe now.]]    
    {else}
        <input type="text" class="text" id="newsletter_unsubscribe_email" name="newsletter_unsubscribe_email" value="[[E-mail]]" />
        <a class="button" onclick="newsletter_unsubscribe_submit();" href="javascript:;">[[Unsubscribe]]</a>
    {/if}
</form>
