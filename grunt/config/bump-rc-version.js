const { flattenVersionForFile } = require( "../../webpack/paths" );

/**
 * Grunt task to bump the RC version.
 *
 * @param {Object} grunt The grunt helper object.
 *
 * @returns {void}
 */
module.exports = function( grunt ) {
	grunt.registerTask(
		"bump-rc-version",
		"Bumps the versions to the next RC",
		function() {
			// Parse the command line options.
			const pluginVersionArgument = grunt.option( "plugin-version" );
			const releaseTypeArgument = grunt.option( "type" );

			// Check if arguments were passed.
			if ( ! pluginVersionArgument ) {
				grunt.fail.fatal( "Missing --plugin-version argument" );
			}

			if ( ! releaseTypeArgument ) {
				grunt.fail.fatal( "Missing --type argument" );
			}

			// Retrieve the current plugin version from package.json.
			const packageJson = grunt.file.readJSON( "package.json" );
			const pluginVersionPackageJson = packageJson.yoast.pluginVersion;

			// Strip off the RC part from the current plugin version.
			const parsedVersion = pluginVersionPackageJson.split( "-RC" );

			// From the resulting array, get the first value (the second value is the RC number).
			const strippedVersion = parsedVersion[ 0 ];

			// Declare the new plugin version variable.
			let newPluginVersion = pluginVersionArgument;

			/*
			If the package.json had a version that contained "-RC", the number following that will be incremented by 1.
			Otherwise, this is the first RC, so we set the RC version to 0, in order to add 1 and end up at "-RC1".
			*/
			if ( pluginVersionArgument === strippedVersion ) {
				const currentRCVersion = parsedVersion[ 1 ] || "0";
				const bumpedRCVersion = parseInt( currentRCVersion, 10 ) + 1;
				newPluginVersion += "-RC" + bumpedRCVersion;
			} else {
				// Else, the RC is 1.
				newPluginVersion += "-RC1";
			}

			// eslint-disable-next-line no-console
			console.log( "Bumped the plugin version to " + newPluginVersion + "." );

			// Set the plugin version to the bumped version in package.json.
			grunt.option( "new-version", newPluginVersion );
			grunt.task.run( "set-version" );

			// The below command is needed to make the below 'update-version-trunk' work.
			// This is because 'update-version-trunk' uses 'pluginVersion' from Gruntfile.js.
			// Which is taken from package.json BEFORE package.json is updated by our above code.
			grunt.config.data.pluginVersion = newPluginVersion;
			grunt.config.data.pluginVersionSlug = flattenVersionForFile( newPluginVersion );

			// Set the plugin version to the bumped version in the plugin files.
			grunt.task.run( "update-version-trunk" );
		}
	);
};
