<?php

class CacheHelper
{
    const STATISTICS    = "Statistics";
    const LANGUAGES     = "Languages";
    const COUNTRIES     = "Countries";
    const TOP_TAGS      = "TopTags";
    
    public static function getCached($key, $ttl, $function, $args=null)
    {
        $ret= null;
        if(!apc_exists($key)) {
            $ret = is_null($args) ? call_user_func($function) : call_user_func($function, $args);
            apc_add($key, $ret, $ttl);            
        } else {
            $ret= apc_fetch($key);
        }
        
        return $ret;
    } 
}