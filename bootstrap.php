<?php

$loader = require __DIR__ . "/vendor/autoload.php";

use App\Providers\EntityBuilderProvider;
use App\Providers\YamlParametersProvider;
use App\Providers\ResponseBuilderProvider;
use Silex\Provider\MonologServiceProvider;
use App\Providers\CriteriaBuilderProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Saxulum\Validator\Provider\SaxulumValidatorProvider;
use JDesrosiers\Silex\Provider\JmsSerializerServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

date_default_timezone_set('Europe/Berlin');

$app = new \Silex\Application();

/* used to used parameters.yml in order to easy settings */
$app->register(new YamlParametersProvider(__DIR__ . '/config/parameters.yml'));

/* used to implement Doctrine DBAL */
$app->register(new DoctrineServiceProvider, array('db.options' => $app['params']['db']['default']));

/* used to implement Doctrine ORM */
$app->register(new DoctrineOrmServiceProvider, array(
    'orm.proxies_dir' => __DIR__ . '/cache/proxies',
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'annotation',
                'namespace' => 'App\Entities',
                'path' => __DIR__.'/src/Entities',
                'use_simple_annotation_reader' => false
            )
        ),
    ),
));

/* used to serialize entities */
$app->register(new JmsSerializerServiceProvider, array(
    "serializer.srcDir" => __DIR__ . "/vendor/jms/serializer/src",
));

/* used to build responses in the controller */
$app->register(new ResponseBuilderProvider());

/* used to build entities from json */
$app->register(new EntityBuilderProvider);

/* used to build criteria from url parameters */
$app->register(new CriteriaBuilderProvider);

/* used to register controllers as a service, allows dependency injection */
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

/* enable validation service */
$app->register(new ValidatorServiceProvider());

/* used for annotations constrains */
$app->register(new SaxulumValidatorProvider());

$app->register(new MonologServiceProvider(), array(
    'monolog.name' => "application",
    'monolog.level' => \Monolog\Logger::ERROR,
    'monolog.logfile' => __DIR__ . '/logs/errors.log',
));

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
