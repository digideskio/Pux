<?php
namespace Pux\Middleware;
use Pux\Middleware;
use Negotiation\FormatNegotiator;
use Negotiation\Negotiator;

class ContentNegotiationMiddleware extends Middleware
{
    private $negotiator;

    public function __construct(callable $next, Negotiator $negotiator = null)
    {
        parent::__construct($next);
        $this->negotiator = $negotiator ?: new Negotiator();
    }

    public function call(array $environment, array $response)
    {
        $n = $this->next;
        $accept = isset($environment['_SERVER']['HTTP_ACCEPT']) ? $environment['_SERVER']['HTTP_ACCEPT'] : '';
        $priorities = isset($environment['negotiation.priorities']) ? $environment['negotiation.priorities'] : array();
        $environment['request.best_format'] = $this->negotiator->getBest($accept, $priorities);
        return $n($environment, $response);
    }
}

