<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvitationRequest;
use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Services\InvitationService;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Mail\GeneralMail;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    public function __construct(
        protected InvitationService $invitationService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => ['nullable', 'integer'],
            'search' => ['nullable', 'string', 'max:12'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
        return $this->invitationService->invitationList($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles =  Role::whereNot('slug', 'super-admin')->get();
        $companies = Tenant::query()->get();
        return view('invitation.create', compact('roles','companies'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvitationRequest $request)
    {
        $this->invitationService->storeInvitation($request->validated());
        return redirect()->route('dashboard');

    }

    /**
     * Display the specified resource.
     */
    public function show(Invitation $invitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invitation $invitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        //
    }

    public function invitationAccept(int $id)
    {
        $invitation = Invitation::with('invitedBy','tenant')->find($id);
        $invitation->accepted_at = date('Y-m-d H:i:s');
        $existingUser = DB::table('users')->where('email', $invitation->email)->exists();
        if($existingUser) {
            abort(403, 'Access denied. Your account is already exist.');
        }
        $invitation->save();
        $role = Role::where('slug', $invitation->role)->first();
        $password = 'password';
        $data = [
            'name' => $invitation->name,
            'email' => $invitation->email,
            'tenant_id' => $invitation->tenant_id,
            'role_id' => $role->id,
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => Hash::make($password),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('users')->insert($data);
        //send mail
        Mail::to($invitation->email)->send(
                new GeneralMail(
                    name:  $invitation->name,
                    url: route('login'),
                    message: "Your account has been created, login id : $invitation->email and  password :  $password",
                    mailSubject: "Welcome Onboard!."
                )
            );
        return redirect(route('login'));
    }
}
