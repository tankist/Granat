<?php

/**
 * Helper to generate a set of radio button elements
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Skaya_View_Helper_ProductImage extends Zend_View_Helper_FormElement {
	/**
	 * Input type to use
	 * @var string
	 */
	protected $_inputType = 'radio';

	/**
	 * Whether or not this element represents an array collection by default
	 * @var bool
	 */
	protected $_isArray = false;

	/**
	 * Generates a set of radio button elements.
	 *
	 * @access public
	 *
	 * @param string|array $name If a string, the element name.  If an
	 * array, all other parameters are ignored, and the array elements
	 * are extracted in place of added parameters.
	 *
	 * @param mixed $value The radio value to mark as 'checked'.
	 *
	 * @param array $options An array of key-value pairs where the array
	 * key is the radio value, and the array value is the radio text.
	 *
	 * @param array|string $attribs Attributes added to each radio.
	 *
	 * @return string The radio buttons XHTML.
	 */
	public function productImage($name, $value = null, $attribs = null,
		$options = null, $listsep = "\n") {

		$info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
		extract($info); // name, value, attribs, options, listsep, disable
		$listsep = "\n";

		// retrieve attributes for labels (prefixed with 'label_' or 'label')
		$label_attribs = array();
		foreach ($attribs as $key => $val) {
			$tmp = false;
			$keyLen = strlen($key);
			if ((6 < $keyLen) && (substr($key, 0, 6) == 'label_')) {
				$tmp = substr($key, 6);
			} elseif ((5 < $keyLen) && (substr($key, 0, 5) == 'label')) {
				$tmp = substr($key, 5);
			}

			if ($tmp) {
				// make sure first char is lowercase
				$tmp[0] = strtolower($tmp[0]);
				$label_attribs[$tmp] = $val;
				unset($attribs[$key]);
			}
		}

		// the radio button values and labels
		$options = (array)$options;

		// build the element
		$xhtml = '';
		$list = array();

		// should the name affect an array collection?
		$name = $this->view->escape($name);
		if ($this->_isArray && ('[]' != substr($name, -2))) {
			$name .= '[]';
		}

		// ensure value is an array to allow matching multiple times
		$value = (array)$value;

		// XHTML or HTML end tag?
		$endTag = ' />';
		if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
			$endTag = '>';
		}

		// add radio buttons to the list.
		$filter = new Zend_Filter_Alnum();
		foreach ($options as $opt_value => $opt_label) {
			// is it disabled?
			$disabled = '';
			if (isset($disable) &&
			        (true === $disable ||
			         (is_array($disable) &&
			            in_array($opt_value, $disable)))) {
				$disabled = ' disabled="disabled"';
			}

			// is it checked?
			$checked = '';
			if (in_array($opt_value, $value)) {
				$checked = ' checked="checked"';
			}

			// generate ID
			if (!isset($id)) {
				$optId = $id = $filter->filter($opt_value);
			}
			else {
				$optId = $id . '-' . $filter->filter($opt_value);
			}


			// Wrap the radios in labels
			$image = $attribs['images'][$opt_value];
			$filename = $image['name'];
			$path = $image['path'];
			$thumb = $image['thumb'];
			$image_id = $image['id'];
			if (!$image_id) {
				$image_id = $opt_value;
			}

			$radio = <<<EOS
	<tr>
		<td class="preview">
			<label for="$optId"><div class="image">
				<em>&nbsp;</em>
				<img src="/$path/$thumb" alt="$filename">
			</div>
			<input type="radio" class="checkable" name="modelTitle" value="$image_id" id="$optId"{$checked}{$disabled}{$endTag}Main Image</label>
		</td>
		<td class="file_upload_progress"><div></div></td>
		<td class="file_upload_delete">
			<button class="delete ui-state-default ui-corner-all" title="Delete" data-id="$image_id">
				<span class="ui-icon ui-icon-closethick">Delete</span>
			</button>
		</td>
	</tr>
EOS;

			// add to the array of radio buttons
			$list[] = $radio;
		}

		// done!
		$xhtml .= implode($listsep, $list);

		return '<table id="files">' . $xhtml . '</table>';
	}
}
