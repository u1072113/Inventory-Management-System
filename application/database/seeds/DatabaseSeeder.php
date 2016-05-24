<?php

use App\Company;
use App\Country;
use App\Customer;
use App\Department;
use App\Dispatch;
use App\Product;
use App\Restock;
use App\Role;
use App\Staff;
use App\Supplier;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call("CompanyTableSeeder");
        $this->call('UserTableSeeder');
        $this->call("DepartmentTableSeeder");
        $this->call("StaffTableSeeder");
        $this->call('ProductTableSeeder');
        $this->call('SupplierTableSeeder');
        $this->call('RestockTableSeeder');
        $this->call('DispatchTableSeeder');
        $this->call('RoleTableSeeder');
        $this->call('CountryTableSeeder');
        DB::table('purchase_orders_list')->truncate();
        DB::table('purchase_orders')->truncate();
    }

}

class CompanyTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('companies')->truncate();
        $faker = Faker\Factory::create();
        $company = Company::create(array(
            'companyName' => 'ACME',
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'defaultCurrency' => 'KES',
        ));
    }

}

class DepartmentTableSeeder extends Seeder
{

    public function run()
    {
        Auth::loginUsingId(1);
        $departments = [
            'Finance',
            'ICT',
            'Engineering',
            'Business Development',
            'Research and Development',
            'Operations',
            'Imports',
            'Exports',
            'Human Resource',
            'Maintainance',
            'Services',
            'Marketing',
            'Purchasing',
            'Quality Asurance',
            'Customer Service'
        ];
        DB::table('departments')->truncate();
        $faker = Faker\Factory::create();

        foreach ($departments as $department) {
            $startingDate = $faker->dateTimeBetween($startDate = '-30 days', $endDate = '-15 days');
            $endingDate = $faker->dateTimeBetween($startDate = '+10 days', $endDate = '+20 days');
            $department = Department::create(array(
                'name' => $department,
                'budgetLimit' => $faker->numberBetween($min = 100000, $max = 180000),
                'departmentEmail' => $faker->email,
                'budgetStartDate' => $startingDate->format('Y-m-d'),
                'budgetEndDate' => $endingDate->format('Y-m-d'),
                'companyId' => 1,


            ));
        }
    }

}

class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->truncate();

        $faker = Faker\Factory::create();
        $user = User::create(array(
            'name' => 'Robert Calin',
            'email' => 'u1072113@unimail.hud.ac.uk',
            'password' => Hash::make('testtest'),
            'jobTitle' => 'Root Account',
            'role_id' => 1,
            'companyId' => 1
        ));
        $user = User::create(array(
            'name' => 'Code Calin',
            'email' => 'u1072113@unimail.hud.ac.uk',
            'password' => Hash::make('testtest'),
            'jobTitle' => 'Root Account',
            'role_id' => 1,
            'companyId' => 1
        ));
        $user = User::create(array(
            'name' => 'dispatcher',
            'email' => 'dispatcher@Inventory.com',
            'password' => Hash::make('test123'),
            'role_id' => 3,
            'companyId' => 1
        ));
        $user = User::create(array(
            'name' => 'administrator',
            'email' => 'administrator@Inventory.com',
            'password' => Hash::make('test123'),
            'role_id' => 2,
            'companyId' => 1
        ));
        for ($i = 0; $i < 40; $i++) {
            $user = User::create(array(
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $faker->text(),
                'salutation' => $faker->title,
                'departmentId' => $faker->numberBetween($min = 1, $max = 15),
                'role_id' => 5,
                'companyId' => 1

            ));
        }
    }

}

class StaffTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('staff')->truncate();
        Auth::loginUsingId(1);
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 40; $i++) {
            $staff = Staff::create(array(
                'name' => $faker->name,
                'email' => $faker->email,
                'salutation' => $faker->title,
                'departmentId' => $faker->numberBetween($min = 1, $max = 15),
            ));
        }
    }

}

class ProductTableSeeder extends Seeder
{

