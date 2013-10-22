<table>
<!--thead>
<tr style="background-color:#CCCCCC">
	<th>Поле</th>
	<th>Значение</th>
</tr>
</thead-->
<?php
foreach($this->order['cforms'] as $value)
{
	echo "<tr><td>{$value['name']}</td><td>{$value['value']}</td></tr>";
}
?>
</table>

<br/><br/>
<table style="width:100%">
<tr style="background-color:#CCCCCC">
	<th>Наименование</th>
	<th>&nbsp;</th>
	<th>Цена</th>
	<th>Кол-во</th>
	<th>Сумма</th>
</tr>
<?php
$key = 0;
$price = 0;
foreach($this->order['offers'] as $offer)
{
	$price = round($offer['partnumber'] * $offer['price'],2);
	$itogo += $price;
	if ($key++ % 2) $color = "white";
	else $color = "#DDDDDD";
	echo "<tr style='background-color:{$color};'>
		<td>{$offer['name']}</td>
		<td>{$offer['key']}</td>
		<td style='text-align:center'>{$offer['price']}</td>
		<td style='text-align:center'>{$offer['partnumber']}</td>
		<td style='text-align:center'>{$price}</td>
	</tr>";
}
?>
<tr><td colspan='3'>Итого:</td><td><?php echo $itogo;?></td></tr>
<?php

if ($this->order['info']['discount'])
{
	$itogo = round($itogo - $itogo / 100 * $this->order['info']['discount'],2);
	echo "<tr><td colspan='3'>Цена со скидкой ({$this->order['info']['discount']}%)</td><td>{$itogo}</td></tr>";
}

$delivery = Wpshop_Delivery::getInstance()->getDelivery($this->order['info']['delivery']);
if ($delivery)
{
	$itogo += $delivery->cost;
	echo "<tr><td colspan='3'>Доставка ({$delivery->name})</td><td>{$delivery->cost}</td></tr>";
}
?>
<tr>
	<td colspan='3'>Всего:</td>
	<td><?php echo $itogo;?></td>
</tr>
</table>
