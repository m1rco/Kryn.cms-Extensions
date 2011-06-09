<?php

class newsletterParticipiants extends windowList {

    public $table = 'newsletter_participant';

    public $itemsPerPage = 20;
    public $orderBy = 'name';

    public $filter = array('name', 'email');

    public $add = true;
    public $edit = false;
    public $remove = true;

    public $primary = array('rsn');

    public $export = array(
        'csv' => array(
            'name', 'firstname', 'email', 'ip', 'status'
        )
    );

    public $columns = array(
        'name' => array(
            'label' => 'Name',
            'type' => 'text'
        ),
        'firstname' => array(
            'label' => 'Vorname',
            'type' => 'text'
        ),
        'email' => array(
            'label' => 'E-Mail',
            'type' => 'text'
        ),
        'ip' => array(
            'label' => 'IP',
            'type' => 'text'
        ),
        'status' => array(
            'label' => 'Status',
            'type' => 'text',
            'imageMap' => array(
                1 => 'inc/template/admin/images/icons/tick.png',
                0 => 'inc/template/admin/images/icons/exclamation.png'
            )
        )

    );

}


?>
