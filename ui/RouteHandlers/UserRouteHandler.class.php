<?php

require_once 'Common/models/Register.php';
require_once 'Common/models/Login.php';
require_once 'Common/models/PasswordResetRequest.php';
require_once 'Common/models/PasswordReset.php';

class UserRouteHandler
{
    public function init()
    {
        $app = Slim::getInstance();
        $middleware = new Middleware();

        $app->get('/', array($this, 'home'))->name('home');

        $app->get('/register', array($this, 'register')
        )->via('GET', 'POST')->name('register');

        $app->get('/:uid/password/reset', array($this, 'passwordReset')
        )->via('POST')->name('password-reset');

        $app->get('/password/reset', array($this, 'passResetRequest')
        )->via('POST')->name('password-reset-request');
        
        $app->get('/logout', array($this, 'logout'))->name('logout');
        
        $app->get('/login', array($this, 'login')
        )->via('GET', 'POST')->name('login');

        $app->get('/profile/:user_id', array($this, 'userPublicProfile')
        )->via('POST')->name('user-public-profile');

        $app->get('/profile', array($middleware, 'authUserIsLoggedIn'), 
        array($this, 'userPrivateProfile'))->via('POST')->name('user-private-profile');
    }

    public function home()
    {
        $app = Slim::getInstance();
        $client = new APIClient();        
        
        $use_statistics = Settings::get('site.stats'); 
        
        if ($use_statistics == 'y') {
            $request = APIClient::API_VERSION."/stats/totalUsers";
            $total_users = $client->call($request, HTTP_Request2::METHOD_GET);      
            
            $request = APIClient::API_VERSION."/stats/totalOrgs";
            $total_orgs = $client->call($request, HTTP_Request2::METHOD_GET);
            

            $request = APIClient::API_VERSION."/stats/totalArchivedTasks";
            $total_archived_tasks = $client->call($request, HTTP_Request2::METHOD_GET); 
            
            $request = APIClient::API_VERSION."/stats/totalClaimedTasks";
            $total_claimed_tasks = $client->call($request, HTTP_Request2::METHOD_GET); 
            
            $request = APIClient::API_VERSION."/stats/totalUnclaimedTasks";
            $total_unclaimed_tasks = $client->call($request, HTTP_Request2::METHOD_GET); 
            
            $request = APIClient::API_VERSION."/stats/totalTasks";
            $total_tasks = $client->call($request, HTTP_Request2::METHOD_GET);  

            $app->view()->appendData(array(
                        'total_users' => $total_users
                        ,'total_orgs' => $total_orgs
                        ,'stats' => $use_statistics
                        ,'total_archived_tasks' => $total_archived_tasks
                        ,'total_claimed_tasks' => $total_claimed_tasks
                        ,'total_unclaimed_tasks' => $total_unclaimed_tasks
                        ,'total_tasks' => $total_tasks
            ));
        }
        
        $request = APIClient::API_VERSION."/tags/topTags";
        $response = $client->call($request, HTTP_Request2::METHOD_GET, null,
                                    array('limit' => 10));        
        $top_tags = array();
        if ($response) {
            foreach ($response as $stdObject) {
                $top_tags[] = $client->cast('Tag', $stdObject);
            }
        }        

        $app->view()->appendData(array(
            'top_tags' => $top_tags,
            'current_page' => 'home',
        ));

        $current_user_id = UserSession::getCurrentUserID();
        
        if ($current_user_id == null) {
            $tasks = $client->castCall(array("Task"), APIClient::API_VERSION."/tasks/top_tasks",
                    HTTP_Request2::METHOD_GET, null, array('limit' => 10));
            if ($tasks) {
                $app->view()->appendData(array(
                    'tasks' => $tasks
                ));
            }
        } else {
            $url = APIClient::API_VERSION."/users/$current_user_id/top_tasks";
            $response = $client->call($url, HTTP_Request2::METHOD_GET, null,
                                    array('limit' => 10));
            
            $tasks = array();
            if ($response) {
                foreach ($response as $stdObject) {
                    $tasks[] = $client->cast('Task', $stdObject);
                }
            }

            if ($tasks) {
                $app->view()->setData('tasks', $tasks);
            }

            $url = APIClient::API_VERSION."/users/$current_user_id/tags";
            $response = $client->call($url, HTTP_Request2::METHOD_GET, null,
                                    array('limit' => 10));
            
            $user_tags = array();
            if ($response) {
                foreach ($response as $stdObject) {
                    $user_tags[] = $client->cast('Tag', $stdObject);
                }
            }
            
            $app->view()->appendData(array(
                        'user_tags' => $user_tags
            ));
        }
        
        $numTaskTypes = Settings::get("ui.task_types");
        $taskTypeColours = array();
        
        for($i=1; $i <= $numTaskTypes; $i++) {
            $taskTypeColours[$i] = Settings::get("ui.task_{$i}_colour");
        }  
        
        $app->view()->appendData(array(
                     'taskTypeColours' => $taskTypeColours
        ));
        
        $app->render('index.tpl');
    }

