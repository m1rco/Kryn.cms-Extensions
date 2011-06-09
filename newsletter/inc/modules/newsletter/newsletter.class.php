<?php

class newsletter extends baseModule {

    function unsubscribe( $pConf ){
        tAssign( 'pConf', $pConf );
        
        if( getArgv('newsletter_unsubscribe_sent') == '1' ){
        
            tAssign('newsletter_unsubscribe_sent', true);
            $email = getArgv('newsletter_unsubscribe_email',1);
            $to = dbTableFetch('newsletter_participant', 1, "email = '$email' and newsletter_rsn = ".$pConf['newsletter']);
            tAssign( 'unsubscriber', $to );
            
            if( $to['rsn'] > 0 ){
            
                $key = md5( time()*rand(45,345) . '§!"$=' . $rsn . '=' . rand(843,2885) . md5(rand(355,5552)) );
            
                dbUpdate('newsletter_participant', array('rsn'=>$to['rsn']), array(
                    'activationKey' => $key
                ));
                $unsubscribeLink = kryn::$pageUrl.'/newsletter_unsubscribe:'.$key.'/';
                tAssign('unsubscribeLink', $unsubscribeLink);
                kryn::sendMail( $to['email'], $pConf['subject'], tFetch('newsletter/mailUnsubscribeTemplates/'.$pConf['mailtemplate'].'.tpl') );
                
            }
            
        }
        
        if( getArgv('newsletter_unsubscribe') != '' ){
            //delete from database
            $key = getArgv('newsletter_unsubscribe', 1);
            $to = dbTableFetch('newsletter_participant', 1, "activationKey = '$key'");
            dbDelete('newsletter_participant', "activationKey = '$key'");
            tAssign( 'unsubscriber', $to );

            if( $pConf['sent_email_after_unsubscribe'] == 1 ){
                kryn::sendMail( $to['email'], $pConf['subject_after_unsubscribe'], tFetch('newsletter/mailAfterUnsubscribeTemplates/'.$pConf['template_after_unsubscribe'].'.tpl') );
            }
            tAssign('newsletter_unsubscribe_done', true);

        }
    
        return tFetch( 'newsletter/unsubscribe/'. $pConf['template'].'.tpl' );
    }

    function content( $pConf ){
        $newsletter = $pConf['newsletter_rsn'];
        tAssign( 'pConf', $pConf );

        if( getArgv('newsletterFormId'.$pConf['formId'] ) == '1' ){
            if( getArgv('name') == '' || getArgv('email') == '' )
                return 'Error.';

            $key = md5( time()*rand(45,345) . '§!"$=' . $rsn . '=' . rand(843,2885) . md5(rand(355,5552)) );
            dbInsert( 'newsletter_participant', array('email', 'name', 'lastname', 'sex',
                'status' => 0, 'newsletter_rsn'=>$newsletter, 'activationKey' => $key,
                'created' => time(),
                'ip' => $_SERVER['REMOTE_ADDR']
            ));


            tAssign( 'key', $key );
            kryn::sendMail( getArgv('email'), $pConf['subject'], tFetch('newsletter/mailTemplates/'.$pConf['email'].'.tpl'),
               $pConf['from'] );

            tAssign( 'newsletterAnnounced', true );
        }
        if( getArgv( 'newsletter' ) == 'confirm' ){
            $key = getArgv('activation', true);
            $done = dbTableFetch('newsletter_participant', 1, "activationKey='$key' and newsletter_rsn = $newsletter");
            if( $done['status'] == '1' ){
                tAssign( 'newsletterConfirmAlready', true );
            } elseif( $done['status'] == '0' ){
                dbUpdate('newsletter_participant', array('rsn'=>$done['rsn']), array('status'=>1));
                tAssign( 'newsletterConfirmDone', true );
            } else {
                tAssign( 'newsletterConfirmNotFound', true );
            }
        }
        tAssign('pConf', $pConf);
        return tFetch( 'newsletter/frontend/'. $pConf['template'].'.tpl' );
    }
}

?>
