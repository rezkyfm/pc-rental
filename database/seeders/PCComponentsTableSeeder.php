<?php

namespace Database\Seeders;

use App\Models\Component;
use App\Models\PC;
use App\Models\PCComponent;
use Illuminate\Database\Seeder;

class PCComponentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all PCs
        $pcs = PC::all();

        // Components by category
        $cpus = Component::whereHas('category', function ($query) {
            $query->where('name', 'CPU');
        })->get();

        $gpus = Component::whereHas('category', function ($query) {
            $query->where('name', 'GPU');
        })->get();

        $rams = Component::whereHas('category', function ($query) {
            $query->where('name', 'RAM');
        })->get();

        $motherboards = Component::whereHas('category', function ($query) {
            $query->where('name', 'Motherboard');
        })->get();

        $storages = Component::whereHas('category', function ($query) {
            $query->where('name', 'Storage');
        })->get();

        $psus = Component::whereHas('category', function ($query) {
            $query->where('name', 'Power Supply');
        })->get();

        $cases = Component::whereHas('category', function ($query) {
            $query->where('name', 'Case');
        })->get();

        $monitors = Component::whereHas('category', function ($query) {
            $query->where('name', 'Monitor');
        })->get();

        $keyboards = Component::whereHas('category', function ($query) {
            $query->where('name', 'Keyboard');
        })->get();

        $mice = Component::whereHas('category', function ($query) {
            $query->where('name', 'Mouse');
        })->get();

        $headsets = Component::whereHas('category', function ($query) {
            $query->where('name', 'Headset');
        })->get();

        // Assign components to Gaming Beast
        $this->assignComponentsToPc($pcs[0], $cpus[0], $gpus[0], $rams[0], $motherboards[0], $storages[0], $psus[0], $cases[0], $monitors[0], $keyboards[0], $mice[0], $headsets[0]);

        // Assign components to Pro Gamer
        $this->assignComponentsToPc($pcs[1], $cpus[1], $gpus[1], $rams[1], $motherboards[1], $storages[1], $psus[0], $cases[1], $monitors[1], $keyboards[1], $mice[1], $headsets[1]);

        // Assign components to Streamer Setup
        $this->assignComponentsToPc($pcs[2], $cpus[0], $gpus[2], $rams[1], $motherboards[0], $storages[0], $psus[1], $cases[0], $monitors[2], $keyboards[2], $mice[2], $headsets[0]);

        // Assign components to Casual Gaming
        $this->assignComponentsToPc($pcs[3], $cpus[2], $gpus[4], $rams[3], $motherboards[2], $storages[2], $psus[1], $cases[1], $monitors[1], $keyboards[0], $mice[0], $headsets[2]);

        // Assign components to E-Sports Ready
        $this->assignComponentsToPc($pcs[4], $cpus[3], $gpus[3], $rams[2], $motherboards[1], $storages[1], $psus[0], $cases[0], $monitors[0], $keyboards[1], $mice[1], $headsets[1]);

        // Update component statuses
        Component::whereHas('pcs')->update(['status' => 'in_use']);

        // Update component statuses - gunakan cara alternatif
        $usedComponentIds = \DB::table('pc_components')->pluck('component_id')->unique();
        Component::whereIn('id', $usedComponentIds)->update(['status' => 'in_use']);
    }

    /**
     * Assign components to a PC.
     *
     * @param PC $pc
     * @param Component $cpu
     * @param Component $gpu
     * @param Component $ram
     * @param Component $motherboard
     * @param Component $storage
     * @param Component $psu
     * @param Component $case
     * @param Component $monitor
     * @param Component $keyboard
     * @param Component $mouse
     * @param Component $headset
     * @return void
     */
    private function assignComponentsToPc($pc, $cpu, $gpu, $ram, $motherboard, $storage, $psu, $case, $monitor, $keyboard, $mouse, $headset)
    {
        $components = [$cpu, $gpu, $ram, $motherboard, $storage, $psu, $case, $monitor, $keyboard, $mouse, $headset];
        $assemblyDate = $pc->assembly_date;

        foreach ($components as $component) {
            PCComponent::create([
                'pc_id' => $pc->id,
                'component_id' => $component->id,
                'installation_date' => $assemblyDate,
                'notes' => 'Installed during initial assembly',
            ]);
        }
    }
}