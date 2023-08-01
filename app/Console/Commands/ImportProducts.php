<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\Product;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products into the products table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = $this->argument('file');
        
        if(!$this->confirm('Do you really wish to import products?')) {
            $this->error('Import cancelled');
            return 1;
        }

        $this->info('Starting import');

        $rows = SimpleExcelReader::create($file)->getRows();
        $rows->each(function (array $row) {
            Product::updateOrCreate(
                ['product_name' => $row['productname'],
                'price' => $row['price']]
            );
            $this->info("Imported {$row['productname']}");
        });

        $this->info('Products imported');
        return 0;
    }
}
