<?php 
$last = Wpshop_RecycleBin::getInstance()->getLastOrder();
$order = new Wpshop_Order($this->order['id']);
if ($this->order['info']['payment'] == "wm") :
Wpshop_Orders::setStatus($_GET['order_id'],1);
?>
<form action="https://merchant.webmoney.ru/lmi/payment.asp" method="POST">
	<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?php echo $order->getTotalSum();?>"/>
	<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="<?php echo base64_encode("Заказ #{$this->order['id']} c cайта {$_SERVER['HTTP_HOST']}");?>"/>
	<input type="hidden" name="LMI_PAYEE_PURSE" value="<?php echo $this->wm['wmCheck'];?>"/>
	<input type="hidden" name="LMI_SUCCESS_URL" value="<?php echo $this->wm['successUrl'];?>"/>
	<input type="hidden" name="LMI_FAIL_URL" value="<?php echo $this->wm['failedUrl'];?>"/>
	<input type="hidden" name="LMI_RESULT_URL" value="<?php echo bloginfo('wpurl')."/?wmResult=1&order_id={$this->order['id']}";?>"/>
	<input type="submit" value="Оплатить WM"/>
</form>
<?php
elseif($this->order['info']['payment'] == "robokassa"):
Wpshop_Orders::setStatus($_GET['order_id'],1);

// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)
$mrh_login = $this->robokassa['login'];
$mrh_pass1 = $this->robokassa['pass1'];

// номер заказа
// number of order
$inv_id = $this->order['id'];

// описание заказа
// order description
$inv_desc = urlencode("Заказ #{$this->order['id']} c cайта {$_SERVER['HTTP_HOST']}.");

// сумма заказа
// sum of order
$out_summ = $order->getTotalSum();

// тип товара
// code of goods
$shp_item = 1;

// предлагаемая валюта платежа
// default payment e-currency
$in_curr = "PCR";

// язык
// language
$culture = "ru";

// кодировка
// encoding
$encoding = "utf-8";

// формирование подписи
// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

// HTML-страница с кассой
// ROBOKASSA HTML-page
print "<script language=JavaScript ".
      "src='https://merchant.roboxchange.com/Handler/MrchSumPreview.ashx?".
//      "src='https://test.robokassa.ru/Handler/MrchSumPreview.ashx?".
      "MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&IncCurrLabel=$in_curr".
      "&Desc=$inv_desc&SignatureValue=$crc&Shp_item=$shp_item".
      "&Culture=$culture&Encoding=$encoding'></script>";

?>
<?php else :?>
<script type="text/javascript">
jQuery(document).ready(function()
{
	window.__cart.reset();
});
</script>
<?php endif;?>