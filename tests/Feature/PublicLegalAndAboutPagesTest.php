<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLegalAndAboutPagesTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;
    public function test_landing_page_renders_official_business_address_and_legal_links(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Gang Mahi Salam, RT 001/021, No. 25');
        $response->assertSee('Parigi, Pondok Aren');
        $response->assertSee('Kota Tangerang Selatan');
        $response->assertSee(route('landing.about'));
        $response->assertSee(route('landing.terms'));
    }

    public function test_about_us_page_renders_successfully(): void
    {
        $response = $this->get('/tentang-kami');

        $response->assertStatus(200);
        $response->assertSee('Tentang MooWiFi');
        $response->assertSee('Visi &amp; Komitmen Kami', false);
        $response->assertSee('Gang Mahi Salam, RT 001/021, No. 25');
        $response->assertSee('Kota Tangerang Selatan');
        $response->assertDontSee('Platform SaaS RT/RW Net &amp; ISP Lokal No. 1', false);
        $response->assertSee('wa.me/6288976291662');
        $response->assertSee('+62 889-7629-1662');
    }

    public function test_terms_and_conditions_page_renders_protective_legal_clauses(): void
    {
        $response = $this->get('/syarat-dan-ketentuan');

        $response->assertStatus(200);
        $response->assertSee('Syarat dan Ketentuan Pengguna');
        $response->assertSee('Kedudukan Penyedia Software (Bukan Penyelenggara ISP)');
        $response->assertSee('Kepatuhan Hukum &amp; Legalitas Usaha Tenant', false);
        $response->assertSee('Pembatasan Tanggung Jawab (Limitation of Liability)');
        $response->assertSee('Ganti Rugi &amp; Pelepasan Klaim (Indemnification)', false);
        $response->assertSee('Pengadilan Negeri Kota Tangerang Selatan');
        $response->assertSee('Gang Mahi Salam, RT 001/021, No. 25');
    }

    public function test_sitemap_includes_about_and_terms_pages(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee(url('/tentang-kami'));
        $response->assertSee(url('/syarat-dan-ketentuan'));
    }
}
