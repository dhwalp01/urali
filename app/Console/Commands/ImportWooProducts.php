<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportWooProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-woo-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching WooCommerce products...');

        $products = $this->woocommerce->get('products', ['per_page' => 1]);

        // Dump the first product structure only once for setup
        dump($products[0]);
        return;

        foreach ($products as $product) {
            $this->importProduct($product);
        }

        $this->info('Import completed.');
    }
}
