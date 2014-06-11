<?php

namespace Tagged\FoosballBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tagged\FoosballBundle\Entity\Game;
use Tagged\FoosballBundle\Entity\Player;
use Tagged\FoosballBundle\Form\GameType;

/**
 * Game controller.
 *
 * @Route("/game")
 */
class GameController extends Controller
{

    /**
     * Lists all Game entities.
     *
     * @Route("/", name="game")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TaggedFoosballBundle:Game')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Upload results
     *
     * @Route("/upload", name="game_upload")
     * @Method("GET")
     * @Template()
     */
    public function uploadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $reader = new \EasyCSV\Reader('/Workspace/tagged/web/foosball.csv');

        while ($row = $reader->getRow()) {

            // Player 1
            $player1 = $em->getRepository('TaggedFoosballBundle:Player')->findOneByName($row['Person1']);

            if (!$player1) {
                $player1 = new Player();
                $player1->setName($row['Person1']);
            }

            // Player 2
            $player2 = $em->getRepository('TaggedFoosballBundle:Player')->findOneByName($row['Person2']);

            if (!$player2) {
                $player2 = new Player();
                $player2->setName($row['Person2']);
            }

            // Scores
            if ($row['Score1'] > $row['Score2']) {
                $player1->setScore($player1->getScore() + 1);
            } else {
                $player2->setScore($player2->getScore() + 1);
            }

            // Game
            $entity = new Game();
            $entity->setPlayer1($player1);
            $entity->setScore1((int)$row['Score1']);
            $entity->setPlayer2($player2);
            $entity->setScore2((int)$row['Score2']);

            $em->persist($entity);
            $em->flush();
        }

        return new Response('Scores saved successfully!');
    }


    /**
     * Creates a new Game entity.
     *
     * @Route("/", name="game_create")
     * @Method("POST")
     * @Template("TaggedFoosballBundle:Game:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Game();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('game_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Game entity.
    *
    * @param Game $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Game $entity)
    {
        $form = $this->createForm(new GameType(), $entity, array(
            'action' => $this->generateUrl('game_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Game entity.
     *
     * @Route("/new", name="game_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Game();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Game entity.
     *
     * @Route("/{id}", name="game_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TaggedFoosballBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Game entity.
     *
     * @Route("/{id}/edit", name="game_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TaggedFoosballBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Game entity.
    *
    * @param Game $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Game $entity)
    {
        $form = $this->createForm(new GameType(), $entity, array(
            'action' => $this->generateUrl('game_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Game entity.
     *
     * @Route("/{id}", name="game_update")
     * @Method("PUT")
     * @Template("TaggedFoosballBundle:Game:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TaggedFoosballBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('game_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Game entity.
     *
     * @Route("/{id}", name="game_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TaggedFoosballBundle:Game')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Game entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('game'));
    }

    /**
     * Creates a form to delete a Game entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('game_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
