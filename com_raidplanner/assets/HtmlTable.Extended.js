/*
---

script: HtmlTable.Extended.js

description: Adds a pagination control to a HtmlTable, also add the ablity to filter the HtmlTable.

license: MIT-style license

author:
- Oran Leiba, etootim.com
- Taracque

requires:
- /core/1.2.4:* 
- /More
- /HtmlTable
- /Class.refactor
- /Class.Occlude

provides: [HtmlTable.Extended]

...
*/

HtmlTable = Class.refactor(HtmlTable, {

	options: {
		classPaginationControlContainer:'paginationcc',
		paginate: true,//whether to add pagination or not
        classTablePagination: 'table-paginatable',
        paginatePage:1,
        paginateRows:10,//number of rows to paginate
        paginateRowsSelector:[10,25,50],//array of number of rows to show in the selector
        paginationControlPages:10,//default number of rows
        listenToPush:false,
        classHeaderPaginationContorlTH:'th-pagination-control',
        classHeaderPaginationContorlTR:'tr-pagination-control',
        classHeaderPaginationContorlDiv:'div-pagination-control',
        classHeaderPaginationContorlUL:'ul-pagination-control',
        classHeaderPaginationContorlLI:'li-pagination-control',
        classHeaderNumOfRowsContorlUL:'ul-numOfRows-control',
        classHeaderNumOfRowsContorlLI:'li-numOfRows-control',
        classHeaderFilterContorlDiv:'div-filter-control',
        controlDiv:null,
        pageCtrl:null,
        rowsCtrl:null,
        filterable:true,
        filterEl:null,
        strings:{
            next:'Next',
            previous:'Previous',
            rows:'Rows',
            search : 'Search…'
        },
        _zebraCounter:0
	},

	initialize: function(){
		this.previous.apply(this, arguments);
		if (this.occluded) return this.occluded;

		var numOfHeaders = this.thead.rows[0].getElements('th').length;
		var tr = new Element('tr',{'class':this.options.classHeaderPaginationContorlTR});
		tr.inject(this.thead.rows[0], 'before');
		var th = new Element('th',{'class':this.options.classHeaderPaginationContorlTH}).inject(tr);
		th.addClass(this.options.classNoSort);//to avoid sorting on the control header click
		th.setProperty('colspan',numOfHeaders);
		var controlDiv = new Element('div',{'class':this.options.classHeaderPaginationContorlDiv}).inject(th);
		this.options.controlDiv = controlDiv;

		this.options.pageCtrl = new Element('ul',{'class':this.options.classHeaderPaginationContorlUL}).inject(this.options.controlDiv);
		
		if(this.options.filterable){
			this.options.filterEl = new Element('input',{'type':'text','size':20});
			this.options.filterEl.addEvent('keyup',(function(e){ this.filter(this.options.filterEl.get('value')); }).bind(this));
			var div = new Element('div',{'class':this.options.classHeaderFilterContorlDiv}).inject(this.options.controlDiv);
			this.options.filterEl.inject(div);
			if (window.OverText){
				new OverText(this.options.filterEl,{textOverride:this.options.strings.search});
			}
		}

		this.options.rowsCtrl = new Element('ul',{'class':this.options.classHeaderNumOfRowsContorlUL}).inject(this.options.controlDiv);

		if(this.options.paginate){
			if(this.paginationInitialized==null){
				this.paginationInitialized = true;
			}
		}
		return true;
	},

	applyPagination: function(){
		this.options.paginate = true;
		this.addPaginationControl();
		this.updatePagination();
	},

	addPaginationControl: function(){
		this.options.paginationControlContainer = new Element('div',{'class':this.options.classPaginationControlContainer});
		this.options.paginationControlContainer.set('html','This IS the added control');
		this.options.paginationControlContainer.inject(this.element,'before');
	},

	updatePaginationControl: function(){
		if($defined((this.options.controlDiv))){
			this.options.pageCtrl.empty();
			this.options.rowsCtrl.empty();
			if($defined(this.body.rows)){
				var numOfRows = 0;
				for (row=0;row<this.body.rows.length;row++){
					if (!this.body.rows[row].hasClass('filtered')){
						numOfRows++;
					}
				}
				if(numOfRows>0){
					var numOfPages = Math.ceil(numOfRows/this.options.paginateRows);
					var numOfLi = Math.min(this.options.paginationControlPages, numOfPages);
					var startIndex = Math.min((numOfPages-numOfLi),Math.max(0,this.options.paginatePage-1-Math.floor((numOfLi-1)/2)));
					var endIndex = Math.max((numOfLi),Math.min(numOfPages,this.options.paginatePage+Math.floor((numOfLi-1)/2)));
					
					var liPrevious = new Element('li',{'class':this.options.classHeaderPaginationContorlLI}).inject(this.options.pageCtrl);
					var liPreviousSpan = new Element('span').inject(liPrevious);
					liPreviousSpan.set('html',this.options.strings.previous);
					liPrevious.store('pagination',this.options.paginatePage-1);
					liPreviousSpan.addEvent('click',function(){
						this.updatePagination(arguments[0].retrieve('pagination'));
					}.bind(this).pass(liPrevious));
					if(this.options.paginatePage==1){
						liPrevious.setStyle('visibility','hidden');
					}

					if((endIndex-startIndex)>1){//avoid 1 page pagination
						for(var i=startIndex;i<endIndex;i++){
							var li = new Element('li',{'class':this.options.classHeaderPaginationContorlLI}).inject(this.options.pageCtrl);
							var span = new Element('span').inject(li);
							span.set('html',i+1);
							li.store('pagination',i+1);
							if(this.options.paginatePage!=i+1){
								 span.addEvent('click',function(){
									this.updatePagination(arguments[0].retrieve('pagination'));
								}.bind(this).pass(li));
							}else{
								li.addClass('li-pagination-current');
							}
						}
					}
					
					var liNext = new Element('li',{'class':this.options.classHeaderPaginationContorlLI}).inject(this.options.pageCtrl);
					var liNextSpan = new Element('span').inject(liNext);
					liNextSpan.set('html',this.options.strings.next);
					liNext.store('pagination',this.options.paginatePage+1);
					liNextSpan.addEvent('click',function(){
						this.updatePagination(arguments[0].retrieve('pagination'));
					}.bind(this).pass(liNext));
					if(numOfPages<=this.options.paginatePage){
						liNext.setStyle('visibility','hidden');
					}

					//add number of rows selector
					if(this.options.paginateRowsSelector!=null && this.options.paginateRowsSelector.length>1){
						var liRows = new Element('li',{'class':this.options.classHeaderNumOfRowsContorlLI}).inject(this.options.rowsCtrl);
						liRows.addClass('static');
						var rowSpan = new Element('span').inject(liRows);
						rowSpan.set('html',this.options.strings.rows);
						this.options.paginateRowsSelector.each(function(curVal){
						   var li = new Element('li',{'class':this.options.classHeaderNumOfRowsContorlLI}).inject(this.options.rowsCtrl);
						   li.store('rowCount',curVal);
						   var span = new Element('span').inject(li);
						   span.set('html',curVal);
						   if(this.options.paginateRows==curVal){
							   li.addClass('li-numOfRows-current');
						   }else{
							   span.addEvent('click',function(){
								   this.options.paginateRows = arguments[0].retrieve('rowCount');
								   this.updatePagination(1);
							   }.bind(this).pass(li));
						   }
						}.bind(this));
					}
					
					if (window.OverText){
						this.options.filterEl.retrieve('OverText').reposition();
					}
				}
			}
		}
	},

	updatePagination: function(paginationPage){
		if(paginationPage==null){
			this.options.paginatePage = 1;
		}else{
			this.options.paginatePage = paginationPage;
		}
		this.fireEvent('paginationStart');
		var startIndex = Math.max(0,(this.options.paginatePage-1)*this.options.paginateRows);// this.paginateStartIndex;//(paginatePage-1)*this.options.paginateRows
		var endIndex = Math.min(this.options.paginateRows*this.options.paginatePage-1, this.body.rows.length-1);
		var i = 0;
		for (row=0;row<this.body.rows.length;row++){
			if(i<startIndex || i>endIndex || this.body.rows[row].hasClass('filtered')){
				this.body.rows[row].setStyle('display','none');
			}else{
				this.body.rows[row].setStyle('display','table-row');
			}
			if (!this.body.rows[row].hasClass('filtered')){
				i++;
			}
		}
		
		this.updatePaginationControl();
		this.fireEvent('paginationComplete');
	},

	sort: function(){
		var sorted = this.previous.apply(this, arguments);
		if (this.options.paginate){
			this.options.paginatePage = 1;
			this.updatePagination();
		}
		return sorted;
	},

	push: function(){
		var pushed = this.previous.apply(this, arguments);
		if (this.options.listenToPush) this.updatePagination();
		return pushed;
	},
	zebra: function(row, i){
		if (i==0){
			this.options._zebraCounter=0;
		}
		if (!row.hasClass('filtered')){
			this.options._zebraCounter++;
		}
	    return row[((this.options._zebraCounter % 2) ? 'remove' : 'add')+'Class'](this.options.classZebra);
	},
	filter: function(term){
		var terms = term.toLowerCase().split(" ");
		//for each row of table execute
		for (var r = 0, m = this.body.rows.length; r < m; r++) {
			var display = '';
			//for each term do
			for (var i = 0, j = terms.length; i < j; i++) {
				//strips all tags from row, then test if the 
				//row contains or not the appropriate filter term.
				if (this.body.rows[r].innerHTML.replace(/<[^>]+>/g, "").toLowerCase().indexOf(terms[i]) < 0) {
					this.body.rows[r].addClass('filtered');
				} else {
					this.body.rows[r].removeClass('filtered');
				}
			}
		}
		this.updatePagination();
		if (this.options.zebra){
			this.updateZebras();
		}
	}
});