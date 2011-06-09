<?php

class eventCalendarCategoryList extends windowList {

    public $table = 'event_calendar_category';
    public $itemsPerPage = 20;
    public $orderBy = 'title';

    public $iconAdd = 'add.png';
    public $iconDelete = 'cross.png';

    public $filter = array('title');

    public $add = true;
    public $edit = true;
    public $remove = true;

    public $primary = array('rsn');

    public $columns = array(
        'title' => array(
            'label' => 'Titel',
            'type' => 'text'
        )
    );

}

?>

