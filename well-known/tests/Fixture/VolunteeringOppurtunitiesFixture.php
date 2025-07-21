<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VolunteeringOppurtunitiesFixture
 */
class VolunteeringOppurtunitiesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'event_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'volunteering_duration_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'volunteering_role_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'status' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_volunteering_oppurtunities_events1_idx' => ['type' => 'index', 'columns' => ['event_id'], 'length' => []],
            'fk_volunteering_oppurtunities_volunteering_durations1_idx' => ['type' => 'index', 'columns' => ['volunteering_duration_id'], 'length' => []],
            'fk_volunteering_oppurtunities_volunteering_roles1_idx' => ['type' => 'index', 'columns' => ['volunteering_role_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_volunteering_oppurtunities_events1' => ['type' => 'foreign', 'columns' => ['event_id'], 'references' => ['events', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_volunteering_oppurtunities_volunteering_durations1' => ['type' => 'foreign', 'columns' => ['volunteering_duration_id'], 'references' => ['volunteering_durations', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_volunteering_oppurtunities_volunteering_roles1' => ['type' => 'foreign', 'columns' => ['volunteering_role_id'], 'references' => ['volunteering_roles', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'event_id' => 1,
                'volunteering_duration_id' => 1,
                'volunteering_role_id' => 1,
                'status' => 1,
                'created' => '2020-03-11 06:08:23',
                'modified' => '2020-03-11 06:08:23'
            ],
        ];
        parent::init();
    }
}
