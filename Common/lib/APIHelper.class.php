<?php

require_once __DIR__."/FormatEnum.php";
require_once __DIR__."/JSONSerializer.class.php";
require_once __DIR__."/XMLSerializer.class.php";
require_once __DIR__."/HTMLSerializer.class.php";
require_once __DIR__."/PHPSerializer.class.php";
require_once __DIR__."/ProtobufSerializer.class.php";

class APIHelper
{
    private $_serializer;
    private $responseCode;

    public function __construct($format)
    {
        $format = self::getFormatFromString($format);

        switch ($format)
        {
            default:
            case FormatEnum::JSON:
                $this->_serializer = new JSONSerializer();
                break;
            case FormatEnum::XML:
                $this->_serializer = new XMLSerializer();
                break;
            case FormatEnum::HTML:
                $this->_serializer = new HTMLSerializer();
                break;
            case FormatEnum::PHP:
                $this->_serializer = new PHPSerializer();
                break;
            case FormatEnum::PROTOBUFS:
                $this->_serializer = new ProtobufSerializer();
                break;
        }
    }

    public function call($destination,$url, $method = HttpMethodEnum::GET,
             $data = null, $query_args = array(), $file = null)
    {
        $url = $url.$this->_serializer->getFormat()."/?";
        if (count($query_args) > 0) {
            $first= true;
            foreach ($query_args as $key=>$val){
                if(!$first) $url.="&";                    
                else $first=FALSE;
                $url.="$key=$val";
            }
        }
        $re = curl_init($url);
        curl_setopt($re, CURLOPT_CUSTOMREQUEST, $method);
        $lenght = 0;
        if (!is_null($data) && "null" != $data) {
            $data=$this->_serializer->serialize($data);
            curl_setopt($re, CURLOPT_POSTFIELDS, $data);
            $lenght=strlen($data);
        }
        
        if (!is_null($file)) {
            $lenght=strlen($file);
            curl_setopt($re, CURLOPT_POSTFIELDS, $file);
        }

        if(isset($_COOKIE['slim_session'])) curl_setopt($re, CURLOPT_COOKIE, "slim_session=".$_COOKIE['slim_session'].";");        
        
        $httpHeaders = array(
                $this->_serializer->getContentType(),                                                                                
                'Content-Length:'.$lenght,
                'X-Custom-Authorization:'.UserSession::getHash());       

        curl_setopt($re, CURLOPT_HTTPHEADER, $httpHeaders);
        curl_setopt($re, CURLOPT_RETURNTRANSFER, true); 
        $res=curl_exec($re);
        $success = array(200,201,202,203,204);
        $this->responseCode = curl_getinfo($re, CURLINFO_HTTP_CODE);
        
        curl_close($re);
        
        if(in_array($this->responseCode, $success)){
            $response_data = $this->_serializer->deserialize($res,$destination);
        }else throw new SolasMatchException($res, $this->responseCode);
        

        return $response_data;
    }

    public function cast($destination, $sourceObject)
    {
        $ret = null;
        if (is_array($destination)) {
            if ($sourceObject) {
                foreach ($sourceObject as $row) {
                    $ret[] = $this->_serializer->cast($destination[0], $row);
                }
            }
        } elseif (is_array($sourceObject)) {
            $ret = $this->_serializer->cast($destination, $sourceObject[0]);
        } else { 
            $ret = $this->_serializer->cast($destination, $sourceObject);
        }

        return $ret;
    }

    public function serialize($data)
    {
        return $this->_serializer->serialize($data);
    }

    public function deserialize($data,$type)
    {
        return $this->_serializer->deserialize($data,$type);
    }

    public static function getFormatFromString($format)
    {
        if ($format == ".json") {
            $format = FormatEnum::JSON;
        } elseif (strcasecmp($format, '.xml') == 0) {
            $format = FormatEnum::XML;
        } elseif (strcasecmp($format, '.php') == 0) {
            $format = FormatEnum::PHP;
        } elseif (strcasecmp($format, '.html') == 0) {
            $format = FormatEnum::HTML;
        } elseif (strcasecmp($format, '.proto') == 0) {
            $format = FormatEnum::PROTOBUFS;//change when implmented.
        } else {
            $format = FormatEnum::JSON;
        }
        return $format;
    }

    public static function parseFilterString($filter)
    {
        $ret = array();
        $pairs = explode(";", $filter);
        foreach ($pairs as $pair) {
            if ($pair != '') {
                $keyValue = explode(":", $pair);
                $ret[$keyValue[0]] = $keyValue[1];
            }
        }
        return $ret;
    }

    public function getContentType()
    {
        $ret = null;
        if($this->_serializer) {
            $ret = $this->_serializer->getContentType();
        }
        return $ret;
    }
    
    public function getResponseCode()
    {
        return $this->responseCode;
    }
    
    
    // http://stackoverflow.com/a/1147952
    private function system_extension_mime_types() {
        # Returns the system MIME type mapping of extensions to MIME types, as defined in /etc/mime.types.
        $out = array();
        $file = fopen('/etc/mime.types', 'r');
        while(($line = fgets($file)) !== false) {
            $line = trim(preg_replace('/#.*/', '', $line));
            if(!$line)
                continue;
            $parts = preg_split('/\s+/', $line);
            if(count($parts) == 1)
                continue;
            $type = array_shift($parts);
            foreach($parts as $part)
                $out[$part] = $type;
        }
        fclose($file);
        return $out;
    }

    private function getMimeTypeFromSystem($ext) {
   
        static $types;
        if(!isset($types))
            $types = $this->system_extension_mime_types();
  
        return isset($types[$ext]) ? $types[$ext] : null;
    }
    
    public function getCanonicalMime($filename)
    {
        $mimeMap = array(
             "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            ,"xltx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.template"
            ,"potx" => "application/vnd.openxmlformats-officedocument.presentationml.template"
            ,"ppsx" => "application/vnd.openxmlformats-officedocument.presentationml.slideshow"
            ,"pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation"
            ,"sldx" => "application/vnd.openxmlformats-officedocument.presentationml.slide"
            ,"docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
            ,"dotx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.template"
            ,"xlam" => "application/vnd.ms-excel.addin.macroEnabled.12"
            ,"xlsb" => "application/vnd.ms-excel.sheet.binary.macroEnabled.12"
            ,"xlf"  => "application/xliff+xml"
        );         
        
        $extension = explode(".", $filename);
        $extension =  strtolower($extension[count($extension)-1]);

        return array_key_exists($extension, $mimeMap)? $mimeMap[$extension] : $this->getMimeTypeFromSystem($extension);
    }
}
