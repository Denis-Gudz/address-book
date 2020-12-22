<?php

namespace AppBundle\Controller;

use AppBundle\Service\AddressesSerializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Get;
use AppBundle\Entity\Address;

class ApiAddressesController extends Controller
{

    /**
     * @Get("/addresses")
     * Get addresses list
     */
    public function getAddressesAction()
    {
        $addresses = $this->getDoctrine()
            ->getRepository(Address::class)
            ->findAllOrderedByName();

        return new JsonResponse($this->get(AddressesSerializer::class)->serialize($addresses), 200);
    }


    /**
     * @Get("/address/{id}", requirements={"id"="\d+"})
     * @param $id
     * @return JsonResponse
     */
    public function getAddressAction($id)
    {
        $address = $this->getDoctrine()
            ->getRepository(Address::class)
            ->findOneById($id);

        if ($address === null) {

            return new JsonResponse([], 404);
        }

        return new JsonResponse($this->get(AddressesSerializer::class)->serialize($address), 200);
    }

}
