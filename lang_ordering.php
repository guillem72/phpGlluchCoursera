<?php
/**
 * Adds the lang info to the json and places in the right directory.
*/
lang_dirs();
function lang_dirs()
{
    $langs = array(
        "English" => "en",
        "English, 中文" => "en",
        "English, Français" => "en",
        "En" => "en",
        "en" => "en",
        "es" => "es",
        "Español" => "es",
        "Spanish" => "es");
    $path = __DIR__ . "/clasified/";
    $path_target = __DIR__ . "/lang_fix/";
    $dir = opendir($path);
    while ($item = readdir($dir)) {
        if ($item != "." && $item != ".." && !is_dir($path . $item)) {
            $info0 = file_get_contents($path . $item);
            //echo "\nFIle".$item;
            //var_dump($info0);
            $info = json_decode($info0);
            if (isset($info->language) AND array_key_exists($info->language, $langs)) {
                file_put_contents($path_target . $langs[$info->language] . "/" . $item, json_encode($info, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            } elseif (isset($info->language)) {
                echo "\nLanguage not mapped: " . $info->language;
            }
        }
    }


}


?>
