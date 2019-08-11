<?php

namespace AppBundle\Normalizer;

use AppBundle\Entity\Invitation;
use AppBundle\Entity\User;
use AppBundle\Exception\Api\ApiException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class InvitationNormalizer implements DenormalizerInterface
{
    private $doctrine;

    public function __construct(Registry $registry)
    {
        $this->doctrine = $registry;
    }
    
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        /** @var Invitation $invitation */
        $invitation = new $class();
        /** @var User $recipient */
        $recipient = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $data['user_email']]);
        if (!$recipient) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, 'User not found');
        }
        $invitation->setRecipient($recipient);
        if (isset($data['title']) && $data['title']) {
            $invitation->setTitle($data['title']);
        }

        return $invitation;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return Invitation::class == $type;
    }
}
