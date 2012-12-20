<?php

require_once __DIR__."/../models/MembershipRequest.php";
require_once __DIR__."/../models/ArchivedTask.php";
require_once __DIR__."/../models/PasswordResetRequest.php";
require_once __DIR__."/../models/PasswordReset.php";
require_once __DIR__."/../models/Register.php";
require_once __DIR__."/../models/Country.php";
require_once __DIR__."/../models/Language.php";
require_once __DIR__."/../models/Login.php";
require_once __DIR__."/../models/Badge.php";
require_once __DIR__."/../models/Tag.php";
require_once __DIR__."/../models/Organisation.php";
require_once __DIR__."/../models/TaskMetadata.php";
require_once __DIR__."/../models/User.php";
require_once __DIR__."/../models/Task.php";

class ModelFactory
{
    public static function buildModel($modelName, $modelData)
    {
        $ret = null;

        switch($modelName)
        {
            case "MembershipRequest" :
                $ret = ModelFactory::generateMembershipRequest($modelData);
                break;
            case "ArchivedTask" :
                $ret = ModelFactory::generateArchivedTask($modelData);
                break;
            case "PasswordReset" :
                $ret = ModelFactory::generatePasswordReset($modelData);
                break;
            case "PasswordResetRequest" :
                $ret = ModelFactory::generatePasswordResetRequest($modelData);
                break;
            case "Register" :
                $ret = ModelFactory::generateRegister($modelData);
                break;
            case "Country" :
                $ret = ModelFactory::generateCountry($modelData);
                break;
            case "Language" :
                $ret = ModelFactory::generateLanguage($modelData);
                break;
            case "Login" :
                $ret = ModelFactory::generateLogin($modelData);
                break;
            case "Badge" :
                $ret = ModelFactory::generateBadge($modelData);
                break;
            case "Tag" :
                $ret = ModelFactory::generateTag($modelData);
                break ;
            case "Organisation" :
                $ret = ModelFactory::generateOrganisation($modelData);
                break;
            case "TaskMetadata" :
                $ret = ModelFactory::generateTaskMetadata($modelData);
                break;
            case "User" :
                $ret = ModelFactory::generateUser($modelData);
                break;
            case "Task" :
                $ret = ModelFactory::generateTask($modelData);
                break;
            default :
                echo "Unable to build model $modelName";
        }

        return $ret;
    }

    private static function generateMembershipRequest($modelData)
    {
        $ret = new MembershipRequest();
        $ret ->setId($modelData["id"]);
        if (isset($modelData['user_id'])) {
            $ret->setUserId($modelData['user_id']);
        }
        if (isset($modelData['org_id'])) {
            $ret->setOrgId($modelData['org_id']);
        }
        if (isset($modelData['request-datetime'])) {
            $ret->setRequestTime($modelData['request-datetime']);
        }
        return $ret;
    }

    private static function generateArchivedTask($modelData)
    {
        $ret = new ArchivedTask();

        if (isset($modelData['id'])) {
            $ret->setArchiveId($modelData['id']);
        }
        if (isset($modelData['task_id'])) {
            $ret->setTaskId($modelData['task_id']);
        }
        if (isset($modelData['org_id'])) {
            $ret->setOrgId($modelData['org_id']);
        }
        if (isset($modelData['title'])) {
            $ret->setTitle($modelData['title']);
        }
        if (isset($modelData['word-count'])) {
            $ret->setWordCount($modelData['word-count']);
        }
        if (isset($modelData['source_id'])) {
            $ret->setSourceId($modelData['source_id']);
        }
        if (isset($modelData['target_id'])) {
            $ret->setTargetId($modelData['target_id']);
        }
        if (isset($modelData['created-time'])) {
            $ret->setCreatedTime($modelData['created-time']);
        }
        if (isset($modelData['archived-time'])) {
            $ret->setArchivedTime($modelData['archived-time']);
        }
        if (isset($modelData['impact'])) {
            $ret->setImpact($modelData['impact']);
        }
        if (isset($modelData['reference-page'])) {
            $ret->setReferencePage($modelData['reference-page']);
        }

        return $ret;
    }

    private static function generatePasswordReset($modelData)
    {
        $ret = new PasswordReset();
        
        if (isset($modelData['password'])) {
            $ret->setPassword($modelData['password']);
        }
        if (isset($modelData['key'])) {
            $ret->setKey($modelData['key']);
        }

        return $ret;
    }

    private static function generatePasswordResetRequest($modelData)
    {
        $ret = new PasswordResetRequest();

        if (isset($modelData['user_id'])) {
            $ret->setUserId($modelData['user_id']);
        }
        if (isset($modelData['uid'])) {
            $ret->setKey($modelData['uid']);
        }
        if (isset($modelData['request-time'])) {
            $ret->setRequestTime($modelData['request-time']);
        }

        return $ret;
    }

    private static function generateRegister($modelData)
    {
        $ret = new Register();

        if (isset($modelData['email'])) {
            $ret->setEmail($modelData['email']);
        }
        if (isset($modelData['password'])) {
            $ret->setPassword($modelData['password']);
        }

        return $ret;
    }

