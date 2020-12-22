<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Form\AddressType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AddressesController extends Controller
{
    /**
     * @Route("/admin/address-book", name="address_book")
     * @return Response
     */
    public function listAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $addresses = $this->getDoctrine()
            ->getRepository(Address::class)
            ->findAllOrderedByName();

        return $this->render('@App/addresses/list.html.twig', ['addresses' => $addresses]);
    }

    /**
     * @Route("/admin/address/{id}", name="address_detail", requirements={"id" = "\d+"})
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $address = $this->getDoctrine()
            ->getRepository(Address::class)
            ->findOneById($id);

        if ($address === null) {
            throw $this->createNotFoundException('The address does not exist');
        }

        return $this->render('@App/addresses/detail.html.twig', ['address' => $address]);
    }

    /**
     * @Route("/admin/address/add", name="address_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            $this->addFlash(
                'notice',
                'Address successfully added!'
            );

            return $this->redirectToRoute('address_book');
        }

        return $this->render('@App/addresses/add.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Add'
        ));
    }

    /**
     * @Route("/admin/address/edit/{id}", name="address_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $address = $this->getDoctrine()
            ->getRepository(Address::class)
            ->findOneById($id);

        if ($address === null) {

            $this->addFlash(
                'notice',
                'Address not found!'
            );
            return $this->redirectToRoute('address_book');

        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash(
                'notice',
                'Address successfully edited!'
            );

            return $this->redirectToRoute('address_book');
        }

        return $this->render('@App/addresses/add.html.twig', array(
            'form'  => $form->createView(),
            'title' => 'Edit'
        ));
    }

    /**
     * @Route("/admin/address/remove/{id}", name="address_remove", requirements={"id" = "\d+"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removeAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $address = $this->getDoctrine()
            ->getRepository(Address::class)
            ->findOneById($id);

        if ($address === null) {

            if ($address === null) {
                throw $this->createNotFoundException('The address does not exist');
            }

        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        $this->addFlash(
            'notice',
            'Address successfully deleted!'
        );

        return $this->redirectToRoute('address_book');
    }

}
