<?php

namespace Sparclex\NovaImportCard\Tests\Feature;

use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Sparclex\NovaImportCard\Tests\Fixtures\Entry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Sparclex\NovaImportCard\Tests\IntegrationTest;

class ImportCsvTest extends IntegrationTest
{
    use RefreshDatabase;

    /** @test */
    public function it_should_import_a_csv()
    {
        $this->authenticate();
        Storage::fake('public');

        $this
            ->json('post',
                'nova-vendor/sparclex/nova-import-card/endpoint/entries', [
                    'file' => $this->createTmpFile(__DIR__.'/../stubs/entries.csv'),
                ])
            ->assertSuccessful();

        $this->assertDatabaseHas('entries', [
            'title' => 'Entry 1',
            'amount' => 10,
        ]);

        $this->assertDatabaseHas('entries', [
            'title' => 'Entry 2',
            'amount' => null,
        ]);
    }

    /** @test */
    public function it_should_respect_validation_rules()
    {
        $this->authenticate();
        Storage::fake('public');

        $this
            ->json('post',
                'nova-vendor/sparclex/nova-import-card/endpoint/entries', [
                    'file' => $this->createTmpFile(__DIR__.'/../stubs/entries-without-title.csv'),
                ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['0.title']);
    }

    protected function createTmpFile($path)
    {
        $tmp = tmpfile();
        fwrite($tmp, file_get_contents($path));

        return new File('file.csv', $tmp);
    }
}
