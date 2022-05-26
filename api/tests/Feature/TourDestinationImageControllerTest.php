<?php

namespace Tests\Feature;

use App\Models\TourDestination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TourDestinationImageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itShoudUploadTourDestinationImages()
    {
        Storage::fake();

        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $tour_destination = TourDestination::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->post("/api/tour_destinations/{$tour_destination->id}/images", [
            'image' => UploadedFile::fake()->create('image.jpeg'),
        ]);

        Storage::assertExists(
            $response->json('data.path')
        );
    }

    /**
     * @test
     */
    public function itShouldNotUpdateImageIfNotAuthenticated()
    {
        $tour_destination = TourDestination::factory()->create();

        $response = $this->post("/api/tour_destinations/{$tour_destination->id}/images", [
            'image' => UploadedFile::fake()->create('image.jpeg'),
        ]);

        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function itShouldDeleteImages()
    {
        Storage::put('/TourDestination_image.jpeg', 'empty');

        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $tour_destination = TourDestination::factory()->create();

        $tour_destination->images()->create([
            'path' => 'image1.jpeg',
        ]);

        $image = $tour_destination->images()->create([
            'path' => 'TourDestination_image.jpeg',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->deleteJson("/api/tour_destinations/{$tour_destination->id}/images/{$image->id}");

        $response->assertOk();

        $this->assertModelMissing($image);

        Storage::assertMissing('TourDestination_image.jpeg');
    }

    /**
     * @test
     */
    public function itShouldNotDeleteTheOnlyImages()
    {
        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $tour_destination = TourDestination::factory()->create();

        $image = $tour_destination->images()->create([
            'path' => 'TourDestination_image.jpeg',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->deleteJson("/api/tour_destinations/{$tour_destination->id}/images/{$image->id}");

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors(['image' => 'Obrigatório ter pelo menos uma imagem.']);
    }

    /**
     * @test
     */
    public function itShouldNotDeleteImageThatBelongsToAnotherResource()
    {
        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $tour_destination = TourDestination::factory()->create(['name' => 'tour_destination 1']);
        $tour_destination2 = TourDestination::factory()->create(['name' => 'tour_destination 2']);

        $image = $tour_destination->images()->create([
            'path' => 'TourDestination_image.jpeg',
        ]);


        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->deleteJson("/api/tour_destinations/{$tour_destination2->id}/images/{$image->id}");

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function itShouldNotDeleteTheFeaturedImages()
    {
        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $tour_destination = TourDestination::factory()->create();

        $tour_destination->images()->create([
            'path' => 'image1.jpeg',
        ]);

        $image = $tour_destination->images()->create([
            'path' => 'TourDestination_image.jpeg',
        ]);

        $tour_destination->update(['featured_image_id' => $image->id]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->deleteJson("/api/tour_destinations/{$tour_destination->id}/images/{$image->id}");

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors(['image' => 'Não é possível deletar a imagem principal.']);
    }
}
