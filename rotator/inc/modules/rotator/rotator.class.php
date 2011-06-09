<?php

class rotator extends modul {

    var $name = 'rotator';
    var $version = '0.0.5';
    var $owner = 'kryn.org';
    var $desc = 'Inhalte/Bilder rotieren lassen';
    
    public static $cacheDir = 'inc/cache/rotatorImages/';

    function contentRotate( $pConf ){
        global $tpl;

        kryn::addCss( 'rotator/css/contentRotator.'.$pConf['template'].'.css' );
        kryn::addJs( 'rotator/js/contentRotator.'.$pConf['template'].'.js' );

        $pages = dbTableFetch( 'system_pages', DB_FETCH_ALL, "prsn = ".$pConf['pageRsn'].' ORDER BY sort' );
        foreach( $pages as &$page ){
            $page['content'] = kryn::getPageContent( $page['rsn'] );
        }
        tAssign( 'pages', $pages );
        tAssign( 'pConf', $pConf );
        return tFetch( 'rotator/contentRotator/'.$pConf['template'].'.tpl' );
    }

    function imageRotaterDefault( $pConf ){
        kryn::addCss( 'rotator/css/imageRotatorDefault.'.$pConf['template'].'.css' );
        kryn::addJs( 'rotator/js/imageRotatorDefault.'.$pConf['template'].'.js' );

        $images = kryn::readFolder('inc/template/'.$pConf['folder'], true);
        natcasesort( $images );
        tAssign('images', $images );
        tAssign('folder', $pConf['folder']);

        return tFetch( 'rotator/imageRotatorDefault/'.$pConf['template'].'.tpl' );
    }

    function imageRotater( $pConf ){
        kryn::addCss( 'rotator/css/imageRotator.'.$pConf['template'].'.css' );
        kryn::addJs( 'rotator/js/imageRotator.'.$pConf['template'].'.js' );
        $images = array();

        if( empty($pConf['folder']) ) return;
        $dir = 'inc/template/'.$pConf['folder'];
        $files = kryn::readFolder('inc/template/'.$pConf['folder'], true);
        natcasesort( $files );

        self::$cacheDir = 'inc/template/'.$pConf['folder'].'.rotatorImages/';
        if( !file_exists(self::$cacheDir) )
            mkdir( self::$cacheDir );

        foreach( $files as $file ){
            error_log( $file );
            if( $file == '.' || $file == '..' ) continue;
            $id = filemtime( $dir . $file ) . '.' . $file;
            if(! file_exists( self::$cacheDir.$id ) ){
                self::renderImage( $pConf, $dir, $file );
            }
            list( $oriWidth, $oriHeight, $type ) = getimagesize( $dir.$file );
            if( $type >= 1 && $type <=3 ){
                $nfile = array(
                    'thump' => self::$cacheDir . 'thump.' . $file,
                    'file' => self::$cacheDir . filemtime( $dir . $file ) . '.' .$file
                );
                $images[] = $nfile;
            }
        }
        tAssign( 'images', $images );
        tAssign( 'pConf', $pConf );

        return tFetch( 'rotator/imageRotator/'.$pConf['template'].'.tpl' );
    }
    
