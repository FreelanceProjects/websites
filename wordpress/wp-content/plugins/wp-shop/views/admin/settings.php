<div class="wrap">
<h2><?php echo __("Settings of WP-Shop",'wpshop');?></h2>
<form method="post">
<input type="hidden" name="update_wpshop_settings" value="1"/>
<table>
	<tbody>
		<tr><td valign='top'>
		<div id="poststuff"><div class="postbox">
			<h3>Настройки:</h3>
			<div class="inside">
				<table>
					<tr>
						<td>
							<label for="cssfile">Выберите файл стилей</label>
						</td>
						<td style="width:250px;">
							<select name="cssfile" style="width:150px">
								<?php echo $this->file_list;?>
							</select>
						</td>
						<td></td>
					</tr>
					<tr>
						<td><label for="cform">Доступные формы</label> </td>
						<td>
						<select name="cform" style="width:150px">
						<?php
							foreach($this->cforms as $cform)
							{
								echo "<option value='{$cform['name']}'{$cform['selected']}>{$cform['name']}</option>";
							}							
						?>
						</select>
						<?php echo $this->formlistbox;?>
						</td>
						<td style="padding-left:10px"><code>указаны формы имеющие поле '<?php echo $this->f_order;?>' (Сделайте поле '<?php echo $this->f_order;?>' скрытым!).</code></td>
					</tr>
					<tr>
					
					<?php $postion_sel = array();
					$postion_sel[$this->position] = 'selected';
					$position_select = "<select name='position' id='position'>
								<option value='top'{$postion_sel['top']}>Вверху</option>
								<option value='bottom'{$postion_sel['bottom']}>Внизу</option>
								</select>";
					?>						
					<td><label for="position">Расположение блока цены:</label></td><td colspan='2'><?php echo $position_select;?></td>
					</tr>
					<tr>
					<td><label for="wp-shop_show-cost">Отображать цену товара в записях и архивах:</label></td>
						<?php $showCostChecked = $this->showCost == 1 ? " checked" : ""; ?>
						<td colspan="2"><input type="checkbox" name="wp-shop_show-cost" id="wp-shop_show-cost"<?php echo $showCostChecked;?>/></td>
					</tr>
					<tr>
						<td><label for="wpshop_payments_activate">Показывать способ оплаты:</label></td>
						<?php $payments_activate = $this->payments_activate == 1 ? " checked" : ""; ?>
						<td><input type="checkbox" name="wpshop_payments_activate" id="wpshop_payments_activate"<?php echo $payments_activate;?>/></td>
						<td><code>(Дополнительые опции включаются в разделе "WP Shop Payments")</code></td>
					</tr>
					<tr>
						<td><label for="wpshop_payments_activate">E-mail уведомления о покупках:</label></td>
						<td><input type="text" name="wpshop_email" id="wpshop_email" value="<?php echo $this->email;?>"/></td>
						<td></td>
					</tr>
					</table>
				</div>
			  </div>
		</div>

					
<div id="poststuff">
	<div class="postbox">
		<h3>Линк в формате Yandex-XML. Укажите его при публикации сайта в системе Yandex.Market</h3>
		<div class="inside">		
			<input type="text" readonly="readonly" value="<?php echo $this->link_to_yml;?>" style="width:100%;" />
			<div style="width:100%;padding-top:10px">
				Этот XML-файл содержит в себе список тех товаров Вашего магазина, у которых указана какая-либо цена.
				Для того чтобы исключить товар из списка, добавьте произвольное поле <code>noyml</code> со значением 1.
				Подробнее об этой опции на этой странице - <a href="http://www.wp-shop.ru/yandex-market/" target="_blank">Публикация товаров в Яндекс.Маркете</a>.
			</div>
		</div>
	</div>
</div>

<div id="poststuff">
	<div class="postbox">
		<h3>Корзина</h3>
		<div class="inside">
			<div><strong>Скидка:</strong> <input type="text" name="discount" value="<?php echo $this->discount;?>" style='width:500px'/></div>
			<div>
			<table><thead><th colspan=2 style='text-align:left;'>Опции минимального заказа:</th></thead>
			<tbody><tr>
			<td>Минимальная сумма заказа:</td><td><input type='text' name='minzakaz' value='<?php echo $this->minzakaz;?>'/></td></tr>
			<tr><td>Сообщение для покупателя:</td><td><textarea name='minzakaz_info'/><?php echo $this->minzakaz_info;?></textarea></td></tr>
			<tr><td>Линк на условие доставки:</td><td><input type='text' name='deliveyrCondition' value='<?php echo $this->deliveyrCondition;?>'/></td></tr>
			</tbody>
			</table>
			</div>
			
		</div>
	</div>
