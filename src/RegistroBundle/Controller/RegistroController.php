<?php

namespace RegistroBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RegistroBundle\Entity\Registro;
use RegistroBundle\Form\RegistroType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Registro controller.
 *
 * @Route("/registro")
 */
class RegistroController extends Controller
{

    /**
     * Lists all Registro entities.
     *
     * @Route("/", name="registro_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $registros = $em->getRepository('RegistroBundle:Registro')->findAll();

        return $this->render('registro/index.html.twig', array(
            'registros' => $registros,
        ));
    }


    /**
     * Creates a new Registro entity.
     *
     * @Route("/new", name="registro_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $registro = new Registro();
        $form = $this->createForm('RegistroBundle\Form\RegistroType', $registro);

        $form->remove('activo');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $registro->setCreatedAt(new \DateTime());
            $em->persist($registro);
            $em->flush();

            // Obtiene correo y msg de la forma de contacto
            $mailer = $this->get('mailer');

            $message = \Swift_Message::newInstance()
                ->setSubject('Centro de Ciencias Matemáticas - Acuse de recibo')
                ->setFrom('webmaster@matmor.unam.mx')
                ->setTo(array($registro->getMail()))
                ->setBcc(array('gerardo@matmor.unam.mx'))
                ->setBody($this->renderView('registro/mail.txt.twig', array('entity' => $registro)))
            ;
            $mailer->send($message);

            return $this->render('registro/confirm.html.twig', array('id' => $registro->getId(),'entity'=>$registro));

            //return $this->redirectToRoute('registro_show', array('id' => $registro->getId()));
        }

        return $this->render('registro/new.html.twig', array(
            'registro' => $registro,
            'form' => $form->createView(),
        ));
    }



    /**
     * Finds and displays a Registro entity.
     *
     * @Route("/{id}", name="registro_show")
     * @Method("GET")
     */
    public function showAction(Registro $registro)
    {
        $deleteForm = $this->createDeleteForm($registro);
        $tipo = $registro->getTipo();


        if( ($this->isGranted('ROLE_COMPUTO', $registro) && $tipo == 'docencia') ||
            ($this->isGranted('ROLE_DOCENCIA', $registro) && $tipo == 'cómputo') ){

            throw $this->createAccessDeniedException('No disponible');
        }

        return $this->render('registro/show.html.twig', array(
            'registro' => $registro,
            'delete_form' => $deleteForm->createView(),
        ));


    }

    /**
     * Displays a form to edit an existing Registro entity.
     *
     * @Route("/{id}/edit", name="registro_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Registro $registro)
    {
        $deleteForm = $this->createDeleteForm($registro);
        $editForm = $this->createForm('RegistroBundle\Form\RegistroType', $registro);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($registro);
            $em->flush();

            return $this->redirectToRoute('registro_edit', array('id' => $registro->getId()));
        }

        return $this->render('registro/edit.html.twig', array(
            'registro' => $registro,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Registro entity.
     *
     * @Route("/{id}", name="registro_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Registro $registro)
    {
        $form = $this->createDeleteForm($registro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($registro);
            $em->flush();
        }

        return $this->redirectToRoute('registro_index');
    }

    /**
     * Creates a form to delete a Registro entity.
     *
     * @param Registro $registro The Registro entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Registro $registro)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('registro_delete', array('id' => $registro->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }





}
