<?php
/**
 * Includes the Plugin Class to display all Settings.
 *
 * @filesource
 * @author Stefan Herndler
 * @since 1.5.0 14.09.14 14:47
 */

/**
 * Displays and handles all Settings of the Plugin.
 *
 * @author Stefan Herndler
 * @since 1.5.0
 */
class MCI_Identity_Layout_Settings extends MCI_Identity_LayoutEngine {

	/**
	 * Returns a Priority index. Lower numbers have a higher Priority.
	 *
	 * @author Stefan Herndler
	 * @since 1.5.0
	 * @return int
	 */
	public function getPriority() {
		return 11;
	}

	/**
	 * Returns the unique slug of the sub page.
	 *
	 * @author Stefan Herndler
	 * @since 1.5.0
	 * @return string
	 */
	protected function getSubPageSlug() {
		return "-" . MCI_Identity_Config::C_STR_PLUGIN_NAME;
	}

	/**
	 * Returns the title of the sub page.
	 *
	 * @author Stefan Herndler
	 * @since 1.5.0
	 * @return string
	 */
	protected function getSubPageTitle() {
		return MCI_Identity_Config::C_STR_PLUGIN_PUBLIC_NAME;
	}

	/**
	 * Returns an array of all registered sections for the sub page.
	 *
	 * @author Stefan Herndler
	 * @since  1.5.0
	 * @return array
	 */
	protected function getSections() {
		return array(
			$this->addSection("settings", __("Settings", MCI_Identity_Config::C_STR_PLUGIN_NAME), 0, true)
		);
	}

	/**
	 * Returns an array of all registered meta boxes for each section of the sub page.
	 *
	 * @author Stefan Herndler
	 * @since  1.5.0
	 * @return array
	 */
	protected function getMetaBoxes() {
		return array(
			$this->addMetaBox("settings", "customize", __("Customize any word on public pages", MCI_Identity_Config::C_STR_PLUGIN_NAME), "customTags")
		);
	}

	/**
	 * Displays all settings for the reference container.
	 *
	 * @author Stefan Herndler
	 * @since 1.5.0
	 */
	public function customTags() {
        // iterate through each CI Tag from storage
        foreach(MCI_Identity_Settings::instance()->getAllTags() as $l_str_Tag) {
            if (!empty($l_str_Tag)) {
                echo $this->addTag($l_str_Tag);
            }
        }
        // add a new table row
        echo $this->addTag();

        echo '<script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery(".MCI_identity_color_picker").wpColorPicker();
                });
            </script>';
	}

    /**
     * generates a table row for a specific ID or generates a new one with an unique ID and returns it
     * @since 1.0.0
     * @param string $p_str_ID
     * @return string
     */
    private function addTag($p_str_ID = "") {
        global $g_obj_MCI_Identity;
        // define new Tag object
        $l_obj_Tag = null;
        $l_str_OldString = "";

        // load Tag from ID
        if (!empty($p_str_ID)) {
            $l_obj_Tag = new MCI_Identity_Tag($p_str_ID);
            // get old string for the Tag
            $l_str_OldString = $l_obj_Tag->getOldString();
            // exit here if old string is empty
            if (empty($l_str_OldString)) {
                return "";
            }
        } else {
            // create a new Tag with an unique ID
            $l_obj_Tag = new MCI_Identity_Tag("tag-" . date("YmdHis") . "-" . rand(10,9999) . "-");
        }

        // load template file
        $l_obj_Template = new MCI_Identity_Template(MCI_Identity_Template::C_STR_DASHBOARD, "settings-custom-tags");
        // replace all placeholders
        $l_obj_Template->replace(
            array(
                "label-custom-word" => __("Word being customized", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "custom-word" => $this->addTextBox($l_obj_Tag->getKey("tag")),

                "label-replace-word" => __("Replace word with", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "replace-word" => $this->addTextBox($l_obj_Tag->getKey("replaced")),

                "label-font-family" => __("Font family", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "font-family" => $this->addTextBox($l_obj_Tag->getKey("font")),

                "label-fore-color" => __("Color", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "fore-color" => $this->addTextBox($l_obj_Tag->getKey("forecolor"), "MCI_identity_color_picker"),

                "label-background-color" => __("Background color", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "background-color" => $this->addTextBox($l_obj_Tag->getKey("background"), "MCI_identity_color_picker"),

                "label-hyperlink" => __("Hyperlink", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "hyperlink" => $this->addTextBox($l_obj_Tag->getKey("hyperlink")),

                "label-bold" => __("Bold", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "bold" => $this->addCheckbox($l_obj_Tag->getKey("bold")),

                "label-italic" => __("Italic", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "italic" => $this->addCheckbox($l_obj_Tag->getKey("italic")),

                "label-underline" => __("Underlined", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "underline" => $this->addCheckbox($l_obj_Tag->getKey("underline")),

                "label-line-through" => __("Line through", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "line-through" => $this->addCheckbox($l_obj_Tag->getKey("stroke")),

                "label-case-sensitive" => __("Case sensitive", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "case-sensitive" => $this->addCheckbox($l_obj_Tag->getKey("case")),

                "label-preview" => __("Preview", MCI_Identity_Config::C_STR_PLUGIN_NAME),
                "preview" => $g_obj_MCI_Identity->a_obj_Task->exec("<span>" . $l_str_OldString . "</span>"),

                "id" => $this->addTextBox($l_obj_Tag->getID(), "", true, true)
            )
        );
        // display template with replaced placeholders
        return $l_obj_Template->getContent() . "<br/><hr/><br/>";
    }
}