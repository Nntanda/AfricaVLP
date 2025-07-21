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

/* /Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/phpunit.xml.dist.twig */
class __TwigTemplate_30188c8d856945271eb06d4cf296d8cb53edf490532fadbf9b8ea72709f6b158 extends \Twig\Template
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
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->enter($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/phpunit.xml.dist.twig"));

        // line 16
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<phpunit
    colors=\"true\"
    processIsolation=\"false\"
    stopOnFailure=\"false\"
    syntaxCheck=\"false\"
    bootstrap=\"tests/bootstrap.php\"
    >
    <php>
        <ini name=\"memory_limit\" value=\"-1\"/>
        <ini name=\"apc.enable_cli\" value=\"1\"/>
    </php>

    <!-- Add any additional test suites you want to run here -->
    <testsuites>
        <testsuite name=\"";
        // line 31
        echo twig_escape_filter($this->env, ($context["plugin"] ?? null), "html", null, true);
        echo "\">
            <directory>tests/TestCase/</directory>
        </testsuite>
    </testsuites>

    <!-- Setup a listener for fixtures -->
    <listeners>
        <listener class=\"\\Cake\\TestSuite\\Fixture\\FixtureInjector\">
            <arguments>
                <object class=\"\\Cake\\TestSuite\\Fixture\\FixtureManager\"/>
            </arguments>
        </listener>
    </listeners>

    <filter>
        <whitelist>
            <directory suffix=\".php\">src/</directory>
        </whitelist>
    </filter>

</phpunit>
";
        
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->leave($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof);

    }

    public function getTemplateName()
    {
        return "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/phpunit.xml.dist.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 31,  33 => 16,);
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
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
#}
<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<phpunit
    colors=\"true\"
    processIsolation=\"false\"
    stopOnFailure=\"false\"
    syntaxCheck=\"false\"
    bootstrap=\"tests/bootstrap.php\"
    >
    <php>
        <ini name=\"memory_limit\" value=\"-1\"/>
        <ini name=\"apc.enable_cli\" value=\"1\"/>
    </php>

    <!-- Add any additional test suites you want to run here -->
    <testsuites>
        <testsuite name=\"{{ plugin }}\">
            <directory>tests/TestCase/</directory>
        </testsuite>
    </testsuites>

    <!-- Setup a listener for fixtures -->
    <listeners>
        <listener class=\"\\Cake\\TestSuite\\Fixture\\FixtureInjector\">
            <arguments>
                <object class=\"\\Cake\\TestSuite\\Fixture\\FixtureManager\"/>
            </arguments>
        </listener>
    </listeners>

    <filter>
        <whitelist>
            <directory suffix=\".php\">src/</directory>
        </whitelist>
    </filter>

</phpunit>
", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/phpunit.xml.dist.twig", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/phpunit.xml.dist.twig");
    }
}
