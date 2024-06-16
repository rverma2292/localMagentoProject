<?php
    namespace Custom\Popup\Console\Command;

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Magento\Framework\Filesystem\Io\File;

    class CreateModuleCommand extends Command
    {
        /**
         * @var File
         */
        protected $ioFile;

        public function __construct(
            File $ioFile,
            string $name = null
        ) {
            parent::__construct($name);
            $this->ioFile = $ioFile;
        }

        protected function configure()
        {
            $this->setName('module:create')
                ->setDescription('Create a new Magento module')
                ->addArgument('module_name', InputArgument::REQUIRED, 'The name of the module in Vendor_Module format')
                ->setHelp('This command allows you to create a new Magento module by specifying the module name in Vendor_Module format.');
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $moduleName = $input->getArgument('module_name');

            if (!$this->validateModuleName($moduleName)) {
                $output->writeln('<error>Invalid module name. Please use the format Vendor_Module.</error>');
                return \Magento\Framework\Console\Cli::RETURN_FAILURE;
            }

            list($vendor, $module) = explode('_', $moduleName);
            $modulePath = "app/code/$vendor/$module";

            // Create module directory if it does not exist
            if (!file_exists($modulePath)) {
                if (!$this->ioFile->mkdir($modulePath, 0777, true)) {
                    $output->writeln("<error>Failed to create module directory '$modulePath'.</error>");
                    return \Magento\Framework\Console\Cli::RETURN_FAILURE;
                }
            }

            // Generate registration.php file
            $registrationFile = "$modulePath/registration.php";
            if (!file_put_contents($registrationFile, $this->generateRegistrationFileContent($moduleName))) {
                $output->writeln("<error>Failed to write registration.php file in '$modulePath'.</error>");
                return \Magento\Framework\Console\Cli::RETURN_FAILURE;
            }

            // Generate module.xml file
            $moduleXmlDir = "$modulePath/etc";
            if (!file_exists($moduleXmlDir)) {
                mkdir($moduleXmlDir, 0777, true);
            }
            $moduleXmlFile = "$moduleXmlDir/module.xml";
            if (!file_put_contents($moduleXmlFile, $this->generateModuleXmlFileContent($moduleName))) {
                $output->writeln("<error>Failed to write module.xml file in '$moduleXmlFile'.</error>");
                return \Magento\Framework\Console\Cli::RETURN_FAILURE;
            }

            $output->writeln("<info>Magento module '$moduleName' created successfully at '$modulePath'.</info>");
            return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
        }

        protected function validateModuleName($moduleName)
        {
            return preg_match('/^[A-Za-z]+_[A-Za-z]+$/', $moduleName);
        }

        protected function generateRegistrationFileContent($moduleName)
        {
            return "<?php\n" .
                "use Magento\Framework\Component\ComponentRegistrar;\n" .
                "ComponentRegistrar::register(ComponentRegistrar::MODULE, '$moduleName', __DIR__);\n";
        }

        protected function generateModuleXmlFileContent($moduleName)
        {
            list($vendor, $module) = explode('_', $moduleName);
            return "<?xml version=\"1.0\"?>\n" .
                "<config xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"urn:magento:framework:Module/etc/module.xsd\">\n" .
                "    <module name=\"$moduleName\" setup_version=\"1.0.0\">\n" .
                "    </module>\n" .
                "</config>\n";
        }
    }
