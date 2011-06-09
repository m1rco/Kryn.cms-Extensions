window.addEvent('domready', function(){
    $('rotatorDefaultNaviLeft').addEvent('click', function(){
        rotatorDefault.toPage(1);
    });
    rotatorDefault.pages = $$('.rotatorDefaultPages > div');
    rotatorDefault.pages.setStyles({
        opacity: 0,
        display: 'block'
    });
    rotatorDefault.toPage(0);
});

rotatorDefault = {};
rotatorDefault.lastItem = null;

rotatorDefault.toPage = function( pNr ){
    if( rotatorDefault.lastItem )
        rotatorDefault.lastItem.tween( 'opacity', 0 );

    rotatorDefault.pages[pNr].tween('opacity', 1);
    rotatorDefault.lastItem = rotatorDefault.pages[pNr];
}
