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

use Cosa\UserBundle\Entity\BackendUser;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadBackendUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
     * FOSUser user manager for our nice operations.
     * @return \Cosa\UserBundle\Manager\UserManager
     */
    protected function getUserManager()
    {
        return $this->container->get('fos_user.user_manager');
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $random = rand(11111,99999);
        $time = time();

        $user = new BackendUser();
        $user->setUsername('jorgepalacios');
        $user->setEmail('jorgepalacios@jorgepalacios.es');
        $user->setPlainPassword('jorgepalacios');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());

        $user = $this->getUserManager()->updateUser($user);


    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}