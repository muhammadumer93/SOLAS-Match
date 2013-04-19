<?php

require_once __DIR__."/../../Common/lib/APIHelper.class.php";
require_once __DIR__."/../../Common/lib/CacheHelper.class.php";
require_once __DIR__."/../../Common/TimeToLiveEnum.php";

class LanguageDao
{
    private $client;
    private $siteApi;
    
    public function __construct()
    {
        $this->client = new APIHelper(Settings::get("ui.api_format"));
        $this->siteApi = Settings::get("site.api");
    }

    public function getLanguage($id)
    {
        $request = "{$this->siteApi}v0/languages/$id";
        return $this->client->call("Language", $request);
    }
    
    public function getLanguages()
    {
        $languages = CacheHelper::getCached(CacheHelper::LANGUAGES, TimeToLiveEnum::MONTH, 
                function($client){
                    $request = "{$this->siteApi}v0/languages";
                    return $client->call(array("Language"), $request);
                },
            $this->client);
        return $languages;
    }
    
    public function getLanguageByCode($code)
    {
        $request = "{$this->siteApi}v0/languages/getByCode/$code";
        return $this->client->call("Language", $request);
    }
}
