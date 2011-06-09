<?php

class formularTemplate extends baseModule {


    function viewForm( $pConfig ){

        tAssign( 'config', $pConfig );

        if( $pConfig['template'] == '' )
            return 'no template defined.';

        return tFetch('formularTemplate/forms/'.$pConfig['template'].'.tpl');
    }

    function mailHandler( $pConfig ){
        $mailbody = '';
        $empty = true;

        if( getArgv('formularTemplateSent') != '1' ) return;

        foreach( $_POST as $key => $post ){
            if( $post != '' ) $empty = false;
            if( $key != 'formularTemplateSent' )
                $mailbody .= "$key: $post\n";
        }

        if( $empty != false ) return;

        tAssign('mailbody', $mailbody);
        
        if( $pConfig['mailtemplate'] == '' || !$pConfig['mailtemplate'] )
            $pConfig['mailtemplate'] = 'default';
            
        $template = 'formularTemplate/emailAdmin/'.$pConfig['template'].'.tpl';
        
        $body = tFetch($template);
        
        if(  $pConfig['subject'] == "" )
             $pConfig['subject'] = _l('New formular request');
        
        if( $pConfig['to'] ){
            mail( $pConfig['to'], $pConfig['subject'], $body, "From: kryn@".$_SERVER['SERVER_NAME']."\r\n".
              "Content-Type: text/plain; Charset=utf-8\r\n"
            );
        }

        return tFetch('formularTemplate/formSent/'.$pConfig['template'].'.tpl');

    }

}

?>
