<?php

namespace Mgilet\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class NotifiableNotification
 * @package Mgilet\NotificationBundle\Entity
 *
 * @ORM\Entity(repositoryClass="Mgilet\NotificationBundle\Entity\Repository\NotifiableNotificationRepository")
 *
 */
class NotifiableNotification implements \JsonSerializable
{
    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var boolean
     * @ORM\Column(name="seen", type="boolean")
     */
    protected $seen;

    /**
     * @var boolean
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true)
     */
    protected $is_deleted = false;

    /**
     * @var Notification
     * @ORM\ManyToOne(targetEntity="Mgilet\NotificationBundle\Entity\Notification", inversedBy="notifiableNotifications", cascade={"persist"})
     */
    protected $notification;

    /**
     * @var NotifiableEntity
     * @ORM\ManyToOne(targetEntity="Mgilet\NotificationBundle\Entity\NotifiableEntity", inversedBy="notifiableNotifications", cascade={"persist"})
     *
     */
    protected $notifiableEntity;

    /**
     * AbstractNotification constructor.
     */
    public function __construct()
    {
        $this->seen = false;
    }

    /**
     * @return int Notification Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean Seen status of the notification
     */
    public function isSeen()
    {
        return $this->seen;
    }

    /**
     * @param boolean $isSeen Seen status of the notification
     * @return $this
     */
    public function setSeen($isSeen)
    {
        $this->seen = $isSeen;

        return $this;
    }

    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param Notification $notification
     *
     * @return NotifiableNotification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * @return NotifiableEntity
     */
    public function getNotifiableEntity()
    {
        return $this->notifiableEntity;
    }

    /**
     * @param NotifiableEntity $notifiableEntity
     *
     * @return NotifiableNotification
     */
    public function setNotifiableEntity(NotifiableEntity $notifiableEntity = null)
    {
        $this->notifiableEntity = $notifiableEntity;

        return $this;
    }

    /**
     * @param bool $v
     * @return NotifiableNotification
     */
    public function setIsDeleted(bool $v)
    {
        $this->is_deleted = $v;
        return $this;
    }
    /**
     * @return bool|null
     */
    public function getIsDeleted():?bool
    {
        return $this->is_deleted;
    }

    public function isDeleted():?bool
    {
        return $this->getIsDeleted();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id'           => $this->getId(),
            'seen'         => $this->isSeen(),
            'deleted' => $this->isDeleted(),
            'notification' => $this->getNotification(),
            // for the notifiable, we serialize only the id:
            // - we don't need not want the FQCN exposed
            // - most of the time we will have a proxy and don't want to trigger lazy loading
            'notifiable'   => [ 'id' => $this->getNotifiableEntity()->getId() ]
        ];
    }
}
