<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Core\Configure;

/**
 * Widgets Controller
 *
 * @property \App\Model\Table\WidgetsTable $Widgets
 *
 * @method \App\Model\Entity\Widget[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WidgetsController extends AppController
{
    private $widgetNames = [
        'image_slider' => 'Image slider',
        'about_block' => 'About block',
        'footer' => 'footer',
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index($name = null)
    {
        $widgets = $this->paginate($this->Widgets);

        $names = $this->widgetNames;

        $this->set(compact('widgets', 'names'));
    }

    /**
     * View method
     *
     * @param string|null $id Widget id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $widget = $this->Widgets->get($id, [
            'contain' => ['Widgets_title_translation', 'Widgets_content_translation', 'I18n']
        ]);

        $this->set('widget', $widget);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $widget = $this->Widgets->newEntity();
        if ($this->request->is('post')) {
            $widget = $this->Widgets->patchEntity($widget, $this->request->getData());
            // dd($widget);
            if ($this->Widgets->save($widget)) {
                $this->Flash->success(__('The widget has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The widget could not be saved. Please, try again.'));
        }
        $names = $this->widgetNames;
        $this->set(compact('widget', 'names'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Widget id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Widgets->setlocale('en_GB');
        $widget = $this->Widgets->find('translations')->where(['Widgets.id' => $id])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $widget = $this->Widgets->patchEntity($widget, $this->request->getData());
            if ($this->Widgets->save($widget)) {
                $this->Flash->success(__('The widget has been saved.'));

                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The widget could not be saved. Please, try again.'));
            return $this->redirect($this->referer());
        }
        $names = $this->widgetNames;
        $this->set(compact('widget', 'names'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Widget id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $widget = $this->Widgets->get($id);
        if ($this->Widgets->delete($widget)) {
            $this->Flash->success(__('The widget has been deleted.'));
        } else {
            $this->Flash->error(__('The widget could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    /**
     * home page method
     *
     * @return \Cake\Http\Response|null
     */
    public function homepage($name = null)
    {
        $this->Widgets->setlocale('en_GB');
        $widgets = $this->Widgets->find('translations')->where(['Widgets.name' => $name])->toArray();

        $statuses = Configure::read('STATUSES');
        $this->set(compact('widgets', 'name', 'widget', 'statuses'));
    }

    /**
     * about page method
     *
     * @return \Cake\Http\Response|null
     */
    public function aboutPage($section = null)
    {
        $this->Widgets->setlocale('en_GB');
        $widgets = $this->Widgets->find('translations')->where(['Widgets.name' => $section])->toArray();

        $statuses = Configure::read('STATUSES');
        $this->set(compact('widgets', 'section', 'widget', 'statuses'));
    }
}
