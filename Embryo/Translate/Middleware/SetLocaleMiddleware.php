<?php 

    /**
     * SetLocaleMiddleware
     * 
     * PSR-15 middleware that stores locale language in session.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-translate
     */

    namespace Embryo\Translate\Middleware;
        
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;

    class SetLocaleMiddleware implements MiddlewareInterface 
    {   
        /**
         * @var string $lang
         */
        private $lang = 'en';

        /**
         * @var string $sessionRequestAttrbiute
         */
        private $sessionRequestAttrbiute = 'session';

        /**
         * @var string $sessionKey
         */
        private $sessionKey = 'language';

        /**
         * @var string $languageQueryParam
         */
        private $languageQueryParam = 'language';

        /**
         * Set language.
         *
         * @param string $lang
         * @return self
         */
        public function setLanguage(string $lang): self
        {
            $this->lang = $lang;
            return $this;
        }

        /**
         * Set session in request attibute.
         *
         * @param string $sessionRequestAttrbiute
         * @return void
         */
        public function setSessionRequestAttrbiute(string $sessionRequestAttrbiute): self
        {
            $this->sessionRequestAttrbiute = $sessionRequestAttrbiute;
            return $this;
        }

        /**
         * Set language in request query param.
         *
         * @param string $languageQueryParam
         * @return self
         */
        public function setLanguageQueryParam(string $languageQueryParam): self
        {
            $this->languageQueryParam = $languageQueryParam;
            return $this;
        }

        /**
         * Set session key item.
         *
         * @param string $sessionKey
         * @return void
         */
        public function setSessionKey(string $sessionKey): self
        {
            $this->sessionKey = $sessionKey;
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
            $session = $request->getAttribute($this->sessionRequestAttrbiute);
            $query   = $request->getQueryParams();

            if (isset($query[$this->languageQueryParam])) {
                $session->set($this->sessionKey, $query[$this->languageQueryParam]);
            }

            if (!$session->has($this->sessionKey)) {
                $session->set($this->sessionKey, $this->lang);
            }
            return $handler->handle($request);
        }   
    }