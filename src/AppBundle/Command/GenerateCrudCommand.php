<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Generator\CrudGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Class GenerateCrudCommand
 *
 *
 * @author Tomas Polivla <tomas.polivka@designeo.cz>
 */
class GenerateCrudCommand extends GenerateDoctrineCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('entity', InputArgument::REQUIRED, 'The entity class name to initialize (shortcut notation)'),
                new InputOption('overwrite', '', InputOption::VALUE_NONE, 'Do not stop the generation if crud controller already exist, thus overwriting all generated files'),
            ))
            ->setDescription('Generates a CRUD based on a Doctrine entity')
            ->setHelp(<<<EOT
The <info>designeo:generate:crud</info> command generates a CRUD based on a Doctrine entity.

Every generated file is based on a template. There are default templates but they can be overridden by placing custom templates in one of the following locations, by order of priority:

<info>BUNDLE_PATH/Resources/SensioGeneratorBundle/skeleton/crud
APP_PATH/Resources/SensioGeneratorBundle/skeleton/crud</info>

And

<info>__bundle_path__/Resources/SensioGeneratorBundle/skeleton/form
__project_root__/app/Resources/SensioGeneratorBundle/skeleton/form</info>

You can check https://github.com/sensio/SensioGeneratorBundle/tree/master/Resources/skeleton
in order to know the file structure of the skeleton
EOT
            )
            ->setName('designeo:generate:crud')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();

        if ($input->isInteractive()) {
            $question = new ConfirmationQuestion($questionHelper->getQuestion('Do you confirm generation', 'yes', '?'), true);
            if (!$questionHelper->ask($input, $output, $question)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }

        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $forceOverwrite = $input->getOption('overwrite');

        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle).'\\'.$entity;
        $metadata    = $this->getEntityMetadata($entityClass);
        $bundle      = $this->getContainer()->get('kernel')->getBundle($bundle);

        $generator = $this->getGenerator($bundle);
        $generator->generate($bundle, $entity, $metadata[0], $forceOverwrite);

        $output->writeln('Generating the CRUD code: <info>OK</info>');

        $errors = array();

        $questionHelper->writeGeneratorSummary($output, $errors);
    }

    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();

        if (is_dir($dir = $this->getContainer()->get('kernel')->getRootdir().'/Resources/CrudGeneratorTemplate/skeleton')) {
            $skeletonDirs[] = $dir;
        }

        return $skeletonDirs;
    }


    protected function createGenerator($bundle = null)
    {
        return new CrudGenerator(
            $this->getContainer()->get('filesystem'),
            $this->getContainer()->get('kernel')->getRootdir()
        );
    }
}