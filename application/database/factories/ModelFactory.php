<?php



$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return array(
        'productName' => 'Maize',
        'productSerial' => $faker->regexify('[A-Z0-9._%+-]+[A-Z0-9.-]+[A-Z]{2,4}'),
        'amount' => $faker->numberBetween($min = 0, $max = 20),
        'location' => "Store",
        'unitCost' => $faker->numberBetween($min = 100, $max = 2000),
        'reorderAmount' => $faker->numberBetween($min = 1, $max = 15),
        'expirationDate' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'companyId' => 1);
});

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    return [
        'customerCode' => $faker->numerify('cust###'),
        'customerName' => $faker->company,
        'contactPerson' => $faker->name,
        'contactPersonPhone' => $faker->phoneNumber,
        'companyPhone' => $faker->phoneNumber,
        'contactPersonEmail' => $faker->email,
        'companyEmail' => $faker->companyEmail,
        'street' => $faker->streetName,
        'zipCode' => $faker->postcode,
        'city' => $faker->city,
        'country' => 'Botswana',
        'state' => $faker->state,
        'addressName1' => $faker->address,
        'addressName2' => $faker->address,
        'creditLimit' => $faker->randomNumber(5),
        'discount' => $faker->randomDigit,
        'active' => $faker->boolean(70),
        'disableReason' => ''

    ];
});




