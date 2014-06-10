<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\ContactBundle\Manager;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class ContactManager
{
    protected $em;
    protected $mailer;
    protected $appEmail;
    protected $templating;

    public function __construct($em, $mailer ,$templating, $appEmail)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->appEmail = $appEmail;
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

    /**
     * Send email to administration E-mail
     * @param array $params
     * @return array
     */
    public function sendMail($params)
    {

        try{
            $message = \Swift_Message::newInstance()
                ->setSubject('Contacts Mail')
                ->setFrom($params['email'])
                ->setTo($this->appEmail)
                ->setBody(
                    $this->getTemplating()->render(
                        'CosaContactBundle:Default:email.txt.twig',
                        array(
                            'name'      => $params['name'],
                            'email'     => $params['email'],
                            'phone'     => $params['phone'],
                            'message'   => $params['message']
                        )
                    )
                );

            $response = $this->getMailer()->send($message);
        }catch(\Swift_TransportException $e){
            $response = $e->getMessage() ;
        }

        return $response;
    }

    /**
     * Get contact information
     * @return string
     */
    public function getContactsInfo()
    {

        $config = $this->em->getRepository('AiselConfigBundle:Config')->getConfig('config_contact');
        if(!($config)){
            throw new NotFoundHttpException('Contact info not found');
        }

        $value = json_decode($config->getValue());
        return $value;
    }

//    /**
//     * Get contact setting
//     * @param array $params
//     * @return array
//     */
//    public function getConfig()
//    {
//        $config = $this->em->getRepository('CosaConfigBundle:Config')->findOneBy(array('entity' => 'config_contact'));
//        if(!($config)){
//            throw new NotFoundHttpException('Nothing found');
//        }
//        return $config;
//    }


}
