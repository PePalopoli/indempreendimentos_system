<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\TableHelper;

/**
 * Class RouterListCommand.
 */
class RouterListCommand extends AbstractCommand
{
    /**
     * configure.
     */
    protected function configure()
    {
        $this
            ->setName('router:list')
            ->setDescription('Listar as rotas no sistema');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = $this->getHelperSet()->get('table');
        $table->setHeaders(array('Name', 'Path', 'Requirements'));
        $table->setLayout(TableHelper::LAYOUT_DEFAULT);

        $controllers = $this->get('controllers');
        $collection = $controllers->flush();

        foreach ($collection as $name => $route) {
            $requirements = array();
            foreach ($route->getRequirements() as $key => $requirement) {
                $requirements[] = $key.' => '.$requirement;
            }

            $table->addRow(array($name, $route->getPath(), implode(', ', $requirements)));
        }

        $table->render($output);
    }
}
