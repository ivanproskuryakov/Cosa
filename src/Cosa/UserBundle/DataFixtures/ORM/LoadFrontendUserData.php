<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadFrontendUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Cosa Frontend user manager
     * @return \Cosa\UserBundle\Manager\UserManager
     */
    protected function getUserManager()
    {
        return $this->container->get('frontend.user.manager');
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userData = array(
            'username'=>'frontenduser',
            'password'=>'frontenduser',
            'email'=>'frontenduser@mail.com',
        );

        $user = $this->getUserManager()->registerUser($userData);
        $this->addReference('frontend-user', $user);


    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}