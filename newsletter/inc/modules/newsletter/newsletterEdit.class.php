<?php

class newsletterEdit extends windowEdit {

    public $table = 'newsletter';

    public $primary = array('rsn');

    public $fields = array(
        'title' => array(
            'label' => 'Titel',
            'type' => 'text',
            'empty' => false
        )
    );
}
?>
