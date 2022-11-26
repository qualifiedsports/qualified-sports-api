<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Group
 *
 * @package AppBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="fos_group")
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"group", "group-read"}},
 *     "denormalization_context"={"groups"={"group", "group-write"}},
 *     "filters"={"group.order_filter"}
 * })
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"group"})
     */
    protected $id;

    /**
     * @Groups({"group"})
     */
    protected $name;

    /**
     * @Groups({"group"})
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @Groups({"group"})
     */
    protected $roles;

    /**
     * @Groups({"group"})
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="groups")
     */
    protected $users;

    /**
     * Group constructor.
     */
    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles);

        $this->users = new ArrayCollection();
    }

    /**
     * Add user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Group
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Group
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
