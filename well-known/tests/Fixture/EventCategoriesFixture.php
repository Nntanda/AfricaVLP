<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EventCategoriesFixture
 */
class EventCategoriesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'volunteering_oppurtunity_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'volunteering_category_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_oppurtunity_categories_volunteering_oppurtunities1_idx' => ['type' => 'index', 'columns' => ['volunteering_oppurtunity_id'], 'length' => []],
            'fk_oppurtunity_categories_volunteering_categories1_idx' => ['type' => 'index', 'columns' => ['volunteering_category_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_oppurtunity_categories_volunteering_categories1' => ['type' => 'foreign', 'columns' => ['volunteering_category_id'], 'references' => ['volunteering_categories', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_oppurtunity_categories_volunteering_oppurtunities1' => ['type' => 'foreign', 'columns' => ['volunteering_oppurtunity_id'], 'references' => ['volunteering_oppurtunities', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'volunteering_oppurtunity_id' => 1,
                'volunteering_category_id' => 1
            ],
        ];
        parent::init();
    }
}
