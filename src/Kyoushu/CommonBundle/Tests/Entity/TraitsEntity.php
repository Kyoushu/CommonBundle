<?php

namespace Kyoushu\CommonBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kyoushu\CommonBundle\Entity\Traits\IdTrait;
use Kyoushu\CommonBundle\Entity\Traits\SummaryTrait;
use Kyoushu\CommonBundle\Entity\Traits\TimestampTrait;
use Kyoushu\CommonBundle\Entity\Traits\TitleSlugTrait;

/**
 * @ORM\Entity()
 */
class TraitsEntity
{

    use IdTrait;
    use TitleSlugTrait;
    use SummaryTrait;
    use TimestampTrait;

}