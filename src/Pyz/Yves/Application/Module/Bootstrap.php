<?php
namespace Pyz\Yves\Application\Module;

use ProjectA\Yves\Catalog\Component\Model\Category;
use ProjectA\Yves\Library\Silex\Application;
use ProjectA\Yves\Library\Silex\Provider\CookieServiceProvider;
use ProjectA\Yves\Library\Silex\Provider\StorageServiceProvider;
use ProjectA\Yves\Library\Silex\Provider\ExceptionServiceProvider;
use ProjectA\Yves\Library\Silex\Provider\TranslationServiceProvider;
use ProjectA\Yves\Library\Silex\Provider\TwigServiceProvider;
use ProjectA\Yves\Library\Silex\Provider\YvesLoggingServiceProvider;
use ProjectA\Yves\Library\Silex\Routing\SilexRouter;
use Pyz\Yves\Application\Module\ControllerProvider as ApplicationProvider;
use ProjectA\Yves\Cart\Module\ControllerProvider as CartProvider;
use Pyz\Yves\Catalog\Module\ControllerProvider as CatalogProvider;
use ProjectA\Yves\Checkout\Module\ControllerProvider as CheckoutProvider;
use ProjectA\Yves\Setup\Module\ControllerProvider as SetupProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use SilexRouting\Provider\RoutingServiceProvider;

class Bootstrap extends \ProjectA\Yves\Library\Silex\Bootstrap
{
    /**
     * @param Application $app
     */
    protected function beforeBoot(Application $app)
    {
        $app['locale'] = \ProjectA_Shared_Library_Store::getInstance()->getCurrentLocale();
        if (\ProjectA_Shared_Library_Environment::isDevelopment()) {
            $app['profiler.cache_dir'] = \ProjectA_Shared_Library_Data::getLocalStoreSpecificPath('cache/profiler');
            $app['profiler.mount_prefix'] = '/_profiler';
        }
    }

    /**
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getServiceProviders()
    {
        $providers = [
            new ExceptionServiceProvider(),
            new YvesLoggingServiceProvider(),
            new CookieServiceProvider(),
            new SessionServiceProvider(),
            new UrlGeneratorServiceProvider(),
            new ServiceControllerServiceProvider(),
            new RoutingServiceProvider(),
            new StorageServiceProvider(),
            new TranslationServiceProvider(),
            new FormServiceProvider(),
            new ValidatorServiceProvider(),
            new TwigServiceProvider(),
        ];

        if (\ProjectA_Shared_Library_Environment::isDevelopment()) {
            $providers[] = new WebProfilerServiceProvider();
        }

        return $providers;
    }

    /**
     * @return \Silex\ControllerProviderInterface[]
     */
    protected function getControllerProviders()
    {
        return [
            new ApplicationProvider(),
            new CartProvider(),
            new CatalogProvider(),
            new CheckoutProvider(),
            new SetupProvider()
        ];
    }

    /**
     * @param Application $app
     * @return \Symfony\Component\Routing\RouterInterface[]
     */
    protected function getRouters(Application $app)
    {
        return [
            new SilexRouter($app)
        ];
    }

    protected function globalTemplateVariables(Application $app)
    {
        return [
            'categories' => Category::getCategoryTree($app->getStorageKeyValue())
        ];
    }


}
