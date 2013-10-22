<?php
/*
Plugin Name: Sergey AnythingSlider
Plugin URI: http://www.wp-shop.ru/blog/krasivyiy-slayder-dlya-rotatsii-tovarov/
Description: This is plugin for inserting AnythingSlider to your blog's posts and pages
Author: Sergey Makarin
Author URI: http://sergey.info/
Version: 1.1
*/

function sergey_anythingslider_replace_code($code){	/* Load params from code */
	$code = preg_replace('`"(.*?)"`e', 'urlencode("$1")', $code);
	$params = explode(' ', $code);
	$cat = 0;
	$count = 0;
	$width = 600;
	$height = 360;
	$tag = '';
	$textmore = '';
	foreach ($params as $p){		list($k, $v) = explode('=', $p);
		$k = strtolower($k);
		$v = htmlspecialchars(stripslashes(trim(chop($v))));
		$v = urldecode($v);
		if ($v)
		if (in_array($k, array('cat', 'count', 'width', 'height'))){
			$$k = intval(trim(chop($v)));
		}elseif (in_array($k, array('tag', 'moretext'))){			$$k = $v;
		}
	}
	/* Output AnythingSlider */
	$html = '';
	global $post;

	$starttext = 'Старт';
	$stoptext = 'Стоп';

	$slider_id = 'slider-'.md5(rand().rand().rand());

	$html .= '
	<!-- Sergey AnythingSlider -->

	<script type="text/javascript">
		var $anythingSlider = jQuery.noConflict();
		$anythingSlider(function () {
			$anythingSlider(\'#'.$slider_id.'\').anythingSlider({
				startStopped    : false, // If autoPlay is on, this can force it to start stopped
				width           : '.$width.',  // Override the default CSS width
				height          : '.$height.',  // Override the default CSS width
				startText       : "'.$starttext.'",   // Start button text
				stopText        : "'.$stoptext.'",    // Stop button text
//				toggleControls  : true, // if true, then slide in controls on hover and slider change, hide @ other times
				theme           : \'metallic\',
				onSlideComplete : function(slider){
					// alert(\'Welcome to Slide #\' + slider.currentPage);
				}
			});

			// Report Events to console
			$anythingSlider(\'.anythingSlider\').bind(\'slideshow_start slideshow_stop slideshow_paused slideshow_unpaused slide_init slide_begin slide_complete\',function(e, slider){
				// show object ID + event (e.g. "'.$slider_id.': slide_begin")
				var txt = slider.$el[0].id + \': \' + e.type + \', now on panel #\' + slider.currentPage;
				$anythingSlider(\'#status\').text(txt);
				if (window.console && window.console.firebug){ console.debug(txt); } // added window.console.firebug to make this work in Opera
			});

			// Theme Selector (This is really for demo purposes only)
			var themes = [\'minimalist-round\',\'minimalist-square\',\'metallic\',\'construction\',\'cs-portfolio\'];
			$anythingSlider(\'#currentTheme\').change(function(){
				var theme = $anythingSlider(this).val();

				if (!$anythingSlider(\'link[href*=\' + theme + \']\').length) {
					$anythingSlider(\'body\').append(\'<link rel="stylesheet" href="'.get_bloginfo('wpurl').'/wp-content/plugins/sergey-anythingslider/css/theme-\' + theme + \'.css" type="text/css" media="screen" />\');
				}
				$anythingSlider(\'#'.$slider_id.'\').closest(\'div.anythingSlider\')
					.removeClass( $anythingSlider.map(themes, function(t){ return \'anythingSlider-\' + t; }).join(\' \') )
					.addClass(\'anythingSlider-\' + theme);
			});

		});
	</script>

	<ul id="'.$slider_id.'">'."\r\n\t\t\t\t";
	$myposts = get_posts('category='.$cat.'&numberposts='.$count.'&tag='.$tag);
	foreach($myposts as $post) :
		setup_postdata($post);
		/**/
		ob_start();
			the_content();
			$content = ob_get_contents();
		ob_clean();
		list($excerpt) = explode('<span id="more-'.get_the_ID().'"></span>', $content);
		/**/
		ob_start();
		?>
			<li class="panel1">
				<div id="textSlide">
					<div style="padding:28px">
						<h2><a href="<?= get_permalink() ?>"><?php the_title() ?></a></h2>
						<div><?php
							/*the_content();*/
							/*the_excerpt();*/
							echo $excerpt;
							echo ' <p style="text-align:right"><b><a href="'.get_permalink().'">'.$moretext.'</a></b></p> ';
						?></div>
					</div>
				</div>
			</li>
		<?
		$html .= ob_get_contents();
		ob_clean();
	endforeach;
	$html .= '
	</ul>
	<div></div>
	<!-- /Sergey AnythingSlider -->
	';
	/*  Rerurn HTML code */	return $html;
}

function sergey_anythingslider_head(){?>
<!-- Sergey AnythingSlider -->
<link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-content/plugins/sergey-anythingslider/css/anythingslider.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('wpurl'); ?>/wp-content/plugins/sergey-anythingslider/css/sergey-anythingslider.css" type="text/css" media="screen" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>

<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/sergey-anythingslider/js/jquery.easing.1.2.js"></script>

<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/sergey-anythingslider/js/swfobject.js"></script>

<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/sergey-anythingslider/js/jquery.anythingslider.js" charset="utf-8"></script>
<!-- /Sergey AnythingSlider -->
<?}

function sergey_anythingslider_content($txt){	$txt = preg_replace("`\<!--sergey-anythingslider (.*?)-->`ei", "sergey_anythingslider_replace_code(\"$1\")", $txt);
	return $txt;}
function wp_slider_footer()
{
if (is_front_page())
{
$content = '<a title="создание и продвижение интернет магазинов" href="http://www.wp-shop.ru"></a>';
echo $content;
}
}
add_action('wp_footer', 'wp_slider_footer');
function sergey_anythingslider_tinymce_add_button($head){	if (preg_match('~post(-new)?.php~',$_SERVER['REQUEST_URI'])){		wp_print_scripts( 'quicktags' );
		echo "<script type=\"text/javascript\">"."\n";
		echo "/* <![CDATA[ */"."\n";
		echo "edButtons[edButtons.length] = new edButton"."\n";
		echo "\t('ed_sergey_anythingslider',"."\n";
		echo "\t'AnythingSlider'"."\n";
		echo "\t,'<!--sergey-anythingslider cat=1 count=20 width=600 height=360 moretext=\"Подробнее\" -->'"."\n";

		echo "\t,''"."\n";
		echo "\t,'n'"."\n";
		echo "\t);"."\n";
		echo "/* ]]> */"."\n";
		echo "</script>"."\n";
	}
}

function sergey_anythingslider_show($params){	echo sergey_anythingslider_replace_code($params);}

if (function_exists('add_filter')) {
	add_filter('the_content', 'sergey_anythingslider_content');
	add_filter('wp_head', 'sergey_anythingslider_head');
	add_action('admin_head', 'sergey_anythingslider_tinymce_add_button');
}
?>