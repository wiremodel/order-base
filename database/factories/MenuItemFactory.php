<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $menuItems = [
            ['name' => 'Caesar Salad', 'description' => 'Fresh romaine lettuce with parmesan cheese and croutons'],
            ['name' => 'Margherita Pizza', 'description' => 'Classic pizza with tomato sauce, mozzarella, and fresh basil'],
            ['name' => 'Grilled Salmon', 'description' => 'Atlantic salmon with lemon herb seasoning'],
            ['name' => 'Beef Burger', 'description' => 'Juicy beef patty with lettuce, tomato, and cheese'],
            ['name' => 'Chicken Alfredo', 'description' => 'Fettuccine pasta with grilled chicken in creamy alfredo sauce'],
            ['name' => 'Fish and Chips', 'description' => 'Beer battered cod with golden fries'],
            ['name' => 'Vegetable Stir Fry', 'description' => 'Fresh seasonal vegetables in teriyaki sauce'],
            ['name' => 'Chocolate Cake', 'description' => 'Rich chocolate cake with chocolate ganache'],
            ['name' => 'Tiramisu', 'description' => 'Classic Italian dessert with coffee and mascarpone'],
            ['name' => 'Iced Coffee', 'description' => 'Cold brew coffee served over ice'],
        ];
        
        $item = $this->faker->randomElement($menuItems);
        
        $dietaryOptions = ['vegetarian', 'vegan', 'gluten-free', 'dairy-free', 'nut-free'];
        $dietaryInfo = [];
        
        if ($this->faker->boolean(30)) {
            $dietaryInfo[$this->faker->randomElement($dietaryOptions)] = 'true';
        }

        return [
            'name' => $item['name'] . ' ' . $this->faker->optional(0.3)->word(),
            'description' => $item['description'],
            'price' => $this->faker->randomFloat(2, 8.99, 29.99),
            'is_available' => $this->faker->boolean(85),
            'image_url' => $this->faker->optional(0.7)->imageUrl(640, 480, 'food'),
            'dietary_info' => empty($dietaryInfo) ? null : $dietaryInfo,
        ];
    }
}
