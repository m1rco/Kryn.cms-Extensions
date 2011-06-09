<?php

class newsletter_admin {

    function init(){
        switch( getArgv(3) ){
        case 'add':
            if( getArgv('save') == '1' ){
                dbInsert( 'newsletter', array('title') );
                kryn::redirect('admin/newsletter');
            }
            return tFetch( 'newsletter/admin.add.tpl' );
        case 'edit':
            $rsn = getArgv('rsn')+0;
            if( getArgv('save') == '1' ){
                dbUpdate( 'newsletter', array('rsn'=>$rsn), array('title') );
                kryn::redirect('admin/newsletter');
            }
            tAssign( 'item', dbTableFetch('newsletter',1,"rsn=$rsn") );
            return tFetch( 'newsletter/admin.edit.tpl' );
        case 'participant':
            if( getArgv('delete')+0 > 0 ){
                dbDelete( 'newsletter_participant', 'rsn = '.getArgv('delete'));
                kryn::redirect('admin/newsletter/participant/rsn='.(getArgv('rsn')));
            }
            $rsn = getArgv('rsn')+0;
            tAssign('newsletter', dbTableFetch('newsletter',1,"rsn=$rsn"));
            tAssign( 'items', dbTableFetch('newsletter_participant', DB_FETCH_ALL, "newsletter_rsn=$rsn") );
            return tFetch( 'newsletter/admin.participant.tpl' );
        default:
            tAssign( 'items',
                dbExfetch(
                "SELECT *, n.rsn, count(p.rsn) as participants
                FROM %pfx%newsletter n
                LEFT OUTER JOIN %pfx%newsletter_participant p ON
                ( p.newsletter_rsn = n.rsn AND p.status = 1)
                GROUP BY n.rsn
                ",
                DB_FETCH_ALL) );
            return tFetch( 'newsletter/admin.tpl' );
        }
        return 'hi';
    }

}

?>
