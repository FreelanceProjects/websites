<?php
class Wpshop_Orders
{
	private static $instance = null;
	private $statuses = array(
		0 => 'Новый',
		1 => 'Оплачено',
		2 => 'Отменено',
		3 => 'В обработке',
		4 => 'Доставлено',
		5 => 'Архив'
	);
	
	private function __construct()
	{
	
	}
	public static function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new Wpshop_Orders();
		}
		return self::$instance;
	}
	
	public function getStatuses()
	{
		return $this->statuses;
	}
	
	public function getStatus($id)
	{
		if (isset($this->statuses[$id]))
		{
			return $this->statuses[$id];
		}
		throw new Exception("The status with id {$id} not found.");
	}
	
	public function save($id,$data)
	{
		global $wpdb;
		$wpdb->update($wpdb->prefix."wpshop_orders", $data, array('order_id' => $id),array("%s"),array("%s"));
	}
	
	static public function setStatus($order_id,$status)
	{
		global $wpdb;
		$wpdb->update($wpdb->prefix."wpshop_orders", array('order_status'=>$status), array('order_id' => $order_id),array("%d"),array("%d"));
	}
}