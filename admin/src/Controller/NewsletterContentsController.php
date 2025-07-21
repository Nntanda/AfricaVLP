<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * NewsletterContents Controller
 *
 * @property \App\Model\Table\NewsletterContentsTable $NewsletterContents
 *
 * @method \App\Model\Entity\NewsletterContent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NewsletterContentsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['News']
        ];

        $newsletterContents = $this->NewsletterContents->find();

        $range = $this->request->getQuery('range');
        if($range != null && $range !== '') {
            $dates = explode('/', $range);
            $newsletterContents = $newsletterContents->where(function ($exp, $q) use ($dates) {
                return $exp->gte('NewsletterContents.created', $dates[0]);
            })->where(function ($exp, $q) use ($dates) {
                return $exp->lte('NewsletterContents.created', $dates[1]);
            });
        }

        $total = $newsletterContents->count();
        $newsletterContents = $this->paginate($newsletterContents);

        $this->set(compact('newsletterContents', 'total', 'range'));
    }
}
