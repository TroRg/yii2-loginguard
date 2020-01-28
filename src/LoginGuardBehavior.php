<?php

namespace trorg\yii2\loginguard;

use yii\base\{ Model, InvalidConfigException };
use yii\caching\CacheInterface;

class LoginGuardBehavior extends \yii\base\Behavior
{
    /**
     * @var int Maximum login attempts in login period
     */
    public $attempts = 3;

    /**
     * @var int Login period duration
     */
    public $loginPeriod = 300;

    /**
     * @var int Block login period duration
     */
    public $blockPeriod = 600;

    /**
     * @var string Username attribute name in form
     */
    public $usernameAttribute = 'login';

    /**
     * @var string Password attribute name in form
     */
    public $passwordAttribute = 'password';

    /**
     * @var string $message
     */
    public $message = 'You have exceeded login attempts. Try again later';

    /**
     * @var string | \yii\caching\CacheInterface $cache
     */
    public $cache = 'cache';

    /**
     * @var int Cached attempts for user
     */
    private $_record = 0;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->cache = is_string($this->cache) ? \Yii::$app->get($this->cache) : $this->cache;

        if (!$this->cache || !$this->cache instanceof CacheInterface) {
            throw new InvalidConfigException('"cache" is mandatory and must be instance of "\yii\caching\CacheInterface".');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            Model::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            Model::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        $this->_record = (int)$this->cache->get($this->getKey());
        if ($this->_record >= $this->attempts) {
            $this->owner->addError($this->passwordAttribute, \Yii::t('user', $this->message));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterValidate()
    {
        if ($this->owner->hasErrors($this->passwordAttribute)) {
            $this->_record++;
            $ttl = $this->_record >= $this->attempts ? $this->blockPeriod : $this->loginPeriod;
            $this->cache->set($this->getKey(), $this->_record, $ttl);
        }
    }

    /**
     * Get cache key for username
     *
     * @return string
     */
    public function getKey(): string
    {
        return sprintf('login_guard_%s', $this->owner->{$this->usernameAttribute});
    }
}

