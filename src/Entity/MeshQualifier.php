<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\CreatedAtEntity;
use Doctrine\ORM\Mapping as ORM;
use App\Annotation as IS;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Traits\NameableEntity;
use App\Traits\IdentifiableEntity;
use App\Traits\StringableIdEntity;
use App\Traits\TimestampableEntity;
use App\Repository\MeshQualifierRepository;

/**
 * Class MeshQualifier
 *
 * @ORM\Table(name="mesh_qualifier")
 * @ORM\Entity(repositoryClass=MeshQualifierRepository::class)
 *
 * @IS\Entity
 */
class MeshQualifier implements MeshQualifierInterface
{
    use IdentifiableEntity;
    use TimestampableEntity;
    use NameableEntity;
    use StringableIdEntity;
    use CreatedAtEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="mesh_qualifier_uid", type="string", length=12)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @Assert\Type(type="string")
     *
     * @IS\Expose
     * @IS\Type("string")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=60)
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(
     *      min = 1,
     *      max = 60
     * )
     *
     * @IS\Expose
     * @IS\Type("string")
    */
    protected $name;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @IS\Expose
     * @IS\ReadOnly
     * @IS\Type("dateTime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @IS\Expose
     * @IS\ReadOnly
     * @IS\Type("dateTime")
     */
    protected $updatedAt;

    /**
     * @var ArrayCollection|MeshDescriptorInterface[]
     *
     * @ORM\ManyToMany(targetEntity="MeshDescriptor", inversedBy="qualifiers")
     * @ORM\JoinTable(name="mesh_descriptor_x_qualifier",
     *   joinColumns={
     *     @ORM\JoinColumn(name="mesh_qualifier_uid", referencedColumnName="mesh_qualifier_uid")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="mesh_descriptor_uid", referencedColumnName="mesh_descriptor_uid")
     *   }
     * )
     * @ORM\OrderBy({"id" = "ASC"})
     *
     * @IS\Expose
     * @IS\Type("entityCollection")
     */
    protected $descriptors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->descriptors = new ArrayCollection();
    }

    /**
     * @param Collection $descriptors
     */
    public function setDescriptors(Collection $descriptors)
    {
        $this->descriptors = $descriptors;

        foreach ($descriptors as $descriptor) {
            $this->addDescriptor($descriptor);
        }
    }

    /**
     * @param MeshDescriptorInterface $descriptor
     */
    public function addDescriptor(MeshDescriptorInterface $descriptor)
    {
        if (!$this->descriptors->contains($descriptor)) {
            $this->descriptors->add($descriptor);
        }
    }

    /**
     * @param MeshDescriptorInterface $descriptor
     */
    public function removeDescriptor(MeshDescriptorInterface $descriptor)
    {
        $this->descriptors->removeElement($descriptor);
    }

    /**
     * @return ArrayCollection|MeshDescriptorInterface[]
     */
    public function getDescriptors()
    {
        return $this->descriptors;
    }
}
