<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Support Controller
 *
 *
 * @method \App\Model\Entity\Support[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SupportController extends AppController
{
    public function initialize() 
    {
        parent::initialize();
        $this->loadModel('AdminSupports');

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $supports = $this->AdminSupports->find()->contain(['Organizations'])->innerJoinWith('AdminSupportMessages')->group('AdminSupports.id');
        $supports->formatResults(function ($results) {
            return $results->map(function ($conversationData) {
                return $this->AdminSupports->loadInto($conversationData, [
                    'AdminSupportMessages' => [
                        'strategy' => 'subquery',
                        'queryBuilder' => function ($q) {
                            return $q->order(['AdminSupportMessages.created' => 'DESC'])->limit(1);
                        },
                        'SenderUsers',
                        'SenderAdmins',
                    ]
                ]);
            });
        });
        $supports = $this->paginate($supports);

        $this->set(compact('supports'));
    }

    /**
     * View method
     *
     * @param string|null $id Support id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function message($id = null)
    {
        if (!$this->AdminSupports->Organizations->exists(['id' => $id])) {
            $this->Flash->error(__('Invalid Organization'));

            return $this->redirect(['action' => 'index']);
        }
        
        if (!$this->AdminSupports->exists(['organization_id' => $id])) {
            $adminSupport = $this->AdminSupports->newEntity(['organization_id' => $id, 'status' => STATUS_ACTIVE]);
            $this->AdminSupports->save($adminSupport);
        }
        
        $support = $this->AdminSupports->find()->where(['AdminSupports.organization_id' => $id])->contain(['AdminSupportMessages' => function ($q) {
            return $q->order(['AdminSupportMessages.created'])->contain(['SenderUsers', 'SenderAdmins']);
        }, 'Organizations'])->first();

        $this->AdminSupports->AdminSupportMessages->query()
        ->update()
        ->set(['is_read' => true])
        ->where(['admin_support_id' => $support->id, 'sender' => 'organization', 'is_read IS NOT' => true])
        ->execute();

        $supportMessage = $this->AdminSupports->AdminSupportMessages->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['admin_support_id'] = $support->id;
            $data['sender'] = 'au';
            $data['sender_user_id'] = $this->Auth->user('id');
            $data['status'] = STATUS_ACTIVE;

            $supportMessage = $this->AdminSupports->AdminSupportMessages->patchEntity($supportMessage,$data);
            if ($this->AdminSupports->AdminSupportMessages->save($supportMessage)) {
                $this->Flash->success(__('The message has been sent.'));

                $redirectUrl = ['action' => 'index'];
                return $this->redirect($redirectUrl);
            }
            $this->Flash->error(__('The message could not been sent.'));
        }

        $this->set(compact('supportMessage'));
        $this->set('support', $support);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function sendMessage()
    {
        $supportMessage = $this->AdminSupports->AdminSupportMessages->newEntity();
        if ($this->request->is('post')) {
            $supportMessage = $this->AdminSupports->AdminSupportMessages->patchEntity($supportMessage, $this->request->getData());
            if ($this->AdminSupports->AdminSupportMessages->save($supportMessage)) {
                $this->Flash->success(__('The message has been sent.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The message could not be sent. Please, try again.'));
        }
        $this->set(compact('supportMessage'));
        return $this->redirect($this->referer());
    }

    public function new()
    {
        # code...
    }
}
