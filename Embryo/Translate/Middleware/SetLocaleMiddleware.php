<?php 

    /**
     * SetLocaleMiddleware
     */

    namespace Embryo\Translate\Middleware;
        
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;

    class SetLocaleMiddleware implements MiddlewareInterface 
    {   
        private $lang = 'en';

        private $sessionAttribute = 'session';

        private $languageQueryParam = 'language';

        public function setLanguage(string $lang)
        {
            $this->lang = $lang;
            return $this;
        }

        public function setSessionAttrbiute(string $session)
        {
            $this->sessionAttribute = $sessionAttribute;
            return $this;
        }

        public function setLanguageQueryParam($languageQueryParam)
        {
            $this->languageQueryParam = $languageQueryParam;
            return $this;
        }

        /**
         * Process a server request and return a response.
         *
         * @param ServerRequestInterface $request
         * @param RequestHandlerInterface $handler
         * @return ResponseInterface
         */
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $session = $request->getAttribute($this->sessionAttribute);
            $query   = $request->getQueryParams();

            if (isset($query[$this->languageQueryParam])) {
                $session->set('language', $query[$this->languageQueryParam]);
            }

            if (!$session->has('language')) {
                $session->set('language', $this->lang);
            }
            return $handler->handle($request);
        }   
    }