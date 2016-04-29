<?php

/* twig.html */
class __TwigTemplate_906a00a3db80700e970cd61ab37fba19c0d34edb1859d438e51f4722ba03ecc3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <title>";
        // line 4
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</title>
    </head>
    <body>
        <ul id=\"navigation\">
        ";
        // line 8
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["navigation"]) ? $context["navigation"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 9
            echo "            <li><a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "href", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "caption", array()), "html", null, true);
            echo "</a></li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 11
        echo "        </ul>

        <h1>My Webpage</h1>
        ";
        // line 14
        echo twig_escape_filter($this->env, (isset($context["a_variable"]) ? $context["a_variable"] : null), "html", null, true);
        echo "
    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "twig.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  51 => 14,  46 => 11,  35 => 9,  31 => 8,  24 => 4,  19 => 1,);
    }
}
/* <!DOCTYPE html>*/
/* <html>*/
/*     <head>*/
/*         <title>{{ title }}</title>*/
/*     </head>*/
/*     <body>*/
/*         <ul id="navigation">*/
/*         {% for item in navigation %}*/
/*             <li><a href="{{ item.href }}">{{ item.caption }}</a></li>*/
/*         {% endfor %}*/
/*         </ul>*/
/* */
/*         <h1>My Webpage</h1>*/
/*         {{ a_variable }}*/
/*     </body>*/
/* </html>*/
