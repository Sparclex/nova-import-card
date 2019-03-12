<?php

namespace Sparclex\NovaImportCard\Tests\Feature;

use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Sparclex\NovaImportCard\Tests\Fixtures\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Sparclex\NovaImportCard\Tests\IntegrationTest;

class ImportCsvTest extends IntegrationTest
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_should_import_a_csv()
    {
        $this->authenticate();
        Storage::fake('public');

        $this
            ->json(
                'post',
                'nova-vendor/sparclex/nova-import-card/endpoint/users',
                [
                    'file' => $this->createTmpFile(__DIR__.'/../stubs/users.csv'),
                ]
            )
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'username' => 'user1',
            'name' => 'john',
            'age' => 21,
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'user2',
            'name' => 'jane',
            'age' => null,
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'user3',
            'name' => 'jannet',
            'age' => 42,
        ]);
    }

    /** @test */
    public function it_should_respect_validation_rules()
    {
        $this->authenticate();
        Storage::fake('public');

        $this
            ->json(
                'post',
                'nova-vendor/sparclex/nova-import-card/endpoint/users',
                [
                    'file' => $this->createTmpFile(__DIR__.'/../stubs/users-with-null-value.csv'),
                ]
            )
            ->assertStatus(422)
            ->assertJsonValidationErrors([0]);
    }

    /** @test */
    public function it_should_not_import_unkown_file_types()
    {
        $this->authenticate();
        Storage::fake('public');

        $this
            ->json(
                'post',
                'nova-vendor/sparclex/nova-import-card/endpoint/users',
                [
                    'file' => $this->createTmpFile(__DIR__.'/../stubs/unknown.zip', 'zip'),
                ]
            )
            ->assertStatus(422)
            ->assertJsonValidationErrors([0]);
    }

    /** @test */
    public function it_should_import_with_related()
    {
        $this->authenticate();
        Storage::fake('public');
        factory(User::class, 3)->create();

        $this
            ->json(
                'post',
                'nova-vendor/sparclex/nova-import-card/endpoint/addresses',
                [
                    'file' => $this->createTmpFile(__DIR__.'/../stubs/addresses.csv'),
                ]
            )
            ->assertSuccessful();

        $this->assertDatabaseHas('addresses', [
            'user_id' => 1,
            'street' => 'street1',
        ]);
        $this->assertDatabaseHas('addresses', [
            'user_id' => 2,
            'street' => 'street2',
        ]);
    }

    /** @test */
    public function it_should_import_with_nullable_related()
    {
        $this->authenticate();
        Storage::fake('public');
        factory(User::class, 3)->create();

        $this
            ->json(
                'post',
                'nova-vendor/sparclex/nova-import-card/endpoint/addresses',
                [
                    'file' => $this->createTmpFile(__DIR__.'/../stubs/addresses-nullable.csv'),
                ]
            )
            ->assertSuccessful();

        $this->assertDatabaseHas('addresses', [
            'user_id' => null,
            'street' => 'street1',
        ]);
        $this->assertDatabaseHas('addresses', [
            'user_id' => null,
            'street' => 'street2',
        ]);
    }

    protected function createTmpFile($path, $ext = 'csv')
    {
        $tmp = tmpfile();
        fwrite($tmp, file_get_contents($path));

        return new File('file.'.$ext, $tmp);
    }
}
