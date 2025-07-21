<?php
namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Footer cell
 */
class FooterCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize()
    {
    }

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
        $this->loadModel('Widgets');
        $footer = $this->Widgets->find()->where(['name' => $this->Widgets::FOOTER])->first();
        
        $this->loadModel('ResourceTypes');
        $resourceTypes = $this->ResourceTypes->find()->where(['status' => STATUS_ACTIVE]);

        $this->set(compact('footer', 'resourceTypes'));
    }
}