    public function run()
    {
        Auth::loginUsingId(1);
        DB::table('products')->truncate();
        $path = storage_path() . '/stock.csv';
        Excel::load($path, function ($reader) {
            $faker = Faker\Factory::create();
            $results = $reader->get();
            foreach ($results as $result) {
                $product = Product::create(array(
                    'productName' => $result->product,
                    'productSerial' => $faker->regexify('[A-Z0-9._%+-]+[A-Z0-9.-]+[A-Z]{2,4}'),
                    'amount' => $faker->numberBetween($min = 0, $max = 20),
                    'location' => "Store",
                    'unitCost' => $faker->numberBetween($min = 100, $max = 2000),
                    'reorderAmount' => $faker->numberBetween($min = 1, $max = 15),
                    'expirationDate' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'companyId' => 1

                ));
            }
        });

    }

}

class SupplierTableSeeder extends Seeder
{

    public function run()
    {
        Auth::loginUsingId(1);
        DB::table('suppliers')->truncate();
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 40; $i++) {
            $supplier = Supplier::create(array(
                'supplierName' => $faker->company,
                'address' => $faker->streetAddress,
                'location' => $faker->buildingNumber,
                'website' => $faker->domainName,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'companyId' => 1

            ));
        }
    }

}


class RestockTableSeeder extends Seeder
{

    public function run()
    {
        Auth::loginUsingId(1);
        DB::table('restocks')->truncate();
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $itemCost = $faker->numberBetween($min = 100, $max = 2000);
            $amount = $faker->numberBetween($min = 1, $max = 2);
            $unitCost = $itemCost / $amount;
            $restock = Restock::create(array(
                'productID' => $faker->numberBetween($min = 1, $max = 108),
                'supplierID' => $faker->numberBetween($min = 1, $max = 20),
                'itemCost' => $itemCost,
                'amount' => $amount,
                'unitCost' => $unitCost,
                'remarks' => $faker->sentence($nbWords = 5),
                'receivedBy' => 1,
                'companyId' => 1,
                'created_at' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now')
            ));
            Auth::loginUsingId(1);
            Product::find($restock->productID)->update(array('unitCost' => $restock->unitCost));
        }
    }

}

class DispatchTableSeeder extends Seeder
{

    public function run()
    {
        Auth::loginUsingId(1);
        DB::table('dispatches')->truncate();
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 200; $i++) {
            $dispatchedTo = $faker->numberBetween($min = 4, $max = 40);
            $departmentId = User::find($dispatchedTo)->departmentId;
            $dispatchedItem = $faker->numberBetween($min = 1, $max = 108);
            $unitCost = Product::find(intval($dispatchedItem))->unitCost;
            $amount = $faker->numberBetween($min = 1, $max = 10);
            $totalCost = floatval($unitCost) * floatval($amount);
            $date = $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now');
            $dispatch = Dispatch::create(array(
                'dispatchedItem' => $dispatchedItem,
                'dispatchedTo' => $dispatchedTo,
                'departmentId' => $departmentId,
                'userId' => $faker->numberBetween($min = 0, $max = 3),
                'amount' => $amount,
                'remarks' => $faker->sentence($nbWords = 5),
                'totalCost' => $totalCost,
                'created_at' => $date,
                'updated_at' => $date,
                'companyId' => 1
            ));
        }
    }

}

class RoleTableSeeder extends Seeder
{

    public function run()
    {
        Auth::loginUsingId(1);
        DB::table('roles')->truncate();
        Role::create([
            'id' => 1,
            'name' => 'Root',
            'description' => 'Use this account with extreme caution. When using this account it is possible to cause irreversible damage to the system.'
        ]);

        Role::create([
            'id' => 2,
            'name' => 'Administrator',
            'description' => 'Full access to create, edit, Delete Stock Items, Users and Departments'
        ]);

        Role::create([
            'id' => 3,
            'name' => 'Dispatcher',
            'description' => 'Ability to Dispach and Restock Items can also delete Dispatches and Restocks but not users and departments'
        ]);

        Role::create([
            'id' => 4,
            'name' => 'Elevator',
            'description' => 'Can see what items has been dispatched to him/her'
        ]);

        Role::create([
            'id' => 5,
            'name' => 'User',
            'description' => 'Customizable access can be given to this user'
        ]);

    }

}

}


