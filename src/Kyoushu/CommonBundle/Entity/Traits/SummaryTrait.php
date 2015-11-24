<?php

namespace Kyoushu\CommonBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
trait SummaryTrait
{

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $summary;

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return $this
     */
    public function setSummary($summary = null)
    {
        $this->summary = $summary;
        return $this;
    }

}