<?php 

    /**
     * Translate
     * 
     * PSR compatible PHP library that provides a simple way 
     * to retrieve strings in various languages.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-translate
     */

    namespace Embryo\Translate;
    
    use \FilesystemIterator;
    
    class Translate 
    {
        /**
         * @var string $default
         */
        private $default = 'en';
        
        /**
         * @var array $messages
         */
        private $messages = [];
        
        /**
         * @var array $current
         */
        private $current = [];

        /**
         * Set language path and default languge.
         *
         * @param string $languagePath
         * @param string $default
         */
        public function __construct(string $languagePath, string $default = 'en')
        {
            $this->languagePath = rtrim($languagePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            $this->default = $default;
        }

        /**
         * Set messages from language path.
         * 
         * Language directory must have subdirectory with
         * language name. The message files must be an
         * array.
         *
         * @return self
         */
        public function setMessages(): self
        {
            $messages = [];
            $dirs = new FilesystemIterator($this->languagePath);
            foreach ($dirs as $dir) {
                if ($dir->isDir()) {
                    $messages[$dir->getFilename()] = [];
                    $langDir = new FilesystemIterator($dir->getPathname());
                    foreach ($langDir as $fileinfo) {
                        if ($fileinfo->getExtension() === 'php') {
                            $content = require $fileinfo->getPathname();
                            if (is_array($content)) {
                                $messages[$dir->getFilename()] = array_merge($messages[$dir->getFilename()], $content);
                            }
                        }
                    }  
                }
            }
            $this->messages = $messages;
            return $this;
        }

        /**
         * Get language messages.
         *
         * @param string $lang
         * @return self
         */
        public function getMessages(string $lang = 'en'): self
        {
            if (isset($this->messages[$lang])) {
                $this->current = $this->messages[$lang];
            } else {
                $this->current = $this->messages[$this->default];
            }
            return $this;
        }

        /**
         * Get message.
         * 
         * If key doesn't exists return the key.
         *
         * @param string $key
         * @param array $context
         * @return string
         */
        public function get(string $key, array $context = []): string
        {
            $replace = [];
            foreach ($context as $var => $val) {
                if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                    $replace['{' . $var . '}'] = $val;
                }
            }

            if (isset($this->current[$key])) {
                return strtr($this->current[$key], $replace);
            }
            return $key;
        }
    }