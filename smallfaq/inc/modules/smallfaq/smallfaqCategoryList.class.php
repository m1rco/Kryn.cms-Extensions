<?php

class smallfaqCategoryList extends windowList {

    public $table = 'smallfaqCategory';

    public $itemsPerPage = 25;
    public $primary = array('rsn');

    public $orderBy = 'title';
    public $orderByDirection = 'ASC';

    public $add = true;
    public $edit = true;
    public $remove = true;

    public $columns = array(
        'title' => array(
            'label' => 'Title',
            'type' => 'text'
        ),
    );

}

?>
