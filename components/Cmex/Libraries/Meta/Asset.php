<?php

namespace Cmex\Libraries\Meta;

class Asset
{
    private $scripts = array();
    private $stylesheets = array();

    private $metainformation = null;

    public function __construct(MetaInformation $meta)
    {
        $this->metainformation = $meta;
    }

    public function add($name, $path)
    {
        if (substr_compare($path, "http://", 0, 7) == 0 || substr_compare($path, "https://", 0, 8) == 0) {
            $url = $path;
        } else {
            $url = asset($path);
        }

        $args = "";
        if (func_num_args() == 3 && is_array(func_get_arg(2))) {
            foreach (func_get_arg(2) as $key => $value) {
                $args .= ' '.$key.'="'.$value.'"';
            }
        }

        // Determine type
        if (substr($path, strlen($path) - 2) == "js") {
            if (!isset($this->scripts[$name])) {
                $this->scripts[$name] = "<script src=\"".$url."\"".$args."></script>\n";
            }
        } else {
            if (!isset($this->stylesheets[$name])) {
                $this->metainformation->link(
                    'stylesheet',
                    'text/css',
                    $url,
                    '',
                    $args
                );
            }
        }
    }

    public function getScripts()
    {
        return implode("", $this->scripts);
    }

    public function getStylesheets()
    {
        return implode("", $this->stylesheets);
    }

    public function get()
    {
        return $this->getScripts() . $this->getStylesheets();
    }

    public function scripts()
    {
        return "__scripts__";
    }
}
