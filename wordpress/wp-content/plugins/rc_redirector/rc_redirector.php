<?php
/*
Plugin Name: RC Link Redirector
Plugin URI: http://chanishvili.org/rc-redirector/
Description: Превращение внешних ссылок во внутренние, с попутной их шифрацией. Очень удобно прятать рефовые и прочие «хитрые» ссылки. Управление плагином: <a href="/wp-admin/themes.php?page=rc_redirector/rc_redirector.php">Внешний вид » RC Link Redirector</a>.
Author: Роланд Чанишвили
Version: 0.7.2
Author URI: http://chanishvili.org/
*/

function rcr_redirect()
{
	$url=$_SERVER['REQUEST_URI'];
	if(($url[0]!='/') and ($url[strlen($url)]!='/'))return;
	$uarr=explode("/",$url);
	$rcr_opt = maybe_unserialize(get_option('rcr_settings'));
	if(!isset($rcr_opt['rcr_relink_val'])) return;
	if($uarr[1]==$rcr_opt['rcr_relink_val']){
		$link = str_replace("/".$uarr[1]."/",'',$url);
		$link = str_replace("==/",'==',$link);
		wp_redirect(html_entity_decode(base64_decode($link)), 302);
		exit;
	}
}

function rcr_isstopwords($data)
{
	global  $rcr_opt;
	if($rcr_opt['rcr_stop']){
	$list = explode("\n",$rcr_opt['rcr_stop_list']);
    foreach($list as $sw)
    	if(stripos($data, trim($sw))!==false) return true;
    }
	return false;
}

function rcr_isstoprel($data)
{
	global  $rcr_opt;
	if($rcr_opt['rcr_rel_stop']){
		$needle = "rel=\"".$rcr_opt['rcr_rel_val']."\"";
		if(stripos($data, $needle)!==false) return true;
		$needle = "rel='".$rcr_opt['rcr_rel_val']."'";
		if(stripos($data, $needle)!==false) return true;
	}
	if($rcr_opt['rcr_class_stop']){
		$needle = "class=\"".$rcr_opt['rcr_class_val']."\"";
		if(stripos($data, $needle)!==false) return true;
		$needle = "class='".$rcr_opt['rcr_class_val']."'";
		if(stripos($data, $needle)!==false) return true;
	}
	return false;
}

function rcr_iscanblank($data)
{
	global  $rcr_opt;
	if (!$rcr_opt['rcr_blank']) return false;
	if ((stripos($data, '.exe')!==false) or (stripos($data, '.zip')!==false) or
		(stripos($data, '.rar')!==false) or (stripos($data, '.msi')!==false)) return false;
	return true;

}

function rcr_encode($data)
{
	global  $rcr_opt;
	$home = get_settings('home');
	preg_match_all('#<a(?:.*?)href=[\"\']((https?|ftp):\/\/\S*?)[\"\']([^>]*)>(.*?)<\/a>#im',$data,$arr);
#	echo "<pre>"; print_r($arr);echo "</pre>";
	for ($i=0; $i<count($arr[0]); $i++) {
		if(stripos($arr[1][$i],$home)!==0){ // Внешняя ссылка
			if(rcr_isstoprel($arr[0][$i])==false){ // Не со служебным rel или class
				if(rcr_isstopwords($arr[1][$i])==false){ // Не в стопсписке
					if (!stristr($arr[0][$i], 'javascript:')) { // Не яваскрипт
						$tmp = $arr[0][$i];
						if($rcr_opt['rcr_comment_text']==true){
							if(strpos($arr[4][$i],'<img')===false){
								if (preg_match('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/im', $arr[4][$i])) $tmp = str_replace('>'.$arr[4][$i].'<','>'.$rcr_opt['rcr_comment_text_val'].'<',$tmp);
							}
						}
						if($rcr_opt['rcr_comment']==true){
							$tmp = str_replace($arr[1][$i],$home.'/'.$rcr_opt['rcr_relink_val'].'/'.base64_encode($arr[1][$i]).'/',$tmp);
						}
						if(rcr_iscanblank($arr[0][$i])) {// Добавлять target=_blank
							$tmp = ereg_replace('target=["\'][^\'".]+["\']', '', $tmp);
							$tmp = str_replace('<a','<a target="_blank"',$tmp);
						}
						if($rcr_opt['rcr_nofollow']){ // Добавлять rel=nofollow
                            $tmp = ereg_replace('rel=["\'][^\'".]+["\']', '', $tmp);
							$tmp = str_replace('<a','<a rel="nofollow"',$tmp);
	                    }
						if($rcr_opt['rcr_noindex']){ // Добавлять noindex
							$tmp = str_replace('</a>','</a></noindex>',$tmp);
							$tmp = str_replace('<a','<noindex><a',$tmp);
	                    }
						$data = str_replace($arr[0][$i],$tmp,$data);
					}
	            }
			}else { // Не обрабатывать, ибо есть служебный rel
				$tmp = ereg_replace('rel=["\'][^\'".]+["\']', '', $arr[0][$i]);
				$data = str_replace($arr[0][$i],$tmp,$data);
			}
		}
	}
return $data;
}

