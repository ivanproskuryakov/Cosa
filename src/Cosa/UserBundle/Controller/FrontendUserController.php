<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\UserBundle\Controller;

use Cosa\UserBundle\Entity\FrontendUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cosa\UserBundle\Form\Type\FrontendUserLoginType;
use Cosa\UserBundle\Form\Type\FrontendUserRegisterType;
use Cosa\UserBundle\Form\Type\FrontendUserForgotPasswordType;
use Cosa\UserBundle\Form\Type\FrontendUserForgotChangeType;


class FrontendUserController extends Controller
{
    protected function isAuthenticated()
    {
        if ($this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN') === false) {
            return $this->container->get('security.context')->isGranted('ROLE_USER');
        }
        return false;
    }

    protected function getUserManager()
    {
        return $this->get('frontend.user.manager');
    }

    protected function loginUser(FrontendUser $user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.context')->setToken($token);
        $this->get('session')->set('_security_main',serialize($token));
    }

    public function loginAction()
    {
        $form = $this->createForm(new FrontendUserLoginType(), array(
            'action' => $this->generateUrl('cosa_user_login'),
        ));

        $request = $this->get('request');
        if ($request->getMethod() === 'POST') {
            $form->bind($request);

            if ($form->isValid()) {

                $username = $form->get('username')->getData();
                $password = $form->get('password')->getData();

                /** @var \Cosa\UserBundle\Entity\FrontendUserRepository $um */
                $um = $this->getUserManager();
                $user = $um->loadUserByUsername($username);


                if ((!$user instanceof FrontendUser) || (!$this->getUserManager()->checkUserPassword($user, $password))) {
                    $this->get('session')->getFlashBag()->set('error',$this->get('translator')->trans('user.flash.error.wrong_login', array(), 'CosaUserBundle'));
                } else {
                    $this->loginUser($user);
                    $this->get('session')->getFlashBag()->set('success',$this->get('translator')->trans('user.flash.success.login_success', array(), 'CosaUserBundle'));
                    return $this->redirect($this->generateUrl('cosa_user_account'));
                }

            }
        }

        return $this->render(
            'CosaUserBundle:Frontend:login.html.twig',
            array('form' => $form->createView())
        );
    }

    public function registerAction()
    {

        $form = $this->createForm(new FrontendUserRegisterType(), array(
            'action' => $this->generateUrl('cosa_user_register'),
        ));

        $request = $this->get('request');
        if ($request->getMethod() === 'POST') {
            $form->bind($request);

            if ($form->isValid()) {

                $username = $form->get('username')->getData();
                $email = $form->get('email')->getData();
                $password = $form->get('password')->getData();

                if ($this->getUserManager()->findUser($username, $email)) {
                    $this->get('session')->getFlashBag()->set('notice',$this->get('translator')->trans('user.flash.error.register_email_busy', array(), 'CosaUserBundle'));
                } else {

                    $userData = array(
                        'username'=>$username,
                        'password'=>$password,
                        'email'=>$email,
                        'pin'=>$pin,
                    );
                    $user = $this->getUserManager()->registerUser($userData);
                    if ($user) {
                        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                        $this->get('security.context')->setToken($token);
                        $this->get('session')->set('_security_main',serialize($token));
                    }

                    $this->get('session')->getFlashBag()->set('notice',$this->get('translator')->trans('user.flash.success.register_success', array(), 'CosaUserBundle'));
                    return $this->redirect($this->generateUrl('cosa_user_account'));

                }
            }
        }

        return $this->render(
            'CosaUserBundle:Frontend:register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function accountAction()
    {
        $user     = $this->get('security.context')->getToken()->getUser();
        $id       = $user->getId();
        $username = $user->getUsername();
        $balance  = $user->getBalance();

        $user = $this->getUserManager()->getUserInformation($id);

        return $this->render(
            'CosaUserBundle:Frontend:account.html.twig',
            array(
                'user' => $user,
            )
        );
    }

    public function forgotPasswordAction()
    {
        $form = $this->createForm(new FrontendUserForgotPasswordType(), array(
            'action' => $this->generateUrl('cosa_user_register'),
        ));

        $request = $this->get('request');
        if ($request->getMethod() === 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $email = $form->get('email')->getData();

                if ($this->getUserManager()->loadUserByEmail($email)) {

                    $result = $this->getUserManager()->sendEmailWithGeneratedPassword($email);

                    if ($result == 1) {
                        $this->get('session')->getFlashBag()->set('notice',$this->get('translator')->trans('user.flash.notice.password_sent', array(), 'CosaUserBundle'));
                        return $this->redirect($this->generateUrl('cosa_user_login'));
                    } else {
                        $this->get('session')->getFlashBag()->set('notice',$result);
                    }

                } else {
                    $this->get('session')->getFlashBag()->set('notice',$this->get('translator')->trans('user.flash.error.not_found', array(), 'CosaUserBundle'));
                }
            }
        }


        return $this->render(
            'CosaUserBundle:Frontend:forgotPassword.html.twig',
            array('form' => $form->createView())
        );
    }

    /*
     * Change password and redirect to account page
     * */
    public function changePasswordAction()
    {

        $form = $this->createForm(new FrontendUserForgotChangeType(), array(
            'action' => $this->generateUrl('cosa_user_passwordchange'),
        ));

        $request = $this->get('request');
        if ($request->getMethod() === 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $username = $this->get('security.context')->getToken()->getUser()->getUsername();
                $currentPassword = $form->get('currentPassword')->getData();
                $password = $form->get('password')->getData();

                // Check is entered password valid
                $user = $this->getUserManager()->loadUserByUsername($username);
                if (!$this->getUserManager()->checkUserPassword($user, $currentPassword)) {
                    $this->get('session')->getFlashBag()->set('notice',$this->get('translator')->trans('user.flash.error.password_wrong', array(), 'CosaUserBundle'));
                    return $this->redirect($this->generateUrl('cosa_user_passwordchange'));
                }

                // Change password
                $this->getUserManager()->changePassword($username, $password);
                $this->get('session')->getFlashBag()->set('notice',$this->get('translator')->trans('user.flash.success.password_changed', array(), 'CosaUserBundle'));
                return $this->redirect($this->generateUrl('cosa_user_account'));
            }
        }
        return $this->render(
            'CosaUserBundle:Frontend:changePassword.html.twig',
            array('form' => $form->createView())
        );
    }


}
