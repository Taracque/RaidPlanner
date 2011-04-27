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
	setupTooltip();
});