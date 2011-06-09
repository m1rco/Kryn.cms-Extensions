<?php

class smallfaqCategoryAdd extends windowAdd {

    public $table = 'smallfaqCategory';

    public $primary = array('rsn');

    public $fields = array(
        'title' => array(
            'label' => 'Title',
            'type' => 'text',
            'empty' => false
        ),
    );
}

?>