    private static function generateCountry($modelData)
    {
        $ret = new Country();

        if (isset($modelData['id'])) {
            $ret->setId($modelData['id']);
        }
        if (isset($modelData['code'])) {
            $ret->setCode($modelData['code']);
        }
        if (isset($modelData['country'])) {
            $ret->setName($modelData['country']);
        }

        return $ret;
    }

    private static function generateLanguage($modelData)
    {
        $ret = new Language();

        if (isset($modelData['id'])) {
            $ret->setId($modelData['id']);
        }
        if (isset($modelData['code'])) {
            $ret->setCode($modelData['code']);
        }
        if (isset($modelData['language'])) {
            $ret->setName($modelData['language']);
        }

        return $ret;
    }

    private static function generateLogin($modelData)
    {
        $ret = new Login();

        if (isset($modelData['email'])) {
            $ret->setEmail($modelData['email']);
        }
        if (isset($modelData['password'])) {
            $ret->setPassword($modelData['password']);
        }

        return $ret;
    }

    private static function generateBadge($modelData)
    {
        $ret = new Badge();

        if (isset($modelData['id'])) {
            $ret->setId($modelData['id']);
        }
        if (isset($modelData['title'])) {
            $ret->setTitle($modelData['title']);
        }
        if (isset($modelData['description'])) {
            $ret->setDescription($modelData['description']);
        }
        if (isset($modelData['owner_id'])) {
            $ret->setOwnerId($modelData['owner_id']);
        }

        return $ret;
    }

    private static function generateTag($modelData)
    {
        $ret = new Tag();

        if (isset($modelData['id'])) {
            $ret->setId($modelData['id']);
        }
        if (isset($modelData['label'])) {
            $ret->setLabel($modelData['label']);
        }

        return $ret;
    }

    private static function generateOrganisation($modelData)
    {
        $ret = new Organisation();

        if (isset($modelData['id'])) {
            $ret->setId($modelData['id']);
        }
        if (isset($modelData['name'])) {
            $ret->setName($modelData['name']);
        }
        if (isset($modelData['home-page'])) {
            $ret->setHomePage($modelData['home-page']);
        }
        if (isset($modelData['biography'])) {
            $ret->setBiography($modelData['biography']);
        }

        return $ret;
    }

    private static function generateTaskMetadata($modelData)
    {
        $ret = new TaskMetadata();

        if (isset($modelData['task_id'])) {
            $ret->setId($modelData['task_id']);
        }
        if (isset($modelData['version_id'])) {
            $ret->setVersion($modelData['version_id']);
        }
        if (isset($modelData['filename'])) {
            $ret->setFilename($modelData['filename']);
        }
        if (isset($modelData['content-type'])) {
            $ret->setContentType($modelData['content-type']);
        }
        if (isset($modelData['user_id'])) {
            $ret->setUserId($modelData['user_id']);
        }
        if (isset($modelData['upload-time'])) {
            $ret->setUploadTime($modelData['upload-time']);
        }

        return $ret;
    }

    private static function generateUser($modelData)
    {
        $ret = new User();

        if (isset($modelData['id'])) {
            $ret->setUserId($modelData['id']);
        }
        if (isset($modelData['email'])) {
            $ret->setEmail($modelData['email']);
        }
        if (isset($modelData['nonce'])) {
            $ret->setNonce($modelData['nonce']);
        }
        if (isset($modelData['password'])) {
            $ret->setPassword($modelData['password']);
        }
        if (isset($modelData['display-name'])) {
            $ret->setDisplayName($modelData['display-name']);
        }
        if (isset($modelData['biography'])) {
            $ret->setBiography($modelData['biography']);
        }
        if (isset($modelData['language_id'])) {
            $ret->setNativeLangId($modelData['language_id']);
        }
        if (isset($modelData['country_id'])) {
            $ret->setNativeRegionId($modelData['country_id']);
        }
        if (isset($modelData['created-time'])) {
            $ret->setCreatedTime($modelData['created-time']);
        }

        return $ret;
    }

    private static function generateTask($modelData)
    {
        $ret = new Task();

        if (isset($modelData['id'])) {
            $ret->setId($modelData['id']);
        }
        if (isset($modelData['organisation_id'])) {
            $ret->setOrgId($modelData['organisation_id']);
        }
        if (isset($modelData['title'])) {
            $ret->setTitle($modelData['title']);
        }
        if (isset($modelData['word-count'])) {
            $ret->setWordCount($modelData['word-count']);
        }
        if (isset($modelData['source_id'])) {
            $ret->setSourceLangId($modelData['source_id']);
        }
        if (isset($modelData['target_id'])) {
            $ret->setTargetLangId($modelData['target_id']);
        }
        if (isset($modelData['created-time'])) {
            $ret->setCreatedTime($modelData['created-time']);
        }
        if (isset($modelData['impact'])) {
            $ret->setImpact($modelData['impact']);
        }
        if (isset($modelData['reference-page'])) {
            $ret->setReferencePage($modelData['reference-page']);
        }
        if (isset($modelData['country_id-source'])) {
            $ret->setSourceRegionId($modelData['country_id-source']);
        }
        if (isset($modelData['country_id-target'])) {
            $ret->setTargetRegionId($modelData['country_id-target']);
        }
        if (isset($modelData['tags'])) {
            foreach ($modelData['tags'] as $tag) {
                $ret->addTags($tag);
            }
        }

        return $ret;
    }
}
