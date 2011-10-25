<?php

/* macros_google.html */
class __TwigTemplate_f7bf05eda483498dcdc56421bad0efbf extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 4
        echo "
";
    }

    // line 1
    public function getwebfonts($fontnames = null)
    {
        $context = array_merge($this->env->getGlobals(), array(
            "fontnames" => $fontnames,
        ));

        ob_start();
        // line 2
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://fonts.googleapis.com/css?family=";
        echo twig_escape_filter($this->env, (isset($context['fontnames']) ? $context['fontnames'] : null), "html");
        echo "\">
";

        return ob_get_clean();
    }

    // line 5
    public function getanalytics($siteId = null)
    {
        $context = array_merge($this->env->getGlobals(), array(
            "siteId" => $siteId,
        ));

        ob_start();
        // line 6
        echo "<script type=\"text/javascript\">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '";
        // line 9
        echo twig_escape_filter($this->env, (isset($context['siteId']) ? $context['siteId'] : null), "html");
        echo "']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
";

        return ob_get_clean();
    }

    public function getTemplateName()
    {
        return "macros_google.html";
    }
}
