<?php

$path = __DIR__ . "/courses/";
$path_target = __DIR__ . "/coursesStandard/";
//echo $path."\n";
$dir = opendir($path);
//echo $dir;


$map = array(
    "aboutTheCourse" => "description",
    "shortDescription" => "description",
    "courseSyllabus" => "contents",
    "instructorIds" => "instructors",
    "recommendedBackground" => "requirements",
    "estimatedClassWorkload" => "effort",
    "workload" => "effort",
    "partnerIds" => "institutions",
    "domainTypes" => "subjects",
    "language" => "language",
    "shortName" => "url",
    "name" => "title",
    "id" => "courseraId"
);

while ($item = readdir($dir)) {
    if ($item != "." && $item != ".." && !is_dir($path . $item)) {
        //echo "if superat".PHP_EOL;
        $info = json_decode(file_get_contents($path . $item));
        $course = array("source" => "Coursera");
        //$course->source="Coursera";
        //echo "a punt foreach ".PHP_EOL;
        foreach ($map as $original => $new) {
            //echo "item= ".$item." new=".$new.PHP_EOL;
            if (isset($info->$original) AND $info->$original != "") {
                //echo "item= ".$item." new=".$new.PHP_EOL;
                if (isset($course[$new])) $course[$new] .= " " . strip_tags($info->$original);
                else  $course[$new] = strip_tags($info->$original);
                if ($original === "shortName") $course["url"] = "https://www.coursera.org/course/" . $info->$original;
                if ($original === "instructorIds") $course["instructors"] = getInstructors($info->$original);
                if ($original === "partnerIds") $course["institutions"] = getInstitutions($info->$original);
                if ($original === "courseSyllabus") {
                    $p = $info->$original;
                    if (count($p) > 1) {
                        $provi = array_map("strip_tags", $p);
                        $course["contents"] = array_map("trim", $provi);
                    } elseif (count($p) == 1) $course["contents"] = strip_tags($p);
                }


            }

        }
        //var_dump($course);
        try {
            @file_put_contents($path_target . $info->name . ".json", json_encode($course, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        } catch (Exception $e) {
            echo $e . PHP_EOL;
        }
    }
}

function getInstructors($ids)
{
    if (!isset($ids) OR $ids === null OR $ids === "") return false;
    $url1 = "https://api.coursera.org/api/instructors.v1?ids=";
    $url2 = "&fields=fullName,firstName,middleName,lastName";
    $instructors = array();
    foreach ($ids as $id) {
        $url = $url1 . $id . $url2;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $infoj = curl_exec($ch);
        sleep(12);
        if ($infoj AND $infoj != "") {
            $info = json_decode($infoj)->elements[0];
            if (isset($info->fullname) AND $info->fullname != "") $instructors[] = trim($info->fullname);
            else {
                if (isset($info->middleName) AND $info->middleName != "") $instructors[] = trim(implode(" ", array($info->firstName, $info->middleName, $info->lastName)));
                else
                    $instructors[] = trim(implode(" ", array($info->firstName, $info->lastName)));
            }

        }
    }
    if (count($instructors) == 1) return $instructors[0];
    else return $instructors;
}

function getInstitutions($ids)
{
    if (!isset($ids) OR $ids === null OR $ids === "") return false;
    $url = "https://api.coursera.org/api/partners.v1?ids=";
    $institutions = array();
    //echo "var_dump Start:".PHP_EOL;
    //var_dump($ids);
    //echo "var_dump ENd:".PHP_EOL;
    foreach ($ids as $id) {
        $url = $url . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $infoj = curl_exec($ch);
        sleep(16);
        if ($infoj AND $infoj != "") {
            $info = json_decode($infoj)->elements[0];
            $institutions[] = $info->name;
        }
    }
    if (count($institutions) == 1) return $institutions[0];
    else return $institutions;

}


?>
