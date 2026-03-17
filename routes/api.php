<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Customer\CustomerBookingController;
use App\Http\Controllers\Api\Customer\CustomerChatController;
use App\Http\Controllers\Api\Customer\CustomerDashboardController;
use App\Http\Controllers\Api\Customer\CustomerEstimateController;
use App\Http\Controllers\Api\Customer\CustomerLocationController;
use App\Http\Controllers\Api\Customer\CustomerNotificationController;
use App\Http\Controllers\Api\Customer\CustomerPaymentController;
use App\Http\Controllers\Api\Customer\CustomerProfileController;
use App\Http\Controllers\Api\Customer\CustomerReviewController;
use App\Http\Controllers\Api\Technician\TechnicianBookingController;
use App\Http\Controllers\Api\Technician\TechnicianChatController;
use App\Http\Controllers\Api\Technician\TechnicianDashboardController;
use App\Http\Controllers\Api\Technician\TechnicianIncomeController;
use App\Http\Controllers\Api\Technician\TechnicianNotificationController;
use App\Http\Controllers\Api\Technician\TechnicianPriceReferenceController;
use App\Http\Controllers\Api\Technician\TechnicianProfileController;
use App\Http\Controllers\Api\Technician\TechnicianProgressController;
use App\Http\Controllers\Api\Technician\TechnicianReportController;
use App\Http\Controllers\Api\Technician\TechnicianSurveyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function () {
    Route::post('/register/customer', [AuthController::class, 'registerCustomer']);
    Route::post('/login/customer', [AuthController::class, 'loginCustomer']);
    Route::post('/login/technician', [AuthController::class, 'loginTechnician']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('customer')
    ->middleware(['auth:sanctum', 'role:customer'])
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index']);

        // profile
        Route::get('/profile', [CustomerProfileController::class, 'show']);
        Route::post('/profile', [CustomerProfileController::class, 'update']);

        // locations
        Route::get('/locations', [CustomerLocationController::class, 'index']);
        Route::post('/locations', [CustomerLocationController::class, 'store']);
        Route::get('/locations/{id}', [CustomerLocationController::class, 'show']);
        Route::put('/locations/{id}', [CustomerLocationController::class, 'update']);
        Route::delete('/locations/{id}', [CustomerLocationController::class, 'destroy']);

        // services & bookings
        Route::get('/services', [CustomerBookingController::class, 'services']);
        Route::post('/bookings', [CustomerBookingController::class, 'store']);
        Route::get('/bookings', [CustomerBookingController::class, 'index']);
        Route::get('/bookings/{id}', [CustomerBookingController::class, 'show']);
        Route::post('/bookings/{id}/cancel', [CustomerBookingController::class, 'cancel']);
        Route::get('/bookings/{id}/progresses', [CustomerBookingController::class, 'progresses']);
        Route::get('/bookings/{id}/report', [CustomerBookingController::class, 'report']);
        Route::get('/bookings/{id}/history', [CustomerBookingController::class, 'history']);

        // estimate approval
        Route::get('/bookings/{id}/estimate', [CustomerEstimateController::class, 'show']);
        Route::post('/bookings/{id}/estimate/approve', [CustomerEstimateController::class, 'approve']);
        Route::post('/bookings/{id}/estimate/reject', [CustomerEstimateController::class, 'reject']);
        Route::post('/bookings/{id}/estimate/postpone', [CustomerEstimateController::class, 'postpone']);

        // payment
        Route::get('/bookings/{id}/payments', [CustomerPaymentController::class, 'index']);
        Route::post('/bookings/{id}/payments', [CustomerPaymentController::class, 'store']);
        Route::get('/payments/{paymentId}', [CustomerPaymentController::class, 'show']);

        // review
        Route::post('/bookings/{id}/review', [CustomerReviewController::class, 'store']);
        Route::get('/bookings/{id}/review', [CustomerReviewController::class, 'show']);

        // notifications
        Route::get('/notifications', [CustomerNotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [CustomerNotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [CustomerNotificationController::class, 'markAllAsRead']);

        // chat
        Route::get('/chats', [CustomerChatController::class, 'rooms']);
        Route::post('/chats/room-by-booking/{bookingId}', [CustomerChatController::class, 'findOrCreateRoom']);
        Route::get('/chats/{roomId}/messages', [CustomerChatController::class, 'messages']);
        Route::post('/chats/{roomId}/messages', [CustomerChatController::class, 'sendMessage']);
    });

Route::prefix('technician')
    ->middleware(['auth:sanctum', 'role:technician'])
    ->group(function () {
        Route::get('/dashboard', [TechnicianDashboardController::class, 'index']);

        // profile
        Route::get('/profile', [TechnicianProfileController::class, 'show']);
        Route::post('/profile', [TechnicianProfileController::class, 'update']);

        // incoming survey bookings / active orders
        Route::get('/bookings/incoming', [TechnicianBookingController::class, 'incoming']);
        Route::get('/bookings/schedules', [TechnicianBookingController::class, 'schedules']);
        Route::get('/bookings/active', [TechnicianBookingController::class, 'active']);
        Route::get('/bookings/history', [TechnicianBookingController::class, 'history']);
        Route::get('/bookings/{id}', [TechnicianBookingController::class, 'show']);

        Route::post('/bookings/{id}/accept', [TechnicianBookingController::class, 'accept']);
        Route::post('/bookings/{id}/reject', [TechnicianBookingController::class, 'reject']);
        Route::post('/bookings/{id}/schedule-survey', [TechnicianBookingController::class, 'scheduleSurvey']);
        Route::post('/bookings/{id}/start-maintenance', [TechnicianBookingController::class, 'startMaintenance']);
        Route::post('/bookings/{id}/complete', [TechnicianBookingController::class, 'complete']);

        // survey
        Route::get('/bookings/{id}/survey', [TechnicianSurveyController::class, 'show']);
        Route::post('/bookings/{id}/survey', [TechnicianSurveyController::class, 'storeOrUpdate']);
        Route::post('/bookings/{id}/survey/submit', [TechnicianSurveyController::class, 'submit']);

        // price references
        Route::get('/price-references', [TechnicianPriceReferenceController::class, 'index']);
        Route::post('/price-references', [TechnicianPriceReferenceController::class, 'store']);
        Route::get('/price-references/{id}', [TechnicianPriceReferenceController::class, 'show']);
        Route::put('/price-references/{id}', [TechnicianPriceReferenceController::class, 'update']);
        Route::delete('/price-references/{id}', [TechnicianPriceReferenceController::class, 'destroy']);

        // progress
        Route::get('/bookings/{id}/progresses', [TechnicianProgressController::class, 'index']);
        Route::post('/bookings/{id}/progresses', [TechnicianProgressController::class, 'store']);

        // reports
        Route::get('/bookings/{id}/report', [TechnicianReportController::class, 'show']);
        Route::post('/bookings/{id}/report', [TechnicianReportController::class, 'storeOrUpdate']);

        // notifications
        Route::get('/notifications', [TechnicianNotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [TechnicianNotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [TechnicianNotificationController::class, 'markAllAsRead']);

        // chat
        Route::get('/chats', [TechnicianChatController::class, 'rooms']);
        Route::get('/chats/{roomId}/messages', [TechnicianChatController::class, 'messages']);
        Route::post('/chats/{roomId}/messages', [TechnicianChatController::class, 'sendMessage']);

        // income
        Route::get('/income/summary', [TechnicianIncomeController::class, 'summary']);
    });
