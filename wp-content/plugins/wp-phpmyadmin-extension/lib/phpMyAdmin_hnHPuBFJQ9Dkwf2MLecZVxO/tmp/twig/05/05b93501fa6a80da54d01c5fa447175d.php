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

/* server/status/queries/index.twig */
class __TwigTemplate_b6f4acaecba3b29bec24a9a45f6edfce extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "server/status/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 2
        $context["active"] = "queries";
        // line 1
        $this->parent = $this->loadTemplate("server/status/base.twig", "server/status/queries/index.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "
";
        // line 5
        if (($context["is_data_loaded"] ?? null)) {
            // line 6
            echo "<div class=\"row\">
  <h3 id=\"serverstatusqueries\">
    ";
// l10n: Questions is the name of a MySQL Status variable
echo _gettext("Questions since startup:");
            // line 13
            echo "    ";
            echo twig_escape_filter($this->env, PhpMyAdmin\Util::formatNumber(twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "total", [], "any", false, false, false, 13), 0), "html", null, true);
            echo "
    ";
            // line 14
            echo PhpMyAdmin\Html\MySQLDocumentation::show("server-status-variables", false, null, null, "statvar_Questions");
            echo "
  </h3>
</div>

<div class=\"row\">
  <ul>
    <li>ø ";
echo _gettext("per hour:");
            // line 20
            echo " ";
            echo twig_escape_filter($this->env, PhpMyAdmin\Util::formatNumber(twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "per_hour", [], "any", false, false, false, 20), 0), "html", null, true);
            echo "</li>
    <li>ø ";
echo _gettext("per minute:");
            // line 21
            echo " ";
            echo twig_escape_filter($this->env, PhpMyAdmin\Util::formatNumber(twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "per_minute", [], "any", false, false, false, 21), 0), "html", null, true);
            echo "</li>
    ";
            // line 22
            if ((twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "per_second", [], "any", false, false, false, 22) >= 1)) {
                // line 23
                echo "      <li>ø ";
echo _gettext("per second:");
                echo " ";
                echo twig_escape_filter($this->env, PhpMyAdmin\Util::formatNumber(twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "per_second", [], "any", false, false, false, 23), 0), "html", null, true);
                echo "</li>
    ";
            }
            // line 25
            echo "  </ul>
</div>

<div class=\"row\">
  <table id=\"serverStatusQueriesDetails\" class=\"table table-striped table-hover sortable col-md-4 col-12 w-auto\">
    <colgroup>
      <col class=\"namecol\">
      <col class=\"valuecol\" span=\"3\">
    </colgroup>

    <thead>
      <tr>
        <th scope=\"col\">";
echo _gettext("Statements");
            // line 37
            echo "</th>
        <th class=\"text-end\" scope=\"col\">";
// l10n: # = Amount of queries
echo _gettext("#");
            // line 38
            echo "</th>
        <th class=\"text-end\" scope=\"col\">";
echo _gettext("ø per hour");
            // line 39
            echo "</th>
        <th class=\"text-end\" scope=\"col\">%</th>
      </tr>
    </thead>

    <tbody>
      ";
            // line 45
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["queries"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["query"]) {
                // line 46
                echo "        <tr>
          <th scope=\"row\">";
                // line 47
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["query"], "name", [], "any", false, false, false, 47), "html", null, true);
                echo "</th>
          <td class=\"font-monospace text-end\">";
                // line 48
                echo twig_escape_filter($this->env, PhpMyAdmin\Util::formatNumber(twig_get_attribute($this->env, $this->source, $context["query"], "value", [], "any", false, false, false, 48), 5, 0, true), "html", null, true);
                echo "</td>
          <td class=\"font-monospace text-end\">";
                // line 49
                echo twig_escape_filter($this->env, PhpMyAdmin\Util::formatNumber(twig_get_attribute($this->env, $this->source, $context["query"], "per_hour", [], "any", false, false, false, 49), 4, 1, true), "html", null, true);
                echo "</td>
          <td class=\"font-monospace text-end\">";
                // line 50
                echo twig_escape_filter($this->env, PhpMyAdmin\Util::formatNumber(twig_get_attribute($this->env, $this->source, $context["query"], "percentage", [], "any", false, false, false, 50), 0, 2), "html", null, true);
                echo "</td>
        </tr>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['query'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 53
            echo "    </tbody>
  </table>

  <div id=\"serverstatusquerieschart\" class=\"w-100 col-12 col-md-6\" data-chart=\"";
            // line 56
            echo twig_escape_filter($this->env, json_encode(($context["chart"] ?? null)), "html", null, true);
            echo "\"></div>
</div>
";
        } else {
            // line 59
            echo "  ";
            echo $this->env->getFilter('error')->getCallable()(_gettext("Not enough privilege to view query statistics."));
            echo "
";
        }
        // line 61
        echo "
";
    }

    public function getTemplateName()
    {
        return "server/status/queries/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  177 => 61,  171 => 59,  165 => 56,  160 => 53,  151 => 50,  147 => 49,  143 => 48,  139 => 47,  136 => 46,  132 => 45,  124 => 39,  120 => 38,  115 => 37,  100 => 25,  92 => 23,  90 => 22,  85 => 21,  79 => 20,  69 => 14,  64 => 13,  58 => 6,  56 => 5,  53 => 4,  49 => 3,  44 => 1,  42 => 2,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "server/status/queries/index.twig", "/chroot/home/a6a56c84/reg-fb3eebd0c9.nxcli.io/html/wp-content/plugins/wp-phpmyadmin-extension/lib/phpMyAdmin_hnHPuBFJQ9Dkwf2MLecZVxO/templates/server/status/queries/index.twig");
    }
}
