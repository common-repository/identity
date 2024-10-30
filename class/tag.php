<?php
/**
 * Created by Stefan Herndler.
 * User: Stefan
 * Date: 21.08.14 19:14
 * Version: 1.0.0
 * Since: 0.0.1
 */


/**
 * Class Identity_Tag
 */
class MCI_Identity_Tag {
	// array, contains all attributes for the specified tag
	// @since 1.0.0
	private $a_arr_Attributes = array();

	// string, contains the unique ID for the specified tag
	// @since 1.0.0
	private $a_str_ID = "";

	/**
	 * @constructor
	 * @since 1.0.0
     * @param string $p_str_Tag
	 */
	public function __construct($p_str_Tag) {
		// set unique Tag ID
		$this->a_str_ID = $p_str_Tag;
		// load settings for current Tag
		$this->addAttribute("tag");
		$this->addAttribute("replaced");
		$this->addAttribute("font");
		$this->addAttribute("forecolor");
		$this->addAttribute("background");
		$this->addAttribute("hyperlink");
		$this->addAttribute("bold");
		$this->addAttribute("italic");
		$this->addAttribute("underline");
		$this->addAttribute("stroke");
		$this->addAttribute("case");
	}

	/**
	 * returns the specified text that should be replaced on public pages
	 * @since 1.0.0
	 * @return string
	 */
	public function getOldString() {
		return $this->getAttribute("tag");
	}

	/**
	 * returns the new string with all html attributes to be placed on public pages instead of the old one
	 * @since 1.0.0
	 * @return string
	 */
	public function getNewString() {
		// define new text
		$l_str_Text = $this->getAttribute("replaced");
		// generate styling
		$l_str_Style = "";
		// define output stream
		$l_str_OutputStream = "";

		// check if new text is empty
		if(empty($l_str_Text)) {
			$l_str_Text = $this->getOldString();
		}

		// append font if set
		$l_str_Font = $this->getAttribute("font");
		if (!empty($l_str_Font)) {
			$l_str_Style .= 'font-family:' . $l_str_Font . ';';
		}
		// append fore color if set
		$l_str_ForeColor = $this->getAttribute("forecolor");
		if (!empty($l_str_ForeColor)) {
			$l_str_Style .= 'color:' . $l_str_ForeColor . ';';
		}
		// append background color if set
		$l_str_Background = $this->getAttribute("background");
		if (!empty($l_str_Background)) {
			$l_str_Style .= 'background-color:' . $l_str_Background . ';';
		}
		// append bold if set
		$l_bool_Bold = MCI_Identity_Convert::toBool($this->getAttribute("bold"));
		if (!empty($l_bool_Bold)) {
			$l_str_Style .= 'font-weight:bold;';
		}
		// append italic if set
		$l_bool_Italic = MCI_Identity_Convert::toBool($this->getAttribute("italic"));
		if (!empty($l_bool_Italic)) {
			$l_str_Style .= 'font-style:italic;';
		}
		// append underline and line-through
		$l_bool_Underline = MCI_Identity_Convert::toBool($this->getAttribute("underline"));
		$l_bool_Stroke = MCI_Identity_Convert::toBool($this->getAttribute("stroke"));
		if (!empty($l_bool_Underline) && !empty($l_bool_Stroke)) {
			$l_str_Style .= 'text-decoration:underline line-through;';
		// append underline if set
		} else if (!empty($l_bool_Underline)) {
			$l_str_Style .= 'text-decoration:underline;';
		// append line-through if set
		} else if (!empty($l_bool_Stroke)) {
			$l_str_Style .= 'text-decoration:line-through;';
		} else {
			$l_str_Style .= 'text-decoration:none;';
		}

		// append hyperlink if set
		$l_str_Hyperlink_URL = $this->getAttribute("hyperlink");
		if (!empty($l_str_Hyperlink_URL)) {
			// append HTTP protocol to URL if not set
			if (strpos($l_str_Hyperlink_URL, "http") === false) {
				$l_str_Hyperlink_URL = "http://" . $l_str_Hyperlink_URL;
			}
			// overwrite inner text to be a hyperlink
			$l_str_OutputStream = sprintf('<a href="%s" target="_blank" style="text-decoration:none;"><span style="%s">%s</span></a>', $l_str_Hyperlink_URL, $l_str_Style, $l_str_Text);
		} else {
			// no hyperlink set
			$l_str_OutputStream .= sprintf('<span style="%s">%s</span>', $l_str_Style, $l_str_Text);
		}
		// return new string
		return $l_str_OutputStream;
	}


	/**
	 * appends a attribute to the class
	 * @since 1.0.0
	 * @param string $p_str_Key
	 * @return bool
	 */
	private function addAttribute($p_str_Key) {
		$this->a_arr_Attributes[$p_str_Key] = MCI_Identity_Settings::instance()->get($this->a_str_ID . $p_str_Key);
		return true;
	}

	/**
	 * returns the value of a specified key or an empty string if invalid key given
	 * @since 1.0.0
	 * @param string $p_str_Key
	 * @return string
	 */
	public function getAttribute($p_str_Key) {
		return array_key_exists($p_str_Key, $this->a_arr_Attributes) ? $this->a_arr_Attributes[$p_str_Key] : "";
	}

	/**
	 * returns the whole Key for an Attribute
	 * @since 1.0.0
	 * @param string $p_str_Key
	 * @return string
	 */
	public function getKey($p_str_Key) {
		return $this->getID() . $p_str_Key;
	}

	/**
	 * returns the ID for this Tag
	 * @since 1.0.0
	 * @return string
	 */
	public function getID() {
		return $this->a_str_ID;
	}
}