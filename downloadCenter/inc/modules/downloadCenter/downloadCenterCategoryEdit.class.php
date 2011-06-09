<?php

class downloadCenterCategoryEdit extends windowEdit {

    public $table = 'dlc_categories';

    public $primary = array('rsn');
    public $multiLanguage = true;
    
    public $fields = array(
        'cat_name' => array(
            'label' => 'Name',
            'type' => 'text',
            'empty' => false
        )
    );
}

?>

