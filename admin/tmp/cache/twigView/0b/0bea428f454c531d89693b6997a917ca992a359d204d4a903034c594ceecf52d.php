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

/* /Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/tests/bootstrap.php.twig */
class __TwigTemplate_25d716aeeddec9af89ae2f5b42686cd29c36fb26b9e53f2c0ca128891a459004 extends \Twig\Template
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
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->enter($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/tests/bootstrap.php.twig"));

        // line 18
        echo "<?php
/**
 * Test suite bootstrap for ";
        // line 20
        echo twig_escape_filter($this->env, ($context["plugin"] ?? null), "html", null, true);
        echo ".
 *
 * This function is used to find the location of CakePHP whether CakePHP
 * has been installed as a dependency of the plugin, or the plugin is itself
 * installed as a dependency of an application.
 */
\$findRoot = function (\$root) {
    do {
        \$lastRoot = \$root;
        \$root = dirname(\$root);
        if (is_dir(\$root . '/vendor/cakephp/cakephp')) {
            return \$root;
        }
    } while (\$root !== \$lastRoot);

    throw new Exception(\"Cannot find the root of the application, unable to run tests\");
};
\$root = \$findRoot(__FILE__);
unset(\$findRoot);

chdir(\$root);

require_once \$root . '/vendor/autoload.php';

/**
 * Define fallback values for required constants and configuration.
 * To customize constants and configuration remove this require
 * and define the data required by your plugin here.
 */
require_once \$root . '/vendor/cakephp/cakephp/tests/bootstrap.php';

if (file_exists(\$root . '/config/bootstrap.php')) {
    require \$root . '/config/bootstrap.php';

    return;
}
";
        
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->leave($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof);

    }

    public function getTemplateName()
    {
        return "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/tests/bootstrap.php.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 20,  33 => 18,);
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
 * Tests bootstrap file
 *
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
<?php
/**
 * Test suite bootstrap for {{ plugin }}.
 *
 * This function is used to find the location of CakePHP whether CakePHP
 * has been installed as a dependency of the plugin, or the plugin is itself
 * installed as a dependency of an application.
 */
\$findRoot = function (\$root) {
    do {
        \$lastRoot = \$root;
        \$root = dirname(\$root);
        if (is_dir(\$root . '/vendor/cakephp/cakephp')) {
            return \$root;
        }
    } while (\$root !== \$lastRoot);

    throw new Exception(\"Cannot find the root of the application, unable to run tests\");
};
\$root = \$findRoot(__FILE__);
unset(\$findRoot);

chdir(\$root);

require_once \$root . '/vendor/autoload.php';

/**
 * Define fallback values for required constants and configuration.
 * To customize constants and configuration remove this require
 * and define the data required by your plugin here.
 */
require_once \$root . '/vendor/cakephp/cakephp/tests/bootstrap.php';

if (file_exists(\$root . '/config/bootstrap.php')) {
    require \$root . '/config/bootstrap.php';

    return;
}
", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/tests/bootstrap.php.twig", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/tests/bootstrap.php.twig");
    }
}
