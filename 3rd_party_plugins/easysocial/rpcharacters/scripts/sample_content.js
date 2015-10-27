EasySocial.module('fields/user/rpcharacters/sample_content', function($) {
	var module = this;

	EasySocial.Controller('Field.RPCharacters.Sample', {
		defaultOptions: {
			'{input}'			: '[data-input]',
		}
	}, function(self) {
		return {
			init: function() {

			},

			'{self} onConfigChange': function(el, event, name, value) {
				switch(name) {
					case 'default':
						self.input().val(value);
					break;
				}
			}
		}
	});

	module.resolve();
});
