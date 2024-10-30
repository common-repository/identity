<?php
/**
 * Created by Stefan Herndler.
 * User: Stefan
 * Date: 15.08.14 14:03
 * Version: 1.0.0
 * Since: 0.0.1
 */

/**
 * Class Identity_PublicTask
 */
class MCI_Identity_Task {

	/**
	 * @constructor
	 * @since 1.0.0
	 */
	public function __construct() {

	}

	/**
	 * register WordPress hooks for replacing short codes in public pages
	 * @since 1.0.0
	 */
	public function RegisterHooks() {
		// register WordPress hooks for public page content with a really low priority to be executed after all other Plugins
		add_filter('the_content', array($this, "exec"), PHP_INT_MAX);
		add_filter('the_excerpt', array($this, "exec"), PHP_INT_MAX);
		add_filter('widget_title', array($this, "exec"), PHP_INT_MAX);
		add_filter('widget_text', array($this, "exec"), PHP_INT_MAX);
	}

	/**
	 * replace all short codes in specific content with the defined CI
	 * @since 1.0.0
	 * @param string $p_str_Content
	 * @return string
	 */
	public function exec($p_str_Content) {
		// return the content with replaced CI
		return preg_replace_callback("/(<([^.]+)>)([^<]+)(<\\/\\2>)/s", array(&$this, 'replace'), $p_str_Content);
	}

    /**
     * Uses regular expression to find and replace each tag.
     *
     * Parameter array keys:
     * 0 - full tag
     * 1 - open tag, for example <h1>
     * 2 - tag name h1
     * 3 - content
     * 4 - closing tag
     *
     * @author Stefan Herndler
     * @since 1.0.2
     * @param array $p_arr_Matches
     * @return string
     */
    private function replace($p_arr_Matches) {
        // get text inside a html tag
        $l_str_Text = $p_arr_Matches[3];
        // iterate through each CI Tag
        foreach(MCI_Identity_Settings::instance()->getAllTags() as $l_str_Tag) {
            // unique Tag ID is empty
            if (empty($l_str_Tag)) {
                continue;
            }
            // initialize new Tag
            $l_obj_Tag = new MCI_Identity_Tag($l_str_Tag);
            // get old string for the Tag
            $l_str_OldString = $l_obj_Tag->getOldString();
            // old string is empty
            if (empty($l_str_OldString)) {
                continue;
            }
            // define additional regular expression
            $l_str_ReplaceFunction = "str_replace";
            // check if case insensitive
            if (MCI_Identity_Convert::toBool($l_obj_Tag->getAttribute("case"))) {
                $l_str_ReplaceFunction = "str_ireplace";
            }
            // replace old text with new text
            $l_str_Text = $l_str_ReplaceFunction($l_str_OldString, $l_obj_Tag->getNewString(), $l_str_Text);
        }
        // return html open tag - new text - html close tag
        return $p_arr_Matches[1] . $l_str_Text . $p_arr_Matches[4];
    }
}