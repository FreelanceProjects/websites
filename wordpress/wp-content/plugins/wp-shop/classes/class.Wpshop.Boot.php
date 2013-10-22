<?php

function my_cforms_filter($POSTdata)
{
	Wpshop_Forms::setDataSend();
	return Wpshop_RecycleBin::actionOrder($POSTdata);
}

class Wpshop_Boot
{
	private $view;
	
	public function __construct()
	{
		define( 'CURR',	get_option("wpshop.currency") ); // Валюта	
		$this->disableMagicQuotes();		
		$installer = new Wpshop_Installer();
		$page = new Wpshop_Page();
		$recycleBin = Wpshop_RecycleBin::getInstance();
		
		add_action('init', array(&$this,'ajaxRequest'));
		add_action('init', array(&$this,'ymlRequest'));
		
		$post = new Wpshop_Post();
		
		if (is_admin())
		{
			$admin = new Wpshop_Admin();
		}
		else
		{
			add_filter('widget_text',	array(&$this,'widgetReplace'));	
		}
		$user = new Wpshop_User();
		$eximp = new Wpshop_ExImp();
		$GLOBALS['wpshop_obj'] = new WpShop();
	}
	
	/**
	 * Специальная обработка cforms
	 * Код ниже отключается магические кавычки.
	 */
	private function disableMagicQuotes()
	{
		if (get_magic_quotes_gpc()) {
			$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
			while (list($key, $val) = each($process)) {
			foreach ($val as $k => $v) {
			    unset($process[$key][$k]);
			    if (is_array($v)) {
				$process[$key][stripslashes($k)] = $v;
				$process[] = &$process[$key][stripslashes($k)];
			    } else {
				$process[$key][stripslashes($k)] = stripslashes($v);
			    }
			}
		    }
		    unset($process);
		}
	}
	
	
	public function ymlRequest()
	{
		if (isset($_GET['wpshop_yml']))
		{
			global $wpdb;
			ob_end_clean();
			ob_start();
			include WPSHOP_DIR ."/wpshop-yml.php";
			echo ob_get_clean();
			exit;
		}	
	}
	
	public function ajaxRequest()
	{
		if (isset($_REQUEST['wpshop-ajax']))
		{
			$ajax = new Wpshop_Ajax();
			exit;
		}
	}
	
	public function widgetReplace($content)
	{
		$minicart = '<div id="'.MINICART_ID.'">' . __('You need activate support of JavaScript and Cookies in your browser.') . '</div>';
		return str_replace(MINICART_TAG, $minicart, $content);		
	}
}

class WpShop
{
	/**
	 * @var integer
	 */
	private $_showCost;

	/**
	 * Css style file using administator of site.
	 * @var string
	 */
	private $_css;

	/**
	 * Position of wpshop block (top or down).
	 * @var string
	 */
	private $_position;

	/**
	 * Default columns will be visible in wpshop block.
	 * @var array
	 */
	private $_defaultWidgetColumns = array('name' => true, 'cost' => true, 'add' => true);
	private $view;
	
