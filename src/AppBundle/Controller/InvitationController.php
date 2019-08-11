<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 8/11/19
 * Time: 12:41
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Invitation;
use AppBundle\Exception\Api\ApiException;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvitationController
 *
 * @Route("/api/{version}/invitation", defaults={"version": "v1"}, requirements={"version": "v\d+(\.{1}\d+)?"})
 */
class InvitationController extends Controller
{
    /**
     * @Route("/{type}/list", name="api_invitation_sent", requirements={"type": "sent|received"}, methods={"GET"})
     * @param string $type
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAction(string $type)
    {
        $user = $this->getUser();
        
        if ($type == Invitation::TYPE_SENT){
            $criteria = ['sender' => $user];
        } else {
            $criteria = ['recipient' => $user];
        }
        
        $invitations = $this->getDoctrine()->getRepository(Invitation::class)->findBy($criteria);
        
        return $this->json(['invitations' => $invitations], Response::HTTP_OK);
    }
    
    /**
     * @Route("/new", name="api_invitation_new", methods={"POST"})
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        
        $serializer = $this->get('serializer');

        $invitation = $serializer->deserialize($request->getContent(), Invitation::class, 'json');
        $invitation->setSender($user);
        $validator = $this->get('validator');
        $errors = $validator->validate($invitation);
    
        if ($errors->count()) {
            $message = 'Invalid fields: ';
            $fields = [];
            foreach ($errors as $error) {
                $fields[] = $error->getPropertyPath() . ' (' . $error->getMessage() . ')';
            }
        
            throw new ApiException(Response::HTTP_BAD_REQUEST, $message . implode(', ', $fields));
        }
    
        $em = $this->getDoctrine()->getManager();
        $em->persist($invitation);
        $em->flush();
    
        return $this->json(['invitation' => $invitation], Response::HTTP_CREATED);
    }
    
    /**
     * @Route("/{id}", name="api_invitation_show", requirements={"id": "\d+"}, methods={"GET"})
     * @param Invitation $invitation
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showAction(Invitation $invitation)
    {
        $user = $this->getUser();
        
        if (!$invitation) {
            throw new ApiException(Response::HTTP_NOT_FOUND, 'Invitation not found');
        }
        if ($invitation->getSender()->getId() != $user->getId() && $invitation->getRecipient()->getId() != $user->getId()) {
            throw new ApiException(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        
        return $this->json(['invitation' => $invitation], Response::HTTP_OK);
    }
    
    /**
     * @Route("/{id}", name="api_invitation_show", requirements={"id": "\d+"}, methods={"DELETE"})
     * @param Invitation $invitation
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAction(Invitation $invitation)
    {
        $user = $this->getUser();
        
        if (!$invitation) {
            throw new ApiException(Response::HTTP_NOT_FOUND, 'Invitation not found');
        }
    
        if ($invitation->getSender()->getId() != $user->getId()) {
            throw new ApiException(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        
        try {
            $this->getDoctrine()->getManager()->remove($invitation);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }
        
        return $this->json([
            'success' => [
                'code' => Response::HTTP_OK,
                'message' => 'Invitation was deleted'
            ]
        ], Response::HTTP_OK);
    }
    
    /**
     * @Route("/{id}/{status}", name="api_invitation_status", requirements={"id": "\d+", "status": "allow|decline" }, methods={"PATCH"})
     * @param Invitation $invitation
     * @param string $status
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function statusAction(Invitation $invitation, string $status)
    {
        $user = $this->getUser();
        
        if (!$invitation) {
            throw new ApiException(Response::HTTP_NOT_FOUND, 'Invitation not found');
        }
        
        if ($invitation->getRecipient()->getId() != $user->getId()) {
            throw new ApiException(Response::HTTP_FORBIDDEN, 'Access denied');
        }
        
        try {
            if ($status == Invitation::STATUS_ALLOWED || $status == Invitation::STATUS_DECLINED) {
                $invitation->setStatus($status);
                $this->getDoctrine()->getManager()->persist($invitation);
                $this->getDoctrine()->getManager()->flush();
            }
        } catch (\Exception $exception) {
            throw new ApiException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }
        
        return $this->json([
            'success' => [
                'code' => Response::HTTP_OK,
                'message' => 'Status was changed'
            ]
        ], Response::HTTP_OK);
    }
}