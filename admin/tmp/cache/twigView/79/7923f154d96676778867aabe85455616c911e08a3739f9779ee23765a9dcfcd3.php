<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* /Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Command/command.twig */
class __TwigTemplate_993d56e884ed95586c11ecb49cf4c9c10c15c5c8dbee882048c423cc4ef5b8e0 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa = $this->env->getExtension("WyriHaximus\\TwigView\\Lib\\Twig\\Extension\\Profiler");
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->enter($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Command/command.twig"));

        // line 16
        echo "<?php
namespace ";
        // line 17
        echo twig_escape_filter($this->env, ($context["namespace"] ?? null), "html", null, true);
        echo "\\Command;

use Cake\\Console\\Arguments;
use Cake\\Console\\Command;
use Cake\\Console\\ConsoleIo;
use Cake\\Console\\ConsoleOptionParser;

/**
 * ";
        // line 25
        echo twig_escape_filter($this->env, ($context["name"] ?? null), "html", null, true);
        echo " command.
 */
class ";
        // line 27
        echo twig_escape_filter($this->env, ($context["name"] ?? null), "html", null, true);
        echo "Command extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \\Cake\\Console\\ConsoleOptionParser \$parser The parser to be defined
     * @return \\Cake\\Console\\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser \$parser)
    {
        \$parser = parent::buildOptionParser(\$parser);

        return \$parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \\Cake\\Console\\Arguments \$args The command arguments.
     * @param \\Cake\\Console\\ConsoleIo \$io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments \$args, ConsoleIo \$io)
    {
    }
}
";
        
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->leave($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof);

    }

    public function getTemplateName()
    {
        return "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Command/command.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  52 => 27,  47 => 25,  36 => 17,  33 => 16,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         1.7.4
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
#}
<?php
namespace {{ namespace }}\\Command;

use Cake\\Console\\Arguments;
use Cake\\Console\\Command;
use Cake\\Console\\ConsoleIo;
use Cake\\Console\\ConsoleOptionParser;

/**
 * {{ name }} command.
 */
class {{ name }}Command extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \\Cake\\Console\\ConsoleOptionParser \$parser The parser to be defined
     * @return \\Cake\\Console\\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser \$parser)
    {
        \$parser = parent::buildOptionParser(\$parser);

        return \$parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \\Cake\\Console\\Arguments \$args The command arguments.
     * @param \\Cake\\Console\\ConsoleIo \$io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments \$args, ConsoleIo \$io)
    {
    }
}
", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Command/command.twig", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Command/command.twig");
    }
}
