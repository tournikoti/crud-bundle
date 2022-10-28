<?php

namespace Tournikoti\CrudBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Doctrine\EntityDetails;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Renderer\FormTypeRenderer;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Tournikoti\CrudBundle\Admin\AdminCRUD;
use Tournikoti\CrudBundle\Configuration\ConfigurationListInterface;
use Tournikoti\CrudBundle\Controller\CRUDController;
use Tournikoti\CrudBundle\Generator\ConfigurationListGenerator;
use Tournikoti\CrudBundle\Manipulator\File\YamlFileManipulator;
use Tournikoti\CrudBundle\Provider\ClassDetailProvider;
use Tournikoti\CrudBundle\Utils\RoleNameGenerator;
use Tournikoti\CrudBundle\Utils\RouteNameGenerator;

class CrudMaker extends AbstractMaker
{
    private Filesystem $filesystem;

    private string $bundleResourcesSkeletonDirectory;

    public function __construct(
        private DoctrineHelper         $doctrineHelper,
        private EntityManagerInterface $entityManager,
        private FormTypeRenderer       $formTypeRenderer
    )
    {
        $this->filesystem = new Filesystem();
        $this->bundleResourcesSkeletonDirectory = rtrim(implode(DIRECTORY_SEPARATOR, [
            realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'),
            'Resources',
            'skeleton'
        ]), DIRECTORY_SEPARATOR);
    }

    public static function getCommandName(): string
    {
        return 'make:admin:crud';
    }

