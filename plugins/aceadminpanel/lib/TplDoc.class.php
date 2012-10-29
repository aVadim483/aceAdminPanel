<?php

require_once 'simple_html_dom.php';

class TplDoc extends simple_html_dom
{
    public function __construct($sHtml)
    {
        $this->dom_node_class = 'TplNode';
        parent::__construct($sHtml, true, false, 'UTF-8', false);
    }

    public function html()
    {
        return $this->outertext;
    }

    // remove noise from html content
    // save the noise in the $this->noise array.
    protected function remove_noise($pattern, $remove_tag=false)
    {
        global $debugObject;
        if (is_object($debugObject)) { $debugObject->debugLogEntry(1); }

        $count = preg_match_all($pattern, $this->doc, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);

        for ($i=$count-1; $i>-1; --$i)
        {
            $key = '___noise___'.sprintf('%05d', count($this->noise)+1000);
            if (is_object($debugObject)) { $debugObject->debugLog(2, 'key is: ' . $key); }
            $idx = ($remove_tag) ? 0 : 1;
            $this->noise[$key] = $matches[$i][$idx][0];
            $this->doc = substr_replace($this->doc, $key, $matches[$i][$idx][1], strlen($matches[$i][$idx][0]));
        }

        // reset the length of content
        $this->size = strlen($this->doc);
        if ($this->size>0)
        {
            $this->char = $this->doc[0];
        }
    }

    function load($str, $lowercase=true, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT)
    {
        global $debugObject;

        // prepare
        $this->prepare($str, $lowercase, $stripRN, $defaultBRText, $defaultSpanText);
        // strip out comments
        $this->remove_noise("'<!--(.*?)-->'is");
        // strip out cdata
        $this->remove_noise("'<!\[CDATA\[(.*?)\]\]>'is", true);
        // Per sourceforge http://sourceforge.net/tracker/?func=detail&aid=2949097&group_id=218559&atid=1044037
        // Script tags removal now preceeds style tag removal.
        // strip out <script> tags
        $this->remove_noise("'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is");
        $this->remove_noise("'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is");
        // strip out <style> tags
        $this->remove_noise("'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is");
        $this->remove_noise("'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is");
        // strip out preformatted tags
        $this->remove_noise("'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is");
        // strip out server side scripts
        $this->remove_noise("'(<\?)(.*?)(\?>)'s", true);

        // strip smarty scripts
        $this->remove_noise("'(\{[\$\w\/])(.*?)(\})'s", true);

        // parsing
        while ($this->parse());
        // end
        $this->root->_[HDOM_INFO_END] = $this->cursor;
        $this->parse_charset();

        // make load function chainable
        return $this;

    }
}

class TplNode extends simple_html_dom_node
{
    public function prepend($sHtml)
    {
        $this->innertext = $sHtml . $this->innertext;
        return $this;
    }

    public function append($sHtml)
    {
        $this->innertext = $this->innertext . $sHtml;
        return $this;
    }

    public function before($sHtml)
    {
        $this->outertext = $sHtml . $this->outertext;
        return $this;
    }

    public function after($sHtml)
    {
        $this->outertext = $this->outertext . $sHtml;
        return $this;
    }

    public function replaceWith($sHtml)
    {
        $this->outertext = $sHtml;
        return $this;
    }

    public function html($sHtml=null)
    {
        if (func_num_args()) {
            $this->innertext = $sHtml;
            return $this;
        } else {
            return $this->innertext;
        }
    }

    public function text($sText=null)
    {
        if (func_num_args()) {
            $this->plaintext = $sText;
            return $this;
        } else {
            return parent::text();
        }
    }

    public function find($selector, $idx=null, $lowercase=false)
    {
        if (substr($selector, -7) == ':parent') {
            $aElements = parent::find(substr($selector, 0, strlen($selector)-7), $idx, $lowercase);
            $aResult = array();
            if ($aElements) {
                foreach($aElements as $el) {
                    $p = $el->parent();
                    if ($p) $aResult[] = $p;
                }
            }
        } else {
            $aResult = parent::find($selector, $idx, $lowercase);
        }
        return $aResult;
    }

}

// EOF