<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\UserBundle\Manager;

use Cosa\UserBundle\Entity\FrontendUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Cosa\UserBundle\Utility\PasswordUtility;

class UserManager  implements UserProviderInterface
{
    protected $encoder;
    protected $em;
    protected $mailer;
    protected $appEmail;
    protected $templating;
    protected $translator;

    public function __construct($em, $encoder, $mailer ,
                                $templating, $appEmail,
                                $translator)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->appEmail = $appEmail;
        $this->translator = $translator;
    }


    /**
     * Get Templating service
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * Get Mailer service
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    protected function getRepository() {
        return $this->em->getRepository('CosaUserBundle:FrontendUser');
    }

    public function checkUserPassword(FrontendUser $user, $password)
    {
        $encoder = $this->encoder->getEncoder($user);

        if(!$encoder){
            return false;
        }
        $isValid = $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());

        return $isValid;
    }


    /*
     * Register User
     * @return object
     */
    public function registerUser(Array $userData)
    {
        $user = $this->loadUserByUsername($userData['username']);

        if (!$user) {
            $user = new FrontendUser();
            $encoder = $this->encoder->getEncoder($user);
            $encodedPassword = $encoder->encodePassword($userData['password'], $user->getSalt());

            $user->setEmail($userData['email']);
            $user->setUsername($userData['username']);
            $user->setPassword($encodedPassword);
            $user->setEnabled(true);
            $user->setLocked(false);

            $user->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $user->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $user->setLastLogin(new \DateTime(date('Y-m-d H:i:s')));

            $this->em->persist($user);
            $this->em->flush();

            // Send password via email
            try{
                $message = \Swift_Message::newInstance()
                    ->setSubject($this->translator->trans('user.email.new_account', array(), 'CosaUserBundle'))
                    ->setFrom($this->appEmail)
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->getTemplating()->render(
                            'CosaUserBundle:Email:registration.txt.twig',
                            array(
                                'username'  => $user->getUsername(),
                                'password'  => $userData['password'],
                                'email'  => $user->getEmail(),
                            )
                        )
                    );

                $response = $this->getMailer()->send($message);
            }catch(\Swift_TransportException $e){
                $response = $e->getMessage() ;
            }

            return $user;

        } else {
            return false;
        }

    }

    /*
     * Change password for username
     */
    public function changePassword($username,$password)
    {
        $user = $this->loadUserByUsername($username);
        if(!($user)){
            throw new NotFoundHttpException('Nothing found');
        }

        $encoder = $this->encoder->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($encodedPassword);
        $this->em->persist($user);
        $this->em->flush();


    }

    /*
     * Generate new password for user and send it by E-mail
     *
     * @return string
     */
    public function sendEmailWithGeneratedPassword($email)
    {
        $user = $this->loadUserByEmail($email);

        $utility = new PasswordUtility();
        $password = $utility->generatePassword();

        $encoder = $this->encoder->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());

        $user->setPassword($encodedPassword);
        $this->em->persist($user);
        $this->em->flush();


        // Send password via email
        try{
            $message = \Swift_Message::newInstance()
                ->setSubject($this->translator->trans('user.email.password_updated', array(), 'CosaUserBundle'))
                ->setFrom($this->appEmail)
                ->setTo($email)
                ->setBody(
                    $this->getTemplating()->render(
                        'CosaUserBundle:Email:newPassword.txt.twig',
                        array(
                            'username'  => $user->getUsername(),
                            'password'  => $password,
                        )
                    )
                );

            $response = $this->getMailer()->send($message);
        }catch(\Swift_TransportException $e){
            $response = $e->getMessage() ;
        }

        return $response;
    }


    public function loadUserByEmail($email)
    {
        $user = $this->getRepository()->findOneBy(array('email' => $email));
        return $user;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->getRepository()->findOneBy(array('username' => $username));
        return $user;
    }

    public function findUser($username, $email)
    {
        $user = $this->getRepository('CosaUserBundle:FrontendUser')->findUser($username, $email);
        return $user;
    }

    public function getUserInformation($id)
    {
        $user = $this->getRepository('CosaUserBundle:FrontendUser')->userInformation($id);
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);

        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
        || is_subclass_of($class, $this->getEntityName());
    }


}
