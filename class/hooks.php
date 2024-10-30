<?php
/**
 * Handles all WordPress hooks of this Plugin.
 *
 * @filesource
 * @author Stefan Herndler
 * @since 1.5.0 12.09.14 10:56
 */

/**
 * Registers all WordPress Hooks and executes them on demand.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 */
class MCI_Identity_Hooks {

	/**
	 * Registers all WordPress hooks.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	public static function registerHooks() {
		register_activation_hook(dirname(__FILE__) . "/../identity.php", array("MCI_Identity_Hooks", "activatePlugin"));
		register_deactivation_hook(dirname(__FILE__) . "/../identity.php", array("MCI_Identity_Hooks", "deactivatePlugin"));
		register_uninstall_hook(dirname(__FILE__) . "/../identity.php", array("MCI_Identity_Hooks", "uninstallPlugin"));
	}

	/**
	 * Executed when the Plugin gets activated.
	 *
	 * @author Stefan Herndler
	 * @since 1.5.0
	 */
	public static function activatePlugin() {
		// currently unused
	}

	/**
	 * Executed when the Plugin gets deactivated.
	 *
	 * @author Stefan Herndler
	 * @since 1.5.0
	 */
	public static function deactivatePlugin() {
		// currently unused
	}

	/**
	 * Executed when the Plugin gets uninstalled.
	 *
	 * @author Stefan Herndler
	 * @since 1.5.0
	 */
	public static function uninstallPlugin() {
		// WordPress User has to be logged in
		if (!is_user_logged_in()) {
			wp_die(__('You must be logged in to run this script.', MCI_Identity_Config::C_STR_PLUGIN_NAME));
		}
		// WordPress User needs the permission to (un)install plugins
		if (!current_user_can('install_plugins')) {
			wp_die(__('You do not have permission to run this script.', MCI_Identity_Config::C_STR_PLUGIN_NAME));
		}
		// deletes all settings and restore the default values
		MCI_Identity_Settings::instance()->ClearAll();
	}

	/**
	 * Add Links to the Plugin in the "installed Plugins" page.
	 *
	 * @author Stefan Herndler
	 * @since 1.5.0
	 * @param array $p_arr_Links Current Links.
	 * @param string $p_str_PluginFileName Plugins init file name.
	 * @return array
	 */
	public static function PluginLinks($p_arr_Links, $p_str_PluginFileName) {
		// append link to the WordPress Plugin page
		$p_arr_Links[] = sprintf('<a href="http://wordpress.org/support/plugin/identity" target="_blank">%s</a>', __('Support', MCI_Identity_Config::C_STR_PLUGIN_NAME));
		// append link to the Settings page
		$p_arr_Links[] = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=mfmmf-identity'), __('Settings', MCI_Identity_Config::C_STR_PLUGIN_NAME));
		// return new links
		return $p_arr_Links;
	}
}