	public function __construct()
	{
		$this->view = new Wpshop_View();
		$this->_css = get_option('wp-shop_cssfile');
		$this->_position = get_option('wp-shop_position');
		$this->_showCost = get_option('wp-shop_show-cost');
		
		add_filter('the_content', array(&$this,'PriceList'));
		add_filter('the_content', array(&$this,'PriceInfo'));
		add_filter('the_content', array(&$this,'Vitrina'));
		add_filter('the_content', array(&$this,'goodPostHook'));
		add_filter('the_content', array(&$this,'AutoChanging'),15);
		add_filter('the_content', array(&$this,'propertyHook'));

		function wpshop_tinymce_add_button($head)
		{
			if (preg_match('~post(-new)?.php~',$_SERVER['REQUEST_URI']))
			{
				wp_print_scripts( 'quicktags' );
				echo "<script type=\"text/javascript\">"."\n";
				echo "/* <![CDATA[ */"."\n";
				echo "edButtons[edButtons.length] = new edButton"."\n";
				echo "\t('ed_vitrina',"."\n";
				echo "\t'vitrina'"."\n";
				echo "\t,'<!--vitrina tag_name 3 500 2 150-->'"."\n";

				echo "\t,''"."\n";
				echo "\t,'n'"."\n";
				echo "\t);"."\n";
				echo "/* ]]> */"."\n";
				echo "</script>"."\n";
			}
		}
		add_action('admin_head', 'wpshop_tinymce_add_button');

		function wp_shop_header()
		{
			if (is_front_page())
			{
				$content = '<a title="создание и продвижение интернет магазинов" href="http://wp-shop.ru"></a>';
				echo $content;
			}
		}
	
		add_action('wp_head', 'wp_shop_header');
		wp_enqueue_style('wp-shop_style',"/wp-content/plugins/wp-shop/styles/{$this->_css}");
		wp_enqueue_style('wp-shop_style_main',"/wp-content/plugins/wp-shop/wp-shop.css");
		wp_enqueue_script('jquery');
		wp_enqueue_script('wp-shop_general.js',WPSHOP_URL . "/general.js");
		wp_enqueue_script('wp-shop_wp-shop.js',WPSHOP_URL . "/wp-shop.js");
	}
	
	public function GetGoodWidget($post = null, $class = "", array $columns = array('name'=>true,'cost'=>true,'add'=>true))
	{
		if ($post == null)
		{
			global $post;
		}
		$cost = false;
		$cost0 = array();
		$name0 = array();
		$sklad0 = array();
		$sklad = array();
		$meta = get_post_custom($post->ID);
		
		//Properties of good
		//$meta_properties = '';

		foreach ($meta as $key => $val)
		{
			if ( preg_match('/^cost_(\d+)/i', $key, $m) )
			{
				$cost0[$m[1]] = $val[0];
			}
			if ( preg_match('/^name_(\d+)/i', $key, $m) )
			{
				$name0[$m[1]] = $val[0];
			}
			if ( preg_match('/^sklad_(\d+)/i', $key, $m) )
			{
				$sklad0[$m[1]] = $val[0];
			}
		}
		
		if (count($cost0) > 0)
		{
			$cost = array();
			foreach ($cost0 as $key => $val)
			{
				$val = $name0[$key];
				$cost[$val] = round($cost0[$key],2);
				if (isset($sklad0[$key]))
				{
					$sklad[$val] = $sklad0[$key];
				}
			}
			asort($cost);
		}
		else
		{
			$cost = $cost ? array('' => $cost ) : false;
		}
		if ($cost)
		{
			asort($cost);
			ob_start();			
			$this->view->class = $class;
			$this->view->cost = $cost;
			$this->view->sklad = $sklad;
			$this->view->columns = $columns;
			$this->view->post = $post;
			$this->view->render("good.widget.inc.php");
			return ob_get_clean();
		}
		return "";
	}

	/**
	 * Function processing content of page. It is checked as hook.
	 * @param string $content
	 * @return string
	*/
	public function goodPostHook($content)
	{
		if ($this->_showCost == 0)
		{
			return $content;
		}
		global $post;
		if ( $this->_position == 'top' )
		{
			//Uncomment if you want properties in wpshop_block
			//return $this->GetGoodWidget($post,"",$this->_defaultWidgetColumns,null,true).$content;
			return $this->GetGoodWidget($post,'wpshop_post_block').$content;
		}
		else
		{
			//Uncomment if you want properties in wpshop_block
			//return $content.$this->GetGoodWidget($post,"",$this->_defaultWidgetColumns,null,true);
			return $content.$this->GetGoodWidget($post);
		}
	}

	/**
	* Hook for addition properties of good
	* @param string $content
	* @return string
	*/
	public function propertyHook($content)
	{
		global $post;
		$properties_meta = get_post_meta($post->ID,'prop',true);
		$return = '';
		$return .= "<div id='wpshop_property_{$post->ID}'>";
		if ($properties_meta != '')
		{
			$props = $this->parseProperty($properties_meta);
			$return .= $this->getAdditionProperty($props);
		}
		$return .= '</div>';
		$content = preg_replace("/<!--wp-shop text_fied\[\'(.+)\'\]-->/U","<label class='wpshop-textfield' for='wpshop-wpfield'>$1</label><br /><textarea id='wpshop-wpfield' type='text' name='wpshop-wpfield'></textarea>",$content);
		return str_replace('<!--wpshop_prop-->',$return,$content);
	}

