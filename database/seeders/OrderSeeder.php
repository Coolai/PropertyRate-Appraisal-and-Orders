<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Use: php artisan db:seed --class=OrderSeeder
     */
    public function run(): void
    {
        $orders_file_path = public_path('sample_orders.csv');
        $orders_file = file($orders_file_path);

        foreach ($orders_file as $line) {
            $data = str_getcsv($line);
            $order_id = $data[0];
            $product = $data[1];
            $city = $data[2];
            $state_code = $data[3];
            $zip_code = $data[4];
            $county = $data[5];

            DB::table('orders')->insert([
                'order_id' => $order_id,
                'product' => $product,
                'city' => $city,
                'state_code' => $state_code,
                'zip_code' => $zip_code,
                'county' => $county,
                'created_at' => date('Y-m-d H:i:s', strtotime($data[7]))
            ]);
        }
    }
}
