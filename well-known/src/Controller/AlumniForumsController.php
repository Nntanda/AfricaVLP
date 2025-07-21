<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AlumniForums Controller
 *
 */
class AlumniForumsController extends AppController
{
    public function index($orgId = null)
    {
        try {
            $this->loadModel('ForumThreads');
            $this->loadModel('Organizations');
            $organization = $this->Organizations->get($orgId, [
                'contain' => ['Countries', 'Cities']
            ]);
            $userId = $this->Auth->user('id');

            if (!$this->Organizations->OrganizationAlumni->exists(['organization_id' => $organization->id, 'user_id' => $userId])) {
                $this->Flash->error(__('The user is not an alumni of the requested organization.'));
                return $this->redirect($this->referer());
            }

            $threads = $this->ForumThreads->find()->where(['ForumThreads.organization_id' => $organization->id])->contain(['Users']);
            $threads->select(['users_count' => $threads->func()->count('DISTINCT ForumPosts.user_id')])->leftJoinWith('ForumPosts')->group('ForumThreads.id')->enableAutoFields(true);
            $threads = $this->paginate($threads);

            $joinedThreads = $this->ForumThreads->find()->where(['ForumThreads.organization_id' => $organization->id])->contain(['Users']);
            $joinedThreads->select(['users_count' => $joinedThreads->func()->count('DISTINCT ForumPosts.user_id')])->leftJoinWith('ForumPosts')->innerJoinWith('ForumPosts', function ($q) use ($userId) {
                return $q->where(['ForumPosts.user_id' => $userId]);
            })->group('ForumThreads.id')->enableAutoFields(true);
            $joinedThreads->limit(15);

            $myThreads = $this->ForumThreads->find()->where(['ForumThreads.organization_id' => $organization->id, 'ForumThreads.user_id' => $userId])->contain(['Users']);
            $myThreads->select(['users_count' => $myThreads->func()->count('DISTINCT ForumPosts.user_id')])->leftJoinWith('ForumPosts')->group('ForumThreads.id')->enableAutoFields(true)->limit(15);
            
            $this->set(compact('organization', 'threads', 'joinedThreads', 'myThreads'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            // return $this->redirect('/');
        }
    }
    
    public function addNewThread($orgId = null)
    {
        try {
            $this->loadModel('ForumThreads');
            $this->loadModel('Organizations');
            $organization = $this->Organizations->get($orgId);
            $thread = $this->ForumThreads->newEntity([
                'organization_id' => $organization->id,
                'user_id' => $this->Auth->user('id')
            ]);

            if ($this->request->is(['patch', 'put', 'post'])) {
                $data = $this->request->getData();
                $thread = $this->ForumThreads->patchEntity($thread, $data);
                if ($thread = $this->ForumThreads->save($thread)) {
                    $this->Flash->success(__('The thread has been saved.'));
                    return $this->redirect(['action' => 'thread', $thread->id]);
                }
                $this->Flash->error(__('The thread could not be saved. Please, try again.'));
            }
            
            $this->set(compact('organization'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            return $this->redirect($this->referer());
        }
    }

    public function thread($id = null)
    {
        try {
            $this->loadModel('ForumThreads');
            $thread = $this->ForumThreads->get($id,[
                'contain' => ['Organizations' => ['Countries', 'Cities'], 'Users']
            ]);

            if (!$this->ForumThreads->Organizations->OrganizationAlumni->exists(['organization_id' => $thread->organization_id, 'user_id' => $this->Auth->user('id')])) {
                $this->Flash->error(__('The user is not an alumni of the requested organization.'));
                return $this->redirect($this->referer());
            }

            $comments = $this->ForumThreads->ForumPosts->find()->select(['date_created' => 'DATE (ForumPosts.created)'])->where(['ForumPosts.forum_thread_id' => $thread->id])->order(['ForumPosts.created' => 'DESC'])->contain(['Users'])->enableAutoFields(true);
            $comments = $this->paginate($comments);
            $comments = collection($comments)->groupBy('date_created');

            if ($this->request->is(['patch', 'put', 'post'])) {
                $data = $this->request->getData();
                $post = $this->ForumThreads->ForumPosts->newEntity($data);
                $post->forum_thread_id = $thread->id;
                $post->user_id = $this->Auth->user('id');
                $post->status = STATUS_ACTIVE;
                if ($this->ForumThreads->ForumPosts->save($post)) {
                    $this->Flash->success(__('The comment has been saved.'));
                    return $this->redirect(['action' => 'thread', $thread->id]);
                }
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
            }
            
            $this->set(compact('thread', 'comments'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            // return $this->redirect($this->referer());
        }
    }

    public function publicThreads()
    {
        try {
            $this->loadModel('ForumThreads');
            $userId = $this->Auth->user('id');

            $threads = $this->ForumThreads->find()->where(['ForumThreads.organization_id IS' => null])->contain(['Users'])->order(['ForumThreads.created' => 'DESC']);
            $threads->select(['users_count' => $threads->func()->count('DISTINCT ForumPosts.user_id')])->leftJoinWith('ForumPosts')->group('ForumThreads.id')->enableAutoFields(true);
            $threads = $this->paginate($threads);

            $joinedThreads = $this->ForumThreads->find()->where(['ForumThreads.organization_id IS' => null])->contain(['Users']);
            $joinedThreads->select(['users_count' => $joinedThreads->func()->count('DISTINCT ForumPosts.user_id')])->leftJoinWith('ForumPosts')->innerJoinWith('ForumPosts', function ($q) use ($userId) {
                return $q->where(['ForumPosts.user_id' => $userId]);
            })->group('ForumThreads.id')->enableAutoFields(true);
            $joinedThreads->limit(15);

            $myThreads = $this->ForumThreads->find()->where(['ForumThreads.organization_id IS' => null, 'ForumThreads.user_id' => $userId])->contain(['Users']);
            $myThreads->select(['users_count' => $myThreads->func()->count('DISTINCT ForumPosts.user_id')])->leftJoinWith('ForumPosts')->group('ForumThreads.id')->enableAutoFields(true)->limit(15);
            
            $this->set(compact('threads', 'joinedThreads', 'myThreads'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            return $this->redirect('/');
        }
    }
    
    public function addNewPublicThread()
    {
        try {
            $this->loadModel('ForumThreads');
            $thread = $this->ForumThreads->newEntity([
                'user_id' => $this->Auth->user('id')
            ]);

            if ($this->request->is(['patch', 'put', 'post'])) {
                $data = $this->request->getData();
                $thread = $this->ForumThreads->patchEntity($thread, $data);
                if ($thread = $this->ForumThreads->save($thread)) {
                    $this->Flash->success(__('The thread has been saved.'));
                    return $this->redirect(['action' => 'publicThread', $thread->id]);
                }
                $this->Flash->error(__('The thread could not be saved. Please, try again.'));
            }
            
            $this->set(compact('organization'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            return $this->redirect($this->referer());
        }
    }

    public function publicThread($id = null)
    {
        try {
            $this->loadModel('ForumThreads');
            $thread = $this->ForumThreads->get($id,[
                'contain' => ['Users']
            ]);

            $comments = $this->ForumThreads->ForumPosts->find()->select(['date_created' => 'DATE (ForumPosts.created)'])->where(['ForumPosts.forum_thread_id' => $thread->id])->order(['ForumPosts.created' => 'DESC'])->contain(['Users'])->enableAutoFields(true);
            $comments = $this->paginate($comments);
            $comments = collection($comments)->groupBy('date_created');

            if ($this->request->is(['patch', 'put', 'post'])) {
                $data = $this->request->getData();
                $post = $this->ForumThreads->ForumPosts->newEntity($data);
                $post->forum_thread_id = $thread->id;
                $post->user_id = $this->Auth->user('id');
                $post->status = STATUS_ACTIVE;
                if ($this->ForumThreads->ForumPosts->save($post)) {
                    $this->Flash->success(__('The comment has been saved.'));
                    return $this->redirect(['action' => 'publicThread', $thread->id]);
                }
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
            }
            
            $this->set(compact('thread', 'comments'));
        } catch (\Throwable $ex) {
            $this->log($ex);
            return $this->redirect($this->referer());
        }
    }
}
