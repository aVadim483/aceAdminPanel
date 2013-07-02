<?php
/*---------------------------------------------------------------------------
 * @Plugin Name: aceAdminPanel
 * @Plugin Id: aceadminpanel
 * @Plugin URI: 
 * @Description: Advanced Administrator's Panel for LiveStreet/ACE
 * @Version: 2.0.382
 * @Author: Vadim Shemarov (aka aVadim)
 * @Author URI: 
 * @LiveStreet Version: 1.0.1
 * @File Name: %%filename%%
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *----------------------------------------------------------------------------
 */

/**
 * DOM Fragmental
 */
require_once 'simple_html_dom.php';

define('HDOM_ROOT_PSEUDOTAG', 'hdomrootpseudotag');
define('HDOM_WRAP_REPLACE', '{{$_}}');

class DomFrag extends simple_html_dom
{
    public function __construct($sHtml)
    {
        $this->block_tags[HDOM_ROOT_PSEUDOTAG] = 1;

        $this->dom_node_class = 'DomFragNode';
        parent::__construct($sHtml, false, false, 'UTF-8', false);
    }

    /**
     * @param   string  $sFile
     * @return  DomFrag
     */
    static public function NewFromFile($sFile)
    {
        return new DomFrag(file_get_contents($sFile));
    }

    /**
     * @param   string  $sHtml
     * @return  DomFrag
     */
    static public function NewFromString($sHtml)
    {
        return new DomFrag($sHtml);
    }

    public function html()
    {
        return $this->outertext;
    }

    // remove noise from html content
    // save the noise in the $this->noise array.
    protected function remove_noise($pattern, $remove_tag = false)
    {
        $count = preg_match_all($pattern, $this->doc, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        if ($count) {
            $idx = ($remove_tag) ? 0 : 1;
            for ($i = $count - 1; $i >= 0; --$i) {
                $key = '___noise___' . sprintf('%05d', count($this->noise) + 1);
                $this->noise[$key] = $matches[$i][$idx][0];
                $this->doc = substr_replace($this->doc, $key, $matches[$i][$idx][1], strlen($matches[$i][$idx][0]));
            }

            // reset the length of content
            $this->size = strlen($this->doc);
            if ($this->size > 0) {
                $this->char = $this->doc[0];
            }
        }
    }

    // restore noise to html content
    function restore_noise($text)
    {
        while (($pos = strpos($text, '___noise___')) !== false) {
            // Sometimes there is a broken piece of markup, and we don't GET the pos+11 etc... token which indicates a problem outside of us...
            if (strlen($text) > $pos + 15) {
                $key = '___noise___' . $text[$pos + 11] . $text[$pos + 12] . $text[$pos + 13] . $text[$pos + 14] . $text[$pos + 15];

                if (isset($this->noise[$key])) {
                    $text = substr($text, 0, $pos) . $this->noise[$key] . substr($text, $pos + 16);
                } else {
                    // do this to prevent an infinite loop.
                    $text = substr($text, 0, $pos) . 'UNDEFINED NOISE FOR KEY: ' . $key . substr($text, $pos + 16);
                }
            } else {
                // There is no valid key being given back to us... We must get rid of the ___noise___ or we will have a problem.
                $text = substr($text, 0, $pos) . 'NO NUMERIC NOISE KEY' . substr($text, $pos + 11);
            }
        }
        return $text;
    }

    function load($str, $lowercase = true, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT, $defaultSpanText = DEFAULT_SPAN_TEXT)
    {
        $str = '<' . HDOM_ROOT_PSEUDOTAG . '>' . $str . '</' . HDOM_ROOT_PSEUDOTAG . '>';

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
        // выражения {if}..{/if} внутри значения атрибутов тега
        $this->remove_noise('#\<\w+[^>]*["]({if[^"]*{\/if})["]#siuU', false);
        $this->remove_noise('#\<\w+[^>]*[\']({if[^\']*{\/if})[\']#siuU', false);

        // выражения {if}..{/if} внутри тега
        $this->remove_noise('#\<\w+[^>]*({if[^>]*{\/if}).*\/?\>#siuU', false);

        // прочие Smarty-выражения
        $this->remove_noise("#\{[\$\w\/][^\}]*\}#siuU", true);

        $cnt = 0;
        /*
         * отвратительный хак, но без него иногда пропадают {/if} в конце тегов
         */
        $this->doc = preg_replace('#(["\'])___noise___(\d{5}\s*\/?)\>#siu', '$1 ___noise___$2>', $this->doc, -1, $cnt);
        $this->size += $cnt;
        $this->doc = preg_replace('#(["\'])___noise___(\d{5})(["\'])(\/?)\>#siu', '$1___noise___$2$3 $4>', $this->doc, -1, $cnt);
        $this->size += $cnt;

        // parsing
        while ($this->parse()) ;
        // end

        $this->root->_[HDOM_INFO_END] = $this->cursor;
        //$this->parse_charset();
        $this->_charset = 'UTF-8';

        // "закрываем" незакрытые теги
        /* псевдорутовый тег решает эту проблему
        foreach ($this->nodes as $oNode) {
            //if (!isset($oNode->_[HDOM_INFO_END])) $oNode->_[HDOM_INFO_END] = $this->cursor;
        }
        */

        // make load function chainable
        return $this;

    }

    protected function read_tag()
    {
        if ($this->char !== '<') {
            $this->root->_[HDOM_INFO_END] = $this->cursor;
            return false;
        }
        // read internal smarty
        return parent::read_tag();
    }
}

class DomFragNode extends simple_html_dom_node
{
    public $tag_level = 0;

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

