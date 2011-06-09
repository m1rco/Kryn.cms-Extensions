<?php

class movies extends baseModule {


    public function view( $pConf ){
    
        $code = _l('Invalid link.');
        
        $align = $pConf['align'];
        
        if( strpos($pConf['link'], 'youtube.com') !== false ){
        
            $width = ($pConf['width']+0 > 0) ? $pConf['width']+0 : 480;
            $height = ($pConf['height']+0 > 0) ? $pConf['height']+0 : 385;
            $videoKey = preg_replace('/(.*)youtube.com\/watch\?v=([a-zA-Z0-9]*).*/', '$2', $pConf['link']);
            
            $code = '<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/v/'.$videoKey.'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$videoKey.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$width.'" height="'.$height.'"></embed></object>';
        
        }
        
        
        if( strpos($pConf['link'], 'google.com') !== false ){
        
        
            
            $width = ($pConf['width']+0 > 0) ? $pConf['width']+0 : 400;
            $height = ($pConf['height']+0 > 0) ? $pConf['height']+0 : 326;
            
            $videoKey = preg_replace('/(.*)google.com\/videoplay\?docid=([a-zA-Z0-9]*).*/', '$2', $pConf['link']);
            print $videoKey;
            $code = '<embed id=VideoPlayback src=http://video.google.com/googleplayer.swf?docid='.$videoKey.'&fs=true style=width:'.$width.'px;height:'.$height.'px allowFullScreen=true allowScriptAccess=always type=application/x-shockwave-flash> </embed>';

        }
        
        
        return '<div style="text-align: '.$align.'">'.$code.'</div>';
    }


}

?>