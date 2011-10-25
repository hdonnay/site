<?php

/* blueprint.html */
class __TwigTemplate_66ded69bb2edec9ffee1d08189851425 extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['site']) ? $context['site'] : null), "root", array(), "any", false, 1), "html");
        echo "css/blueprint/screen.css\" type=\"text/css\" media=\"screen, projection\" />
<link rel=\"stylesheet\" href=\"";
        // line 2
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['site']) ? $context['site'] : null), "root", array(), "any", false, 2), "html");
        echo "css/blueprint/print.css\" type=\"text/css\" media=\"print\" /> 
<!--[if lt IE 8]>
    <link rel=\"stylesheet\" href=\"";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context['site']) ? $context['site'] : null), "root", array(), "any", false, 4), "html");
        echo "css/blueprint/ie.css\" type=\"text/css\" media=\"screen, projection\" />
<![endif]-->
";
    }

    public function getTemplateName()
    {
        return "blueprint.html";
    }
}
