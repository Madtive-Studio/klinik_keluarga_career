<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Candidate;
use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(DocumentType::cases());
        
        return [
            'name' => $this->faker->word() . '.pdf',
            'file' => 'candidates/documents/' . $type->value . '/' . $this->faker->uuid() . '.pdf',
            'type' => $type,
            'candidate_id' => Candidate::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
    // State untuk tipe CV
    public function cv(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DocumentType::CV,
            'name' => 'cv_' . $this->faker->name() . '.pdf',
        ]);
    }
    
    // State untuk tipe MCU
    public function mcu(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DocumentType::MCU,
            'name' => 'mcu_' . $this->faker->date() . '.pdf',
        ]);
    }
    
    // State untuk tipe OTHERS
    public function others(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => DocumentType::OTHERS,
            'name' => $this->faker->word() . '_document.pdf',
        ]);
    }
    
    // State untuk soft deleted
    public function trashed(): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }
}