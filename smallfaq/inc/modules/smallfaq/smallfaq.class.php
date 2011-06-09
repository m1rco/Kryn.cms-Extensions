<?php

class smallfaq extends baseModule {

    public function content( $pConf ){
        $cat = implode(",",$pConf['category_rsn']);
        kryn::addJs( 'kryn/mootools-core.js' );
        kryn::addCss( 'smallfaq/list.css' );
        kryn::addJs( 'smallfaq/list.js' );
        tAssign( 'items', dbTableFetch('smallfaq',DB_FETCH_ALL, "category_rsn IN($cat)"));
        return tFetch( 'smallfaq/list.tpl');
    }
}

?>
