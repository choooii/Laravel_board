<?php
/*****************************************************
 * 프로젝트명   : laravel_board
 * 디렉토리     : Controllers
 * 파일명       : UserController.php
 * 이력         : v001 0530 AR.Choe new
 *****************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function login() {
        return view('/login');
    }

    public function registration() {
        return view('/registration');
    }

    public function loginpost(Request $req) {
        // 유효성 체크
        $req->validate([
            'email'    => 'required|email|between:3,100'
            ,'password' => 'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^*-]).{8,20}$/'
        ]);

        // 유저 정보 습득
        $user = User::where('email', $req->email)->first();
        if (!$user || !(Hash::check($req->password, $user->password))) {
            $errors[] = '아이디와 비밀번호를 확인해주세요.';
            return redirect()
                ->back()
                ->with('errors', collect($errors));
        }

        // 유저 인증 작업
        // ?? auth 지워도 로그인됨;
        Auth::login($user);
        if (Auth::check()) {
            session([$user->only('id')]); // 세션에 인증된 회원의 pk값을 등록
            return redirect()->intended(route('boards.index'));
        } else {
            $errors[] = '유저 인증작업 에러.';
            return redirect()
                ->back()
                ->with('errors', collect($errors));
        }
    }

    public function registrationpost(Request $req) {
        // 유효성 체크
        $req->validate([
                'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
                ,'email'    => 'required|email|between:3,100'
                ,'password' => 'same:passwordChk|regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^*-]).{8,20}$/'
            ]);

        $data['name'] = "박".mb_substr($req->name, 1);
        // $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password); // 암호화

        // insert
        $user = User::create($data);
        if (!$user) {
            $errors[] = '시스템 에러가 발생하여, 회원가입에 실패했습니다.';
            $errors[] = '잠시 후에 다시 회원가입을 시도해 주십시오.';
            return redirect()
                ->route('users.registration')
                ->with('errors', collect($errors));
        }

        // 회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입이 완료되었습니다. 로그인해주세요.');

    }
}
