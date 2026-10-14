<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database (Slide 30).
     */
    public function run(): void
    {
        // 1. Akun Pengguna (RBAC: Admin & Staff)
        $admin = User::firstOrCreate(
            ['email' => 'admin@neurovault.net'],
            [
                'name' => 'Dr. Victor Vector (Master Ripperdoc)',
                'username' => 'admin',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@neurovault.net'],
            [
                'name' => 'David Martinez (Vault Technician)',
                'username' => 'staff',
                'role' => 'staff',
                'password' => Hash::make('staff123'),
            ]
        );

        // 2. Kategori Produk
        $categoriesData = [
            ['name' => 'Neural Implants', 'description' => 'Implan pengendali saraf, cyberdeck, dan neural operating systems.'],
            ['name' => 'Combat Cyberware', 'description' => 'Augmentasi tempur, senjata biometrik, dan persenjataan sub-dermal.'],
            ['name' => 'Bioware & Synthetics', 'description' => 'Organ buatan, modifikasi kerangka tulang, dan pelindung jaringan.'],
            ['name' => 'Bio-Nanites & Stimulants', 'description' => 'Stimulan medis trauma, penyembuh nanit seluler, dan pemicu adrenalin.'],
            ['name' => 'Sensory Augmentations', 'description' => 'Optik cibernetik, pendengaran audio-spasial, dan sensor frekuensi.'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[] = Category::create($c);
        }

        // 3. Suppliers
        $suppliersData = [
            ['name' => 'Arasaka Cybernetics Corp', 'contact_person' => 'Hanako Sasaki', 'phone' => '+81-3-5555-0199', 'email' => 'sales@arasaka.corp.jp', 'address' => 'Arasaka Waterfront Tower, Chuo District'],
            ['name' => 'Militech Defense Systems', 'contact_person' => 'Meredith Stout', 'phone' => '+1-202-555-0144', 'email' => 'defense@militech.us', 'address' => 'Militech HQ Complex, North Industrial'],
            ['name' => 'Kang Tao Smart Tech', 'contact_person' => 'Zhen Kang', 'phone' => '+86-21-555-0177', 'email' => 'inquiry@kangtao.cn', 'address' => 'Kang Tao Tech Park, Pudong Metro'],
            ['name' => 'Night City Ripper Syndicate', 'contact_person' => 'Fingers MD', 'phone' => '+62-819-0011-2233', 'email' => 'rippers@nc-syndicate.net', 'address' => 'Jig-Jig Street Basement 4B, Night City'],
            ['name' => 'Biotechnica Bio-Genetics', 'contact_person' => 'Dr. Joanne Koch', 'phone' => '+39-06-555-0188', 'email' => 'contact@biotechnica.it', 'address' => 'Biotechnica Protein Flats, South Coast'],
        ];

        $suppliers = [];
        foreach ($suppliersData as $s) {
            $suppliers[] = Supplier::create($s);
        }

        // 4. Products
        $productsData = [
            ['category_id' => $categories[0]->id, 'sku' => 'NV-NEU-SND04', 'name' => 'Sandevistan Mk.4 Neural OS', 'description' => 'Perangkat modulasi saraf kecepatan tinggi dengan latensi 0.05ms.', 'price' => 28500000.00, 'stock' => 8, 'minimum_stock' => 5],
            ['category_id' => $categories[0]->id, 'sku' => 'NV-NEU-DK01', 'name' => 'Militech Paraline Cyberdeck', 'description' => 'Cyberdeck portabel untuk penetrasi node jaringan lokal.', 'price' => 14500000.00, 'stock' => 3, 'minimum_stock' => 5], // LOW STOCK
            ['category_id' => $categories[1]->id, 'sku' => 'NV-CMB-MNT01', 'name' => 'Mantis Blades Titanium Edge', 'description' => 'Bilah lengan lipat paduan titanium dengan pisau termal.', 'price' => 35000000.00, 'stock' => 2, 'minimum_stock' => 4], // LOW STOCK
            ['category_id' => $categories[1]->id, 'sku' => 'NV-CMB-GOR02', 'name' => 'Gorilla Arms Hydraulic Power', 'description' => 'Augmentasi lengan hidrolik dengan daya angkat hingga 5 ton.', 'price' => 32000000.00, 'stock' => 6, 'minimum_stock' => 3],
            ['category_id' => $categories[2]->id, 'sku' => 'NV-BIO-LNG01', 'name' => 'Synthetic Lungs Aerobic-X', 'description' => 'Paru-paru sintetis tahan toksin gas industri dan polusi ekstrim.', 'price' => 18000000.00, 'stock' => 10, 'minimum_stock' => 5],
            ['category_id' => $categories[2]->id, 'sku' => 'NV-BIO-ARM01', 'name' => 'Subdermal Armor Polycarbonate', 'description' => 'Anyaman serat sub-dermal penahan proyektil kaliber ringan.', 'price' => 22000000.00, 'stock' => 12, 'minimum_stock' => 6],
            ['category_id' => $categories[3]->id, 'sku' => 'NV-STM-DOC03', 'name' => 'MaxDoc Mk.3 Trauma Inhaler', 'description' => 'Inhaler nanit penyembuh luka trauma mayor dalam 15 detik.', 'price' => 1500000.00, 'stock' => 45, 'minimum_stock' => 10],
            ['category_id' => $categories[3]->id, 'sku' => 'NV-STM-BB01', 'name' => 'Bounce_Back Booster Pack', 'description' => 'Serum pemicu regenerasi seluler berkelanjutan.', 'price' => 2200000.00, 'stock' => 4, 'minimum_stock' => 10], // LOW STOCK
            ['category_id' => $categories[4]->id, 'sku' => 'NV-SEN-KIR03', 'name' => 'Kiroshi Optics Gen 3 Vision', 'description' => 'Mata sibernetik optik dengan zoom 8x dan pemindai ancaman.', 'price' => 9200000.00, 'stock' => 15, 'minimum_stock' => 5],
        ];

        $products = [];
        foreach ($productsData as $p) {
            $products[] = Product::create($p);
        }

        // 5. Relasi Many-to-Many Produk & Supplier
        $products[0]->suppliers()->attach([$suppliers[0]->id => ['supplier_price' => 24000000], $suppliers[3]->id => ['supplier_price' => 25500000]]);
        $products[1]->suppliers()->attach([$suppliers[1]->id => ['supplier_price' => 12000000]]);
        $products[2]->suppliers()->attach([$suppliers[0]->id => ['supplier_price' => 29000000], $suppliers[3]->id => ['supplier_price' => 31000000]]);
        $products[3]->suppliers()->attach([$suppliers[1]->id => ['supplier_price' => 27000000]]);
        $products[4]->suppliers()->attach([$suppliers[4]->id => ['supplier_price' => 14500000]]);
        $products[5]->suppliers()->attach([$suppliers[1]->id => ['supplier_price' => 18000000], $suppliers[4]->id => ['supplier_price' => 19000000]]);
        $products[6]->suppliers()->attach([$suppliers[3]->id => ['supplier_price' => 1100000], $suppliers[4]->id => ['supplier_price' => 1000000]]);
        $products[7]->suppliers()->attach([$suppliers[4]->id => ['supplier_price' => 1600000]]);
        $products[8]->suppliers()->attach([$suppliers[2]->id => ['supplier_price' => 7500000], $suppliers[3]->id => ['supplier_price' => 8000000]]);

        // 6. Initial Stock Movements
        StockMovement::create(['product_id' => $products[0]->id, 'user_id' => $admin->id, 'type' => 'IN', 'quantity' => 10, 'reference_no' => 'PO-2026-001', 'notes' => 'Pasokan perdana Sandevistan']);
        StockMovement::create(['product_id' => $products[0]->id, 'user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 2, 'reference_no' => 'SO-2026-001', 'notes' => 'Pemasangan implan klien solo']);

        StockMovement::create(['product_id' => $products[1]->id, 'user_id' => $admin->id, 'type' => 'IN', 'quantity' => 5, 'reference_no' => 'PO-2026-002', 'notes' => 'Restock cyberdeck Militech']);
        StockMovement::create(['product_id' => $products[1]->id, 'user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 2, 'reference_no' => 'SO-2026-002', 'notes' => 'Pengambilan unit oleh netrunner']);

        StockMovement::create(['product_id' => $products[2]->id, 'user_id' => $admin->id, 'type' => 'IN', 'quantity' => 5, 'reference_no' => 'PO-2026-003', 'notes' => 'Pasokan Mantis Blades']);
        StockMovement::create(['product_id' => $products[2]->id, 'user_id' => $staff->id, 'type' => 'OUT', 'quantity' => 3, 'reference_no' => 'SO-2026-003', 'notes' => 'Operasi augmentasi solo merc']);
    }
}
