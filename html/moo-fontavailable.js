/* fontAvailable Mootools Plugin, v1.0
 *
 * Copyright (c) 2009, Eneko Alonso
 * Licensed under the MIT License
 * http://code.google.com/p/moo-fontavailable/
 *
 * Based on jQuery fontAvailable plugin:
 * http://code.google.com/p/jquery-fontavailable/
 *
 */

Element.implement({
	fontAvailable: function(fontName) {
		var el = new Element('span', {
			styles: {
				'font-family': '__FAKEFONT__',
				'visibility': 'hidden'
			},
			text: 'abcdefghijklmnopqrstuvwxyz'
		}).inject(this);

		var width    = el.getWidth();
		var newWidth = el.setStyle('font-family', fontName).getWidth();

		el.dispose();
		return width != newWidth;
	}
});
