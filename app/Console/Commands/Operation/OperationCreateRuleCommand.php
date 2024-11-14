<?php

namespace App\Console\Commands\Operation;

use App\Models\OperationRule;
use Illuminate\Console\Command;

class OperationCreateRuleCommand extends Command
{
    protected $signature = 'operation.rules.create.command';
    public function handle()
    {
        $contractors = [417, 259, 322, 428, 418, 222, 971, 974];
        foreach ($contractors as $contractor) {
            OperationRule::query()->firstOrCreate([
                'name' => 'фудкост - Пиццерии + отправка средств',
                'category_id' => 79,
                'contractor_id' => $contractor,
            ]);
        }

        $contractors = [417, 259, 322, 428, 418, 222, 971, 974];
        foreach ($contractors as $contractor) {
            OperationRule::query()->firstOrCreate([
                'name' => 'фудкост - перемещения Контрагенты пиццерии и текст - какая категория?',
                'category_id' => 79,
                'contractor_id' => $contractor,
                'purpose_expression' => '/Перечисление денежных средств по договору/',
            ]);
        }

        $contractors = [417, 259, 322, 428, 418, 222, 971, 974];
        foreach ($contractors as $contractor) {
            OperationRule::query()->firstOrCreate([
                'name' => 'Прочие доходы - перемещения - Контрагенты пиццерии и текст - какая категория?',
                'category_id' => 180,
                'contractor_id' => $contractor,
                'purpose_expression' => '/Перечисление денежных средств по договору/',
            ]);
        }

        OperationRule::query()->firstOrCreate([
            'name' => 'Управление - ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ЛУКИНА НАТАЛЬЯ СПИРИДОНОВНА',
            'category_id' => 99,
            'contractor_id' => 106,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Управление - ЛУКИН АНДРЕЙ ЕВГЕНЬЕВИЧ (ИП)',
            'category_id' => 99,
            'contractor_id' => 105,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'фудкост - ООО "ИСТ-ВЕСТ ЛОДЖИСТИКС"',
            'category_id' => 79,
            'contractor_id' => 227,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'фудкост - ИП Акбуюков Давид Хусейнович',
            'category_id' => 79,
            'contractor_id' => 83,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'фудкост - ООО "МУЛТОН ПАРТНЕРС"',
            'category_id' => 79,
            'contractor_id' => 193,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Оплата труда доставки - ООО "КОНСОЛЬ.ПРО"',
            'category_id' => 43,
            'contractor_id' => 234,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Оплата труда кухни',
            'category_id' => 44,
            'contractor_id' => 234,
            'purpose_expression' => '/Отпускные по реестру/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'эквайринг',
            'category_id' => 107,
            'purpose_expression' => '/Зачисление средств по операциям эквайринга/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'эквайринг',
            'category_id' => 107,
            'purpose_expression' => '/Зачисление по операциям эквайринга/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'эквайринг',
            'category_id' => 38,
            'purpose_expression' => '/НДФЛ за/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'инкассация',
            'category_id' => 24,
            'purpose_expression' => '/^.*(?:При[её]м|при[её]м) (?:ден\.|день) (?:нал\.|нал) через УС.*$/u'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Взносы в маркетинговый фонд - ООО "ДОДО ФРАНЧАЙЗИНГ"',
            'category_id' => 8,
            'contractor_id' => 220,
            'purpose_expression' => '/Плата за рекламу/',
        ]);

    }
}