    public function register()
    {
        $app = Slim::getInstance();
        $client = new APIClient();
        
        $use_openid = Settings::get("site.openid");
        $app->view()->setData('openid', $use_openid);
        if (isset($use_openid)) {
            if ($use_openid == 'y' || $use_openid == 'h') {
                $extra_scripts = "
                    <script type=\"text/javascript\" src=\"".$app->urlFor("home").
                        "ui/js/jquery-1.9.0.min.js\"></script>
                    <script type=\"text/javascript\" src=\"".$app->urlFor("home").
                        "ui/js/openid-jquery.js\"></script>
                    <script type=\"text/javascript\" src=\"".$app->urlFor("home").
                        "ui/js/openid-en.js\"></script>
                    <link type=\"text/css\" rel=\"stylesheet\" media=\"all\" href=\"".
                        $app->urlFor("home")."resources/css/openid.css\" />";
                $app->view()->appendData(array('extra_scripts' => $extra_scripts));
            }   
        }   
        $error = null;
        $warning = null;
        if (isValidPost($app)) {
            $post = (object) $app->request()->post();
            
            if (!TemplateHelper::isValidEmail($post->email)) {
                $error = 'The email address you entered was not valid. Please cheak for typos and try again.';
            } elseif (!TemplateHelper::isValidPassword($post->password)) {
                $error = 'You didn\'t enter a password. Please try again.';
            }
            
            if (is_null($error)) {

                $registerData = array();
                $registerData['email'] = $post->email;
                $registerData['password'] = $post->password;
                $register =  ModelFactory::buildModel("Register", $registerData);

                $request = APIClient::API_VERSION."/register";
                $response = $client->call($request, HTTP_Request2::METHOD_POST, $register);

                if ($response) {
                
                    $loginData = array();
                    $loginData['email'] = $post->email;
                    $loginData['password'] = $post->password;
                    $login = ModelFactory::buildModel("Login", $loginData);

                    $request = APIClient::API_VERSION."/login";             
                    $user = $client->call($request, HTTP_Request2::METHOD_POST, $login);

                    try {                        
                        if (!is_array($user) && !is_null($user)) {
                            $user = $client->cast("User", $user);
                            UserSession::setSession($user->getUserId());
                        } else {
                            throw new InvalidArgumentException('Sorry, the  password or username entered is incorrect.
                                                                Please check the credentials used and try again.');    
                        }                    
                                       
                        
                        if (isset($_SESSION['previous_page'])) {
                            if (isset($_SESSION['old_page_vars'])) {
                                $app->redirect($app->urlFor($_SESSION['previous_page'], $_SESSION['old_page_vars']));
                            } else {
                                $app->redirect($app->urlFor($_SESSION['previous_page']));
                            }
                        }
                        $app->redirect($app->urlFor('user-public-profile', array('user_id' => $user->getUserId())));
                    } catch (InvalidArgumentException $e) {
                        $error = '<p>Unable to log in. Please check your email and password.';
                        $error .= ' <a href="' . $app->urlFor('login') . '">Try logging in again</a>';
                        $error .= ' or <a href="'.$app->urlFor('register').'">register</a> for an account.</p>';
                        $error .= '<p>System error: <em>' . $e->getMessage() .'</em></p>';

                        $app->flash('error', $error);
                        $app->redirect($app->urlFor('login'));
                        echo $error;                                        
                    }
                } else {
                    $warning = 'You have already created an account.
                        <a href="' . $app->urlFor('login') . '">Please log in.</a>';
                }
            }
        }
        if ($error !== null) {
            $app->view()->appendData(array('error' => $error));
        }
        if ($warning !== null) {
            $app->view()->appendData(array('warning' => $warning));
        }
        $app->render('register.tpl');
    }

    public function passwordReset($uid)
    {
        $app = Slim::getInstance();
        $client = new APIClient();
        
        $request = APIClient::API_VERSION."/password_reset/$uid";
        $response = $client->call($request);
        if (is_object($response)) {
            $reset_request = $client->cast('PasswordResetRequest', $response);
        } else {
            $app->flash('error', "Incorrect Unique ID. Are you sure you copied the URL correctly?");
            $app->redirect($app->urlFor('home'));
        }
        
        $user_id = $reset_request->getUserId();
        $app->view()->setData("uid", $uid);
        if ($app->request()->isPost()) {
            $post = (object) $app->request()->post();

            if (isset($post->new_password) && TemplateHelper::isValidPassword($post->new_password)) {
                if (isset($post->confirmation_password) && 
                        $post->confirmation_password == $post->new_password) {

                    $data = array();
                    $data['password'] = $post->new_password;
                    $data['key'] = $uid;

                    $request = APIClient::API_VERSION."/password_reset";
                    $response = $client->call($request, HTTP_Request2::METHOD_POST, 
                            ModelFactory::buildModel("PasswordReset", $data));
                    
                    if ($response) {
                    
                        $app->flash('success', "You have successfully changed your password");
                        $app->redirect($app->urlFor('home'));
                    } else {
                        $app->flashNow('error', "Unable to change Password");
                    }
                } else {
                    $app->flashNow('error', "The passwords entered do not match.
                                        Please try again.");
                }
            } else {
                $app->flashNow('error', "Please check the password provided, and try again.
                                It was not found to be valid.");
            }
        }        
        $app->render('password-reset.tpl');
    }

    public function passResetRequest()
    {
        $app = Slim::getInstance();
        $client = new APIClient();
        
        if ($app->request()->isPost()) {
            $post = (object) $app->request()->post();
            if (isset($post->password_reset)) {
                if (isset($post->email_address) && $post->email_address != '') {
                    $request = APIClient::API_VERSION."/users/getByEmail/{$post->email_address}";
                    $response = $client->call($request, HTTP_Request2::METHOD_GET);
                    $user = $client->cast('User', $response); 
                    
                    if ($user) {  
                        $request = APIClient::API_VERSION."/users/{$user->getUserId()}/passwordResetRequest";
                        $hasUserRequestedPwReset = $client->call($request, HTTP_Request2::METHOD_GET);

                        $message = "";
                        if (!$hasUserRequestedPwReset) {
                            //send request
                            $request = APIClient::API_VERSION."/users/{$user->getUserId()}/passwordResetRequest";
                            $client->call($request, HTTP_Request2::METHOD_POST);
                            $app->flash('success', "Password reset request sent. Check your email
                                                    for further instructions.");
                            $app->redirect($app->urlFor('home'));
                        } else {
                            //get request time
                            $request = APIClient::API_VERSION."/users/{$user->getUserId()}/passwordResetRequest/time";
                            $response = $client->call($request, HTTP_Request2::METHOD_GET);
                            $app->flashNow('info', "Password reset request was already sent on $response.
                                                     Another email has been sent to your contact address.
                                                     Follow the link in this email to reset your password");
                            //Send request
                            $request = APIClient::API_VERSION."/users/{$user->getUserId()}/passwordResetRequest";
                            $client->call($request, HTTP_Request2::METHOD_POST);
                        }
                    } else {
                        $app->flashNow("error", "Please enter a valid email address");
                    }
                } else {
                    $app->flashNow("error", "Please enter a valid email address");
                }
            }
        }
        $app->render('user.reset-password.tpl');
    }
    
    public function logout()
    {
        $app = Slim::getInstance();
        UserSession::destroySession();    //TODO revisit when oauth is in place
        $app->redirect($app->urlFor('home'));
    }

    public function login()
    {
        $app = Slim::getInstance();
        $client = new APIClient();
        
        $error = null;
        $openid = new LightOpenID(Settings::get("site.url"));
        $use_openid = Settings::get("site.openid");
        $app->view()->setData('openid', $use_openid);
        if (isset($use_openid)) {
            if ($use_openid == 'y' || $use_openid == 'h') {
                $extra_scripts = "
                    <script type=\"text/javascript\" src=\"".$app->urlFor("home").
                        "ui/js/jquery-1.9.0.min.js\"></script>
                    <script type=\"text/javascript\" src=\"".$app->urlFor("home").
                        "ui/js/openid-jquery.js\"></script>
                    <script type=\"text/javascript\" src=\"".$app->urlFor("home").
                        "ui/js/openid-en.js\"></script>
                    <link type=\"text/css\" rel=\"stylesheet\" media=\"all\" href=\"".
                        $app->urlFor("home")."resources/css/openid.css\" />";
                $app->view()->appendData(array('extra_scripts' => $extra_scripts));
            }
        }
        
        try {
            if (isValidPost($app)) {
                $post = (object) $app->request()->post();

                if (isset($post->login)) {

                    $loginData = array();
                    $loginData['email'] = $post->email;
                    $loginData['password'] = $post->password;
                    $login = ModelFactory::buildModel("Login", $loginData);

                    $request = APIClient::API_VERSION."/login";             
                    $user = $client->call($request, HTTP_Request2::METHOD_POST, $login);
                    if (!is_array($user) && !is_null($user)) {
                        $user = $client->cast("User", $user);
                        UserSession::setSession($user->getUserId());
                    } else {
                        throw new InvalidArgumentException('Sorry, the username or password entered is incorrect.
                            Please check the credentials used and try again.');    
                    }
                    
                    $app->redirect($app->urlFor("home"));
                } elseif (isset($post->password_reset)) {
                    $app->redirect($app->urlFor('password-reset-request'));
                }
            } elseif ($app->request()->isPost() || $openid->mode) {
                if($this->openIdLogin($openid, $app)){
                   $app->redirect($app->urlFor("home"));
                }  else {
                    $app->redirect($app->urlFor('user-public-profile', array('user_id' => UserSession::getCurrentUserID())));
                }
            }
            $app->render('login.tpl');
        } catch (InvalidArgumentException $e) {
            $error = '<p>Unable to log in. Please check your email and password.';
            $error .= ' <a href="' . $app->urlFor('login') . '">Try logging in again</a>';
            $error .= ' or <a href="'.$app->urlFor('register').'">register</a> for an account.</p>';
            $error .= '<p>System error: <em>' . $e->getMessage() .'</em></p>';
            
            $app->flash('error', $error);
            $app->redirect($app->urlFor('login'));
            echo $error;
        }
    }
    
    public function openIdLogin($openid, $app)
    {       
        if (!$openid->mode) {
            try {
                $openid->identity = $openid->data['openid_identifier'];
                $openid->required = array('contact/email');
                $url = $openid->authUrl();
                $app->redirect($openid->authUrl());
            } catch (ErrorException $e) {
                echo $e->getMessage();
            }
        } elseif ($openid->mode == 'cancel') {
            throw new InvalidArgumentException('User has canceled authentication!');
        } else {
            $retvals= $openid->getAttributes();
            if ($openid->validate()) {
                $client = new APIClient();
                $request = APIClient::API_VERSION."/users/getByEmail/{$retvals['contact/email']}";
                $response = $client->call($request);
                if (is_null($response)) {
                    $registerData = array();
                    $registerData['email'] = $retvals['contact/email'];
                    $registerData['password'] = md5($retvals['contact/email']);

                    $request = APIClient::API_VERSION."/register";
                    $response = $client->call($request, HTTP_Request2::METHOD_POST, 
                    ModelFactory::buildModel("Register", $registerData));
                    $user = $client->cast("User", $response);
                    UserSession::setSession($user->getUserId());
                    return false;
                }
                $user = $client->cast("User", $response);
                UserSession::setSession($user->getUserId());
                
            }
            return true;
        }
    }        

    public static function userPrivateProfile()
    {
        $app = Slim::getInstance();
        $client = new APIClient();
        $user_id = UserSession::getCurrentUserID();
        
        $url = APIClient::API_VERSION."/users/$user_id";
        $response = $client->call($url);
        $user = $client->cast('User', $response);
        $languages = TemplateHelper::getLanguageList();      //wait for API support
        $countries = TemplateHelper::getCountryList();       //wait for API support
        
        if (!is_object($user)) {
            $app->flash('error', 'Login required to access page');
            $app->redirect($app->urlFor('login'));
        }
        
        if ($app->request()->isPost()) {
            $displayName = $app->request()->post('name');
            if ($displayName != null) {
                $user->setDisplayName($displayName);
            }
            
            $userBio = $app->request()->post('bio');
            if ($userBio != null) {
                $user->setBiography($userBio);
            }
            
            $nativeLang = $app->request()->post('nLanguage');
            $langCountry= $app->request()->post('nLanguageCountry');
            if ($nativeLang != null && $langCountry != null) {
                $user->setNativeLangId($nativeLang);
                $user->setNativeRegionId($langCountry);

                $badge_id = BadgeTypes::NATIVE_LANGUAGE;
                $url = APIClient::API_VERSION."/badges/$badge_id";
                $response = $client->call($url);
                $badge = $client->cast('Badge', $response);
                
                $request = APIClient::API_VERSION."/users/$user_id/badges";
                $client->call($request, HTTP_Request2::METHOD_POST, $badge);               

            }
            
            $request = APIClient::API_VERSION."/users/$user_id";
            $client->call($request, HTTP_Request2::METHOD_PUT, $user);
            
            if ($user->getDisplayName() != '' && $user->getBiography() != ''
                    && $user->getNativeLangId() != '' && $user->getNativeRegionId() != '') {

                $badge_id = BadgeTypes::PROFILE_FILLER;
                $url = APIClient::API_VERSION."/badges/$badge_id";
                $response = $client->call($url);
                $badge = $client->cast('Badge', $response);
                
                $request = APIClient::API_VERSION."/users/$user_id/badges";
                $response = $client->call($request, HTTP_Request2::METHOD_POST, $badge); 
            
            }
            
            $app->redirect($app->urlFor('user-public-profile', array('user_id' => $user->getUserId())));
        }
        
        $app->view()->setData('languages', $languages);
        $app->view()->setData('countries', $countries);
        
       
        $app->render('user-private-profile.tpl');
    }

    public static function userPublicProfile($user_id)
    {
        $app = Slim::getInstance();
        $client = new APIClient();

        $url = APIClient::API_VERSION."/users/$user_id";
        $response = $client->call($url);
        $user = $client->cast('User', $response);
        
        if ($app->request()->isPost()) {
            $post = (object) $app->request()->post();
            
            if (isset($post->revokeBadge) && isset($post->badge_id) && $post->badge_id != ''){
                $badge_id = $post->badge_id;
                $request = APIClient::API_VERSION."/users/$user_id/badges/$badge_id";
                $response = $client->call($request, HTTP_Request2::METHOD_DELETE);                 
            }
                
            if (isset($post->revoke)) {
                $org_id = $post->org_id;
                $request = APIClient::API_VERSION."/users/leaveOrg/$user_id/$org_id";
                $response = $client->call($request, HTTP_Request2::METHOD_DELETE); 
            } 
        }
                    
        $activeJobs = array();        
        $request = APIClient::API_VERSION."/users/$user_id/tasks";
        $response = $client->call($request);
        
        if ($response) {
            foreach ($response as $stdObject) {
                $activeJobs[] = $client->cast('Task', $stdObject);
            }
        }

        $archivedJobs = array();
        $request = APIClient::API_VERSION."/users/$user_id/archived_tasks";
        $response = $client->call($request, HTTP_Request2::METHOD_GET, null, array('limit' => 10 )); 
        
        if ($response) {
            foreach ($response as $stdObject) {
                $archivedJobs[] = $client->cast('Task', $stdObject);
            }
        }        
         
        $user_tags = array();
        $request = APIClient::API_VERSION."/users/$user_id/tags";
        $response = $client->call($request);
        
        if ($response) {
            foreach ($response as $stdObject) {
                $user_tags[] = $client->cast('Tag', $stdObject);
            }
        }            
        
        $request = APIClient::API_VERSION."/users/$user_id/orgs";
        $orgs = $client->call($request);

        $user_orgs = array();
        if ($orgs) {
            foreach ($orgs as $org) {
                $user_orgs[] = $client->cast('Organisation', $org);
            }
        }
        
        $badges = array();
        $orgList = array();
        $request = APIClient::API_VERSION."/users/$user_id/badges";
        $response = $client->call($request);
        foreach ($response as $stdObject) {
            $badge = $client->cast('Badge', $stdObject);
            $badges[] = $badge;
            if ($badge->getOwnerId() != null) {
                $mRequest = APIClient::API_VERSION."/orgs/".$badge->getOwnerId();
                $mResponse = $client->call($mRequest);
                $org = $client->cast('Organisation', $mResponse);
                $orgList[$badge->getOwnerId()] = $org;
            }
        }       
       
        $org_creation = Settings::get("site.organisation_creation");
            
        $extra_scripts = "<script type=\"text/javascript\" src=\"".$app->urlFor("home");
        $extra_scripts .= "resources/bootstrap/js/confirm-remove-badge.js\"></script>";

        $app->view()->setData('orgList', $orgList);
        $app->view()->appendData(array('badges' => $badges,
                                    'user_orgs' => $user_orgs,
                                    'current_page' => 'user-profile',
                                    'activeJobs' => $activeJobs,
                                    'archivedJobs' => $archivedJobs,
                                    'user_tags' => $user_tags,
                                    'this_user' => $user,
                                    'extra_scripts' => $extra_scripts,
                                    'org_creation' => $org_creation
        ));
                
        if (UserSession::getCurrentUserID() === $user_id) {
            $app->view()->appendData(array('private_access' => true));
        }
                    
        $app->render('user-public-profile.tpl');
    }
    

    public static function isLoggedIn()
    {
        return (!is_null(UserSession::getCurrentUserId()));
    }     
}
