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
    /*
     * @var array $elements Contains the structured information of the file
     */
    private static $elements = array();
    /*
     * @var array $keys An associative array of key=>value
     */
    private static $keys = array();
    /*
     * @var string $location Where the .lsd file is
     */
    private static $location = null;
    /*
     * How long did it take to read/parse the .lsd file the last time
     */
    private static $lastReadTime = null;
    /*
     * How long did it take to compose/write the .lsd file the last time
     */
    private static $lastWriteTime = null;

    /**
     * Loads the .lsd file and invokes parse method
     * @param string $settingsFile Where the file is located
     * @throws Exception
     */
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

    /**
     * Saves the .lsd file
     */
    public static function save(){
        $startTime = (microtime(true) * 1000);
        $outContent = self::composeFile();
        file_put_contents(self::$location, $outContent);
        self::$lastWriteTime = round((microtime(true) * 1000) - $startTime);
    }

    /**
     * Returns the value of the specified key
     * @param string $key Name of the key we want the value of
     * @return mixed|null
     */
    public static function get($key){
        $value = null;
        if(isset(self::$keys[$key])){
            $value = self::$keys[$key];
        }
        return $value;
    }

    /**
     * Sets the value of the specified key. It is not stored in the file until save method is invoked
     * @param string $key
     * @param string $value
     */
    public static function put($key, $value){
        self::$keys[$key] = $value;
    }

    /**
     * Returns how long took the last read/parse operation in milliseconds
     * @return int
     */
    public static function getLastReadTime(){
        return self::$lastReadTime;
    }

    /**
     * Returns how long took the last compose/write operation in milliseconds
     * @return null
     */
    public static function getLastWriteTime(){
        return self::$lastWriteTime;
    }

    /**
     * Recreates the .lsd file from the structured data
     * @return string The content of the file to be written
     */
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

    /**
     * Parses the .lsd file and stores structured information in arrays
     * @param $fileContent The content of the .lsd file
     */
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