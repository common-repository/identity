<?php
/**
 * Includes the main Class of the Plugin.
 *
 * @filesource
 * @author Stefan Herndler
 * @since 1.5.0 12.09.14 10:56
 */


/**
 * Entry point of the Plugin. Loads the Dashboard and executes the Task.
 *
 * @author Stefan Herndler
 * @since 1.0.0
 */
class MCI_Identity {

	/**
	 * Reference to the Plugin Task object.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 * @var null|MCI_Identity_Task
	 */
	public $a_obj_Task = null;

	/**
	 * Executes the Plugin.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	public function run() {
		// register language
		MCI_Identity_Language::registerHooks();
		// register general hooks
		MCI_Identity_Hooks::registerHooks();
		// initialize the Plugin Dashboard
		$this->initializeDashboard();
		// initialize the Plugin Task
		$this->initializeTask();

        // Register all Public Stylesheets
        add_action('init', array($this, 'registerPublicStyling'));
        // Enqueue all Public Stylesheets
        add_action('wp_enqueue_scripts', array($this, 'registerPublicStyling'));
	}

	/**
	 * Initializes the Dashboard of the Plugin and loads them.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	private function initializeDashboard() {
		new MCI_Identity_Layout_Init();
	}

	/**
	 * Initializes the Plugin Task and registers the Task hooks.
	 *
	 * @author Stefan Herndler
	 * @since 1.0.0
	 */
	private function initializeTask() {
		$this->a_obj_Task = new MCI_Identity_Task();
		$this->a_obj_Task->registerHooks();
	}

    /**
     * Registers and enqueue stylesheets to the public pages.
     *
     * @author Stefan Herndler
     * @since 1.5.0
     */
    public function registerPublicStyling() {
        wp_register_style('mci_identity_css_public', plugins_url('../css/public.css', __FILE__));
        wp_enqueue_style('mci_identity_css_public');
    }
}