</div>

<div id="poststuff">
	<div class="postbox">
		<h3>Товар</h3>
		<div class="inside">
			<div>
				<div>
					<table>
					<tbody>
					<tr>
						<td>Валюта:</td>
						<td><input type="text" name='currency' value='<?php echo $this->currency;?>'/></td>
					</tr>
					<tr>
						<td>Текст, если товара нет в наличие:</td>
						<td><textarea name='noGoodText' style="width:500px;height:100px"><?php echo $this->noGoodText;?></textarea></td>
					</tr>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
			
<input type="submit" value="Сохранить" class="button">

</td>
<td valign='top' style="width:300px;padding:0px 10px">
<div id="poststuff" class="metabox-holder" style="padding:0px;">
	<div id="side-sortables" class="meta-box-sortabless ui-sortable">
		<div id="sm_pnres" class="postbox">
		<h3 class="hndle"><span>Поддержите авторов плагина WP-Shop!</span></h3>
			<div class="inside">

<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td width='300px' valign='top' style='font-weight: normal; font-size: 11px; font-family: Verdana;'><table cellpadding='0' cellspacing='0' border='0'><tr><td style="padding-bottom:5px;"></td></tr><tr><td valign='top' align='left' style='padding: 5px 5px 5px;'><p>Если наш плагин помог Вам в Вашей работе, поддержите авторов денежкой! С Вашей поддержкой мы сможем продолжать улучшать существующие плагины и делать новые.</p>
<?/*				<h3 style="color: #008000;">
				Выберите Ваш вариант поддержки:<br>
					</td></tr><tr><td valign='middle' style='padding: 0 15px 15px; font-size: 14px; font-family: Verdana; font-weight: bold;' align='center'><table width="100%" cellpadding="5" cellspacing="0" border="0" style="font-size:90%;">
					<tr id="ps_tab_1437" valign="middle"><td width="100%" align="left" style="padding-right:1em; padding-left:0.75em; border-top:1px dotted #999;">скромно</td><td width="100%" align="right" style="border-top:1px dotted #999;"><nobr><strong style="color:#b22222;"><img style="vertical-align:middle;" src="http://donate.wp-shop.ru/goodsblocks/img_price.php?goodsid=1437"></strong>&nbsp;руб.</nobr></td><td align="left" style="border-top:1px dotted #999;"><form action='http://www.wp-shop.ru/payments/cpartners_payments.php' method='post' style='margin:0;'>
					<input type='hidden' name='partner' value='3'>
					<input type='hidden' name='goodsid' value='1437'>
					<input type='hidden' name='userid' value='2'>
					<input type='image' src='http://donate.wp-shop.ru/images/buy.gif' style='width: 72px; height: 22px;'>
					</form>
					</td></tr>
					<tr id="ps_tab_1438" valign="middle"><td width="100%" align="left" style="padding-right:1em; padding-left:0.75em; border-top:1px dotted #999;">солидно</td><td width="100%" align="right" style="border-top:1px dotted #999;"><nobr><strong style="color:#b22222;"><img style="vertical-align:middle;" src="http://donate.wp-shop.ru/goodsblocks/img_price.php?goodsid=1438"></strong>&nbsp;руб.</nobr></td><td align="left" style="border-top:1px dotted #999;"><form action='http://www.wp-shop.ru/payments/cpartners_payments.php' method='post' style='margin:0;'>
					<input type='hidden' name='partner' value='3'>
					<input type='hidden' name='goodsid' value='1438'>
					<input type='hidden' name='userid' value='2'>
					<input type='image' src='http://donate.wp-shop.ru/images/buy.gif' style='width: 72px; height: 22px;'>
					</form>
					</td></tr>
					<tr id="ps_tab_1439" valign="middle"><td width="100%" align="left" style="padding-right:1em; padding-left:0.75em; border-top:1px dotted #999;">V.I.P</td><td width="100%" align="right" style="border-top:1px dotted #999;"><nobr><strong style="color:#b22222;"><img style="vertical-align:middle;" src="http://donate.wp-shop.ru/goodsblocks/img_price.php?goodsid=1439"></strong>&nbsp;руб.</nobr></td><td align="left" style="border-top:1px dotted #999;"><form action='http://www.wp-shop.ru/payments/cpartners_payments.php' method='post' style='margin:0;'>
					<input type='hidden' name='partner' value='3'>
					<input type='hidden' name='goodsid' value='1439'>
					<input type='hidden' name='userid' value='2'>
					<input type='image' src='http://donate.wp-shop.ru/images/buy.gif' style='width: 72px; height: 22px;'>
					</form>
					</td></tr>
					</table>
				</h3>
*/?>
<iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/small.xml?uid=41001786529092&amp;button-text=06&amp;button-size=l&amp;button-color=orange&amp;targets=%d0%9f%d0%be%d0%b4%d0%b4%d0%b5%d1%80%d0%b6%d0%ba%d0%b0+%d0%b0%d0%b2%d1%82%d0%be%d1%80%d0%be%d0%b2+%d0%bf%d0%bb%d0%b0%d0%b3%d0%b8%d0%bd%d0%b0+wp-shop&amp;default-sum=299&amp;mail=on" width="auto" height="54"></iframe>

					<div style="font-weight:normal; text-align:left; margin:0; padding:0.5em 0 0; border-top:1px dotted #999;">
								<!-- доп.инфо -->
								</div>
								</td></tr></table></td></td></tr></table>
					<ol>
					<li> <strong>Авторы плагина:</strong> Кузнецов Александр, Бобко Игорь</li>
					<li><strong>Сайт плагина:</strong> <a href="http://www.wp-shop.ru/" target="_blank">www.wp-shop.ru</a></li>
					<li><strong>email:</strong> <a href="mailto:masta@wp-shop.ru">masta@wp-shop.ru</a></li>
					<li><strong>Skype:</strong> wpshop</li>
					</ol>
			</div></div></div></div>
	
