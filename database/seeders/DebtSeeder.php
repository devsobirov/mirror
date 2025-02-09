<?php

namespace Database\Seeders;

use App\Enums\Finance\DebtStatusEnum;
use App\Models\Client;
use App\Models\Finance\Debt;
use App\Models\Finance\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DebtSeeder extends Seeder
{
    public function run(): void
    {
        $startDate = Carbon::now()->subYear(1); // Начинаем с октября прошлого года
        $now = Carbon::now();



        // Создаем базу клиентов (для повторных долгов)
        $clients = collect();

        for ($i = 0; $i < 2000; $i++) {
            // 1. Подготовка данных долга
            $debtDate = Carbon::create(
                $startDate->copy()->addDays(rand(0, $now->diffInDays($startDate)))->format('Y-m-d')
            );
            
            $debtAmount = rand(500000, 10000000);
            $debtPeriod = rand(1, 3); // 1-3 месяца
            $paymentDate = $debtDate->copy()->addMonths($debtPeriod);
            
            // 2. Получение или создание клиента
            $shouldUseExistingClient = rand(1, 100) <= 20 && $clients->isNotEmpty();
            
            if ($shouldUseExistingClient) {
                $client = $clients->random();
            } else {
                $client = Client::create([
                    'name' => fake()->name(),
                    'phone' => fake()->unique()->numerify('+998#########'),
                ]);
                $clients->push($client);
            }

            // 3. Создание долга
            $debt = Debt::create([
                'client_id' => $client->id,
                'amount' => $debtAmount,
                'payment_date' => $paymentDate,
                'status' => DebtStatusEnum::Paid->value,
                'created_at' => $debtDate,
                'updated_at' => $paymentDate,
            ]);

            // 4. Создание платежей
            $remainingAmount = $debtAmount;
            $numberOfPayments = rand(1, 4); // От 1 до 4 платежей для закрытия долга
            
            for ($j = 0; $j < $numberOfPayments; $j++) {
                $isLastPayment = $j === $numberOfPayments - 1;
                
                $paymentAmount = $isLastPayment 
                    ? $remainingAmount 
                    : rand(ceil($remainingAmount * 0.2), ceil($remainingAmount * 0.8));

                $paymentDate = $debtDate->copy()->addDays(
                    rand(1, $debt->payment_date->diffInDays($debtDate))
                );

                Payment::create([
                    'debt_id' => $debt->id,
                    'client_id' => $client->id,
                    'amount' => $paymentAmount,
                    'type' => rand(1, 3), // Предполагаем, что есть 3 типа платежей
                    'rating' => rand(3, 5),
                    'left_debt_amount' => max(0, $remainingAmount - $paymentAmount),
                    'created_at' => $paymentDate,
                    'updated_at' => $paymentDate,
                ]);

                $remainingAmount -= $paymentAmount;
            }

            if ($i % 100 === 0) {
                $this->command->info("Создано $i долгов");
            }
        }

        $this->command->info('Создание долгов завершено!');
    }
} 