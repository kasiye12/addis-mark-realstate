<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PropertyController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminBlogCategoryController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| File Serving Route (For cPanel - No storage symlink needed)
|--------------------------------------------------------------------------
*/

Route::get('/file/{path}', function($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    return response()->file($fullPath, [
        'Content-Type' => mime_content_type($fullPath),
        'Accept-Ranges' => 'bytes',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('file.show');

/*
|--------------------------------------------------------------------------
| Frontend Routes (Public)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Property Routes
Route::prefix('properties')->name('properties.')->group(function () {
    Route::get('/', [PropertyController::class, 'index'])->name('index');
    Route::get('/featured', [PropertyController::class, 'featured'])->name('featured');
    Route::get('/for-sale', [PropertyController::class, 'forSale'])->name('for-sale');
    Route::get('/for-rent', [PropertyController::class, 'forRent'])->name('for-rent');
    Route::get('/new-listings', [PropertyController::class, 'newListings'])->name('new-listings');
    Route::get('/category/{slug}', [PropertyController::class, 'category'])->name('category');
    Route::get('/location/{slug}', [PropertyController::class, 'location'])->name('location');
    Route::get('/type/{type}', [PropertyController::class, 'type'])->name('type');
    Route::get('/{slug}', [PropertyController::class, 'show'])->name('show');
    
    Route::post('/{property}/favorite', [PropertyController::class, 'toggleFavorite'])
        ->middleware('auth')
        ->name('favorite');
    Route::post('/{property}/share', [PropertyController::class, 'share'])->name('share');
    Route::get('/{property}/download-brochure', [PropertyController::class, 'downloadBrochure'])->name('download-brochure');
    Route::post('/{property}/inquiry', [ContactController::class, 'propertyInquiry'])->name('inquiry');
});
 // Projects Routes
Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProjectController::class, 'show'])->name('show');
});

// Blog Routes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});
// Static Pages
Route::view('/about', 'frontend.about')->name('about');
Route::view('/services', 'frontend.services')->name('services');
Route::view('/privacy-policy', 'frontend.privacy-policy')->name('privacy');
Route::view('/terms-of-service', 'frontend.terms')->name('terms');
Route::view('/faq', 'frontend.faq')->name('faq');

// Contact Routes
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::post('/', [ContactController::class, 'send'])->name('send');
    Route::post('/property/{property}', [ContactController::class, 'propertyInquiry'])->name('property');
    Route::post('/valuation', [ContactController::class, 'valuationRequest'])->name('valuation');
    Route::post('/callback', [ContactController::class, 'callbackRequest'])->name('callback');
});

Route::get('/contact-simple', [ContactController::class, 'index'])->name('contact');
Route::post('/contact-simple', [ContactController::class, 'send'])->name('contact.send');

// Newsletter
Route::post('/newsletter/subscribe', [HomeController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [HomeController::class, 'newsletterUnsubscribe'])->name('newsletter.unsubscribe');

// Testimonials (Frontend)
Route::get('/testimonials', [HomeController::class, 'testimonials'])->name('testimonials');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
    Route::get('/reset-password/{token}', fn($token) => view('auth.reset-password', ['token' => $token]))->name('password.reset');
});

/*
|--------------------------------------------------------------------------
| All Authenticated Users (Admin, Agent, User)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard - Redirects based on role
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    });
    
    // My Properties (Agents and Admins)
    Route::prefix('my-properties')->name('user.properties.')->group(function () {
        Route::get('/', [DashboardController::class, 'myProperties'])->name('index');
        Route::get('/favorites', [DashboardController::class, 'favoriteProperties'])->name('favorites');
        Route::get('/inquiries', [DashboardController::class, 'myInquiries'])->name('inquiries');
        Route::get('/searches', [DashboardController::class, 'savedSearches'])->name('searches');
        Route::post('/searches', [DashboardController::class, 'saveSearch'])->name('searches.save');
        Route::delete('/searches/{id}', [DashboardController::class, 'deleteSearch'])->name('searches.delete');
    });
});

/*
|--------------------------------------------------------------------------
| Agent Routes (Admin + Agent)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'agent'])->prefix('agent')->name('agent.')->group(function () {
    
    // Agent Dashboard
    Route::get('/dashboard', [DashboardController::class, 'agentDashboard'])->name('dashboard');
    
    // Properties Management (Agents can manage their own properties)
    Route::resource('properties', AdminPropertyController::class)->except(['destroy']);
    Route::post('/properties/{property}/toggle-status', [AdminPropertyController::class, 'toggleStatus'])->name('properties.toggle-status');
    Route::post('/properties/{property}/images', [AdminPropertyController::class, 'uploadImages'])->name('properties.upload-images');
    Route::delete('/properties/{property}/images/{image}', [AdminPropertyController::class, 'deleteImage'])->name('properties.delete-image');
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::post('/quick-action', [DashboardController::class, 'quickAction'])->name('quick-action');
    
    // Properties Management
    Route::resource('properties', AdminPropertyController::class);
    Route::post('/properties/{property}/toggle-featured', [AdminPropertyController::class, 'toggleFeatured'])->name('properties.toggle-featured');
    Route::post('/properties/{property}/toggle-status', [AdminPropertyController::class, 'toggleStatus'])->name('properties.toggle-status');
    Route::post('/properties/{property}/images', [AdminPropertyController::class, 'uploadImages'])->name('properties.upload-images');
    Route::delete('/properties/{property}/images/{image}', [AdminPropertyController::class, 'deleteImage'])->name('properties.delete-image');
    Route::post('/properties/import', [AdminPropertyController::class, 'import'])->name('properties.import');
    Route::get('/properties/export', [AdminPropertyController::class, 'export'])->name('properties.export');
    Route::post('/properties/bulk-action', [AdminPropertyController::class, 'bulkAction'])->name('properties.bulk-action');
    
    // Categories Management
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);
    Route::post('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::post('/categories/reorder', [AdminCategoryController::class, 'reorder'])->name('categories.reorder');
    
    // Locations Management
    Route::resource('locations', AdminLocationController::class)->except(['create', 'edit']);
    Route::post('/locations/{location}/toggle-popular', [AdminLocationController::class, 'togglePopular'])->name('locations.toggle-popular');
    Route::post('/locations/bulk-delete', [AdminLocationController::class, 'bulkDelete'])->name('locations.bulk-delete');
    Route::get('/locations/export', [AdminLocationController::class, 'export'])->name('locations.export');
    Route::get('/locations/search', [AdminLocationController::class, 'search'])->name('locations.search');
    
    // Users Management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/verify', [AdminUserController::class, 'verify'])->name('users.verify');
    Route::post('/users/{user}/assign-role', [AdminUserController::class, 'assignRole'])->name('users.assign-role');
    Route::post('/users/bulk-delete', [AdminUserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::get('/users/export', [AdminUserController::class, 'export'])->name('users.export');
    
    // Testimonials Management
    Route::resource('testimonials', TestimonialController::class);
    Route::post('/testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
    Route::post('/testimonials/reorder', [TestimonialController::class, 'reorder'])->name('testimonials.reorder');
    Route::post('/testimonials/bulk-delete', [TestimonialController::class, 'bulkDelete'])->name('testimonials.bulk-delete');
    
    // Team Management
    Route::resource('team', TeamController::class);
    Route::post('/team/{member}/toggle-status', [TeamController::class, 'toggleStatus'])->name('team.toggle-status');
    Route::post('/team/reorder', [TeamController::class, 'reorder'])->name('team.reorder');
    
    // Projects Management
    Route::resource('projects', AdminProjectController::class)->except(['show']);
    Route::post('/projects/{project}/toggle-featured', [AdminProjectController::class, 'toggleFeatured'])->name('projects.toggle-featured');
    Route::post('/projects/{project}/toggle-status', [AdminProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
    
    // Blog Management
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::resource('posts', AdminBlogController::class)->except(['show']);
        Route::post('/posts/{post}/toggle-publish', [AdminBlogController::class, 'togglePublish'])->name('posts.toggle-publish');
        Route::post('/posts/{post}/toggle-featured', [AdminBlogController::class, 'toggleFeatured'])->name('posts.toggle-featured');
        Route::resource('categories', AdminBlogCategoryController::class)->except(['show', 'create', 'edit']);
        Route::post('/categories/{category}/toggle-status', [AdminBlogCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    });
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
        Route::post('/logo', [SettingController::class, 'uploadLogo'])->name('upload-logo');
        Route::post('/favicon', [SettingController::class, 'uploadFavicon'])->name('upload-favicon');
        Route::post('/video', [SettingController::class, 'uploadVideo'])->name('upload-video');
        Route::delete('/video', [SettingController::class, 'deleteVideo'])->name('delete-video');
        Route::delete('/logo', [SettingController::class, 'deleteLogo'])->name('delete-logo');
        Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        Route::post('/backup', [SettingController::class, 'createBackup'])->name('backup');
    });
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->group(function () {
    Route::get('/properties/nearby', [PropertyController::class, 'nearby'])->name('properties.nearby');
    Route::get('/properties/quick-search', [PropertyController::class, 'quickSearch'])->name('properties.quick-search');
});

// Fallback route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

require __DIR__.'/auth.php';