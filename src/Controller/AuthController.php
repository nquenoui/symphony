<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * @Route("/register", name="auth.register")
     */
    public function register(Request $request,ManagerRegistry $doctrine,UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $userForm = $this->createForm(RegisterType::class, $user);
        
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
             $entityManager = $doctrine->getManager();
             // tell Doctrine you want to (eventually) save the Product (no queries yet)
             $entityManager->persist($user);

             // actually executes the queries (i.e. the INSERT query)
             $entityManager->flush();
            
            return $this->redirectToRoute('auth.register');
        }

        return $this->render('auth/register.html.twig', [
            'controller_name' => 'AuthController',
            'userForm' => $userForm->createView() 
        ]);
    }

    /**
     * @Route("/login", name="auth.login")
     */
    public function login(Request $request,ManagerRegistry $doctrine,UserPasswordEncoderInterface $encoder,AuthenticationUtils $authUtils): Response
    {
        $error = $authUtils->getLastAuthenticationError();
        return $this->render('auth/login.html.twig', [
            'controller_name' => 'AuthController',
            'error' => $error
        ]);
    }
}
