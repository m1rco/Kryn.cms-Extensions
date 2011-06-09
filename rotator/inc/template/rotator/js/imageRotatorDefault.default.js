window.addEvent('domready', function(){
    var images = $$('.imageRotatorDefault-default img');
    images.setStyle('opacity', 0);
    images.set('tween', {duration: 1400});
    var pos = 0;
    var last = null;
    var doRotate = function(){
        if( pos >= images.length ){
            pos = 0;
        }
        if( last )
            last.tween('opacity', 0);
        images[pos].tween('opacity', 1);
        last = images[pos];
        pos++;
    }
    doRotate.delay(200);
    doRotate.periodical(3000);
});
