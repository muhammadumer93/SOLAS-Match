<?php

/**
 * Description of Login
 *
 * @author sean
 */

require_once __DIR__.'/../DataAccessObjects/UserDao.class.php';

class LoginAPI {
    
    public  $email;
    public  $pass;
    
    public function __construct()
    {
         $this->pass="";
         $this->email="";
    }
    
    public static function init()
    {
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/login(:format)/', function ($format = ".json") {
            $data = new Login();
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getLoginTemplate');
        
        
        
        Dispatcher::registerNamed(HttpMethodEnum::POST, '/v0/login(:format)/', function ($format = ".json") {
            $data = Dispatcher::getDispatcher()->request()->getBody();
            $client = new APIHelper($format);
            $data = $client->deserialize($data,"Login");
            
            $data = UserDao::apiLogin($data->getEmail(), $data->getPassword());
            Dispatcher::sendResponce(null, $data, null, $format);
         }, 'login');
    }
}
LoginAPI::init();
