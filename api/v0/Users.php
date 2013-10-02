<?php

/**
 * Description of Users
 *
 * @author sean
 */

require_once __DIR__."/../DataAccessObjects/UserDao.class.php";
require_once __DIR__."/../DataAccessObjects/TaskDao.class.php";
require_once __DIR__."/../lib/Notify.class.php";
require_once __DIR__."/../lib/NotificationTypes.class.php";
require_once __DIR__."/../lib/Middleware.php";


class Users {
    
    public static function init()
    {
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users(:format)/',
                                                        function ($format = ".json") {
            
            Dispatcher::sendResponce(null, "display all users", null, $format);
        }, 'getUsers');
                    
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/',
                                                        function ($id, $format = ".json") {
            
            if (!is_numeric($id) && strstr($id, '.')) {
                $id = explode('.', $id);
                $format = '.'.$id[1];
                $id = $id[0];
            }
            $data = UserDao::getUser($id);
            if (is_array($data)) {
                $data = $data[0];
            }
            if(!is_null($data)){
                $data->setPassword(null);
                $data->setNonce(null);
            }


           
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getUser',  "Middleware::authUserOwnsResource");
        
        Dispatcher::registerNamed(HttpMethodEnum::DELETE, '/v0/users/:id/',
                                                        function ($id, $format = ".json") {
            
            if (!is_numeric($id) && strstr($id, '.')) {
                $id = explode('.', $id);
                $format = '.'.$id[1];
                $id = $id[0];
            }

            UserDao::deleteUser($id);           
            Dispatcher::sendResponce(null, null, null, $format);
        }, 'deleteUser');

        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:uuid/registered(:format)/',
                function ($uuid, $format = '.json')
                {
                    $user = UserDao::getRegisteredUser($uuid);
                    Dispatcher::sendResponce(null, $user, null, $format);
                }, 'getRegisteredUser', null);

