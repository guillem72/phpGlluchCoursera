<?php
/**
 * This file is not used
 **/
$path = __DIR__ . "/categories";
$path_courses = __DIR__ . "/courses";
$path_target = __DIR__ . "/courses_completed";
//echo $path."\n";
$dir = opendir($path);
//$limit=0;
while ($item = readdir($dir)) {
    if ($item != "." && $item != ".." && !is_dir($path . "/" . $item)) {
        $json = file_get_contents($path . "/" . $item);
        $category = json_decode($json);
        //var_dump($category);
        $name = $category->name;
        $courses = $category->links->courses;
        echo "Working on " . $name . "\n";
        //var_dump($courses);
        foreach ($courses as $course) {
            if (@$json = file_get_contents($path_courses . "/course." . $course . ".json")) {
                $course_info = json_decode($json, true);
                $course_info['categories'][] = $name;
                //var_dump($course_info);
                file_put_contents($path_target . "/course." . $course . ".json", json_encode($course_info, JSON_PRETTY_PRINT));
            }
        }

    } else {
        ;//echo "Warning: there is a dir in categories";
    }
//if ($limit++>1) break;
}

?>
