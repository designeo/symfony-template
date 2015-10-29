<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DesigneoBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

/**
 * Generates a CRUD controller.
 *
 * @author Tomas Polivla <tomas.polivka@designeo.cz>
 */
class CrudGenerator extends Generator
{
    protected $filesystem;
    protected $bundle;
    protected $entity;
    protected $entityClass;
    protected $entityName;
    protected $entityId;
    protected $rootDir;
    protected $newFiles = [];

    /**
     * Constructor.
     *
     * @param Filesystem $filesystem A Filesystem instance
     * @param $rootDir
     */
    public function __construct(Filesystem $filesystem, $rootDir)
    {
        $this->filesystem  = $filesystem;
        $this->rootDir = $rootDir;
    }

    /**
     * Generate the CRUD controller.
     *
     * @param BundleInterface $bundle A bundle object
     * @param string $entity The entity relative class name
     * @param ClassMetadataInfo $metadata The entity class metadata
     * @param $forceOverwrite
     */
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $forceOverwrite)
    {

        if (count($metadata->identifier) != 1) {
            throw new \RuntimeException('The CRUD generator does not support entity classes with multiple or no primary keys.');
        }

        $this->entity   = $entity;
        $this->bundle   = $bundle;
        $this->metadata = $metadata;

        $parts = explode('\\', $this->entity);
        $this->entityClass = array_pop($parts);
        $this->entityName = lcfirst($this->entityClass);
        $this->entityId = $this->entityClass[0];

        $this->generateControllerClass($forceOverwrite);

        $this->generateModel();
        $this->generateException();
        $this->generateForm();
        $this->generateDataSource();
        $this->generateViews();
        $this->generateConfiguration();
        $this->generateTranslations();

        foreach ($this->newFiles as $newFile) {
            $this->addToGit($newFile);
        }
    }

    protected function generateTranslations()
    {
        $newTranslations = Yaml::parse($this->render('crud/translation.yml.twig', [
            'entity_class'      => $this->entityClass,
            'entity'            => $this->entityName,
        ]));

        $finder = new Finder();
        $finder
            ->in(sprintf("%s/Resources/translations", $this->rootDir))
            ->files()
            ->name('messages.*.yml');

        /** @var SplFileInfo $messageFile */
        foreach ($finder as $messageFile) {
            $translations = Yaml::parse(file_get_contents($messageFile->getRealpath()));

            if (array_key_exists($this->entityName, $translations['admin'])) {
                continue;
            }

            $translations['admin'] = array_merge($translations['admin'], $newTranslations);

            file_put_contents($messageFile->getRealpath(), Yaml::dump($translations, 10));
        }
    }

    protected function generateConfiguration()
    {
        $dir = sprintf("%s/config/crud", $this->rootDir);

        if (!file_exists($dir)) {
            $this->filesystem->mkdir($dir, 0777);
        }

        $this->renderFile('crud/config.yml.twig', sprintf("%s/%s.yml", $dir, $this->entityName), [
            'entity_class'      => $this->entityClass,
            'entity'            => $this->entityName,
        ]);

        $this->newFiles[] = sprintf("%s/%s.yml", $dir, $this->entityName);

        // update loader
        $loaderPath = sprintf("%s/loader.yml", $dir);
        $loaderData = Yaml::parse(file_get_contents($loaderPath));

        $found = false;

        if (empty($loaderData['imports'])) {
            $loaderData['imports'] = [];
        }

        foreach ($loaderData['imports'] as $import) {
            if ($import['resource'] == sprintf('%s.yml', $this->entityName)) {
                $found = true;
            }
        }

        if (!$found) {
            $loaderData['imports'][] = ['resource' => sprintf('%s.yml', $this->entityName)];

            file_put_contents($loaderPath, Yaml::dump($loaderData));
        }
    }

    protected function generateException()
    {
        $dir = sprintf("%s/Exception", $this->bundle->getPath());

        if (!file_exists($dir)) {
            $this->filesystem->mkdir($dir, 0777);
        }

        $this->renderFile('crud/exception.php.twig', sprintf("%s/%sException.php", $dir, $this->entityClass), [
            'entity_class'      => $this->entityClass,
        ]);

        $this->newFiles[] = sprintf("%s/%sException.php", $dir, $this->entityClass);
    }

    protected function generateModel()
    {
        $dir = sprintf("%s/Model", $this->bundle->getPath());

        if (!file_exists($dir)) {
            $this->filesystem->mkdir($dir, 0777);
        }

        $this->renderFile('crud/model.php.twig', sprintf("%s/%sModel.php", $dir, $this->entityClass), [
            'entity'            => $this->entityName,
            'entity_class'      => $this->entityClass,
        ]);

        $this->newFiles[] = sprintf("%s/%sModel.php", $dir, $this->entityClass);
    }

    protected function generateForm()
    {
        $dir = sprintf("%s/Form/Admin", $this->bundle->getPath());

        if (!file_exists($dir)) {
            $this->filesystem->mkdir($dir, 0777);
        }

        $this->renderFile('crud/form.php.twig', sprintf("%s/%sType.php", $dir, $this->entityClass), [
            'entity'            => $this->entityName,
            'entity_class'      => $this->entityClass,
        ]);

        $this->newFiles[] = sprintf("%s/%sType.php", $dir, $this->entityClass);
    }

    protected function generateDataSource()
    {
        $dir = sprintf("%s/GridDataSources/Admin", $this->bundle->getPath());

        if (!file_exists($dir)) {
            $this->filesystem->mkdir($dir, 0777);
        }

        $this->renderFile('crud/datasource.php.twig', sprintf("%s/%sDataSource.php", $dir, $this->entityClass), [
            'entity'            => $this->entityName,
            'entity_class'      => $this->entityClass,
            'entity_id'         => $this->entityId,
        ]);

        $this->newFiles[] = sprintf("%s/%sDataSource.php", $dir, $this->entityClass);
    }

    protected function generateViews()
    {
        $dir = sprintf('%s/Resources/views/Admin/%s', $this->bundle->getPath(), str_replace('\\', '/', $this->entity));

        if (!file_exists($dir)) {
            $this->filesystem->mkdir($dir, 0777);
        }

        $this->generateIndexView($dir);
        $this->generateEditView($dir);
        $this->generateGridView($dir);
    }

    protected function generateIndexView($dir)
    {
        $this->renderTwigTemplate('crud/views/index.html.twig.twig', $dir.'/index.html.twig', array(
            'bundle'            => $this->bundle->getName(),
            'entity'            => $this->entityName,
            'entity_class'      => $this->entityClass,
            'entity_id'         => $this->entityId
        ));

        $this->newFiles[] = $dir.'/index.html.twig';
    }

    protected function generateEditView($dir)
    {
        $this->renderTwigTemplate('crud/views/edit.html.twig.twig', $dir.'/edit.html.twig', array(
            'bundle'            => $this->bundle->getName(),
            'entity'            => $this->entityName,
            'entity_class'      => $this->entityClass,
            'entity_id'         => $this->entityId
        ));

        $this->newFiles[] = $dir.'/edit.html.twig';
    }

    protected function generateGridView($dir)
    {
        $dir = sprintf('%s/includes', $dir);

        if (!file_exists($dir)) {
            $this->filesystem->mkdir($dir, 0777);
        }

        $this->renderTwigTemplate('crud/views/includes/grid.html.twig.twig', $dir.'/grid.html.twig', array(
            'entity'            => $this->entityName,
            'entity_class'      => $this->entityClass,
            'entity_id'         => $this->entityId
        ));

        $this->newFiles[] = $dir.'/grid.html.twig';
    }

    /**
     * Generates the controller class only.
     *
     */
    protected function generateControllerClass($forceOverwrite)
    {
        $dir = $this->bundle->getPath();

        $target = sprintf(
            '%s/Controller/Admin/%sController.php',
            $dir,
            $this->entityClass
        );

        if (!$forceOverwrite && file_exists($target)) {
            //throw new \RuntimeException('Unable to generate the controller as it already exists.');
        }

        $this->renderFile('crud/controller.php.twig', $target, array(
            'entity'            => $this->entityName,
            'entity_class'      => $this->entityClass,
            'entity_id'         => $this->entityId,
        ));

        $this->newFiles[] = $target;
    }

    protected function renderTwigTemplate($template, $target, $parameters)
    {
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }

        $twig = $this->getTwigEnvironment();

        $twig->setLexer(new \Twig_Lexer($twig, array(
            'tag_comment'   => array('[#', '#]'),
            'tag_block'     => array('[%', '%]'),
            'tag_variable'  => array('[[', ']]'),
            'interpolation' => array('#[', ']'),
        )));

        return file_put_contents($target, $twig->render($template, $parameters));
    }

    protected function addToGit($file)
    {
        $git = new Process(sprintf("git add %s", $file));
        $git->run();
    }
}
