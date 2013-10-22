<?php

class Wpshop_Delivery_Data
{
	public $ID;
	public $cost;
	public $name;
}


class Wpshop_Delivery
{
	private static $instance = null;
	private $view;
	private $deliveries;
	public function __construct()
	{
		$deliv = get_option("wpshop.delivery");
		
		
		$this->deliveries[0] = new  Wpshop_Delivery_Data();
		$this->deliveries[0]->ID = "postByCountry";
		$this->deliveries[0]->name = "Доставка почтой по стране";
		$this->deliveries[0]->cost = $deliv['postByCountry']['cost'];
		
		$this->deliveries[1] = new  Wpshop_Delivery_Data();
		$this->deliveries[1]->ID = "postByWorld";
		$this->deliveries[1]->name = "Международная доставка почтой";
		$this->deliveries[1]->cost = $deliv['postByWorld']['cost'];
		
		$this->deliveries[2] = new  Wpshop_Delivery_Data();
		$this->deliveries[2]->ID = "courier";
		$this->deliveries[2]->name = "Доставка курьером";
		$this->deliveries[2]->cost = $deliv['courier']['cost'];
		
		$this->deliveries[3] = new  Wpshop_Delivery_Data();
		$this->deliveries[3]->ID = "vizit";
		$this->deliveries[3]->name = "Визит в офис";		
		$this->deliveries[3]->cost = $deliv['vizit']['cost'];
	}
	
	public static function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new Wpshop_Delivery();
		}
		return self::$instance;
	}
	
	public function deliveryAction()
	{
		if (isset($_POST['update']))
		{
			update_option("wpshop.delivery",$_POST['wpshop_delivery']);
		}
		
		
		$this->view = new Wpshop_View();
		$this->view->delivery = get_option("wpshop.delivery");
		$this->view->render('admin/delivery.php');
	}
	
	public function getDeliveries()
	{
		return $this->deliveries;
	}
	
	public function getDelivery($id)
	{
		foreach($this->deliveries as $delivery)
		{
			if ($delivery->ID == $id)
			{
				return $delivery;
			}
		}
		return null;
	}
	
}
