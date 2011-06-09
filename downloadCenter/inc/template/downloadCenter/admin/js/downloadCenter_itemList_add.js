var downloadCenter_downloadCenter_itemList_add = new Class({
    Extends : ka.windowAdd,
	initialize: function( pWin ){
	
		this.win = pWin;
		this.uploadFileNameField;
		this.uploadFileTypeField;       
		this.parent(pWin);
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
        this.uploadFileNameField.set('value', pFile.name);
        this.uploadFileTypeField.set('value', this.getFileType(pFile.name));
       
    }, 
    
    
    renderContent: function(){
        var _this = this;

        this.actions = new Element('div', {
            'class': 'ka-windowEdit-actions'
        }).inject( this.win.content );

        this.exit = new ka.Button(_('Cancel'))
        .addEvent( 'click', function(){
            _this.win.close();
        })
        .inject( this.actions );

        this.save = new ka.Button(_('Save and close'))
        .addEvent('click', function(){
            _this._preSave( true );
        })
        .inject( this.actions );
    },
    
    _preSave : function(pClose) {
    	logger('custom save');
    	logger(this.fields);
    	logger(this.values.tabFields);
    	//this.uploadFileNameField
    	if(this.fields.item_copy.getValue() == 'none' && this.fields.item_name.getValue() == '') {
    		this.fields.item_name.empty();
    		this.fields.item_name.highlight();
    		if( this.currentTab != 'File' ){
    			var button = this._buttons[ 'File' ];
    			this._buttons[ 'File' ].startTip(_('Please fill!'));
    			button.toolTip.loader.set('src', _path+'inc/template/admin/images/icons/error.png');
    			button.toolTip.loader.setStyle('position', 'relative');
    			button.toolTip.loader.setStyle('top', '-2px');
    		}
    		
    		
    		return false;
    	}
    	this._save(pClose);
    }

});
