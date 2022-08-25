<?php

namespace App\Console\Commands;

use App\Services\ProductServices;
use Illuminate\Console\Command;

class ProductXmlUpdator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create xml from API request';

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
        ini_set('memory_limit',-1);
        (new ProductServices())->updateProduct();
        $this->info('Successfully updated!');

        return 0;
    }
}
