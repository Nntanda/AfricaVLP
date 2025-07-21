<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Scheduler command.
 */
class SchedulerCommand extends Command
{
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
        $parser->addOption('action', [
            'short' => 'a',
            'help' => 'action',
            'default' => 'run',
            'choices' => ['run', 'list']
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
        $action = $args->getOption('action');
        $io->out('Action --'.$action);

        if ($action === "list") {
            echo shell_exec('./vendor/bin/crunz schedule:list ./schedule');
            echo PHP_EOL;
        }

        if ($action === "run") {
            echo shell_exec('./vendor/bin/crunz schedule:run ./schedule');
            echo PHP_EOL;
        }
    }
}
