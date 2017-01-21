<?php
$path=__DIR__."/coursesStandard/";
$path_target=__DIR__."/clasified/";
$path_cats=__DIR__."/categories/";

$dir = opendir($path_cats);
//var_dump($dir);
$cats=array();
while ($item = readdir($dir))
{
	
	if( isset($item) AND $item != "." && $item != ".." && !is_dir($path.$item))
	{
		
		$info=json_decode(file_get_contents($path_cats.$item));
		if (isset($info->name))
		$cats[$info->name]=$info->links->courses;
		else 
		{
			echo $item.PHP_EOL;
			var_dump($info);
		}
		
	}
}

$dir = opendir($path);

while ($item = readdir($dir))
{
	if( $item != "." && $item != ".." && !is_dir($path.$item))
	{
		$info=json_decode(file_get_contents($path.$item));
		//var_dump($info->courseraId);
		
		foreach ($cats as $cat => $ids )
		{
			//echo "ids=".PHP_EOL;
			//var_dump($ids);
			//echo "Fi ids".PHP_EOL;
			 
			if (is_array($ids))
			{
				if (in_array($info->courseraId,$ids))
				{
					//echo $info->title." is in cat ".$cat.PHP_EOL;
					if (isset ($info->subjects))
					{
						$info->subjects.=", ".$cat;
					}
					else
						$info->subjects=$cat;
				}
			}  
			//"ids is not an array!!".$cat.PHP_EOL;
			//var_dump($info); 
			//sleep(5);
		}//foreach 
		
	if (isset($info->subjects))
	@file_put_contents ( $path_target.$item ,json_encode($info,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	else echo $info->title." doesn't have any subject".PHP_EOL;
	}
	
}


?>
