<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // КОМАНДЫ С ВЫСОКИМ ПРИОРИТЕТОМ
        $schedule->call(function () {
            Artisan::queue('scloud:saled-products')->onQueue('high'); // добавил логгер
        })->everyMinute()->name('scloud:saled-products')->withoutOverlapping();
//        $schedule->command('scloud:saled-products')->everyMinute()->runInBackground();

//        $schedule->command('ozon.stock.by.props')->everyThreeMinutes()->runInBackground();

        $schedule->call(function () {
            Artisan::queue('ozon.stock.by.props')->onQueue('high'); // добавил логгер
        })->everyThreeMinutes()->name('ozon.stock.by.props')->withoutOverlapping();

        $schedule->call(function () {
            Artisan::queue('wb:stocks')->onQueue('high'); // добавил логгер
        })->everyMinute()->name('wb:stocks')->withoutOverlapping();

        $schedule->call(function () {
            Artisan::queue('yandex:stocks')->onQueue('high');
        })->everyTwoMinutes()->name('yandex:stocks')->withoutOverlapping();

        $schedule->call(function () {
            Artisan::queue('wb.new.orders')->onQueue('high'); // добавил логгер
        })->everyTwoMinutes()->name('wb.new.orders')->withoutOverlapping();

        $schedule->call(function () {
            Artisan::queue('ozon.new.orders')->onQueue('high'); // добавил логгер
        })->everyTwoMinutes()->name('ozon.new.orders')->withoutOverlapping();

//      Отправление всех активных резервов
        $schedule->call(function () {
            Artisan::queue('send.reserves')->onQueue('high'); // добавил логгер
        })->everyFiveMinutes()->name('send.reserves')->withoutOverlapping();

//      -------------------------------
//      КОМАНДЫ СО СРЕДНИМ ПРИОРИТЕТОМ

//           Уведомление продавцам, которые взяли заказ не сборку, но по каким то причинам не закочнили
        $schedule->call(function () {
            Artisan::queue('notify.about.processing.order')->onQueue('middle');
        })->hourly()->name('notify.about.processing.order')->withoutOverlapping();

//           Уведомление в магазины, где заказы не были приняты в сборку более 2х часов
        $schedule->call(function () {
            Artisan::queue('notify.about.not.accepted.order')->onQueue('middle');
        })->hourly()->name('notify.about.not.accepted.order')->withoutOverlapping();

        //          Проверяем неотмененные резервы за последние несколько дней и есть такие - отменяем
        $schedule->call(function () {
            Artisan::queue('check.not.cancelled.reserves')->onQueue('middle');
        })->hourly()->name('check.not.cancelled.reserves')->withoutOverlapping();

//          Отправляем дневную стастистику по каждому магазину
        $schedule->call(function () {
            Artisan::queue('send.daily.statistic')->onQueue('middle');
        })->dailyAt('10:30')->name('send.daily.statistic')->withoutOverlapping();

//          Уведомляем админов о не принятых товарах курьерами
        $schedule->call(function () {
            Artisan::queue('check.couriers.goods.acception')->onQueue('middle');
        })->dailyAt('19:00')->name('check.couriers.goods.acception')->withoutOverlapping();

//          Отправляем курьерам сообщение если поставки/отгрпузки на сегодня не было
//
        $schedule->call(function () {
            Artisan::queue('is.no.reserve.for.stores')->onQueue('high');
        })->dailyAt('10:40')->name('is.no.reserve.for.stores')->withoutOverlapping();

//          Проверяем заказы озона, есть отмененные среди запакованных
        $schedule->call(function () {
            Artisan::queue('check.cancelled.orders')->onQueue('middle');
        })->everyTenMinutes()->name('check.cancelled.orders')->withoutOverlapping();

////          Отправляем поставку в доставку
        $schedule->call(function () {
            Artisan::queue('send.supply.to.delivery')->onQueue('middle');
        })->everyTenMinutes()->name('send.supply.to.delivery')->withoutOverlapping();

//          Отправляем отгрузку в доставку
        $schedule->call(function () {
            Artisan::queue('send.consignment.to.delivery')->onQueue('middle');
        })->everyTenMinutes()->name('send.consignment.to.delivery')->withoutOverlapping();

//            Обновление остатков из 1С
        $schedule->call(function () {
            Artisan::queue('scloud:new.amounts')->onQueue('middle'); // добавил логгер
        })->hourly()->name('scloud:new.amounts')->withoutOverlapping();

//         Проверяем на все ли новые заказы были отправлены уведомления и если нет, то отправялем заново
        $schedule->call(function () {
            Artisan::queue('check.not.sent.notifications')->onQueue('middle');
        })->everyTwoHours()->name('check.not.sent.notifications')->withoutOverlapping();

//      -----------------------------
//      КОМАНДЫ С НИЗКИМ ПРИОРИТЕТОМ

        $schedule->call(function () {
            Artisan::queue('app:amounts-sync-ids')->onQueue('low');
        })->dailyAt('2:00')->name('app:amounts-sync-ids')->withoutOverlapping();

//        $schedule->command('scloud:amounts:demo')->daily()->withoutOverlapping();
//            Удаление стикеров Озона после преобразования через Яндекс клауд
        $schedule->call(function () {
            Artisan::queue('clean.old.sticker')->onQueue('low');
        })->everyThreeHours()->name('clean.old.sticker')->withoutOverlapping();


//            Добавление новых товаров из загруженного файла  (JOB)

//        $schedule->command('props.import.by.uids')->everyThreeHours();
        $schedule->call(function () {
            Artisan::queue('props.import.by.uids')->onQueue('low');
        })->everyThreeHours()->name('props.import.by.uids')->withoutOverlapping();

//            Обновление цен вб
        $schedule->call(function () {
            Artisan::queue('wb:prices')->onQueue('low');
        })->hourly()->name('wb:prices')->withoutOverlapping();

//            Синхронизация nmids
        $schedule->call(function () {
            Artisan::queue('wb:nmids')->onQueue('low');
        })->daily()->name('wb:nmids')->withoutOverlapping();

        $schedule->call(function () {
            Artisan::queue('ozon:set-codes')->onQueue('low');
        })->daily()->name('ozon:set-codes')->withoutOverlapping();

//           Обновлекние цен озон
        $schedule->call(function () {
            Artisan::queue('ozon:prices')->onQueue('low');
        })->daily()->name('ozon:prices')->withoutOverlapping();

//        Удаление фоток собранных заказов, которым больше недели
        $schedule->call(function () {
            Artisan::queue('image.clean')->onQueue('low');
        })->daily()->name('image.clean')->withoutOverlapping();

//        Перенос изображений из локалки в яндекс
        $schedule->call(function () {
            Artisan::queue('image.to.s3.storage')->onQueue('low');
        })->daily()->name('image.to.s3.storage')->withoutOverlapping();

        // Импорт новых продуктов из 1С
        $schedule->call(function () {
            Artisan::queue('scloud:import.latest.products')->onQueue('low');
        })->dailyAt('4:00')->name('scloud:import.latest.products')->withoutOverlapping();

//      Синхронизация товаров из вб в ммс
        $schedule->call(function () {
            Artisan::queue('sync.wb-props.from.wb')->onQueue('low');
        })->daily()->name('sync.wb-props.from.wb')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}