    public function wrap($sHtml)
    {
        if (strpos($sHtml, HDOM_WRAP_REPLACE))
            $this->outertext = str_replace(HDOM_WRAP_REPLACE, $this->outertext, $sHtml);
        return $this;
    }

    public function html($sHtml = null)
    {
        if (func_num_args()) {
            $this->innertext = $sHtml;
            return $this;
        } else {
            return $this->innertext;
        }
    }

    public function text($sText = null)
    {
        if (func_num_args()) {
            $this->plaintext = $sText;
            return $this;
        } else {
            return parent::text();
        }
    }

    public function find($selector, $idx = null, $lowercase = false)
    {
        if (preg_match("':([\w\-]+)(\((\d+)\))?$'siu", $selector, $m, PREG_OFFSET_CAPTURE)) {
            $sPseudo = $m[1][0];
            $nNumChild = (isset($m[3]) ? $m[3][0] : null);
            $selector = substr($selector, 0, $m[0][1]);
        } else {
            $sPseudo = '';
            $nNumChild = null;
        }
        if (is_null($idx) AND !is_null($nNumChild)) $idx = $nNumChild;
        if ($sPseudo == 'parent') {
            $aElements = parent::find($selector, $idx, $lowercase);
            $aNodes = array();
            if ($aElements) {
                foreach ($aElements as $el) {
                    $p = $el->parent();
                    if ($p) $aNodes[] = $p;
                }
            }
        } elseif ($sPseudo == 'eq') {
            $aNodes = parent::find($selector, $idx, $lowercase);
        } elseif ($sPseudo == 'nth-child') {
            $aNodes = parent::find($selector, $idx - 1, $lowercase);
        } elseif ($sPseudo == 'first') {
            $aNodes = parent::find($selector, 1, $lowercase);
        } elseif ($sPseudo == 'last') {
            $aNodes = parent::find($selector, -1, $lowercase);
        } else {
            $aNodes = parent::find($selector, $idx, $lowercase);
        }
        return new DomFragSet($this->dom, $aNodes);
    }

    public function hash()
    {
        return $this->tag . '-' . spl_object_hash($this);
    }

    public function name()
    {
        return $this->nodeName();
    }

    public function attr()
    {
        return $this->attr;
    }

    public function tag()
    {
        if ($this->tag != 'text')
            return $this->makeup();
    }

    public function setDummy()
    {
        $this->nodetype = HDOM_TYPE_UNKNOWN;
        $this->tag = '__dummy__';
    }

    public function isDummy()
    {
        return ($this->tag == '__dummy__');
    }

    function outertext()
    {
        if ($this->tag === HDOM_ROOT_PSEUDOTAG) return $this->innertext();
        return parent::outertext();
    }
}

class DomFragSet
{
    protected $oDom = null;
    protected $sSelector;
    protected $aNodes = array();

    protected $aGroupMethods = array('append', 'prepend', 'after', 'before', 'replaceWith', 'wrap');
    protected $aSingleMethods = array('hash', 'html', 'text', 'name', 'attr', 'tag');

