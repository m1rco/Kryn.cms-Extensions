<?php 

class googleanalytics extends baseModule {
	
	
    function __construct(){
	
    	if( kryn::$domain['extproperties']['googleanalytics']['code'] && kryn::$domain['extproperties']['googleanalytics']['code'] != "" ){
    		$code = kryn::$domain['extproperties']['googleanalytics']['code'];
    		
    		$code = "<script type=\"text/javascript\">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$code']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>";
    		kryn::addHeader($code);
    		
    		
    	}
    	
    }
	
}


?>