        Dispatcher::registerNamed(HttpMethodEnum::POST, '/v0/users/:uuid/finishRegistration(:format)/',
                function ($uuid, $format = '.json')
                {
                    $user = UserDao::getRegisteredUser($uuid);
                    if ($user != null) {
                        $ret = UserDao::finishRegistration($user->getId());
                        $server = Dispatcher::getOauthServer();
                        $response = $server->getGrantType('password')->completeFlow(array("client_id"=>$user->getId(),"client_secret"=>$user->getPassword()));
                        
                        $oAuthResponse = new OAuthResponce();
                        $oAuthResponse->setToken($response['access_token']);
                        $oAuthResponse->setTokenType($response['token_type']);
                        $oAuthResponse->setExpires($response['expires']);
                        $oAuthResponse->setExpiresIn($response['expires_in']);
                        
                        Dispatcher::sendResponce(null, $ret, null, $format, $oAuthResponse);
                    } else {
                        Dispatcher::sendResponce(null, "Invalid UUID", HttpStatusEnum::UNAUTHORIZED, $format);
                    }
                }, 'finishRegistration', null);
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:userId/verified(:format)/',
                function ($userId, $format = '.json')
                {
                    $ret = UserDao::isUserVerified($userId);
                    Dispatcher::sendResponce(null, $ret, null, $format);
                }, 'isUserVerified', null);
        
        Dispatcher::registerNamed(HttpMethodEnum::DELETE, '/v0/users/leaveOrg/:id/:org/',
                                                           function ($id, $org, $format = ".json") {
            if (!is_numeric($org) && strstr($org, '.')) {
                $org = explode('.', $org);
                $format = '.'.$org[1];
                $org = $org[0];
            }
            $data = OrganisationDao::revokeMembership($org, $id);
            if (is_array($data)) {
                $data = $data[0];
            }
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'userLeaveOrg');        

        Dispatcher::registerNamed(HttpMethodEnum::PUT, '/v0/users/:id/requestReference(:format)/',
                function ($id, $format = ".json")
                {
                    UserDao::requestReference($id);
                    Dispatcher::sendResponce(null, null, null, $format);
                }, "userRequestReference");
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/getByEmail/:email/',
                                                        function ($email, $format = ".json") {
            
            if (!is_numeric($email) && strstr($email, '.')) {
                $temp = array();
                $temp = explode('.', $email);
                $lastIndex = sizeof($temp)-1;
                if ($lastIndex > 1) {
                    $format='.'.$temp[$lastIndex];
                    $email = $temp[0];
                    for ($i = 1; $i < $lastIndex; $i++) {
                        $email = "{$email}.{$temp[$i]}";
                    }
                }
            }
            $data = UserDao::getUser(null, $email);
            if (is_array($data)) {
                $data = $data[0];
            }
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getUserByEmail',  "Middleware::Registervalidation");

        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/getClaimedTasksCount/:userId/',
                function ($userId, $format = '.json')
                {
                    if (!is_numeric($userId) && strstr($userId, '.')) {
                        $userId = explode('.', $userId);
                        $format = '.'.$userId[1];
                        $userId = $userId[0];
                    }
                    $data = TaskDao::getUserTasksCount($userId);
                    Dispatcher::sendResponce(null, $data, null, $format);
                }, 'getUserClaimedTasksCount');

       
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/subscribedToTask/:id/:taskID/',
                                                        function ($id, $taskID, $format = ".json") {

            if (!is_numeric($taskID) && strstr($taskID, '.')) {
                $taskID = explode('.', $taskID);
                $format = '.'.$taskID[1];
                $taskID = $taskID[0];
            }
            Dispatcher::sendResponce(null, UserDao::isSubscribedToTask($id, $taskID), null, $format);
        }, 'userSubscribedToTask');        
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/subscribedToProject/:id/:projectID/',
                                                        function ($id, $projectID, $format = ".json") {

            if (!is_numeric($projectID) && strstr($projectID, '.')) {
                $projectID = explode('.', $projectID);
                $format = '.'.$projectID[1];
                $projectID = $projectID[0];
            }
            Dispatcher::sendResponce(null, UserDao::isSubscribedToProject($id, $projectID), null, $format);
        }, 'userSubscribedToProject');  
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/isBlacklistedForTask/:userId/:taskId/',
                                                        function ($userId, $taskId, $format = ".json") {

            if (!is_numeric($taskId) && strstr($taskId, '.')) {
                $taskId = explode('.', $taskId);
                $format = '.'.$taskId[1];
                $taskId = $taskId[0];
            }
            Dispatcher::sendResponce(null, UserDao::isBlacklistedForTask($userId, $taskId), null, $format);
        }, 'isBlacklistedForTask');  
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/orgs(:format)/',
                                                        function ($id, $format = ".json") {
            Dispatcher::sendResponce(null, UserDao::findOrganisationsUserBelongsTo($id), null, $format);
        }, 'getUserOrgs');
       
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/badges(:format)/',
                                                        function ($id, $format = ".json") {
            Dispatcher::sendResponce(null, UserDao::getUserBadges($id), null, $format);
        }, 'getUserbadges');
        
        Dispatcher::registerNamed(HttpMethodEnum::POST, '/v0/users/:id/badges(:format)/',
                                                        function ($id, $format=".json") {
            
            $data = Dispatcher::getDispatcher()->request()->getBody();
            $client = new APIHelper($format);
            $data = $client->deserialize($data,'Badge');
//            $data = $client->cast('Badge', $data);
            Dispatcher::sendResponce(null, BadgeDao::assignBadge($id, $data->getId()), null, $format);
        }, 'addUserbadges');

        Dispatcher::registerNamed(HttpMethodEnum::PUT, '/v0/users/assignBadge/:email/:badgeId/',
                function ($email, $badgeId, $format = ".json")
                {
                    if (!is_numeric($badgeId) && strstr($badgeId, '.')) {
                        $badgeId = explode('.', $badgeId);
                        $format = '.'.$badgeId[1];
                        $badgeId = $badgeId[0];
                    }
                    $ret = false;
                    $user = UserDao::getUser(null, $email);
                    if (count($user) > 0) {
                        $user = $user[0];
                        $ret = BadgeDao::assignBadge($user->getId(), $badgeId);
                    }
                    Dispatcher::sendResponce(null, $ret, null, $format);
                }, "assignBadge", null);
        
        Dispatcher::registerNamed(HttpMethodEnum::PUT, '/v0/users/:id/badges/:badge/',
                                                        function ($id, $badge, $format = ".json") {
            
            if (!is_numeric($badge) && strstr($badge, '.')) {
                 $badge = explode('.', $badge);
                 $format = '.'.$badge[1];
                 $badge = $badge[0];
            }
            Dispatcher::sendResponce(null, BadgeDao::assignBadge($id, $badge), null, $format);
        }, 'addUserbadgesByID');
        
        Dispatcher::registerNamed(HttpMethodEnum::DELETE, '/v0/users/:id/badges/:badge/',
                                                            function ($id, $badge, $format = ".json") {
            if (!is_numeric($badge) && strstr($badge, '.')) {
                $badge = explode('.', $badge);
                $format = '.'.$badge[1];
                $badge = $badge[0];
            }
            Dispatcher::sendResponce(null, BadgeDao::removeUserBadge($id, $badge), null, $format);
        }, 'deleteUserbadgesByID');
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/tags(:format)/',
                                                        function ($id, $format = ".json") {
            $limit = Dispatcher::clenseArgs('limit', HttpMethodEnum::GET, null);
            Dispatcher::sendResponce(null, UserDao::getUserTags($id, $limit), null, $format);
        }, 'getUsertags');

        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/taskStreamNotification(:format)/',
                function ($id, $format = ".json")
                {
                    $data = UserDao::getUserTaskStreamNotification($id);
                    Dispatcher::sendResponce(null, $data, null, $format);
                }, 'getUserTaskStreamNotification');

        Dispatcher::registerNamed(HttpMethodEnum::DELETE, '/v0/users/:id/taskStreamNotification(:format)/',
                function ($id, $format = ".json")
                {
                    $ret = UserDao::removeTaskStreamNotification($id);
                    Dispatcher::sendResponce(null, $ret, null, $format);
                }, 'removeUserTaskStreamNotification');

        Dispatcher::registerNamed(HttpMethodEnum::PUT, '/v0/users/:id/taskStreamNotification(:format)/',
                function ($id, $format = ".json")
                {
                    $data = Dispatcher::getDispatcher()->request()->getBody();
                    $client = new APIHelper($format);
                    $data = $client->deserialize($data, 'UserTaskStreamNotification');
                    $ret = UserDao::requestTaskStreamNotification($data);
                    Dispatcher::sendResponce(null, $ret, null, $format);
                }, 'updateTaskStreamNotification');
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/tasks(:format)/',
                                                        function ($id, $format = ".json") {
            $limit = Dispatcher::clenseArgs('limit', HttpMethodEnum::GET, 10);
            $offset = Dispatcher::clenseArgs('offset', HttpMethodEnum::GET, 0);
            Dispatcher::sendResponce(null, TaskDao::getUserTasks($id, $limit, $offset), null, $format);
        }, 'getUsertasks');
        
        Dispatcher::registerNamed(HttpMethodEnum::POST, '/v0/users/:id/tasks(:format)/',
                                                        function ($id, $format = ".json") {
            
            $data = Dispatcher::getDispatcher()->request()->getBody();
            $client = new APIHelper($format);
            $data = $client->deserialize($data,'Task');
            Dispatcher::sendResponce(null, TaskDao::claimTask($data->getId(), $id), null, $format);
            Notify::notifyUserClaimedTask($id, $data->getId());
            Notify::notifyOrgClaimedTask($id, $data->getId());
        }, 'userClaimTask');
       
        Dispatcher::registerNamed(HttpMethodEnum::DELETE, '/v0/users/:id/tasks/:tID/',
                                                        function ($id, $tID ,$format = ".json") {
             
            if (!is_numeric($tID) && strstr($tID, '.')) {
                 $tID = explode('.', $tID);
                 $format = '.'.$tID[1];
                 $tID = $tID[0];
            }
            Dispatcher::sendResponce(null, TaskDao::unClaimTask($tID,$id), null, $format);
            Notify::sendTaskRevokedNotifications($tID, $id);
        }, 'userUnClaimTask');

        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:user_id/tasks/:task_id/review(:format)/',
                function ($userId, $taskId, $format = '.json')
                {
                    $reviews = TaskDao::getTaskReviews(null, $taskId, $userId);
                    Dispatcher::sendResponce(null, $reviews[0], null, $format);
                }, 'getUserTaskReview');
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/topTasks(:format)/',
                                                        function ($id, $format = ".json") {
            
            $limit = Dispatcher::clenseArgs('limit', HttpMethodEnum::GET, 5);
            $offset = Dispatcher::clenseArgs('offset', HttpMethodEnum::GET, 0);
            $filter = Dispatcher::clenseArgs('filter', HttpMethodEnum::GET, '');
            $strict = Dispatcher::clenseArgs('strict', HttpMethodEnum::GET, false);
            $filters = APIHelper::parseFilterString($filter);
            $filter = "";
            if (isset($filters['taskType']) && $filters['taskType'] != '') {
                $filter .= " AND t.`task-type_id`=".$filters['taskType'];
            }
            if (isset($filters['sourceLanguage']) && $filters['sourceLanguage'] != '') {
                $filter .= " AND t.`language_id-source`= (SELECT id FROM Languages WHERE code=\'".
                            $filters['sourceLanguage']."\')";
            }
            if (isset($filters['targetLanguage']) && $filters['targetLanguage'] != '') {
                $filter .= " AND t.`language_id-target`= (SELECT id FROM Languages WHERE code=\'".
                            $filters['targetLanguage']."\')";
            }

            $dao = new TaskDao();
            $data = $dao->getUserTopTasks($id, $strict, $limit, $offset, $filter);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getUserTopTasks',  "Middleware::isloggedIn");
        
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/archivedTasks(:format)/',
                                                        function ($id, $format = ".json") {
            
            $limit = Dispatcher::clenseArgs('limit', HttpMethodEnum::GET, 5);
            $data = TaskDao::getUserArchivedTasks($id, $limit);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getUserArchivedTasks');
        
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/archivedTasks/:tid/archiveMetaData(:format)/',
                                                        function ($id, $tID, $format = ".json") {
            if (!is_numeric($tID) && strstr($tID, '.')) {
                 $tID = explode('.', $tID);
                 $format = '.'.$tID[1];
                 $tID = $tID[0];
            }
            
            $data = TaskDao::getArchivedTaskMetaData($tID);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getUserArchivedTaskMetaData');     
        
        
        Dispatcher::registerNamed(HttpMethodEnum::PUT, '/v0/users/:id/',
                                                        function ($id, $format = ".json") {
            if (!is_numeric($id) && strstr($id, '.')) {
                $id = explode('.', $id);
                $format = '.'.$id[1];
                $id = $id[0];
            }
            $data = Dispatcher::getDispatcher()->request()->getBody();
            $client = new APIHelper($format);
            $data = $client->deserialize($data,'User');
            $data->setId($id);
            $data = UserDao::save($data);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'updateUser');
        
        Dispatcher::registerNamed(HttpMethodEnum::POST, '/v0/users/:id/tags(:format)/',
                                                        function ($id, $format = ".json"){
            $data = Dispatcher::getDispatcher()->request()->getBody();
            $client = new APIHelper($format);
            $data = $client->deserialize($data,'Tag');
//            $data = $client->cast('Tag', $data);
            $data = UserDao::likeTag($id, $data->getId());
            if (is_array($data)) {
                $data = $data[0];
            }
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'addUsertag');
        
        Dispatcher::registerNamed(HttpMethodEnum::PUT, '/v0/users/:id/tags/:tagId/',
                                                        function ($id, $tagId, $format = ".json") {
            if (!is_numeric($tagId) && strstr($tagId, '.')) {
                $tagId = explode('.', $tagId);
                $format = '.'.$tagId[1];
                $tagId = $tagId[0];
            }
            $data = UserDao::likeTag($id, $tagId);
            if (is_array($data)) {
                $data = $data[0];
            }
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'addUserTagById');
        
        Dispatcher::registerNamed(HttpMethodEnum::DELETE, '/v0/users/:id/tags/:tagId/',
                                                            function ($id, $tagId, $format = ".json") {
            if (!is_numeric($tagId) && strstr($tagId, '.')) {
                $tagId = explode('.', $tagId);
                $format = '.'.$tagId[1];
                $tagId = $tagId[0];
            }
            $data = UserDao::removeTag($id, $tagId);
            if (is_array($data)) {
                $data = $data[0];
            }
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'deleteUserTagById');
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/trackedTasks(:format)/',
                                                        function ($id, $format = ".json") {
            
            $data=UserDao::getTrackedTasks($id);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getUserTrackedTasks');
        
        Dispatcher::registerNamed(HttpMethodEnum::POST, '/v0/users/:id/trackedTasks(:format)/',
                                                        function ($id, $format=".json"){
            $data = Dispatcher::getDispatcher()->request()->getBody();
            $client = new APIHelper($format);
            $data = $client->deserialize($data,'Task');
//            $data = $client->cast('Task', $data);
            $data = UserDao::trackTask($id, $data->getId());
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'addUserTrackedTasks');
        
        Dispatcher::registerNamed(HttpMethodEnum::PUT, '/v0/users/:id/trackedTasks/:taskID/',
                                                        function ($id, $taskID, $format = ".json") {
            
            if (!is_numeric($taskID) && strstr($taskID, '.')) {
                $taskID = explode('.', $taskID);
                $format = '.'.$taskID[1];
                $taskID = $taskID[0];
            }
            $data = UserDao::trackTask($id, $taskID);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'addUserTrackedTasksById');
        
        Dispatcher::registerNamed(HttpMethodEnum::DELETE, '/v0/users/:id/trackedTasks/:taskID/',
                                                            function ($id, $taskID, $format = ".json") {
            
            if (!is_numeric($taskID) && strstr($taskID, '.')) {
                $taskID = explode('.', $taskID);
                $format = '.'.$taskID[1];
                $taskID = $taskID[0];
            }
            $data=UserDao::ignoreTask($id, $taskID);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'deleteUserTrackedTasksById');
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/email/:email/passwordResetRequest(:format)/',
                                                        function ($email, $format = ".json") {
            $data = UserDao::hasRequestedPasswordReset($email) ? 1 : 0;

            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'hasUserRequestedPasswordReset', null);

        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/email/:email/passwordResetRequest/time(:format)/',
                                                        function ($email, $format = ".json"){
            $resetRequest = UserDao::getPasswordResetRequests($email);
            Dispatcher::sendResponce(null, $resetRequest->getRequestTime(), null, $format);
        }, "PasswordResetRequestTime", null);
        
        Dispatcher::registerNamed(HttpMethodEnum::POST, '/v0/users/email/:email/passwordResetRequest(:format)/',
                                                        function ($email, $format=".json"){
            $user = UserDao::getUser(null, $email);
            $user = $user[0];
            if ($user) {
                Dispatcher::sendResponce(null, UserDao::createPasswordReset($user), null, $format);
                Notify::sendPasswordResetEmail($user->getId());
            } else {
                Dispatcher::sendResponce(null, null, null, $format);
            }
        }, 'createPasswordResetRequest', null);
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/projects(:format)/',
                                                        function ($id, $format=".json"){
            $data = UserDao::getTrackedProjects($id);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getUserTrackedProjects'); 
        
        Dispatcher::registerNamed(HttpMethodEnum::PUT, '/v0/users/:id/projects/:pID/',
                                                        function ($id,$pID, $format=".json"){
            if (!is_numeric($pID) && strstr($pID, '.')) {
                $pID = explode('.', $pID);
                $format = '.'.$pID[1];
                $pID = $pID[0];
            }
            $data = UserDao::trackProject($pID,$id);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'userTrackProject'); 
        
        Dispatcher::registerNamed(HttpMethodEnum::DELETE, '/v0/users/:id/projects/:pID/',
                                                        function ($id,$pID, $format=".json"){
            if (!is_numeric($pID) && strstr($pID, '.')) {
                $pID = explode('.', $pID);
                $format = '.'.$pID[1];
                $pID = $pID[0];
            }
            $data = UserDao::unTrackProject($pID,$id);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'userUnTrackProject'); 
        
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/personalInfo(:format)/',
                                                        function ($id, $format = ".json") {
            
            if (!is_numeric($id) && strstr($id, '.')) {
                $id = explode('.', $id);
                $format = '.'.$id[1];
                $id = $id[0];
            }

            $data = UserDao::getPersonalInfo(null,$id);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getUserPersonalInfo',  "Middleware::authUserOwnsResource");
        
        Dispatcher::registerNamed(HttpMethodEnum::POST, '/v0/users/:id/personalInfo(:format)/',
                                                        function ($id, $format = ".json") {
            
            if (!is_numeric($id) && strstr($id, '.')) {
                $id = explode('.', $id);
                $format = '.'.$id[1];
                $id = $id[0];
            }
            
            $data = Dispatcher::getDispatcher()->request()->getBody();
            $client = new APIHelper($format);
            $data = $client->deserialize($data, "UserPersonalInformation");    
            
            Dispatcher::sendResponce(null, UserDao::createPersonalInfo($data), null, $format);

        }, 'createUserPersonalInfo');
        
        Dispatcher::registerNamed(HttpMethodEnum::PUT, '/v0/users/:id/personalInfo(:format)/',
                                                        function ($id, $format = ".json") {
            if (!is_numeric($id) && strstr($id, '.')) {
                $id = explode('.', $id);
                $format = '.'.$id[1];
                $id = $id[0];
            }
            $data = Dispatcher::getDispatcher()->request()->getBody();
            $client = new APIHelper($format);
            $data = $client->deserialize($data,'UserPersonalInformation');
            $data = UserDao::updatePersonalInfo($data);

            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'updateUserPersonalInfo');
        
        
        Dispatcher::registerNamed(HttpMethodEnum::GET, '/v0/users/:id/secondaryLanguages(:format)/',
                                                        function ($id, $format = ".json") {
            
            if (!is_numeric($id) && strstr($id, '.')) {
                $id = explode('.', $id);
                $format = '.'.$id[1];
                $id = $id[0];
            }

            $data = UserDao::getSecondaryLanguages($id);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'getSecondaryLanguages');
        
        Dispatcher::registerNamed(HttpMethodEnum::POST, '/v0/users/:id/secondaryLanguages(:format)/',
                                                        function ($id, $format = ".json") {
            $data = Dispatcher::getDispatcher()->request()->getBody();
            $client = new APIHelper($format);
            $data = $client->deserialize($data, "Locale");    
            
            Dispatcher::sendResponce(null, UserDao::createSecondaryLanguage($id, $data), null, $format);

        }, 'createSecondaryLanguage');
        
        Dispatcher::registerNamed(HttpMethodEnum::DELETE, '/v0/users/removeSecondaryLanguage/:userId/:languageCode/:countryCode/',
                                                        function ($userId,$languageCode,$countryCode, $format=".json"){
            if (strstr($countryCode, '.')) {
                $countryCode = explode('.', $countryCode);
                $format = '.'.$countryCode[1];
                $countryCode = $countryCode[0];
            }
            $data = UserDao::deleteSecondaryLanguage($userId, $languageCode, $countryCode);
            Dispatcher::sendResponce(null, $data, null, $format);
        }, 'deleteSecondaryLanguage');
    }
}
Users::init();
