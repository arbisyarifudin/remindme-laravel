<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/email-test', function () {
  $userName = 'John Doe';
  $userEmail = 'pekerjarimot@gmail.com';
  $reminderTitle = 'Reminder Title';
  $reminderDescription = 'Reminder Description';
  $reminderEventDate = '02 January 2021';
  $reminderEventTime = '10:00 AM';

  // return view('emails.reminder', [
  //     'userName' => $userName,
  //     'title' => $reminderTitle,
  //     'description' => $reminderDescription,
  //     'eventDate' => $reminderEventDate,
  //     'eventTime' => $reminderEventTime,
  // ]);

  $result = Mail::send('emails.reminder', [
    'userName' => $userName,
    'title' => $reminderTitle,
    'description' => $reminderDescription,
    'eventDate' => $reminderEventDate,
    'eventTime' => $reminderEventTime,
  ], function ($message) use ($userEmail, $reminderTitle) {
    $message->to($userEmail)->subject('🔔 Reminder: ' . $reminderTitle);
  });

  dd($result);

  return 'Email sent successfully';
});

// route for vue js
Route::get('/{any}', function () {
  return view('app');
})->where('any', '.*');
