<?php

namespace App\Http\Controllers;

use App\Models\StorefrontPage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class StorefrontPageController extends Controller
{
    public function page(string $slug): View
    {
        abort_unless($slug !== 'home', 404);

        $page = Schema::hasTable((new StorefrontPage)->getTable())
            ? StorefrontPage::query()
                ->where('slug', $slug)
                ->where('is_published', true)
                ->first()
            : null;

        abort_unless($page, 404);

        $defaults = [
            'title' => $page->title,
            'description' => $page->meta_description ?? $page->title,
            'eyebrow' => null,
            'heading' => $page->title,
            'subheading' => null,
            'body' => null,
        ];

        $sections = collect($page->sections ?? [])
            ->filter(fn ($section): bool => is_array($section) && (bool) ($section['is_active'] ?? true))
            ->values();

        return view('pages.cms', compact('page', 'defaults', 'sections'));
    }

    public function about(): View
    {
        return $this->show('about', [
            'title' => 'AK2 | About Us',
            'description' => 'Learn about AK2 and our approach to style.',
            'eyebrow' => 'THE AK2 STORY',
            'heading' => 'About AK2',
            'subheading' => 'Style that reflects your identity.',
            'body' => 'Discover the story behind AK2 and the collections we create for everyday life.',
            'translations' => [
                'ar' => [
                    'title' => 'AK2 | من نحن',
                    'description' => 'تعرّف على AK2 ونهجنا في عالم الأناقة.',
                    'eyebrow' => 'قصة AK2',
                    'heading' => 'عن AK2',
                    'subheading' => 'أسلوب يعكس هويتك.',
                    'body' => 'اكتشف قصة AK2 والمجموعات التي نصممها لحياتك اليومية.',
                ],
            ],
        ]);
    }

    public function contact(): View
    {
        return $this->show('contact', [
            'title' => 'AK2 | Contact',
            'description' => 'Contact the AK2 team.',
            'eyebrow' => 'WE ARE HERE TO HELP',
            'heading' => 'Contact AK2',
            'subheading' => 'Get in touch with our team.',
            'body' => 'Use the contact details or links published here to reach the AK2 team.',
            'translations' => [
                'ar' => [
                    'title' => 'AK2 | تواصل معنا',
                    'description' => 'تواصل مع فريق AK2.',
                    'eyebrow' => 'نحن هنا لمساعدتك',
                    'heading' => 'تواصل مع AK2',
                    'subheading' => 'يسعد فريقنا بالتواصل معك.',
                    'body' => 'استخدم بيانات الاتصال أو الروابط المنشورة هنا للتواصل مع فريق AK2.',
                ],
            ],
        ]);
    }

    /** @param array<string, mixed> $defaults */
    private function show(string $slug, array $defaults): View
    {
        $page = Schema::hasTable((new StorefrontPage)->getTable())
            ? StorefrontPage::query()
                ->where('slug', $slug)
                ->where('is_published', true)
                ->first()
            : null;

        $sections = collect($page?->sections ?? [])
            ->filter(fn ($section): bool => is_array($section) && (bool) ($section['is_active'] ?? true))
            ->values();

        return view("pages.{$slug}", [
            'page' => $page,
            'defaults' => $defaults,
            'sections' => $sections,
        ]);
    }
}
