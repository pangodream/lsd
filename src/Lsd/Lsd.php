<?php
/**
 * Created by Pangodream.
 * User: Development
 * Date: 05/11/2018
 * Time: 16:15
 */

namespace Lsd;

use \Exception;

class Lsd
{
    private static $elements = array();
    private static $keys = array();
    private static $location = null;
    private static $lastReadTime = null;
    private static $lastWriteTime = null;
    public static function load($settingsFile = '.lsd'){
        $startTime = (microtime(true) * 1000);
        self::$location = $settingsFile;
        if((file_exists(self::$location))) {
            $content = file_get_contents(self::$location);
            self::parseFile($content);
        }else{
            throw new Exception("LSD: Settings file not found");
        }
        self::$lastReadTime = round((microtime(true) * 1000) - $startTime);
    }
    public static function save(){
        $startTime = (microtime(true) * 1000);
        $outContent = self::composeFile();
        file_put_contents(self::$location, $outContent);
        self::$lastWriteTime = round((microtime(true) * 1000) - $startTime);
    }
    public static function get($key){
        $value = null;
        if(isset(self::$keys[$key])){
            $value = self::$keys[$key];
        }
        return $value;
    }
    public static function put($key, $value){
        self::$keys[$key] = $value;
    }
    public static function getLastReadTime(){
        return self::$lastReadTime;
    }
    public static function getLastWriteTime(){
        return self::$lastWriteTime;
    }
    private function composeFile(){
        $content = '';
        foreach(self::$elements as $element){
            $line = '';
            $line .= $element['key'];
            if(isset(self::$keys[$element['key']])){
                $line .= '='.self::$keys[$element['key']];
            }
            if($line > ''){
                $line .= ' ';
            }
            $line .= $element['comment'];
            $content .= $line."\n";
        }
        return $content;
    }
    private function parseFile($fileContent){
        $startTime = (microtime(true) * 1000);
        $lines = explode("\n", $fileContent);
        foreach($lines as $line){
            //Analize comments
            $commentStart = strpos($line, '#');
            if($commentStart !== false){
                $comment = substr($line, $commentStart);
                $line = substr($line, 0, $commentStart);
            }else{
                $comment = false;
            }
            //Analize line content
            $equalStart = strpos($line, '=');
            if($equalStart !== false){
                $key = trim(substr($line, 0, $equalStart));
                $value = trim(substr($line, $equalStart+1));
            }else{
                $key = trim($line);
                $value = '';
            }
            self::$elements[] = array('key' => $key, 'value' => $value, 'comment' => $comment);
            if($key > ''){
                self::$keys[$key] = $value;
            }
        }
    }
}