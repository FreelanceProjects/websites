<?php
if ($this->dataSend)
{
	$this->render("RecycleBinAfterSend.php");
	return;
}
?>
<script type="text/javascript">
<?php
if (!empty($this->cartCols['name'])) echo " window.cart_col_name ='{$this->cartCols['name']}';\n";
if (!empty($this->cartCols['price'])) echo " window.cart_col_price ='{$this->cartCols['price']}';\n";
if (!empty($this->cartCols['count'])) echo " window.cart_col_count ='{$this->cartCols['count']}';\n";
if (!empty($this->cartCols['sum'])) echo " window.cart_col_sum ='{$this->cartCols['sum']}';\n";
if (!empty($this->cartCols['type'])) echo " window.cart_col_type ='{$this->cartCols['type']}';\n";
?>
jQuery(function()
{
	jQuery('.cform').prepend("<input type='hidden' name='payment' value='<?php echo $_GET['payment'];?>'/>");
});
</script>

<div id="<?php echo CART_ID;?>">
<?php echo __('You need activate support of JavaScript and Cookies in your browser.');?>
</div>

<?php 
//Подсчет количества общей суммы
$temp = explode('}{',$_COOKIE['wpshop_cost']);
foreach(explode('}{',$_COOKIE['wpshop_num']) as $key => $value)
{
	$temp[$key] = $temp[$key] * $value;
}

$total =  array_sum($temp);

$can_do = true;
if (!empty($this->minzakaz))
{
	if ($total > 0 && $total < $this->minzakaz)
	{
		$can_do	= false;
	}
}

//Определение скидки.
$max_discount = 0;
if ($this->discount != '')
{
	foreach(explode("\r\n",$this->discount) as $value)
	{
		$q = explode(":",$value);
		if ($total > $q[0])
		{
			if ($max_discount < $q[1])
			{
				$max_discount = $q[1];
			}
		}
	}
	echo "<script type='text/javascript'>jQuery(document).ready(function(){
			window.__cart.discount = '" . str_replace("\r","",str_replace("\n",';',$this->discount)) . "';
			window.__cart.update();
	});</script>";
}

if ($total > 0) :
	if ($can_do)
	{
		if (function_exists("insert_cform") && ($this->cform !== false || $this->cform != ""))
		{
			if (count($this->payments))
			{
				if (!isset($_GET['payment']))
				{
				?>
				<table id='payments-table'>
					<thead id='mode-paymets-title'>
						<th colspan='<?php echo $this->paymentsCount;?>'>Выберите способ оплаты:</th>
					</thead>
					<tbody>
						<?php
						$robokassa = false;
						foreach($this->payments as $payment)
						{
							if ($payment->data['activate'] == true)
							{
								if ($payment->paymentID == "robokassa")
								{
									$robokassa = $payment;
									continue;
								}
								echo "<td>
										<a href='{$payment->data[cart_url]}&payment={$payment->paymentID}'><img src='".WPSHOP_URL."/images/payments/{$payment->picture}' title='{$payment->name}'/></a><br/>
										<a href='{$payment->data[cart_url]}&payment={$payment->paymentID}'>{$payment->name}</a>													
									  </td>";
							}
						}
						?>
					</tbody>
				</table>
				<?php if ($robokassa):?>
				<table id='payments-table'>
				<thead id='mode-paymets-title'>
					<th colspan='5'>Оплата производится через платежный сервис RoboKassa.ru<br/> Взимается небольшая дополнительная комисcия.</th>
				</thead>
				<tbody>
					<tr>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=yandex";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_yandex.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=yandex";?>'>Yandex - деньги</a>													
						</td>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=card";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_visa.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=card";?>'>Visa / MasterCard</a>	
						</td>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=mail";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_mail.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=mail";?>'>MoneyMail</a>	
						</td>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=ino";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_ino.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=ino";?>'>INO.ru</a>
						</td>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=rbk";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_rbk.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=rbk";?>'>RBK Money</a>
						</td>
					</tr>
					<tr>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=pecunix";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_pecunix.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=pecunix";?>'>Pecunix</a>													
						</td>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=qiwi";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_qiwi.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=qiwi";?>'>Терминалы QIWI</a>	
						</td>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=eleksnet";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_eleksnet.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=eleksnet";?>'>Элекснет</a>	
						</td>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=sms";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_sms.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=sms";?>'>SMS</a>
						</td>
						<td>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=UkrMoney";?>'><img src='<?php echo WPSHOP_URL;?>/images/payments/robo_UkrMoney.gif' title=''/></a><br/>
							<a href='<?php echo "{$robokassa->data[cart_url]}&payment={$robokassa->paymentID}&rk=UkrMoney";?>'>UkrMoney.com</a>
						</td>
					</tr>
				</tbody>
				</table>
				<?php 
				endif;
				}
				else
				{
					$this->render("RecycleBinPayment.php");
				}
			}
			else
			{	
				insert_cform($this->cform);
			}
		}
		else
		{
			echo "<div style='color:red'>Ошибка: Не установлен cforms II.</div>";
		}
	}
	else
	{
		echo $this->minzakaz_info;
	}
endif;
