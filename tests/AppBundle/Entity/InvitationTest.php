<?php
/**
 * Created by PhpStorm.
 * User: echudniy
 * Date: 05.09.19
 * Time: 12:26
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Invitation;
use PHPUnit\Framework\TestCase;

class InvitationTest extends TestCase
{
    public function testSettingTitle()
    {
        $invitation = new Invitation();

        $this->assertTrue('' == $invitation->getTitle());

        $invitation->setTitle('New invitation');
        $this->assertSame('New invitation', $invitation->getTitle());
    }
}