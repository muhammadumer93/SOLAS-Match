<?php
namespace SolasMatch\Tests\UI;

require_once 'PHPUnit/Autoload.php';
require_once __DIR__.'/../../api/vendor/autoload.php';
\DrSlump\Protobuf::autoload();
require_once __DIR__.'/../../api/DataAccessObjects/BadgeDao.class.php';
require_once __DIR__.'/../../api/DataAccessObjects/ProjectDao.class.php';
require_once __DIR__.'/../../api/DataAccessObjects/UserDao.class.php';
require_once __DIR__.'/../../api/DataAccessObjects/TagsDao.class.php';
require_once __DIR__.'/../../api/lib/Notify.class.php';
require_once __DIR__.'/../../Common/Enums/BadgeTypes.class.php';
require_once __DIR__.'/../../Common/Enums/HttpMethodEnum.class.php';
require_once __DIR__.'/../../Common/lib/ModelFactory.class.php';
require_once __DIR__.'/../../Common/lib/SolasMatchException.php';
require_once __DIR__.'/../../Common/lib/UserSession.class.php';
require_once __DIR__.'/../../ui/DataAccessObjects/AdminDao.class.php';
require_once __DIR__.'/../../ui/DataAccessObjects/OrganisationDao.class.php';
require_once __DIR__.'/../../ui/DataAccessObjects/ProjectDao.class.php';
require_once __DIR__.'/../../ui/DataAccessObjects/TaskDao.class.php';
require_once __DIR__.'/../../ui/DataAccessObjects/UserDao.class.php';
require_once __DIR__.'/../../ui/lib/Localisation.php';
require_once __DIR__.'/../UnitTestHelper.php';

use \SolasMatch\API as API;
use \SolasMatch\Common as Common;
use \SolasMatch\Tests as Tests;
use \SolasMatch\UI as UI;
use SolasMatch\Tests\UnitTestHelper;

class TaskDaoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers UI\DAO\TaskDao::createTask
     */
    public function testCreateTask()
    {
        UnitTestHelper::teardownDb();
        
        $userDao = new UI\DAO\UserDao();
        $orgDao = new UI\DAO\OrganisationDao();
        $projectDao = new UI\DAO\ProjectDao();
        $taskDao = new UI\DAO\TaskDao();
        
        $userEmail = "blah@test.com";
        $userPw = "password";
        $isRegistered = $userDao->register($userEmail, $userPw);
        $this->assertTrue($isRegistered);
        
        $registerUser = API\DAO\UserDao::getUser(null, $userEmail);
        $userId = $registerUser->getId();
        $this->assertNotNull($registerUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $registerUser);
        //Use API DAO because UI one requires UUID which we cannot retrieve (it would be emailed to the user)
        $finishRegResult = API\DAO\UserDao::finishRegistration($userId);
        $this->assertEquals("1", $finishRegResult);
        
        $userDao->login($userEmail, $userPw);
        $org = UnitTestHelper::createOrg();
        $insertedOrg = $orgDao->createOrg($org, $userId);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = $projectDao->createProject($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        $task = UnitTestHelper::createTask($insertedProject->getId());
        $insertedTask = $taskDao->createTask($task);
        $this->assertNotNull($insertedTask);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $insertedTask);
    }
    
    /**
     * @covers UI\DAO\TaskDao::getTask 
     */
    public function testGetTask()
    {
        UnitTestHelper::teardownDb();
        
        $userDao = new UI\DAO\UserDao();
        $orgDao = new UI\DAO\OrganisationDao();
        $projectDao = new UI\DAO\ProjectDao();
        $taskDao = new UI\DAO\TaskDao();
        
        $userEmail = "blah@test.com";
        $userPw = "password";
        $isRegistered = $userDao->register($userEmail, $userPw);
        $this->assertTrue($isRegistered);
        
        $registerUser = API\DAO\UserDao::getUser(null, $userEmail);
        $userId = $registerUser->getId();
        $this->assertNotNull($registerUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $registerUser);
        //Use API DAO because UI one requires UUID which we cannot retrieve (it would be emailed to the user)
        $finishRegResult = API\DAO\UserDao::finishRegistration($userId);
        $this->assertEquals("1", $finishRegResult);
        
        $userDao->login($userEmail, $userPw);
        $org = UnitTestHelper::createOrg();
        $insertedOrg = $orgDao->createOrg($org, $userId);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = $projectDao->createProject($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        $task = UnitTestHelper::createTask($insertedProject->getId());
        $insertedTask = $taskDao->createTask($task);
        $this->assertNotNull($insertedTask);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $insertedTask);
        
        $getTask = $taskDao->getTask($insertedTask->getId());
        $this->assertNotNull($getTask);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $getTask);
        $this->assertEquals($insertedTask, $getTask);
    }
}