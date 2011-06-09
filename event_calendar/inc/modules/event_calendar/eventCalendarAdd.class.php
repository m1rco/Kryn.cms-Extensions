<?php

class eventCalendarAdd extends windowAdd {

    public $table = 'event_calendar';

    public $primary = array('rsn');

    public $tabFields = array(
        'General' => array(
            'title' => array(
                'label' => 'Title',
                'type' => 'text',
                'empty' => false
            ),
            'category_rsn' => array(
                'label' => 'Category',
                'type' => 'select',
                'table' => 'event_calendar_category',
                'table_label' => 'title',
                'table_key' => 'rsn'
            ),           
            'event_date' => array(
                'label' => 'Event date',                
                'type' => 'datetime',
                'empty' => false
            ),
            'event_date_end' => array(
                'label' => 'Event end date',                
                'type' => 'datetime'                
            ),
            'event_location' => array(
                'label' => 'Event location',                
                'type' => 'text'
            ),             
            'deactivate' => array(
                'label' => 'Deactivate',
                'type' => 'checkbox'
            ),           
        ),
        'Event intro' => array(
            'introImage' => array(
                'label' => 'Intro image',
                'type' => 'file'                
            ),
            'intro' => array(
                'label' => 'Intro',
                'width' => 600,
                'height' => 150,
                'type' => 'wysiwyg'                
            )    
        ),
        'Event information' => array(
            'content' => array(
                'label' => 'Content',
                'type' => 'wysiwyg',
                'width' => 600,
                'height' => 260
            )
        )
    );
}

?>

