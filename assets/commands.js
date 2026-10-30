/* Register navigation shortcuts in the native WordPress Command Palette. */
(function () {
	'use strict';
	if (!window.wp || !wp.data || !wp.commands || !Array.isArray(window.blpCommands)) {
		return;
	}
	var actions = wp.data.dispatch(wp.commands.store);
	if (!actions || typeof actions.registerCommand !== 'function') {
		return;
	}
	window.blpCommands.forEach(function (command) {
		actions.registerCommand({
			name: command.name,
			label: command.label,
			category: 'view',
			callback: function () {
				if (typeof actions.close === 'function') {
					actions.close();
				}
				window.location.assign(command.url);
			}
		});
	});
}());
