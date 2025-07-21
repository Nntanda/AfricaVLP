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

/* /Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/composer.json.twig */
class __TwigTemplate_b1a7447cc0f07d887d24d7c9578bc5c49039d05be0bfb5d28423ab9d09007781 extends \Twig\Template
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
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->enter($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/composer.json.twig"));

        // line 16
        $context["namespace"] = twig_replace_filter(($context["namespace"] ?? null), ["\\" => "\\\\"]);
        // line 17
        echo "{
    \"name\": \"";
        // line 18
        echo twig_escape_filter($this->env, ($context["package"] ?? null), "html", null, true);
        echo "\",
    \"description\": \"";
        // line 19
        echo twig_escape_filter($this->env, ($context["plugin"] ?? null), "html", null, true);
        echo " plugin for CakePHP\",
    \"type\": \"cakephp-plugin\",
    \"license\": \"MIT\",
    \"require\": {
        \"cakephp/cakephp\": \"^3.5\"
    },
    \"require-dev\": {
        \"phpunit/phpunit\": \"^5.7.14|^6.0\"
    },
    \"autoload\": {
        \"psr-4\": {
            \"";
        // line 30
        echo twig_escape_filter($this->env, ($context["namespace"] ?? null), "html", null, true);
        echo "\\\\\": \"src/\"
        }
    },
    \"autoload-dev\": {
        \"psr-4\": {
            \"";
        // line 35
        echo twig_escape_filter($this->env, ($context["namespace"] ?? null), "html", null, true);
        echo "\\\\Test\\\\\": \"tests/\",
            \"Cake\\\\Test\\\\\": \"vendor/cakephp/cakephp/tests/\"
        }
    }
}
";
        
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->leave($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof);

    }

    public function getTemplateName()
    {
        return "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/composer.json.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  64 => 35,  56 => 30,  42 => 19,  38 => 18,  35 => 17,  33 => 16,);
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
{% set namespace = namespace|replace({'\\\\': '\\\\\\\\'}) %}
{
    \"name\": \"{{ package }}\",
    \"description\": \"{{ plugin }} plugin for CakePHP\",
    \"type\": \"cakephp-plugin\",
    \"license\": \"MIT\",
    \"require\": {
        \"cakephp/cakephp\": \"^3.5\"
    },
    \"require-dev\": {
        \"phpunit/phpunit\": \"^5.7.14|^6.0\"
    },
    \"autoload\": {
        \"psr-4\": {
            \"{{ namespace }}\\\\\": \"src/\"
        }
    },
    \"autoload-dev\": {
        \"psr-4\": {
            \"{{ namespace }}\\\\Test\\\\\": \"tests/\",
            \"Cake\\\\Test\\\\\": \"vendor/cakephp/cakephp/tests/\"
        }
    }
}
", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/composer.json.twig", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/composer.json.twig");
    }
}
