<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = rand(0, 1); // 0 -- male; 1 -- female
        $photos = $this->getPhotosForGender($gender);

        return [
            'first_name' => $gender ? fake()->firstNameFemale() : fake()->firstNameMale(),
            'last_name' => fake()->lastName(),
            'photo' => $photos[array_rand($photos)],
            'gender' => $gender,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function getPhotosForGender(int $gender)
    {
        return [
            0 => [
                'male-1.jpeg',
                'male-2.jpeg',
                'male-3.jpeg',
                'male-4.jpeg',
                'male-5.jpeg',
                'male-6.jpeg',
                'male-7.jpeg',
                'male-8.jpeg',
                'male-9.jpeg',
                'male-10.jpeg',
            ],
            1 => [
                'female-1.jpeg',
                'female-2.jpeg',
                'female-3.jpeg',
                'female-4.jpeg',
                'female-5.jpeg',
                'female-6.jpeg',
                'female-7.jpeg',
                'female-8.jpeg',
                'female-9.jpeg',
                'female-10.jpeg',
            ],
        ][$gender];
    }
}
