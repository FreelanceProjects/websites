<?php 
/**
 * 
 * @author Igor Bobko
 */
class Wpshop_View extends stdClass
{
	public function render($file)
	{
		$path = WPSHOP_VIEWS_DIR."/{$file}";
		if (file_exists($path))
		{
			include $path;	
		}
	}
}
