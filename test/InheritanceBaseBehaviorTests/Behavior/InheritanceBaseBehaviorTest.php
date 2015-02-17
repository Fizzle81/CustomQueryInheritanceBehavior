<?php

namespace InheritanceBaseBehaviorTests\Behavior;

use Propel\Generator\Util\QuickBuilder;
use Propel\Generator\Config\QuickGeneratorConfig;

class InheritanceBaseBehaviorTest extends \PHPUnit_Framework_TestCase {

    private static $created = false;

    /**
     * setup the unit test
     *
     * @see PHPUnit_Framework_TestCase::setUp()
     *
     * @return void
     */
    public function setUp() {
        if (self::$created === false) {
            $this->_create();
        }
        self::$created = true;
    }

    /**
     * create sqllite table
     *
     * @return void
     */
    private function _create() {
        $schema = '<database name="bookstore" defaultIdMethod="native" namespace="Bookstore">
            <table name="book" phpName="Book">
                <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />
                <column name="title" type="VARCHAR" required="true" />
                <column name="genre" phpName="Genre" type="INTEGER" size="11" required="true" defaultValue="0" inheritance="single">
                <inheritance key="1" class="FantasyBook" extends="Book" />
                <inheritance key="2" class="HorrorBook" extends="Book" />
                </column>
                <behavior name="custom-query-inheritance" />
            </table>
            <table name="author" phpName="Author">
                <column name="id" required="true" primaryKey="true" autoIncrement="true" type="INTEGER" />
                <column name="name" type="VARCHAR" required="true" />
                <column name="publisher" phpName="Publisher" type="INTEGER" size="11" required="true" defaultValue="0" inheritance="single">
                <inheritance key="1" class="BildPublisher" extends="Author" />
                <inheritance key="2" class="SpiegelPublisher" extends="Author" />
                </column>
                <behavior name="custom-query-inheritance">
                    <parameter name="base" value="InheritanceBaseBehaviorTests\\TestQuery" />
                </behavior>
            </table>
        </database>';
        $extraconf = array(
            'propel' => array(
                'generator' => array(
                    'objectModel' => array(
                        'builders' => array(
                            'queryinheritance' => 'QueryInheritance\\Builder\\QueryInheritanceBehaviorBuilder'
                        )
                    )
                )
            )
        );
        $config  = new QuickGeneratorConfig($extraconf);
        $builder = new QuickBuilder();
        $config  = $builder->setConfig($config);
        $builder->setSchema($schema);
        $con = $builder->build();
    }

    /**
     * test insertion of generic query class
     *
     * @return void
     */
    public function testInsertQueryClass() {
        $this->assertEquals('Bookstore\\Base\\FantasyBookQuery', get_parent_class('Bookstore\\FantasyBookQuery'));
        $this->assertEquals('Bookstore\\BookQuery', get_parent_class('Bookstore\\Base\\FantasyBookQuery'));
        $this->assertEquals('Bookstore\\Base\\BookQuery', get_parent_class('Bookstore\\BookQuery'));
        $this->assertEquals('Propel\\Runtime\\ActiveQuery\\ModelCriteria', get_parent_class('Bookstore\\Base\\BookQuery'));
    }

    /**
     * test insertion of generic query class with custom class
     *
     * @return void
     */
    public function testInsertQueryClassWithCustomClass() {
        $this->assertEquals('Bookstore\\Base\\SpiegelPublisherQuery', get_parent_class('Bookstore\\SpiegelPublisherQuery'));
        $this->assertEquals('InheritanceBaseBehaviorTests\\TestQuery', get_parent_class('Bookstore\\Base\\SpiegelPublisherQuery'));
        $this->assertEquals('Propel\\Runtime\\ActiveQuery\\ModelCriteria', get_parent_class('InheritanceBaseBehaviorTests\\TestQuery'));
    }
}