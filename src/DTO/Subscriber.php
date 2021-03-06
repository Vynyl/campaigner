<?php

namespace Vynyl\Campaigner\DTO;

class Subscriber implements Postable
{
    private $emailAddress = "";

    private $customFields;

    private $sourceId = 0;

    private $publications = [];

    private $lists = [];

    private $autoresponderId = 0;

    private $orders;

    private $force = false;

    private $forcePublication = false;

    public function __construct()
    {
        $this->customFields = new CustomFieldsCollection();
        $this->orders = new OrdersCollection();
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param mixed $emailAddress
     * @return Subscriber
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @return OrdersCollection
     */
    public function getOrdersCollection()
    {
        return $this->orders;
    }

    /**
     * @return mixed
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @param mixed $sourceId
     * @return Subscriber
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    /**
     * @return CustomFieldsCollection
     */
    public function getCustomFieldsCollection()
    {
        return $this->customFields;
    }

    /**
     * @return array
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * @param array $publications
     * @return Subscriber
     */
    public function addPublication($publicationId)
    {
        $this->publications[] = $publicationId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLists()
    {
        return $this->lists;
    }

    /**
     * @param mixed $lists
     * @return Subscriber
     */
    public function addList($listId)
    {
        $this->lists[] = $listId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutoresponderId()
    {
        return $this->autoresponderId;
    }

    /**
     * @param mixed $autoresponderId
     * @return Subscriber
     */
    public function setAutoresponderId($autoresponderId)
    {
        $this->autoresponderId = $autoresponderId;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isForce()
    {
        return $this->force;
    }

    /**
     * @param boolean $force
     * @return Subscriber
     */
    public function setForce($force)
    {
        $this->force = $force;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isForcePublication()
    {
        return $this->forcePublication;
    }

    /**
     * @param boolean $forcePublication
     * @return Subscriber
     */
    public function setForcePublication($forcePublication)
    {
        $this->forcePublication = $forcePublication;
        return $this;
    }


    public function toPost()
    {
        return [
            'EmailAddress' => $this->getEmailAddress(),
            'CustomFields' => $this->customFields->toArray(),
            // for some reason, including any sort of source ID always fails on an initial add with addOrImportMultiple, but works on updates...
            //'SourceID' => $this->getSourceId(),
            'Publications' => $this->getPublications(),
            'Lists' => $this->getLists(),
            'Orders' => $this->orders->toArray(),
            'Force' => $this->isForce(),
            'ForcePublications' => $this->isForcePublication(),
        ];
    }
}
