<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\Invite;
use App\Models\LgpdTerm;
use App\Models\LgpdUserAcceptance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;



    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $messages = [
            'terms.required' => 'Para prosseguir leia e aceite os termos de uso e privacidade.',
            'invitation.required' => 'Informe o convite para prosseguir.',
        ];


        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'invitation' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ], $messages);

        $invite = Invite::where("code", $input["invitation"])->where("used", 0)->first();

        $validator->after(function ($validator) use ($input, $invite) {
            if(!is_object($invite)){
                $validator->errors()->add(
                    'invitation', 'O código do convite é inválido. Se você ainda não tem um convite, solicite análise no email indicado abaixo.'
                );
            }
        });

        $validator->validate();

        $invite->used = 1;
        $invite->save();


        return DB::transaction(function () use ($input) {

            $newUser = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            $lgpd = LgpdTerm::where("publishing_date", "<", date("Y-m-d H:m:s"))->orderBy("publishing_date", "desc")->first();
            LgpdUserAcceptance::create([
                "user_id" => $newUser->id,
                "lgpd_term_id" => $lgpd->id
            ]);
    


            return tap($newUser, function (User $user) {
                $this->createTeam($user);
            });
        });
    }

    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
