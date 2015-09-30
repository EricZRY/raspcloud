<?php
/**
 * 编译模板文件，并且返回模板文件的路径
 * @param $file
 * @return string
 */
function renderTemplate($module, $action = null){
    $file = "$module";
    if (!empty($action)) $file .= "/$action";
    if (!empty($GLOBALS['TEMPLATE_FILE'])) {
        $tplfile = $GLOBALS['TEMPLATE_FILE'];
        if (!file_exists($tplfile)) die('no tpl found : '.$tplfile);
        $file_name = pathinfo($tplfile, PATHINFO_FILENAME);
        $objfile = TEMPLATE_DATA_DIR."/$file_name.tpl.php";
        unset($GLOBALS['TEMPLATE_FILE']);
    } else {
        if(defined('TEMPLATE_EXT')){
            $ext = TEMPLATE_EXT;
        }else{
            $ext = ".htm";
        }
        $tplfile = TEMPLATE_DIR.'/'.$file.$ext;
        if(!file_exists($tplfile)) return false;
        $objfile = TEMPLATE_DATA_DIR.'/'.$file.'.tpl.php';
    }
    if(!file_exists($objfile) || filemtime($tplfile) > filemtime($objfile)) {
        if(!file_exists(TEMPLATE_DATA_DIR)){
            mkdir(TEMPLATE_DATA_DIR,0777,true);
        }
        if(!file_exists(dirname($objfile))){
            mkdir(dirname($objfile),0777,true);
        }
        $t = new Template();
        if($t->complie($tplfile,$objfile) === false){
            die('Cannot write to template cache.');
        }
    }
    return $objfile;
}

function get_lang($k, $p = array(), $bundle = 'message.inc', $user_lang = null) {
    static $msgs = array();
    if(empty($bundle) && empty($k)){
        return '';
    }
    if (!empty($p) && is_string($p)) $bundle = $p;
    //获取玩家的语言选项
    $lang = $user_lang;
    if(empty($lang)) {
        $lang = $_GET['language'];
        if (empty($lang)) $lang = $_GET['locale'];
        if (empty($lang)) $lang = $_POST['language'];
        if (empty($lang)) $lang = $_POST['locale'];
        if (empty($lang)) $lang = $_COOKIE['language'];
        if (empty($lang)) $lang = $_COOKIE['locale'];
        if (empty($lang) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $lang = $lang[0];
        }
    }
    if (empty($lang)) $lang = 'en';
    if (strpos($lang, '-') !== false) {
        $parts = explode('-', $lang);
        $lang = $parts[0].'_'.strtoupper($parts[1]);
    }
    if (empty($msgs[$bundle][$lang])) {
        $langdir = APP_ROOT.'/language/';
        if (!empty($bundle)) {
            $langfile = $langdir.$lang.'/'.$bundle.'.php';
        }
        if (!file_exists($langfile)) {
            $country_lang_mapping = array(
                'DE' => 'de_DE',
                'US' => 'en_US',
                'GB' => 'en_US',
                'ES' => 'es_ES',
                'FR' => 'fr_FR',
                'IT' => 'it_IT',
                'JP' => 'ja_JP',
                'KR' => 'ko_KR',
                'NL' => 'nl_NL',
                'PL' => 'pl_PL',
                'BR' => 'pt_BR',
                'PT' => 'pt_BR',
                'RU' => 'ru_RU',
                'TH' => 'th_TH',
                'TR' => 'tr_TR',
                'VN' => 'vi_VN',
                'CN' => 'zh_CN',
                'HANS' => 'zh_CN',
                'YUE' => 'zh_CN',
                'TW' => 'zh_TW',
                'HK' => 'zh_TW',
                'MO' => 'zh_TW',
                'HANT' => 'zh_TW',
                'AE' => 'ar_SA'
            );
            $parts = explode('_', $lang, 2);
            $country_lang = $country_lang_mapping[$parts[1]];
            if (!empty($country_lang)) {
                $langfile = $langdir.$country_lang.'/'.$bundle.'.php';
            }
        }
        if (!file_exists($langfile)) {
            $langfile = $langdir.'en/'.$bundle.'.php';
        }
        if (!file_exists($langfile)) return '';
        $msgs[$bundle][$lang] = include $langfile;
    }
    $msg = '';
    if(isset($msgs[$bundle][$lang][$k])){
        $msg = $msgs[$bundle][$lang][$k];
    }
    if (empty($msg)) {
        if (empty($msgs[$bundle]['en'])) {
            $langfile = $langdir.'en/'.$bundle.'.php';
            if (file_exists($langfile)) {
                $msgs[$bundle]['en'] = include $langfile;
            }
        }
        if(isset($msgs[$bundle]['en'][$k])) {
            $msg = $msgs[$bundle]['en'][$k];
        }
    }
    if (empty($p) || !is_array($p)) return $msg;
    $keys = array_keys($p);
    if (!elex_is_integer($keys[0])) {
        $search = array();
        foreach ($keys as $k) {
            $search[] = '{'.$k.'}';
        }
        $msg = str_replace($search, array_values($p), $msg);
    } else {
        $msg = vsprintf($msg, $p);
    }
    return $msg;
}

