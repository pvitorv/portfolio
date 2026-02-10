<?php

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', function () {
    $sitemapUrl = rtrim(config('app.url'), '/') . '/sitemap.xml';
    $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /login\n\nSitemap: {$sitemapUrl}\n";
    return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::get('/sitemap.xml', function () {
    $base = rtrim(config('app.url'), '/');
    $urls = [
        ['loc' => $base . '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['loc' => $base . '/docs/criar-seu-portfolio', 'changefreq' => 'monthly', 'priority' => '0.8'],
    ];
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $u) {
        $xml .= "  <url>\n    <loc>" . htmlspecialchars($u['loc']) . "</loc>\n    <changefreq>{$u['changefreq']}</changefreq>\n    <priority>{$u['priority']}</priority>\n  </url>\n";
    }
    $xml .= '</urlset>';
    return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
})->name('sitemap');

Route::get('/', function () {
    $projects = Project::where('is_visible', true)
        ->orderBy('order')
        ->orderBy('created_at', 'desc')
        ->get();
    $profile = User::first(); // único usuário = perfil público
    return view('welcome', compact('projects', 'profile'));
});

Route::get('/docs/criar-seu-portfolio', fn () => view('docs.criar-seu-portfolio'))->name('docs.criar-seu-portfolio');

Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::post('projects/fetch-thumbnail', [ProjectController::class, 'fetchThumbnail'])->name('projects.fetch-thumbnail');
    Route::resource('projects', ProjectController::class);
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