	/**
	 * Method parsing meta_value "prop"
	 * @param string $property_meta that saving meta field "prop"
	 * @return array width datas
	 */
	private function parseProperty($property_meta)
	{
		$return = array();
		$properties_str = explode("\n",$property_meta);
		$prop_count = count($properties_str);
		for ($i = 0; $i < $prop_count; ++$i)
		{
			$z = &$return[];
			$parse_property = explode(':',$properties_str[$i]);
			$z['name'] = $parse_property[0];
			$z['values'] = explode(',',$parse_property[1]);
		}
		return $return;
	}

	/**
	 * Method returning addition list boxes saving addition properties of goods
	 * @param array $properties that saving array with properties
	 * @return string html for page
	 */
	private function getAdditionProperty($properties)
	{
		$return .= "<div class='wpshop_properties'><dl>";
		$prop_count = count($properties);
		for ($i = 0; $i < $prop_count; ++$i)
		{
			$values_count = count($properties[$i]['values']);
			if (!empty($properties[$i]['name']) && $values_count > 0)
			{
				$return .= "<dt>{$properties[$i]['name']} ";
				$return .= "<select name='{$properties[$i]['name']}'>";
				for ($j = 0; $j < $values_count; $j++)
				{
					$value = $properties[$i]['values'][$j];
					$return .= "<option value='{$value}'>{$value}</option>";
				}
				$return .= "</select></dt>";
			}
		}
		$return .= '</dl></div>';
		return $return;
	}

	public function AutoChangingCallback($m)
	{
		return $this->GetGoodWidget(get_post($m[1]),'wpshop_buy_new',array('cost'=>true,'add'=>true));
	}

	public function AutoChanging($content)
	{
		return preg_replace_callback("/<\!--wpshop id_(\d+)-->/",array(&$this,'AutoChangingCallback'),$content);
	}

	/**
	 * Callback function for ShopWindow.
	 *
	 * @param array $params
	 * @return string
	 */
	public function VitrinaCallBack($params)
	{
		ob_start();
		$this->view->shop = $this;
		$this->view->colCount = $params[3];
		$this->view->rowCount = $params[5];
		$this->view->height = $params[4];
		$this->view->countSimbols = empty($params[6]) ? 150 : $params[6];
		$this->view->page = isset($_GET['vpage']) ? $_GET['vpage'] : 1;
		// Проверяем, возможно ли это витрина по категориям
		$category = "";
		if (preg_match("/cat=(\S+)/",$params[2],$category))
		{
			$this->view->category = $category[1];
		}
		else
		{
			$this->view->tag = $params[2];
		}
		$this->view->params = $params;
		$this->view->render("vitrina.php");
		return ob_get_clean();
	}	

	public function Vitrina($content)
	{
		return preg_replace_callback('/(<!--|\[)vitrina (\S+)\s*(\d+)\s*(\d+)\s*(\d*)\s*(\d*)(\]|-->)/',array(&$this,'VitrinaCallBack'),$content);
	}
	
	public function PriceList($content)
	{
		return preg_replace_callback('|<!--wpshop pricelist\s*([\d,]*)-->|', array(&$this,'PriceListCallback'),$content);
	}
	