function rcr_encode_bookmarks($data)
{
	global  $rcr_opt;
	for ($i =0 ; $i<count($data); $i++) {
		$data[$i]->link_url = get_settings('home').'/'.$rcr_opt['rcr_relink_val'].'/'.base64_encode($data[$i]->link_url).'/';
		if($rcr_opt['rcr_blank']) $data[$i]->link_target = '_blank';
	}

return $data;
}

function rcr_info($str)
{
	$class = (strpos($str,'!')===false) ? 'updated' : 'error';
	echo '<div class="'.$class.'"><p>'.$str.'</p></div>';
}

function rcr_adminpage()
{
	global  $rcr_opt;
	if(isset($_POST['rcr_save'])) {
		unset($_POST['rcr_save']);
		if(strlen(trim($_POST['rcr_rel_val']))==0)		$_POST['rcr_rel_stop']=0;
		if(strlen(trim($_POST['rcr_class_val']))==0)	$_POST['rcr_class_stop']=0;
		if(strlen(trim($_POST['rcr_stop_list']))==0)	$_POST['rcr_stop']=0;
		if(strlen(trim($_POST['rcr_comment_text_val']))==0)	$_POST['rcr_comment_text']=0;
		if(strlen(trim($_POST['rcr_relink_val']))==0)	$_POST['rcr_relink_val']=dechex(rand(0x1000,0xFFFFFF));
		update_option('rcr_settings',serialize($_POST));
		rcr_info('Установки <strong>RC Redirector</strong> сохранены');
	};

	$rcr_opt = maybe_unserialize(get_option('rcr_settings'));
	if(count($rcr_opt)==1) $rcr_opt = array(
		'rcr_content'=>"1",
		'rcr_excerpt'=>"1",
		'rcr_stop'=>"1",
		'rcr_rel_val'=>dechex(rand(0x1000,0xFFFFFF)),
		'rcr_relink_val'=>dechex(rand(0x1000,0xFFFFFF)),
		'rcr_stop_list'=>"kaak.ru\nchanishvili.org\nrssguru.ru",
		'rcr_comment_text_val'=>"[ссылка]",
	);

?>
<div class="wrap">
<h2>RC Link Redirector</h2>
<form method='post'>
  <fieldset>
  <table>
   <tr><td><label><input name='rcr_content' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_content'] ? 'checked' : '') ?> />
    &nbsp;"Оборачивать" ссылки в <strong>записях</strong></label></td></tr>
   <tr><td><label><input name='rcr_excerpt' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_excerpt'] ? 'checked' : '') ?> />
	&nbsp;"Оборачивать" ссылки в <strong>цитатах</strong></label></td></tr>
   <tr><td><label><input name='rcr_comment' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_comment'] ? 'checked' : '') ?> />
	&nbsp;"Оборачивать" ссылки в <strong>комментариях</strong></label></td></tr>
   <tr><td><label><input name='rcr_comment_author' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_comment_author'] ? 'checked' : '') ?> />
	&nbsp;"Оборачивать" ссылки <strong>на сайты коментаторов</strong></label></td></tr>
   <tr><td><label><input name='rcr_bookmarks' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_bookmarks'] ? 'checked' : '') ?> />
	&nbsp;"Оборачивать" ссылки в <strong>блогролле</strong></label></td></tr>

   <tr><td><label><input name='rcr_comment_text' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_comment_text'] ? 'checked' : '') ?> />
    &nbsp;Если текст ссылки сам является ссылкой, то заменять его на текст </label><input name='rcr_comment_text_val' type='text' size=10 value='<?php  echo $rcr_opt['rcr_comment_text_val'] ?>' /></label></td></tr>

	<tr><td><label><input name='rcr_blank' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_blank'] ? 'checked' : '') ?> />
	&nbsp;Добавлять <strong>target='_blank'</strong> ко всем обработанным ссылкам, кроме архивов и исполнимых файлов</label></td></tr>

	<tr><td><label><input name='rcr_nofollow' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_nofollow'] ? 'checked' : '') ?> />
	&nbsp;Добавлять <strong>rel='nofollow'</strong> ко всем обработанным ссылкам</label></td></tr>

	<tr><td><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Базоваая ссылка для редиректа <input name='rcr_relink_val' type='text' size=6 value='<?php  echo $rcr_opt['rcr_relink_val'] ?>' /></label></td></tr>

	<tr><td><label><input name='rcr_noindex' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_noindex'] ? 'checked' : '') ?> />
	&nbsp;Обрамлять обработанные ссылки тегом <strong>&lt;noindex&gt;</strong></label></td></tr>

	<tr><td><label><input name='rcr_rel_stop' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_rel_stop'] ? 'checked' : '') ?> />
	&nbsp;Использовать служебное значение тега <b>rel="</b></label><label><input name='rcr_rel_val' type='text' size=6 value='<?php  echo $rcr_opt['rcr_rel_val'] ?>' /><b>"</b> отменяющee обработку ссылки.</label></td></tr>

	<tr><td><label><input name='rcr_class_stop' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_class_stop'] ? 'checked' : '') ?> />
	&nbsp;Использовать CSS <b>class="</b></label><label><input name='rcr_class_val' type='text' size=6 value='<?php  echo $rcr_opt['rcr_class_val'] ?>' /><b>"</b> отменяющий обработку ссылки. Специально для продажи ссылок через SAPE.</label></td></tr>

	<tr><td><label><input name='rcr_stop' type='checkbox' value='1' <?php echo ($rcr_opt['rcr_stop'] ? 'checked' : '') ?> />
	&nbsp;Использовать «белый список» слов отменяющих обработку ссылки.<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Один URL на строку, регистр неважен, поиск идет по аттрибуту <strong>href</strong> ссылки</label></td></tr>
	<tr><td><textarea name='rcr_stop_list' rows=6 cols=50><?php echo stripslashes($rcr_opt['rcr_stop_list']); ?></textarea></td></tr>

    <tr><td>&nbsp;<br /><input name='rcr_save' type='submit' id='rcr_save' value='Сохранить изменения »'  /></td></tr>
   </table>
  </fieldset>
</form>

<p>Самая свежая версия плагина доступна на <strong><a href='http://www.chanishvili.org/rc-redirector/' target='_blank'>www.chanishvili.org/rc-redirector</a></strong><br />Консультации и доработки обычно платные, пишите на <a href='mailto:roland@turtleblast.com'>roland@turtleblast.com</a> или стучитесь в ICQ <img alt="Статус ICQ" src="http://web.icq.com/whitepages/online?icq=48426188&amp;img=26" border="0"  class="wp-smiley"> 48426188. О цене  договоримся :)</p>
</div> <!-- wrap -->
<?php
}

function rcr_redirector_hook(){
	if (function_exists('add_management_page')) {
		add_theme_page('RC Link Redirector',  'RC Link Redirector', 8, 'rc_redirector/'.basename(__FILE__), 'rcr_adminpage');
	}
}

add_action('admin_menu','rcr_redirector_hook');
add_action('template_redirect', 'rcr_redirect');

$rcr_opt = maybe_unserialize(get_option('rcr_settings'));
if($rcr_opt['rcr_excerpt']) add_action('the_excerpt', 'rcr_encode');
if($rcr_opt['rcr_content']) add_action('the_content', 'rcr_encode');
if($rcr_opt['rcr_comment']) add_filter('comment_text', 'rcr_encode');
if($rcr_opt['rcr_comment_author']) add_filter('get_comment_author_link', 'rcr_encode');
if($rcr_opt['rcr_bookmarks']) add_filter('get_bookmarks', 'rcr_encode_bookmarks');

?>