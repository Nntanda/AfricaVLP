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

/* /Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/config/routes.php.twig */
class __TwigTemplate_b8d39b3f800fdd765556187a2f0caab530b643b971a1248d23f3a56af379df6f extends \Twig\Template
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
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->enter($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/config/routes.php.twig"));

        // line 16
        echo "<?php
use Cake\\Routing\\RouteBuilder;
use Cake\\Routing\\Router;
use Cake\\Routing\\Route\\DashedRoute;

Router::plugin(
    '";
        // line 22
        echo twig_escape_filter($this->env, ($context["plugin"] ?? null), "html", null, true);
        echo "',
    ['path' => '/";
        // line 23
        echo twig_escape_filter($this->env, ($context["routePath"] ?? null), "html", null, true);
        echo "'],
    function (RouteBuilder \$routes) {
        \$routes->fallbacks(DashedRoute::class);
    }
);
";
        
        $__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa->leave($__internal_770edd655cdeb606afc443e4edb1f19b4248a91788cb82e88bf8b9495a7c5cfa_prof);

    }

    public function getTemplateName()
    {
        return "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/config/routes.php.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 23,  41 => 22,  33 => 16,);
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
<?php
use Cake\\Routing\\RouteBuilder;
use Cake\\Routing\\Router;
use Cake\\Routing\\Route\\DashedRoute;

Router::plugin(
    '{{ plugin }}',
    ['path' => '/{{ routePath }}'],
    function (RouteBuilder \$routes) {
        \$routes->fallbacks(DashedRoute::class);
    }
);
", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/config/routes.php.twig", "/Applications/MAMP/htdocs/au_vlp_admin/vendor/cakephp/bake/src/Template/Bake//Plugin/config/routes.php.twig");
    }
}
