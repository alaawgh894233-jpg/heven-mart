<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arabic = [
            'العاب',
            'الكترونيات',
            'كتب',
            'ملابس',
            'مشروبات',
            'ماكولات',
            'ادوات منزلية'
        ];
        return [
            'name_ar' => $this->faker->randomElement($arabic),
            'name_en' => $this->faker->name(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'parent_id' => $this->faker->optional()->randomElement(
                Category::pluck('id')->toArray()),
            'image' => $this->faker->imageUrl()
            ];
    }
}
