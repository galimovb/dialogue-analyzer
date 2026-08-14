<?php

namespace Database\Seeders;

use App\Modules\Dialogues\Enums\DialogueOutcome;
use App\Modules\Users\Enums\UserRole;
use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DialogueSeeder extends Seeder
{
    public function run(): void
    {
        if (Dialogue::query()->exists()) {
            return;
        }

        $managers = User::query()->where('role', UserRole::Manager)->get();
        $clients = User::query()->where('role', UserRole::Client)->get();

        $this->seedScripted($managers->keyBy('email'), $clients->keyBy('email'));
        $this->seedGenerated($managers, $clients);
    }

    /**
     * Осмысленные сценарии из ТЗ.
     *
     * @param  Collection<string, User>  $managers
     * @param  Collection<string, User>  $clients
     */
    private function seedScripted(Collection $managers, Collection $clients): void
    {
        $scripted = [
            [
                'manager' => 'anna@example.com',
                'client' => 'client.smirnov@example.com',
                'outcome' => DialogueOutcome::Purchased,
                'messages' => [
                    ['manager', 'Здравствуйте! Меня зовут Анна, чем могу помочь?'],
                    ['client', 'Добрый день. Интересует тариф «Бизнес».'],
                    ['manager', 'Отличный выбор — он включает поддержку 24/7 и до 10 пользователей.'],
                    ['client', 'Звучит хорошо. Как оплатить?'],
                    ['manager', 'Отправлю ссылку на оплату, всё займёт пару минут.'],
                    ['client', 'Оплатил, спасибо!'],
                ],
            ],
            [
                'manager' => 'igor@example.com',
                'client' => 'client.orlova@example.com',
                'outcome' => DialogueOutcome::NotPurchased,
                'messages' => [
                    ['manager', 'Здравствуйте! Подскажу по нашим тарифам.'],
                    ['client', 'Здравствуйте. Пока просто смотрю варианты.'],
                    ['manager', 'Могу рассказать про самый популярный тариф.'],
                    ['client', 'Спасибо, но нам сейчас не актуально.'],
                    ['manager', 'Понял, буду рад помочь, если передумаете.'],
                ],
            ],
            [
                'manager' => 'igor@example.com',
                'client' => 'client.morozova@example.com',
                'outcome' => DialogueOutcome::Purchased,
                'messages' => [
                    ['manager', 'Добрый день! Готов ответить на вопросы по продукту.'],
                    ['client', 'Здравствуйте. Дороговато выглядит, если честно.'],
                    ['manager', 'Понимаю. Давайте посчитаем экономию — обычно окупается за месяц.'],
                    ['client', 'Хорошо, убедили. Берём.'],
                    ['manager', 'Отлично! Оформляю.'],
                ],
            ],
            [
                'manager' => 'anna@example.com',
                'client' => 'client.volkov@example.com',
                'outcome' => DialogueOutcome::NotPurchased,
                'messages' => [
                    ['manager', 'Здравствуйте! Видела вашу заявку, готова помочь с выбором.'],
                    ['client', 'Здравствуйте, да, рассматриваю подключение.'],
                    ['manager', 'Отлично! Предлагаю созвон на 15 минут, покажу демо.'],
                    ['manager', 'Также могу прислать материалы на почту, как удобнее?'],
                ],
            ],
        ];

        foreach ($scripted as $i => $data) {
            $this->createDialogue(
                $managers[$data['manager']],
                $clients[$data['client']],
                $data['outcome'],
                $data['messages'],
                Carbon::now()->subDays(20 - $i)->setTime(10, 0),
            );
        }
    }

    /**
     * Пачка сгенерированных диалогов — чтобы список и сообщения пагинировались.
     *
     * @param  Collection<int, User>  $managers
     * @param  Collection<int, User>  $clients
     */
    private function seedGenerated(Collection $managers, Collection $clients): void
    {
        $managerPhrases = [
            'Здравствуйте! Чем могу помочь?',
            'Расскажу подробнее о тарифах.',
            'Готов подобрать оптимальный вариант.',
            'Отправлю коммерческое предложение.',
            'Уточните, сколько пользователей планируете?',
            'Могу показать демо продукта.',
            'Есть вопросы по интеграции?',
            'Спасибо за обращение!',
        ];
        $clientPhrases = [
            'Здравствуйте.',
            'Интересует ваш продукт.',
            'Сколько это стоит?',
            'А есть пробный период?',
            'Нужно подумать.',
            'Дороговато выходит.',
            'Хорошо, рассмотрим.',
            'Спасибо за информацию.',
        ];

        for ($d = 0; $d < 12; $d++) {
            $count = random_int(6, 18);
            $lines = [];
            for ($m = 0; $m < $count; $m++) {
                $isManager = $m % 2 === 0;
                $lines[] = [
                    $isManager ? 'manager' : 'client',
                    $isManager ? $managerPhrases[array_rand($managerPhrases)] : $clientPhrases[array_rand($clientPhrases)],
                ];
            }

            $this->createDialogue(
                $managers->random(),
                $clients->random(),
                DialogueOutcome::cases()[array_rand(DialogueOutcome::cases())],
                $lines,
                Carbon::now()->subDays(random_int(1, 15))->setTime(random_int(9, 18), 0),
            );
        }
    }

    /**
     * @param  array<int, array{0: string, 1: string}>  $lines
     */
    private function createDialogue(User $manager, User $client, DialogueOutcome $outcome, array $lines, Carbon $start): void
    {
        $dialogue = Dialogue::create([
            'manager_id' => $manager->id,
            'client_id' => $client->id,
            'outcome' => $outcome,
        ]);

        $time = $start;
        foreach ($lines as [$role, $text]) {
            $dialogue->messages()->create([
                'sender_id' => ($role === 'manager' ? $manager : $client)->id,
                'text' => $text,
                'sent_at' => $time,
            ]);
            $time = $time->addMinutes(random_int(2, 12));
        }
    }
}