<div id="poststuff" class="metabox-holder" style="padding:0px;">
	<div id="side-sortables" class="meta-box-sortabless ui-sortable">
		<div id="sm_pnres" class="postbox">
			<h3 class="hndle"><span>META-поле для вывода в прайс-листах</span></h3>
			<div class="inside" id="price_option_window">
			<script type="text/javascript">
				jQuery(document).ready(function()
				{
						jQuery('#price_submit').bind('click',function()
						{
							jQuery.post('<?php echo WPSHOP_URL;?>/ajax_post.php',{act:'price_options',under_title:jQuery("[name='under_title']").val()},function(data, textStatus)
							{
								if (textStatus == "success")
								{
									alert('Готово');
								}
							},'html');
					});
				});
				</script>
				<div><p>Здесь можно указать наименование дополнительного <code>post_meta</code>, которое Вы хотите показывать в прайслистах.</p></div>
				<br/>
				<input type='text' name="under_title" value="<?php echo $this->opt_under_title;?>">
				<div class="submit"><input id="price_submit" type='button' value="Сохранить"></div>
			</div>
		</div>
	</div>
</div>
<div id="poststuff" class="metabox-holder" style="padding:0px;">
	<div id="side-sortables" class="meta-box-sortabless ui-sortable">
		<div id="sm_pnres" class="postbox">
			<h3 class="hndle"><span>Обновление по валюте.</span></h3>'
			<div class="inside">
				<script type="text/javascript">
				jQuery(document).ready(function()
				{
						jQuery('#update_currency').bind('click',function()
						{
							jQuery.post('<?php echo WPSHOP_URL; ?>/set_currency.php', {usd:jQuery("[name='usd']").val(),eur:jQuery("[name='eur']").val()},function(data, textStatus)
							{
								if (textStatus == "success")
								{
									alert('Готово');
								}
							});

						});
				});
				</script>
				<div>USD - <input type='input' value="<?php echo $this->usd_cur;?>" name="usd"></div>
				<div>EUR - <input type='input' value="<?php echo $this->eur_cur;?>" name="eur"></div>
				<br/>
				<div>
					<input type='button' value="Обновить" id="update_currency">
				</div>
			</div>
		</div>
	</div>
	<form method = 'post'><div id="poststuff" class="metabox-holder" style="padding:0px;">
		<div id="side-sortables" class="meta-box-sortabless ui-sortable">
			<div id="sm_pnres" class="postbox">
				<h3 class="hndle"><span>Линк для несчастных пользователей IE 6</span></h3>
				<div class="inside">
					<div><input type='input' value="<?php echo $this->link_ie6;?>" name="link_ie6" style="width:100%"/></div>
					<div><input type='submit' value="Сохранить"/></div>
				</div>
			</div>
		</div>
	</form>


	</td>
</tr>
</tbody>
</table>
</form>
</div>
