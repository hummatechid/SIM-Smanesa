<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->email(),
            'nik' => $this->faker->randomNumber(),
            'nisn' => $this->faker->randomNumber(),
            'nipd' => $this->faker->randomNumber(),
            'full_name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'phone_number' => $this->faker->randomNumber(),
            'address' => $this->faker->address(),
            'religion' => 'islam',
            'nama_rombel' => 'RPL 1',
            'register_type' => '1',
            'school_origin' => '1',
            'birth_location' => '1',
            'birth_date' => '1',
            'father_name' => '1',
            'father_job' => '1',
            'mother_name' => '1',
            'mother_job' => '1',
            'anak_keberapa' => '1',
            'weight' => '1',
            'height' => '1',
            'semester' => '1',
            'tingkat_pendidikan' => '1',
            'kurikulum' => '1',
            'kebutuhan_khusus' => '1',
        ];
    }
}
