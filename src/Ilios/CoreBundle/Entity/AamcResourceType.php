<?php

namespace Ilios\CoreBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ilios\CoreBundle\Traits\DescribableEntity;
use Ilios\CoreBundle\Traits\IdentifiableEntity;
use Ilios\CoreBundle\Traits\StringableIdEntity;
use Ilios\CoreBundle\Traits\TitledEntity;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AamcResourceType
 * @package Ilios\CoreBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="aamc_resource_type")
 *
 * @JMS\ExclusionPolicy("all")
 * @JMS\AccessType("public_method")
 */
class AamcResourceType implements AamcResourceTypeInterface
{
    use IdentifiableEntity;
    use DescribableEntity;
    use TitledEntity;
    use StringableIdEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="resource_type_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Assert\Type(type="integer")
     *
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("id")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)

     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(
     *      min = 1,
     *      max = 200
     * )
     *
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $title;

    /**
     * @ORM\Column(name="description", type="text")
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(
     *      min = 1,
     *      max = 65000
     * )
     *
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $description;

    /**
     * @var ArrayCollection|TermInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Term", mappedBy="aamcResourceTypes")
     *
     * @JMS\Expose
     * @JMS\Type("array<string>")
     */
    protected $terms;


    public function __construct()
    {
        $this->terms = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    public function setTerms(Collection $terms)
    {
        $this->terms = new ArrayCollection();

        foreach ($terms as $term) {
            $this->addTerm($term);
        }
    }

    /**
     * @inheritdoc
     */
    public function addTerm(TermInterface $term)
    {
        if (!$this->terms->contains($term)) {
            $this->terms->add($term);
            $term->addAamcResourceType($this);
        }
    }

    /**
     * @inheritdoc
     */
    public function getTerms()
    {
        return $this->terms;
    }
}
