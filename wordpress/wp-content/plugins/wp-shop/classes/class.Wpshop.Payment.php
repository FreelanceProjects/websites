<?php
/**
 @todo Найти переменную храняющую в себе слеш для определенной операционной системы
 */



class Wpshop_Payment_Data
{
	public $paymentID;
	public $name;
	public $fields;
	public $picture;
	/**
	 * Массив имеющий разного рода назначение
	 */
	public $data;
};

class Wpshop_Payment
{
	private static $instance = null;
	public $payments = array();
	
	private function __construct()
	{
	
		$i = 0;
		$this->payments[$i] = new Wpshop_Payment_Data();
		$this->payments[$i]->paymentID = "vizit";
		$this->payments[$i]->name = "Самовывоз";
		$this->payments[$i]->title = "Оформление заказа с через ‘самовывоз’ товара из нашего магазина/офиса";
		$this->payments[$i]->fields = array('Заказ$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Тип заказа: <b>Визит в наш офис</b>$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Для оформления заказа заполните эту форму:$#$fieldsetstart$#$0$#$0$#$0$#$0$#$0',
						   'Ваше имя|Ваше имя||||Name$#$textfield$#$1$#$0$#$1$#$0$#$0',
                           'Контактный телефон$#$textfield$#$1$#$0$#$0$#$0$#$0',
						   'Email$#$textfield$#$0$#$1$#$0$#$0$#$0',
						   'Комментарий к заказу$#$textarea$#$0$#$0$#$0$#$0$#$0');
		$this->payments[$i]->picture = 'vizit.gif';


		$i = 1;
		$this->payments[$i] = new Wpshop_Payment_Data();
		$this->payments[$i]->paymentID = "cash";
		$this->payments[$i]->title = "Оформление заказа с оплатой наличными курьеру при получении товара";
		$this->payments[$i]->name = "Наличными курьеру";
		$this->payments[$i]->fields = array('Заказ$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Тип заказа: <b>Наличными курьеру</b>$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Для оформления заказа заполните эту форму:$#$fieldsetstart$#$0$#$0$#$0$#$0$#$0',
						   'Ваше имя|Ваше имя||||Name$#$textfield$#$1$#$0$#$1$#$0$#$0',
						   'Контактный телефон$#$textfield$#$1$#$0$#$0$#$0$#$0',
						   'Email$#$textfield$#$0$#$1$#$0$#$0$#$0',
						   'Комментарий к заказу$#$textarea$#$0$#$0$#$0$#$0$#$0');
		$this->payments[$i]->picture = 'nal.gif';
		
		$i = 2;
		$this->payments[$i] = new Wpshop_Payment_Data();
		$this->payments[$i]->paymentID = "post";
		$this->payments[$i]->title = "Оформление заказа с оплатой на почте при получении (Наложенный платеж)";
		$this->payments[$i]->name = "Наложенный платеж";
		$this->payments[$i]->fields = array('Заказ$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Тип заказа: <b>Наложный платеж</b>$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Для оформления заказа заполните эту форму:$#$fieldsetstart$#$0$#$0$#$0$#$0$#$0',
						   'Ваше имя|Ваше имя||||Name$#$textfield$#$1$#$0$#$1$#$0$#$0',
                           'Контактный телефон$#$textfield$#$1$#$0$#$0$#$0$#$0',
						   'Email$#$textfield$#$0$#$1$#$0$#$0$#$0',
						   'Комментарий к заказу$#$textarea$#$0$#$0$#$0$#$0$#$0');
		$this->payments[$i]->picture = 'post.gif';	
		
		$i = 3;
		$this->payments[$i] = new Wpshop_Payment_Data();
		$this->payments[$i]->paymentID = "wm";
		$this->payments[$i]->title = "Оформление заказа с оплатой через систему ‘Web-Money’";
		$this->payments[$i]->name = "Web-Money";
		$this->payments[$i]->fields = array('Заказ$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Тип заказа: <b>Web-Money</b>$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Для оформления заказа заполните эту форму:$#$fieldsetstart$#$0$#$0$#$0$#$0$#$0',
						   'Ваше имя|Ваше имя||||Name$#$textfield$#$1$#$0$#$1$#$0$#$0',
                           'Контактный телефон$#$textfield$#$1$#$0$#$0$#$0$#$0',
						   'Email$#$textfield$#$0$#$1$#$0$#$0$#$0',
						   'Комментарий к заказу$#$textarea$#$0$#$0$#$0$#$0$#$0');
		$this->payments[$i]->picture = 'wm.gif';
		$this->payments[$i]->textAfterSend = "<h3>Для оплаты Вашего заказа нажмите кнопку выше \"Оплатить WM\".<br/>После совершения Вами оплаты заказа информация передается нам, и наш менеджер обязательно свяжется с Вами для уточнения деталей доставки.<br/>Благодарим, что воспользовались нашим сервисом!</h3>";
		
		$i = 4;
		$this->payments[$i] = new Wpshop_Payment_Data();
		$this->payments[$i]->paymentID = "bank";
		$this->payments[$i]->name = "Безналичный расчет";
		$this->payments[$i]->title = "Оформление заказа с оплатой через банк (Безналичный расчет)";
		$this->payments[$i]->fields = array('Заказ$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Тип заказа: <b>Безналичный расчет</b>$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Для оформления заказа заполните эту форму:$#$fieldsetstart$#$0$#$0$#$0$#$0$#$0',
						   'Ваше имя|Ваше имя||||Name$#$textfield$#$1$#$0$#$1$#$0$#$0',
			               'Контактный телефон$#$textfield$#$1$#$0$#$0$#$0$#$0',
						   'Email$#$textfield$#$0$#$1$#$0$#$0$#$0',
						   'Комментарий к заказу$#$textarea$#$0$#$0$#$0$#$0$#$0');
		$this->payments[$i]->picture = 'bank.gif';		
		
		$i = 5;
		$this->payments[$i] = new Wpshop_Payment_Data();
		$this->payments[$i]->paymentID = "robokassa";
		$this->payments[$i]->name = "RoboKassa";
		$this->payments[$i]->title = "Оформление заказа с оплатой через систему ‘RoboKassa.ru’";
		$this->payments[$i]->fields = array('Заказ$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Тип заказа: <b>RoboKassa</b>$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Для оформления заказа заполните эту форму:$#$fieldsetstart$#$0$#$0$#$0$#$0$#$0',
						   'Ваше имя|Ваше имя||||Name$#$textfield$#$1$#$0$#$1$#$0$#$0',
                           'Контактный телефон$#$textfield$#$1$#$0$#$0$#$0$#$0',
						   'Email$#$textfield$#$0$#$1$#$0$#$0$#$0',
						   'Комментарий к заказу$#$textarea$#$0$#$0$#$0$#$0$#$0');
		$this->payments[$i]->picture = 'robokassa.gif';
		$this->payments[$i]->textAfterSend = "<h3>Выберите подходящий Вам способ оплаты из списка имеющихся вариантов и осуществите платеж. Данные по совершенному Вами платежу поступят нашим менеджерам, которые свяжутся с Вами по контактному телефону для уточнения деталей по Вашему заказу.<br>
Благодарим, что воспользовались нашим сервисом!</h3>";
		
		$i = 6;
		$this->payments[$i] = new Wpshop_Payment_Data();
		$this->payments[$i]->paymentID = "paypal";
		$this->payments[$i]->name = "Pay Pal";
		$this->payments[$i]->fields = array('Заказ$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Тип заказа: <b>PayPal</b>$#$hidden$#$0$#$0$#$0$#$0$#$0',
						   'Для оформления заказа заполните эту форму:$#$fieldsetstart$#$0$#$0$#$0$#$0$#$0',
						   'Ваше имя|Ваше имя||||Name$#$textfield$#$1$#$0$#$1$#$0$#$0',
                           'Контактный телефон$#$textfield$#$1$#$0$#$0$#$0$#$0',
						   'Email$#$textfield$#$0$#$1$#$0$#$0$#$0',
						   'Комментарий к заказу$#$textarea$#$0$#$0$#$0$#$0$#$0');
		$this->payments[$i]->picture = 'paypal.gif';
		
		add_filter('init', array(&$this,'webMoneyResult'));
		add_filter('init', array(&$this,'robokassaResult'));		
	}

	
	/**
	 * обыкновенный синглетон)
	 */
	public function getInstance()
	{
		if (self::$instance==null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * @deprecated
	 */
	public function getSingleton()
	{
		return self::getInstance();
	}	
	
	/**
	 * Возвращает доступные формы оплаты
	 *
	 * @return Array
	 */
	public function getPayments()
	{
		return $this->payments;
	}
	/**
	 *
	 */
	public function getPaymentByID($id)
	{
		foreach($this->payments as $key => $value)
		{
			if ($value->paymentID == $id) return $this->payments[$key];
		}
		return null;
	}
	
	public function webMoneyResult()
	{
		if (isset($_GET['wmResult']))
		{
			Wpshop_Orders::setStatus($_GET['order_id'],1);
			echo "YES";
			exit;
		}
		
	}
	
	public function robokassaResult()
	{
		if (isset($_GET['robokassaResult']))
		{
			$robokassa = get_option("wpshop.payments.robokassa");
			
			// регистрационная информация (пароль #2)
			// registration info (password #2)
			$mrh_pass2 = $robokassa['pass2'];

			//установка текущего времени
			//current date
			$tm=getdate(time()+9*3600);
			$date="{$tm['year']}-{$tm['mon']}-{$tm['mday']} {$tm['hours']}:{$tm['minutes']}:{$tm['seconds']}";

			// чтение параметров
			// read parameters
			$out_summ = $_REQUEST["OutSum"];
			$inv_id = $_REQUEST["InvId"];
			$shp_item = $_REQUEST["Shp_item"];
			$crc = $_REQUEST["SignatureValue"];

			$crc = strtoupper($crc);

			$my_crc = strtoupper(md5("{$out_summ}:{$inv_id}:{$mrh_pass2}:Shp_item={$shp_item}"));

			// проверка корректности подписи
			// check signature
			if ($my_crc !=$crc)
			{
			  echo "bad sign\n";
			  exit();
			}
			// признак успешно проведенной операции
			// success
			echo "OK$inv_id\n";
			Wpshop_Orders::setStatus($inv_id,1);
			exit;
		}
	}
}
