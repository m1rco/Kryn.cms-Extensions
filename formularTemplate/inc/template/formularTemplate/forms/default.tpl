{addJs file='kryn/mootools-core.js}
<form id="formFormsDefaultTable" method="post" action="{$config.page_id|@realUrl}">
<input type="hidden" name="formularTemplateSent" value="1" />
<table>
    <tr>
        <td width="150">[[First-name]]*</td>
        <td><input type="text" class="text" name="[[First-name]]" /></td>
        <td width="150" rowspan="7">
        </td>
    </tr>
    <tr>
        <td>[[Name]]*</td>
        <td><input type="text" class="text" name="[[Name]]" /></td>
    </tr>
    <tr>
        <td>[[Company]]*</td>
        <td><input type="text" class="text" name="[[Company]]" /></td>
    </tr>
    <tr>
        <td>[[Street]]*</td>
        <td><input type="text" class="text" name="[[Street]]" /></td>
    </tr>
    <tr>
        <td>[[ZIP]] / [[City]]*</td>
        <td><input type="text" class="text" name="[[City]]" /></td>
    </tr>
    <tr>
        <td>[[E-Mail]]*</td>
        <td><input type="text" class="text" name="[[E-Mail]]" /></td>
    </tr>
    <tr>
        <td>[[Phone]]*</td>
        <td><input type="text" class="text" name="[[Phone]]" /></td>
    </tr>
    <tr>
        <td>[[Fax]]*</td>
        <td><input type="text" class="text" name="[[Fax]]" /></td>
    </tr>
    <tr>
        <td>[[Message]]*</td>
        <td><textarea name="[[Message]]"></textarea></td>
    </tr>
    <tr>
        <td></td>
        <td>* [[Mandatory field]]</td>
    </tr>
    <tr>
        <td></td>
        <td>
            <a class="buttonBig" href="javascript: formFormsDefaultTableSubmit();" >[[Send]]</a>
        </td>
    </tr>
</table>
</form>
{literal}
<script type="text/javascript">

var formFormsDefaultTableSubmit = function(){
    var req = ['[[First-name]]', '[[Name]]', '[[Company]]', '[[Street]]', '[[City]]', '[[E-Mail]]', '[[Phone]]', '[[Fax]]'];

    var failed = false;
    req.each(function(item){
        var obj = $('formFormsDefaultTable').getElement('input[name='+item+']');
        if( obj && obj.value == "" ){
            failed = true;
            obj.highlight();
        }
    });
	
	var obj = $('formFormsDefaultTable').getElement('textarea[name=[[Message]]');
    if( obj && obj.get('value') == "" ){
        failed = true;
        obj.highlight();
    }
	

    if( failed == true )
        return;
    
    $('formFormsDefaultTable').submit();
}
</script>
{/literal}
