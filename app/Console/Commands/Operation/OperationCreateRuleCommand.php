<?php

namespace App\Console\Commands\Operation;

use App\Models\OperationRule;
use Illuminate\Console\Command;

class OperationCreateRuleCommand extends Command
{
    protected $signature = 'operation.rules.create.command';
    public function handle()
    {

        OperationRule::query()->firstOrCreate([
            'name' => 'НЕ принимаемые доходы',
            'category_id' => 156,
            'purpose_expression' => '/Возврат ошибочно перечисленных средств/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'НЕ принимаемые доходы',
            'category_id' => 156,
            'purpose_expression' => '/Возврат ошибочно перечисленных денежных средств/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'НЕ принимаемые доходы',
            'category_id' => 156,
            'purpose_expression' => '/\bВОЗВРАТ ДЕНЕЖНЫХ СРЕДСТВ ЗА ЗАКАЗ\b/i'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Расчетный Food Cost - покупки держателя дятлова в магазинах - ПАО Сбербанк и текст',
            'category_id' => 79,
            'contractor_id' => 323,
            'purpose_expression' => '/Покупка PURCHASE_CB в ТУ Сбербанка/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'банковские услуги  - ПАО Сбербанк',
            'category_id' => 6,
            'contractor_id' => 323,
        ]);

        $contractors = [417, 259, 322, 428, 418, 222, 971, 974];
        foreach ($contractors as $contractor) {
            OperationRule::query()->firstOrCreate([
                'name' => 'Прочие доходы - Пиццерии + получение средств',
                'category_id' => 79,
                'contractor_id' => $contractor,
                'purpose_expression' => '/Перемещение .* за продукты, расходные материалы, инвентарь и оборудование/i',
            ]);
        }

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

        OperationRule::query()->firstOrCreate([
            'name' => 'роялти - ООО "ДОДО ФРАНЧАЙЗИНГ"',
            'category_id' => 79,
            'contractor_id' => 220,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Управление - Спецова Виктория Геннадиевна',
            'category_id' => 99,
            'contractor_id' => 447,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Управление - ИП Попов Никита Андреевич',
            'category_id' => 99,
            'contractor_id' => 296,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Управление - Вдовина Елена Федоровна',
            'category_id' => 99,
            'contractor_id' => 476,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Управление - Вдовина Елена Федоровна',
            'category_id' => 99,
            'contractor_id' => 973,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Территориальный управляющий - Петров Павел Сергеевич',
            'category_id' => 94,
            'contractor_id' => 285,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'HR-менеджер - Бойцова Ирина Николаевна',
            'category_id' => 2,
            'contractor_id' => 35,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Вывоз отходов - ООО "ЭКО-СИТИ',
            'category_id' => 12,
            'contractor_id' => 280,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Вывоз отходов - ИП Ветров Александр Николаевич',
            'category_id' => 12,
            'contractor_id' => 661,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Вывоз отходов - АО "МУСОРОУБОРОЧНАЯ КОМПАНИЯ',
            'category_id' => 12,
            'contractor_id' => 635,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Вывоз отходов - ООО "РЕГУЛИРУЕМЫЙ ОПЕРАТОР',
            'category_id' => 12,
            'contractor_id' => 856,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Прочие расходы на сотрудников - Политова Анастасия Анатольевна',
            'category_id' => 66,
            'contractor_id' => 291,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Прочее оборудование - ООО "ЭНЕРГОКОМ',
            'category_id' => 179,
            'contractor_id' => 601,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Кредитные расходы (Ст4) - ООО "СТРОЙБАЗА-КМВ',
            'category_id' => 146,
            'contractor_id' => 584,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Кредитные расходы (Ст4) - АО КПК "СТАВРОПОЛЬСТРОЙОПТОРГ',
            'category_id' => 146,
            'contractor_id' => 74,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Охрана и безопасность - АО "МОБИЛЬНЫЕ ВИДЕОРЕШЕНИЯ',
            'category_id' => 47,
            'contractor_id' => 189,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Охрана и безопасность - ООО "ГРАНИТ',
            'category_id' => 47,
            'contractor_id' => 736,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Прочие доходы - ООО "ДОДО КЦ СЫКТЫВКАР"',
            'category_id' => 180,
            'contractor_id' => 219,
            'purpose_expression' => '/Перечисление выручки по Агентскому договору/',
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Колл-центр - ООО "ДОДО КЦ СЫКТЫВКАР"',
            'category_id' => 26,
            'contractor_id' => 219,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Распространение, размещение - ИП Коляко Наталья Петровна',
            'category_id' => 72,
            'contractor_id' => 884,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Распространение, размещение - Конуп Алена Владимировна',
            'category_id' => 72,
            'contractor_id' => 843,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Распространение, размещение - ООО "АДВ-СЕРВИС',
            'category_id' => 72,
            'contractor_id' => 202,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Распространение, размещение - ИП Авдеев Борис Александрович',
            'category_id' => 72,
            'contractor_id' => 82,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Распространение, размещение - Гусарова Анастасия Валерьевна',
            'category_id' => 72,
            'contractor_id' => 647,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Распространение, размещение - Гусарова Анастасия Валерьевна',
            'category_id' => 72,
            'contractor_id' => 674,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Интернет и телефон - ООО "СЕТЬ',
            'category_id' => 25,
            'contractor_id' => 254,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Интернет и телефон - ПАО "ВЫМПЕЛКОМ',
            'category_id' => 25,
            'contractor_id' => 284,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Интернет и телефон - АО "ЭР-ТЕЛЕКОМ ХОЛДИНГ',
            'category_id' => 25,
            'contractor_id' => 638,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Интернет и телефон - ООО "СВЯЗЬПОСТАВКА',
            'category_id' => 25,
            'contractor_id' => 897,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Интернет и телефон - ПАО "РОСТЕЛЕКОМ',
            'category_id' => 25,
            'contractor_id' => 804,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Ремонт, обслуживание - ООО "МИДАС"',
            'category_id' => 85,
            'contractor_id' => 239,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Ремонт, обслуживание - ИП Бережной Юрий Викторович',
            'category_id' => 85,
            'contractor_id' => 657,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Программное обеспечение, размещение - АО "ПФ "СКБ КОНТУРЭ"',
            'category_id' => 58,
            'contractor_id' => 75,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Программное обеспечение, размещение - ООО "КОМПАНИЯ "ТЕНЗОР"',
            'category_id' => 58,
            'contractor_id' => 233,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Юридические услуги - ИП Бойко Виктория Владимировна',
            'category_id' => 110,
            'contractor_id' => 90,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Ревизор-контролер - Холодов Антон Владимирович',
            'category_id' => 81,
            'contractor_id' => 349,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Ревизор-контролер - Смирнов Олег Валерьевич',
            'category_id' => 81,
            'contractor_id' => 319,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Ревизор-контролер - Смирнов Олег Валерьевич',
            'category_id' => 81,
            'contractor_id' => 199,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'фудкост - ООО "КУБАНЬОВОЩ"',
            'category_id' => 79,
            'contractor_id' => 854,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'фудкост - ИП БОКОВ АНДРЕЙ ФЕДОРОВИЧ',
            'category_id' => 79,
            'contractor_id' => 659,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'фудкост - ООО "ТРАНСКУБАНЬ',
            'category_id' => 79,
            'contractor_id' => 267,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'разработка - ИП Сысоев Иван Геннадьевич',
            'category_id' => 71,
            'contractor_id' => 128,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'доставка - ООО"ДЕЛОВЫЕ ЛИНИИ"',
            'category_id' => 17,
            'contractor_id' => 216,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Дезинсекция и дезинфекция - ООО "СЭС "КОНТРОЛЬ"',
            'category_id' => 16,
            'contractor_id' => 858,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Дезинсекция и дезинфекция - ИП Мороз Юрий Леонидович',
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
                'name' => $name,
                'category_id' => 4,
                'contractor_id' => $contractorId,
            ]);
        }

