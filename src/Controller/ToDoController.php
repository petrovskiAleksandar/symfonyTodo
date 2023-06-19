<?php
namespace App\Controller;

use App\Entity\Todo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class ToDoController extends AbstractController {
    public $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;        
    }

    public function list ()
    {
        $todos = $this->entityManager->getRepository(Todo::class)->findAll();

        return $this->render('/Todos/index.html.twig', [
            'todos' => $todos
        ]);
    }

    public function post (Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (
            !array_key_exists('name', $data) ||
            !array_key_exists('description', $data) ||
            strlen($data['name']) === 0 ||
            strlen($data['description']) === 0
        ) {
            throw new BadRequestException('Bad request!');
        }

        $product = new Todo();

        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setStatus(false);
        $product->setCreatedOn(new \DateTime());

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->json(['done' => true]);
    }

    public function patch (Request $request, $id) {
        $data = json_decode($request->getContent(), true);

        $todo = $this->entityManager->getRepository(Todo::class)->findOneBy(['id' => $id]);

        $todo->setStatus($data['status']);

        $this->entityManager->flush();

        return $this->json(['done' => true]);
    }
}