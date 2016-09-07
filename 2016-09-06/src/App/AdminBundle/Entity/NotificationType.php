<?php

namespace App\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * NotificationType
 *
 * @ORM\Table(name="notificationType_tb")
 * @ORM\Entity(repositoryClass="App\AdminBundle\Entity\NotificationTypeRepository")
 */
class NotificationType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $icon;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $color;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var boolean
     *
     * @ORM\Column(name="onBody", type="boolean")
     */
    private $onBody;

    /**
     * @ORM\ManyToMany(targetEntity="Notification", inversedBy="notificationTypes")
     * @ORM\JoinTable(name="notificationType_notification_tb", joinColumns={@ORM\JoinColumn
    (name="notificationType_id", referencedColumnName="id")}, inverseJoinColumns=
    {@ORM\JoinColumn(name="notification_id", referencedColumnName="id")})
     */
    private $notifications;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="notificationtypes")
     */
    private $users;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->users = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return NotificationType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return NotificationType
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Add notification
     *
     * @param \App\AdminBundle\Entity\Notification $notification
     * @return NotificationType
     */
    public function addNotification(Notification $notification)
    {
        $this->notifications[] = $notification;
        return $this;
    }

    /**
     * Remove notification
     *
     * @param \App\AdminBundle\Entity\Notification $notification
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Add user
     *
     * @param \App\AdminBundle\Entity\User $user
     * @return NotificationType
     */
    public function addUser(\App\AdminBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \App\AdminBundle\Entity\User $user
     */
    public function removeUser(\App\AdminBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return NotificationType
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return NotificationType
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return NotificationType
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getOnBody()
    {
        return $this->onBody;
    }

    /**
     * @param boolean $onBody
     * @return NotificationType
     */
    public function setOnBody($onBody)
    {
        $this->onBody = $onBody;
        return $this;
    }
}
