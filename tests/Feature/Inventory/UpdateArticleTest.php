<?php

namespace Tests\Feature\Inventory;

use Bouncer;
use Tests\TestCase;
use App\Organization;
use App\Model\Inventory\Article;
use Laravel\Cashier\Subscription;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    private $subscription;

    private $organization;

    private $article;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subscription = factory(Subscription::class)->state('active')->create();
        $this->organization = factory(Organization::class)->create([
            'user_id' => $this->subscription->user->id
        ]);
        $this->article = factory(Article::class)->create(['organization_id' => $this->organization->id]);
    }

    /** @test */
    function subcribed_user_can_update_inventory_articles()
    {
        Bouncer::allow($this->subscription->user)->to('update', $this->article);

        $response = $this->withoutExceptionHandling()->actingAs($this->subscription->user)
            ->put(route('inventory.articles.update', [
                'slug' => $this->organization->slug,
                'article' => $this->article
            ]), [
                'name' => 'Articulo A',
                'cost' => 200,
            ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHas(['success' => 'Articulo actualizado correctamente.']);

        $this->assertDatabaseHas('inventory_articles', [
            'organization_id' => $this->organization->id,
            'name' => 'articulo a',
            'cost' => 200,
        ]);
    }

    /** @test */
    function other_subcribed_user_can_update_inventory_articles_if_are_invited_to_organization()
    {
        $other_subscription = factory(Subscription::class)->state('active')->create();

        Bouncer::allow($other_subscription->user)->to('update', $this->article);

        $this->organization->addContributor($other_subscription->user);

        $response = $this->withoutExceptionHandling()->actingAs($other_subscription->user)
            ->put(route('inventory.articles.update', [
                'slug' => $this->organization->slug,
                'article' => $this->article
            ]), [
                'name' => 'Articulo A',
                'cost' => 200,
            ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHas(['success' => 'Articulo actualizado correctamente.']);

        $this->assertDatabaseHas('inventory_articles', [
            'organization_id' => $this->organization->id,
            'name' => 'articulo a',
            'cost' => 200,
        ]);
    }

    /** @test */
    function guest_cannot_update_inventory_articles()
    {
        $response = $this->withExceptionHandling()
            ->put(route('inventory.articles.update', [
                'slug' => $this->organization->slug,
                'article' => $this->article
            ]), [
                'name' => 'Articulo A',
                'cost' => 200,
            ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('inventory_articles', [
            'organization_id' => $this->organization->id,
            'name' => 'articulo a',
            'cost' => 200,
        ]);
    }

    /** @test */
    function subcribed_user_cannot_update_inventory_articles_to_other_organization()
    {
        $subscription = factory(Subscription::class)->state('active')->create();

        $response = $this->withoutExceptionHandling()->actingAs($subscription->user)
            ->put(route('inventory.articles.update', [
                'slug' => $this->organization->slug,
                'article' => $this->article
            ]), [
                'name' => 'Articulo A',
                'cost' => 200,
            ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('home.index'));

        $this->assertDatabaseMissing('inventory_articles', [
            'organization_id' => $this->organization->id,
            'name' => 'articulo a',
            'cost' => 200,
        ]);
    }

    /** @test */
    function subcribed_user_cannot_create_inventory_articles_if_the_subscription_expired()
    {
        $this->subscription->update([
            'ends_at' => now()
        ]);

        $response = $this->withExceptionHandling()->actingAs($this->subscription->user)
            ->put(route('inventory.articles.update', [
                'slug' => $this->organization->slug,
                'article' => $this->article
            ]), [
                'name' => 'Articulo A',
                'cost' => 200,
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('inventory_articles', [
            'organization_id' => $this->organization->id,
            'name' => 'articulo a',
            'cost' => 200,
        ]);
    }
}