    public function __construct($oDom, $aNodes = array())
    {
        $this->oDom = $oDom;
        if ($aNodes) {
            if (!is_array($aNodes)) $aNodes = array($aNodes);
            $this->addNodes($aNodes);
        }
    }

    protected function _addNodes($oNode)
    {
        $this->aNodes[$oNode->hash()] = $oNode;
    }

    protected function _getNodes($aKeys = null, $bDelete = false)
    {
        if ($this->aNodes) {
            if ($aKeys) {
                if (!is_array($aKeys)) {
                    if (is_numeric($aKeys) AND intval($aKeys) == $aKeys) {
                        $aKeys = $this->_seekKey($aKeys);
                    }
                    if (!$aKeys) return null;

                    $aKeys = array($aKeys);
                    $bSingle = true;
                } else {
                    $bSingle = false;
                }
                $aNodes = array();
                foreach ($aKeys as $sKey) {
                    if (array_key_exists($sKey, $this->aNodes)) {
                        $aNodes[$sKey] = $this->aNodes[$sKey];
                        if ($bDelete) unset($aNodes{$sKey});
                    }
                }
                if ($bSingle)
                    return array_shift($aNodes);
                else
                    return $aNodes;
            } else {
                return $this->aNodes;
            }
        } else
            return array();
    }

    protected function _seekKey($nOrd)
    {
        $sResult = null;
        $nOrd = intval($nOrd);
        if ($nOrd) {
            $aKeys = array_keys($this->_getNodes());
            if ($nOrd > 0 AND $nOrd <= sizeof($aKeys)) {
                $aKeys = array_slice($aKeys, $nOrd - 1, 1);
                $sResult = $aKeys[0];
            } elseif ($nOrd < 0 AND -$nOrd <= sizeof($aKeys)) {
                $aKeys = array_slice($aKeys, sizeof($aKeys) + $nOrd - 1, 1);
                $sResult = $aKeys[0];
            }
        }
        return $sResult;
    }

    protected function _firstNode()
    {
        if ($sKey = $this->_seekKey(1)) {
            return $this->node($sKey);
        }
        return null;
    }

    protected function _lastNode()
    {
        if ($sKey = $this->_seekKey(-1)) {
            return $this->node($sKey);
        }
        return null;
    }

    protected function _makeEmptyNode()
    {
        $oNode = new DomFragNode($this->oDom);
        $oNode->setDummy();
        return $oNode;
    }

    public function __call($sName, $aArgs)
    {
        if (in_array($sName, $this->aGroupMethods)) {
            $aNodes = array_reverse($this->nodes());
            foreach ($aNodes as $sKey => $oNode) {
                call_user_func_array(array($oNode, $sName), $aArgs);
                //$this->aNodes[$sKey] = call_user_func_array(array($oNode, $sName), $aArgs);
                //$oNode->$sName($aArgs[0]);
                //$this->aNodes[$sKey]->$sName($aArgs[0]);
            }
        } elseif (in_array($sName, $this->aSingleMethods)) {
            $oNode = $this->first();
            return call_user_func_array(array($oNode, $sName), $aArgs);
        }
        return $this;
    }

    public function count()
    {
        return sizeof($this->nodes());
    }

    public function addNode($oNode)
    {
        $this->_addNodes($oNode);
    }

    public function addNodes($aNodes)
    {
        if ($aNodes instanceof DomFragSet) $aNodes = $aNodes->nodes();
        foreach ($aNodes as $oNode) {
            $this->addNode($oNode);
        }
    }

    public function nodes()
    {
        return $this->_getNodes();
    }

    public function node($nKey)
    {
        return $this->_getNodes($nKey);
    }

    public function find($sSelector)
    {
        $this->sSelector = $sSelector;
        $oDomFragSet = new DomFragSet($this->oDom);
        /** var $oNode DomFragNode */
        foreach ($this->_getNodes() as $oNode) {
            $oFoundNodes = $oNode->find($sSelector);
            $oDomFragSet->addNodes($oFoundNodes);
        }
        return $oDomFragSet;
    }

    public function first()
    {
        if ($aNode = $this->_firstNode())
            return $aNode;
        return $this->_makeEmptyNode();
    }

    public function last()
    {
        if ($aNode = $this->_lastNode())
            return $aNode;
        return $this->_makeEmptyNode();
    }

    public function hash()
    {
        return $this->first()->hash();
    }

    public function html()
    {
        return $this->last()->html();
    }

}

// EOF