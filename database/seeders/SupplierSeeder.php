<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Fresh Farm Foods',
                'email' => 'orders@freshfarmfoods.com',
                'phone' => '(555) 123-4567',
                'address' => '123 Farm Road, Valley City, CA 95432',
                'contact_person' => 'John Smith',
                'notes' => 'Organic produce supplier, delivers Tuesdays and Fridays',
                'is_active' => true,
            ],
            [
                'name' => 'Premium Meat Co.',
                'email' => 'sales@premiummeat.com',
                'phone' => '(555) 987-6543',
                'address' => '456 Industrial Blvd, Meat City, CA 95433',
                'contact_person' => 'Sarah Johnson',
                'notes' => 'High-quality meats, requires 24-hour notice for large orders',
                'is_active' => true,
            ],
            [
                'name' => 'Ocean Fresh Seafood',
                'email' => 'info@oceanfresh.com',
                'phone' => '(555) 456-7890',
                'address' => '789 Harbor St, Coastal Town, CA 95434',
                'contact_person' => 'Mike Wilson',
                'notes' => 'Daily fresh catch, best prices on bulk orders',
                'is_active' => true,
            ],
            [
                'name' => 'Local Dairy Farm',
                'email' => 'contact@localdairy.com',
                'phone' => '(555) 321-0987',
                'address' => '321 Pasture Lane, Dairy Valley, CA 95435',
                'contact_person' => 'Lisa Brown',
                'notes' => 'Local organic dairy products, weekend deliveries available',
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
