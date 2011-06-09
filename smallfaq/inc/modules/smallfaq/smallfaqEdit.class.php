<?php

class smallfaqEdit extends windowEdit {

    public $table = 'smallfaq';

    public $primary = array('rsn');

    public $fields = array(
        'title' => array(
            'label' => 'Title',
            'type' => 'text',
            'empty' => false
        ),
        'content' => array(
            'label' => 'Content',
            'type' => 'wysiwyg'
        ),
        'category_rsn' => array(
            'label' => 'Category',
            'type' => 'select',
            'table' => 'smallfaqCategory',
            'table_key' => 'rsn',
            'table_label' => 'title'
        )
    );
}

?>
