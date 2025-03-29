<?php

namespace Database\Seeders;

use App\Models\Component;
use App\Models\ComponentCategory;
use Illuminate\Database\Seeder;

class ComponentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CPUs
        $cpuCategory = ComponentCategory::where('name', 'CPU')->first();
        $cpus = [
            [
                'name' => 'Intel Core i9-13900K',
                'brand' => 'Intel',
                'model' => 'Core i9-13900K',
                'specifications' => 'P-cores: 3.0 GHz (Base), 5.8 GHz (Boost); E-cores: 2.2 GHz (Base), 4.3 GHz (Boost); 24C/32T; 36MB Cache',
                'purchase_price' => 599.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'INTL' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'AMD Ryzen 9 7950X',
                'brand' => 'AMD',
                'model' => 'Ryzen 9 7950X',
                'specifications' => '4.5 GHz (Base), 5.7 GHz (Boost); 16C/32T; 80MB Cache',
                'purchase_price' => 699.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'AMD' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Intel Core i7-13700K',
                'brand' => 'Intel',
                'model' => 'Core i7-13700K',
                'specifications' => 'P-cores: 3.4 GHz (Base), 5.4 GHz (Boost); E-cores: 2.5 GHz (Base), 4.2 GHz (Boost); 16C/24T; 30MB Cache',
                'purchase_price' => 419.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'INTL' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'AMD Ryzen 7 7800X3D',
                'brand' => 'AMD',
                'model' => 'Ryzen 7 7800X3D',
                'specifications' => '4.2 GHz (Base), 5.0 GHz (Boost); 8C/16T; 96MB Cache',
                'purchase_price' => 449.99,
                'purchase_date' => now()->subMonths(1),
                'serial_number' => 'AMD' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Intel Core i5-13600K',
                'brand' => 'Intel',
                'model' => 'Core i5-13600K',
                'specifications' => 'P-cores: 3.5 GHz (Base), 5.1 GHz (Boost); E-cores: 2.6 GHz (Base), 3.9 GHz (Boost); 14C/20T; 24MB Cache',
                'purchase_price' => 329.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'INTL' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($cpus as $cpu) {
            Component::create(array_merge($cpu, ['category_id' => $cpuCategory->id]));
        }

        // GPUs
        $gpuCategory = ComponentCategory::where('name', 'GPU')->first();
        $gpus = [
            [
                'name' => 'NVIDIA GeForce RTX 4090',
                'brand' => 'NVIDIA',
                'model' => 'GeForce RTX 4090',
                'specifications' => '24GB GDDR6X, 16384 CUDA Cores, 2.52 GHz Boost',
                'purchase_price' => 1599.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'NVID' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'AMD Radeon RX 7900 XTX',
                'brand' => 'AMD',
                'model' => 'Radeon RX 7900 XTX',
                'specifications' => '24GB GDDR6, 12288 Stream Processors, 2.5 GHz Game Clock',
                'purchase_price' => 999.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'AMDR' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'NVIDIA GeForce RTX 4080',
                'brand' => 'NVIDIA',
                'model' => 'GeForce RTX 4080',
                'specifications' => '16GB GDDR6X, 9728 CUDA Cores, 2.51 GHz Boost',
                'purchase_price' => 1199.99,
                'purchase_date' => now()->subMonths(1),
                'serial_number' => 'NVID' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'AMD Radeon RX 7800 XT',
                'brand' => 'AMD',
                'model' => 'Radeon RX 7800 XT',
                'specifications' => '16GB GDDR6, 3840 Stream Processors, 2.4 GHz Game Clock',
                'purchase_price' => 599.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'AMDR' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'NVIDIA GeForce RTX 4070',
                'brand' => 'NVIDIA',
                'model' => 'GeForce RTX 4070',
                'specifications' => '12GB GDDR6X, 5888 CUDA Cores, 2.48 GHz Boost',
                'purchase_price' => 599.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'NVID' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($gpus as $gpu) {
            Component::create(array_merge($gpu, ['category_id' => $gpuCategory->id]));
        }

        // RAMs
        $ramCategory = ComponentCategory::where('name', 'RAM')->first();
        $rams = [
            [
                'name' => 'Corsair Vengeance 32GB DDR5',
                'brand' => 'Corsair',
                'model' => 'Vengeance DDR5',
                'specifications' => '32GB (2x16GB) DDR5-6000 CL36',
                'purchase_price' => 159.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'CORS' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'G.Skill Trident Z5 32GB DDR5',
                'brand' => 'G.Skill',
                'model' => 'Trident Z5 RGB',
                'specifications' => '32GB (2x16GB) DDR5-6400 CL32',
                'purchase_price' => 189.99,
                'purchase_date' => now()->subMonths(1),
                'serial_number' => 'GSKL' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Kingston Fury Beast 32GB DDR5',
                'brand' => 'Kingston',
                'model' => 'Fury Beast',
                'specifications' => '32GB (2x16GB) DDR5-5200 CL40',
                'purchase_price' => 139.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'KING' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Corsair Vengeance RGB Pro 32GB DDR4',
                'brand' => 'Corsair',
                'model' => 'Vengeance RGB Pro',
                'specifications' => '32GB (2x16GB) DDR4-3600 CL18',
                'purchase_price' => 119.99,
                'purchase_date' => now()->subMonths(6),
                'serial_number' => 'CORS' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'G.Skill Ripjaws V 32GB DDR4',
                'brand' => 'G.Skill',
                'model' => 'Ripjaws V',
                'specifications' => '32GB (2x16GB) DDR4-3200 CL16',
                'purchase_price' => 109.99,
                'purchase_date' => now()->subMonths(7),
                'serial_number' => 'GSKL' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($rams as $ram) {
            Component::create(array_merge($ram, ['category_id' => $ramCategory->id]));
        }

        // Add more components for other categories
        $this->seedOtherComponents();
    }

    /**
     * Seed other components (motherboards, storage, etc).
     */
    private function seedOtherComponents(): void
    {
        // Motherboards
        $motherboardCategory = ComponentCategory::where('name', 'Motherboard')->first();
        $motherboards = [
            [
                'name' => 'ASUS ROG Maximus Z790 Hero',
                'brand' => 'ASUS',
                'model' => 'ROG Maximus Z790 Hero',
                'specifications' => 'Intel Z790 Chipset, ATX, DDR5, PCIe 5.0',
                'purchase_price' => 629.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'ASUS' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'MSI MPG X670E Carbon WiFi',
                'brand' => 'MSI',
                'model' => 'MPG X670E Carbon WiFi',
                'specifications' => 'AMD X670E Chipset, ATX, DDR5, PCIe 5.0',
                'purchase_price' => 479.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'MSI' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Gigabyte Z790 Aorus Elite AX',
                'brand' => 'Gigabyte',
                'model' => 'Z790 Aorus Elite AX',
                'specifications' => 'Intel Z790 Chipset, ATX, DDR5, PCIe 5.0',
                'purchase_price' => 329.99,
                'purchase_date' => now()->subMonths(1),
                'serial_number' => 'GIGA' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($motherboards as $motherboard) {
            Component::create(array_merge($motherboard, ['category_id' => $motherboardCategory->id]));
        }

        // Storage
        $storageCategory = ComponentCategory::where('name', 'Storage')->first();
        $storages = [
            [
                'name' => 'Samsung 990 Pro 2TB',
                'brand' => 'Samsung',
                'model' => '990 Pro',
                'specifications' => '2TB NVMe PCIe 4.0, 7450MB/s Read, 6900MB/s Write',
                'purchase_price' => 249.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'SAMS' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'WD Black SN850X 1TB',
                'brand' => 'Western Digital',
                'model' => 'Black SN850X',
                'specifications' => '1TB NVMe PCIe 4.0, 7300MB/s Read, 6300MB/s Write',
                'purchase_price' => 169.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'WD' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Samsung 870 EVO 1TB',
                'brand' => 'Samsung',
                'model' => '870 EVO',
                'specifications' => '1TB SATA SSD, 560MB/s Read, 530MB/s Write',
                'purchase_price' => 99.99,
                'purchase_date' => now()->subMonths(5),
                'serial_number' => 'SAMS' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($storages as $storage) {
            Component::create(array_merge($storage, ['category_id' => $storageCategory->id]));
        }

        // Power Supplies
        $psuCategory = ComponentCategory::where('name', 'Power Supply')->first();
        $psus = [
            [
                'name' => 'Corsair RM1000x',
                'brand' => 'Corsair',
                'model' => 'RM1000x',
                'specifications' => '1000W, 80 Plus Gold, Fully Modular',
                'purchase_price' => 199.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'CORS' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'EVGA SuperNOVA 850 T2',
                'brand' => 'EVGA',
                'model' => 'SuperNOVA 850 T2',
                'specifications' => '850W, 80 Plus Titanium, Fully Modular',
                'purchase_price' => 219.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'EVGA' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($psus as $psu) {
            Component::create(array_merge($psu, ['category_id' => $psuCategory->id]));
        }

        // Cases
        $caseCategory = ComponentCategory::where('name', 'Case')->first();
        $cases = [
            [
                'name' => 'Lian Li O11 Dynamic EVO',
                'brand' => 'Lian Li',
                'model' => 'O11 Dynamic EVO',
                'specifications' => 'Mid Tower, Tempered Glass, E-ATX Support',
                'purchase_price' => 169.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'LIAN' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Fractal Design Meshify 2',
                'brand' => 'Fractal Design',
                'model' => 'Meshify 2',
                'specifications' => 'Mid Tower, Mesh Front Panel, E-ATX Support',
                'purchase_price' => 159.99,
                'purchase_date' => now()->subMonths(4),
                'serial_number' => 'FRAC' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($cases as $case) {
            Component::create(array_merge($case, ['category_id' => $caseCategory->id]));
        }

        // Monitors
        $monitorCategory = ComponentCategory::where('name', 'Monitor')->first();
        $monitors = [
            [
                'name' => 'LG 27GP950-B',
                'brand' => 'LG',
                'model' => '27GP950-B',
                'specifications' => '27", 4K UHD, 144Hz, 1ms, IPS, HDR600',
                'purchase_price' => 799.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'LG' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Samsung Odyssey G7',
                'brand' => 'Samsung',
                'model' => 'Odyssey G7',
                'specifications' => '32", WQHD, 240Hz, 1ms, VA, HDR600, Curved',
                'purchase_price' => 699.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'SAMS' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'ASUS ROG Swift PG279QM',
                'brand' => 'ASUS',
                'model' => 'ROG Swift PG279QM',
                'specifications' => '27", WQHD, 240Hz, 1ms, IPS, G-Sync',
                'purchase_price' => 749.99,
                'purchase_date' => now()->subMonths(1),
                'serial_number' => 'ASUS' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($monitors as $monitor) {
            Component::create(array_merge($monitor, ['category_id' => $monitorCategory->id]));
        }

        // Peripherals (Keyboard, Mouse, Headset)
        $this->seedPeripherals();
    }

    /**
     * Seed peripherals components.
     */
    private function seedPeripherals(): void
    {
        // Keyboards
        $keyboardCategory = ComponentCategory::where('name', 'Keyboard')->first();
        $keyboards = [
            [
                'name' => 'Logitech G Pro X',
                'brand' => 'Logitech',
                'model' => 'G Pro X',
                'specifications' => 'Mechanical, Hot-swappable Switches, RGB',
                'purchase_price' => 149.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'LOGI' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Razer Huntsman V2',
                'brand' => 'Razer',
                'model' => 'Huntsman V2',
                'specifications' => 'Optical Switches, RGB, Wrist Rest',
                'purchase_price' => 199.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'RAZR' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Corsair K100 RGB',
                'brand' => 'Corsair',
                'model' => 'K100 RGB',
                'specifications' => 'Optical-Mechanical Switches, RGB, Media Controls',
                'purchase_price' => 229.99,
                'purchase_date' => now()->subMonths(1),
                'serial_number' => 'CORS' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($keyboards as $keyboard) {
            Component::create(array_merge($keyboard, ['category_id' => $keyboardCategory->id]));
        }

        // Mice
        $mouseCategory = ComponentCategory::where('name', 'Mouse')->first();
        $mice = [
            [
                'name' => 'Logitech G Pro X Superlight',
                'brand' => 'Logitech',
                'model' => 'G Pro X Superlight',
                'specifications' => 'Wireless, 25K DPI, 63g Weight',
                'purchase_price' => 149.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'LOGI' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Razer Viper V2 Pro',
                'brand' => 'Razer',
                'model' => 'Viper V2 Pro',
                'specifications' => 'Wireless, 30K DPI, 58g Weight',
                'purchase_price' => 149.99,
                'purchase_date' => now()->subMonths(1),
                'serial_number' => 'RAZR' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'SteelSeries Aerox 5 Wireless',
                'brand' => 'SteelSeries',
                'model' => 'Aerox 5 Wireless',
                'specifications' => 'Wireless, 18K DPI, 74g Weight, 9 Buttons',
                'purchase_price' => 139.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'STEL' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($mice as $mouse) {
            Component::create(array_merge($mouse, ['category_id' => $mouseCategory->id]));
        }

        // Headsets
        $headsetCategory = ComponentCategory::where('name', 'Headset')->first();
        $headsets = [
            [
                'name' => 'SteelSeries Arctis Nova Pro Wireless',
                'brand' => 'SteelSeries',
                'model' => 'Arctis Nova Pro Wireless',
                'specifications' => 'Wireless, Active Noise Cancellation, Hot-swappable Battery',
                'purchase_price' => 349.99,
                'purchase_date' => now()->subMonths(2),
                'serial_number' => 'STEL' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'Logitech G Pro X 2 Lightspeed',
                'brand' => 'Logitech',
                'model' => 'G Pro X 2 Lightspeed',
                'specifications' => 'Wireless, 50mm Drivers, Blue VO!CE Microphone',
                'purchase_price' => 249.99,
                'purchase_date' => now()->subMonths(1),
                'serial_number' => 'LOGI' . rand(100000, 999999),
                'status' => 'available',
            ],
            [
                'name' => 'HyperX Cloud Alpha Wireless',
                'brand' => 'HyperX',
                'model' => 'Cloud Alpha Wireless',
                'specifications' => 'Wireless, 50mm Drivers, 300h Battery Life',
                'purchase_price' => 199.99,
                'purchase_date' => now()->subMonths(3),
                'serial_number' => 'HYPR' . rand(100000, 999999),
                'status' => 'available',
            ],
        ];

        foreach ($headsets as $headset) {
            Component::create(array_merge($headset, ['category_id' => $headsetCategory->id]));
        }
    }
}