<?php
namespace App\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\ContentManagementBundle\Entity\ContentBlock;

class LoadContenBlockData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $path = 'web/bundles/admin/data/ContentBlock.csv';
        if (!file_exists($path))
            exit("File not found");
        $rows = file($path);
        foreach ($rows as $row) {
            $fields = explode(";", $row);
            $contentblock = new ContentBlock();
            $contentblock->setName($fields[0]);
            $contentblock->setPosition($fields[1]);
            $contentblock->setImage($fields[2]);
            $contentblock->setFile($fields[3]);
            $contentblock->setActive($fields[4]);
            $manager->persist($contentblock);
        }
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }
}

