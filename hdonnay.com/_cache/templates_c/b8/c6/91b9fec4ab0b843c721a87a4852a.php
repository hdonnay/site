<?php

/* default.html */
class __TwigTemplate_b8c691b9fec4ab0b843c721a87a4852a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'header' => array($this, 'block_header'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
        );
    }

    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        $context['google'] = $this->env->loadTemplate("macros_google.html");
        // line 2
        $context['less'] = $this->env->loadTemplate("macros_less.html");
        // line 3
        echo "<!doctype html>
<html>
<head>
    <title>";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['site']) ? $context['site'] : null), "title", array(), "any", false, 6), "html");
        if ((!twig_test_empty($this->getAttribute((isset($context['page']) ? $context['page'] : null), "title", array(), "any", false, 6)))) {
            echo " &mdash; ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context['page']) ? $context['page'] : null), "title", array(), "any", false, 6), "html");
        }
        echo "</title>
\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
\t<meta name=\"description\" content=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['site']) ? $context['site'] : null), "description", array(), "any", false, 8), "html");
        echo "\" />
\t<meta name=\"author\" content=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['site']) ? $context['site'] : null), "author", array(), "any", false, 9), "html");
        echo "\" />
    <meta name=\"generator\" content=\"PieCrust ";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['piecrust']) ? $context['piecrust'] : null), "version", array(), "any", false, 10), "html");
        echo "\" />
    <meta name=\"template-engine\" content=\"Twig\" />
";
        // line 12
        $this->env->loadTemplate("blueprint.html")->display($context);
        // line 13
        echo "\t";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['google']) ? $context['google'] : null), "webfonts", array("Lobster", ), "method", false, 13), "html");
        echo "
\t";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['less']) ? $context['less'] : null), "stylesheet", array((isset($context['baker']) ? $context['baker'] : null), ($this->getAttribute((isset($context['site']) ? $context['site'] : null), "root", array(), "any", false, 14) . "css/default"), ), "method", false, 14), "html");
        echo "
    ";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['less']) ? $context['less'] : null), "less_js", array((isset($context['baker']) ? $context['baker'] : null), $this->getAttribute((isset($context['site']) ? $context['site'] : null), "root", array(), "any", false, 15), ), "method", false, 15), "html");
        echo "
</head>
<body>
    <div id=\"container\" class=\"container\">
        <div id=\"header\" class=\"span-24 last\">
\t\t\t";
        // line 20
        $this->displayBlock('header', $context, $blocks);
        // line 23
        echo "        </div>
        <div id=\"menu\" class=\"span-4 push-20 last\">
\t\t\t<div class=\"menu\">
\t\t\t\t<ul>
\t\t\t\t\t<li><a href=\"";
        // line 27
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['site']) ? $context['site'] : null), "root", array(), "any", false, 27), "html");
        echo "\">Home</a></li>
\t\t\t\t\t<li><a href=\"";
        // line 28
        echo twig_escape_filter($this->env, $this->env->getExtension('piecrust')->getUrl("blog"), "html");
        echo "\">Blog</a></li>
\t\t\t\t\t<li><a href=\"";
        // line 29
        echo twig_escape_filter($this->env, $this->env->getExtension('piecrust')->getUrl("about"), "html");
        echo "\">About</a></li>
\t\t\t\t</ul>
\t\t\t</div>
        </div>
        <div id=\"content\" class=\"span-20 pull-4 last\">
            <div class=\"content\">
                ";
        // line 35
        $this->displayBlock('content', $context, $blocks);
        // line 38
        echo "            </div>
        </div>
        <div id=\"footer\" class=\"span-24 last\">
\t\t\t";
        // line 41
        $this->displayBlock('footer', $context, $blocks);
        // line 44
        echo "        </div>
    </div>
</body>
</html>
";
    }

    // line 20
    public function block_header($context, array $blocks = array())
    {
        // line 21
        echo "            <h1><a href=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['site']) ? $context['site'] : null), "root", array(), "any", false, 21), "html");
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['site']) ? $context['site'] : null), "title", array(), "any", false, 21), "html");
        echo "</a></h1>
\t\t\t";
    }

    // line 35
    public function block_content($context, array $blocks = array())
    {
        // line 36
        echo "                ";
        echo (isset($context['content']) ? $context['content'] : null);
        echo "
        \t\t";
    }

    // line 41
    public function block_footer($context, array $blocks = array())
    {
        // line 42
        echo "            <p>";
        echo $this->getAttribute((isset($context['piecrust']) ? $context['piecrust'] : null), "branding", array(), "any", false, 42);
        echo "</p>
\t\t\t";
    }

    public function getTemplateName()
    {
        return "default.html";
    }
}
