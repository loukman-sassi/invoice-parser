<?php

declare(strict_types=1);


namespace App\Command;

use App\Service\CsvInvoiceParser;
use App\Service\JsonInvoiceParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:parse')]
class ParseInvoicesCommand extends Command
{
    private JsonInvoiceParser $JsonParser;
    private CsvInvoiceParser $CsvParser;

    public function __construct(JsonInvoiceParser $JsonParser, CsvInvoiceParser $CsvParser)
    {
        parent::__construct();
        $this->JsonParser = $JsonParser;
        $this->CsvParser = $CsvParser;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directory = 'data/';
        $files = glob($directory . '*');
        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $parser_map = [
                'csv' => $this->CsvParser,
                'json' => $this->JsonParser,
            ];
            if (isset($parser_map[$extension])) {
                $parser_map[$extension]->parse($file);
            }
        }
        return Command::SUCCESS;
    }
}