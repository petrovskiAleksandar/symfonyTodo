<?php
namespace App\Controller;

use App\Entity\Todo;
use App\Form\Types\TodoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ToDoController extends AbstractController {
    public $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;        
    }

    public function list (Request $request)
    {
        $todos = $this->entityManager->getRepository(Todo::class)->findAll();

        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();
            
            $this->entityManager->persist($todo);
            $this->entityManager->flush();
        }

        return $this->render('/Todos/index.html.twig', [
            'todos' => $todos,
            'form' => $form->createView()
        ]);
    }

    public function delete ($id): JsonResponse
    {
        $todo = $this->entityManager->getRepository(Todo::class)->findOneBy(['id' => $id]);

        if (is_null($todo)) {
            throw new NotFoundHttpException('Todo not found');
        }

        $this->entityManager->remove($todo);
        $this->entityManager->flush();

        return $this->json(['status' => true]);
    }

    public function show (Request $request, $id): Response
    {
        $todo = $this->entityManager->getRepository(Todo::class)->findOneBy(['id' => $id]);

        if (is_null($todo)) {
            throw new NotFoundHttpException('Todo not found');
        }

        $form = $this->createForm(TodoType::class, $todo);
        $form->add('status', CheckboxType::class, [
            'required' => false
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();

            $this->entityManager->flush();
        }

        return $this->render('/Todos/Edit/edit.html.twig', [
            'todo' => $todo,
            'form' => $form->createView()
        ]);
    }

    public function patch (Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);

        $todo = $this->entityManager->getRepository(Todo::class)->findOneBy(['id' => $id]);

        if (is_null($todo) || is_null($data)) {
            throw new NotFoundHttpException('Todo not found');
        }

        $todo->setStatus($data['status']);

        $this->entityManager->flush();

        return $this->json(['status' => true]);
    }
}