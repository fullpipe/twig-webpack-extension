<?php

// src/Controller/LuckyController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    public function index()
    {
        return $this->render('test/index.html.twig');
    }
}
