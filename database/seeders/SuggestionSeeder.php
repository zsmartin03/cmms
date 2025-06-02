<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Suggestion;
use App\Models\User;
use App\Enums\SuggestionType;
use App\Enums\SuggestionStatus;
use App\Enums\SuggestionPriority;
use App\Enums\SuggestionCategory;
use Faker\Factory as Faker;

class SuggestionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('hu_HU');
        $users = User::all();

        if ($users->count() < 2) {
            $this->command->warn('Legalább 2 felhasználó szükséges a javaslatokhoz!');
            return;
        }

        $examples = [
            // PROBLEM típusok
            [
                'title' => 'WiFi hálózat gyakori szakadozása',
                'description' => 'Az irodai WiFi naponta többször is megszakad, ami akadályozza a munkavégzést.',
                'type' => SuggestionType::PROBLEM,
                'category' => SuggestionCategory::IT,
                'priority' => SuggestionPriority::HIGH,
                'status' => SuggestionStatus::SUBMITTED,
            ],
            [
                'title' => 'Beléptető rendszer hibája',
                'description' => 'A beléptető kártyák gyakran nem működnek, dolgozók nem tudnak bejutni.',
                'type' => SuggestionType::PROBLEM,
                'category' => SuggestionCategory::SECURITY,
                'priority' => SuggestionPriority::URGENT,
                'status' => SuggestionStatus::IN_PROGRESS,
            ],
            [
                'title' => 'Lift gyakran meghibásodik',
                'description' => 'Az épület liftje hetente többször is elromlik, nagy kellemetlenséget okozva.',
                'type' => SuggestionType::PROBLEM,
                'category' => SuggestionCategory::INFRASTRUCTURE,
                'priority' => SuggestionPriority::HIGH,
                'status' => SuggestionStatus::UNDER_REVIEW,
            ],
            [
                'title' => 'Áramszünetek gyakorisága',
                'description' => 'Túl gyakran vannak áramszünetek az épületben, ami zavarja a munkát.',
                'type' => SuggestionType::PROBLEM,
                'category' => SuggestionCategory::INFRASTRUCTURE,
                'priority' => SuggestionPriority::HIGH,
                'status' => SuggestionStatus::COMPLETED,
            ],

            // IDEA típusok
            [
                'title' => 'Növények telepítése az irodába',
                'description' => 'Zöld növények elhelyezése az irodákban a jobb levegőminőség érdekében.',
                'type' => SuggestionType::IDEA,
                'category' => SuggestionCategory::ENVIRONMENT,
                'priority' => SuggestionPriority::NORMAL,
                'status' => SuggestionStatus::SUBMITTED,
            ],
            [
                'title' => 'Új kávégép beszerzése',
                'description' => 'Jobb minőségű kávégép beszerzése a konyhahelyiségbe.',
                'type' => SuggestionType::IDEA,
                'category' => SuggestionCategory::INFRASTRUCTURE,
                'priority' => SuggestionPriority::LOW,
                'status' => SuggestionStatus::UNDER_REVIEW,
            ],
            [
                'title' => 'Flexibilis munkaidő bevezetése',
                'description' => 'Rugalmasabb munkaidő beosztás lehetővé tétele a dolgozók számára.',
                'type' => SuggestionType::IDEA,
                'category' => SuggestionCategory::WORK_CONDITIONS,
                'priority' => SuggestionPriority::NORMAL,
                'status' => SuggestionStatus::IN_PROGRESS,
            ],
            [
                'title' => 'Céges kirándulás szervezése',
                'description' => 'Közös csapatépítő program szervezése a kollégák számára.',
                'type' => SuggestionType::IDEA,
                'category' => SuggestionCategory::OTHER,
                'priority' => SuggestionPriority::LOW,
                'status' => SuggestionStatus::SUBMITTED,
            ],

            // COMPLAINT típusok
            [
                'title' => 'Túl hideg a légkondicionáló miatt',
                'description' => 'Az irodában túl hideg van, a légkondicionáló beállítása nem megfelelő.',
                'type' => SuggestionType::COMPLAINT,
                'category' => SuggestionCategory::WORK_CONDITIONS,
                'priority' => SuggestionPriority::NORMAL,
                'status' => SuggestionStatus::IN_PROGRESS,
            ],
            [
                'title' => 'Túl hangos a nyomtató',
                'description' => 'Az új nyomtató túl hangos, zavarja a munkavégzést.',
                'type' => SuggestionType::COMPLAINT,
                'category' => SuggestionCategory::IT,
                'priority' => SuggestionPriority::LOW,
                'status' => SuggestionStatus::COMPLETED,
            ],
            [
                'title' => 'Túl kevés parkolóhely',
                'description' => 'Nem elegendő a parkolóhelyek száma a dolgozók autóinak.',
                'type' => SuggestionType::COMPLAINT,
                'category' => SuggestionCategory::INFRASTRUCTURE,
                'priority' => SuggestionPriority::NORMAL,
                'status' => SuggestionStatus::SUBMITTED,
            ],
            [
                'title' => 'Rossz belső kommunikáció',
                'description' => 'Az információk nem jutnak el időben minden érintetthez.',
                'type' => SuggestionType::COMPLAINT,
                'category' => SuggestionCategory::COMMUNICATION,
                'priority' => SuggestionPriority::HIGH,
                'status' => SuggestionStatus::UNDER_REVIEW,
            ],

            // IMPROVEMENT típusok
            [
                'title' => 'Papírmentes iroda bevezetése',
                'description' => 'Digitális dokumentumkezelés bevezetése a papírhasználat csökkentésére.',
                'type' => SuggestionType::IMPROVEMENT,
                'category' => SuggestionCategory::ENVIRONMENT,
                'priority' => SuggestionPriority::HIGH,
                'status' => SuggestionStatus::IN_PROGRESS,
            ],
            [
                'title' => 'Tűzvédelmi oktatás frissítése',
                'description' => 'A tűzvédelmi oktatás anyagának és rendszerességének javítása.',
                'type' => SuggestionType::IMPROVEMENT,
                'category' => SuggestionCategory::SECURITY,
                'priority' => SuggestionPriority::HIGH,
                'status' => SuggestionStatus::IN_PROGRESS,
            ],
            [
                'title' => 'Karbantartási folyamatok optimalizálása',
                'description' => 'A karbantartási ütemterv és folyamatok hatékonyságának javítása.',
                'type' => SuggestionType::IMPROVEMENT,
                'category' => SuggestionCategory::PROCESSES,
                'priority' => SuggestionPriority::NORMAL,
                'status' => SuggestionStatus::REJECTED,
            ],
            [
                'title' => 'Belső kommunikációs rendszer fejlesztése',
                'description' => 'Új kommunikációs platform bevezetése a jobb információáramlás érdekében.',
                'type' => SuggestionType::IMPROVEMENT,
                'category' => SuggestionCategory::COMMUNICATION,
                'priority' => SuggestionPriority::NORMAL,
                'status' => SuggestionStatus::UNDER_REVIEW,
            ],
            [
                'title' => 'Zöld energia használatának növelése',
                'description' => 'Napelemes rendszer telepítése és energiatakarékos megoldások bevezetése.',
                'type' => SuggestionType::IMPROVEMENT,
                'category' => SuggestionCategory::ENVIRONMENT,
                'priority' => SuggestionPriority::HIGH,
                'status' => SuggestionStatus::IN_PROGRESS,
            ],
            [
                'title' => 'IT biztonsági protokollok frissítése',
                'description' => 'A számítógépes rendszerek biztonsági előírásainak modernizálása.',
                'type' => SuggestionType::IMPROVEMENT,
                'category' => SuggestionCategory::IT,
                'priority' => SuggestionPriority::HIGH,
                'status' => SuggestionStatus::IN_PROGRESS,
            ],
            [
                'title' => 'Ergonomikus munkakörülmények javítása',
                'description' => 'Állítható asztalok és ergonomikus székek beszerzése.',
                'type' => SuggestionType::IMPROVEMENT,
                'category' => SuggestionCategory::WORK_CONDITIONS,
                'priority' => SuggestionPriority::NORMAL,
                'status' => SuggestionStatus::SUBMITTED,
            ],
        ];

        foreach ($examples as $data) {
            $author = $users->random();
            $assigned = $users->where('id', '!=', $author->id)->random();

            Suggestion::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'type' => $data['type'],
                'category' => $data['category'],
                'priority' => $data['priority'],
                'status' => $data['status'],
                'author_id' => $author->id,
                'assigned_to' => $assigned->id,
                'location' => $faker->company . ' - ' . $faker->city,
                'admin_notes' => $faker->boolean(30) ? $faker->sentence(6) : null,
                'resolved_at' => in_array($data['status'], [SuggestionStatus::COMPLETED, SuggestionStatus::REJECTED]) ? $faker->dateTimeThisYear() : null,
            ]);
        }

        $this->command->info('19 javaslat sikeresen létrehozva!');
    }
}
