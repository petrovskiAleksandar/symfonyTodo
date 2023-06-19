<?php
namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ToDoController extends AbstractController {
    public $todoRepository;
    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;        
    }

    public function list ()
    {

        return $this->render('/Todos/List/list_to_dos.html.twig', [
            'notes' => [
                [
                    'noteText' => 'Go to work',
                    'status' => 'not done'
                ],
                [
                    'noteText' => 'Go shopping',
                    'status' => 'done'
                ],
                [
                    'noteText' => 'Work out',
                    'status' => 'not done'
                ]
            ]
        ]);
    }

    public function saveTodo () {
        $product = new Todo;

        $product->setName('Wash');
        $product->setDescritpion('Need to wash my car today');
        $product->setStatus(false);

        $this->todoRepository->save($product);

        return $this->json(['done' => true]);
    }
}