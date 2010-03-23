<?php
namespace Bundle\TagHelperBundle\Helper;

use Symfony\Components\Templating\Helper\HelperInterface;
use Bundle\TagHelperBundle\Toolkit\StringToolkit;

class TagTemplateHelper implements HelperInterface
{
  protected $charset = 'UTF-8';
  protected $options;

  public function __construct(array $options = array())
  {
    $this->configure($options);
  }

  /**
   * Configures the current object.
   *
   * @param array $options     An array of options
   */
  public function configure(array $options = array())
  {
    $this->options = array_merge(
      null !== $this->options ? $this->options : $this->getDefaultOptions(),
      $options
    );
  }

  public function getDefaultOptions()
  {
    return array(
      'empty_elements'  => array('br', 'hr', 'img', 'input')
    );
  }

  /**
   * Sets the default charset.
   *
   * @param string $charset The charset
   */
  public function setCharset($charset)
  {
    $this->charset = $charset;
  }

  /**
   * Gets the default charset.
   *
   * @return string The default charset
   */
  public function getCharset()
  {
    return $this->charset;
  }

  public function getName()
  {
    return 'tag';
  }

  /**
   * Creates an HTML tag
   * @param string  $tagName      the tag name, or a CSS expression like div#an_id.a_class
   * @param array   $attributes   an optional array of HTML attributes
   * @param string  $content      the content of the tag, can be text or HTML
   * @param boolean $openAndClose whether to close the tag or not
   * @return string The HTML tag
   */
  public function tag($tagName, $attributes = array(), $content = false, $openAndClose = true)
  {
    if (!($tagName = trim($tagName)))
    {
      return '';
    }

    $tagOpt = array();

    // separate tag name from attribues in $tagName
    $firstSpacePos = strpos($tagName, ' ');

    if ($firstSpacePos)
    {
      $tagNameOpt = substr($tagName, $firstSpacePos + 1);
      $tagName = substr($tagName, 0, $firstSpacePos);

      // DMS STYLE - string opt in name
      StringToolkit::retrieveOptFromString($tagNameOpt, $tagOpt);
    }

    // JQUERY STYLE - css expression
    StringToolkit::retrieveCssFromString($tagName, $tagOpt);

    // ARRAY STYLE - array opt
    if (is_array($attributes) && !empty($attributes))
    {
      if (isset($attributes['json']))
      {
        $tagOpt['class'][] = json_encode($attributes['json']);
        unset($attributes['json']);
      }
      if (isset($attributes['class']))
      {
        $tagOpt['class'][] = is_array($attributes['class']) ? implode(' ', $attributes['class']) : $attributes['class'];
        unset($attributes['class']);
      }

      $tagOpt = array_merge($tagOpt, $attributes);
    }
    // SYMFONY STYLE - string opt
    elseif (is_string($attributes) && $content)
    {
      $attributes = StringToolkit::stringToArray($attributes);
      if (isset($attributes['class']))
      {
        $tagOpt['class'][] = explode(' ', $attributes['class']);
        unset($attributes['class']);
      }

      $tagOpt = array_merge($tagOpt, $attributes);
    }

    if (!$content)
    {
      if (!is_array($attributes))
      {
        $content = $attributes;
      }
      else // No opt
      {
        $content = null;
      }
    }

    $class = isset($tagOpt['class']) ? $tagOpt['class'] : array();
    
    if (isset($tagOpt['class']) && is_array($tagOpt['class']))
    {
      $tagOpt['class'] = implode(' ', array_unique($tagOpt['class']));
    }

    $optHtml = '';
    foreach ($tagOpt as $key => $val)
    {
      $optHtml .= ' '.$key.'="'.htmlentities($val, ENT_COMPAT, $this->charset).'"';
    }

    if(in_array($tagName, $this->options['empty_elements']))
    {
      $tag = '<'.$tagName.$optHtml.' />';
    }
    elseif ($openAndClose)
    {
      $tag = '<'.$tagName.$optHtml.'>'.$content.'</'.$tagName.'>';
    }
    else
    {
      $tag = '<'.$tagName.$optHtml.'>';
    }

    return $tag;
  }
}