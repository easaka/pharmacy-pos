<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories if they don't exist
        $categories = [
            ['name' => 'Analgesics', 'description' => 'Pain relief medications'],
            ['name' => 'Antibiotics', 'description' => 'Antibacterial medications'],
            ['name' => 'Antipyretics', 'description' => 'Fever reducing medications'],
            ['name' => 'Antacids', 'description' => 'Digestive system medications'],
            ['name' => 'Vitamins & Supplements', 'description' => 'Nutritional supplements'],
            ['name' => 'Cold & Flu', 'description' => 'Cold and flu medications'],
            ['name' => 'Skincare', 'description' => 'Skin care products'],
            ['name' => 'First Aid', 'description' => 'First aid supplies'],
            ['name' => 'Baby Care', 'description' => 'Baby care products'],
            ['name' => 'Personal Care', 'description' => 'Personal hygiene products'],
            ['name' => 'Herbal Products', 'description' => 'Natural herbal remedies'],
            ['name' => 'Diabetic Care', 'description' => 'Diabetes management products'],
        ];

        $categoryIds = [];
        foreach ($categories as $catData) {
            $category = Category::firstOrCreate(
                ['name' => $catData['name']],
                ['description' => $catData['description']]
            );
            $categoryIds[] = $category->id;
        }

        // Create suppliers if they don't exist
        $suppliers = [
            ['name' => 'Pharma Supplier Ltd', 'phone' => '0241234567', 'email' => 'info@pharmasupplier.com', 'address' => 'Accra, Ghana'],
            ['name' => 'MediCorp Distributors', 'phone' => '0242345678', 'email' => 'sales@medicorp.com', 'address' => 'Kumasi, Ghana'],
            ['name' => 'HealthCare Supplies Inc', 'phone' => '0243456789', 'email' => 'orders@healthcare.com', 'address' => 'Tamale, Ghana'],
            ['name' => 'Pharmacy Wholesale Co', 'phone' => '0244567890', 'email' => 'contact@wholesale.com', 'address' => 'Takoradi, Ghana'],
            ['name' => 'Global Medical Solutions', 'phone' => '0245678901', 'email' => 'info@globalmed.com', 'address' => 'Cape Coast, Ghana'],
        ];

        $supplierIds = [];
        foreach ($suppliers as $supData) {
            $supplier = Supplier::firstOrCreate(
                ['name' => $supData['name']],
                [
                    'phone' => $supData['phone'],
                    'email' => $supData['email'],
                    'address' => $supData['address'],
                ]
            );
            $supplierIds[] = $supplier->id;
        }

        // Sample products with realistic data
        $products = [
            // Analgesics
            ['sku' => 'PARA-500', 'name' => 'Paracetamol 500mg Tablets', 'category' => 'Analgesics', 'cost' => 5.00, 'price' => 8.50, 'reorder' => 50, 'description' => 'Pain relief and fever reducer'],
            ['sku' => 'IBU-400', 'name' => 'Ibuprofen 400mg Tablets', 'category' => 'Analgesics', 'cost' => 6.50, 'price' => 10.00, 'reorder' => 40, 'description' => 'Anti-inflammatory pain relief'],
            ['sku' => 'ASP-100', 'name' => 'Aspirin 100mg Tablets', 'category' => 'Analgesics', 'cost' => 4.00, 'price' => 7.00, 'reorder' => 60, 'description' => 'Pain relief and blood thinner'],
            ['sku' => 'DICLO-50', 'name' => 'Diclofenac 50mg Tablets', 'category' => 'Analgesics', 'cost' => 7.50, 'price' => 12.00, 'reorder' => 35, 'description' => 'Muscle pain and inflammation relief'],
            ['sku' => 'TRAM-50', 'name' => 'Tramadol 50mg Capsules', 'category' => 'Analgesics', 'cost' => 12.00, 'price' => 18.00, 'reorder' => 25, 'description' => 'Strong pain relief medication'],
            
            // Antibiotics
            ['sku' => 'AMOX-250', 'name' => 'Amoxicillin 250mg Capsules', 'category' => 'Antibiotics', 'cost' => 8.50, 'price' => 15.00, 'reorder' => 30, 'description' => 'Broad spectrum antibiotic'],
            ['sku' => 'AMOX-500', 'name' => 'Amoxicillin 500mg Capsules', 'category' => 'Antibiotics', 'cost' => 12.00, 'price' => 20.00, 'reorder' => 30, 'description' => 'Higher strength antibiotic'],
            ['sku' => 'AZIT-500', 'name' => 'Azithromycin 500mg Tablets', 'category' => 'Antibiotics', 'cost' => 15.00, 'price' => 25.00, 'reorder' => 20, 'description' => 'Macrolide antibiotic'],
            ['sku' => 'CEF-250', 'name' => 'Cefalexin 250mg Capsules', 'category' => 'Antibiotics', 'cost' => 10.00, 'price' => 17.00, 'reorder' => 25, 'description' => 'Cephalosporin antibiotic'],
            ['sku' => 'METRO-400', 'name' => 'Metronidazole 400mg Tablets', 'category' => 'Antibiotics', 'cost' => 9.00, 'price' => 16.00, 'reorder' => 28, 'description' => 'Antibiotic for infections'],
            
            // Antipyretics
            ['sku' => 'ACET-500', 'name' => 'Acetaminophen 500mg Tablets', 'category' => 'Antipyretics', 'cost' => 4.50, 'price' => 8.00, 'reorder' => 55, 'description' => 'Fever reducer and pain relief'],
            ['sku' => 'MEFF-250', 'name' => 'Mefenamic Acid 250mg Capsules', 'category' => 'Antipyretics', 'cost' => 6.00, 'price' => 11.00, 'reorder' => 45, 'description' => 'Pain and fever relief'],
            
            // Antacids
            ['sku' => 'OMEP-20', 'name' => 'Omeprazole 20mg Capsules', 'category' => 'Antacids', 'cost' => 11.00, 'price' => 18.00, 'reorder' => 40, 'description' => 'Stomach acid reducer'],
            ['sku' => 'RANTI-150', 'name' => 'Ranitidine 150mg Tablets', 'category' => 'Antacids', 'cost' => 7.50, 'price' => 13.00, 'reorder' => 35, 'description' => 'Acid reflux relief'],
            ['sku' => 'ANTAC-500', 'name' => 'Antacid Suspension 500ml', 'category' => 'Antacids', 'cost' => 8.00, 'price' => 14.00, 'reorder' => 25, 'description' => 'Liquid antacid'],
            
            // Vitamins & Supplements
            ['sku' => 'VIT-C-1000', 'name' => 'Vitamin C 1000mg Tablets', 'category' => 'Vitamins & Supplements', 'cost' => 15.00, 'price' => 25.00, 'reorder' => 50, 'description' => 'Immune system booster'],
            ['sku' => 'VIT-D-1000', 'name' => 'Vitamin D3 1000IU Capsules', 'category' => 'Vitamins & Supplements', 'cost' => 18.00, 'price' => 30.00, 'reorder' => 45, 'description' => 'Bone health supplement'],
            ['sku' => 'MULTI-VIT', 'name' => 'Multivitamin Tablets', 'category' => 'Vitamins & Supplements', 'cost' => 20.00, 'price' => 35.00, 'reorder' => 40, 'description' => 'Complete vitamin supplement'],
            ['sku' => 'IRON-65', 'name' => 'Iron 65mg Tablets', 'category' => 'Vitamins & Supplements', 'cost' => 12.00, 'price' => 20.00, 'reorder' => 35, 'description' => 'Iron deficiency supplement'],
            ['sku' => 'CALC-500', 'name' => 'Calcium 500mg + D3 Tablets', 'category' => 'Vitamins & Supplements', 'cost' => 16.00, 'price' => 28.00, 'reorder' => 38, 'description' => 'Bone strengthening supplement'],
            
            // Cold & Flu
            ['sku' => 'COUGH-SYRUP', 'name' => 'Cough Syrup 100ml', 'category' => 'Cold & Flu', 'cost' => 10.00, 'price' => 18.00, 'reorder' => 30, 'description' => 'Cough relief syrup'],
            ['sku' => 'COLD-TABS', 'name' => 'Cold & Flu Relief Tablets', 'category' => 'Cold & Flu', 'cost' => 8.50, 'price' => 15.00, 'reorder' => 45, 'description' => 'Multi-symptom cold relief'],
            ['sku' => 'NASAL-SPRAY', 'name' => 'Nasal Decongestant Spray', 'category' => 'Cold & Flu', 'cost' => 7.00, 'price' => 12.00, 'reorder' => 40, 'description' => 'Nasal congestion relief'],
            ['sku' => 'VAPOR-RUB', 'name' => 'Vapor Rub 50g', 'category' => 'Cold & Flu', 'cost' => 6.50, 'price' => 11.00, 'reorder' => 50, 'description' => 'Chest rub for cold relief'],
            
            // Skincare
            ['sku' => 'MOISTURIZER', 'name' => 'Facial Moisturizer 50ml', 'category' => 'Skincare', 'cost' => 25.00, 'price' => 45.00, 'reorder' => 30, 'description' => 'Hydrating facial cream'],
            ['sku' => 'SUNSCREEN-30', 'name' => 'Sunscreen SPF 30 100ml', 'category' => 'Skincare', 'cost' => 30.00, 'price' => 55.00, 'reorder' => 25, 'description' => 'UV protection lotion'],
            ['sku' => 'ANTI-ACNE', 'name' => 'Anti-Acne Cream 30g', 'category' => 'Skincare', 'cost' => 22.00, 'price' => 40.00, 'reorder' => 35, 'description' => 'Acne treatment cream'],
            ['sku' => 'LIP-BALM', 'name' => 'Lip Balm 4.5g', 'category' => 'Skincare', 'cost' => 5.00, 'price' => 9.00, 'reorder' => 60, 'description' => 'Moisturizing lip balm'],
            
            // First Aid
            ['sku' => 'BAND-AID', 'name' => 'Band-Aid Strips (Box of 50)', 'category' => 'First Aid', 'cost' => 12.00, 'price' => 22.00, 'reorder' => 20, 'description' => 'Adhesive bandages'],
            ['sku' => 'ANTISEPTIC', 'name' => 'Antiseptic Solution 100ml', 'category' => 'First Aid', 'cost' => 8.00, 'price' => 15.00, 'reorder' => 30, 'description' => 'Wound cleaning solution'],
            ['sku' => 'GAUZE-PAD', 'name' => 'Gauze Pads 10cm x 10cm (Box of 20)', 'category' => 'First Aid', 'cost' => 15.00, 'price' => 28.00, 'reorder' => 25, 'description' => 'Sterile gauze pads'],
            ['sku' => 'MEDICAL-TAPE', 'name' => 'Medical Tape 2.5cm x 10m', 'category' => 'First Aid', 'cost' => 6.50, 'price' => 12.00, 'reorder' => 40, 'description' => 'Adhesive medical tape'],
            
            // Baby Care
            ['sku' => 'BABY-POWDER', 'name' => 'Baby Powder 200g', 'category' => 'Baby Care', 'cost' => 14.00, 'price' => 25.00, 'reorder' => 30, 'description' => 'Gentle baby skin care'],
            ['sku' => 'BABY-OIL', 'name' => 'Baby Oil 200ml', 'category' => 'Baby Care', 'cost' => 16.00, 'price' => 28.00, 'reorder' => 28, 'description' => 'Moisturizing baby oil'],
            ['sku' => 'DIAPER-RASH', 'name' => 'Diaper Rash Cream 100g', 'category' => 'Baby Care', 'cost' => 18.00, 'price' => 32.00, 'reorder' => 25, 'description' => 'Diaper rash prevention cream'],
            ['sku' => 'BABY-WIPES', 'name' => 'Baby Wipes (Pack of 80)', 'category' => 'Baby Care', 'cost' => 20.00, 'price' => 35.00, 'reorder' => 22, 'description' => 'Gentle cleansing wipes'],
            
            // Personal Care
            ['sku' => 'TOOTHPASTE', 'name' => 'Toothpaste 100g', 'category' => 'Personal Care', 'cost' => 8.50, 'price' => 15.00, 'reorder' => 50, 'description' => 'Fluoride toothpaste'],
            ['sku' => 'MOUTHWASH', 'name' => 'Mouthwash 500ml', 'category' => 'Personal Care', 'cost' => 12.00, 'price' => 22.00, 'reorder' => 35, 'description' => 'Antiseptic mouthwash'],
            ['sku' => 'DEODORANT', 'name' => 'Deodorant Roll-On 50ml', 'category' => 'Personal Care', 'cost' => 10.00, 'price' => 18.00, 'reorder' => 45, 'description' => 'Antiperspirant deodorant'],
            ['sku' => 'SHAMPOO', 'name' => 'Shampoo 400ml', 'category' => 'Personal Care', 'cost' => 15.00, 'price' => 28.00, 'reorder' => 30, 'description' => 'Hair care shampoo'],
            
            // Herbal Products
            ['sku' => 'GINGER-TEA', 'name' => 'Ginger Tea Bags (Pack of 20)', 'category' => 'Herbal Products', 'cost' => 9.00, 'price' => 16.00, 'reorder' => 40, 'description' => 'Digestive health tea'],
            ['sku' => 'GINSENG-CAPS', 'name' => 'Ginseng Capsules 500mg (30 caps)', 'category' => 'Herbal Products', 'cost' => 25.00, 'price' => 45.00, 'reorder' => 20, 'description' => 'Energy and vitality supplement'],
            ['sku' => 'TURMERIC-500', 'name' => 'Turmeric 500mg Capsules (60 caps)', 'category' => 'Herbal Products', 'cost' => 20.00, 'price' => 38.00, 'reorder' => 25, 'description' => 'Anti-inflammatory supplement'],
            
            // Diabetic Care
            ['sku' => 'GLUCOMETER', 'name' => 'Blood Glucose Meter', 'category' => 'Diabetic Care', 'cost' => 150.00, 'price' => 250.00, 'reorder' => 10, 'description' => 'Portable blood sugar monitor'],
            ['sku' => 'TEST-STRIPS', 'name' => 'Glucose Test Strips (Box of 50)', 'category' => 'Diabetic Care', 'cost' => 35.00, 'price' => 60.00, 'reorder' => 30, 'description' => 'Blood glucose test strips'],
            ['sku' => 'LANCETS', 'name' => 'Lancets (Box of 100)', 'category' => 'Diabetic Care', 'cost' => 15.00, 'price' => 28.00, 'reorder' => 25, 'description' => 'Sterile lancets for testing'],
        ];

        $created = 0;
        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            
            if ($category) {
                Product::firstOrCreate(
                    ['sku' => $productData['sku']],
                    [
                        'name' => $productData['name'],
                        'category_id' => $category->id,
                        'supplier_id' => $supplierIds[array_rand($supplierIds)],
                        'cost_price' => $productData['cost'],
                        'selling_price' => $productData['price'],
                        'reorder_level' => $productData['reorder'],
                        'description' => $productData['description'],
                    ]
                );
                $created++;
            }
        }

        $this->command->info("Successfully created/seeded {$created} products!");
    }
}