	public function PriceListCallback($matches)
	{
		global $post;
		$categories = explode(",",$matches[1]);
		$result = "";
		$result .= "<ul class=\"price_categories\">";
		$cats = array();
		foreach ($categories as $cat_ID)
		{
			$cat = get_category($cat_ID);
			$result .= "<li><a href=\"#{$cat->slug}\">{$cat->name}</a></li>";
			$cats[] = $cat;
		}
		$result .="</ul>";

		$meta_under_title = get_option('wpshop_price_under_title');

		foreach($cats as $cat)
		{
			$my_query = new WP_Query("cat={$cat->term_id}&orderby=date&order=desc&posts_per_page=-1&page_id != {$post->ID}");
			if (!$my_query->have_posts()) continue;

			$result .= "<table class=\"price_table\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
			$result .= "<tr class=\"h\"><th colspan=\"3\"><h3><a name=\"{$cat->slug}\">{$cat->name}</a></h3></th></tr>";
			preg_match_all("/(.+)(\r\n|<br \/>)*/",get_post_meta($post->ID,"thead",true),$r,PREG_PATTERN_ORDER);

			$result .= "<tr class=\"_h\">";
			foreach($r[0] as $key=>$temp)
			{
				$result .="<th>{$temp}</th>";
			}
			$result .="</tr>";

			while ($my_query->have_posts())
			{
				$my_query->the_post();
				$p = $my_query->post;

				if (!get_post_meta($p->ID,"cost_1",true))continue;

				#Пропускаем запись, если все склады равны нулю.
				$all_sklad = 0;
				$post_custom = get_post_custom($p->ID);
				$is_sklad == false;
				foreach($post_custom as $key => $value)
				{
					if (strpos($key,"sklad_") !== false)
					{
						$all_sklad += current($value);
						$is_sklad = true;
					}
				}

				if ($all_sklad == 0 && $is_sklad) continue;
				if ($i++ % 2) $class="odd"; else $class="even";
				$result .= "<tr class=\"{$class}\" valign=\"top\">";
				if (!empty($meta_under_title))
				{
					$under_title = get_post_meta($p->ID,$meta_under_title,true);
				}
				else
				{
					$under_title = '';
				}
				$result .= "<td class=\"title\"><a href=\"".get_permalink($p->ID)."\">{$p->post_title}</a><div>{$under_title}</div></td>";
				$result .= "<td class='wpshop_table_td'>".$this->GetGoodWidget($p,null,array('name'=>true,'cost'=>true,'add'=>true))."</td>";
				$result .= "</tr>";
			}
			wp_reset_query();
			$result .="</table>";
		}
		return $result;
	}
	
	public function PriceInfoCallback($matches)
	{
		global $post;
		$meta_under_title = get_option('wpshop_price_under_title');
		$my_query = new WP_Query(array("tag"=>$matches[1],"posts_per_page"=>"-1"));
		$result .= "<table class=\"price_table\" cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
		$pos = $my_query->get_posts();
		wp_reset_query();

		preg_match_all("/(.+)(\r\n|<br \/>)*/",get_post_meta($post->ID,"thead",true),$r,PREG_PATTERN_ORDER);
		$result .= "<tr class=\"_h\">";
		foreach($r[0] as $key=>$temp)
		{
			$result .="<th>{$temp}</th>";
		}
		$result .="</tr>";
		
		foreach($pos as $p)
		{
			if (!get_post_meta($p->ID,"cost_1",true))continue;
			$all_sklad = 0;
			$is_sklad = false;
			$post_custom = get_post_custom($p->ID);
			foreach($post_custom as $key => $value)
			{
				if (strpos($key,"sklad_") !== false)
				{
					$all_sklad += current($value);
					$is_sklad = true;
				}
			}
			if ($all_sklad == 0 && $is_sklad) continue;

			if ($i++ % 2) $class = "odd"; else $class = "even";

			if (!empty($meta_under_title))
			{
				$under_title = get_post_meta($p->ID,$meta_under_title,true);
			}
			else
			{
				$under_title = '';
			}

			$result .= "<tr class=\"{$class}\" valign=\"top\">";
			$result .= "<td width=50% class=\"title\"><a href=\"".get_permalink($p->ID)."\">{$p->post_title}</a><div>{$under_title}</div></td>";
			$result .= "<td class='wpshop_table_td'>".$this->GetGoodWidget($p,null,array('name'=>true,'cost'=>true,'add'=>true),true)."</td>";
			$result .= "</tr>";
		}
		$result .= "</table>";
		return $result;
	}

	public function PriceInfo($content)
	{
		return preg_replace_callback('|<!--wpshop price_tag\s*([\S,]*)-->|', array(&$this,'PriceInfoCallback'),$content);
	}
}


