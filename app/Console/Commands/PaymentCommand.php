<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// Carbon
use Carbon\Carbon;
// Models
use App\Models\Order;

class PaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PaymentCommand:display';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Order::where([
            "payment_type"  => "visa",
            "payment_status"  => "pending",
        ])->get();
        foreach($orders as $order) {
            $o = (new \App\Payment\Payment)->checkPayment($order);
            if($o["status"] == true) {
                /**
                 * SUCCESSFUL
                 * FAILED
                 * EXPIRED
                 */
                if($o["action"] == "SUCCESSFUL") {
                    $order->update([
                        "order_status"      => Order::STATUS_CONFIRMED,
                        "payment_status"    => "paid"
                    ]);
                    $order->histories()->create([
                        "order_status"    => Order::STATUS_CONFIRMED
                    ]);
                    (new \App\Support\FireBase)->setTitle(env("APP_NAME"))->setBody("Order Number #".$order->id." is ".Order::STATUS_CONFIRMED)->setToken($order->customer->fire_base_token)->build();
                } elseif ($o["action"] == "FAILED") {
                    $order->update([
                        "order_status"      => Order::STATUS_REJECTED,
                        "payment_status"    => "unpaid"
                    ]);
                    $order->histories()->create([
                        "order_status"    => Order::STATUS_REJECTED
                    ]);
                    (new \App\Support\FireBase)->setTitle(env("APP_NAME"))->setBody("Order Number #".$order->id." is ".Order::STATUS_REJECTED)->setToken($order->customer->fire_base_token)->build();
                } elseif ($o["action"] == "EXPIRED") {
                    $order->update([
                        "order_status"      => Order::STATUS_REJECTED,
                        "payment_status"    => "unpaid"
                    ]);
                    $order->histories()->create([
                        "order_status"    => Order::STATUS_REJECTED
                    ]);
                    (new \App\Support\FireBase)->setTitle(env("APP_NAME"))->setBody("Order Number #".$order->id." is ".Order::STATUS_REJECTED)->setToken($order->customer->fire_base_token)->build();
                }
            }
        }
        return Command::SUCCESS;
    }
}
