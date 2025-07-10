<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    private array $animalTypes = [
        'horse', 'pig', 'cow', 'sheep', 'chicken', 
        'duck', 'goat', 'rabbit', 'cat', 'dog'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $startDate = Carbon::now()->subWeek();
        $now = Carbon::now();
        
        $events = [];
        $targetEventCount = 1000;
        
        // Calculate how many events per user to reach our target
        $eventsPerUser = intval($targetEventCount / $users->count());
        $remainingEvents = $targetEventCount % $users->count();
        
        foreach ($users as $index => $user) {
            // Some users get one extra event to reach exactly 1000
            $userEventCount = $eventsPerUser + ($index < $remainingEvents ? 1 : 0);
            
            // Each user gets 3-7 different animal types to track
            $userAnimalTypes = fake()->randomElements($this->animalTypes, fake()->numberBetween(3, 7));
            
            // Distribute events across the animal types for this user
            $eventsPerType = [];
            $totalTypesEvents = 0;
            
            foreach ($userAnimalTypes as $typeIndex => $type) {
                if ($typeIndex === count($userAnimalTypes) - 1) {
                    // Last type gets remaining events
                    $eventsPerType[$type] = $userEventCount - $totalTypesEvents;
                } else {
                    $typeEvents = fake()->numberBetween(1, max(1, intval($userEventCount / count($userAnimalTypes)) + 2));
                    $eventsPerType[$type] = min($typeEvents, $userEventCount - $totalTypesEvents);
                    $totalTypesEvents += $eventsPerType[$type];
                }
            }
            
            foreach ($userAnimalTypes as $type) {
                if ($eventsPerType[$type] > 0) {
                    // Create a continuous timeline for this user+type combination
                    $timeline = $this->createTimelineForUserType($user->id, $type, $startDate, $now, $eventsPerType[$type]);
                    $events = array_merge($events, $timeline);
                }
            }
        }
        
        Event::insert($events);
    }
    
    private function createTimelineForUserType(int $userId, string $type, Carbon $startDate, Carbon $now, int $numEvents): array
    {
        $events = [];
        
        // Start timeline at random point in the past week
        $currentTime = fake()->dateTimeBetween($startDate, $now->copy()->subHours(1));
        $currentValence = fake()->randomElement(['GOOD', 'BAD']);
        
        // Calculate time windows for each event to spread them across the week
        $totalTimeSpan = $now->diffInSeconds($startDate);
        $avgEventDuration = $totalTimeSpan / $numEvents;
        
        for ($i = 0; $i < $numEvents; $i++) {
            $isLastEvent = ($i === $numEvents - 1);
            
            if ($isLastEvent) {
                // 70% chance the last event is still active
                $isActive = fake()->boolean(70);
                $validTo = $isActive ? null : fake()->dateTimeBetween($currentTime, $now);
            } else {
                // Non-last events are always closed
                // Calculate a reasonable end time for this event
                $minDuration = fake()->numberBetween(300, 3600); // 5 minutes to 1 hour
                $maxDuration = min($avgEventDuration * 2, $now->diffInSeconds($currentTime) / ($numEvents - $i));
                $eventDuration = fake()->numberBetween($minDuration, max($minDuration, $maxDuration));
                $validTo = Carbon::instance($currentTime)->addSeconds($eventDuration);
                
                // Ensure we don't go past current time
                if ($validTo->gt($now)) {
                    $validTo = $now->copy()->subMinutes(fake()->numberBetween(1, 60));
                }
            }
            
            $events[] = [
                'user_id' => $userId,
                'type' => $type,
                'valence' => $currentValence,
                'valid_from' => $currentTime,
                'valid_to' => $validTo,
                'created_at' => $currentTime,
                'updated_at' => $validTo ?? $now,
            ];
            
            // If this event is closed, prepare for next event
            if ($validTo && !$isLastEvent) {
                $currentTime = Carbon::instance($validTo)->addSecond();
                $currentValence = $currentValence === 'GOOD' ? 'BAD' : 'GOOD';
            }
        }
        
        return $events;
    }
}
