<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\ContactBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cosa\ContactBundle\Form\Type\FrontendContactType;

class FrontendController extends Controller
{

    public function viewAction(Request $request)
    {
        $info = $this->container->get("cosa.contact.manager")->getContactsInfo();


        $form = $this->createForm(new FrontendContactType(), array(
            'action' => $this->generateUrl('cosa_contact'),
        ));

        $request = $this->get('request');
        if ($request->getMethod() === 'POST') {
            $form->bind($request);

            if ($form->isValid()) {

                $params = array(
                    'name'=>$form->get('name')->getData(),
                    'email'=>$form->get('email')->getData(),
                    'phone'=>$form->get('phone')->getData(),
                    'message'=>$form->get('message')->getData(),
                );


                $response = $this->container->get("cosa.contact.manager")->sendMail($params);

                if ($response == 1) {
                    $this->get('session')->getFlashBag()->set('notice',$this->get('translator')->trans('contact.flash.success.message_sent', array(), 'CosaContactBundle'));
                    return $this->redirect($this->generateUrl('cosa_contact'));

                } else {
//                    var_dump($response);
//                    exit();
                    $this->get('session')->getFlashBag()->set('notice',$response);
                }


            }
        }

        return $this->render(
            'CosaContactBundle:Frontend:view.html.twig',
            array(
                'form' => $form->createView(),
                'contactInfo' => $info
            )
        );
    }
}
