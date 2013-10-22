var CURR = '&nbsp;';

var SPL =	'}{';

var cart_col_name = 'Наименование';
var cart_col_price = 'Цена';
var cart_col_count = 'Кол-во';
var cart_col_sum = 'Сумма';
var cart_col_type = '';

function wshop(url,cart,win)
{
	var self = this;
	var _url = url; // url with hash of prodovator
	var gSellers = []; // global array for this class of sellers
	var _cart = cart;
	var _win = win;
	
	this.findElement = function(arr,id)
	{
		var result = -1;
		jQuery.each(arr, function(i,item)
		{
			if (item.id == id)
			{
				result = i;
				return;
			}
		});
		return result;
	}
}

function Cart(eid_mini, eid_cart)
{
	this.mini = document.getElementById(eid_mini);
	this.cart = document.getElementById(eid_cart);
	this.discount = 0;
	

	var OnUpdate = undefined;
	var wps = undefined;
	
	var CARTTHIS = this;

	
	this.init = function()
	{
		this.count = 0;
		this.a_id = new Array();
		this.a_key = new Array();
		this.a_name = new Array();
		this.a_href = new Array();
		this.a_num = new Array();
		this.a_cost = new Array();
	}

	this.load = function()
	{
		var count = getcookie('wpshop_count');
		this.count = count ? parseFloat(count) : 0;

		if (this.count)
		{
			this.a_id = String(s_id = getcookie('wpshop_id')).split(SPL);
			this.a_key = String(s_id = getcookie('wpshop_key')).split(SPL);
			this.a_name = String(s_name = getcookie('wpshop_name')).split(SPL);
			this.a_href = String(s_href = getcookie('wpshop_href')).split(SPL);
			this.a_num = String(s_num = getcookie('wpshop_num')).split(SPL);
			this.a_cost = String(s_cost = getcookie('wpshop_cost')).split(SPL);
		}
	}

	this.save = function()
	{
		var s_id = this.a_id.join(SPL);
		var s_key = this.a_key.join(SPL);
		var s_name = this.a_name.join(SPL);
		var s_href = this.a_href.join(SPL);
		var s_num = this.a_num.join(SPL);
		var s_cost = this.a_cost.join(SPL);

		
		setcookie('wpshop_count', this.count, 0, '/');
		setcookie('wpshop_id', s_id, 0, '/');
		setcookie('wpshop_key', s_key, 0, '/');
		setcookie('wpshop_name', s_name, 0, '/');
		setcookie('wpshop_href', s_href, 0, '/');
		setcookie('wpshop_cost', s_cost, 0, '/');
		setcookie('wpshop_num', s_num, 0, '/');
	}

	
	this.getTotalSum = function()
	{
		var total = 0;
		var i = 0;
		for (i = 0; i < this.count; i++)
		{
			t = parseFloat(this.a_cost[i]) * this.a_num[i];
			t = t.toFixed(2);
			t = t * 1;
			total += t;
		}
		
		var tmp = String(this.discount).split(";");
		var max_discount = 0;
		for (property in tmp)
		{
			var t = tmp[property].split(':');
			if (total > t[0])
			{
				if ((max_discount*1) < (t[1]*1))
				{
					max_discount = t[1];
				}
				
			}
		}
		total = (total / 100 * (100-max_discount)).toFixed(2);
		
		return total;
	}
	
	this.afterChange = function()
	{
		jQuery("[name='select_delivery']").change(function()
		{
			var deliveryCost = (jQuery(this).find('option:selected').attr('cost') * 1).toFixed(2);
			jQuery('#delivery_cost_value').html(deliveryCost);
			CARTTHIS.getTotalSum();
			jQuery('#delivery_cost_total').html((CARTTHIS.getTotalSum()*1 + deliveryCost*1).toFixed(2));
			jQuery('#delivery_cost').css('display','table-row');
			jQuery('#delivery_cost').width(jQuery(".recycle_bin").width());
			jQuery(".cform input[name='delivery']").val(jQuery(this).find('option:selected').val());
		}).change();
		
		//document.location = document.location;
	}
	
	this.update = function()
	{
		if (this.count)
		{
			this.content_mini = '<div class="wpshop_widget">';
			this.content_cart = 
				'<table class="recycle_bin" cellpadding="5" cellspacing="0" border="0">'+
				'<thead>'+
				'<tr>'+
				'<th class="name">' + window.cart_col_name + '</th>'+
				'<th class="type">' + window.cart_col_type + '</th>'+
				'<th class="cost">' + window.cart_col_price + ' ('+ CURR +')</th>'+
				'<th class="num">'+ window.cart_col_count + '</th>'+
				'<th class="total">' + window.cart_col_sum + ' ('+ CURR +')</th>'+
				'<th class="delete">&nbsp;</th>'+
				'</tr>'+
				'</thead>'+
				'<tbody>';
			
			var i, t, total;
			for (i = 0, total = 0; i < this.count; i++)
			{								t = parseFloat(this.a_cost[i]) * this.a_num[i];				t = t.toFixed(2);				t = t * 1;
				total += t;
				


				this.content_cart +=
					'<tr class="rb_item" valign="top">' +
					'<td class="rb_name"><a href="'+ this.a_href[i] +'">'+ this.a_name[i] +'</a></td>' +
					'<td class="rb_type">'+ this.a_key[i] +'&nbsp;</td>' +
					'<td class="rb_cost">'+ this.a_cost[i] +'</td>' +
					'<td class="rb_num"><a class="minus" href="#" onmousedown="__cart.minus(\''+this.a_id[i]+'\', \''+this.a_key[i]+'\', 1,'+i+'); return false;">&minus;</a><a class="plus" href="#" onmousedown="__cart.plus(\''+this.a_id[i]+'\', \''+this.a_key[i]+'\', 1,'+i+'); return false;">+</a><input type="text" value="'+ this.a_num[i] +'" size="3" maxlength="6" onchange="__cart.set(\''+this.a_id[i]+'\', \''+this.a_key[i]+'\', this.value,'+i+'); return false;" /></td>' +
					'<td class="rb_total">'+ t +'</td>' +
					'<td class="rb_delete"><a title="Удалить" href="#" onclick="__cart.remove(\''+this.a_id[i]+'\', \''+this.a_key[i]+'\',' + i + '); return false;" style="text-decoration:none; color:#f00;">&times;</a></td>' +
					'</tr>';
			}
			
			total = total.toFixed(2)*1;
			
			this.content_cart +=
				'<tfoot>'+
				'<tr>'+
				'<td colspan="4" align="right"><strong>ИТОГО:</strong></td>'+
				'<td class="rb_total cost"><strong>'+total+'</strong></td>'+
				'<td class="rb_delete"><a title="Удалить все" href="#" onclick="if (confirm(\'Очистить корзину?\')) __cart.reset(); return false;" style="text-decoration:none; color:#f00;">&times;</a></td>' +
				'</tr>';
			if (this.discount != 0 && this.discount != undefined)
			{
				var tmp = String(this.discount).split(";");
				var max_discount = 0;
				for (property in tmp)
				{
					var t = String(tmp[property]).split(':');
					
					if (total*1 > t[0])
					{
						if ((max_discount*1) < (t[1]*1))
						{
							max_discount = t[1];
						}
						
					}
				}
			
				if (max_discount > 0)
				{
					var price = (total / 100 * (100-max_discount)).toFixed(2);
					setcookie('wpshop_discount', max_discount, 0, '/');
					setcookie('wpshop_new_price', price, 0, '/');
				
				this.content_cart += '<tr class="discount_row">'+
				'<td colspan="4" align="right" ><strong>Ваша скидка: ' + max_discount + '%. ИТОГО со скидкой:</strong></td>'+
				'<td class="rb_total cost"><strong>'+ price+'</strong></td>'+
				'<td class="rb_delete"></td>' +
				'</tr>';
				}
				else
				{
					setcookie('wpshop_discount', 0, 0, '/');
				}
			}


			this.content_cart +=	
			"<tr style='display:none;text-align:right;margin-top:15px' id='delivery_cost'><td colspan='4' style='font-weight:bold'>Стоимость с учетом доставки (<span id='delivery_cost_value'></span> "+CURR+")</td><td id='delivery_cost_total' style='text-align:left;font-weight:bold'></td><td></td></tr>" + 
			
			'</tfoot>'+
			
				'</table>';

			this.content_mini +=
				'<div class="wpshop_mini_count"><strong>Позиций</strong>: '+this.count+'</div>'+
				'<div class="wpshop_mini_sum"><strong>На сумму</strong>: '+total+'&nbsp;'+CURR+'</div>'+
				'<div class="wpshop_mini_under"></div>'+
				'</div>';	
		}
		else
		{
			this.content_mini = '<div class="minicart">Ваша корзина пуста.</div>';
			this.content_cart = '<div class="minicart">Ваша корзина пуста.</div>';
		}

		if (this.mini)
		{
			this.mini.innerHTML = this.content_mini;
		}
		if (this.cart)
		{
			this.cart.innerHTML = this.content_cart;
			//if (this.mini) this.mini.parentNode.parentNode.style.opacity = 0.33;
		}
		this.afterChange();
	}

	this.reset = function()
	{
		this.is_empty = true;
		this.content_mini = '';
		this.content_cart = '';

		setcookie('wpshop_count', null, 0, '/');
		setcookie('wpshop_id', null, 0, '/');
		setcookie('wpshop_key', null, 0, '/');
		setcookie('wpshop_name', null, 0, '/');
		setcookie('wpshop_href', null, 0, '/');
		setcookie('wpshop_cost', null, 0, '/');
		setcookie('wpshop_num', null, 0, '/');
		
		this.init();
		this.save();
		//location.reload();
		this.update();
	}

	this.add = function(id, key, name, href, cost, num, cnt)
	{
		if (cnt!=undefined)
		{
			num = cnt;
		}
		
		var n = this.count;
		/*
		for (i = 0; i < this.count; i++)
			if (this.a_id[i] == id && this.a_key[i] == key)
				n = i;
		*/		
		this.a_id[n] = id;
		this.a_key[n] = key;
		this.a_name[n] = name;
		this.a_href[n] = href;
		this.a_cost[n] = cost;
		this.a_num[n] = this.a_num[n] ? parseFloat(this.a_num[n]) + parseFloat(num) : parseFloat(num);

		if (n == this.count)
			this.count++;
		this.save();
		this.update();
	}
	
	this.remove = function(id, key, index)
	{
		if (index != undefined && this.a_num[index] != undefined)
		{
			this.a_id.splice(index, 1);
			this.a_key.splice(index, 1);
			this.a_name.splice(index, 1);
			this.a_href.splice(index, 1);
			this.a_cost.splice(index, 1);
			this.a_num.splice(index, 1);
			this.count--;
		}
		else
		{
			for (i = 0; i < this.count; i++)
			{
				if (this.a_id[i] == id && this.a_key[i] == key)
				{
					this.a_id.splice(i, 1);
					this.a_key.splice(i, 1);
					this.a_name.splice(i, 1);
					this.a_href.splice(i, 1);
					this.a_cost.splice(i, 1);
					this.a_num.splice(i, 1);
					this.count--;
					break;
				}
			}
		}	
		this.save();
		this.update();
		document.location = document.location;
	}

	this.set = function(id, key, value,index)
	{
		if (index != undefined && this.a_num[index] != undefined && parseFloat(value) > 0)
		{
			this.a_num[index] = parseFloat(value);
		}
		else
		{		
			for (i = 0; i < this.count; i++)
			{
				if (this.a_id[i] == id && this.a_key[i] == key && (value = parseFloat(value)) > 0)
				{
					this.a_num[i] = value;
					break;
				}
			}
		}
		this.save();
		this.update();
	}
	
	/**
	 * @since 17.04.2011 Вводится понятие индекса. Если он указан, то поиск элемента не делается.
	 */
	this.plus = function(id, key, value,index)
	{
		if (index != undefined && this.a_num[index] != undefined)
		{
			this.a_num[index] = parseFloat(this.a_num[index]) + parseFloat(value);
		}
		else
		{
			for (i = 0; i < this.count; i++)
			{
				if (this.a_id[i] == id && this.a_key[i] == key)
				{
					this.a_num[i] = parseFloat(this.a_num[i]) + parseFloat(value);
					break;
				}
			}
		}

		this.save();
		this.update();
		document.location = document.location;
	}

	this.minus = function(id, key, value,index)
	{
		if (index != undefined && this.a_num[index] != undefined && (num = parseFloat(this.a_num[index])) > 1)
		{
			
			this.a_num[index] = num - parseFloat(value);
		}
		else
		{
			var num;
			for (i = 0; i < this.count; i++)
			{
				if (this.a_id[i] == id && this.a_key[i] == key && (num = parseFloat(this.a_num[i])) > 1)
				{
					this.a_num[i] = num - parseFloat(value);
					break;
				}
			}
		}

		this.save();
		this.update();
		document.location = document.location;
	}

	this.init();
	this.load();
	this.update();
}


