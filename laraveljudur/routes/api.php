<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuctionController;

use App\Http\Controllers\VolunteerAnalyticsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonorController;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\UserController;
use App\Http\Controllers\LandInspectionController;
use App\Http\Controllers\PostController;



Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{id}/comments', [PostController::class, 'storeComment'])->name('comments.store');




// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::apiResource('land-inspections', LandInspectionController::class);
// });

Route::put('/profile/{id}', [UserController::class, 'updateProfile']);

Route::get('/profile/{id}', [UserController::class, 'getProfile']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
use App\Http\Controllers\EventController;

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);

// Register a new user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/volunteer', [AuthController::class, 'registerVolunteer']);
// Login a user

Route::post('/login', [AuthController::class, 'login']);



// Logout a user (requires authentication)
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});


Route::get('/volunteer-summary/{volunteerId}', [VolunteerAnalyticsController::class, 'getVolunteerSummary']);
Route::get('/volunteer-activity/{volunteerId}', [VolunteerAnalyticsController::class, 'getVolunteerActivityOverTime']);
Route::get('/volunteer/by-user/{userId}', [VolunteerAnalyticsController::class, 'getVolunteerIdByUserId']);
Route::get('/volunteer-events/{volunteerId}', [VolunteerAnalyticsController::class, 'getVolunteerEvents']);
Route::get('/examiner-lands/{volunteerId}', [VolunteerAnalyticsController::class, 'getExaminerLandData']);
Route::get('/land-inspections/{examinerId}', [VolunteerAnalyticsController::class, 'getLandInspections']);

Route::get('/pending-lands', [VolunteerAnalyticsController::class, 'getPendingLands']);
Route::post('/lands/notify-land-owners', [VolunteerAnalyticsController::class, 'notifyLandOwner']);





// Route to show the contact form
Route::get('/contact', [ContactUsController::class, 'showContactForm'])->name('contact.form');

// Route to handle the form submission
Route::post('/contact/send', [ContactUsController::class, 'sendContactMessage'])->name('contact.send');





// Route::get('/auctions', [AuctionController::class, 'index']);




// Route::apiResource('auctions', AuctionController::class);
Route::get('/auctions', [AuctionController::class, 'index']);



////

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/donate-land', [DonationController::class, 'donateLand']);
    Route::post('/donate-item', [DonationController::class, 'donateItem']);
    Route::post('/donate-money', [DonationController::class, 'donateMoney']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/donor/dashboard', [DonorController::class, 'dashboard']);
    Route::get('/donor/view-details/{type}', [DonorController::class, 'viewDetails']);
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/donor/dashboard', [DonorController::class, 'dashboard'])->name('donor.dashboard');
});


Route::get('/send-test-email', function () {
    Mail::raw('This is a test email from Judur!', function ($message) {
        $message->to('your-email@example.com')
                ->subject('Test Email');
    });
    return 'Test email sent!';
});
// Dashboard Routes Start

Route::get('/donors', [AdminController::class, 'getDonors']);
Route::get('/volunteers', [AdminController::class, 'getVolunteers']);
Route::get('/donor/{id}', [AdminController::class, 'donorDetails']);
Route::get('/volunteer/{id}', [AdminController::class, 'volunteerDetails']);

Route::get('/pending-volunteers', [AdminController::class, 'getPendingVolunteers']);
Route::put('/volunteer/{id}/status', [AdminController::class, 'updateStatus']);

Route::get('/pending-examiners', [AdminController::class, 'getPendingExaminers']);
Route::get('/examiner/{id}', [AdminController::class, 'examinerDetails']);
Route::put('/examiner/{id}/status', [AdminController::class, 'updateExaminerStatus']);



    Route::get('/users', [UserController::class, 'index']); 
    Route::post('/users', [UserController::class, 'store']); 
    Route::get('/users/{id}', [UserController::class,'show']); 
    Route::put('/users/{id}', [UserController::class, 'update']); 
    Route::delete('/users/{id}', [UserController::class, 'destroy']); 



Route::get('/dashboard/events', [AdminController::class, 'getEvents']);
Route::get('/dashboard/events/{id}', [AdminController::class, 'eventDetails']);

Route::get('/dashboard/events/create/form', [AdminController::class, 'eventForm']); 
Route::post('/dashboard/events/create', [AdminController::class, 'createEvent']); 
Route::put('/dashboard/events/{id}', [AdminController::class, 'editEvent']);
Route::delete('/dashboard/events/{id}', [AdminController::class, 'deleteEvent']);

// Dashboard Routes End



Route::post('/donate', [DonationController::class, 'donate']);
Route::post('/create-payment', [DonationController::class, 'createPayment']);




