# MauticRebrandingBundle

Mautic plugin for rebranding.

## Features
- Change the logo and brand name displayed in the upper-left corner of the Mautic interface.
- Update the logo on the login page.
- Update element colors.
- Revert to the original Mautic branding if needed.

## Compatibility
This plugin is compatible with Mautic 5.2.

## Installation
1. Clone this repository into your Mautic plugins directory.
2. Run `php bin/console cache:clear`.
3. Run `php bin/console mautic:plugins:reload` to load the plugin.
4. Navigate to the "Rebranding Settings" menu in the Mautic admin panel to configure the plugin.

## Usage
1. Update the brand name and logo in the "Rebranding Settings" page.
2. Save the changes to apply the new branding.

## Reverting to Original Branding
1. Use the "Revert to Default" button in the "Rebranding Settings" page.
2. If the revert action fails, manually delete the configuration file located at:
   ```
   plugins/RebrandingBundle/Config/config.json
   ```
3. Clear your browser cache to ensure the original branding is displayed.

## Troubleshooting
- If the plugin does not work as expected, ensure that the required PHP version (>=7.4) is installed.
- Check the browser console for errors and ensure the plugin's assets are loaded correctly.
