<?php



class Wpshop_Order
{
	private $order;
	private $ordered;
	public function __construct($id)
	{
		global $wpdb;
		$this->order =  $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}wpshop_orders` WHERE `order_id` = '{$id}'"); 
		$this->ordered = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}wpshop_ordered` WHERE `ordered_pid` = '{$id}'");
	}
	
	public function getDiscount()
	{
		return $this->order->order_discount;
	}
	
	public function getDelivery()
	{
		if ($this->order->order_delivery)
		{
			return Wpshop_Delivery::getInstance()->getDelivery($this->order->order_delivery);
		}
		return null;
	}
	
	public function getTotalSum()
	{
		foreach($this->ordered as $order)
		{
			$total += $order->ordered_count * $order->ordered_cost;
		}
		
		if ($this->getDiscount())
		{
			$total = $total - $total /100 * $this->getDiscount();
		}
		
		$delivery = $this->getDelivery();
		if ($delivery)
		{
			$total += $delivery->cost;
		}
		return round($total,2);
	}
}