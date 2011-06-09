<?php

class eventCalendarCategoryAdd extends windowAdd {

    public $table = 'event_calendar_category';

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

