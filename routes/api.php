 <?php
    use App\Http\Controllers\AuthController;
     use App\Http\Controllers\UserController; 
     use App\Http\Controllers\VideoController; 
     use App\Http\Controllers\AccessController; 
     use App\Http\Controllers\LicenseController;
      use App\Http\Controllers\AdminController; 
      use Illuminate\Support\Facades\Route;
      Route::post('/login', [AuthController::class, 'login' ]);
     Route::post('/register', [AuthController::class, 'register' ]); Route::middleware('auth:api')->group(function () {
     Route::get('/me', [AuthController::class, 'me']);
     Route::post('/videos', [VideoController::class, 'store']);
     Route::get('/access/{video}', [AccessController::class, 'getAccess']);
     Route::post('/license/{video}', [LicenseController::class, 'requestLicense']);
     });