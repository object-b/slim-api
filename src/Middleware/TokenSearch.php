<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;

class TokenSearch
{
    public function __construct()
    {
        $this->options = [
            'header' => 'X-Authorization',
            'regex' => '/Bearer\s+(.*)$/i',
        ];
    }

    public function __invoke(Request $request)
    {
        /** Check for token on header */
        if (isset($this->options['header'])) {
            if ($request->hasHeader($this->options['header'])) {
                $header = $request->getHeader($this->options['header'])[0];
                if (preg_match($this->options['regex'], $header, $matches)) {
                    return $matches[1];
                }
            }
        }

        return false;
    }
}