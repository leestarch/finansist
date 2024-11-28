<?php

namespace Database\Seeders;

use App\Models\OperationRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void

    {
        OperationRule::query()->firstOrCreate([
            'category_id' => 8,
            'contractor_id' => 220,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 123,
            'contractor_id' => 177,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 12,
            'contractor_id' => 895,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'contractor_id' => 970,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'contractor_id' => 986,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 57,
            'contractor_id' => 128,
            'purpose_expression' => '/сервиса Lookin. Team/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 4,
            'contractor_id' => 905,
            'purpose_expression' => '/Оплата по Долгосрочному договору аренды/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 4,
            'contractor_id' => 904,
            'purpose_expression' => '/Оплата по Долгосрочному договору аренды/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 2,
            'contractor_id' => 1057,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 81,
            'contractor_id' => 319,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 20,
            'contractor_id' => 295,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 84,
            'contractor_id' => 231,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'contractor_id' => 672,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 265,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 84,
            'contractor_id' => 1041,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'contractor_id' => 258,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'contractor_id' => 282,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 757,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 90,
            'contractor_id' => 17,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 16,
            'contractor_id' => 276,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 16,
            'contractor_id' => 276,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 16,
            'contractor_id' => 898,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 43,
            'contractor_id' => 913,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 84,
            'contractor_id' => 275,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'contractor_id' => 255,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 52,
            'contractor_id' => 1037,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'contractor_id' => 255,
            'purpose_expression' => '/бухгалтерские услуги/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 323,
            'purpose_expression' => '/Покупка PURCHASE_CB в ТУ Сбербанка/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'contractor_id' => 323,
        ]);

        $contractors = [417, 259, 322, 428, 418, 222, 971, 974];
        foreach ($contractors as $contractor) {
            OperationRule::query()->firstOrCreate([
                'category_id' => 79,
                'contractor_id' => $contractor,
                'purpose_expression' => '/Перемещение .* за продукты, расходные материалы, инвентарь и оборудование/i',
            ]);
        }

        $contractors = [417, 259, 322, 428, 418, 222, 971, 974];
        foreach ($contractors as $contractor) {
            OperationRule::query()->firstOrCreate([
                'category_id' => 79,
                'contractor_id' => $contractor,
            ]);
        }

        $contractors = [417, 259, 322, 428, 418, 222, 971, 974];
        foreach ($contractors as $contractor) {
            OperationRule::query()->firstOrCreate([
                'category_id' => 79,
                'contractor_id' => $contractor,
                'purpose_expression' => '/Перечисление денежных средств по договору/',
            ]);
        }

        $contractors = [417, 259, 322, 428, 418, 222, 971, 974];
        foreach ($contractors as $contractor) {
            OperationRule::query()->firstOrCreate([
                'category_id' => 180,
                'contractor_id' => $contractor,
                'purpose_expression' => '/Перечисление денежных средств по договору/',
            ]);
        }

        OperationRule::query()->firstOrCreate([
            'category_id' => 99,
            'contractor_id' => 106,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 99,
            'contractor_id' => 105,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 227,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 83,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 193,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 43,
            'contractor_id' => 234,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'contractor_id' => 234,
            'purpose_expression' => '/Отпускные по реестру/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 8,
            'contractor_id' => 220,
            'purpose_expression' => '/Плата за рекламу/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 220,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 99,
            'contractor_id' => 447,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 99,
            'contractor_id' => 296,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 99,
            'contractor_id' => 476,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 99,
            'contractor_id' => 973,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 94,
            'contractor_id' => 285,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 2,
            'contractor_id' => 35,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 12,
            'contractor_id' => 280,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 12,
            'contractor_id' => 661,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 12,
            'contractor_id' => 635,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 12,
            'contractor_id' => 856,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 66,
            'contractor_id' => 291,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 179,
            'contractor_id' => 601,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 146,
            'contractor_id' => 584,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 146,
            'contractor_id' => 74,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'contractor_id' => 189,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'contractor_id' => 736,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 180,
            'contractor_id' => 219,
            'purpose_expression' => '/Перечисление выручки по Агентскому договору/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 26,
            'contractor_id' => 219,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 72,
            'contractor_id' => 884,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 72,
            'contractor_id' => 843,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 72,
            'contractor_id' => 202,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 72,
            'contractor_id' => 82,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 72,
            'contractor_id' => 647,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 72,
            'contractor_id' => 674,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 25,
            'contractor_id' => 254,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 25,
            'contractor_id' => 284,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 25,
            'contractor_id' => 638,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 25,
            'contractor_id' => 897,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 25,
            'contractor_id' => 804,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 85,
            'contractor_id' => 239,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 85,
            'contractor_id' => 657,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 58,
            'contractor_id' => 75,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 58,
            'contractor_id' => 233,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 110,
            'contractor_id' => 90,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 81,
            'contractor_id' => 349,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 81,
            'contractor_id' => 319,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 81,
            'contractor_id' => 199,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 854,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 659,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 267,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 71,
            'contractor_id' => 128,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 17,
            'contractor_id' => 216,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 16,
            'contractor_id' => 858,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 16,
            'contractor_id' => 678,
        ]);

        $contractors = [
            855 => 'ИП Бариев Абуталим Анварович',
            493 => 'ИП Поляков Вадим Александрович',
            905 => 'ИП Волкова Надежда Владимировна',
            904 => 'ООО "МЕЙСТЕР"',
            506 => 'ИП Мхоян Нелли Владимировна',
            396 => 'ИП Сергеев Сергей Тимофеевич',
            909 => 'ИП Загирова Зайима Гашимовна',
            122 => 'ИП Силютин Сергей Юрьевич',
            887 => 'ИП Попов Автандил Дмитриевич',
            675 => 'Левченко Андрей Сергеевич',
            670 => 'ИП Котов Николай Владимирович',
            551 => 'ООО "ИНТУРИСТ-СТАВРОПОЛЬ'
        ];

        foreach ($contractors as $contractorId => $name) {
            OperationRule::query()->firstOrCreate([
                'category_id' => 4,
                'contractor_id' => $contractorId,
            ]);
        }

        OperationRule::query()->firstOrCreate([
            'category_id' => 73,
            'contractor_id' => 214,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 73,
            'contractor_id' => 213,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 73,
            'contractor_id' => 206,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 83,
            'contractor_id' => 88,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 23,
            'contractor_id' => 279,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 23,
            'contractor_id' => 226,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 23,
            'contractor_id' => 268,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 96,
            'contractor_id' => 101,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 123,
            'contractor_id' => 496,
            'purpose_expression' => '/Перечисление дивидендов/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 123,
            'contractor_id' => 117,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 175,
            'contractor_id' => 496,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 73,
            'contractor_id' => 399,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 73,
            'contractor_id' => 781,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 84,
            'contractor_id' => 231,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 84,
            'contractor_id' => 133,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 84,
            'contractor_id' => 116,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 52,
            'contractor_id' => 1037,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 61,
            'contractor_id' => 191,
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 84,
            'contractor_id' => 275
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 43,
            'contractor_id' => 913
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 16,
            'contractor_id' => 276
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 16,
            'contractor_id' => 898
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 20,
            'contractor_id' => 295
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 90,
            'contractor_id' => 17
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 757
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'contractor_id' => 282
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 109,
            'contractor_id' => 816
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'contractor_id' => 258
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 84,
            'contractor_id' => 1041
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 7,
            'contractor_id' => 255
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 79,
            'contractor_id' => 265
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'contractor_id' => 672
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 29,
            'contractor_id' => 923
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 10,
            'contractor_id' => 715
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression'=> '/Отпускные по реестру/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression'=> '/Комиссия за обслуживание/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression'=> '/Комиссия в другие банки/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression'=> '/Комиссия за прием наличных денежных сред/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression'=> '/Расчет при увольнении по реестру/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression'=> '/Комиссия внутри Сбербанка/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression'=> '/Заработная плата/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression'=> '/Аванс по заработной плате по реестру/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression'=> '/Комиссия за услугу/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression'=> '/Комиссия за перечисление средств/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 20,
            'purpose_expression'=> '/услуги по сопровождению закупочных проце/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 81,
            'purpose_expression'=> '/выездные проверки и видеонаблюдение/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression'=> '/Плата за пакет услуг Только для ИП/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'purpose_expression'=> '/обслуживание систем пожарной безопасности/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'purpose_expression'=> '/Компенсации по реестру/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 109,
            'purpose_expression'=> '/Электроэнергии/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression'=> '/Взносы на обязательное страхование/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 72,
            'purpose_expression'=> '/организацию праздничного мероприятия/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression'=> '/Комиссия за предоставл. информации об опе/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 169,
            'purpose_expression'=> '/2636218750-23-1/',
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 156,
            'purpose_expression' => '/Возврат ошибочно перечисленных средств/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 156,
            'purpose_expression' => '/Возврат ошибочно перечисленных денежных средств/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 156,
            'purpose_expression' => '/\bВОЗВРАТ ДЕНЕЖНЫХ СРЕДСТВ ЗА ЗАКАЗ\b/i'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 107,
            'purpose_expression' => '/Зачисление средств по операциям эквайринга/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 107,
            'purpose_expression' => '/Зачисление по операциям эквайринга/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 38,
            'purpose_expression' => '/НДФЛ за/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 24,
            'purpose_expression' => '/^.*(?:При[её]м|при[её]м) (?:ден\.|день) (?:нал\.|нал) через УС.*$/u'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 36,
            'purpose_expression' => '/Единый налоговый платеж/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression' => '/Комиссия за обслуживание/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression' => '/Комиссия в другие банки/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression' => '/Комиссия за прием наличных денежных средств через банкомат/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression' => '/Расчет при увольнении по реестру/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression' => '/Комиссия внутри Сбербанка/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression' => '/Заработная плата/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression' => '/Аванс по заработной плате по реестру/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression' => '/Комиссия за услугу/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression' => '/Комиссия за перечисление средств/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 20,
            'purpose_expression' => '/услуги по сопровождению закупочных процедур/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 7,
            'purpose_expression' => '/выездные проверки и видеонаблюдение/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression' => '/Плата за пакет услуг Только для ИП/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 47,
            'purpose_expression' => '/обслуживание систем пожарной безопасности/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 6,
            'purpose_expression' => '/Компенсация по реестру/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 109,
            'purpose_expression' => '/Электроэнергии/'
        ]);


        OperationRule::query()->firstOrCreate([
            'category_id' => 44,
            'purpose_expression' => '/Взносы на обязательное страхование/'
        ]);

        OperationRule::query()->firstOrCreate([
            'category_id' => 72,
            'purpose_expression' => '/организацию праздничного мероприятия/'
        ]);

    }
}
