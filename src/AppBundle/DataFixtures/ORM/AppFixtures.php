<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Genus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Fixtures::load(__DIR__.'/fixtures.yml', $manager);
        Fixtures::load(__DIR__.'/fixtures.yml',
            $manager,
        [
            'providers' => [$this]
        ]
        );
    }

    public function genus()
    {
        $genera = [
            'Pantholops chiru',
            'Budorcas takin',
            'Ammotragus Barbary sheep',
            'Arabitragus Arabian tahr',
            'Hemitragus Himalayan tahr',
            'Pseudois blue sheep',
            'Capra goats and ibexes',
            'Nilgiritragus Nilgiri tahr',
            'Ovis sheep',
            'Rupicapra chamois',
            'Nemorhaedus goral',
            'Oreamnos Rocky Mountain goat',
            'Capricornis serows',
            'Ovibos muskox'
        ];

        $key = array_rand($genera);

        return $genera[$key];
    }
}