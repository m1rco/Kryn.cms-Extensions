var rotatorImageDefault = {};

window.addEvent('domready', function(){
    var objs = $$('.rotator-image-default');
    if( objs.length > 1 ){
        objs.each(function(item){
            rotatorImageDefaultInit( item );
        });
    } else {
        rotatorImageDefaultInit( objs );
    }
});

rotatorImageDefaultInit = function( init ){
    var left = init.getElement('.rotator-image-default-toLeft');
    var right = init.getElement('.rotator-image-default-toRight');
    var panel = init.getElement('.rotator-image-default-images');
    left.addEvent('click', function(){
        var newLeft = parseInt(panel.scrollLeft - panel.getSize().x );
        new Fx.Scroll(panel).start( newLeft, 0 );
    });
    right.addEvent('click', function(){
        var newLeft = parseInt(panel.scrollLeft + panel.getSize().x);
        if( newLeft < panel.scrollWidth ){
            new Fx.Scroll(panel).start( newLeft, 0 );
        }
    });
}


rotatorImageDefault.toRight = function(){
    var newLeft = parseInt(rotatorImageDefault.panel.scrollLeft + rotatorImageDefault.width);
    if( newLeft < rotatorImageDefault.panel.scrollWidth ){
        new Fx.Scroll(rotatorImageDefault.panel).start( newLeft, 0 );
    }
}

rotatorImageDefault.toLeft = function(){
    var newLeft = parseInt(rotatorImageDefault.panel.scrollLeft - rotatorImageDefault.width);
    new Fx.Scroll(rotatorImageDefault.panel).start( newLeft, 0 );
}
