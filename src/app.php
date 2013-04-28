<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use FranMoreno\Silex\Provider\PagerfantaServiceProvider;
use FranMoreno\Silex\Provider\ParisServiceProvider;
use SMTC\Silex\Service\FakeService;
use SMTC\Silex\Service\UserManager;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

$app->register(new PagerfantaServiceProvider(), array(
    'pagerfanta.view.options'  => array(
        'default_view'  => 'foundation',
    )
));

$app['pagerfanta.view_factory'] = $app->share($app->extend('pagerfanta.view_factory', function($viewFactory, $app) {
    $viewFactory->add(array(
        'foundation' => new \SMTC\Silex\View\FoundationView()
    ));

    return $viewFactory;
}));

$app['smtc.service.fake'] = $app->share(function ($app) {
    $seed = 123;

    return new FakeService($seed);
});

$app->register(new ParisServiceProvider(), array(
    'idiorm.config'  => array(
        'connection_string'  => 'sqlite:'.__DIR__.'/../cache/database.db',
    ),
    'paris.model.prefix' => "\\SMTC\\Silex\\Model\\",
));

$app['smtc.manager.user'] = $app->share(function ($app) {
    return new UserManager($app['paris']->getModel('User'));
});

return $app;
