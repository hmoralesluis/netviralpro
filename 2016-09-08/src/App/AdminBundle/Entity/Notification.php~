<?php

namespace App\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Notification
 *
 * @ORM\Table(name="notification_tb")
 * @ORM\Entity(repositoryClass="App\AdminBundle\Entity\NotificationRepository")
 */
class Notification
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
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $label;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\NotBlank()
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity="NotificationType", mappedBy="notifications")
     */
    private $notificationTypes;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="notifications")
     * @ORM\JoinTable(name="notification_user_tb", joinColumns={@ORM\JoinColumn
    (name="notification_id", referencedColumnName="id")}, inverseJoinColumns=
    {@ORM\JoinColumn(name="user_id", referencedColumnName="id")})
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->notificationTypes = new ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     * @return Notification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add notificationTypes
     *
     * @param \App\AdminBundle\Entity\NotificationType $type
     * @return Notification
     */
    public function addNotificationType(NotificationType $type)
    {
        $this->notificationTypes[] = $type;

        return $this;
    }

    /**
     * Remove notificationType
     *
     * @param \App\AdminBundle\Entity\NotificationType $type
     */
    public function removeNotificationType(NotificationType $type)
    {
        $this->notificationTypes->removeElement($type);
    }

    /**
     * Get notificationTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificationTypes()
    {
        return $this->notificationTypes;
    }

    /**
     * Add user
     *
     * @param \App\AdminBundle\Entity\User $user
     * @return Notification
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Notification
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return Notification
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Notification
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
}
