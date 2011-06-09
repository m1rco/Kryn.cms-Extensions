window.addEvent('domready', function(){
    $$('.optiWikiItem').each(function(item){
        var content = item.getElement('div');
        content.setStyles({
            height: 1,
            display: 'block',
            overflow: 'hidden'
        });
        item.getElements('a').addEvent('click', function(e){
            e.stop();
            if( content.getStyle('height').toInt() != 1 ){
                content.tween('height', 1 );
            } else {
                content.tween('height', content.scrollHeight );
            }
        });
    });
});
