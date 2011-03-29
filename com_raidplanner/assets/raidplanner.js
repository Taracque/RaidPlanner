function rpSwitchTab(switchfrom,switchto) {
	if ($('rp_event_' + switchto) && $('rp_switcher_' + switchto) && $('rp_event_' + switchfrom) && $('rp_switcher_' + switchfrom)) {
		$('rp_event_' + switchto).setStyle('display','');
		$('rp_switcher_' + switchto).addClass('active');
		$('rp_event_' + switchfrom).setStyle('display','none');
		$('rp_switcher_' + switchfrom).removeClass('active');
	}
	return false;
}

function rpShowTooltip(el) {
	$(el).removeEvents('mouseleave');
	if ($(el).title) {
		$(el).addEvent('mouseleave',function(){
			rpHideTooltip();
		});
		$('rpTipWrapper').setText($(el).title);
		pos = $(el).getPosition();
		$('rpTip').setStyles({
				'left': pos.x + 0,
				'top': pos.y + 20
		});
		$('rpTip').setOpacity(0.8);
	}
}

function rpHideTooltip() {
	$('rpTip').setOpacity(0);
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