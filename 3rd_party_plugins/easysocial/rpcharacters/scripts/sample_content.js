EasySocial.module('fields/user/textbox/sample_content', function($) {
	var module = this;

	EasySocial.Controller('Field.Textbox.Sample', {
		defaultOptions: {
			'{input}'			: '[data-input]',

			'min'					: '',
			'max'					: '',
			'regex_validate'		: false,
			'regex_format'			: '',
			'regex_modifier'		: ''
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'placeholder':
						self.input().attr('placeholder', value);
					break;

					case 'default':
						self.input().val(value);
					break;

					case 'readonly':
						if(value) {
							self.input().attr('disabled', 'disabled');
						} else {
							self.input().removeAttr('disabled');
						}
						break;
					break;
				}
			}
		}
	});

	module.resolve();
});
