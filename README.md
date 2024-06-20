Inokufu Search - Repository Plugin for the Moodle™ platform
=================================

The Inokufu Search - Repository Plugin for the Moodle™ platform is part of the set of plugins required to integrate Inokufu Search into your Moodle™ platform. 
This set also includes:
- [Inokufu Search - Local Services plugin pour la plateforme Moodle™](https://github.com/inokufu/moodle-local_inokufu), 
- [Inokufu Search - Atto plugin for the Moodle™ platform](https://github.com/inokufu/moodle-atto_inokufu), 
- [Inokufu Search - TinyMCE plugin for the Moodle™ platform](https://github.com/inokufu/moodle-tinymce_inokufu). 

This plugin enables users to access Inokufu Search technology, and add Learning Objects to courses of your Moodle™ platform.
This documentation will guide you through the installation and usage of the plugin.

**Note:** This Moodle Plugin is only useful when combined with our `Inokufu Search - Moodle Local Services Plugin`. Please be sure to have it installed and configured before installing this plugin.

Please find a French version of this documentation [here](./README.fr.md).

## Installation

### Installation from ZIP
1. Download the plugin zip file from this GitHub repository.
2. Log in to your Moodle site as an administrator.
3. Navigate to `Site administration > Plugins > Install plugins`.
4. Upload the zip file you downloaded from this repository and follow the on-screen instructions.
5. Complete and confirm the forms to finish the plugin installation.

### Installation from Source
1. Establish an SSH connection to your Moodle instance.
2. Clone the source files from this GitHub repository directly into your Moodle source files.
3. Rename the cloned folder to `inokufu`.
4. Move the `inokufu` folder into the `repository` directory of your Moodle installation. Ensure that the plugin folder is named `inokufu`.
5. Log in to your Moodle site as an administrator.
6. Navigate to `Site administration > Notifications` to finalize the plugin installation.

## Configuration
1. After successful installation, navigate to `Site administration > Plugins > Repositories > Manage repositories` to configure the plugin settings.
2. Activate the `Inokufu Search` repository plugin by switching it to `Enabled and visible`.
3. Navigate to the plugin settings, by clicking on `Settings` (next to `Inokufu Search` and `Enabled and visible`), or by navigating to `Site administration > Plugins > Repositories > Inokufu Search`.
4. Enter the Plugin Name (Optional).
5. Save changes, and start using the repository plugin.

## Troubleshooting
If you encounter any issues with the plugin, please check the following:
1. Ensure your Moodle site meets the minimum requirements for the plugin.
2. Verify that your API Key is correctly filled in, and valid.
3. Check the Moodle log (`Site administration > Reports > Logs`) for any error messages related to the plugin.
4. If none of these steps helped, feel free to contact our [Inokufu Support](https://support.inokufu.com/).

## Support
For additional support or to report a bug, please visit the plugin's GitHub repository and open an `issue`. Be sure to include any relevant details, such as your Moodle version, plugin version, and a detailed description of the issue.
