<?php

namespace App\Controller;

use App\Service\PasswordGenerator;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('pages/home.html.twig', [
            'password_default_length' => $this->getParameter('app.password_default_length'),
            'password_min_length' => $this->getParameter('app.password_min_length'),
            'password_max_length' => $this->getParameter('app.password_max_length')
        ]);
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response
    {
        $length = max(
            min($request->query->getInt('length'), $this->getParameter('app.password_max_length')),
            $this->getParameter('app.password_min_length')
        );

        $uppercase = $request->query->getBoolean('uppercase');
        $digits = $request->query->getBoolean('digits');
        $specialCharacters = $request->query->getBoolean('special_characters');

        $password = $passwordGenerator->generate(
            $length,
            $uppercase,
            $digits,
            $specialCharacters,
        );

        $response = $this->render('pages/password.html.twig',  compact('password'));

        $response->headers->setCookie(new Cookie('app_length', $length, new DateTimeImmutable('+5 years')));

        $response->headers->setCookie(new Cookie('app_uppercase', $uppercase ? :'0', new DateTimeImmutable('+5 years')));

        $response->headers->setCookie(new Cookie('app_digits', $digits ? :'0', new DateTimeImmutable('+5 years')));

        $response->headers->setCookie(new Cookie('app_special_characters', $specialCharacters ? :'0', new DateTimeImmutable('+5 years')));
        return $response;
    }
}
