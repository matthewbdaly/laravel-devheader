<?php

namespace LinkLater\Http\Middleware;

use Closure;
use DOMDocument;
use Illuminate\Contracts\Logging\Log;

class DevHeader
{
    protected $logger;

    public function __construct(Log $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (stristr($response->headers->get('Content-Type'), 'text/html')) {
            try {
                $doc = new DOMDocument;
                libxml_use_internal_errors(true);
                $doc->loadHTML($response->getContent());
                $body = $doc->getElementsByTagName('body')[0];
                $body->setAttribute('style', 'padding-top: 30px;');
                $heading = $doc->createElement('p');
                $heading->textContent = ucfirst(config('app.env'));
                $node = $doc->createElement('div');
                $node->appendChild($heading);
                $node->setAttribute('style', 'width: 100%; height: 30px; position: absolute; top: 0; background: tomato; text-align: center; padding-top: 5px; z-index: 99999;');
                $body->appendChild($node);
                $response->setContent($doc->saveHTML());
            } catch (\Exception $e) {
                $this->logger->info($e);
            }
        }
        return $response;
    }
}
