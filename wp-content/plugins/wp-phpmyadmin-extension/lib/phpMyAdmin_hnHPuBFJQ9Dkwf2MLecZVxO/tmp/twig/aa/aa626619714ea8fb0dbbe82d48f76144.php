<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* navigation/tree/quick_warp.twig */
class __TwigTemplate_07cd63403477613a1491cf2bbba6a47a extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div class=\"pma_quick_warp\">
    ";
        // line 2
        if (($context["recent"] ?? null)) {
            echo ($context["recent"] ?? null);
        }
        // line 3
        echo "    ";
        if (($context["favorite"] ?? null)) {
            echo ($context["favorite"] ?? null);
        }
        // line 4
        echo "    <div class=\"clearfloat\"></div>
</div>
";
    }

    public function getTemplateName()
    {
        return "navigation/tree/quick_warp.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 4,  44 => 3,  40 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "navigation/tree/quick_warp.twig", "/chroot/home/a6a56c84/reg-fb3eebd0c9.nxcli.io/html/wp-content/plugins/wp-phpmyadmin-extension/lib/phpMyAdmin_hnHPuBFJQ9Dkwf2MLecZVxO/templates/navigation/tree/quick_warp.twig");
    }
}