function addtocart(id, key, href, cost, num,cnt)
{
	var name = jQuery("[name='wpshop-good-title-" + id + "']").val();
	var properties = [];
	jQuery('#wpshop_property_'+id).find('input,select,textarea').each(function(index,obj)
	{
		properties.push(jQuery(obj).attr('name') + ': ' + jQuery(obj).val());
	});
	
	
	
	if (properties.length > 0)
	{
		name = name + '<br/>(' + properties.join(', ') + ')';
	}
	
	
	var jWpField = jQuery('[for="wpshop-wpfield"]');
	
	if (jWpField.html() != null)
	{
		if (jQuery('#wpshop-wpfield').val().length > 0)
		{
			var wpfield = jWpField.html() + ": " + jQuery('#wpshop-wpfield').val();
			name = name + "<br/>" + wpfield;	
		}
	}
	
	var t = jQuery('[name="goods_count_' + id + "_" + cnt + '"]');
	window.__cart.add(id, key, name, href, cost, num,t.val()); 
	alert('Успешно добавлено в корзину!');
	//document.location = 'http://yandex.ru';
}

function button_effect()
{
	jQuery(".wpshop_animate_icon").mouseover(function()
	{
		jQuery(this).animate({"background-color": '#6B8DB1'}, 200 );
	});
	
	jQuery(".wpshop_animate_icon").mouseout(function()
	{
		jQuery(this).animate({"background-color": '#C4D2E1'}, 200 );
	});
}


jQuery(function()
{
	if (jQuery('.wpshop_bag').find('.wpshop_good_price').css('display') == 'none')
	{
		jQuery('.wpshop_bag').hover(
		function()
		{
			jQuery(this).find('.wpshop_good_price').css('display','block');
		},
		function()
		{
			jQuery(this).find('.wpshop_good_price').css('display','none');
		});
	}
	
	button_effect();
	

});

//----------

