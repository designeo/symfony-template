<?php

namespace AppBundle\Tests\Helpers;

use Symfony\Component\HttpFoundation\Request;

class RequestCurlConverter
{
    /*
     * Converts request object to curl request runnable from terminal
     */
    public static function convert(Request $request)
    {
        return sprintf("
curl -v \\
%s -d %s \\
-X %s \\
\"%s\"",
            static::extractHeaders($request),
            static::extractContent($request),
            $request->getMethod(),
            $request->getUri()
//            "localhost:8000/api/v1/register/"
        );
    }

    protected static function extractHeaders(Request $request)
    {
        $headers = $request->headers->all();

        $output = "";

        $headers = array_intersect_key($headers, array_flip(static::excludedHeaders()));

        foreach ($headers as $key => $value) {
            if (is_array($value)) {
                $valueBag = $value;
                $value = reset($valueBag);
            }
            $output .= sprintf("-h \"%s: %s\" \\\n", $key, $value);
        }

        return $output;
    }

    protected static function extractContent(Request $request)
    {
        return addslashes(sprintf('%s', $request->getContent()));
    }

    protected static function excludedHeaders()
    {
        return [
            'host',
        ];
    }
}