<?php

class eventCalendarList extends windowList {

    public $table = 'event_calendar';
    public $itemsPerPage = 20;
    public $orderBy = 'title';

    public $iconAdd = 'add.png';
    public $iconDelete = 'cross.png';

    public $filter = array('title', 'event_date', 'deactivate');

    public $add = true;
    public $edit = true;
    public $remove = true;

    public $primary = array('rsn');

    public $columns = array(
        'title' => array(
            'label' => 'Title',
            'type' => 'text'
        ),
        'category_rsn' => array(
            'label' => 'Category',
            'type' => 'select',
            'table' => 'event_calendar_category',
            'table_label' => 'title',
            'width' => 130,
            'table_key' => 'rsn'
        ),
        'event_date' => array(
            'label' => 'Event date',
            'width' => 110,
            'type' => 'datetime'
        ),
        'deactivate' => array(
            'label' => 'Deactivated',
            'type' => 'int'
        )
    );

}

?>