    public static function getCommandDescription(): string
    {
        return 'Create Admin CRUD for Doctrine entity class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument('entity', InputArgument::OPTIONAL, sprintf('The class name of the entity to create CRUD (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
            ->addOption('admin', 'a', InputOption::VALUE_OPTIONAL, 'The admin class basename')
            ->addOption('controller', 'c', InputOption::VALUE_OPTIONAL, 'The controller class basename')
            ->addOption('service', 's', InputOption::VALUE_OPTIONAL, 'The services YAML file');

        $inputConfig->setArgumentAsNonInteractive('entity');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        // TODO: Implement configureDependencies() method.
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        $io->title('Welcome to the Admin CRUD Maker');

        if (null === $input->getArgument('entity')) {
            $argument = $command->getDefinition()->getArgument('entity');

            $entities = $this->doctrineHelper->getEntitiesForAutocomplete();

            $question = new Question($argument->getDescription());
            $question->setAutocompleterValues($entities);

            $value = $io->askQuestion($question);

            $input->setArgument('entity', $value);
        }

        // Ask Admin Class
        $defaultAdminClass = Str::asClassName(sprintf('%s Admin', $input->getArgument('entity')));

        $input->setOption('admin', $io->ask(
            sprintf('Choose a name for your admin class (e.g. <fg=yellow>%s</>)', $defaultAdminClass),
            $defaultAdminClass
        ));

        // Ask Controller Class
        $defaultControllerClass = Str::asClassName(sprintf('%s Controller', $input->getArgument('entity')));

        $input->setOption('controller', $io->ask(
            sprintf('Choose a name for your controller class (e.g. <fg=yellow>%s</>)', $defaultControllerClass),
            $defaultControllerClass
        ));

        $input->setOption('service', $io->ask(
            'What is your services YAML configuration file',
            'services.yaml'
        ));
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $input->setOption('service', sprintf('config/%s', $input->getOption('service')));

        if (!$this->filesystem->exists($input->getOption('service'))) {
            throw new RuntimeCommandException(sprintf('The file "%s" does not exist.', $input->getOption('service')));
        }

        $entityClassDetails = $generator->createClassNameDetails(
            Validator::entityExists($input->getArgument('entity'), $this->doctrineHelper->getEntitiesForAutocomplete()),
            'Entity\\'
        );

        $entityDoctrineDetails = $this->doctrineHelper->createDoctrineDetails($entityClassDetails->getFullName());

        if (null === $entityDoctrineDetails->getRepositoryClass()) {
            throw new RuntimeCommandException("The entity {$entityClassDetails->getFullName()} does not have a repository class.");
        }

        $classDetailProvider = new ClassDetailProvider($generator);

        $adminClassDetails = $classDetailProvider->getAdminClassDetail($input->getOption('admin'));
        $controllerClassDetails = $classDetailProvider->getControllerClassDetail($input->getOption('controller'));
        $formClassDetails = $classDetailProvider->getFormClassDetail($input->getArgument('entity'));

        $routePrefix = Str::asSnakeCase($controllerClassDetails->getRelativeNameWithoutSuffix());

        $this->generateForm($formClassDetails, $entityClassDetails, $entityDoctrineDetails, $io);
        $this->generateController($generator, $controllerClassDetails, $adminClassDetails, $io);
        $this->generateAdmin($generator, $adminClassDetails, $controllerClassDetails, $formClassDetails, $entityClassDetails, $io, $routePrefix);
        $this->updateConfig($generator, $input->getOption('service'), $adminClassDetails);

        $generator->writeChanges();

        $io->newLine();
        $io->section("Next: When you're ready :");

        $io->listing([
            "<info>Update</info> roles in your security configuration (maybe in <comment>config/packages/security.yaml</comment> (in <comment>role_hierarchy</comment> section)) :",
            "<info>Manage</info> your route in your application",
            sprintf("<info>Manage</info> your FormType <comment>%s</comment>", $formClassDetails->getFullName()),
        ]);

        $io->table(["Type", "Role", "Route"], [
            ["index", RoleNameGenerator::generate($routePrefix, 'index'), RouteNameGenerator::generate($routePrefix, 'index')],
            ["new", RoleNameGenerator::generate($routePrefix, 'new'), RouteNameGenerator::generate($routePrefix, 'new')],
            ["show", RoleNameGenerator::generate($routePrefix, 'show'), RouteNameGenerator::generate($routePrefix, 'show')],
            ["edit", RoleNameGenerator::generate($routePrefix, 'edit'), RouteNameGenerator::generate($routePrefix, 'edit')],
            ["delete", RoleNameGenerator::generate($routePrefix, 'delete'), RouteNameGenerator::generate($routePrefix, 'delete')],
        ]);
    }

    private function generateAdmin(
        Generator        $generator,
        ClassNameDetails $classDetails,
        ClassNameDetails $controllerClassDetails,
        ClassNameDetails $formClassDetails,
        ClassNameDetails $entityClassDetails,
        ConsoleStyle     $io,
        string           $routePrefix
    ): void
    {
        if (class_exists($classDetails->getFullName())) {
            $io->warning(sprintf("Admin Class '%s' already exist", $classDetails->getFullName()));
            return ;
        }

        $useStatements = new UseStatementGenerator([
            $controllerClassDetails->getFullName(),
            $entityClassDetails->getFullName(),
            $formClassDetails->getFullName(),
            AdminCRUD::class,
            ConfigurationListInterface::class
        ]);

        $configuratorFieldGenerator = new ConfigurationListGenerator($this->entityManager, $entityClassDetails, $useStatements);

        $generator->generateClass($classDetails->getFullName(), $this->bundleResourcesSkeletonDirectory . '/Admin.tpl.php', [
            'namespace' => Str::getNamespace($classDetails->getFullName()),
            'use_statements' => $useStatements,
            'configurator_field' => $configuratorFieldGenerator,
            'entity_class_name' => $entityClassDetails->getShortName(),
            'controller_class_name' => $controllerClassDetails->getShortName(),
            'form_class_name' => $formClassDetails->getShortName(),
            'router_prefix' => $routePrefix,
        ]);
    }

    private function generateForm(
        ClassNameDetails $classDetails,
        ClassNameDetails $entityClassDetails,
        EntityDetails    $entityDoctrineDetails,
        ConsoleStyle $io
    )
    {
        if (class_exists($classDetails->getFullName())) {
            $io->warning(sprintf("Form Type '%s' already exist", $classDetails->getFullName()));
            return ;
        }

        $this->formTypeRenderer->render(
            $classDetails,
            $entityDoctrineDetails->getFormFields(),
            $entityClassDetails
        );
    }

    private function generateController(
        Generator        $generator,
        ClassNameDetails $classDetails,
        ClassNameDetails $adminClassDetails,
        ConsoleStyle $io
    ): void
    {
        if (class_exists($classDetails->getFullName())) {
            $io->warning(sprintf("Controller Class '%s' already exist", $classDetails->getFullName()));
            return ;
        }

        $useStatements = new UseStatementGenerator([
            $adminClassDetails->getFullName(),
            CRUDController::class
        ]);

        $generator->generateController($classDetails->getFullName(), $this->bundleResourcesSkeletonDirectory . '/Controller.tpl.php', [
            'namespace' => Str::getNamespace($classDetails->getFullName()),
            'use_statements' => $useStatements,
            'admin_class_name' => $adminClassDetails->getShortName(),
        ]);
    }

    private function updateConfig(Generator $generator, string $path, ClassNameDetails $classDetails)
    {
        if (!class_exists($classDetails->getFullName())) {
            $manipulator = $this->createYamlManipulator($path);

            $manipulator
                ->section('services')
                ->add($classDetails->getFullName())
                ->setValue('tags', ["name" => "admin.crud"])
                ->end()
                ->end();

            $generator->dumpFile($path, $manipulator->get());
        }
    }

    private function createYamlManipulator(string $path): YamlFileManipulator
    {
        return (new YamlFileManipulator())
            ->load(file_get_contents($path));
    }
}