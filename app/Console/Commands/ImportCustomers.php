<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\Customer;

class ImportCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customers {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers into the customers table';

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
        
        if(!$this->confirm('Do you really wish to import customers?')) {
            $this->error('Import cancelled');
            return 1;
        }

        $this->info('Starting import');
        
        $rows = SimpleExcelReader::create($file)->getRows();
        
        $rows->each(function (array $row) {
           // $this->info($row["FirstName LastName"]);
            Customer::updateOrCreate(
                ['job_title' => $row['Job Title'],
                'email_address' => $row['Email Address'],
                'first_name' => explode(' ',$row['FirstName LastName'])[0],
                'last_name' => explode(' ',$row['FirstName LastName'])[1],
                'registered_since' => Date('Y-m-d H:i:s',strtotime($row['registered_since'])),
                'phone' => $row['phone']]
            );
            $this->info("Imported {$row['FirstName LastName']}");
        });

        $this->info('Customers imported');
        return 0;
    }
}
