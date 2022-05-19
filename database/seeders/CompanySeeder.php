<?php

namespace Database\Seeders;

use App\Models\CompanyModels\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    private $company;
    private $myCompanies = array(), $myProductTypes = array();

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 6; $i++) {
            $name = 'company' . Str::random(3);
            $new_comp = $this->addCompany($name);
            array_push($this->myCompanies, $new_comp);
        }
        foreach ($this->myCompanies as $company) {
            if ($company->id == 1 || $company->id == 2) {
                $this->createCategories($company);
                $prod_types = $this->createProductTypes($company);
                foreach ($prod_types as $prod_type)
                    array_push($this->myProductTypes, $prod_type);
            }
        }
        foreach ($this->myProductTypes as $productType) {
            $this->createProducts($productType);
        }
    }

    public function addCompany($name)
    {
        return $this->company = Company::create([
            'name' => $name,
            'description' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function createCategories($company)
    {
        $company->categories()->createMany(
            [
                [
                    'name' => 'category' . Str::random(3),
                    'description' => '',
                    'company_id' => $company->id
                ],
                [
                    'name' => 'category' . Str::random(3),
                    'description' => '',
                    'company_id' => $company->id
                ],
                [
                    'name' => 'category' . Str::random(3),
                    'description' => '',
                    'company_id' => $company->id
                ],
            ]
        );
    }

    public function createProductTypes($company)
    {
        return $company->productTypes()->createMany([
            [
                'name' => 'productType' . str::random(3),
                'description' => '',
                'company_id' => $company->id
            ],
            [
                'name' => 'productType' . str::random(3),
                'description' => '',
                'company_id' => $company->id
            ],
            [
                'name' => 'productType' . str::random(3),
                'description' => '',
                'company_id' => $company->id
            ]
        ]);
    }

    public function createProducts($productType)
    {
        $category_id = $productType->id > 3 ? 2 : 1;
        $productType->products()->createMany([
            [
                'name' => 'product' . str::random(6),
                'description' => '',
                'price' => 450,
                'reorder_point' => 15,
                'product_type_id' => $productType->id,
                'category_id' => $category_id
            ],
            [
                'name' => 'product' . str::random(6),
                'description' => '',
                'price' => 450,
                'reorder_point' => 15,
                'product_type_id' => $productType->id,
                'category_id' => $category_id
            ],
            [
                'name' => 'product' . str::random(6),
                'description' => '',
                'price' => 450,
                'reorder_point' => 15,
                'product_type_id' => $productType->id,
                'category_id' => $category_id
            ]
        ]);
    }
}