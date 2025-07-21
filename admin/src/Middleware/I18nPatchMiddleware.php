<?php
namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Http\ServerRequest;
use Cake\I18n\I18n;
use Cake\Utility\Hash;
use Psr\Http\Message\ResponseInterface;
// use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use ADmad\I18n\Middleware\I18nMiddleware;

/**
 * I18nRoutePatch middleware
 */
class I18nPatchMiddleware extends I18nMiddleware
{
    use InstanceConfigTrait;

    /**
     * Default config.
     *
     * ### Valid keys
     *
     * - `defaultLanguage`: Default language for app. Default `en_GB`.
     * - `languages`: Languages available in app. Default `[]`.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'defaultLanguage' => 'en_GB',
        'languages' => [],
    ];

    /**
     * Constructor.
     *
     * @param array $config Settings for the filter.
     */
    public function __construct($config = [])
    {
        if (isset($config['languages'])) {
            $config['languages'] = Hash::normalize($config['languages']);
        }

        $this->setConfig($config);
    }

    /**
     * Invoke method.
     *
     * @param \Psr\Http\Message\ServerRequest $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function __invoke(ServerRequest $request, ResponseInterface $response, $next)
    {
        $config = $this->getConfig();
        $url = $request->getUri()->getPath();

        $langs = $config['languages'];
        $requestParams = $request->getAttribute('params');

        if ($url === '/') {
            $cookieLang = $request->getCookie('lang');
            $statusCode = 301;

            if (!isset($cookieLang)) {
                $lang = $config['defaultLanguage'];
                if ($config['detectLanguage']) {
                    $statusCode = 302;
                    $lang = $this->detectLanguage($request, $lang);
                }

                // $response = new RedirectResponse(
                //     $request->getAttribute('webroot') . 'choose-language',
                //     $statusCode
                // );

                // return $response;
    
            } else {
                $lang = $cookieLang;
            }
            $response = new RedirectResponse(
                $request->getAttribute('webroot') . $lang,
                $statusCode
            );

            return $response;

        }

        $langs = $config['languages'];
        $requestParams = $request->getAttribute('params');
        $lang = isset($requestParams['lang']) ? $requestParams['lang'] : $config['defaultLanguage'];
        if (isset($langs[$lang])) {
            I18n::setLocale($langs[$lang]['locale']);
        } else {
            I18n::setLocale($lang);
        }

        Configure::write('App.language', $lang);

        return $next($request, $response);
    }
}
