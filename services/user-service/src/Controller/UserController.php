<?php
namespace App\Controller;


use Microservices\SharedContracts\User\CreateUserRequest;
use Microservices\SharedContracts\User\UserResponse;
use Microservices\SharedEvents\UserRegisteredEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

}
