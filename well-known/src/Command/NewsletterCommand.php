<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use App\Email\EmailSender;
use Cake\Mailer\Email;

/**
 * Newsletter command.
 */
class NewsletterCommand extends Command
{
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
    const QUARTERLY = 'quarterly';
    const MIN_NEWS = 3;

    public function initialize()
    {
        parent::initialize();
        $this->Email = new EmailSender();
        $this->_email = new Email('Sendgrid');
        $this->_email->setEmailFormat('html');
        // $this->_email->setDomain('');
    }
    
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);
        $parser->addArgument('type', [
            'help' => 'The type of newsletter to send.',
            'required' => true,
            'choices' => [SELF::WEEKLY, SELF::MONTHLY, SELF::QUARTERLY]
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $io->out('-- Newsletter Command');
        $type = $args->getArgument('type');
        $this->_updateSubscribers($io);
        switch ($type) {
            case SELF::MONTHLY:
                $this->sendMonthly($io);
                break;
            
            case SELF::QUARTERLY:
                $this->sendQuarterly($io);
                break;
            
            default:
                $this->sendWeekly($io);
                break;
        }
    }

    public function sendWeekly($io)
    {
        $this->_loadNewsletterModels();
        $subscribers = $this->_getSubscribers(SELF::WEEKLY);
        $newsContents = $this->News->find()->where(['News.id NOT IN' => $this->NewsletterContents->find()->where(['NewsletterContents.object_model' => 'News'])->select(['NewsletterContents.object_id'])])->where(['News.organization_id IS' => null, 'News.image IS NOT' => null])->limit(5)->toList();

        if(count($newsContents) >= SELF::MIN_NEWS && (!empty($subscribers))) {
            $title = '[Newsletter] Weekly News from African Union Linkage Platform';
            $this->Email->sendNewsletterEmail($subscribers, $newsContents, $title, $this->_email);
            
            $io->out('Newsletter Sent Successfully');
            $this->_saveNewsletterContent($io, $newsContents, 'News');
        }

    }

    public function sendMonthly($io)
    {
        $this->_loadNewsletterModels();
        $subscribers = $this->_getSubscribers(SELF::MONTHLY);
        $newsContents = $this->News->find()->where(['News.id NOT IN' => $this->NewsletterContents->find()->where(['NewsletterContents.object_model' => 'News'])->select(['NewsletterContents.object_id'])])->where(['News.organization_id IS' => null, 'News.image IS NOT' => null])->limit(5)->toList();

        if(count($newsContents) >= SELF::MIN_NEWS && (!empty($subscribers))) {
            $title = '[Newsletter] Monthly News from African Union Linkage Platform';
            $this->Email->sendNewsletterEmail($subscribers, $newsContents, $title, $this->_email);
            
            $io->out('Newsletter Sent Successfully');
            $this->_saveNewsletterContent($io, $newsContents, 'News');
        }

    }

    public function sendQuarterly($io)
    {
        $this->_loadNewsletterModels();
        $subscribers = $this->_getSubscribers(SELF::QUARTERLY);
        $newsContents = $this->News->find()->where(['News.id NOT IN' => $this->NewsletterContents->find()->where(['NewsletterContents.object_model' => 'News'])->select(['NewsletterContents.object_id'])])->where(['News.organization_id IS' => null, 'News.image IS NOT' => null])->limit(5)->toList();

        if(count($newsContents) >= SELF::MIN_NEWS && (!empty($subscribers))) {
            $title = '[Newsletter] Quarterly News from African Union Linkage Platform';
            $this->Email->sendNewsletterEmail($subscribers, $newsContents, $title, $this->_email);
            
            $io->out('Newsletter Sent Successfully');
            $this->_saveNewsletterContent($io, $newsContents, 'News');
        }

    }

    public function _loadNewsletterModels()
    {
        $this->loadModel('News');
        $this->loadModel('BlogPosts');
        $this->loadModel('NewsletterContents');
    }

    public function _getSubscribers($type)
    {
        $this->loadModel('NewsletterSubscriptions');
        return $this->NewsletterSubscriptions->find()->where([$type => true])->extract('email')->toArray();
    }

    public function _updateSubscribers($io)
    {
        $this->loadModel('Users');
        $this->loadModel('NewsletterSubscriptions');
        
        $users = $this->Users->find()->select('email')->where(['Users.email NOT IN' => $this->NewsletterSubscriptions->find()->select(['NewsletterSubscriptions.email'])]);
        $list = $users->map(function ($user) {
            return ['email' => $user->email];
        })->toArray();
        
        $newSubscribers = $this->NewsletterSubscriptions->newEntities($list);
        return $this->NewsletterSubscriptions->saveMany($newSubscribers);
    }

    public function _saveNewsletterContent($io, $contents, $objectModel)
    {
        $this->loadModel('NewsletterContents');
        $contents = new \Cake\Collection\Collection($contents);
        $contentsData = $contents->map(function ($object) use ($objectModel) {
            return ['object_id' => $object->id, 'object_model' => $objectModel];
        })->toArray();
        
        $newContents = $this->NewsletterContents->newEntities($contentsData);
        return $this->NewsletterContents->saveMany($newContents);
    }
}
