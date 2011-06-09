calendar = new Class({
  Extends : ka.datePicker,
  
  insertInto : null,
  initialize : function (pInsertInto, pOptions) {
    this.insertInto = pInsertInto;
    
        
    this.options = new Hash({
            days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            months: ['january', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Dezember'],
            shortDays: ['M', 'D', 'M', 'D', 'F', 'S', 'S'],
            time: false
        });

        if( pOptions && pOptions.time )
            this.options.time = true;
        if( pOptions && pOptions.empty )
            this.options.empty = true;

        this.choosenDate = new Date();

        if( this.options.time == true){
            this.options.format = '%d.%m.%Y %H:%M';
        } else {
            this.options.format = '%d.%m.%Y';
        }
    
        this._renderChooser();
        this._attach();
  },
  
  _attach: function(){
    this.show();
       
    },
    
    choose: function( pDate, pElem){
      
      //only make a request if events are there for this date
      if(!pElem.hasClass('ka-datePicker-item-event'))
        return;
      
      pDate = pDate.clearTime();
      eventFrom = parseInt((pDate.getTime())/1000);
      eventTo = parseInt((pDate.clone().increment('day', 1)).getTime()/1000);
      
      
      this.tableBody.getElements('.ka-datePicker-item-selected').each(function(item) {
        item.removeClass('ka-datePicker-item-selected');
      })
      
      pElem.getParent().addClass('ka-datePicker-item-selected');
      pElem.addClass('ka-datePicker-item-selected');
      
      
        this.noDate = (pDate == null) ? true: false;
        this.choosenDate = pDate;
        new Request.JSON({ url : pageUrl,
          onRequest: function() {
            $('eventElements').set('html', '');
            new Element('h4', { 'html' : 'Lade Daten bitte warten...'}).inject($('eventElements'));
          },
          onComplete: function(res) {
            $('eventElements').set('html', res);
            Cufon.replace('.eventCalendarListDefaultItemH3', {    
                 fontSize : '18px'
            });
          }
          }).post({'event_from' : eventFrom, 'event_to' : eventTo, 'onlyGetElements' : true});       
    },
    
    getEventsInMonth : function(pFrom, pTo) {    
      
      new Request.JSON({ url : pageUrl,          
          onComplete: function(res) {            
            new Hash(res).each(function(item, key) {  
              $$('td[rel="'+key+'"]').each(function(sitem) {
                sitem.addClass('ka-datePicker-item-event');
                sitem.getFirst().addClass('ka-datePicker-item-event');
              })
            })
          }
          }).post({'getEventsFrom' : pFrom, 'getEventsTo' : pTo, 'getEventsInMonth' : true});
      
      
    },
    
    renderInput: function(){
     
    },
  
  _renderChooser: function(){
        this.chooser = new Element('div', {
            'class': 'ka-datePicker-chooser',
            styles: {
                display: 'block'
            }
        }).inject( this.insertInto );

        this.body = new Element('div', {
            'class': 'ka-datePicker-body'
        }).inject( this.chooser );



        this.controlDiv = new Element('div', {'class' : 'ka-datePicker-controls'}).inject (this.body );
        /*******
            month Selection
        ********/        
        this.monthShow = new Element('div', { 'class' : 'ka-datePicker-month-show'}).inject( this.controlDiv );
        this.monthSelect = new Element('div', { 'class' : 'ka-datePicker-month-select'}).inject( this.controlDiv ); 
        
        
        var a = new Element('a', {
            text: ' ',
            'class': 'ka-Button ka-button-prev'
        })
        .addEvent('click', function(){
            this.choosenDate.decrement('month');
            this.renderMonth(true);
        }.bind(this))
        .inject( this.monthSelect);        
        new Element('span').inject(a);
        
        
        var a= new Element('a', {
            text: ' ',
            'class': 'ka-Button ka-button-next'
        })
        .addEvent('click', function(){
            this.choosenDate.increment('month');
            this.renderMonth(true);
        }.bind(this))
        .inject( this.monthSelect);
        new Element('span').inject(a);    
        
        
        new Element('div', {
          style: 'clear: both'
        }).inject( this.monthSelect );

 


        /*******
            year selection
        ********/
        
        this.yearShow = new Element('div', { 'class' : 'ka-datePicker-year-show' }).inject( this.controlDiv );
        this.yearSelect = new Element('div', { 'class' : 'ka-datePicker-year-select' }).inject( this.controlDiv );
       
        
        
        var a = new Element('a', {
            text: ' ',
            'class': 'ka-Button ka-button-prev'
        })
        .addEvent('click', function(){
            this.choosenDate.decrement('year');
            this.renderMonth(true);
        }.bind(this))
        .inject( this.yearSelect);
        new Element('span').inject(a);
        
        var a = new Element('a', {
            text: ' ',
            'class': 'ka-Button ka-button-next'
        })
        .addEvent('click', function(){
            this.choosenDate.increment('year');
            this.renderMonth(true);
        }.bind(this))
        .inject( this.yearSelect);
        new Element('span').inject(a);
        

        new Element('div', {
          style: 'clear: both'
        }).inject( this.yearSelect );
       


        /*******
            Day Table
        ********/
        this.table = new Element('table', {
            cellpadding: 0, cellspacing: 0, 'class' : 'ka-datePicker-main-table'
        }).inject( this.body );
        
        
        new Element('div', {
          style: 'clear: both'
        }).inject( this.body )
        
        
        this.tableHead = new Element('thead').inject( this.table );
       

        var tr = new Element('tr').inject(this.tableHead);
        this.options.shortDays.each(function( day, index ){
            new Element('td', {
                text: day
            }).inject( tr );
        });
        
        this.tableBody = new Element('tbody').inject( this.table );

    },
    
    
    renderMonth: function( pRenderInput ){      
        if( this.choosenDate == null ) return;        
        
      
        this.nowDate = new Date();
        this.tableBody.empty();

        this.monthShow.set('text', this.options.months[this.choosenDate.format('%m').toInt()-1] );
        this.yearShow.set('text', this.choosenDate.format('%Y').toInt() );


        var firstDay = this.choosenDate.clone().set('date', 1);
        var lastDay = this.choosenDate.get('lastdayofmonth');
        this.currentBodyTr = new Element('tr').inject( this.tableBody );

        var t = firstDay.format('%w');
        if( t == 0 ) t = 7;
        for( var i = 1; i < t; i++){
            this._renderItem( firstDay.clone().decrement('day', t-i), true );
        }

        var tempDate = this.choosenDate.clone();
        for( var i = 1; i <= this.choosenDate.get('lastdayofmonth'); i++){
            tempDate.set('date', i);
            var day = tempDate.format('%w');
            if( day == 0 ) day = 7;

            this._renderItem( tempDate.clone() );

            if( day == 7 ){ //last day in this week
                this.currentBodyTr = new Element('tr').inject( this.tableBody );
            }
        }

        var currentDayInWeek = tempDate.set('date', lastDay).format('%w');
        for( var i = 1; i <= (7-currentDayInWeek) ; i++){
            this._renderItem( tempDate.clone().increment('day', i ), true );
        }

        if( pRenderInput )
            this.renderInput();
        
        var from = parseInt( ((firstDay.clearTime()).getTime())/1000 );
        var tLast = (firstDay.clone().increment('month', 1)).clearTime();        
        var to = parseInt(tLast.getTime()/1000);
    
        this.getEventsInMonth(from, to);

    },
    
    _renderItem: function( pDate, pGray ){
        var myclass = 'ka-datePicker-item';      
        
        if( pDate.format('db') == this.choosenDate.format('db') )
            myclass = ' ka-datePicker-item-selected';

        if( pGray )
            myclass += ' ka-datePicker-item-gray';
      
        if(this.nowDate.format('db') == pDate.clearTime().format('db'))
                myclass += ' ka-datePicker-item-today';
         
      
        var td = new Element( 'td', {
            'class': myclass, 'rel' : pDate.format('%Y%m%d')
        }).inject( this.currentBodyTr );

        if(! pGray )
          new Element('a', {
              text: pDate.get('date'),
              'class': myclass
          })
          .addEvent('click', function(pItem){
              this.choose(pDate, pItem.target);
          }.bind(this))
          .inject( td );
    }
  
});


window.addEvent('domready', function() {
  new calendar($('calendar'));
})
