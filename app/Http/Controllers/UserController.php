<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public $notificationService;

    public function __construct(NotificationService $notificationService) {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create($request->safe()->except('phone'));

            $phoneWithCountryCode = '+595'.substr($request->phone, 1);

            $user->phone = $phoneWithCountryCode;
            $user->save();

            $this->saveAndSendValidationCode($user, $phoneWithCountryCode);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return response()->json([
                'success' => 'error', 
                'message' => 'Ocurrió un error durante el registro. Por favor intentar de nuevo en unos minutos'], 
            400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return $user;
    }
    
    /**
     * Validate user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validateUser(Request $request)
    {
        $user = User::where('code', $request->code)->get()->first();

        if (!$user) {
            return response()->json([
                'success' => 'error', 
                'message' => 'Código incorrecto.'], 
            400);
        }

        $user->active = true;
        $user->code = null;
        $user->save();

        return response()->json([
            'success' => 'success', 
            'message' => 'Teléfono validado correctamente.']
        );
    }

    /**
     * Resend validation code.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendValidationCode(Request $request, User $user)
    {
        if ($user->code_resend_attempts === 5) {
            return response()->json([
                'success' => 'error', 
                'message' => 'Alcanzaste el límite de reenvios de código. Número de referencia #'.$user->id
            ], 400);
        }

        $this->saveAndSendValidationCode($user, $user->phone);

        $user->code_resend_attempts = $user->code_resend_attempts + 1;
        $user->save();

        return response()->json([
            'success' => 'success', 
            'message' => 'Te hemos enviado otro código de validación'
        ]);
    }

    /**
     * Save and send user validation code.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function saveAndSendValidationCode(User $user, string $phone) {
        $code = rand(100000, 999999);
        $user->code = $code;
        $user->save();

        $this->notificationService->send($phone, 'Código de verificación: '.$code);
    }
}
