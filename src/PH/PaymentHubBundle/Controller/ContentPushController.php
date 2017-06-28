<?php

namespace PH\PaymentHubBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/subscriptions/http-push")
 */
class ContentPushController extends Controller
{
    /**
     * @Route("/retrieve", name="ph_subscriptions_httppush_retrieve")
     */
    public function indexAction(Request $request)
    {
        return new JsonResponse(json_decode($request->getContent(), true));
    }
}