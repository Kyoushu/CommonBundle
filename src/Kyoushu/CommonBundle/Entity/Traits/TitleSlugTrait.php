<?php

namespace Kyoushu\CommonBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\MappedSuperclass()
 */
trait TitleSlugTrait
{

    use TitleTrait;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Slug(fields={"title"}, updatable=true)
     */
    protected $slug;

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

}