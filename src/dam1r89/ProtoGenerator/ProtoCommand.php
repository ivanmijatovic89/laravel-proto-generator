<?php

namespace dam1r89\ProtoGenerator;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProtoCommand extends Command
{

    protected $name = 'proto';
    protected $description = 'Create model, views, controller, migration for a defined model. Example usage php `artisan proto user`';
    private $compiler;

    public function __construct(UnderscoreCompiler $compiler)
    {
        $this->compiler = $compiler;
        parent::__construct();
    }

    public function fire()
    {
        $parser = new TranslatableContextDataDecorator(new ContextDataParser($this->argument('model'), $this->option('fields')));


        $compiler = $this->compiler;
        $compiler->setContextData($parser->getContextData());

        $scanner = new TemplateDirScanner($compiler);

        $source = __DIR__ . '/templates/'.$this->option('template');
        $dest = base_path($this->option('output'));

        $this->info("Creating on path $dest from source $source");

        $files = $scanner->scan($source, $dest);

        foreach ($files as $file) {


            $this->createDir($file['dest']);

            $source = file_get_contents($file['src']);

            if (!file_exists($file['dest']) || file_exists($file['dest']) && $this->confirm("File {$file['dest']} exists, to you want to overwrite?")) {

                file_put_contents($file['dest'], $compiler->compile($source));
                $this->info("Generating {$file['dest']}");

            }

        }

        $info = $compiler->compile("add to the routes:\n\tRoute::model('__collection__', '__model__');\n\tRoute::resource('__collection__', '__controller__Controller');\n\n");

        $this->call('dump-autoload');

        $this->info($info);


    }

    private function createDir($target)
    {
        $parts = pathinfo($target);

        $dir = $parts['dirname'];

        if (!file_exists($dir)) {
            return mkdir($dir, 0777, true);
        }
        return false;
    }

    protected function getArguments()
    {
        return array(
            array('model', InputArgument::REQUIRED, 'Model for which you want to generate prototype model, controller, views and migration.'),
        );
    }

    protected function getOptions()
    {
        return array(
            array('fields', 'f', InputOption::VALUE_OPTIONAL, 'Model properties separated by comma (id field is included). Example --fields="name,category,test"', null),
            array('template', 't', InputOption::VALUE_OPTIONAL, 'Template path under the templates folder of source file', 'standard'),
            array('output', 'o', InputOption::VALUE_OPTIONAL, 'Output folder where file/folder structure will be generated, default is app', 'app')
        );
    }

}
