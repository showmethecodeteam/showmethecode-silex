<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/pagerfanta', function (Request $request) use ($app) {
    $results = $app['smtc.service.fake']->getResults();

    $page = $request->query->get('page', 1);
    $pagerfanta = $app['pagerfanta.pager_factory']->getForArray($results)
        ->setMaxPerPage(10)
        ->setCurrentPage($page);

    return $app['twig']->render('pagerfanta.html.twig', array(
        'pager' => $pagerfanta,
    ));
})
->bind('pagerfanta')
;

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', array());
})
->bind('homepage')
;

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
