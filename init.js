/*
 *  Place copyright or other info here...
 */

(function(global, $) {

	// Define core
	var codiad = global.codiad,
		scripts = document.getElementsByTagName('script'),
		path = scripts[scripts.length - 1].src.split('?')[0],
		curpath = path.split('/').slice(0, -1).join('/') + '/';

	// Instantiates plugin
	$(function() {
		codiad.authBasic.init();
	});

	codiad.authBasic = {

		// Allows relative `this.path` linkage
		path: curpath,

		init: function() {
			codiad.user.controller = this.path + 'controller.php';
		}

	};

})(this, jQuery);