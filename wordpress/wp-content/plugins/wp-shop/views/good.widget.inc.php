<div class="wpshop_bag <?php echo $this->class;?>">
	<input type='hidden' name="wpshop-good-title-<?php echo $this->post->ID;?>" value='<?php echo htmlspecialchars($this->post->post_title,ENT_QUOTES);?>'/>
	<div class="wpshop_buy">
		<table cellpadding="5" cellspacing="0" border="0" width="100%">
		<?php
		$i = 0;
		foreach ($this->cost as $key => $val)
		{
			$i++;
			if ($this->sklad[$key] == "" || $this->sklad[$key] > 0)
			{
				$addClick = "addtocart('{$this->post->ID}', '{$key}', '".get_permalink($this->post->ID)."', '{$val}', 1,'{$i}'); return false;";
				?>
				<tr>
					<?php if (isset($this->columns['name'])):?>
					<td class="wpshop_caption">
						<a style="cursor: pointer" href="#" onclick="javascript:<?php echo $addClick; ?>;" style="font-weight:bold;"><?php print $key; ?></a>
					</td>
					<?php endif;?>
					<?php if (isset($this->columns['cost'])) : ?>
					<td class="wpshop_price">
						<?php echo ($val . ' ' .CURR); ?>
					</td>
					<?php endif;?>
					<td class="wpshop_count">
						<input maxlength='3' type='text' value='1' name='goods_count_<?php echo "{$this->post->ID}_{$i}";?>' size='3' />
					</td>
					<td class="wpshop_button">
						<a href="#" onclick="<?php echo $addClick; ?>" class="arrow_button" alt="Добавить" title="Заказать"></a>
					</td>
				</tr>
		<?php
			}
			else
			{
				echo get_option("wpshop.good.noText");
			}
		}
		?>
		</table>
	</div>
</div>