    function renderImage( $pConf, $pDir, $pFile ){
        $file = $pDir.$pFile;
        list( $oriWidth, $oriHeight, $type ) = getimagesize( $file );
        switch( $type ){
            case 1:
                $imagecreate = 'imagecreatefromgif';
                $imagesave = 'imagegif';
                break;
            case 2:
                $imagecreate = 'imagecreatefromjpeg';
                $imagesave = 'imagejpeg';
                break;
            case 3:
                $imagecreate = 'imagecreatefrompng';
                $imagesave = 'imagepng';
                break;
        }
        if(! $imagecreate )
            return;
        $img = $imagecreate( $file );

        $cacheThumpFile = self::$cacheDir.'thump.'.$pFile;
        $cacheFile = self::$cacheDir . filemtime( $file ) . '.' . $pFile;
        
        list( $thumpWidth, $thumpHeight ) = explode( 'x', $pConf['thumpSize'] );
        list( $newWidth, $newHeight ) = explode( 'x', $pConf['bigSize'] );
        
        //
        // render Thump
        //
        $thumpImage = imagecreatetruecolor( $thumpWidth, $thumpHeight );

        if( $oriWidth > $oriHeight ){

            //resize mit hoehe = $tempheight, width = auto;
            
            $ratio = $thumpHeight / ( $oriHeight / 100 );
            $_width = ceil($oriWidth * $ratio / 100);

            $top = 0;
            if( $_width < $thumpWidth) { //berechnung ergibt, dass neue breite zu klein ist => anpassen auf thumpWidth
                $ratio = $_width / ($thumpWidth/100);
                $nHeight = $thumpHeight * $ratio / 100;
                $top =  ($thumpHeight - $nHeight)/2;
                $_width = $thumpWidth;
            }

            $tempImg = imagecreatetruecolor( $_width, $thumpHeight );
            imagecopyresampled( $tempImg, $img, 0, 0, 0, 0, $_width, $thumpHeight, $oriWidth, $oriHeight);//schneide temp-thumpnail raus
            $_left = ($_width/2) - ($thumpWidth/2);

            imagecopyresampled( $thumpImage, $tempImg, 0, 0, $_left, 0, $thumpWidth, $thumpHeight, $thumpWidth, $thumpHeight );//klebe temp-thumpnail auf thumpnail (mit richtiger position x)

        } else {
            $ratio = $thumpWidth / ( $oriWidth / 100 );
            $_height = ceil($oriHeight * $ratio / 100);
            $tempImg = imagecreatetruecolor( $thumpWidth, $_height );
            imagecopyresampled( $tempImg, $img, 0, 0, 0, 0, $thumpWidth, $_height, $oriWidth, $oriHeight );
            $_top = ($_height/2) - ($thumpHeight/2);
            imagecopyresampled( $thumpImage, $tempImg, 0, 0, 0, $_top, $thumpWidth, $thumpHeight, $thumpWidth, $thumpHeight );
        }
        
        //render image(big)
        if( $oriHeight > $oriWidth ){
            $ratio = $newHeight / ( $oriHeight / 100 );
            $_width = ceil($oriWidth * $ratio / 100);
            $newImage = imagecreatetruecolor( $_width, $newHeight );
            imagecopyresampled( $newImage, $img, 0, 0, 0, 0, $_width, $newHeight, $oriWidth, $oriHeight);
        } else {
            $ratio = $newWidth / ( $oriWidth / 100 );
            $_height = ceil($oriHeight * $ratio / 100);
            $newImage = imagecreatetruecolor( $newWidth, $_height );
            imagecopyresampled( $newImage, $img, 0, 0, 0, 0, $newWidth, $_height, $oriWidth, $oriHeight);
        }
        
        //save
        $imagesave( $newImage, $cacheFile );
        $imagesave( $thumpImage, $cacheThumpFile );
    }

    function getPlugins(){
        $plugins['contentRotate'] = array( 'Seiten', array(
            'template' =>	array(
                'label' => 'Template',
                'type' => 'files',
                'withExtension' => false,
                'directory' => 'inc/template/rotator/contentRotator/'
            ),
            'pageRsn' => array(
                'label' => 'Ordner',
                'type' => 'integer'
            )
        ));
        $plugins['imageRotaterDefault'] = array( 'Bilder', array(
            'template' =>	array(
                'label' => 'Template',
                'type' => 'files',
                'withExtension' => false,
                'directory' => 'inc/template/rotator/imageRotatorDefault/'
            ),
            'folder' => array(
                'label' => 'Datei-Ordner',
                'type' => 'string'
            ),
        ));
        $plugins['imageRotater'] = array( 'Bilder (autom. verkleinerung)', array(
            'template' =>	array(
                'label' => 'Template',
                'type' => 'files',
                'withExtension' => false,
                'directory' => 'inc/template/rotator/imageRotator/'
            ),
            'folder' => array(
                'label' => 'Datei-Ordner',
                'type' => 'string'
            ),
            'openWith' => array(
                'label' => 'Oeffnen mit',
                'type' => 'select'
            ),
            'thumpSize' => array(
                'label' => 'Thumpnail Groesse',
                'desc' => 'e.g. 50x50. Wird automatisch ausgeschnitten.',
                'type' => 'string'
            ),
            'bigSize' => array(
                'label' => 'Bildgroesse',
                'desc' => 'e.g. 1024x600. Die Grosse beim Oeffnen',
                'type' => 'string'
            )
        ));
        return $plugins;
    }
}

?>
