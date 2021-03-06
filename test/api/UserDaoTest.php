<?php

namespace SolasMatch\Tests\API;

use \SolasMatch\Tests\UnitTestHelper;
use \SolasMatch\Common as Common;
use \SolasMatch\API as API;

require_once 'PHPUnit/Autoload.php';
require_once __DIR__.'/../../api/vendor/autoload.php';
\DrSlump\Protobuf::autoload();
require_once __DIR__.'/../../api/DataAccessObjects/UserDao.class.php';
require_once __DIR__.'/../../api/DataAccessObjects/OrganisationDao.class.php';
require_once __DIR__.'/../../api/DataAccessObjects/BadgeDao.class.php';
require_once __DIR__.'/../../api/DataAccessObjects/TagsDao.class.php';
require_once __DIR__.'/../../api/DataAccessObjects/TaskDao.class.php';
require_once __DIR__.'/../../api/DataAccessObjects/ProjectDao.class.php';
require_once __DIR__."/../../Common/lib/Authentication.class.php";
require_once __DIR__."/../../Common/Enums/NotificationIntervalEnum.class.php";
require_once __DIR__.'/../../Common/Enums/BadgeTypes.class.php';
require_once __DIR__.'/../UnitTestHelper.php';

class UserDaoTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @covers API\DAO\UserDao::create
     */
    public function testCreateUser()
    {
        UnitTestHelper::teardownDb();
        
        // Success
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        $this->assertEquals("testuser@example.com", $insertedUser->getEmail());
        $this->assertNotNull($insertedUser->getPassword());
        $this->assertNotNull($insertedUser->getNonce());
    }
    
    /**
     * @covers API\DAO\UserDao::save
     */
    public function testUpdateUser()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $insertedUser->setDisplayName("Updated DisplayName");
        $insertedUser->setEmail("updatedEmail@test.com");
        $insertedUser->setBiography("Updated Bio");
        
        $locale = new Common\Protobufs\Models\Locale();
        $locale->setLanguageCode("en");
        $locale->setCountryCode("IE");
        $insertedUser->setNativeLocale($locale);

        $insertedUser->setNonce(123456789);
        $insertedUser->setPassword(md5("derpymerpy"));
      
        // Success
        $updatedUser = API\DAO\UserDao::save($insertedUser);
        $this->assertNotNull($updatedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $updatedUser);
        $this->assertEquals($insertedUser->getId(), $updatedUser->getId());
        $this->assertEquals($insertedUser->getDisplayName(), $updatedUser->getDisplayName()); //Failure!!
        $this->assertEquals($insertedUser->getEmail(), $updatedUser->getEmail());
        $this->assertEquals($insertedUser->getBiography(), $updatedUser->getBiography());
        
        $this->assertEquals(
            $insertedUser->getNativeLocale()->getLanguageCode(),
            $updatedUser->getNativeLocale()->getLanguageCode()
        );
        $this->assertEquals(
            $insertedUser->getNativeLocale()->getCountryCode(),
            $updatedUser->getNativeLocale()->getCountryCode()
        );
        
        $this->assertEquals($insertedUser->getNonce(), $updatedUser->getNonce());
        $this->assertEquals($insertedUser->getPassword(), $updatedUser->getPassword());
    }
    
    /**
     * @covers API\DAO\UserDao::getUser
     */
    public function testGetUser()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $insertedUser->setDisplayName("Updated DisplayName");
        $insertedUser->setEmail("updatedEmail@test.com");
        $insertedUser->setBiography("Updated Bio");
        
        $locale = new Common\Protobufs\Models\Locale();
        $locale->setLanguageCode("en");
        $locale->setCountryCode("IE");
        $insertedUser->setNativeLocale($locale);
        
        $insertedUser->setNonce(123456789);
        $insertedUser->setPassword(md5("derpymerpy"));
        $updatedUser = API\DAO\UserDao::save($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $updatedUser);
              
        // Success
        $getUpdatedUser = API\DAO\UserDao::getUser($updatedUser->getId());
        $this->assertNotNull($getUpdatedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $getUpdatedUser);
        $this->assertEquals($insertedUser->getId(), $getUpdatedUser->getId());
        
        $this->assertEquals($insertedUser->getDisplayName(), $getUpdatedUser->getDisplayName());
        $this->assertEquals($insertedUser->getEmail(), $getUpdatedUser->getEmail());
        $this->assertEquals($insertedUser->getBiography(), $getUpdatedUser->getBiography());
        
        $this->assertEquals(
            $insertedUser->getNativeLocale()->getLanguageCode(),
            $updatedUser->getNativeLocale()->getLanguageCode()
        );
        $this->assertEquals(
            $insertedUser->getNativeLocale()->getCountryCode(),
            $updatedUser->getNativeLocale()->getCountryCode()
        );
        
        $this->assertEquals($insertedUser->getNonce(), $getUpdatedUser->getNonce());
        $this->assertEquals($insertedUser->getPassword(), $getUpdatedUser->getPassword());
    }
    
    /**
     * @covers API\DAO\UserDao::getUsers
     */
    public function testGetUsers()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $user2 = UnitTestHelper::createUser(null,"Foo",null,"foofoo@com.com");
        $insertedUser2 = API\DAO\UserDao::save($user2);
        $this->assertNotNull($insertedUser2);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser2);
        
        $getUsers = API\DAO\UserDao::getUsers();
        $this->assertCount(2, $getUsers);
        foreach ($getUsers as $savedUser) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $savedUser);
        }
    }
    
    /**
     * @covers API\DAO\UserDao::deleteUser
     */
    public function testDeleteUser()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        API\DAO\UserDao::deleteUser($insertedUser->getId());
        $getDeletedUser = API\DAO\UserDao::getUser($insertedUser->getId());
        $this->assertNull($getDeletedUser);
    }
    
    /**
     * @covers API\DAO\UserDao::changePassword
     */
    public function testChangePassword()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        // Success
        $userWithChangedPw = API\DAO\UserDao::changePassword($insertedUser->getId(), "New Password");
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $userWithChangedPw);
        $this->assertNotEquals($insertedUser->getPassword(), $userWithChangedPw->getPassword());
        $this->assertNotEquals($insertedUser->getNonce(), $userWithChangedPw->getNonce());
    }
    
    /**
     * @covers API\DAO\UserDao::apiRegister
     */
    public function testApiRegister()
    {
        UnitTestHelper::teardownDb();
        
        $email = "foocoochoo@blah.com";
        $pw = "password";
        
        $result = API\DAO\UserDao::apiRegister($email, $pw);
        $this->assertEquals('1', $result);

        $user = API\DAO\UserDao::getUser(null, $email);
        $this->assertNotNull($user);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $user);
        $this->assertEquals($email, $user->getEmail());
        
        //Get user's nonce and use it to generate the hashed password that is stored in $regUser and assert that this
        //result does indeed match the password stored in $regUser
        $nonce = $user->getNonce();
        $hashpw = Common\Lib\Authentication::hashPassword($pw, $nonce);
        $this->assertEquals($user->getPassword(), $hashpw);
    }
    
    /**
     * @covers API\DAO\UserDao::finishRegistration
     */
    public function testFinishRegistration()
    {
        UnitTestHelper::teardownDb();
        
        $email = "foocoochoo@blah.com";
        $pw = "password";
        
        $result = API\DAO\UserDao::apiRegister($email, $pw);
        $this->assertEquals('1', $result);

        $user = API\DAO\UserDao::getUser(null, $email);
        $this->assertNotNull($user);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $user);
        $this->assertEquals($email, $user->getEmail());
        
        //Get user's nonce and use it to generate the hashed password that is stored in $regUser and assert that this
        //result does indeed match the password stored in $regUser
        $nonce = $user->getNonce();
        $hashpw = Common\Lib\Authentication::hashPassword($pw, $nonce);
        $this->assertEquals($user->getPassword(), $hashpw);
        
        $finishReg = API\DAO\UserDao::finishRegistration($user->getId());
        $this->assertEquals("1", $finishReg);
    }
    
    /**
     * @covers API\DAO\UserDao::isUserVerified
     */
    public function testIsUserVerified()
    {
        UnitTestHelper::teardownDb();
    
        $email = "foocoochoo@blah.com";
        $pw = "password";
    
        $result = API\DAO\UserDao::apiRegister($email, $pw);
        $this->assertEquals('1', $result);

        $user = API\DAO\UserDao::getUser(null, $email);
        $this->assertNotNull($user);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $user);
        $this->assertEquals($email, $user->getEmail());
    
        //Get user's nonce and use it to generate the hashed password that is stored in $regUser and assert that this
        //result does indeed match the password stored in $regUser
        $nonce = $user->getNonce();
        $hashpw = Common\Lib\Authentication::hashPassword($pw, $nonce);
        $this->assertEquals($user->getPassword(), $hashpw);
    
        $finishReg = API\DAO\UserDao::finishRegistration($user->getId());
        $this->assertEquals("1", $finishReg);
        
        $isVerified = API\DAO\UserDao::isUserVerified($user->getId());
        $this->assertEquals("1", $isVerified);
    }
    
    /**
     * @covers API\DAO\UserDao::findOrganisationUserBelongsTo
     */
    public function testFindOrganisationsUserBelongsTo()
    {
        UnitTestHelper::teardownDb();
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg->getId());
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $resultRequestMembership = API\DAO\OrganisationDao::requestMembership(
            $insertedUser->getId(),
            $insertedOrg->getId()
        );
        $this->assertEquals("1", $resultRequestMembership);
        
        $resultAcceptMembership = API\DAO\OrganisationDao::acceptMemRequest(
            $insertedOrg->getId(),
            $insertedUser->getId()
        );
        $this->assertEquals("1", $resultAcceptMembership);
        
        $org2 = UnitTestHelper::createOrg(
            null,
            "Organisation 2",
            "Organisation 2 Bio",
            "http://www.organisation2.org"
        );
        $insertedOrg2 = API\DAO\OrganisationDao::insertAndUpdate($org2);
        $this->assertNotNull($insertedOrg2);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg2);
        
        $resultRequestMembership2 = API\DAO\OrganisationDao::requestMembership(
            $insertedUser->getId(),
            $insertedOrg2->getId()
        );
        $this->assertEquals("1", $resultRequestMembership2);
        
        $resultAcceptMembership2 = API\DAO\OrganisationDao::acceptMemRequest(
            $insertedOrg2->getId(),
            $insertedUser->getId()
        );
        $this->assertEquals("1", $resultAcceptMembership2);
        
        // Success
        $userOrgs = API\DAO\UserDao::findOrganisationsUserBelongsTo($insertedUser->getId());
        $this->assertCount(2, $userOrgs);
        foreach ($userOrgs as $org) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $org);
        }
    }
    
    /**
     * @covers API\DAO\UserDao::getUserBadges
     */
    public function testGetUserBadges()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        $userId = $insertedUser->getId();
        
        $userBadges = API\DAO\UserDao::getUserBadges($userId);
        $this->assertNull($userBadges);
        
        $assignBadge = API\DAO\BadgeDao::assignBadge($userId, 3);
        $this->assertEquals(1, $assignBadge);
        
        $userBadges1 = API\DAO\UserDao::getUserBadges($userId);
        $this->assertCount(1, $userBadges1);
        foreach ($userBadges1 as $badge) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_BADGE, $badge);
        }
        
        $assignBadge1 = API\DAO\BadgeDao::assignBadge($userId, 4);
        $this->assertEquals(1, $assignBadge1);
        
        $userBadges2 = API\DAO\UserDao::getUserBadges($userId);
        $this->assertCount(2, $userBadges2);
        foreach ($userBadges2 as $badge) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_BADGE, $badge);
        }
        
        $assignBadge2 = API\DAO\BadgeDao::assignBadge($userId, 5);
        $this->assertEquals(1, $assignBadge2);
        
        // Success
        $userBadges3 = API\DAO\UserDao::getUserBadges($userId);
        $this->assertCount(3, $userBadges3);
        foreach ($userBadges3 as $badge) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_BADGE, $badge);
        }
    }
    
    /**
     * @covers API\DAO\UserDao::getUserTags
     */
    public function testGetUserTags()
    {
        UnitTestHelper::teardownDb();
       
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        $userId = $insertedUser->getId();
        
        // Failure
        $noUserTags = API\DAO\UserDao::getUserTags($userId);
        $this->assertNull($noUserTags);
        
        $tag = API\DAO\TagsDao::create("English");
        $this->assertNotNull($tag);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TAG, $tag);
        
        $tag2 = API\DAO\TagsDao::create("French");
        $this->assertNotNull($tag2);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TAG, $tag2);
        
        $tagLiked = API\DAO\UserDao::likeTag($userId, $tag->getId());
        $this->assertEquals("1", $tagLiked);
        // Success
        $oneUserTag = API\DAO\UserDao::getUserTags($userId);
        $this->assertCount(1, $oneUserTag);
        foreach ($oneUserTag as $tag) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_TAG, $tag);
        }

        $tagLiked2 = API\DAO\UserDao::likeTag($userId, $tag2->getId());
        $this->assertEquals("1", $tagLiked2);
        
        // Success
        $twoUserTags = API\DAO\UserDao::getUserTags($userId);
        $this->assertCount(2, $twoUserTags);
        foreach ($twoUserTags as $tag) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_TAG, $tag);
        }
    }
    
    /**
     * @covers API\DAO\UserDao::getUsersWithBadge
     */
    public function testGetUsersWithBadge()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        // Failure
        $noUsersWithBadge = API\DAO\UserDao::getUsersWithBadge(3);
        $this->assertNull($noUsersWithBadge);
        
        $assignBadge = API\DAO\BadgeDao::assignBadge($insertedUser->getId(), 3);
        $this->assertEquals(1, $assignBadge);
        
        // Success
        $oneUserWithBadge = API\DAO\UserDao::getUsersWithBadge(3);
        $this->assertCount(1, $oneUserWithBadge);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $oneUserWithBadge[0]);
        
        $insertedUser2 = API\DAO\UserDao::create("testuser2@example.com", "testpw2");
        $this->assertNotNull($insertedUser2);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser2);
        
        $assignBadge2 = API\DAO\BadgeDao::assignBadge($insertedUser2->getId(), 3);
        $this->assertEquals(1, $assignBadge2);
        
        // Success
        $twoUsersWithBadge = API\DAO\UserDao::getUsersWithBadge(3);
        $this->assertCount(2, $twoUsersWithBadge);
        foreach ($twoUsersWithBadge as $user) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $user);
        }
    }
    
    /**
     * @covers API\DAO\UserDao::likeTag
     */
    public function testLikeTag()
    {
        UnitTestHelper::teardownDb();
       
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        $userId = $insertedUser->getId();
        
        $tag = API\DAO\TagsDao::create("English");
        $this->assertInstanceOf(UnitTestHelper::PROTO_TAG, $tag);
        $this->assertNotNull($tag->getId());
       
        // Success
        $tagLiked = API\DAO\UserDao::likeTag($userId, $tag->getId());
        $this->assertEquals("1", $tagLiked);
        
        // Failure
        $tagLikedFailure = API\DAO\UserDao::likeTag($userId, $tag->getId());
        $this->assertEquals("0", $tagLikedFailure);
    }
    
    /**
     * @covers API\DAO\UserDao::removeTag
     */
    public function testRemoveTag()
    {
        UnitTestHelper::teardownDb();
       
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        $userId = $insertedUser->getId();
        
        $tag = API\DAO\TagsDao::create("English");
        $this->assertInstanceOf(UnitTestHelper::PROTO_TAG, $tag);
        $this->assertNotNull($tag->getId());
        
        $tagLiked = API\DAO\UserDao::likeTag($userId, $tag->getId());
        $this->assertEquals("1", $tagLiked);
        
        // Success
        $removedTag = API\DAO\UserDao::removeTag($userId, $tag->getId());
        $this->assertEquals("1", $removedTag);
        
        // Failure
        $removedTagFailure = API\DAO\UserDao::removeTag($userId, $tag->getId());
        $this->assertEquals("0", $removedTagFailure);
    }
    
    /**
     * @covers API\DAO\UserDao::trackTask
     */
    public function testTrackTask()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        $task = UnitTestHelper::createTask($insertedProject->getId());
        $insertedTask = API\DAO\TaskDao::save($task);
        $this->assertNotNull($insertedTask);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $insertedTask);
        
        $trackTask = API\DAO\UserDao::trackTask($insertedUser->getId(), $insertedTask->getId());
        $this->assertEquals("1", $trackTask);
        
        $trackTaskFail = API\DAO\UserDao::trackTask($insertedUser->getId(), $insertedTask->getId());
        $this->assertEquals("0", $trackTaskFail);
    }
    
    /**
     * @covers API\DAO\UserDao::ignoreTask
     */
    public function testIgnoreTask()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        $task = UnitTestHelper::createTask($insertedProject->getId());
        $insertedTask = API\DAO\TaskDao::save($task);
        $this->assertNotNull($insertedTask);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $insertedTask);
        
        $trackTask = API\DAO\UserDao::trackTask($insertedUser->getId(), $insertedTask->getId());
        $this->assertEquals("1", $trackTask);
        
        $ignoreTask = API\DAO\UserDao::ignoreTask($insertedUser->getId(), $insertedTask->getId());
        $this->assertEquals("1", $ignoreTask);
        
        $ignoreTaskFail = API\DAO\UserDao::ignoreTask($insertedUser->getId(), $insertedTask->getId());
        $this->assertEquals("0", $ignoreTaskFail);
    }
    
    /**
     * @covers API\DAO\UserDao::isSubscribedToTask
     */
    public function testIsSubscribedToTask()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        $task = UnitTestHelper::createTask($insertedProject->getId());
        $insertedTask = API\DAO\TaskDao::save($task);
        $this->assertNotNull($insertedTask);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $insertedTask);
        
        $isTrackingTaskFail = API\DAO\UserDao::isSubscribedToTask($insertedUser->getId(), $insertedTask->getId());
        $this->assertEquals("0", $isTrackingTaskFail);
        
        $trackTask = API\DAO\UserDao::trackTask($insertedUser->getId(), $insertedTask->getId());
        $this->assertEquals("1", $trackTask);
        
        $isTrackingTask = API\DAO\UserDao::isSubscribedToTask($insertedUser->getId(), $insertedTask->getId());
        $this->assertEquals("1", $isTrackingTask);
    }

    /**
     * @covers API\DAO\UserDao::getTrackedTasks
     */
    public function testGetTrackedTasks()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        $task = UnitTestHelper::createTask($insertedProject->getId());
        $insertedTask = API\DAO\TaskDao::save($task);
        $this->assertNotNull($insertedTask);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $insertedTask);
        
        //User has no tracked tasks, nothing is returned
        $getNoTrackedTasks = API\DAO\UserDao::getTrackedTasks($insertedUser->getId());
        $this->assertNull($getNoTrackedTasks);
        
        $trackTask = API\DAO\UserDao::trackTask($insertedUser->getId(), $insertedTask->getId());
        $this->assertEquals("1", $trackTask);
        
        //User has tracked a task, something will be returned
        $getTrackedTasks = API\DAO\UserDao::getTrackedTasks($insertedUser->getId());
        $this->assertCount(1, $getTrackedTasks);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $getTrackedTasks[0]);
        $this->assertEquals($insertedTask->getId(), $getTrackedTasks[0]->getId());
        $this->assertEquals($insertedTask->getTaskStatus(), $getTrackedTasks[0]->getTaskStatus());
    }
    
    /**
     * @covers API\DAO\UserDao::createPasswordResetRequest
     */
    public function testCreatePasswordResetRequest()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        // Success
        $createPwResetRequest = API\DAO\UserDao::addPasswordResetRequest(
            "asfjkosagijo".$insertedUser->getId(),
            $insertedUser->getId()
        );
        $this->assertEquals("1", $createPwResetRequest);
    }
    
    /**
     * @covers API\DAO\UserDao::hasRequestedPasswordReset
     */
    public function testHasRequestedPasswordReset()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $createPwResetRequest = API\DAO\UserDao::addPasswordResetRequest(
            "asfjkosagijo".$insertedUser->getId(),
            $insertedUser->getId()
        );
        $this->assertEquals("1", $createPwResetRequest);
        $hasPwResetReq = API\DAO\UserDao::hasRequestedPasswordReset($insertedUser->getEmail());
        $this->assertTrue($hasPwResetReq);
    }
    
    /**
     * @covers API\DAO\UserDao::removePasswordResetRequest
     */
    public function testRemovePasswordResetRequest()
    {
        UnitTestHelper::teardownDb();
        
        $insertedUser = API\DAO\UserDao::create("testuser@example.com", "testpw");
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $createPwResetRequest = API\DAO\UserDao::addPasswordResetRequest(
            "asfjkosagijo".$insertedUser->getId(),
            $insertedUser->getId()
        );
        $this->assertEquals("1", $createPwResetRequest);
        
        // Success
        $removePwResetRequest = API\DAO\UserDao::removePasswordResetRequest($insertedUser->getId());
        $this->assertEquals("1", $removePwResetRequest);
        
        // Failure
        $removePwResetRequestFail = API\DAO\UserDao::removePasswordResetRequest($insertedUser->getId());
        $this->assertEquals("0", $removePwResetRequestFail);
    }
    
    /**
     * @covers API\DAO\UserDao::getPasswordResetRequests
     */
    public function testGetPasswordResetRequests()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $createPwResetRequest = API\DAO\UserDao::addPasswordResetRequest(
            "asfjkosagijo".$insertedUser->getId(),
            $insertedUser->getId()
        );
        $this->assertEquals("1", $createPwResetRequest);
        
        // Success
        $passwordResetRequest = API\DAO\UserDao::getPasswordResetRequests(
            $insertedUser->getEmail(),
            "asfjkosagijo".$insertedUser->getId()
        );
        $this->assertInstanceOf(UnitTestHelper::PROTO_PASSWORD_RESET_REQ, $passwordResetRequest);
        
        $removePwResetRequest = API\DAO\UserDao::removePasswordResetRequest($insertedUser->getId());
        $this->assertEquals("1", $removePwResetRequest);
        
        // Failure
        $passwordResetRequestFailure = API\DAO\UserDao::getPasswordResetRequests($insertedUser->getId());
        $this->assertNull($passwordResetRequestFailure);
    }
    
    /**
     * @covers API\DAO\UserDao::trackProject
     */
    public function testTrackProject()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        // Failure
        $trackProjectFailure = API\DAO\UserDao::trackProject(999, $insertedUser->getId());
        $this->assertNull($trackProjectFailure);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        // Success
        $trackProject = API\DAO\UserDao::trackProject($insertedProject->getId(), $insertedUser->getId());
        $this->assertEquals("1", $trackProject);
    }
    
    /**
     * @covers API\DAO\UserDao::unTrackProject
     */
    public function testUnTrackProject()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        //User has not already tracked the project, valid failure
        $untrackProjectFailure = API\DAO\UserDao::unTrackProject($insertedProject->getId(), $insertedUser->getId());
        $this->assertEquals("0", $untrackProjectFailure);
        
        $trackProject = API\DAO\UserDao::trackProject($insertedProject->getId(), $insertedUser->getId());
        $this->assertEquals("1", $trackProject);
        
        //User has tracked the project, successful untracking
        $untrackProject = API\DAO\UserDao::unTrackProject($insertedProject->getId(), $insertedUser->getId());
        $this->assertEquals("1", $untrackProject);
    }
    
    /**
     * @covers API\DAO\UserDao::getTrackedProjects
     */
    public function testGetTrackedProjects()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        $project2 = UnitTestHelper::createProject(
            $insertedOrg->getId(),
            null,
            "Project 2 Title",
            "Project 2 Description"
        );
        $insertedProject2 = API\DAO\ProjectDao::save($project2);
        $this->assertNotNull($insertedProject2);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject2);
        
        $trackProject = API\DAO\UserDao::trackProject($insertedProject->getId(), $insertedUser->getId());
        $this->assertEquals("1", $trackProject);
        
        $trackProject2 = API\DAO\UserDao::trackProject($insertedProject2->getId(), $insertedUser->getId());
        $this->assertEquals("1", $trackProject2);
        
        // Success
        $trackedProjects = API\DAO\UserDao::getTrackedProjects($insertedUser->getId());
        $this->assertCount(2, $trackedProjects);
        foreach ($trackedProjects as $project) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $project);
        }
    }
    
    /**
     * @covers API\DAO\UserDao::isSubscribedToProject
     */
    public function testIsSubscribedToProject()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        //User is not subscribed
        $isSubscribedToProjectFailure = API\DAO\UserDao::isSubscribedToProject(
            $insertedUser->getId(),
            $insertedProject->getId()
        );
        $this->assertEquals("0", $isSubscribedToProjectFailure);
        
        $trackProject = API\DAO\UserDao::trackProject($insertedProject->getId(), $insertedUser->getId());
        $this->assertEquals("1", $trackProject);
        
        //User is subscribed
        $isSubscribedToProject = API\DAO\UserDao::isSubscribedToProject(
            $insertedUser->getId(),
            $insertedProject->getId()
        );
        $this->assertEquals("1", $isSubscribedToProject);
    }
    
    /**
     * @covers API\DAO\UserDao::getUserTaskStreamNotification
     */
    public function testGetUserTaskStreamNotification()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        $task = UnitTestHelper::createTask($insertedProject->getId());
        $insertedTask = API\DAO\TaskDao::save($task);
        $this->assertNotNull($insertedTask);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $insertedTask);
        
        $notification = new Common\Protobufs\Models\UserTaskStreamNotification();
        $notification->setUserId($insertedUser->getId());
        $notification->setInterval(Common\Enums\NotificationIntervalEnum::DAILY);
        $notification->setStrict(false);
        API\DAO\UserDao::requestTaskStreamNotification($notification);
        
        $getTsn = API\DAO\UserDao::getUserTaskStreamNotification($insertedUser->getId());
        $this->assertNotNull($getTsn);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER_TSN, $getTsn);
        $this->assertEquals($insertedUser->getId(), $getTsn->getUserId());
        $this->assertEquals(Common\Enums\NotificationIntervalEnum::DAILY, $getTsn->getInterval());
    }
    
    /**
     * @covers API\DAO\UserDao::removeTaskStreamNotification
     */
    public function testRemoveTaskStreamNotification()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $project = UnitTestHelper::createProject($insertedOrg->getId());
        $insertedProject = API\DAO\ProjectDao::save($project);
        $this->assertNotNull($insertedProject);
        $this->assertInstanceOf(UnitTestHelper::PROTO_PROJECT, $insertedProject);
        
        $task = UnitTestHelper::createTask($insertedProject->getId());
        $insertedTask = API\DAO\TaskDao::save($task);
        $this->assertNotNull($insertedTask);
        $this->assertInstanceOf(UnitTestHelper::PROTO_TASK, $insertedTask);
        
        $notification = new Common\Protobufs\Models\UserTaskStreamNotification();
        $notification->setUserId($insertedUser->getId());
        $notification->setInterval(Common\Enums\NotificationIntervalEnum::DAILY);
        $notification->setStrict(false);
        API\DAO\UserDao::requestTaskStreamNotification($notification);
        
        $getTsn = API\DAO\UserDao::getUserTaskStreamNotification($insertedUser->getId());
        $this->assertNotNull($getTsn);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER_TSN, $getTsn);
        $this->assertEquals($insertedUser->getId(), $getTsn->getUserId());
        $this->assertEquals(Common\Enums\NotificationIntervalEnum::DAILY, $getTsn->getInterval());
        
        $removeTsn = API\DAO\UserDao::removeTaskStreamNotification($insertedUser->getId());
        $this->assertTrue($removeTsn);
    }
    
    /**
     * @covers API\DAO\UserDao::savePersonalInfo
     */
    public function testCreatePersonalInfo()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $userInfo = UnitTestHelper::createUserPersonalInfo($insertedUser->getId());
        $insertedInfo = API\DAO\UserDao::savePersonalInfo($userInfo);
        $this->assertNotNull($insertedInfo);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER_INFO, $insertedInfo);
        $this->assertEquals($userInfo->getReceiveCredit(), $insertedInfo->getReceiveCredit());
    }
    
    /**
     * @covers API\DAO\UserDao::savePersonalInfo
     */
    public function testUpdatePersonalInfo()
    {
        UnitTestHelper::teardownDb();
    
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
    
        $userInfo = UnitTestHelper::createUserPersonalInfo($insertedUser->getId());
        $insertedInfo = API\DAO\UserDao::savePersonalInfo($userInfo);
        $this->assertNotNull($insertedInfo);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER_INFO, $insertedInfo);
        
        $insertedInfo->setFirstName("Roy");
        $insertedInfo->setLastName("Jaeger");
        $insertedInfo->setMobileNumber(55555221333);
        $updatedInfo = API\DAO\UserDao::savePersonalInfo($insertedInfo);
        $this->assertNotNull($updatedInfo);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER_INFO, $updatedInfo);
        
        $this->assertEquals($insertedInfo->getFirstName(), $updatedInfo->getFirstName());
        $this->assertEquals($insertedInfo->getLastName(), $updatedInfo->getLastName());
        $this->assertEquals($insertedInfo->getMobileNumber(), $updatedInfo->getMobileNumber());
    }
    
    /**
     * @covers API\DAO\UserDao::getPersonalInfo
     */
    public function testGetPersonalInfo()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $userInfo = UnitTestHelper::createUserPersonalInfo($insertedUser->getId());
        $insertedInfo = API\DAO\UserDao::savePersonalInfo($userInfo);
        $this->assertNotNull($insertedInfo);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER_INFO, $insertedInfo);
        
        $getInfo = API\DAO\UserDao::getPersonalInfo($insertedInfo->getId());
        $this->assertNotNull($getInfo);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER_INFO, $getInfo);
        $this->assertEquals($insertedInfo, $getInfo);
    }
    
    /**
     * @covers API\DAO\UserDao::createSecondaryLanguage
     */
    public function testCreateSecondaryLanguage()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $user->getNativeLocale()->setLanguageCode("en");
        $user->getNativeLocale()->setCountryCode("IE");
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $locale = new Common\Protobufs\Models\Locale();
        $locale->setLanguageCode("ja");
        $locale->setCountryCode("JP");
        
        $afterCreate = API\DAO\UserDao::createSecondaryLanguage($insertedUser->getId(), $locale);
        $this->assertNotNull($afterCreate);
        $this->assertInstanceOf(UnitTestHelper::PROTO_LOCALE, $afterCreate);
        $this->assertEquals($locale->getLanguageCode(), $afterCreate->getLanguageCode());
        $this->assertEquals($locale->getCountryCode(), $afterCreate->getCountryCode());
    }
    
    /**
     * @covers API\DAO\UserDao::getSecondaryLanguages
     */
    public function testGetSecondaryLanguages()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $user->getNativeLocale()->setLanguageCode("en");
        $user->getNativeLocale()->setCountryCode("IE");
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $locale = new Common\Protobufs\Models\Locale();
        $locale->setLanguageCode("ja");
        $locale->setCountryCode("JP");
        
        $afterCreate = API\DAO\UserDao::createSecondaryLanguage($insertedUser->getId(), $locale);
        $this->assertNotNull($afterCreate);
        $this->assertInstanceOf(UnitTestHelper::PROTO_LOCALE, $afterCreate);
        $this->assertEquals($locale->getLanguageCode(), $afterCreate->getLanguageCode());
        $this->assertEquals($locale->getCountryCode(), $afterCreate->getCountryCode());
        
        $locale2 = new Common\Protobufs\Models\Locale();
        $locale2->setLanguageCode("ga");
        $locale2->setCountryCode("IE");
        
        $afterCreate2 = API\DAO\UserDao::createSecondaryLanguage($insertedUser->getId(), $locale2);
        $this->assertNotNull($afterCreate2);
        $this->assertInstanceOf(UnitTestHelper::PROTO_LOCALE, $afterCreate2);
        $this->assertEquals($locale2->getLanguageCode(), $afterCreate2->getLanguageCode());
        $this->assertEquals($locale2->getCountryCode(), $afterCreate2->getCountryCode());
        
        $getSecondaryLangs = API\DAO\UserDao::getSecondaryLanguages($insertedUser->getId());
        
        $this->assertCount(2, $getSecondaryLangs);
        foreach ($getSecondaryLangs as $lang) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_LOCALE, $lang);
        }
    }
    
    /**
     * @covers API\DAO\UserDao::deleteSecondaryLanguage
     */
    public function testDeleteSecondaryLanguage()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $user->getNativeLocale()->setLanguageCode("en");
        $user->getNativeLocale()->setCountryCode("IE");
        $insertedUser = API\DAO\UserDao::save($user);
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $locale = new Common\Protobufs\Models\Locale();
        $locale->setLanguageCode("ja");
        $locale->setCountryCode("JP");
        
        $afterCreate = API\DAO\UserDao::createSecondaryLanguage($insertedUser->getId(), $locale);
        $this->assertNotNull($afterCreate);
        $this->assertInstanceOf(UnitTestHelper::PROTO_LOCALE, $afterCreate);
        $this->assertEquals($locale->getLanguageCode(), $afterCreate->getLanguageCode());
        $this->assertEquals($locale->getCountryCode(), $afterCreate->getCountryCode());
        
        $locale2 = new Common\Protobufs\Models\Locale();
        $locale2->setLanguageCode("ga");
        $locale2->setCountryCode("IE");
        
        $afterCreate2 = API\DAO\UserDao::createSecondaryLanguage($insertedUser->getId(), $locale2);
        $this->assertNotNull($afterCreate2);
        $this->assertInstanceOf(UnitTestHelper::PROTO_LOCALE, $afterCreate2);
        $this->assertEquals($locale2->getLanguageCode(), $afterCreate2->getLanguageCode());
        $this->assertEquals($locale2->getCountryCode(), $afterCreate2->getCountryCode());
        
        $afterDeleteLang = API\DAO\UserDao::deleteSecondaryLanguage($insertedUser->getId(), "ga", "IE");
        //assert that delete worked
        $this->assertEquals("1", $afterDeleteLang);
        $tryRedeleteLang = API\DAO\UserDao::deleteSecondaryLanguage($insertedUser->getId(), "ga", "IE");
        //assert that redelete attempt did nothing
        $this->assertEquals("0", $tryRedeleteLang);
        
        $getLangs = API\DAO\UserDao::getSecondaryLanguages($insertedUser->getId());
        $this->assertNotContains($locale2, $getLangs);
    }
    
    /**
     * @covers API\DAO\UserDao::trackOrganisation
     */
    public function testTrackOrganisation()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $userId = $insertedUser->getId();
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $orgId = $insertedOrg->getId();
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $trackOrg = API\DAO\UserDao::trackOrganisation($userId, $orgId);
        $this->assertEquals("1", $trackOrg);
        $tryTrackOrgAgain = API\DAO\UserDao::trackOrganisation($userId, $orgId);
        $this->assertEquals("0", $tryTrackOrgAgain);
    }
    
    /**
     * @covers API\DAO\UserDao::unTrackOrganisation
     */
    public function testUnTrackOrganisation()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $userId = $insertedUser->getId();
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $orgId = $insertedOrg->getId();
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $trackOrg = API\DAO\UserDao::trackOrganisation($userId, $orgId);
        $this->assertEquals("1", $trackOrg);
        
        $unTrackOrg = API\DAO\UserDao::unTrackOrganisation($userId, $orgId);
        $this->assertEquals("1", $unTrackOrg);
    }
    
    /**
     * @covers API\DAO\UserDao::getTrackedOrganisations
     */
    public function testGetTrackedOrganisations()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $userId = $insertedUser->getId();
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $org = UnitTestHelper::createOrg();
        $insertedOrg = API\DAO\OrganisationDao::insertAndUpdate($org);
        $orgId = $insertedOrg->getId();
        $this->assertNotNull($insertedOrg);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg);
        
        $org2 = UnitTestHelper::createOrg(null,"Bunnyland");
        $insertedOrg2 = API\DAO\OrganisationDao::insertAndUpdate($org2);
        $orgId2 = $insertedOrg2->getId();
        $this->assertNotNull($insertedOrg2);
        $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $insertedOrg2);
        
        $trackOrg = API\DAO\UserDao::trackOrganisation($userId, $orgId);
        $this->assertEquals("1", $trackOrg);
        $trackOrg2 = API\DAO\UserDao::trackOrganisation($userId, $orgId2);
        $this->assertEquals("1", $trackOrg2);
        
        $userTrackedOrgs = API\DAO\UserDao::getTrackedOrganisations($userId);
        $this->assertCount(2, $userTrackedOrgs);
        foreach ($userTrackedOrgs as $trackedOrg) {
            $this->assertInstanceOf(UnitTestHelper::PROTO_ORG, $trackedOrg);
        }
    }
    
    /**
     * @covers API\DAO\UserDao::getUserRealName
     */
    public function testGetUserRealName()
    {
        UnitTestHelper::teardownDb();
        
        $user = UnitTestHelper::createUser();
        $insertedUser = API\DAO\UserDao::save($user);
        $userId = $insertedUser->getId();
        $this->assertNotNull($insertedUser);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER, $insertedUser);
        
        $userInfo = UnitTestHelper::createUserPersonalInfo($insertedUser->getId());
        $userInfo->setReceiveCredit(1);
        $insertedInfo = API\DAO\UserDao::savePersonalInfo($userInfo);
        $this->assertNotNull($insertedInfo);
        $this->assertInstanceOf(UnitTestHelper::PROTO_USER_INFO, $insertedInfo);
        $getName = API\DAO\UserDao::getUserRealName($userId);
        $infoName = $insertedInfo->getFirstName()." ".$insertedInfo->getLastName();
        $this->assertEquals($infoName, $getName);
    }
}
