<?php

/* themes/bootstrap/templates/input/form-element-label.html.twig */
class __TwigTemplate_b73aaa22e91ee7e7d934710aafcc1641fc44f56ad6fbdeb5719d1314f42e6b27 extends Twig_Template
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
        $tags = array("set" => 22, "if" => 30);
        $filters = array();
        $functions = array();

        try {
            $this->env->getExtension('sandbox')->checkSecurity(
                array('set', 'if'),
                array(),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setTemplateFile($this->getTemplateName());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 22
        $context["classes"] = array(0 => "control-label", 1 => (((        // line 24
(isset($context["title_display"]) ? $context["title_display"] : null) == "after")) ? ("option") : ("")), 2 => ((((        // line 25
(isset($context["title_display"]) ? $context["title_display"] : null) == "invisible") &&  !((isset($context["is_checkbox"]) ? $context["is_checkbox"] : null) || (isset($context["is_radio"]) ? $context["is_radio"] : null)))) ? ("sr-only") : ("")), 3 => ((        // line 26
(isset($context["required"]) ? $context["required"] : null)) ? ("js-form-required") : ("")), 4 => ((        // line 27
(isset($context["required"]) ? $context["required"] : null)) ? ("form-required") : ("")));
        // line 30
        if ((( !twig_test_empty((isset($context["title"]) ? $context["title"] : null)) && ((isset($context["title_display"]) ? $context["title_display"] : null) == "invisible")) && ((isset($context["is_checkbox"]) ? $context["is_checkbox"] : null) || (isset($context["is_radio"]) ? $context["is_radio"] : null)))) {
            // line 35
            $context["attributes"] = $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "setAttribute", array(0 => "title", 1 => (isset($context["title"]) ? $context["title"] : null)), "method");
            // line 36
            $context["title"] = null;
        }
        // line 42
        if ((( !twig_test_empty((isset($context["title"]) ? $context["title"] : null)) || (isset($context["is_checkbox"]) ? $context["is_checkbox"] : null)) || (isset($context["is_radio"]) ? $context["is_radio"] : null))) {
            // line 43
            echo "<label";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => (isset($context["classes"]) ? $context["classes"] : null)), "method"), "html", null, true));
            echo ">";
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["element"]) ? $context["element"] : null), "html", null, true));
            echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true));
            // line 44
            if ((isset($context["description"]) ? $context["description"] : null)) {
                // line 45
                echo "<p class=\"help-block\">";
                echo $this->env->getExtension('sandbox')->ensureToStringAllowed($this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["description"]) ? $context["description"] : null), "html", null, true));
                echo "</p>";
            }
            // line 47
            echo "</label>";
        }
    }

    public function getTemplateName()
    {
        return "themes/bootstrap/templates/input/form-element-label.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 47,  66 => 45,  64 => 44,  58 => 43,  56 => 42,  53 => 36,  51 => 35,  49 => 30,  47 => 27,  46 => 26,  45 => 25,  44 => 24,  43 => 22,);
    }

    public function getSource()
    {
        return "{#
/**
 * @file
 * Default theme implementation for a form element label.
 *
 * Available variables:
 * - element: an input element.
 * - title: The label's text.
 * - title_display: Elements title_display setting.
 * - description: element description.
 * - required: An indicator for whether the associated form element is required.
 * - is_checkbox: Whether the label is outputted in checkbox context.
 * - is_radio: Whether the label is outputted in radio button context.
 * - attributes: A list of HTML attributes for the label.
 *
 * @ingroup templates
 *
 * @see template_preprocess_form_element_label()
 */
#}
{%-
  set classes = [
    'control-label',
    title_display == 'after' ? 'option',
    title_display == 'invisible' and not (is_checkbox or is_radio) ? 'sr-only',
    required ? 'js-form-required',
    required ? 'form-required',
  ]
-%}
{% if title is not empty and title_display == 'invisible' and (is_checkbox or is_radio) -%}
  {#
  Clear but preserve label text as attribute (e.g. for screen readers) for
  checkboxes/radio buttons when it actually should be invisible.
  #}
  {%- set attributes = attributes.setAttribute('title', title) -%}
  {%- set title = null -%}
{%- endif -%}
{#
Labels for single checkboxes/radios contain the element itself and thus have
always to be rendered regardless of whether they have a title or not.
#}
{%- if title is not empty or is_checkbox or is_radio -%}
  <label{{ attributes.addClass(classes) }}>{{ element }}{{ title }}
    {%- if description -%}
      <p class=\"help-block\">{{ description }}</p>
    {%- endif -%}
  </label>
{%- endif -%}
";
    }
}
