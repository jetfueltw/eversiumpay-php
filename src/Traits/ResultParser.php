<?php

namespace Jetfuel\Eversiumpay\Traits;

use Sunra\PhpSimple\HtmlDomParser;

trait ResultParser
{
    /**
     * Parse HTML format response to string.
     *
     * @param string $response
     * @return string|null
     */
    public function parseResponse($response)
    {   
        $html = HtmlDomParser::str_get_html($response);
        
        if ($html) {
            $imgSrc = $html->find('img', 0);

            if (isset($imgSrc)) {
                return ltrim($imgSrc->src, '/');
            }
        }

        return null;
    }

}
