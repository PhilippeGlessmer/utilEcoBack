<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\AppAuthAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * @Route("/backoffice")
 */

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthAuthenticator $authenticator): Response
    {
        function Genere_Password($size)
        {
            $password ='';
            // Initialisation des caractères utilisables
            $characters = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,"-","_","@","&","#", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

            for($i=0;$i<$size;$i++)
            {
                $password .= ($i%2) ? strtoupper($characters[array_rand($characters)]) : $characters[array_rand($characters)];
            }

            return $password;
        }
        $mon_mot_de_passe = Genere_Password(6);
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI0OTMxIiwiaWF0IjoxNTk1NTMyOTkzfQ.-9E479z3cp7dJ7bRPQ3Dr05a9_cwHhNen_YFnIkquno"; // https://secure.smsfactor.com/token.html;
            $content = "Bonjour ".$user->getCivil()." ".$user->getNom()." ".$user->getPrenom().", pour vous connecter à https://utilEco.glessmer.fr authentifiez vous avec votre adresse mail: ".$user->getEmail()." et le password suivant ".$user->getPasswordFirst().". A bientôt. Cordialement l'équipe UtilEco";
            $numbers = array($user->getTelPortable());
            $sender = "UtilEco";
            $recipients = array();
            foreach ($numbers as $n) {
                $recipients[] = array('value' => $n);
            }

            $postdata = array(
                'sms' => array(
                    'message' => array(
                        'text' => $content,
                        'sender' => $sender
                    ),
                    'recipients' => array('gsm' => $recipients)
                )
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.smsfactor.com/send");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json', 'Authorization: Bearer ' . $token));
            $response = curl_exec($ch);
            curl_close($ch);
            // generate a signed url and email it to the user
            /*$this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('contact@utileco.glessmer.fr', 'UtilEco enregistrement'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );*/
            // do anything else you need here, like send an email
            if(in_array("ROLE_FOURNISSEUR" , $user->getRoles())) {
                return $this->redirectToRoute('fournisseurs_new');
            }else{
                return $this->redirectToRoute('dashbord');
                /*
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
                */
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'generationpassword' => $mon_mot_de_passe,
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
