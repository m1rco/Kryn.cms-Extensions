<?php

class newsletterList extends windowList {

    public $table = 'newsletter';

    public $itemsPerPage = 20;
    public $orderBy = 'title';

    public $filter = array('title');

    public $add = true;
    public $edit = true;
    public $remove = true;

    public $itemActions = array(
        array('Anmeldungen anzeigen', 'admin/images/icons/group.png', 'newsletter/newsletter/participiants'),
    );

    public $primary = array('rsn');

    public $columns = array(
    
        'title' => array(
            'label' => 'Newsletter-Liste',
            'type' => 'text'
        )

    );

}


?>
