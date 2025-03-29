<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use App\Models\PC;
use App\Models\User;
use Illuminate\Database\Seeder;

class MaintenanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all PCs
        $pcs = PC::all();

        // Get operator
        $operator = User::where('role', 'operator')->first();

        // Previous completed maintenance
        foreach ($pcs as $pc) {
            // Add 1-2 completed maintenance records per PC
            $count = rand(1, 2);

            for ($i = 0; $i < $count; $i++) {
                $scheduledDate = now()->subMonths(rand(1, 6))->subDays(rand(1, 15));
                $completedDate = (clone $scheduledDate)->addHours(rand(1, 8));

                Maintenance::create([
                    'pc_id' => $pc->id,
                    'title' => $this->getRandomMaintenanceTitle(),
                    'description' => $this->getRandomMaintenanceDescription(),
                    'type' => $this->getRandomMaintenanceType(),
                    'scheduled_date' => $scheduledDate,
                    'completed_date' => $completedDate,
                    'status' => 'completed',
                    'cost' => rand(50, 200),
                    'performed_by' => $operator->id,
                ]);
            }
        }

        // Future scheduled maintenance
        foreach ($pcs->random(3) as $pc) {
            $scheduledDate = now()->addDays(rand(1, 14));

            Maintenance::create([
                'pc_id' => $pc->id,
                'title' => $this->getRandomMaintenanceTitle(),
                'description' => $this->getRandomMaintenanceDescription(),
                'type' => 'routine',
                'scheduled_date' => $scheduledDate,
                'completed_date' => null,
                'status' => 'scheduled',
                'cost' => null,
                'performed_by' => $operator->id,
            ]);
        }
    }

    /**
     * Get random maintenance title.
     *
     * @return string
     */
    private function getRandomMaintenanceTitle(): string
    {
        $titles = [
            'Regular Cleaning',
            'System Update',
            'Hardware Checkup',
            'Driver Update',
            'Thermal Paste Replacement',
            'Fan Cleaning',
            'Software Optimization',
            'Game Library Update',
            'Component Testing',
            'Benchmark Testing'
        ];

        return $titles[array_rand($titles)];
    }

    /**
     * Get random maintenance description.
     *
     * @return string
     */
    private function getRandomMaintenanceDescription(): string
    {
        $descriptions = [
            'Regular dust cleaning and system check',
            'Update all drivers and system software',
            'Full hardware diagnostic and testing',
            'GPU driver update and optimization',
            'CPU thermal paste replacement and heatsink cleaning',
            'Clean all case and component fans',
            'Optimize system for better performance',
            'Update all game libraries and launchers',
            'Test all components for optimal performance',
            'Run benchmarks to ensure system meets specifications'
        ];

        return $descriptions[array_rand($descriptions)];
    }

    /**
     * Get random maintenance type.
     *
     * @return string
     */
    private function getRandomMaintenanceType(): string
    {
        $types = ['routine', 'repair', 'upgrade', 'other'];
        $weights = [70, 15, 10, 5]; // Weighted probabilities

        return $this->getWeightedRandom($types, $weights);
    }

    /**
     * Get weighted random item from array.
     *
     * @param array $items
     * @param array $weights
     * @return mixed
     */
    private function getWeightedRandom(array $items, array $weights)
    {
        $totalWeight = array_sum($weights);
        $rand = mt_rand(1, $totalWeight);

        $current = 0;
        foreach ($items as $index => $item) {
            $current += $weights[$index];
            if ($rand <= $current) {
                return $item;
            }
        }

        return $items[0]; // Fallback
    }
}