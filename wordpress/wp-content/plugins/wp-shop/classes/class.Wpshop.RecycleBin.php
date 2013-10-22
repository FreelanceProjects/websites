<?php

class Wpshop_RecycleBin
{
	private $view;
	static private $instance = null;
	private $orderDataTmp = null;
	
	public function getLastOrder()
	{
		if ($this->orderDataTmp == null)
		{
			echo "no";
			//throw Exception("no saving order");
		}
		return $this->orderDataTmp;
	}
	
	static public function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new Wpshop_RecycleBin();
		}
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->view = new Wpshop_View();
		add_filter('the_content', array(&$this,"recycleBinAction"));
	}
	
	/**
	 * Функция сохраняет заказ!!!
	 *
	 * @param array $orders заказы переданные в массиве
	 * @return boolean
	 */
	public function saveOrder(Array $orders)
	{
		global $wpdb;
		/**
		 * @todo Действие очищает корзину в обычном режиме
		 */
		if (!get_option("wpshop.payments.activate"))
		{
			$this->view->render('js.inc.clearCart.php');
		}
		
		$status = 0;
		$wpdb->insert( "{$wpdb->prefix}wpshop_orders", array( 'order_date' => time(),
															  'order_discount' => $orders['info']['discount'],
															  'order_payment' => $orders['info']['payment'],
															  'client_name' => $orders['info']['username'],
															  'client_email' => $orders['info']['email'],
															  'client_ip' => $orders['info']['ip'],
															  'order_status' => $status,
															  'order_delivery' => $orders['info']['delivery'],
															  'order_comment' => $orders['info']['comment']
															  ),
													   array('%d','%d','%s','%s','%s','%s','%d','%s','%s') );
										   
		$pid = $wpdb->insert_id;
		
		$this->orderDataTmp = $orders;
		$this->orderDataTmp['id'] = $pid;
		
		foreach($orders['offers'] as $order)
		{
			$wpdb->insert( "{$wpdb->prefix}wpshop_ordered" , array( 'ordered_pid' => $pid, 'ordered_name' => $order['name'], 'ordered_cost' => $order['price'],'ordered_count' => $order['partnumber'],'ordered_key' => $order['key'] ) , array( "%d" , "%s", "%f", "%d", "%s" ));
		}
		
		ob_start();
		$this->view->order = $orders;
		$this->view->render("mail/admin.php");
		// отправка почты администратору 
		$email = get_option("wpshop.email");
		$siteurl = get_bloginfo('wpurl');
		mail($email, "Новый заказ  #{$pid} с сайта {$siteurl}", ob_get_clean(),"		
Content-type: text/html; charset=UTF-8
Reply-To: {$email}
From:{$email}");

		ob_start();
		$this->view->order = $orders;
		$this->view->render("mail/client.php");		
		mail($orders['info']['email'], "Re: Ваш заказ  #{$pid} с сайта {$siteurl}", ob_get_clean(),"		
Content-type: text/html; charset=UTF-8
Reply-To: {$email}
From: {$email}");		
		
		if ($payment) 
		{
			$this->paymentAction($payment);
		}
		return true;
	}
	
	public function recycleBinAction($content)
	{
		global $post;
		if ($post->post_excerpt == "wm_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}
		
		if ($post->post_excerpt == "robokassa_success")
		{
			ob_start();
			$this->view->render("js.inc.clearCart.php");
			$content = $content . ob_get_clean();
		}
		$this->view->dataSend = Wpshop_Forms::isDataSend();
		
		$this->view->cartCols = array(
									'name' => get_post_meta($post->ID,'cart_col_name',true),
									'price' => get_post_meta($post->ID,'cart_col_price',true),
									'count' => get_post_meta($post->ID,'cart_col_count',true),
									'sum' => get_post_meta($post->ID,'cart_col_sum',true),
									'type' => get_post_meta($post->ID,'cart_col_type',true)
								);						
		if (get_option('wpshop.payments.activate'))
		{
			$count = 0;
			$this->view->payments = Wpshop_Payment::getInstance()->getPayments();
			foreach($this->view->payments as $key => $value)
			{
				$this->view->payments[$key]->data = get_option("wpshop.payments.{$value->paymentID}");
				$query = new WP_Query( array( 'post_type' => 'wpshopcarts', 'posts_per_page' => -1));
				foreach($query->posts as $tutu)
				{
					if ($tutu->post_excerpt == $this->view->payments[$key]->paymentID)
					{
						$this->view->payments[$key]->data['cart_url'] = get_permalink($tutu->ID);
						break;
					}	
				}
				if ($value->data['activate'] && $value->paymentID != 'robokassa') $count++;
			}
		}
		
		$this->view->paymentsCount = $count;
		$this->view->minzakaz = get_option('wpshop.cart.minzakaz');
		$this->view->discount = get_option('wpshop.cart.discount');
		$this->view->minzakaz_info = get_option('wpshop.cart.minzakaz_info');
		$this->view->cform = get_option('wp-shop_cform');
		$this->view->delivery = Wpshop_Delivery::getInstance()->getDeliveries();
		
		ob_start();
		if (Wpshop_Forms::isDataSend())
		{
			/** Получаем сделанный заказ */
			$this->view->order = Wpshop_RecycleBin::getInstance()->getLastOrder();
			
			/** Проверяем оплачено ли через Web-Money и если да, то устанавливаем нужные переменные */
			if ($this->view->order['info']['payment'] == "wm")
			{
				$this->view->wm = get_option("wpshop.payments.wm");
			}
			if ($this->view->order['info']['payment'] == "robokassa")
			{
				$this->view->robokassa = get_option("wpshop.payments.robokassa");
			}			
			$this->view->render("RecycleBinAfterSend.php");
		}
		else
		{
			$this->view->render("RecycleBin.php");
		}
		return str_replace(CART_TAG, ob_get_clean(), $content);
	}
	
	public function paymentAction($payment)
	{
		$totalSum = 0;
		foreach($this->orderDataTmp['offers'] as $good)
		{
			$totalSum += $good['price'];
		}

		if ($payment == "wm")
		{
			$this->view->payment_no = $this->orderDataTmp['id'];
			$this->view->amount = $totalSum;
			$this->view->render("wm.redirect.php");
		}
	}
	
	
	public static function actionOrder($POSTData)
	{
		if (isset($POSTData['payment']) && !empty($POSTData['payment']))
		{
			$cform_name = "wpshop-" . $POSTData['payment'];
		}
		else
		{
			$cform_name = get_option("wp-shop_cform");
		}
		
		$keys = explode("}{",$_COOKIE['wpshop_key']);
		$names = explode("}{",$_COOKIE['wpshop_name']);
		$costes = explode("}{",$_COOKIE['wpshop_cost']);
		$nums = explode("}{",$_COOKIE['wpshop_num']);
		$discount = $_COOKIE['wpshop_discount'];
		$sum = 0;

		// Формируем таблицу из сделанных заказов
		// Начало
		$final = "<table style=\"border: solid 1px #DDDDDD\">";
		$color = '';
		foreach($names as $key => $value)
		{
			if ($key % 2) $color = "#DDDDDD";
			else $color = "white";
			$total =  $costes[$key] * $nums[$key];
			$count = $key+1;
			$final .= "<tr bgcolor=$color><td>{$count}</td><td>$value ({$keys[$key]})</td><td>{$costes[$key]}</td><td>{$nums[$key]}</td><td>{$total}</td></tr>";
			$sum += $costes[$key] * $nums[$key];
		}
		$final .= "<tr><td colspan=5 align=right>Итого: $sum</td></tr>";
		if (!empty($discount))
		{
			$final	.= "<tr><td colspan=5 align=right>Со скидкой {$discount}%: " . round((100-$discount)*$sum/100,2) ."</td></tr>";
		}
		$final .= "</table>";
		// Конец

		$allInfo = array();
		foreach($names as $key => $value)
		{
			$offers = &$allInfo['offers'][];
			$offers['name'] = $value;	
			$offers['price'] = $costes[$key];
			//$offers['quant'] = '';//$res[$j]['quant'];
			$offers['partnumber'] = $nums[$key];
			$offers['key'] = $keys[$key];
			//$offers['color'] = '';
			//$offers['size'] = '';
		}
		
		// Отсюда начинаем работу с данными формы
		$allInfo['info'] = array();
		$allInfo['info']['payment'] = $POSTData['payment'];
		$allInfo['info']['ip'] = $_SERVER['REMOTE_ADDR'];
		$allInfo['info']['discount'] = $_COOKIE['wpshop_discount'];
		$allInfo['info']['delivery'] = $POSTData['delivery'];
		$allInfo['info']['total'] = $total;
		$form = Wpshop_Forms::getInstance()->getFormByName($cform_name);
		
		$mainComment = "";
		
		foreach($form['fields'] as $field)
		{	
			$mainComment .= "{$field['name']} - {$POSTData[$field['postName']]}\n";
			// Определяем E-mail
			if ($field['email'])
			{
				$allInfo['info']['email'] = $POSTData[$field['postName']];
			}
			
			if ($field['order'])
			{
				$POSTData[$field['postName']] = $final;
			}
			
			if ($field['type'] == "Name")
			{
				$allInfo['info']['username'] = $POSTData[$field['postName']];
			}
			// Комментарий к заказу
			$allInfo['info']['comment'] = "";
			if ($field['type'] == '$textarea')
			{
				$allInfo['info']['comment'] = $POSTData[$field['postName']];
			}
			/**
			 * @todo отменить отправку кода с картинки и ненужные скрыте поля 
			 */
			if ($field['name'] != Wpshop_Forms::getInstance()->getRightField() && $field['type'] != '$fieldsetstart' && $field['type'] != '$captcha')
			{
				$row = &$allInfo['cforms'][];
				$row['name'] = $field['name'];
				$row['value'] = $POSTData[$field['postName']];
			}
		}
		
		$allInfo['info']['comment'] = $mainComment;
		self::getInstance()->saveOrder($allInfo);
		return $POSTdata;
	}
}