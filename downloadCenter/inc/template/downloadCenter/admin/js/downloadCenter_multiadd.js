var dlcFileNameConvert = function(pFile, pSecParam) {
   rName = pSecParam.substr(( pSecParam.lastIndexOf('/') + 1));
   rName = rName.substr(0, rName.length-1);  
   return rName;
}


var downloadCenter_downloadCenter_multiadd = new Class({
    Extends : ka.windowAdd,
	initialize: function( pWin ){
		this.uploadedFileNum = 1;
		this.win = pWin;
		this.uploadFileNameField;
		this.uploadFileTypeField; 		
		
		this.parent(pWin);
		
		this.uploadedFiles = {};
		
    }, 
    
    renderContent: function(){
        var _this = this;

        this.actions = new Element('div', {
            'class': 'ka-windowEdit-actions'
        }).inject( this.win.content );

        this.exit = new ka.Button(_('Cancel'))
        .addEvent( 'click', function(){
        	_this._close();
        })
        .inject( this.actions );

        this.saveNoClose = new ka.Button(_('Save'))
        .addEvent('click', function(){
            _this._save();
        })
        .inject( this.actions );

        this.save = new ka.Button(_('Save and close'))
        .addEvent('click', function(){
            _this._save( true );
        })
        .inject( this.actions );
    },
    
    addField: function( pField, pFieldId, pContainer ){    
        if( !pField ) return;
    
        if( pField.type == 'wysiwyg' && !this.windowAdd ){
            pField.withOutTinyInit = true;
        }
        pField.win = this.win;
        pField.label = _(pField.label);
        pField.desc = _(pField.desc);
        var field = new ka.field(pField, pFieldId );
        //add on upload event to get the file type automatically
        if(pField.type == 'multiUpload' || pField.type == 'multiupload') { 
        	field.obj.addEvent('upload',  function(pUploadedFileNum, pUploadedFiles) {
        		pUploadedFiles['UFN'+pUploadedFileNum].childFields.item_type.setValue(this.getFileType(pUploadedFiles['UFN'+pUploadedFileNum].name));     		
        	}.bind(this));
        }
        
        
        field.inject( pContainer );

        if( pField.type == 'wysiwyg' && this.windowAdd ){
            //var contentCss = _path+"inc/template/css/kryn_tinyMceContentElement.css";
            //initResizeTiny( field.lastId, contentCss );
            ka._wysiwygId2Win.include( field.lastId, this.win );
            initResizeTiny( field.lastId, _path+'inc/template/css/kryn_tinyMceContent.css' );
        }

        this.fields.include( pFieldId, field );
        this._fields.include( pFieldId, pField );
    },
    
    getFileType : function(pFileName) {
    	lPos = (pFileName+'').lastIndexOf('.');
    	if(lPos)
    		return pFileName.substr(lPos+1, pFileName.length-1).toLowerCase();
    	
    	return '';
    },   
    
    _close : function() {
    	_this = this;
    	this.loader.show();
        
        new Request.JSON({url: _path+'admin/files/deleteFile/', onComplete: function(res){
        	_this.loader.hide();           
            _this.win.close();
        }.bind(this)}).post({path: '/downloadCenter/tempUpload/', name: _sid});
    	
        
        
    },
    
    _save: function( pClose ){
    	var go = true;
        var _this = this;
        var req = $H();
        if( this.item )
            req = $H(this.item.values);
        
        req.include( 'module', this.win.module );
        req.include( 'code', this.win.code );

                
        this.fields.each(function(item, fieldId){
            if(! item.isOk() ){
            	
            	if( this.currentTab && this.values.tabFields){
            		var currenTab2highlight = false;
            		$H(this.values.tabFields).each(function(fields,key){
            			$H(fields).each(function(field, fieldKey){
            				if( fieldKey == fieldId ){
            					currenTab2highlight = key;
            				}
            			})
            		});
            		
            		if( currenTab2highlight && this.currentTab != currenTab2highlight ){
            			var button = this._buttons[ currenTab2highlight ];
            			this._buttons[ currenTab2highlight ].startTip(_('Please fill!'));
            			button.toolTip.loader.set('src', _path+'inc/template/admin/images/icons/error.png');
            			button.toolTip.loader.setStyle('position', 'relative');
            			button.toolTip.loader.setStyle('top', '-2px');
            		}
            	}
            	
                item.highlight();
                
                go = false;
            }
            /*if( item.field.type == 'file' ){
                item.input.inject( form );
                item.renderFile();
            } else {
            */
                if( item.field.relation == 'n-n' )
                    req.set( fieldId, JSON.encode(item.getValue()) );
                else
                    req.set( fieldId, item.getValue() );
            //}
            //if( item.field.type == 'wysiwyg' && pClose ){
            //    tinyMCE.execCommand('mceRemoveControl', false, item.lastId);
            //}
        }.bind(this));
        
      
        if( this.values.multiLanguage ){
        	req.set('lang', this.languageSelect.value);
        }
        
        if( go ){
            /*iframe.addEvent('load', function(){
                ka.wm.softReloadWindows( _this.win.module, _this.win.code.substr(0, _this.win.code.lastIndexOf('/')) );
                if( pClose )
                    _this.win.close(); 
            });
            form.submit();
            */
            this.loader.show();
            if( _this.win.module == 'users' && (_this.win.code == 'users/edit/'
                || _this.win.code == 'users/edit'
                || _this.win.code == 'users/editMe'
                || _this.win.code == 'users/editMe/'
                ) ){
                ka.settings.get('user').set('adminLanguage', req.get('adminLanguage') );
            }
            new Request.JSON({url: _path+'admin/backend/window/loadClass/saveItem', noCache: true, onComplete: function(res){
                ka.wm.softReloadWindows( _this.win.module, _this.win.code.substr(0, _this.win.code.lastIndexOf('/'))+'/itemList' );
                _this.loader.hide();
                if( pClose )
                    _this.win.close();
            }}).post(req);
        }
    }
});