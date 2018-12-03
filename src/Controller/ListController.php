<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\ShoppingList;

class ListController extends Controller {

    /**
    * @Route("/list", name="shopping_list")
    * @Method({"GET"})
    */
    public function index()
    {
        $items = $this->getDoctrine()->getRepository(ShoppingList::class)->findAll();

        return $this->render('list/index.html.twig', array('items' => $items));
    }

    /**
    * @Route("/edit/{id}", name="edit")
    * @Method({"GET", "POST"})
    */
    public function edit(Request $request, $id)
    {
        $item = new ShoppingList();
        $item = $this->getDoctrine()->getRepository(ShoppingList::class)->find($id);

        $form = $this->createFormBuilder($item)
            ->add('item', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('quantity', TextType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
            ->add('unit', TextType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-block btn-primary mt-3')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('shopping_list');
        }

        return $this->render('list/new.html.twig', array('form' => $form->createView()));

    }

    /**
    * @Route("/delete/{id}", name="delete")
    * @Method({"DELETE"})
    */
    public function delete(Request $request, $id)
    {
        $item = new ShoppingList();
        $item = $this->getDoctrine()->getRepository(ShoppingList::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        $items = $this->getDoctrine()->getRepository(ShoppingList::class)->findAll();
        return $this->render('list/index.html.twig', array('items' => $items));
    }

    /**
    * @Route("/add", name="add")
    * @Method({"GET", "POST"})
    */
    public function addItem(Request $request)
    {
        $item = new ShoppingList();

        $form = $this->createFormBuilder($item)
            ->add('item', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('quantity', TextType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
            ->add('unit', TextType::class, array('required' => false, 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Submit', 'attr' => array('class' => 'btn btn-block btn-primary mt-3')))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $list = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($list);
            $entityManager->flush();

            return $this->redirectToRoute('shopping_list');
        }

        return $this->render('list/new.html.twig', array('form' => $form->createView()));
    }
}