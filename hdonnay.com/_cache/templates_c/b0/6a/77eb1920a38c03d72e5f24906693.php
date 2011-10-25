<?php

/* macros_less.html */
class __TwigTemplate_b06a77eb1920a38c03d72e5f24906693 extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 8
        echo "
";
    }

    // line 1
    public function getstylesheet($baker = null, $path = null, $media = null)
    {
        $context = array_merge($this->env->getGlobals(), array(
            "baker" => $baker,
            "path" => $path,
            "media" => $media,
        ));

        ob_start();
        // line 2
        if ($this->getAttribute((isset($context['baker']) ? $context['baker'] : null), "is_baking", array(), "any", false, 2)) {
            // line 3
            echo "<link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, (isset($context['path']) ? $context['path'] : null), "html");
            echo ".css\" type=\"text/css\" media=\"";
            echo twig_escape_filter($this->env, ((twig_test_defined("media", $context)) ? (twig_default_filter((isset($context['media']) ? $context['media'] : null), "all")) : ("all")), "html");
            echo "\" />
";
        } else {
            // line 5
            echo "<link rel=\"stylesheet/less\" href=\"";
            echo twig_escape_filter($this->env, (isset($context['path']) ? $context['path'] : null), "html");
            echo ".less\" type=\"text/css\" media=\"";
            echo twig_escape_filter($this->env, ((twig_test_defined("media", $context)) ? (twig_default_filter((isset($context['media']) ? $context['media'] : null), "all")) : ("all")), "html");
            echo "\" />
";
        }

        return ob_get_clean();
    }

    // line 9
    public function getless_js($baker = null, $site_root = null)
    {
        $context = array_merge($this->env->getGlobals(), array(
            "baker" => $baker,
            "site_root" => $site_root,
        ));

        ob_start();
        // line 10
        if ((!$this->getAttribute((isset($context['baker']) ? $context['baker'] : null), "is_baking", array(), "any", false, 10))) {
            // line 11
            echo "<script src=\"";
            echo twig_escape_filter($this->env, (isset($context['site_root']) ? $context['site_root'] : null), "html");
            echo "js/less-1.0.41.min.js\" type=\"text/javascript\"></script>
";
        }

        return ob_get_clean();
    }

    public function getTemplateName()
    {
        return "macros_less.html";
    }
}
