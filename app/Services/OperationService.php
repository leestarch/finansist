<?php

namespace App\Services;

use App\Models\Contractor;
use App\Models\LocalOperation;
use App\Models\Pizzeria;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OperationService
{

    private $selects;

    public function __construct()
    {
        // Инициализация массива с условиями
        $this->selects = [
            ['id' => '156', 'condition' => function ($item) { //НЕ принимаемые доходы
                return str_contains($item['sber_paymentPurpose'], 'Возврат ошибочно перечисленных денежных средств') || str_contains($item['sber_paymentPurpose'], 'Возврат ошибочно перечисленных средств');
            }],
            ['id' => '156', 'condition' => function ($item) { //НЕ принимаемые доходы
                return preg_match('/\bВОЗВРАТ ДЕНЕЖНЫХ СРЕДСТВ ЗА ЗАКАЗ\b/i', $item['sber_paymentPurpose']);
            }],
            ['id' => '79', 'condition' => function ($item) { //Расчетный Food Cost - покупки держателя дятлова в магазинах - ПАО Сбербанк и текст
                return $item['payee_contractor_id'] === 323 && str_contains($item['sber_paymentPurpose'], 'Покупка PURCHASE_CB в ТУ Сбербанка');
            }],
            ['id' => '6', 'condition' => function ($item) { //банковские услуги  - ПАО Сбербанк
                return $item['payee_contractor_id'] === 323;
            }],
            ['id' => '180', 'condition' => function ($item) { //Прочие доходы - Пиццерии + получение средств
                return in_array($item['payee_contractor_id'], [417, 259, 322, 428, 418, 222, 971, 974])  && preg_match('/Перемещение .* за продукты, расходные материалы, инвентарь и оборудование/i', $item['sber_paymentPurpose']);
            }],
            ['id' => '79', 'condition' => function ($item) { //фудкост - Пиццерии + отправка средств
                return in_array($item['payee_contractor_id'], [417, 259, 322, 428, 418, 222, 971, 974]);
            }],
            ['id' => '79', 'condition' => function ($item) { //фудкост - перемещения Контрагенты пиццерии и текст - какая категория?
                return in_array($item['payee_contractor_id'], [417, 259, 322, 428, 418, 222, 971, 974]) && str_contains($item['sber_paymentPurpose'], 'Перечисление денежных средств по договору') && $item['sber_direction'] == 'CREDIT';
            }],
            ['id' => '180', 'condition' => function ($item) { //Прочие доходы - перемещения - Контрагенты пиццерии и текст - какая категория?
                return in_array($item['payee_contractor_id'], [417, 259, 322, 428, 418, 222, 971, 974]) && str_contains($item['sber_paymentPurpose'], 'Перечисление денежных средств по договору') && $item['sber_direction'] == 'DEBIT';
            }],
            ['id' => '99', 'condition' => function ($item) { //Управление - ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ЛУКИНА НАТАЛЬЯ СПИРИДОНОВНА
                return $item['payee_contractor_id'] === 106;
            }],
            ['id' => '99', 'condition' => function ($item) { //Управление - ЛУКИН АНДРЕЙ ЕВГЕНЬЕВИЧ (ИП)
                return $item['payee_contractor_id'] === 105;
            }],
            ['id' => '79', 'condition' => function ($item) { //фудкост - ООО "ИСТ-ВЕСТ ЛОДЖИСТИКС"
                return $item['payee_contractor_id'] === 227;
            }],
            ['id' => '79', 'condition' => function ($item) { //фудкост - ИП Акбуюков Давид Хусейнович
                return $item['payee_contractor_id'] === 83;
            }],
            ['id' => '79', 'condition' => function ($item) { //фудкост - ООО "МУЛТОН ПАРТНЕРС"
                return $item['payee_contractor_id'] === 193;
            }],
            ['id' => '43', 'condition' => function ($item) { //Оплата труда доставки - ООО "КОНСОЛЬ.ПРО"
                return $item['payee_contractor_id'] === 234;
            }],
            ['id' => '44', 'condition' => function ($item) { //Оплата труда кухни
                return str_contains($item['sber_paymentPurpose'], 'Отпускные по реестру');
            }],
            ['id' => '107', 'condition' => function ($item) { //эквайринг
                return str_contains($item['sber_paymentPurpose'], 'Зачисление средств по операциям эквайринга');
            }],
            ['id' => '107', 'condition' => function ($item) { //эквайринг
                return str_contains($item['sber_paymentPurpose'], 'Зачисление по операциям эквайринга');
            }],
            ['id' => '38', 'condition' => function ($item) { //эквайринг
                return str_contains($item['sber_paymentPurpose'], 'НДФЛ за');
            }],
            ['id' => '24', 'condition' => function ($item) { // инкассация
                return preg_match('/^.*(?:При[её]м|при[её]м) (?:ден\.|день) (?:нал\.|нал) через УС.*$/u', $item['sber_paymentPurpose']);
            }],
            ['id' => '8', 'condition' => function ($item) { //Взносы в маркетинговый фонд - ООО "ДОДО ФРАНЧАЙЗИНГ"
                return $item['payee_contractor_id'] === 220 && str_contains($item['sber_paymentPurpose'], 'Плата за рекламу');
            }],
            ['id' => '79', 'condition' => function ($item) { //роялти - ООО "ДОДО ФРАНЧАЙЗИНГ"
                return $item['payee_contractor_id'] === 220;
            }],
            ['id' => '99', 'condition' => function ($item) { //Управление - Спецова Виктория Геннадиевна
                return $item['payee_contractor_id'] === 447;
            }],
            ['id' => '99', 'condition' => function ($item) { //Управление - ИП Попов Никита Андреевич
                return $item['payee_contractor_id'] === 296;
            }],
            ['id' => '99', 'condition' => function ($item) { //Управление - Вдовина Елена Федоровна
                return $item['payee_contractor_id'] === 476 || $item['payee_contractor_id'] === 973;
            }],
            ['id' => '94', 'condition' => function ($item) { //Территориальный управляющий - Петров Павел Сергеевич
                return $item['payee_contractor_id'] === 285;
            }],
            ['id' => '2', 'condition' => function ($item) { //HR-менеджер - Бойцова Ирина Николаевна
                return $item['payee_contractor_id'] === 35;
            }],
            ['id' => '12', 'condition' => function ($item) { //Вывоз отходов - ООО "ЭКО-СИТИ
                return $item['payee_contractor_id'] === 280;
            }],
            ['id' => '12', 'condition' => function ($item) { //Вывоз отходов - ИП Ветров Александр Николаевич
                return $item['payee_contractor_id'] === 661;
            }],
            ['id' => '12', 'condition' => function ($item) { //Вывоз отходов - АО "МУСОРОУБОРОЧНАЯ КОМПАНИЯ
                return $item['payee_contractor_id'] === 635;
            }],
            ['id' => '12', 'condition' => function ($item) { //Вывоз отходов - ООО "РЕГУЛИРУЕМЫЙ ОПЕРАТОР
                return $item['payee_contractor_id'] === 856;
            }],
            ['id' => '66', 'condition' => function ($item) { //Прочие расходы на сотрудников - Политова Анастасия Анатольевна
                return $item['payee_contractor_id'] === 291;
            }],
            ['id' => '179', 'condition' => function ($item) { //Прочее оборудование - ООО "ЭНЕРГОКОМ
                return $item['payee_contractor_id'] === 601;
            }],
            ['id' => '146', 'condition' => function ($item) { //Кредитные расходы (Ст4) - ООО "СТРОЙБАЗА-КМВ
                return $item['payee_contractor_id'] === 584;
            }],
            ['id' => '146', 'condition' => function ($item) { //Кредитные расходы (Ст4) - АО КПК "СТАВРОПОЛЬСТРОЙОПТОРГ
                return $item['payee_contractor_id'] === 74;
            }],
            ['id' => '47', 'condition' => function ($item) { //Охрана и безопасность - АО "МОБИЛЬНЫЕ ВИДЕОРЕШЕНИЯ
                return $item['payee_contractor_id'] === 189;
            }],
            ['id' => '47', 'condition' => function ($item) { //Охрана и безопасность - ООО "ГРАНИТ
                return $item['payee_contractor_id'] === 736;
            }],
            ['id' => '180', 'condition' => function ($item) { //Прочие доходы - ООО "ДОДО КЦ СЫКТЫВКАР"
                return ($item['payeer_contractor_id'] === 219) && str_contains($item['sber_paymentPurpose'], 'Перечисление выручки по Агентскому договору');
            }],
            ['id' => '26', 'condition' => function ($item) { //Колл-центр - ООО "ДОДО КЦ СЫКТЫВКАР"
                return $item['payee_contractor_id'] === 219;
            }],
            ['id' => '72', 'condition' => function ($item) { //Распространение, размещение - ИП Коляко Наталья Петровна
                return $item['payee_contractor_id'] === 884;
            }],
            ['id' => '72', 'condition' => function ($item) { //Распространение, размещение - Конуп Алена Владимировна
                return $item['payee_contractor_id'] === 843;
            }],
            ['id' => '72', 'condition' => function ($item) { //Распространение, размещение - ООО "АДВ-СЕРВИС
                return $item['payee_contractor_id'] === 202;
            }],
            ['id' => '72', 'condition' => function ($item) { //Распространение, размещение - ИП Авдеев Борис Александрович
                return $item['payee_contractor_id'] === 82;
            }],
            ['id' => '72', 'condition' => function ($item) { //Распространение, размещение - Гусарова Анастасия Валерьевна
                return $item['payee_contractor_id'] === 647;
            }],
            ['id' => '72', 'condition' => function ($item) { //Распространение, размещение - Гусарова Анастасия Валерьевна
                return $item['payee_contractor_id'] === 674;
            }],
            ['id' => '25', 'condition' => function ($item) { //Интернет и телефон - ООО "СЕТЬ
                return $item['payee_contractor_id'] === 254;
            }],
            ['id' => '25', 'condition' => function ($item) { //Интернет и телефон - ПАО "ВЫМПЕЛКОМ
                return $item['payee_contractor_id'] === 284;
            }],
            ['id' => '25', 'condition' => function ($item) { //Интернет и телефон - АО "ЭР-ТЕЛЕКОМ ХОЛДИНГ
                return $item['payee_contractor_id'] === 638;
            }],
            ['id' => '25', 'condition' => function ($item) { //Интернет и телефон - ООО "СВЯЗЬПОСТАВКА
                return $item['payee_contractor_id'] === 897;
            }],
            ['id' => '25', 'condition' => function ($item) { //Интернет и телефон - ПАО "РОСТЕЛЕКОМ
                return $item['payee_contractor_id'] === 804;
            }],
            ['id' => '85', 'condition' => function ($item) { //Ремонт, обслуживание - ООО "МИДАС"
                return $item['payee_contractor_id'] === 239;
            }],
            ['id' => '85', 'condition' => function ($item) { //Ремонт, обслуживание - ИП Бережной Юрий Викторович
                return $item['payee_contractor_id'] === 657;
            }],
            ['id' => '58', 'condition' => function ($item) { //Программное обеспечение, размещение - АО "ПФ "СКБ КОНТУРЭ"
                return $item['payee_contractor_id'] === 75;
            }],
            ['id' => '58', 'condition' => function ($item) { //Программное обеспечение, размещение - ООО "КОМПАНИЯ "ТЕНЗОР"
                return $item['payee_contractor_id'] === 233;
            }],
            ['id' => '110', 'condition' => function ($item) { //Юридические услуги - ИП Бойко Виктория Владимировна
                return $item['payee_contractor_id'] === 90;
            }],
            ['id' => '81', 'condition' => function ($item) { //Ревизор-контролер - Холодов Антон Владимирович
                return $item['payee_contractor_id'] === 349;
            }],
            ['id' => '81', 'condition' => function ($item) { //Ревизор-контролер - Смирнов Олег Валерьевич
                return $item['payee_contractor_id'] === 319 || $item['payee_contractor_id'] === 199;
            }],
            ['id' => '79', 'condition' => function ($item) { //фудкост - ООО "КУБАНЬОВОЩ"
                return $item['payee_contractor_id'] === 854;
            }],
            ['id' => '79', 'condition' => function ($item) { //фудкост - ИП БОКОВ АНДРЕЙ ФЕДОРОВИЧ
                return $item['payee_contractor_id'] === 659;
            }],
            ['id' => '79', 'condition' => function ($item) { //фудкост - ООО "ТРАНСКУБАНЬ
                return $item['payee_contractor_id'] === 267;
            }],
            ['id' => '71', 'condition' => function ($item) { //разработка - ИП Сысоев Иван Геннадьевич
                return $item['payee_contractor_id'] === 128;
            }],
            ['id' => '17', 'condition' => function ($item) { //доставка - ООО"ДЕЛОВЫЕ ЛИНИИ"
                return $item['payee_contractor_id'] === 216;
            }],
            ['id' => '16', 'condition' => function ($item) { //Дезинсекция и дезинфекция - ООО "СЭС "КОНТРОЛЬ"
                return $item['payee_contractor_id'] === 858;
            }],
            ['id' => '16', 'condition' => function ($item) { //Дезинсекция и дезинфекция - ИП Мороз Юрий Леонидович
                return $item['payee_contractor_id'] === 678;
            }],
            ['id' => '4', 'condition' => function ($item) { //аренда -
                // ИП Бариев Абуталим Анварович, ИП Поляков Вадим Александрович,
                // ИП Волкова Надежда Владимировна, ООО "МЕЙСТЕР", ИП Мхоян Нелли Владимировна,
                // ИП Сергеев Сергей Тимофеевич, ИП Загирова Зайима Гашимовна
                //ИП Силютин Сергей Юрьевич, ИП Попов Автандил Дмитриевич
                //Левченко Андрей Сергеевич, ИП Котов Николай Владимирович
                //ООО "ИНТУРИСТ-СТАВРОПОЛЬ
                return in_array($item['payee_contractor_id'], [855, 493, 905, 904, 506, 396, 909, 122, 887, 675, 670, 551]);
            }],
            ['id' => '36', 'condition' => function ($item) { //налог
                return str_contains($item['sber_paymentPurpose'], 'Единый налоговый платеж');
            }],
            ['id' => '73', 'condition' => function ($item) { //расходные материлаы - ООО "ГУДВИН-М"
                return $item['payee_contractor_id'] === 214;
            }],
            ['id' => '73', 'condition' => function ($item) { //расходные материлаы - ООО "ГИГИЕНА-СЕРВИС
                return $item['payee_contractor_id'] === 213;
            }],
            ['id' => '73', 'condition' => function ($item) { //расходные материлаы - ООО "АЛТЭК
                return $item['payee_contractor_id'] === 206;
            }],
            ['id' => '83', 'condition' => function ($item) { //Рекламные материалы - ИП Беседин Никита Михайлович
                return $item['payee_contractor_id'] === 88;
            }],
            ['id' => '23', 'condition' => function ($item) { //Инвентарь и мелкое оборудование - ООО "ЭЙБИЭС
                return $item['payee_contractor_id'] === 279;
            }],
            ['id' => '23', 'condition' => function ($item) { //Инвентарь и мелкое оборудование - ООО Интернет Решения
                return $item['payee_contractor_id'] === 226;
            }],
            ['id' => '23', 'condition' => function ($item) { //Инвентарь и мелкое оборудование - ООО "ТРАПЕЗА
                return $item['payee_contractor_id'] === 268;
            }],
            ['id' => '96', 'condition' => function ($item) { //Технический специалист - Клыба Максим Олегович
                return $item['payee_contractor_id'] === 101;
            }],
            ['id' => '123', 'condition' => function ($item) { //Выплата Дивидендов - ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ДЯТЛОВА АННА АНДРЕЕВНА
                return $item['payee_contractor_id'] === 496 && str_contains($item['sber_paymentPurpose'], 'Перечисление дивидендов');
            }],
            ['id' => '123', 'condition' => function ($item) { //Выплата Дивидендов - ПРИДАННИКОВ ДМИТРИЙ СЕРГЕЕВИЧ
                return $item['payee_contractor_id'] === 117;
            }],
            ['id' => '175', 'condition' => function ($item) { //полученные займы - ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ДЯТЛОВА АННА АНДРЕЕВНА
                return $item['payee_contractor_id'] === 496;
            }],
            ['id' => '73', 'condition' => function ($item) { //расходные материлаы - ИП Тальвик Марина Михайловна
                return $item['payee_contractor_id'] === 399;
            }],
            ['id' => '73', 'condition' => function ($item) { //расходные материлаы - ООО "ТРИАЛ МАРКЕТ
                return $item['payee_contractor_id'] === 781;
            }],
            ['id' => '33', 'condition' => function ($item) { //маректолог - Криничная Юлия Сергеевна
                return $item['payee_contractor_id'] === 168;
            }],
            ['id' => '84', 'condition' => function ($item) { //рекрутинг - ООО "КЕХ ЕКОММЕРЦ
                return $item['payee_contractor_id'] === 231;
            }],
            ['id' => '84', 'condition' => function ($item) { //рекрутинг - ИП Хрусталев Евгений Павлович
                return $item['payee_contractor_id'] === 133;
            }],
            ['id' => '84', 'condition' => function ($item) { //рекрутинг - ИП Педашенко Александр Алексеевич
                return $item['payee_contractor_id'] === 116;
            }],
            ['id' => '52', 'condition' => function ($item) { //Подрядчики
                return $item['payee_contractor_id'] === 1037;
            }],
            ['id' => '61', 'condition' => function ($item) { //прочие - МОО "СТАРШИЕ БРАТЬЯ СТАРШИЕ СЕСТРЫ
                return $item['payee_contractor_id'] === 191;
            }],
            ['id' => '6', 'condition' => function ($item) { //Банковские услуги
                return str_contains($item['sber_paymentPurpose'], 'Комиссия за обслуживание');
            }],
            ['id' => '6', 'condition' => function ($item) { //Банковские услуги
                return str_contains($item['sber_paymentPurpose'], 'Комиссия в другие банки');
            }],
            ['id' => '6', 'condition' => function ($item) { //Банковские услуги
                return str_contains($item['sber_paymentPurpose'], 'Комиссия за прием наличных денежных средств через банкомат');
            }],
            ['id' => '44', 'condition' => function ($item) { //Оплата труда кухни
                return str_contains($item['sber_paymentPurpose'], 'Расчет при увольнении по реестру');
            }],
            ['id' => '6', 'condition' => function ($item) { //Банковские услуги
                return str_contains($item['sber_paymentPurpose'], 'Комиссия внутри Сбербанка');
            }],
            ['id' => '44', 'condition' => function ($item) { //Оплата труда кухни
                return str_contains($item['sber_paymentPurpose'], 'Заработная плата');
            }],
            ['id' => '44', 'condition' => function ($item) { //Оплата труда кухни
                return str_contains($item['sber_paymentPurpose'], 'Аванс по заработной плате по реестру');
            }],
            ['id' => '6', 'condition' => function ($item) { //Банковские услуги
                return str_contains($item['sber_paymentPurpose'], 'Комиссия за услугу');
            }],
            ['id' => '6', 'condition' => function ($item) { //Банковские услуги
                return str_contains($item['sber_paymentPurpose'], 'Комиссия за перечисление средств');
            }],
            ['id' => '84', 'condition' => function ($item) { //Рекрутинг
                return $item['payee_contractor_id'] === 275;
            }],
            ['id' => '43', 'condition' => function ($item) { //Оплата труда доставки
                return $item['payee_contractor_id'] === 913;
            }],
            ['id' => '16', 'condition' => function ($item) { //Дезинсекция и дезинфекция
                return $item['payee_contractor_id'] === 276;
            }],
            ['id' => '16', 'condition' => function ($item) {  //Дезинсекция и дезинфекция
                return $item['payee_contractor_id'] === 898;
            }],
            ['id' => '20', 'condition' => function ($item) { //Закупщик сырья - Попов Евгений Янович
                return $item['payee_contractor_id'] === 295;
            }],
            ['id' => '20', 'condition' => function ($item) { //Закупщик сырья
                return str_contains($item['sber_paymentPurpose'], 'услуги по сопровождению закупочных процедур');
            }],
            ['id' => '7', 'condition' => function ($item) { //Бухгалтерские услуги
                return str_contains($item['sber_paymentPurpose'], 'выездные проверки и видеонаблюдение');
            }],
            ['id' => '6', 'condition' => function ($item) { //Банковские услуги
                return str_contains($item['sber_paymentPurpose'], 'Плата за пакет услуг Только для ИП');
            }],
            ['id' => '47', 'condition' => function ($item) { //Охрана и безопасность
                return str_contains($item['sber_paymentPurpose'], 'обслуживание систем пожарной безопасности');
            }],
            ['id' => '90', 'condition' => function ($item) { //Страхование
                return $item['payee_contractor_id'] === 17;
            }],
            ['id' => '6', 'condition' => function ($item) { //Банковские услуги
                return str_contains($item['sber_paymentPurpose'], 'Компенсация по реестру');
            }],
            ['id' => '109', 'condition' => function ($item) { //Электроэнергия
                return str_contains($item['sber_paymentPurpose'], 'Электроэнергии');
            }],
            ['id' => '109', 'condition' => function ($item) { //электроэнергия - АО "ЭР-ТЕЛЕКОМ ХОЛДИНГ
                return $item['payee_contractor_id'] === 816;
            }],
            ['id' => '79', 'condition' => function ($item) { //Расчетный Food Cost
                return $item['payee_contractor_id'] === 757;
            }],
            ['id' => '44', 'condition' => function ($item) { //Оплата труда кухни
                return $item['payee_contractor_id'] === 282;
            }],
            ['id' => '44', 'condition' => function ($item) { //Оплата труда кухни
                return str_contains($item['sber_paymentPurpose'], 'Взносы на обязательное страхование');
            }],
            ['id' => '47', 'condition' => function ($item) { //Охрана и безопасность
                return $item['payee_contractor_id'] === 258;
            }],
            ['id' => '84', 'condition' => function ($item) { //Рекрутинг
                return $item['payee_contractor_id'] === 1041;
            }],
            ['id' => '7', 'condition' => function ($item) { //Бухгалтерские услуги
                return $item['payee_contractor_id'] === 255;
            }],
            ['id' => '72', 'condition' => function ($item) { //Распространение, размещение
                return str_contains($item['sber_paymentPurpose'], 'организацию праздничного мероприятия');
            }],
            ['id' => '79', 'condition' => function ($item) { //Расчетный Food Cost
                return $item['payee_contractor_id'] === 265;
            }],
            ['id' => '47', 'condition' => function ($item) { //Охрана и безопасность
                return $item['payee_contractor_id'] === 672;
            }],
            ['id' => '29', 'condition' => function ($item) { //Координатор
                return $item['payee_contractor_id'] === 923;
            }],
            ['id' => '10', 'condition' => function ($item) { //Вода и канализация
                return $item['payee_contractor_id'] === 715;
            }],
        ];
    }

    public function process1CFile($file)
    {
        if (is_string($file)) {
            // Если передан путь, читаем содержимое из файла в storage
            $content = file_get_contents($file);
        } else {
            // Если передан объект, получаем содержимое через $file->getRealPath()
            $content = file_get_contents($file->getRealPath());
        }

        $contentutf8 = mb_convert_encoding($content, 'UTF-8', 'Windows-1251');
        $lines = explode("\r\n", $contentutf8);

// Массив для хранения ключей и значений
        $sections = [];
        $currentSection = null;

// Перебираем каждую строку и разбираем на ключ и значение
        foreach ($lines as $line) {
            // Пропускаем пустые строки
            if (empty($line)) {
                continue;
            }

            // Проверяем, является ли строка "разделом документа"
            if (str_starts_with($line, 'СекцияДокумент=')) {
                $currentSection = [];
            }

            // Разделяем строку по первому знаку `=`
            if (strpos($line, '=') !== false) {
                [$key, $value] = explode('=', $line, 2);

                // Сохраняем ключ-значение в текущем документе
                $currentSection[$key] = $value;
            }
            if (str_starts_with($line, 'КонецДокумента') && $currentSection !== null) {
                $sections[] = $currentSection;
                $currentSection = null;
            }
        }

        $result = collect();
        foreach ($sections as $section) {
            $pizzeria = Pizzeria::where('inn', $section['ПлательщикИНН'])->first();
            $contractor = Contractor::where('inn_kpp', $section['ПолучательИНН'])->first();

            $budgetAnalizeData = collect([
                'sber_paymentPurpose' => $section['НазначениеПлатежа'],
                'payee_contractor_id' => $contractor->id,
                'sber_direction' => 'CREDIT',
            ]);

            $budgetCategory = $this->findBudgetCategory($budgetAnalizeData);
            $date = Carbon::parse($section['Дата'])->toDateString();

            if (!$contractor) {
                $contractor = Contractor::create([
                    'full_name' => $section['Получатель1'],
                    'inn_kpp' => $section['ПолучательИНН'],
                    'bank_bik' => $section['ПолучательБИК'],
                    'corr_account' => $section['ПолучательКорсчет'],
                    'checking_account' => $section['ПолучательРасчСчет'],
                ]);
            }

            $result->push(collect([
                'sum' => $section['Сумма'],
                'pizzeria' => $pizzeria,
                'contractor' => $contractor,
                'purpose' => $section['НазначениеПлатежа'],
                'date' => $date,
                'account_number' => $section['ПлательщикСчет'],
                'budget_category' => $budgetCategory
            ]));
        }
        return $result;
    }

    public function createLocalOperationFromFileRequest(Request $request)
    {
        $files = [];
        $ticket_id = 30000;

        $createdOperations = [];

        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                if (pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION) == 'txt') { // если txt
                    $operationsData = $this->process1CFile($file);
                    foreach ($operationsData as $operationData) {
                        $createdLocalOperation = LocalOperation::create([
                            'account_number' => $operationData->account_number,
                            'date' => $operationData->date,
                            'pizzeria_id' => $operationData->pizzeria->id,
                            'budget' => $operationData->pizzeria->budget,
                            'sum' => $operationData->sum,
                            'contractor_id' => $operationData->contractor->id,
                            'payment_purpose' => $operationData->purpose,
                            'budget_category_id' => $operationData?->budget_category?->id ?? null,
                            'ticket_id' => $ticket_id ?? $request->input('ticket_id'),
                        ]);
                        $createdOperations[] = $createdLocalOperation;
                    }
                }
            }
        }
        return $createdOperations;
    }

    public function createLocalOperationFromFile($file, $ticket_id = null)
    {
        $createdOperations = [];
        $extension = is_string($file)
            ? pathinfo($file, PATHINFO_EXTENSION)
            : pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        if ($extension == 'txt') { // если txt
            $operationsData = $this->process1CFile($file); //сделать обработку нескольких поручений
            foreach ($operationsData as $operationData) {
                $createdLocalOperation = LocalOperation::create([
                    'account_number' => $operationData->account_number,
                    'date' => $operationData->date,
                    'pizzeria_id' => $operationData->pizzeria->id,
                    'budget' => $operationData->pizzeria->budget,
                    'sum' => $operationData->sum,
                    'contractor_id' => $operationData->contractor->id,
                    'payment_purpose' => $operationData->purpose,
                    'budget_category_id' => $operationData?->budget_category?->id ?? null,
                    'ticket_id' => $ticket_id,
                ]);
                $createdOperations[] = $createdLocalOperation;
            }
        }
        return $createdOperations;
    }


    public function findBudgetCategory($transactionData)
    {
        foreach ($this->selects as $id => $condition) {
            foreach ($this->selects as $select) {
                if ($select['condition']($transactionData)) {
                    return $select['id']; // возвращаем id, если условие выполнено
                }
            }
        }
        return null; // если ничего не найдено
    }

}
