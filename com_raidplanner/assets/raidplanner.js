function rpSwitchTab(switchto) {
	$$('.rp_switchers').each(function(el){
		el.removeClass('active');
		if (el.get('id')) {
			$(el.get('id').replace('rp_switcher', 'rp_event')).setStyle('display','none');
		}
	});
	if ($('rp_event_' + switchto) && $('rp_switcher_' + switchto)) {
		$('rp_event_' + switchto).setStyle('display','');
		$('rp_switcher_' + switchto).addClass('active');
	}
	return false;
}

function rpShowTooltip(el) {
	$(el).removeEvents('mouseleave');
	if ($(el).title) {
		$(el).addEvent('mouseleave',function(){
			rpHideTooltip();
		});
		$('rpTipWrapper').set('text', $(el).title);
		pos = $(el).getPosition();
		$('rpTip').setStyles({
				'left': pos.x + 0,
				'top': pos.y + 20,
				'visibility': 'visible'
		});
		$('rpTip').setOpacity(0.8);
	}
}

function rpHideTooltip() {
	$('rpTip').setOpacity(0);
	$('rpTip').setStyle('visibility','hidden');
}

function setupTooltip() {
	if (!($('rpTip'))) {
		(new Element('div', {
				'class': 'rp_tool-tip',
				'id' : 'rpTip',
				'styles': {
					'position': 'absolute',
					'top': '0',
					'left': '0',
					'visibility': 'hidden',
					'opacity' : 0,
					'z-index' : 70000
				}
		})).inject(document.body);
		(new Element('div',{'id':'rpTipWrapper'})).inject($('rpTip'));
	}
}

window.addEvent('domready',function() {
	if ((MooTools.version >= '1.3') && (typeof(SqueezBox)!='undefined')) {
		SqueezeBox.handlers.extend({
			ajax: function(url) {
				var options = this.options.ajaxOptions || {};
				this.asset = new Request.HTML($merge({
					method: 'get',
					evalScripts: false,
					onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript) {
						this.applyContent(responseHTML);
						if (options.evalScripts !== null && options.evalScripts) $exec(responseJavaScript);
						this.fireEvent('onAjax', [responseTree, responseElements, responseHTML, responseJavaScript]);
						this.asset = null;
					}.bind(this),
					onFailure: this.onError.bind(this)
				}, this.options.ajaxOptions));
				this.asset.send.delay(10, this.asset, [{url: url}]);
			}
		}); 
	}
	if ($('roster_table')) {
		if ((MooTools.version >= '1.2.4') && (typeof(HtmlTable)!='undefined')) {
			var rosterTable = new HtmlTable(
				$('roster_table'),
				{
					properties: {
						border: 0,
						cellspacing: 1,
						cellpadding: 5
					},
					sortable :true,
					zebra: true,
					selectable: true,
					allowMultiSelect: false,
					paginate:true,
					paginateRows:25,
					paginationControlPages:25,
					filterEl:$('roster_filter')
	        	}
			).updatePagination();
		}
	}
	setupTooltip();
});