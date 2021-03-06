<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\PageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cosa\PageBundle\Entity\Page;
use Cosa\CategoryBundle\Entity\Category;

class LoadPageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $loremIpsumText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris consectetur dolor eget viverra commodo. Ut vehicula volutpat massa. Maecenas congue sed risus ut semper. Fusce blandit sem nunc, nec facilisis neque eleifend eget. Pellentesque fringilla velit enim, vel convallis libero ultrices vel. Interdum et malesuada fames ac ante ipsum primis in faucibus. Maecenas consectetur lacus et nibh facilisis, non vulputate urna convallis. Donec quis dictum magna, id dictum urna. Aliquam euismod sit amet arcu vulputate laoreet. Vivamus at leo nibh. Proin scelerisque orci sit amet sem varius, a porttitor tortor iaculis. Aenean sollicitudin diam sed euismod varius. Duis commodo a metus eu scelerisque. Etiam porttitor placerat urna vel tincidunt. Quisque congue tellus quam, non volutpat justo eleifend vehicula. Phasellus cursus convallis aliquam. Morbi adipiscing vulputate tellus, id auctor metus interdum a. Fusce diam tellus, varius commodo tincidunt in, ornare a mauris. Phasellus interdum, metus non fringilla rhoncus, odio massa pharetra orci, in semper tortor enim nec quam. Duis consectetur quis nibh at convallis. Integer tincidunt ligula sem, vitae bibendum sem elementum nec. Etiam ornare nisl lacinia, facilisis nisl a, mollis sem. Aliquam erat volutpat.';

        // referenced Categories
        $rootCategory = $this->getReference('root-category');
        $childCategory = $this->getReference('child-category');


        // Hidden About page
        $hiddenPage = new Page();
        $hiddenPage->setTitle('About Us');
        $hiddenPage->setContent($loremIpsumText);
        $hiddenPage->setStatus(true);
        $hiddenPage->setIsHidden(true);
        $hiddenPage->setCommentStatus(false);
        $hiddenPage->setMetaUrl('about');
        $hiddenPage->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $hiddenPage->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($hiddenPage);
        $manager->flush();
        $this->addReference('about-page', $hiddenPage);

        // Disabled page
        $page = new Page();
        $page->setTitle('Disabled Page');
        $page->setContent($loremIpsumText);
        $page->setStatus(false);
        $page->setIsHidden(true);
        $page->setCommentStatus(false);
        $page->setMetaUrl('page-disabled');
        $page->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $page->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($page);
        $manager->flush();

        // Pages
        for ($i = 1; $i <= 10; $i++ ) {
            $page = new Page();
            $page->setTitle('Sample Page '. $i);
            $page->setContent($loremIpsumText);
            $page->setStatus(true);
            $page->setIsHidden(false);
            $page->addCategory($rootCategory);
            $page->addCategory($childCategory);
            $page->setCommentStatus(false);
            $page->setMetaUrl('page-'.$i);
            $page->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $page->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
            $manager->persist($page);
            $manager->flush();
            $this->addReference('page'.$i, $page);
        }

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}