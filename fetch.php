<?php
//https://api.coursera.org/api/catalog.v1/courses
$json=file_get_contents("courses.json");
$courses=json_decode($json);
//var_dump($courses->elements[0]->id);
//url="https://api.coursera.org/api/catalog.v1/courses\?ids\=".id."\&fields\=language,shortDescription,aboutTheCourse,targetAudience,courseSyllabus,courseFormat,suggestedReadings,instructor, previewLink,estimatedClassWorkload,recommendedBackground";

$well=0;
$bad=0;
foreach ($courses->elements as $course )
{
	$url="https://api.coursera.org/api/catalog.v1/courses?ids=".$course->id."&fields=language,shortDescription,aboutTheCourse,targetAudience,courseSyllabus,courseFormat,suggestedReadings,instructor,previewLink,estimatedClassWorkload,recommendedBackground";
	echo $url."\n";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$infoj=curl_exec($ch);
	if ($infoj AND $infoj!="")
	{
		$info=json_decode($infoj)->elements[0];
		file_put_contents ( "courses/course.".$course->id.".json" ,json_encode($info));
		echo "course ".$course->id." (".$info->shortName.") OK\n";
		//var_dump($info);
		$well++;
		sleep(15);//for bandwith
	}
	else 
	{
		echo $course->id." ERROR\n";
		file_put_contents(missing.log,$course->id."\n",FILE_APPEND);
		$bad++;
	}
echo "\n".$well." well, ".$bad." wrong";
}
?>
