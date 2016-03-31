EasySocial.module('fields/user/textbox/content', function($) {
	var module = this;

	EasySocial
		.require()
		.language(
			'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_REQUIRED',
			'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_TOO_SHORT',
			'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_TOO_LONG',
			'PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_INVALID_FORMAT')
		.done(function($) {
			EasySocial.Controller('Field.Textbox', {
				defaultOptions: {
					required: false,

					min: 0,
					max: 0,

					'{field}': '[data-field-textbox]',

					'{input}': '[data-field-textbox-input]',

					'{notice}': '[data-check-notice]'
				}
			}, function(self) {
				return {
					init: function() {
						self.options.min = self.field().data('min');
						self.options.max = self.field().data('max');
					},

					'{input} keyup': function()
					{
						self.validateInput();
					},

					'{input} blur': function()
					{
						self.validateInput();
					},

					validateInput: function()
					{
						self.clearError();

						var value = self.input().val();

						if(self.options.required && $.isEmpty(value)) {
							self.raiseError($.language('PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_REQUIRED'));
							return false;
						}

						if(!$.isEmpty(value) && self.options.min > 0 && value.length < self.options.min) {
							self.raiseError($.language('PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_TOO_SHORT'));
							return false;
						}

						if(self.options.max > 0 && value.length > self.options.max) {
							self.raiseError($.language('PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_TOO_LONG'));
							return false;
						}

						return true;
					},

					raiseError: function(msg) {
						self.trigger('error', [msg]);
					},

					clearError: function() {
						self.trigger('clear');
					},

					'{self} onError': function(el, ev, type) {
						if(type === 'required') {
							self.raiseError($.language('PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_REQUIRED'));
						}

						if(type === 'validate') {
							self.raiseError($.language('PLG_FIELDS_TEXTBOX_VALIDATION_INPUT_INVALID_FORMAT'));
						}
					},

					'{self} onSubmit': function(el, ev, register) {
						register.push(self.validateInput());
					}
				}
			});

			module.resolve();
		});
});
