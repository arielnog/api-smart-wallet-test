<?php

namespace Database\Seeders;

use App\Models\TypeTransaction;
use Illuminate\Database\Seeder;

class TypeTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = [
            ['name' => 'Deposito/Transferencia'],
            ['name' => 'Saque/Transferencia'],
            ['name' => 'Deposito'],
            ['name' => 'Saque']
        ];

        TypeTransaction::insert($transactions);
    }
}
