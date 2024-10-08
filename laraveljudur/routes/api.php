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
use App\Http\Controllers\EventController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\BidController;
use App\Events\EventCreated;
Route::put('/lands/{id}/accept', [LandController::class, 'accept']);
Route::put('/lands/{id}/reject', [LandController::class, 'reject']);
Route::put('/examiner-reports/report-details/{id}/status', [LandController::class, 'updateStatus']);

Route::get('/trigger-event', function() {
    // Trigger the event with a message
    broadcast(new EventCreated('This is a test notification!'));
    
    return 'Event broadcasted!';
});
Route::middleware('auth:sanctum')->post('/list-event/join-event', [EventController::class, 'joinEvent']);
Route::middleware('auth:sanctum')->delete('/list-event/cancel-event/{eventId}', [EventController::class, 'cancelEvent']);

Route::middleware('auth:sanctum')->get('events/{eventId}/is-joined', [EventController::class, 'isVolunteerJoined']);
use App\Http\Controllers\PaymentController;

// Your routes/api.php
//////////working
use App\Http\Controllers\VolunteerController;

Route::middleware('auth:sanctum')->resource('volunteers', VolunteerController::class);
Route::middleware('auth:sanctum')->post('/volunteer/request-examiner', [VolunteerController::class, 'requestExaminer']);
Route::middleware('auth:sanctum')->get('/volunteer/check-examiner-request', [VolunteerController::class, 'checkExaminerRequest']);


    // Create a payment and redirect to PayPal for approval



    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::post('/initiate-payment', [PaymentController::class, 'initiatePayment']);
    // });



////////////////////////


Route::middleware('auth:sanctum')->post('/land-inspection', [LandInspectionController::class, 'store']);
Route::middleware('auth:sanctum')->get('/lands', [LandInspectionController::class, 'getLands']);
///////////////////////

 Route::put('/profile/{id}', [UserController::class, 'updateProfile']);

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{id}/comments', [PostController::class, 'storeComment'])->name('comments.store');

Route::get('/posts/{id}', [PostController::class, 'show']);



// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::apiResource('land-inspections', LandInspectionController::class);
// });



Route::delete('/examiner-reports/{id}', [LandInspectionController::class, 'destroy']);
Route::get('/examiner-reports', [LandInspectionController::class, 'index']);
Route::get('/examiner-reports/report-details/{id}', [LandInspectionController::class, 'show']);
Route::apiResource('land-inspections', LandInspectionController::class);
Route::apiResource('lands', LandController::class);
Route::apiResource('posts', PostController::class);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('land-inspections', LandInspectionController::class);
});

Route::put('/profile/{id}', [UserController::class, 'updateProfile']);

Route::get('/profile/{id}', [UserController::class, 'getProfile']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);

// Register a new user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/volunteer', [AuthController::class, 'registerVolunteer']);
// Login a user

// Route::post('/login', [AuthController::class, 'login']);



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

// Route::post('/auction/{auctionId}/complete', [BidController::class, 'getAuctionWinnerAndStorePayment']);
Route::post('/auction/{auctionId}/complete', [BidController::class, 'completeAuction']);
Route::middleware('auth:sanctum')->get('/completed-auctions', [AuctionController::class, 'getCompletedAuctions']);


// Route::get('/auctions', [AuctionController::class, 'index']);




// Route::apiResource('auctions', AuctionController::class);
Route::get('/auctions', [AuctionController::class, 'index']); // View all available auctions
Route::get('/auctions/{id}', [AuctionController::class, 'show']); // View a single auction by ID

// Protected routes (requires authentication using Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auctions/{id}/bid', [BidController::class, 'store']); // Place a bid on an auction
    Route::post('/auctions/{id}/complete', [AuctionController::class, 'completeAuction']); // Complete an auction
});
Route::middleware('auth:sanctum')->post('/auctions/{auction_id}/bids', [BidController::class, 'placeBid']);
Route::post('/confirm-auction-payment', [DonationController::class, 'confirmPayment']);
 
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
Route::get('/dashboard/main/index', [AdminController::class, 'index']);

Route::get('/dashboard/events/create/form', [AdminController::class, 'eventForm']);
Route::post('/dashboard/events/create', [AdminController::class, 'createEvent']);
Route::put('/dashboard/events/{id}', [AdminController::class, 'editEvent']);
Route::delete('/dashboard/events/{id}', [AdminController::class, 'deleteEvent']);

Route::get('/dashboard/auctions', [AdminController::class, 'getAuctions']);
Route::get('/dashboard/auctions/{id}', [AdminController::class, 'auctionDetails']);
Route::post('/dashboard/auctions', [AdminController::class, 'createAuction']);
Route::get('/dashboard/statuses/auctions', [AdminController::class, 'getAuctionStatuses']);
Route::get('/dashboard/items/auctions', [AdminController::class, 'getAuctionItems']);
Route::get('/dashboard/allitems/auctions', [AdminController::class, 'getAllItems']);
Route::get('/dashboard/valuableitems/itemDonation/{id}', [AdminController::class, 'getValuableItemDetails']);

Route::put('/dashboard/auctions/{id}', [AdminController::class, 'editAuction']);
Route::delete('/dashboard/auctions/{id}', [AdminController::class, 'deleteAuction']);
Route::get('/pie-chart-data', [AdminController::class, 'getPieChartData']);
Route::get('/dashboard-data', [AdminController::class, 'getDashboardData']);

// Dashboard Routes End



Route::post('/donate', [DonationController::class, 'donate']);
Route::post('/create-payment', [DonationController::class, 'createPayment']);
Route::post('/login', [AuthController::class, 'login']);


  

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $token = Str::random(60);

    // Insert the token manually for testing
    DB::table('password_reset_tokens')->insert([
        'email' => $request->email,
        'token' => $token,
        'created_at' => now(),
    ]);

    // Generate the reset link
    $resetLink = "http://localhost:4200/reset-password?token={$token}&email={$request->email}";

    // Send the reset link email using Mail::to() directly
    Mail::raw("Click here to reset your password: {$resetLink}", function ($message) use ($request) {
        $message->to($request->email)
            ->subject('Password Reset');
    });

    return response()->json(['message' => 'Reset link sent to your email.'], 200);
});

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|confirmed|min:8',
    ]);

    // Check if the reset token exists
    $passwordReset = DB::table('password_reset_tokens')->where([
        ['email', $request->email],
        ['token', $request->token],
    ])->first();

    if (!$passwordReset) {
        return response()->json(['message' => 'Invalid token or email.'], 400);
    }

    // Update the user's password
    $user = DB::table('users')->where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    DB::table('users')->where('email', $request->email)->update([
        'password' => Hash::make($request->password),
        'remember_token' => Str::random(60),
    ]);

    // Delete the token after password reset
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json(['message' => 'Password reset successful.'], 200);
});