        OperationRule::query()->firstOrCreate([
            'name' => 'налог',
            'category_id' => 36,
            'purpose_expression' => '/Единый налоговый платеж/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'расходные материлаы - ООО "ГУДВИН-М"',
            'category_id' => 73,
            'contractor_id' => 214,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'расходные материлаы - ООО "ГИГИЕНА-СЕРВИС',
            'category_id' => 73,
            'contractor_id' => 213,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'расходные материлаы - ООО "АЛТЭК',
            'category_id' => 73,
            'contractor_id' => 206,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Рекламные материалы - ИП Беседин Никита Михайлович',
            'category_id' => 83,
            'contractor_id' => 88,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Инвентарь и мелкое оборудование - ООО "ЭЙБИЭС"',
            'category_id' => 23,
            'contractor_id' => 279,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Инвентарь и мелкое оборудование - ООО Интернет Решения',
            'category_id' => 23,
            'contractor_id' => 226,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Инвентарь и мелкое оборудование - ООО "ТРАПЕЗА"',
            'category_id' => 23,
            'contractor_id' => 268,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Технический специалист - Клыба Максим Олегович',
            'category_id' => 96,
            'contractor_id' => 101,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Выплата Дивидендов - ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ДЯТЛОВА АННА АНДРЕЕВНА',
            'category_id' => 123,
            'contractor_id' => 496,
            'purpose_expression' => '/Перечисление дивидендов/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Выплата Дивидендов - ПРИДАННИКОВ ДМИТРИЙ СЕРГЕЕВИЧ',
            'category_id' => 123,
            'contractor_id' => 117,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'полученные займы - ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ДЯТЛОВА АННА АНДРЕЕВНА',
            'category_id' => 175,
            'contractor_id' => 496,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'расходные материлаы - ИП Тальвик Марина Михайловна',
            'category_id' => 73,
            'contractor_id' => 399,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'расходные материлаы - ООО "ТРИАЛ МАРКЕТ"',
            'category_id' => 73,
            'contractor_id' => 781,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'рекрутинг - ООО "КЕХ ЕКОММЕРЦ',
            'category_id' => 84,
            'contractor_id' => 231,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'рекрутинг - ИП Хрусталев Евгений Павлович',
            'category_id' => 84,
            'contractor_id' => 133,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'рекрутинг - ИП Педашенко Александр Алексеевич',
            'category_id' => 84,
            'contractor_id' => 116,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Подрядчики',
            'category_id' => 52,
            'contractor_id' => 1037,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'прочие - МОО "СТАРШИЕ БРАТЬЯ СТАРШИЕ СЕСТРЫ',
            'category_id' => 61,
            'contractor_id' => 191,
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Банковские услуги',
            'category_id' => 6,
            'purpose_expression' => '/Комиссия за обслуживание/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Банковские услуги',
            'category_id' => 6,
            'purpose_expression' => '/Комиссия в другие банки/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Банковские услуги',
            'category_id' => 6,
            'purpose_expression' => '/Комиссия за прием наличных денежных средств через банкомат/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Оплата труда кухни',
            'category_id' => 44,
            'purpose_expression' => '/Расчет при увольнении по реестру/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Банковские услуги',
            'category_id' => 6,
            'purpose_expression' => '/Комиссия внутри Сбербанка/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Оплата труда кухни',
            'category_id' => 44,
            'purpose_expression' => '/Заработная плата/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Оплата труда кухни',
            'category_id' => 44,
            'purpose_expression' => '/Аванс по заработной плате по реестру/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Банковские услуги',
            'category_id' => 6,
            'purpose_expression' => '/Комиссия за услугу/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Банковские услуги',
            'category_id' => 6,
            'purpose_expression' => '/Комиссия за перечисление средств/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Рекрутинг',
            'category_id' => 84,
            'contractor_id' => 275
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Оплата труда доставки',
            'category_id' => 43,
            'contractor_id' => 913
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Дезинсекция и дезинфекция',
            'category_id' => 16,
            'contractor_id' => 276
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Дезинсекция и дезинфекция',
            'category_id' => 16,
            'contractor_id' => 898
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Закупщик сырья - Попов Евгений Янович',
            'category_id' => 20,
            'contractor_id' => 295
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Закупщик сырья',
            'category_id' => 20,
            'purpose_expression' => '/услуги по сопровождению закупочных процедур/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Бухгалтерские услуги',
            'category_id' => 7,
            'purpose_expression' => '/выездные проверки и видеонаблюдение/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Банковские услуги',
            'category_id' => 6,
            'purpose_expression' => '/Плата за пакет услуг Только для ИП/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Охрана и безопасность',
            'category_id' => 47,
            'purpose_expression' => '/обслуживание систем пожарной безопасности/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Страхование',
            'category_id' => 90,
            'contractor_id' => 17
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Банковские услуги',
            'category_id' => 6,
            'purpose_expression' => '/Компенсация по реестру/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Электроэнергия',
            'category_id' => 109,
            'purpose_expression' => '/Электроэнергии/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'электроэнергия - АО "ЭР-ТЕЛЕКОМ ХОЛДИНГ',
            'category_id' => 109,
            'contractor_id' => 816
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Расчетный Food Cost',
            'category_id' => 79,
            'contractor_id' => 757
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Оплата труда кухни',
            'category_id' => 44,
            'contractor_id' => 282
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Оплата труда кухни',
            'category_id' => 44,
            'purpose_expression' => '/Взносы на обязательное страхование/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Охрана и безопасность',
            'category_id' => 47,
            'contractor_id' => 258
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Рекрутинг',
            'category_id' => 84,
            'contractor_id' => 1041
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Бухгалтерские услуги',
            'category_id' => 7,
            'contractor_id' => 255
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Распространение, размещение',
            'category_id' => 72,
            'purpose_expression' => '/организацию праздничного мероприятия/'
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Расчетный Food Cost',
            'category_id' => 79,
            'contractor_id' => 265
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Охрана и безопасность',
            'category_id' => 47,
            'contractor_id' => 672
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Координатор',
            'category_id' => 29,
            'contractor_id' => 923
        ]);

        OperationRule::query()->firstOrCreate([
            'name' => 'Вода и канализация',
            'category_id' => 10,
            'contractor_id' => 715
        ]);

    }
}
