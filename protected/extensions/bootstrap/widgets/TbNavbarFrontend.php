<?php
/**
 * TbNavbar class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap.widgets
 * @since 0.9.7
 */

/**
 * Bootstrap navigation bar widget.
 */
class TbNavbarFrontend extends CWidget
{
	// Navbar types.
	const TYPE_INVERSE = 'inverse';

	// Navbar fix locations.
	const FIXED_TOP = 'top';
	const FIXED_BOTTOM = 'bottom';

	/**
	 * @var string the navbar type. Valid values are 'inverse'.
	 * @since 1.0.0
	 */
	public $type;
	/**
	 * @var string the text for the brand.
	 */
	public $brand;
	/**
	 * @var string the URL for the brand link.
	 */
	public $brandUrl;
	/**
	 * @var array the HTML attributes for the brand link.
	 */
	public $brandOptions = array();
	/**
	 * @var mixed fix location of the navbar if applicable.
	 * Valid values are 'top' and 'bottom'. Defaults to 'top'.
	 * Setting the value to false will make the navbar static.
	 * @since 0.9.8
	 */
	public $fixed = self::FIXED_TOP;
	/**
	* @var boolean whether the nav span over the full width. Defaults to false.
	* @since 0.9.8
	*/
	public $fluid = false;
	/**
	 * @var boolean whether to enable collapsing on narrow screens. Default to false.
	 */
	public $collapse = false;
	/**
	 * @var array navigation items.
	 * @since 0.9.8
	 */
	public $items = array();
	/**
	 * @var array the HTML attributes for the widget container.
	 */
	public $htmlOptions = array();

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{

		$menus = '';
		foreach ($this->items as $menu)
		{
			
			if(!empty($menu["items"]))
			{
				$menus .= '<li><a class="hsubs" href="'.$menu["url"].'">'.$menu["label"].'</a>';
				$menus .= '<ul class="subs">';
				
				foreach($menu["items"] as $submenu)
				{
					$menus .= '<li><a href="'.$submenu["url"].'">'.$submenu["label"].'</a></li>';
				}
				
				$menus .= '</ul>';
			}
			else
			{
				$menus .= '<li><a href="'.$menu["url"].'">'.$menu["label"].'</a>';
			}
			
            $menus .= '</li>';
		}
		
		echo $menus;
	}

	/**
	 * Returns the navbar container CSS class.
	 * @return string the class
	 */
	protected function getContainerCssClass()
	{
		return $this->fluid ? 'container-fluid' : 'container';
	}
}