/**
 * 模板类
 *
 */
class Template {
    var $var_regexp = "\@?\\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\[\]\$]+\]|\-\>\w+)*";
    var $vtag_regexp = "\<\?=(\@?\\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\[\]\$]+\])*)\?\>";
    var $const_regexp = "\{([\w]+)\}";

    /**
     *  读模板页进行替换后写入到cache页里
     *
     * @param string $tplfile ：模板源文件地址
     * @param string $objfile ：模板cache文件地址
     * @return string
     */
    public function complie($tplfile, $objfile) {
        $template = file_get_contents ( $tplfile );
        $template = $this->parse ( $template );
        return file_put_contents($objfile,$template,LOCK_EX);
    }

    /**
     *  解析模板标签
     *
     * @param string $template ：模板源文件内容
     * @return string
     */
    protected function parse($template) {
        $template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);

        $template = preg_replace("/\{($this->var_regexp)\}/", "<?=\\1?>", $template);
        $template = preg_replace("/\{($this->const_regexp)\}/", "<?=\\1?>", $template);
        $template = preg_replace("/(?<!\<\?\=|\\\\|\[)$this->var_regexp/", "<?=\\0?>", $template);//?<!表示不以XXX开头的，在正则表达式里边，叫做Lookbehind

        $template = preg_replace("/\<\?=(\@?\\\$[a-zA-Z_]\w*)((\[[\\$\[\]\w]+\])+)\?\>/ies", "\$this->arrayindex('\\1', '\\2')", $template);

        $template = preg_replace("/\{\{php (.*?)\}\}/ies", "\$this->stripvtag('<? \\1?>')", $template);
        $template = preg_replace("/\{php (.*?)\}/ies", "\$this->stripvtag('<? \\1?>')", $template);
        $template = preg_replace("/\{for (.*?)\}/ies", "\$this->stripvtag('<? for(\\1) {?>')", $template);

        $template = preg_replace("/\{elseif\s+(.+?)\}/ies", "\$this->stripvtag('<? } elseif(\\1) { ?>')", $template);

        for($i=0; $i<2; $i++) {
            $template = preg_replace("/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopsection('\\1', '\\2', '\\3', '\\4')", $template);
            $template = preg_replace("/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopsection('\\1', '', '\\2', '\\3')", $template);
        }
        $template = preg_replace("/\{if\s+(.+?)\}/ies", "\$this->stripvtag('<? if(\\1) { ?>')", $template);

        $template = preg_replace("/\{include\s+(.*?)\}/is", "<?php include \\1; ?>", $template );
        $template = preg_replace("/\{template\s+([\w\.\/\\\\]+?)(?:\s+([\w\.\/\\\\]+?))?\}/is", "<?php include renderTemplate('\\1','\\2'); ?>", $template);

        $template = preg_replace("/\{else\}/is", "<? } else { ?>", $template);
        $template = preg_replace("/\{\/if\}/is", "<? } ?>", $template);
        $template = preg_replace("/\{\/for\}/is", "<? } ?>", $template);

        $template = preg_replace("/$this->const_regexp/", "<?=\\1?>", $template);

        $template = "<? if(!defined('APP_ROOT')) exit('Access Denied');?>\r\n$template";
        $template = preg_replace("/(\\\$[a-zA-Z_]\w+\[)([a-zA-Z_]\w+)\]/i", "\\1'\\2']", $template);

        $template = preg_replace("/\<\?(\s{1})/is", "<?php\\1", $template);
        $template = preg_replace("/\<\?\=(.+?)\?\>/is", "<?php echo \\1;?>", $template);
        $template = preg_replace("/\{(?:lang|msg)\s+([^\s\}]+?)\s+(\w+?)\s+(\w+?)\}/is", "<?php echo get_lang('\\1', $\\2, '\\3'); ?>", $template);
        $template = preg_replace("/\{(?:lang|msg)\s+([^\s\}]+?)\s+(\w+?)\}/is", "<?php echo get_lang('\\1', $\\2); ?>", $template);
        $template = preg_replace("/\{(?:lang|msg)\s+([^\s\}]+?)\}/is", "<?php echo get_lang('\\1'); ?>", $template);

        return $template;
    }

    function arrayindex($name, $items) {
        $items = preg_replace("/\[([a-zA-Z_]\w*)\]/is", "['\\1']", $items);
        return "<?=$name$items?>";
    }

    function stripvtag($s) {
        return preg_replace("/$this->vtag_regexp/is", "\\1", str_replace("\\\"", '"', $s));
    }

    function loopsection($arr, $k, $v, $statement) {
        $arr = $this->stripvtag($arr);
        $k = $this->stripvtag($k);
        $v = $this->stripvtag($v);
        $statement = str_replace("\\\"", '"', $statement);
        return $k ? "<? foreach((array)$arr as $k => $v) {?>$statement<? }?>" : "<? foreach((array)$arr as $v) {?>$statement<? } ?>";
    }
}
?>