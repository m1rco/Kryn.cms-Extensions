var downloadCenter_downloadCenter_itemList_edit = new Class({
    Extends : ka.windowEdit,
	initialize: function( pWin ){
	
		this.win = pWin;
		this.uploadFileNameField;
		this.uploadFileTypeField;       
		//this.parent(pWin);
		this.win.content.setStyle('overflow', 'visible');
		this.newPictureUploaded = false;
		this.oldCatRsn = false;
		this.oldFileName = false;
	    this.load();
    },  
    
   addField: function( pField, pFieldId, pContainer ){
        if( pField.type == 'wysiwyg' && !this.windowAdd ){
            pField.withOutTinyInit = true;
        }

        pField.label = _(pField.label);
        pField.desc = _(pField.desc);
        var field = new ka.field(pField, pFieldId );
        field.inject( pContainer );

        if( pField.type == 'wysiwyg' && this.windowAdd ){
            //var contentCss = _path+"inc/template/css/kryn_tinyMceContentElement.css";
            //initResizeTiny( field.lastId, contentCss );
            ka._wysiwygId2Win.include( field.lastId, this.win );
            initResizeTiny( field.lastId, _path+'inc/template/css/kryn_tinyMceContent.css' );
        }
        
        
        if(pField.type == 'fileUpload' || pField.type == 'fileupload') {
    		field.fieldPanel.setStyle('float', 'left');
    		
    		this.uploadFileNameField = field.fieldPanel.getElements('input')[0];
    		this.uploadFileNameField.disabled = true;
    		
    		var uploadBtn = new Element('div', {
    					'title' : 'Upload file',     					 
    					'class' : 'kwindow-win-buttonWrapper', 
    					'style' :  'background : transparent url('+_path+'inc/template/admin/images/admin-files-uploadFile.png) center center no-repeat;cursor:pointer;' }
    			).inject(pContainer);
    	   
    	      this.uploadBtn = uploadBtn;
    	      this.buttonId = 'uploadBtn_'+Math.ceil(Math.random()*100)+'_'+Math.ceil(Math.random()*100);
    	      this.uploadBtn.set('html', '<span id="'+this.buttonId+'"></span>');
    		
    	      this.initSWFUpload();
    		
    	      
    	      new Element('br', { 'style' : 'clear:both;'}).inject(pContainer);
    	}
        
    	if(pFieldId == 'item_type') 
    		 this.uploadFileTypeField = field.fieldPanel.getElements('input')[0];
        

        this.fields.include( pFieldId, field );
        this._fields.include( pFieldId, pField );
    },
    
    _loadItem: function( pItem ){
        this.item = pItem;
        this.oldCatRsn = pItem.values.cat_rsn;        
        this.oldFileName =  pItem.values.item_name;
        
        this.fields.each(function(field, fieldId){
            try {
                if( $type(pItem.values[fieldId]) == false )
                    field.setValue( '' );
                else if( !this._fields[fieldId].startempty )
                    field.setValue( pItem.values[fieldId] );

                if( !this.windowAdd ){
                    var contentCss = _path+"inc/template/css/kryn_tinyMceContentElement.css";
                    initResizeTiny( field.lastId, contentCss );
                    ka._wysiwygId2Win.include( field.lastId, this.win );
                }
            } catch(e) {
                logger( "Error with "+fieldId+": "+e);
            }
        }.bind(this));
        
        if( this.values.multiLanguage ){
        	this.languageSelect.value = this.item.values.lang;
        	this.changeLanguage();
        }
        
        
        this.loader.hide();
    },
    
    
    _save: function( pClose ){
        var go = true;
        var _this = this;
        var req = $H();
        if( this.item )
            req = $H(this.item.values);
        
        req.include( 'module', this.win.module );
        req.include( 'code', this.win.code );
        req.newPictureUploaded = this.newPictureUploaded;
        req.oldCatRsn = this.oldCatRsn;
        req.oldFileName = this.oldFileName;
        
        this.fields.each(function(item, fieldId){
            if(! item.isOk() ){
                item.highlight();
                go = false;
            }
            if( item.field.type == 'file' ){
                item.input.inject( form );
                item.renderFile();
            } else {
            logger(fieldId);
                if( item.field.relation == 'n-n' )
                    req.set( fieldId, JSON.encode(item.getValue()) );
                else
                    req.set( fieldId, item.getValue() );
            }           
        });        
        if( this.values.multiLanguage ){
        	req.set('lang', this.languageSelect.value);
        }
        
        if( go ){           
            this.loader.show();
            if( _this.win.module == 'users' && (_this.win.code == 'users/edit/'
                || _this.win.code == 'users/edit'
                || _this.win.code == 'users/editMe'
                || _this.win.code == 'users/editMe/'
                ) ){
                ka.settings.get('user').set('adminLanguage', req.get('adminLanguage') );
            }
            new Request.JSON({url: _path+'admin/backend/window/loadClass/saveItem', noCache: true, onComplete: function(res){
                ka.wm.softReloadWindows( _this.win.module, _this.win.code.substr(0, _this.win.code.lastIndexOf('/')) );
                _this.loader.hide();
                if( pClose )
                    _this.win.close();
            }}).post(req);
        }
    },
    
    
    
    
    getFileType : function(pFileName) {
    	
    	lPos = (pFileName+'').lastIndexOf('.');
    	if(lPos)
    		return pFileName.substr(lPos+1, pFileName.length-1).toLowerCase();
    	
    	return '';    	
    },
    
    /* file operations */   

    _uploadStart: function( pFile ){
        ka.uploads[this.win.id].removeFileParam( pFile.id, 'path' );
        ka.uploads[this.win.id].addFileParam( pFile.id, 'path', '/downloadCenter/tempUpload/' );
        ka.uploads[this.win.id].startUpload( pFile.id );
        ka.fupload.addToUploadMonitor( pFile, ka.uploads[this.win.id] );
    },
    
    initSWFUpload: function(){    
    	ka.uploads[this.win.id] = new SWFUpload({
            upload_url: _path+"admin/files/upload/krynsessionid:"+window._sid+"/",
           
            file_post_name: "file",
            post_params: { "_sessionid": _sid},
            flash_url : _path+"inc/template/admin/swfupload.swf",
            file_upload_limit : "500",
            file_queue_limit : "0",

            file_queued_handler: this._uploadStart.bind(this),

            upload_progress_handler: ka.fupload._progress,
            upload_error_handler: ka.fupload.error,
            upload_success_handler: this._uploadSuccess.bind(this),

            button_placeholder_id : this.buttonId,
            button_width: 26,
            button_height: 20,
            button_text : '<span class="button"></span>',
            button_text_style : '.button { position: absolute; }',
            button_text_top_padding: 0,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,
            button_action : SWFUpload.BUTTON_ACTION.SELECT_FILE
        });
    },

    _uploadSuccess: function( pFile ){
        ka.fupload.success( pFile );
        this.newPictureUploaded = true;
        this.uploadFileNameField.set('value', pFile.name);
        this.uploadFileTypeField.set('value', this.getFileType(pFile.name));
       
    },
    
    
    
   

});
